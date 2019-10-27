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
 * Course Request Functions
 *
 * @package     block_usp_mcrs
 * @category    string
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function getFormData($fromform, $DB, $USER, $request)
{
    $request->request_date = date('Y-m-d H:i:s');
    $request->course_code = $DB->get_field_select('block_usp_mcrs_courses', 'course_code', 'id = '.$fromform->coursecode, array(), $strictness=IGNORE_MISSING);
    $request->course_name = $DB->get_field_select('block_usp_mcrs_courses', 'course_name', 'id = '.$fromform->coursename, array(), $strictness=IGNORE_MISSING);
    $request->course_school = $DB->get_field_select('block_usp_mcrs_courses', 'school_name', 'id = '.$fromform->courseschool, array(), $strictness=IGNORE_MISSING);
    $request->course_faculty = $DB->get_field_select('block_usp_mcrs_courses', 'faculty_name', 'id = '.$fromform->coursefaculty, array(), $strictness=IGNORE_MISSING);
    $request->course_requester = $USER->email;
    $request->course_lecturer = $fromform->courselecturer;
    $request->additional_info = $fromform->additionalinfo;
    return $request;
}

function createCourse($request, $fromform, $mode)
{
    $data = new stdClass();
    $data->category = 1;
    if($mode == 0)
    {
        $data->idnumber = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester;
        $data->shortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester;
        $data->fullname = $request->course_code.': '.$request->course_name;
    }
    elseif($mode == 1)
    {
        $data->idnumber = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_F';
        $data->shortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_F';
        $data->fullname = $request->course_code.': '.$request->course_name.' (Face to Face)';
    }
    elseif($mode == 2)
    {
        $data->idnumber = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_O';
        $data->shortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_O';
        $data->fullname = $request->course_code.': '.$request->course_name.' (Online)';
    }
    elseif($mode == 3)
    {
        $data->idnumber = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_P';
        $data->shortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_P';
        $data->fullname = $request->course_code.': '.$request->course_name.' (Print)';
    }
    else
    {
        $data->idnumber = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_B';
        $data->shortname = $request->course_code.'_'.$fromform->courseyear.''.$fromform->coursesemester.'_B';
        $data->fullname = $request->course_code.': '.$request->course_name.' (Blended)';
    }
    $data->summary = '';
    $data->summaryformat = 0;
    $data->format = 'topics';
    $data->showgrades = 1;
    $data->visible = 1;
    $h = create_course($data); 
}

function check_enrol($shortname, $userid, $roleid, $enrolmethod = 'manual') {
    global $DB;
    $user = $DB->get_record('user', array('id' => $userid, 'deleted' => 0), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('shortname' => $shortname), '*', MUST_EXIST);
    $context = context_course::instance($course->id);
    if (!is_enrolled($context, $user)) {
        $enrol = enrol_get_plugin($enrolmethod);
        if ($enrol === null) {
            return false;
        }
        $instances = enrol_get_instances($course->id, true);
        $manualinstance = null;
        foreach ($instances as $instance) {
            if ($instance->name == $enrolmethod) {
                $manualinstance = $instance;
                break;
            }
        }
        if ($manualinstance !== null) {
            $instanceid = $enrol->add_default_instance($course);
            if ($instanceid === null) {
                $instanceid = $enrol->add_instance($course);
            }
            $instance = $DB->get_record('enrol', array('id' => $instanceid));
        }
        $enrol->enrol_user($instance, $userid, $roleid);
    }
    return true;
}