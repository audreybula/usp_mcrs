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

require_once('../../config.php'); // Change depending on depth
require_once("$CFG->libdir/formslib.php");
require_login();
global $CFG, $USER, $DB;


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('courserequestform', 'block_usp_mcrs'), new moodle_url('/blocks/usp_mcrs/requestcourse.php'));

$PAGE->set_url('/blocks/usp_mcrs/requestcourse.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('requestcourse', 'block_usp_mcrs'));
$PAGE->set_title(get_string('requestcourse', 'block_usp_mcrs'));
echo $OUTPUT->header();


class requestcourse extends moodleform
{
    function definition() 
    {   
        global $CFG;
        global $currentsess, $DB, $USER, $currentrecord; // Have to re-define inside functions
    
        $mform =& $this->_form; // Don't forget the underscore! 

        // Form header
        $mform->addElement('header', 'mainheader','<span style="font-size:22px">'.get_string('courserequestform','block_usp_mcrs'). '</span>');

        // Course Code field
        $mform->addElement('text', 'coursecode', get_string('coursecode', 'block_usp_mcrs'), 'onkeyup="showHint(this.value)"');
        $mform->addRule('coursecode', get_string('required'), 'required', null, 'client');
        $mform->setType('coursecode', PARAM_TEXT);

        // Course Name field
        $mform->addElement('text', 'coursename', get_string('coursename', 'block_usp_mcrs'), 'size="65px');
        $mform->addRule('coursename', get_string('required'), 'required', null, 'client');
        $mform->setType('coursename', PARAM_TEXT);

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
        // TODO: Fix help button 
        $mform->addHelpButton('courselecturer', get_string('courselecturerhelp', 'block_usp_mcrs'));  
        $mform->setType('courselecturer', PARAM_TEXT);

        // Course Faculty field
        $mform->addElement('text', 'coursefaculty', get_string('coursefaculty', 'block_usp_mcrs'));
        $mform->setType('coursefaculty', PARAM_TEXT);

        // Course School field
        $mform->addElement('text', 'courseschool', get_string('courseschool', 'block_usp_mcrs'));
        $mform->setType('courseschool', PARAM_TEXT);
        
        // Number of shells dropdown 
        $options = array('1' => 'Single', '2' => 'Multiple');
        $select = $mform->addElement('select', 'courseshellnumber', get_string('courseshellnumber', 'block_usp_mcrs'), $options);
        $select->setSelected('1');

        // Course Mode checkboxes 
        $mform->addElement('checkbox', 'f2f', 'Course Mode', get_string('f2f', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'f2f\', this.checked)"');
        $mform->addElement('checkbox', 'online', '', get_string('online', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'online\', this.checked)"');
        $mform->addElement('checkbox', 'print', '', get_string('print', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'print\', this.checked)"');
        $mform->addElement('checkbox', 'blended', '', get_string('blended', 'block_usp_mcrs'), 'onclick="coordinates_form_display(\'blended\', this.checked)"');

        // Additional Information
        $mform->addElement('editor', 'additionalinfo', get_string('additionalinfo', 'block_usp_mcrs'));
        $mform->setType('additionalinfo', PARAM_RAW);
                
        // TODO: Add other form elements here
        
        // Submit button with Cancel button
        $this->add_action_buttons(true, get_string('submitbutton', 'block_usp_mcrs'));
    } 
}

//Instantiate the Form
$mform = new requestcourse();

//Form processing and displaying is done here
if ($mform->is_cancelled()) 
{
    //Handle form cancel operation, if cancel button is present on form
    echo '<script>window.location="/moodle/my/index.php";</script>';
    die;
} 
else if ($fromform = $mform->get_data()) 
{
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    
} 
else 
{
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    //Set default data (if any)
    $mform->set_data($toform);
    //displays the form
    $mform->display();
}

echo $OUTPUT->footer();