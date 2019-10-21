<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * @package    block_usp_mcrs
* @copyright   2019 IS314 Group 4 <you@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();


/**
 * @param $from_form
 * @param $DB
 * @param $USER
 * @param $request
 * @param $strictness
 * @param $course_code
 * @param $course_name
 */
function set_form_details_for_course_mode($from_form, $DB, $USER, &$request, &$strictness, &$course_code, &$course_name)
{
    $request->request_date = date('Y-m-d H:i:s');
    $codeId = $from_form->coursecode;
    $course_code = $DB->get_field_select('block_usp_mcrs_courses', 'course_code', 'id = ' . $codeId, array(), $strictness = IGNORE_MISSING);
    $request->course_code = $course_code;
    $nameId = $from_form->coursename;
    $course_name = $DB->get_field_select('block_usp_mcrs_courses', 'course_name', 'id = ' . $nameId, array(), $strictness = IGNORE_MISSING);
    $request->course_name = $course_name;
    $schoolId = $from_form->courseschool;
    $courseSchool = $DB->get_field_select('block_usp_mcrs_courses', 'school_name', 'id = ' . $schoolId, array(), $strictness = IGNORE_MISSING);
    $request->course_school = $courseSchool;
    $facultyId = $from_form->coursefaculty;
    $courseFaculty = $DB->get_field_select('block_usp_mcrs_courses', 'faculty_name', 'id = ' . $facultyId, array(), $strictness = IGNORE_MISSING);
    $request->course_faculty = $courseFaculty;
    $request->course_requester = $USER->email;
    $request->course_lecturer = $from_form->courselecturer;
}

/**
 * @param $moodle_format
 * @param $course_code
 * @param $course_name
 * @return stdClass $course_data
 */
function create_new_shell($moodle_format, $course_code, $course_name)
{
    $course_data = new stdClass();
    $course_data->category = 1;
    $course_data->idnumber = $moodle_format;
    $course_data->fullname = $course_code . ': ' . $course_name;
    $course_data->shortname = $moodle_format;
    $course_data->summary = '';
    $course_data->summaryformat = 0;
    $course_data->format = 'topics';
    $course_data->showgrades = 1;
    $course_data->visible = 1;

    create_course($course_data);

    return $course_data;
}

/**
 * Build the SQL query from the search params
 *
 * @return SQL
 */
function build_sql_from_search($query, $constraints) {
    $sql = "SELECT co.id, co.fullname, co.shortname, co.idnumber, cat.name
        AS category FROM {course} co, {course_categories} cat WHERE
        co.category = cat.id AND (";

    // Set up the SQL constraints.
    $constraintsqls = array();

    // Loop through the provided constraints and build the SQL contraints.
    foreach ($constraints as $c) {
        if (in_array($c->operator, array('LIKE', 'NOT LIKE'))) {
            $parts = array();

            foreach (explode('|', $c->search_terms) as $s) {
                $parts[] = "$c->criteria $c->operator '%{$s}%'";
            }

            $constraintsqls[] = '(' . implode(' OR ', $parts) . ')';
        } else {
            $instr = str_replace('|', "', '", $c->search_terms);

            $constraintsqls[] = "($c->criteria $c->operator ('$instr'))";
        }
    }

    // Return the appropriate SQL.
    return $sql . implode(" $query->type ", $constraintsqls) . ');';
}

/**
 * Delete courses based on supplied courseids
 *
 * @return bool
 */
function usp_mcrs_delete_course($courseid) {
    global $DB;
    // Get the course object based on the supplied courseid.
    $course = $DB->get_record('course', array('id' => $courseid));

    // Delete the course.
    if (delete_course($course, false)) {
        fix_course_sortorder();
        return true;
    } else {
        return false;
    }
}

/**
 * Generates the last bit of the backup .zip's filename based on the
 * pattern and roles that the admin chose in config.
 *
 * @return $suffix
 */
function generate_suffix($courseid) {
    $suffix = '';

    // Grab the allowed suffixes.
    $field = get_config('block_usp_mcrs', 'suffix');

    // Grab the administratively selected roles.
    $roleids = explode(',', get_config('block_usp_mcrs', 'roles'));

    // Grab the course context.
    $context = context_course::instance($courseid);

    // When NOT using fullname (which we might want to avoid anyway).
    if ($field != 'fullname') {
        // Loop through all the administratively selected roles.
        foreach ($roleids as $r) {
            // If the role has any users in the course, return them.
            if ($users = get_role_users($r, $context, false)) {
                // Loop through the users and grab the appropriate suffix.
                foreach ($users as $k => $v) {
                    $suffix .= '_' . $v->$field;
                }
            }
        }
    } else {
        // Loop through all the administratively selected roles.
        foreach ($roleids as $r) {
            // If the role has any users in the course, return them.
            if ($users = get_role_users($r, $context, false)) {
                // Loop through the users and grab the appropriate suffix.
                foreach ($users as $k => $v) {
                    $suffix .= '_' . $v->firstname . $v->lastname;
                }
            }
        }
    }
    return $suffix;
}

/**
 * Instantiate the moodle backup subsystem
 * and backup the course.
 *
 * @return true
 */
function usp_mcrs_backup_course($course) {
    global $CFG;

    // Required files for the backups.
    require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
    require_once($CFG->dirroot . '/backup/controller/backup_controller.class.php');

    // Setup the backup controller.
    $bc = new backup_controller(backup::TYPE_1COURSE, $course->id,
        backup::FORMAT_MOODLE, backup::INTERACTIVE_NO, backup::MODE_AUTOMATED, 2);
    $outcome = $bc->execute_plan();
    $results = $bc->get_results();
    $file = $results['backup_destination'];
    $suffix = generate_suffix($course->id);
    $matchers = array('/\s/', '/\//');

    // Ensure the shortname is safe.
    $safeshort = preg_replace($matchers, '-', $course->shortname);

    // Name the file.
    $usp_mcrsfile = "usp_mcrs-{$safeshort}{$suffix}.zip";

    // Build the path.
    $usp_mcrspath = get_config('block_usp_mcrs', 'path');

    // Copy the file to the destination.
    $file->copy_content_to($CFG->dataroot . $usp_mcrspath . $usp_mcrsfile);

    // Kill the backup controller.
    $bc->destroy();
    unset($bc);

    return true;
}

/**
 * Email the admins
 *
 */
function usp_mcrs_email_admins($errors) {
    $dellink = new moodle_url('/blocks/usp_mcrs/delete.php');

    $subject = get_string('email_subject', 'block_usp_mcrs');
    $from = get_string('email_from', 'block_usp_mcrs');
    $messagetext = $errors . "\n\n" . get_string('email_body', 'block_usp_mcrs') . $dellink;

    foreach (get_admins() as $admin) {
        email_to_user($admin, $from, $subject, $messagetext);
    }
}
