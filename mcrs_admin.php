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
 * Plugin strings are defined here.
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
// todo: wrap around cap check
$PAGE->navbar->add(get_string('moodle_admin', 'block_usp_mcrs'));
$PAGE->set_url('/blocks/usp_mcrs/mcrs_admin.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('moodle_admin', 'block_usp_mcrs'));
$PAGE->set_title(get_string('moodle_admin', 'block_usp_mcrs'));


$defineurl = $CFG->wwwroot . '/' . $CFG->admin . '/roles/define.php';
// Initialise table.
$rec = $DB->get_records_sql('SELECT * FROM  `mdl_block_usp_mcrs_requests`');
$table = new html_table();
$table->colclasses = array('leftalign', 'leftalign', 'leftalign', 'leftalign');
$table->id = 'requests';
$table->attributes['class'] = 'admintable generaltable';
$table->head = array(
    get_string('requestid', 'block_usp_mcrs'),
    get_string('requestsubject', 'block_usp_mcrs'),
    get_string('requestdate', 'block_usp_mcrs'),
    get_string('requestername', 'block_usp_mcrs'),
    get_string('requestlecturer', 'block_usp_mcrs'),
    get_string('requeststatus', 'block_usp_mcrs')
);
foreach ($rec as $records) 
{
    $id = $records->id;
    $coursecode = $records->course_code;
    $coursename = $records->course_name;    
    $schoolname = $records->course_school;
    $subject = 'Create Course Shell for '.$coursecode.': '.$coursename;
    $facultyname = $records->course_faculty;
    $requestdate = $records->request_date;
    $requestername = $records->course_requester;
    $requestlecturer = $records->course_lecturer;
    $status = 'Pending';
    $table->data[] = array($id, $subject, $requestdate, $requestername, $requestlecturer, $status);    
}
echo $OUTPUT->header();



echo $OUTPUT->footer();







