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
 * Allows you to request for a course
 *
 * @package     block_usp_mcrs
 * @category    string
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\output\notification;

require_once('../../config.php'); // Change depending on depth
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/blocks/usp_mcrs/lib.php');
require_once($CFG->dirroot.'/blocks/usp_mcrs/email_lib.php');
require_once($CFG->dirroot.'/blocks/usp_mcrs/requestcourse_form.php');
require_login();
$PAGE->requires->js(new moodle_url('/blocks/usp_mcrs/js/module.js'));

global $CFG, $USER, $DB;

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('courserequestform', 'block_usp_mcrs'), new moodle_url('/blocks/usp_mcrs/requestcourse.php'));
$PAGE->set_url('/blocks/usp_mcrs/requestcourse.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('requestcourse', 'block_usp_mcrs')); 
$PAGE->set_title(get_string('requestcourse', 'block_usp_mcrs'));

echo $OUTPUT->header();

//Instantiate the Form
$m_form = new requestcourse_form();

//Form processing and displaying is done here
if ($m_form->is_cancelled())
{
    //Handle form cancel operation, if cancel button is present on form
    echo '<script>window.location="/moodle37/my/index.php";</script>';
    die;
} 
else if ($from_form = $m_form->get_data())
{
    // Array to store the shells to be backed up
    $course_id = array();

    //In this case you process validated data. $mform->get_data() returns data posted in form.
    // single
    if($from_form->singlemultiple == 0)
    {
        $request = new stdClass();
        set_form_details_for_course_mode($from_form, $DB, $USER,$request,$strictness, $course_code, $course_name);
        if($from_form->newbackedup == 0)
        {
            $course_id_general = $from_form->courseidgeneral;
            $copy_from_general = $DB->get_field_select('course', 'shortname', 'id = '.$course_id_general, array(), $strictness= IGNORE_MISSING);
            $request->course_copyfrom = $copy_from_general;

            $course_id[0] = $course_id_general;
        }
        else
        {
            $moodle_format = $course_code.'_'.$from_form->courseyear.''.$from_form->coursesemester;
            $request->course_new = $moodle_format;

            create_new_shell($moodle_format, $course_code, $course_name);
        }
        $last_insert_id = $DB->insert_record('block_usp_mcrs_requests', $request);
    }
    // multiple
    else
    {
        if(isset($from_form->f2f))
        {
            $request_f2f = new stdClass();
            set_form_details_for_course_mode($from_form, $DB, $USER,$request_f2f,$strictness, $course_code, $course_name);

            if($from_form->newbackedup1 == 0)
            {
                $course_id_f2f = $from_form->courseidf2f;
                $copy_from_f2f = $DB->get_field_select('course', 'shortname', 'id = '.$course_id_f2f, array(), $strictness= IGNORE_MISSING);
                $request_f2f->course_copyfrom = $copy_from_f2f;

                $course_id[1] = $course_id_f2f;
            }
            else
            {
                $moodle_format_f2f = $course_code.'_'.$from_form->courseyear.''.$from_form->coursesemester.'_F';
                $request_f2f->course_new = $moodle_format_f2f;
                create_new_shell($moodle_format_f2f, $course_code, $course_name);

            }
            $last_insert_id1 = $DB->insert_record('block_usp_mcrs_requests', $request_f2f);
        }
        if(isset($from_form->online))
        {
            $request_online = new stdClass();
            set_form_details_for_course_mode($from_form, $DB, $USER,$request_online,$strictness, $course_code, $course_name);
            if($from_form->newbackedup2 == 0)
            {
                $course_id_online = $from_form->courseidonline;
                $copy_from_online = $DB->get_field_select('course', 'shortname', 'id = '.$course_id_online, array(), $strictness= IGNORE_MISSING);
                $request_online->course_copyfrom = $copy_from_online;

                $course_id[2] = $course_id_online;
            }
            else
            {
                $moodle_format_online = $course_code.'_'.$from_form->courseyear.''.$from_form->coursesemester.'_O';
                $request_online->course_new = $moodle_format_online;

                create_new_shell($moodle_format_online, $course_code, $course_name);
            }
            $last_insert_id2 = $DB->insert_record('block_usp_mcrs_requests', $request_online);
        }
        if(isset($from_form->print))
        {
            $request_print = new stdClass();
            set_form_details_for_course_mode($from_form, $DB, $USER,$request_print,$strictness, $course_code, $course_name);
            if($from_form->newbackedup3 == 0)
            {
                $course_id_print = $from_form->courseidprint;
                $copy_from_print = $DB->get_field_select('course', 'shortname', 'id = '.$course_id_print, array(), $strictness= IGNORE_MISSING);
                $request_print->course_copyfrom = $copy_from_print;

                $course_id[3] = $course_id_print;
            }
            else
            {
                $moodle_format_print = $course_code.'_'.$from_form->courseyear.''.$from_form->coursesemester.'_P';
                $request_print->course_new = $moodle_format_print;

                create_new_shell($moodle_format_print, $course_code, $course_name);
            }
            $last_insert_id3 = $DB->insert_record('block_usp_mcrs_requests', $request_print);
            
        }
        if(isset($from_form->blended))
        {
            $request_blended = new stdClass();
            set_form_details_for_course_mode($from_form, $DB, $USER,$request_blended,$strictness, $course_code, $course_name);
            if($from_form->newbackedup4 == 0)
            {
                $course_id_blended = $from_form->courseidblended;
                $copy_from_blended = $DB->get_field_select('course', 'shortname', 'id = '.$course_id_blended, array(), $strictness= IGNORE_MISSING);
                $request_blended->course_copyfrom = $copy_from_blended;

                $course_id[4] = $course_id_blended;
            }
            else
            {
                $moodle_format_blended = $course_code.'_'.$from_form->courseyear.''.$from_form->coursesemester.'_B';
                $request_blended->course_new = $moodle_format_blended;

                create_new_shell($moodle_format_blended, $course_code, $course_name);
            }
            $last_insert_id4 = $DB->insert_record('block_usp_mcrs_requests', $request_blended);
        }        
    }

    email_request_details_to_requester($USER);

    $_SESSION['courseid'] = $course_id;
    redirect('backup.php', 'Request Submitted Successfully!', null, notification::NOTIFY_SUCCESS);
} 
else 
{
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    //Set default data (if any)
    $m_form->set_data($from_form);
    //displays the form
    $m_form->display();
}

echo $OUTPUT->footer();