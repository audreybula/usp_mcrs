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
 * Course Request Form
 *
 * @package     block_usp_mcrs
 * @category    string
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');

require_once("$CFG->libdir/formslib.php");

class requestcourse_form extends moodleform
{
    function definition() 
    {   
        /*
                TODO: 
                1. Disable other fields if Course Code not selected
                2. Remove moodle from backup shells
                3. Course modes will depend on Single/Multiple shell copies
        */

        global $CFG, $currentsess, $DB, $USER, $currentrecord; 
    
        $mform =& $this->_form; // Don't forget the underscore! 

        // Form header
        $mform->addElement('header', 'mainheader','<span style="font-size:22px">'.get_string('courserequestform','block_usp_mcrs'). '</span>');

        // Course Code field. 
        $coursecodearray = array();
        $coursecodearray[0] = get_string('choosecoursecode', 'block_usp_mcrs');
        $allcoursecodes = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, course_code');
        foreach ($allcoursecodes as $id => $coursecodeobject) {
            $coursecodearray[$id] = $coursecodeobject->course_code;
        }
        $coursecode = $mform->addElement('select', 'coursecode', get_string('coursecode', 'block_usp_mcrs'), $coursecodearray);
        $mform->addRule('coursecode', get_string('required'), 'required', null, 'client');
        $mform->setType('coursecode', PARAM_RAW); 	 

        // Course Name field
        $coursenamearray = array();
        $coursenamearray[0] = get_string('choosecoursename', 'block_usp_mcrs');
        $allcoursenames = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, course_name');
        foreach ($allcoursenames as $id => $coursenameobject) {
            $coursenamearray[$id] = $coursenameobject->course_name;
        }
        $mform->addElement('select', 'coursename', get_string('coursename', 'block_usp_mcrs'), $coursenamearray);
        $mform->addRule('coursename', get_string('required'), 'required', null, 'client');
        $mform->setType('coursename', PARAM_RAW);

        // Course Requester field
        $mform->addElement('text', 'courserequester', get_string('courserequester', 'block_usp_mcrs'));
        // Set default requester to username of currently logged in user
        $mform->setDefault('courserequester', $USER->username); 
        $mform->addRule('courserequester', get_string('required'), 'required', null, 'client');
        $mform->setType('courserequester', PARAM_TEXT);

        // Course Lecturer field
        $mform->addElement('text', 'courselecturer', get_string('courselecturer', 'block_usp_mcrs'));
        // Set default lecturer to username of currently logged in user
        $mform->setDefault('courselecturer', $USER->username); 
        $mform->addRule('courselecturer', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('courselecturer', 'courselecturer', 'block_usp_mcrs');  
        $mform->setType('courselecturer', PARAM_TEXT);

        // Course Faculty field
        $coursefacultyarray = array();
        $coursefacultyarray[0] = get_string('choosecoursefaculty', 'block_usp_mcrs');
        $allcoursefaculties = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, faculty_name');
        foreach ($allcoursefaculties as $id => $coursefacultyobject) {
            $coursefacultyarray[$id] = $coursefacultyobject->faculty_name;
        }
        $mform->addElement('select', 'coursefaculty', get_string('coursefaculty', 'block_usp_mcrs'), $coursefacultyarray);
        $mform->setType('coursefaculty', PARAM_RAW);

        // Course School field
        $courseschoolarray = array();
        $courseschoolarray[0] = get_string('choosecourseschool', 'block_usp_mcrs');
        $allcourseschools = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, school_name');
        foreach ($allcourseschools as $id => $courseschoolobject) {
            $courseschoolarray[$id] = $courseschoolobject->school_name;
        }
        $mform->addElement('select', 'courseschool', get_string('courseschool', 'block_usp_mcrs'), $courseschoolarray);
        $mform->setType('courseschool', PARAM_RAW);
        
        // Number of shells dropdown 
        $options = array('0' => 'Select number of shells...', '1' => 'Single', '2' => 'Multiple');
        $select = $mform->addElement('select', 'courseshellnumber', get_string('courseshellnumber', 'block_usp_mcrs'), $options);
        $select->setSelected('0');

        // Copyfrom dropdown
        $coursshellearray = array();
        $courseshellarray[0] = get_string('choosecourseshell', 'block_usp_mcrs');
        $allcourseshells = $DB->get_records_select('course', 'id > 0', array(), 'id', 'id, shortname');
        foreach ($allcourseshells as $id => $courseshellobject) {
            $courseshellarray[$id] = $courseshellobject->shortname;
        }
        $mform->addElement('select', 'courseid', get_string('coursetocopyfrom', 'block_usp_mcrs'), $courseshellarray);

        // Course Mode checkboxes 
        $mform->addElement('checkbox', 'f2f', 'Course Mode', get_string('f2f', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'f2f\', this.checked)"');
        $mform->disabledIf('f2f','courseshellnumber','2');
        $mform->addElement('checkbox', 'online', '', get_string('online', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'online\', this.checked)"');
        $mform->disabledIf('online','courseshellnumber','notselected');
        $mform->addElement('checkbox', 'print', '', get_string('print', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'print\', this.checked)"');
        $mform->disabledIf('print','courseshellnumber','notselected');
        $mform->addElement('checkbox', 'blended', '', get_string('blended', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'blended\', this.checked)"'); 
        $mform->disabledIf('blended','courseshellnumber','notselected');   
        
        // Additional Information
        $mform->addElement('editor', 'additionalinfo', get_string('additionalinfo', 'block_usp_mcrs'));
        $mform->setType('additionalinfo', PARAM_RAW);
        
        // Submit button with Cancel button
        $this->add_action_buttons(true, get_string('submitbutton', 'block_usp_mcrs'));
    } 
}