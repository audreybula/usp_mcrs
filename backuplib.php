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

function block_usp_mcrs_getbackupdir() {
    global $CFG;
    $backupdir = $CFG->tempdir . '/backup';

    if (!check_dir_exists($backupdir) && !mkdir($backupdir)) {
        return null;
    }
    return $backupdir;
}

/**
 * Get backup path
 * @param string $filename
 * @return string
 */
function block_usp_mcrs_getbackuppath($filename) {
    global $CFG;
    $backupdir = block_usp_mcrs_getbackupdir();
    return $backupdir ? "{$backupdir}/{$filename}" : "{$CFG->tempdir}/backup_{$filename}";
}

/**
 * Instantiate the moodle backup subsystem
 * and backup the course.
 *
 * @return true
 */
function usp_mcrs_backup_course($course) {
    global $CFG, $DB, $USER;

    // Required files for the backups.
    require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
    require_once($CFG->dirroot . '/backup/controller/backup_controller.class.php');
    require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

    $systemcontext = context_system::instance();
    $usercontext = context_user::instance($USER->id);

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

    mtrace('***********************************');
    mtrace('*****BEGIN RESTORE FOR USP_MCRS*****');
    mtrace('***********************************');

    // Get name of backup file
    $mbzfilename = $usp_mcrsfile;

    // Copy to temporary directory
    $archivename = restore_controller::get_tempdir_name(0, $USER->id);
    $archivepath = block_usp_mcrs_getbackuppath($archivename);
    if (!$file->copy_content_to($archivepath)) {
        throw new Exception(get_string('error_cannotsaveuploadfile', 'block_usp_mcrs'));
    }

    // Extract file
    $extractedname = restore_controller::get_tempdir_name($systemcontext->id, $USER->id);
    $extractedpath = block_usp_mcrs_getbackuppath($extractedname);
    $fb = get_file_packer('application/vnd.moodle.backup');
    if (!$fb->extract_to_pathname($archivepath, $extractedpath, null)) {
        throw new Exception(get_string('error_cannotextractfile', 'block_usp_mcrs'));
    }

    // Miscellaneous
    $category = 1;

    // Verify category
    if (!$DB->get_record('course_categories', ['id' => $category])) {
        throw new Exception(get_string('error_categorynotfound', 'block_usp_mcrs'));
    }

    // Create a new course
    list($fullname, $shortname) = restore_dbops::calculate_course_names(0, get_string('restoringcourse', 'backup'), get_string('restoringcourseshortname', 'backup'));
    $courseid = restore_dbops::create_new_course($fullname, $shortname, $category);

    $course = $DB->get_record('course', ['id' => $courseid]);

    raise_memory_limit(MEMORY_EXTRA);
    $coursecontext = context_course::instance($courseid);

    // Setup restore controller
    $rc = new restore_controller($extractedname, $courseid, backup::INTERACTIVE_NO, backup::MODE_GENERAL, $USER->id, backup::TARGET_NEW_COURSE);
    $rc->set_status(backup::STATUS_AWAITING);
    $rc->get_plan()->execute();

    $blocks = backup_general_helper::get_blocks_from_path($extractedpath . '/course');

    // Kill restore controller
    $rc->destroy();

    fulldelete($extractedpath);
    fulldelete($archivepath);

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
