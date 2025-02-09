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
 * Course Request
 *
 * @package     block_usp_mcrs
 * @category    string
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");
require_once('requestlib.php');
require_once('backuplib.php');
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

// Instantiate the Form
$mform = new requestcourse_form(); 

// Form processing and displaying is done here
if ($mform->is_cancelled()) 
{
    // Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot);
} 
else if ($fromform = $mform->get_data()) 
{    
    // Intantiate a request
    $request = new stdClass();

    // In this case you process validated data. $mform->get_data() returns data posted in form. 
    if($fromform->singlemultiple == 0)
    {            
        $request = getFormData($fromform, $DB, $USER, $request);
        $request->course_copytoshortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester;
        $request->course_copytofullname = $request->course_code.': '.$request->course_name;
        if($fromform->newbackedup == 0)
        {            
            if(!$fromform->additionalinfo)
            {
                $request->course_copyfromid = $fromform->courseidgeneral;
                $request->request_status = 'PENDINGBACKUP';
            }
        }
        else
        {
            $request->request_status = 'PENDINGNEW';
            if(!$fromform->additionalinfo)
            {
                createCourse($request, $fromform); 
                check_enrol($request->course_copytoshortname, $USER->id, 3);     
            }  
        }
        $DB->insert_record('block_usp_mcrs_requests', $request);
    }
    else
    {
        if(isset($fromform->f2f))
        {
            $request = getFormData($fromform, $DB, $USER, $request);
            $request->course_copytoshortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_F';
            $request->course_copytofullname = $request->course_code.': '.$request->course_name.' (Face To Face)';
            if($fromform->newbackedup1 == 0)
            {
                if(!$fromform->additionalinfo)
                {
                    $request->course_copyfromid = $fromform->courseidf2f;
                    $request->request_status = 'PENDINGBACKUP';
                }
            }
            else
            {  
                $request->request_status = 'PENDINGNEW';              
                if(!$fromform->additionalinfo)
                {
                    createCourse($request, $fromform);  
                    check_enrol($request->course_copytoshortname, $USER->id, 3);     
                }  
            }
            $DB->insert_record('block_usp_mcrs_requests', $request);
        }
        if(isset($fromform->online))
        {
            $request = getFormData($fromform, $DB, $USER, $request);
            $request->course_copytoshortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_O';
            $request->course_copytofullname = $request->course_code.': '.$request->course_name.' (Online)';
            if($fromform->newbackedup2 == 0)
            {               
                if(!$fromform->additionalinfo)
                {
                    $request->course_copyfromid = $fromform->courseidonline;
                    $request->request_status = 'PENDINGBACKUP';
                }
            }
            else
            { 
                $request->request_status = 'PENDINGNEW';               
                if(!$fromform->additionalinfo)
                {
                    createCourse($request, $fromform);  
                    check_enrol($request->course_copytoshortname, $USER->id, 3);     
                }  
            }
            $DB->insert_record('block_usp_mcrs_requests', $request);
        }
        if(isset($fromform->print))
        {
            $request = getFormData($fromform, $DB, $USER, $request);
            $request->course_copytoshortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_P';
            $request->course_copytofullname = $request->course_code.': '.$request->course_name.' (Print)';
            if($fromform->newbackedup3 == 0)
            {                
                if(!$fromform->additionalinfo)
                {
                    $request->course_copyfromid = $fromform->courseidprint;
                    $request->request_status = 'PENDINGBACKUP';
                }
            }
            else
            {
                $request->request_status = 'PENDINGNEW';
                if(!$fromform->additionalinfo)
                {
                    createCourse($request, $fromform); 
                    check_enrol($request->course_copytoshortname, $USER->id, 3);      
                }  
            }
            $DB->insert_record('block_usp_mcrs_requests', $request);
        }
        if(isset($fromform->blended))
        {
            $request = getFormData($fromform, $DB, $USER, $request);
            $request->course_copytoshortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_B';
            $request->course_copytofullname = $request->course_code.': '.$request->course_name.' (Blended)';
            if($fromform->newbackedup4 == 0)
            {               
                if(!$fromform->additionalinfo)
                {
                    $request->course_copyfromid = $fromform->courseidblended;
                    $request->request_status = 'PENDINGBACKUP';
                }
            }
            else
            {
                $request->request_status = 'PENDINGNEW';
                if(!$fromform->additionalinfo)
                {
                    createCourse($request, $fromform);   
                    check_enrol($request->course_copytoshortname, $USER->id, 3);    
                }  
            }
            $DB->insert_record('block_usp_mcrs_requests', $request);
        }        
    } 
    redirect($CFG->wwwroot, 'Request Submitted Successfully!', null, \core\output\notification::NOTIFY_SUCCESS);
} 
else 
{
    // Set default data (if any)
    $mform->set_data($fromform);
    // Displays the form
    $mform->display();
}

echo $OUTPUT->footer();