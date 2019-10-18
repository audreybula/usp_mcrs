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

require_once('../../config.php'); // Change depending on depth
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/blocks/usp_mcrs/requestcourse_form.php');
require_login();
$PAGE->requires->js(new moodle_url('/blocks/usp_mcrs/javascript/module.js'));

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
$mform = new requestcourse_form(); 

//Form processing and displaying is done here
if ($mform->is_cancelled()) 
{
    //Handle form cancel operation, if cancel button is present on form
    echo '<script>window.location="/moodle37/my/index.php";</script>';
    die;
} 
else if ($fromform = $mform->get_data()) 
{
    //In this case you process validated data. $mform->get_data() returns data posted in form. 
    if($fromform->singlemultiple == 0)
    {
        $request = new stdClass();
        $request->request_date = date('Y-m-d H:i:s');
        $codeid = $fromform->coursecode;
        $coursecode = $DB->get_field_select('block_usp_mcrs_courses', 'course_code', 'id = '.$codeid, array(), $strictness=IGNORE_MISSING);
        $request->course_code = $coursecode;    
        $nameid = $fromform->coursename;
        $coursename = $DB->get_field_select('block_usp_mcrs_courses', 'course_name', 'id = '.$nameid, array(), $strictness=IGNORE_MISSING);
        $request->course_name = $coursename;
        $schoolid = $fromform->courseschool;
        $courseschool = $DB->get_field_select('block_usp_mcrs_courses', 'school_name', 'id = '.$schoolid, array(), $strictness=IGNORE_MISSING);
        $request->course_school = $courseschool;
        $facultyid = $fromform->coursefaculty;
        $coursefaculty = $DB->get_field_select('block_usp_mcrs_courses', 'faculty_name', 'id = '.$facultyid, array(), $strictness=IGNORE_MISSING);
        $request->course_faculty = $coursefaculty;
        $request->course_requester = $USER->email;
        $request->course_lecturer = $fromform->courselecturer;
        if($fromform->newbackedup == 0)
        {
            $courseidgeneral = $fromform->courseidgeneral;
            $copyfromgeneral = $DB->get_field_select('course', 'shortname', 'id = '.$courseidgeneral, array(), $strictness=IGNORE_MISSING);
            $request->course_copyfrom = $copyfromgeneral;
        }
        else
        {
            $moodleformat = $coursecode.'_'.$fromform->courseyear.''.$fromform->coursesemester;
            $request->course_new = $moodleformat;
        }
        $lastinsertid = $DB->insert_record('block_usp_mcrs_requests', $request);
    }
    else
    {
        if(isset($fromform->f2f))
        {
            $requestf2f = new stdClass();
            $requestf2f->request_date = date('Y-m-d H:i:s');
            $codeid = $fromform->coursecode;
            $coursecode = $DB->get_field_select('block_usp_mcrs_courses', 'course_code', 'id = '.$codeid, array(), $strictness=IGNORE_MISSING);
            $requestf2f->course_code = $coursecode;    
            $nameid = $fromform->coursename;
            $coursename = $DB->get_field_select('block_usp_mcrs_courses', 'course_name', 'id = '.$nameid, array(), $strictness=IGNORE_MISSING);
            $requestf2f->course_name = $coursename;
            $schoolid = $fromform->courseschool;
            $courseschool = $DB->get_field_select('block_usp_mcrs_courses', 'school_name', 'id = '.$schoolid, array(), $strictness=IGNORE_MISSING);
            $requestf2f->course_school = $courseschool;
            $facultyid = $fromform->coursefaculty;
            $coursefaculty = $DB->get_field_select('block_usp_mcrs_courses', 'faculty_name', 'id = '.$facultyid, array(), $strictness=IGNORE_MISSING);
            $requestf2f->course_faculty = $coursefaculty;
            $requestf2f->course_requester = $USER->email;
            $requestf2f->course_lecturer = $fromform->courselecturer;
            if($fromform->newbackedup1 == 0)
            {
                $courseidf2f = $fromform->courseidf2f;
                $copyfromf2f = $DB->get_field_select('course', 'shortname', 'id = '.$courseidf2f, array(), $strictness=IGNORE_MISSING);
                $requestf2f->course_copyfrom = $copyfromf2f;
            }
            else
            {
                $moodleformatf2f = $coursecode.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_F';
                $requestf2f->course_new = $moodleformatf2f;
            }
            $lastinsertid1 = $DB->insert_record('block_usp_mcrs_requests', $requestf2f);
        }
        if(isset($fromform->online))
        {
            $requestonline = new stdClass();
            $requestonline->request_date = date('Y-m-d H:i:s');
            $codeid = $fromform->coursecode;
            $coursecode = $DB->get_field_select('block_usp_mcrs_courses', 'course_code', 'id = '.$codeid, array(), $strictness=IGNORE_MISSING);
            $requestonline->course_code = $coursecode;    
            $nameid = $fromform->coursename;
            $coursename = $DB->get_field_select('block_usp_mcrs_courses', 'course_name', 'id = '.$nameid, array(), $strictness=IGNORE_MISSING);
            $requestonline->course_name = $coursename;
            $schoolid = $fromform->courseschool;
            $courseschool = $DB->get_field_select('block_usp_mcrs_courses', 'school_name', 'id = '.$schoolid, array(), $strictness=IGNORE_MISSING);
            $requestonline->course_school = $courseschool;
            $facultyid = $fromform->coursefaculty;
            $coursefaculty = $DB->get_field_select('block_usp_mcrs_courses', 'faculty_name', 'id = '.$facultyid, array(), $strictness=IGNORE_MISSING);
            $requestonline->course_faculty = $coursefaculty;
            $requestonline->course_requester = $USER->email;
            $requestonline->course_lecturer = $fromform->courselecturer;
            if($fromform->newbackedup2 == 0)
            {
                $courseidonline = $fromform->courseidonline;
                $copyfromonline = $DB->get_field_select('course', 'shortname', 'id = '.$courseidonline, array(), $strictness=IGNORE_MISSING);
                $requestonline->course_copyfrom = $copyfromonline;
            }
            else
            {
                $moodleformatonline = $coursecode.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_O';
                $requestonline->course_new = $moodleformatonline;
            }
            $lastinsertid2 = $DB->insert_record('block_usp_mcrs_requests', $requestonline);
        }
        if(isset($fromform->print))
        {
            $requestprint = new stdClass();
            $requestprint->request_date = date('Y-m-d H:i:s');
            $codeid = $fromform->coursecode;
            $coursecode = $DB->get_field_select('block_usp_mcrs_courses', 'course_code', 'id = '.$codeid, array(), $strictness=IGNORE_MISSING);
            $requestprint->course_code = $coursecode;    
            $nameid = $fromform->coursename;
            $coursename = $DB->get_field_select('block_usp_mcrs_courses', 'course_name', 'id = '.$nameid, array(), $strictness=IGNORE_MISSING);
            $requestprint->course_name = $coursename;
            $schoolid = $fromform->courseschool;
            $courseschool = $DB->get_field_select('block_usp_mcrs_courses', 'school_name', 'id = '.$schoolid, array(), $strictness=IGNORE_MISSING);
            $requestprint->course_school = $courseschool;
            $facultyid = $fromform->coursefaculty;
            $coursefaculty = $DB->get_field_select('block_usp_mcrs_courses', 'faculty_name', 'id = '.$facultyid, array(), $strictness=IGNORE_MISSING);
            $requestprint->course_faculty = $coursefaculty;
            $requestprint->course_requester = $USER->email;
            $requestprint->course_lecturer = $fromform->courselecturer;
            if($fromform->newbackedup3 == 0)
            {
                $courseidprint = $fromform->courseidprint;
                $copyfromprint = $DB->get_field_select('course', 'shortname', 'id = '.$courseidprint, array(), $strictness=IGNORE_MISSING);
                $requestprint->course_copyfrom = $copyfromprint;
            }
            else
            {
                $moodleformatprint = $coursecode.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_P';
                $requestprint->course_new = $moodleformatprint;
            }
            $lastinsertid3 = $DB->insert_record('block_usp_mcrs_requests', $requestprint);
        }
        if(isset($fromform->blended))
        {
            $requestblended = new stdClass();
            $requestblended->request_date = date('Y-m-d H:i:s');
            $codeid = $fromform->coursecode;
            $coursecode = $DB->get_field_select('block_usp_mcrs_courses', 'course_code', 'id = '.$codeid, array(), $strictness=IGNORE_MISSING);
            $requestblended->course_code = $coursecode;    
            $nameid = $fromform->coursename;
            $coursename = $DB->get_field_select('block_usp_mcrs_courses', 'course_name', 'id = '.$nameid, array(), $strictness=IGNORE_MISSING);
            $requestblended->course_name = $coursename;
            $schoolid = $fromform->courseschool;
            $courseschool = $DB->get_field_select('block_usp_mcrs_courses', 'school_name', 'id = '.$schoolid, array(), $strictness=IGNORE_MISSING);
            $requestblended->course_school = $courseschool;
            $facultyid = $fromform->coursefaculty;
            $coursefaculty = $DB->get_field_select('block_usp_mcrs_courses', 'faculty_name', 'id = '.$facultyid, array(), $strictness=IGNORE_MISSING);
            $requestblended->course_faculty = $coursefaculty;
            $requestblended->course_requester = $USER->email;
            $requestblended->course_lecturer = $fromform->courselecturer;
            if($fromform->newbackedup4 == 0)
            {
                $courseidblended = $fromform->courseidblended;
                $copyfromblended = $DB->get_field_select('course', 'shortname', 'id = '.$courseidblended, array(), $strictness=IGNORE_MISSING);
                $requestblended->course_copyfrom = $copyfromblended;
            }
            else
            {
                $moodleformatblended = $coursecode.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_B';
                $requestblended->course_new = $moodleformatblended;
            }
            $lastinsertid4 = $DB->insert_record('block_usp_mcrs_requests', $requestblended);
        }        
    } 
    // Testing for new course shell
    $data = new stdClass();
    $data->category = 1;
    $data->idnumber = $moodleformat;
    $data->fullname = $coursecode.': '.$coursename;
    $data->shortname = $moodleformat;
    $data->summary = '';
    $data->summaryformat = 0;
    $data->format = 'topics';
    $data->showgrades = 1;
    $data->visible = 1;
    $h = create_course($data);
    redirect('/moodle37/my/index.php', 'Request Submitted Successfully!', null, \core\output\notification::NOTIFY_SUCCESS);
} 
else 
{
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    //Set default data (if any)
    $mform->set_data($fromform);
    //displays the form
    $mform->display();
}

echo $OUTPUT->footer();