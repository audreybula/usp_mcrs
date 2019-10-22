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

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');

class usp_mcrs_lib
{
    // list request entries 
     function list_request_entries()
    {
        global $CFG, $DB, $OUTPUT;

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
        foreach ($rec as $records) {
            $id = $records->id;
            $coursecode = $records->course_code;
            $coursename = $records->course_name;
            $schoolname = $records->course_school;
            $subject = 'Create Course Shell for ' . $coursecode . ': ' . $coursename;
            $facultyname = $records->course_faculty;
            $requestdate = $records->request_date;
            $requestername = $records->course_requester;
            $requestlecturer = $records->course_lecturer;
            $status = 'Pending';
            $table->data[] = array($id, $subject, $requestdate, $requestername, $requestlecturer, $status);
        }
        echo html_writer::table($table);
    }


    function list_support_staff(){
        global $CFG, $DB, $OUTPUT;

        $rec = $DB->get_records_sql('SELECT support_staff_name,school_name,course_count FROM  `mdl_block_usp_mcrs_support_staff` ORDER BY course_count ASC ');
        $table = new html_table();
        $table->colclasses = array('leftalign', 'leftalign', 'leftalign');
        $table->id = 'support_staffs';
        $table->attributes['class'] = 'support_staff_table generaltable';
        $table->head = array(
            get_string('support_staff_name', 'block_usp_mcrs'),
            get_string('school', 'block_usp_mcrs'),
            get_string('course_count', 'block_usp_mcrs')
        );
        foreach ($rec as $records) {
            $support_staff_name = $records->support_staff_name;
            $school_name = $records->school_name;
            $course_count = $records->course_count;

            $table->data[] = array($support_staff_name, $school_name, $course_count);
        }
        echo html_writer::table($table);

    }
}
