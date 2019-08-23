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

require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");
require_login();
global $CFG, $USER, $DB;


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('requestcourse', 'block_usp_mcrs'), new moodle_url('/blocks/usp_mcrs/requestcourse.php'));

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
        global $currentsess, $DB, $currentrecord;
    
        $mform =& $this->_form; // Don't forget the underscore! 
        
        $mform->addElement('header', 'mainheader','<span style="font-size:22px">'.get_string('courserequestform','block_usp_mcrs'). '</span>');

        // TODO: Add other form elements here
  
        // Submit button helper function. First parameter is to specify whether we want a cancel button
        // Second parameter is the string to label the buttons
        $this->add_action_buttons(true, 'Submit');
    }    
}

//Instantiate requestcourse 
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