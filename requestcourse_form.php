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
        global $CFG, $currentsess, $DB, $USER, $currentrecord; 
    
        $mform =& $this->_form; // Don't forget the underscore! 

        // Form header
        $mform->addElement('header', 'mainheader','<span style="font-size:22px">'.get_string('courserequestform','block_usp_mcrs'). '</span>');

        // Course Code field
        $allcoursecodes = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, course_code');                                                           
        $coursecodearray = array(); 
        $coursecodearray[0] = get_string('choosecoursecode', 'block_usp_mcrs');                                                                                                      
        foreach ($allcoursecodes as $id => $coursecodeobject) {                                                                          
            $coursecodearray[$id] = $coursecodeobject->course_code;                                                                  
        }                                                                                                                        
        $options = array(                                                                                                           
            'multiple' => false,             
            'noselectionstring' => get_string('allareas', 'search'), 
            'tags' => true,                                                               
        );    
        $mform->addElement('autocomplete', 'coursecode', get_string('coursecode', 'block_usp_mcrs'), $coursecodearray, $options); 	

        // Course Name field
        $allcoursenames = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, course_name');                                                           
        $coursenamearray = array(); 
        $coursenamearray[0] = get_string('choosecoursename', 'block_usp_mcrs');                                                                                                      
        foreach ($allcoursenames as $id => $coursenameobject) {                                                                          
            $coursenamearray[$id] = $coursenameobject->course_name;                                                                  
        }                                                                                                                        
        $options = array(                                                                                                           
            'multiple' => false,                         
            'noselectionstring' => get_string('allareas', 'search'),  
            'tags' => true,                                                               
        );    
        $mform->addElement('autocomplete', 'coursename', get_string('coursename', 'block_usp_mcrs'), $coursenamearray, $options);  

        // Course Faculty field
        $allcoursefaculty = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, faculty_name');                                                           
        $coursefacultyarray = array(); 
        $coursefacultyarray[0] = get_string('coursefaculty', 'block_usp_mcrs');                                                                                                      
        foreach ($allcoursefaculty as $id => $coursefacultyobject) {                                                                          
            $coursefacultyarray[$id] = $coursefacultyobject->faculty_name;                                                                  
        }                                                                                                                        
        $options = array(                                                                                                           
            'multiple' => false,                         
            'noselectionstring' => get_string('allareas', 'search'),  
            'tags' => true,                                                               
        );    
        $mform->addElement('select', 'coursefaculty', get_string('coursefaculty', 'block_usp_mcrs'), $coursefacultyarray, $options);

        // Course School field
        $allcourseschool = $DB->get_records_select('block_usp_mcrs_courses', 'id > 0', array(), 'id', 'id, school_name');                                                           
        $courseschoolarray = array(); 
        $courseschoolarray[0] = get_string('courseschool', 'block_usp_mcrs');                                                                                                      
        foreach ($allcourseschool as $id => $courseschoolobject) {                                                                          
            $courseschoolarray[$id] = $courseschoolobject->school_name;                                                                  
        }                                                                                                                        
        $options = array(                                                                                                           
            'multiple' => false,                         
            'noselectionstring' => get_string('allareas', 'search'),  
            'tags' => true,                                                               
        );    
        $mform->addElement('select', 'courseschool', get_string('courseschool', 'block_usp_mcrs'), $courseschoolarray, $options);
        
        // Course Lecturer field
        $mform->addElement('text', 'courselecturer', get_string('courselecturer', 'block_usp_mcrs'));
        // Set default lecturer to username of currently logged in user
        $mform->setDefault('courselecturer', $USER->username); 
        $mform->addRule('courselecturer', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('courselecturer', 'courselecturer', 'block_usp_mcrs');  
        $mform->setType('courselecturer', PARAM_TEXT);

        // Single or Multiple shells
        $radioarray1 = array();
        $radioarray1[] = $mform->createElement('radio', 'singlemultiple', '', get_string('courseshellsingle', 'block_usp_mcrs'), 0);
        $radioarray1[] = $mform->createElement('radio', 'singlemultiple', '', get_string('courseshellmultiple', 'block_usp_mcrs'), 1);
        $mform->addGroup($radioarray1, 'radioar1', 'Single or Multiple Shells', array(' '), false); 

        // New or Backed up shell
        $radioarray2 = array();
        $radioarray2[] = $mform->createElement('radio', 'newbackedup', '', get_string('courseshellnew', 'block_usp_mcrs'), 1);
        $radioarray2[] = $mform->createElement('radio', 'newbackedup', '', get_string('courseshellexisting', 'block_usp_mcrs'), 0);
        $mform->addGroup($radioarray2, 'radioar2', 'New or Existing Shell', array(' '), false);
        $mform->hideIf('radioar2','singlemultiple','eq', '1');

        // Copyfrom dropdown General
        $coursshellearray = array();
        $courseshellarray[0] = get_string('choosecourseshellgeneral', 'block_usp_mcrs');
        $allcourseshells = $DB->get_records_select('course', 'id > 0', array(), 'id', 'id, shortname');
        foreach ($allcourseshells as $id => $courseshellobject) {
            $courseshellarray[$id] = $courseshellobject->shortname;
        }
        $mform->addElement('select', 'courseidgeneral', 'Course Shell To Be Copied', $courseshellarray);
        $mform->hideIf('courseidgeneral','newbackedup','eq', '1');
        $mform->hideIf('courseidgeneral','singlemultiple','eq', '1');
        $mform->hideIf('courseidgeneral','radioar2','eq', '1');

        // Course Year General
        $mform->addElement('text', 'courseyeargeneral', get_string('courseyeargeneral', 'block_usp_mcrs'));
        $mform->setType('courseyeargeneral', PARAM_TEXT);
        $mform->hideIf('courseyeargeneral','newbackedup','eq', '0');
        $mform->hideIf('courseyeargeneral','singlemultiple','eq', '1');
        $mform->hideIf('courseyeargeneral','radioar2','eq', '0');

        // Course Semester General
        $mform->addElement('text', 'coursesemestergeneral', get_string('coursesemestergeneral', 'block_usp_mcrs')); 
        $mform->setType('coursesemestergeneral', PARAM_TEXT);
        $mform->hideIf('coursesemestergeneral','newbackedup','eq', '0');
        $mform->hideIf('coursesemestergeneral','singlemultiple','eq', '1');
        $mform->hideIf('coursesemestergeneral','radioar2','eq', '0');

        // Course Mode F2F checkbox
        $mform->addElement('checkbox', 'f2f', 'Course Mode', get_string('f2f', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'f2f\', this.checked)"');
        $mform->hideIf('f2f','singlemultiple','eq', '0');

        // New or Backed up F2F shell
        $radioarray3 = array();
        $radioarray3[] = $mform->createElement('radio', 'newbackedup1', '', get_string('courseshellnew', 'block_usp_mcrs'), 1);
        $radioarray3[] = $mform->createElement('radio', 'newbackedup1', '', get_string('courseshellexisting', 'block_usp_mcrs'), 0);
        $mform->addGroup($radioarray3, 'radioar3', '', array(' '), false);
        $mform->hideIf('radioar3','f2f','notchecked'); 
        $mform->hideIf('radioar3','singlemultiple','eq', '0');

        // Copyfrom dropdown F2F        
        $coursshellearray = array();
        $courseshellarray[0] = get_string('choosecourseshell1', 'block_usp_mcrs');
        $allcourseshells = $DB->get_records_select('course', 'id > 0', array(), 'id', 'id, shortname');
        foreach ($allcourseshells as $id => $courseshellobject) {
            $courseshellarray[$id] = $courseshellobject->shortname;
        }
        $mform->addElement('select', 'courseidf2f', '', $courseshellarray);
        $mform->hideIf('courseidf2f','f2f','notchecked');   
        $mform->hideIf('courseidf2f','newbackedup1','eq', '1');  
        $mform->hideIf('courseidf2f','singlemultiple','eq', '0');  

        // Course Year F2F
        $mform->addElement('text', 'courseyearf2f', get_string('courseyearf2f', 'block_usp_mcrs'));
        $mform->setType('courseyearf2f', PARAM_TEXT);
        $mform->hideIf('courseyearf2f','f2f','notchecked');  
        $mform->hideIf('courseyearf2f','newbackedup1','eq', '0');
        $mform->hideIf('courseyearf2f','singlemultiple','eq', '0');
        $mform->hideIf('courseyearf2f','radioar3','eq', '0');

        // Course Semester F2F
        $mform->addElement('text', 'coursesemesterf2f', get_string('coursesemesterf2f', 'block_usp_mcrs')); 
        $mform->setType('coursesemesterf2f', PARAM_TEXT);
        $mform->hideIf('coursesemesterf2f','f2f','notchecked');
        $mform->hideIf('coursesemesterf2f','newbackedup1','eq', '0');
        $mform->hideIf('coursesemesterf2f','singlemultiple','eq', '0');
        $mform->hideIf('coursesemesterf2f','radioar3','eq', '0');

        // Course Mode Online checkbox
        $mform->addElement('checkbox', 'online', '', get_string('online', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'online\', this.checked)"');
        $mform->hideIf('online','singlemultiple','eq', '0');

        // New or Backed up Online shell
        $radioarray4 = array();
        $radioarray4[] = $mform->createElement('radio', 'newbackedup2', '', get_string('courseshellnew', 'block_usp_mcrs'), 1);
        $radioarray4[] = $mform->createElement('radio', 'newbackedup2', '', get_string('courseshellexisting', 'block_usp_mcrs'), 0);
        $mform->addGroup($radioarray4, 'radioar4', '', array(' '), false);
        $mform->hideIf('radioar4','online','notchecked'); 
        $mform->hideIf('radioar4','singlemultiple','eq', '0');

        // Copyfrom dropdown Online
        $coursshellearray = array();
        $courseshellarray[0] = get_string('choosecourseshell2', 'block_usp_mcrs');
        $allcourseshells = $DB->get_records_select('course', 'id > 0', array(), 'id', 'id, shortname');
        foreach ($allcourseshells as $id => $courseshellobject) {
            $courseshellarray[$id] = $courseshellobject->shortname;
        }
        $mform->addElement('select', 'courseidonline', '', $courseshellarray);
        $mform->hideIf('courseidonline','online','notchecked');
        $mform->hideIf('courseidonline','newbackedup2','eq', '1');  
        $mform->hideIf('courseidonline','singlemultiple','eq', '0');

        // Course Year Online
        $mform->addElement('text', 'courseyearonline', get_string('courseyearonline', 'block_usp_mcrs'));
        $mform->setType('courseyearonline', PARAM_TEXT);
        $mform->hideIf('courseyearonline','online','notchecked');  
        $mform->hideIf('courseyearonline','newbackedup2','eq', '0');
        $mform->hideIf('courseyearonline','singlemultiple','eq', '0');
        $mform->hideIf('courseyearonline','radioar4','eq', '0');

        // Course Semester Online
        $mform->addElement('text', 'coursesemesteronline', get_string('coursesemesteronline', 'block_usp_mcrs')); 
        $mform->setType('coursesemesteronline', PARAM_TEXT);
        $mform->hideIf('coursesemesteronline','online','notchecked');
        $mform->hideIf('coursesemesteronline','newbackedup2','eq', '0');
        $mform->hideIf('coursesemesteronline','singlemultiple','eq', '0');
        $mform->hideIf('coursesemesteronline','radioar4','eq', '0');

        // Course Mode Print checkbox
        $mform->addElement('checkbox', 'print', '', get_string('print', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'print\', this.checked)"');
        $mform->hideIf('print','newbackedup','eq', '1');
        $mform->hideIf('print','singlemultiple','eq', '0');

        // New or Backed up Print shell
        $radioarray5 = array();
        $radioarray5[] = $mform->createElement('radio', 'newbackedup3', '', get_string('courseshellnew', 'block_usp_mcrs'), 1);
        $radioarray5[] = $mform->createElement('radio', 'newbackedup3', '', get_string('courseshellexisting', 'block_usp_mcrs'), 0);
        $mform->addGroup($radioarray5, 'radioar5', '', array(' '), false);
        $mform->hideIf('radioar5','print','notchecked'); 
        $mform->hideIf('radioar5','singlemultiple','eq', '0');

        // Copyfrom dropdown Print
        $coursshellearray = array();
        $courseshellarray[0] = get_string('choosecourseshell3', 'block_usp_mcrs');
        $allcourseshells = $DB->get_records_select('course', 'id > 0', array(), 'id', 'id, shortname');
        foreach ($allcourseshells as $id => $courseshellobject) {
            $courseshellarray[$id] = $courseshellobject->shortname;
        }
        $mform->addElement('select', 'courseidprint', '', $courseshellarray);
        $mform->hideIf('courseidprint','print','notchecked');
        $mform->hideIf('courseidprint','newbackedup3','eq', '1'); 
        $mform->hideIf('courseidprint','singlemultiple','eq', '0');

        // Course Year Print
        $mform->addElement('text', 'courseyearprint', get_string('courseyearprint', 'block_usp_mcrs'));
        $mform->setType('courseyearprint', PARAM_TEXT);
        $mform->hideIf('courseyearprint','print','notchecked');  
        $mform->hideIf('courseyearprint','newbackedup3','eq', '0');
        $mform->hideIf('courseyearprint','singlemultiple','eq', '0');
        $mform->hideIf('courseyearprint','radioar4','eq', '0');

        // Course Semester Print
        $mform->addElement('text', 'coursesemesterprint', get_string('coursesemesterprint', 'block_usp_mcrs')); 
        $mform->setType('coursesemesterprint', PARAM_TEXT);
        $mform->hideIf('coursesemesterprint','print','notchecked');
        $mform->hideIf('coursesemesterprint','newbackedup3','eq', '0');
        $mform->hideIf('coursesemesterprint','singlemultiple','eq', '0');
        $mform->hideIf('coursesemesterprint','radioar5','eq', '0');

        // Course Mode Blended checkbox
        $mform->addElement('checkbox', 'blended', '', get_string('blended', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'blended\', this.checked)"'); 
        $mform->hideIf('blended','newbackedup','eq', '1');
        $mform->hideIf('blended','singlemultiple','eq', '0');  

        // New or Backed up Blended shell
        $radioarray6 = array();
        $radioarray6[] = $mform->createElement('radio', 'newbackedup4', '', get_string('courseshellnew', 'block_usp_mcrs'), 1);
        $radioarray6[] = $mform->createElement('radio', 'newbackedup4', '', get_string('courseshellexisting', 'block_usp_mcrs'), 0);
        $mform->addGroup($radioarray6, 'radioar6', '', array(' '), false);
        $mform->hideIf('radioar6','blended','notchecked'); 
        $mform->hideIf('radioar6','singlemultiple','eq', '0');
        
        // Copyfrom dropdown Blended
        $coursshellearray = array();
        $courseshellarray[0] = get_string('choosecourseshell4', 'block_usp_mcrs');
        $allcourseshells = $DB->get_records_select('course', 'id > 0', array(), 'id', 'id, shortname');
        foreach ($allcourseshells as $id => $courseshellobject) {
            $courseshellarray[$id] = $courseshellobject->shortname;
        }
        $mform->addElement('select', 'courseidblended', '', $courseshellarray);
        $mform->hideIf('courseidblended','blended','notchecked');
        $mform->hideIf('courseidblended','newbackedup4','eq', '1'); 
        $mform->hideIf('courseidblended','singlemultiple','eq', '0'); 

        // Course Year Blended
        $mform->addElement('text', 'courseyearblended', get_string('courseyearblended', 'block_usp_mcrs'));
        $mform->setType('courseyearblended', PARAM_TEXT);
        $mform->hideIf('courseyearblended','blended','notchecked');  
        $mform->hideIf('courseyearblended','newbackedup4','eq', '0');
        $mform->hideIf('courseyearblended','singlemultiple','eq', '0');
        $mform->hideIf('courseyearblended','radioar6','eq', '0');

        // Course Semester Blended
        $mform->addElement('text', 'coursesemesterblended', get_string('coursesemesterblended', 'block_usp_mcrs')); 
        $mform->setType('coursesemesterblended', PARAM_TEXT);
        $mform->hideIf('coursesemesterblended','blended','notchecked');
        $mform->hideIf('coursesemesterblended','newbackedup4','eq', '0');
        $mform->hideIf('coursesemesterblended','singlemultiple','eq', '0');
        $mform->hideIf('coursesemesterblended','radioar6','eq', '0');
        
        // Additional Information
        $mform->addElement('editor', 'additionalinfo', get_string('additionalinfo', 'block_usp_mcrs'));
        $mform->setType('additionalinfo', PARAM_RAW);
        
        // Submit button with Cancel button
        $this->add_action_buttons(true, get_string('submitbutton', 'block_usp_mcrs'));
    } 
}