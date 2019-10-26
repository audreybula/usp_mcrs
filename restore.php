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
 * Restoration process
 *
 * @package block_usp_mcrs
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('restorelib.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

$systemcontext = context_system::instance();
$usercontext = context_user::instance($USER->id);

require_capability('block/usp_mcrs:upload', $usercontext);

$step = optional_param('step', BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE, PARAM_INT);

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE) {
    /* $courseuploadform = new courseupload_form();
    if ($courseuploadform->is_submitted()) {
        // Upload new course

        $courseuploaddata = $courseuploadform->get_data();
        $mbzfilename = $courseuploadform->get_new_filename('coursefile');

        $archivename = restore_controller::get_tempdir_name(0, $USER->id);
        $archivepath = block_usp_mcrs_getbackuppath($archivename);
        if (!$courseuploadform->save_file('coursefile', $archivepath)) {
            throw new Exception(get_string('error_cannotsaveuploadfile', 'block_usp_mcrs'));
        }

    }  */
    $archivename = restore_controller::get_tempdir_name(0, $USER->id);
    $archivepath = block_usp_mcrs_getbackuppath($archivename);
    $mbzfilename = 'usp_mcrs-CS219_CS318_201903.mbz';
    if (!$courseuploadform->save_file($mbzfilename, $archivepath)) {
        throw new Exception(get_string('error_cannotsaveuploadfile', 'block_usp_mcrs'));
    }

    $step = BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED;
}

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED) {

    $extractedname = restore_controller::get_tempdir_name($systemcontext->id, $USER->id);
    $extractedpath = block_usp_mcrs_getbackuppath($extractedname);
    $fb = get_file_packer('application/vnd.moodle.backup');
    if (!$fb->extract_to_pathname($archivepath, $extractedpath, null)) {
        throw new Exception(get_string('error_cannotextractfile', 'block_usp_mcrs'));
    }

    $step = BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED;
}

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED) {
    // New Course
    $category = get_config('block_usp_mcrs', 'defaultcategory');

    if (!$DB->get_record('course_categories', ['id' => $category])) {
        throw new Exception(get_string('error_categorynotfound', 'block_usp_mcrs'));
    }

    list($fullname, $shortname) = restore_dbops::calculate_course_names(0, get_string('restoringcourse', 'backup'), get_string('restoringcourseshortname', 'backup'));
    $courseid = restore_dbops::create_new_course($fullname, $shortname, $category);

    $course = $DB->get_record('course', ['id' => $courseid]);

    raise_memory_limit(MEMORY_EXTRA);

    try {
        $coursecontext = context_course::instance($courseid);

        if (!has_capability('moodle/restore:restorecourse', $coursecontext)) {
            $roleid = block_usp_mcrs_getroleid();
            if (!$roleid) {
                throw new Exception(get_string('error_cannotgetroleinfo', 'block_usp_mcrs'));
            }

            role_assign($roleid, $USER->id, $coursecontext->id);
            assign_capability('moodle/restore:restorecourse', CAP_ALLOW, $roleid, $coursecontext->id, true);
            $coursecontext->mark_dirty();
        }

        $rc = new restore_controller($extractedname, $courseid, backup::INTERACTIVE_NO, backup::MODE_GENERAL, $USER->id, backup::TARGET_NEW_COURSE);
        $rc->set_status(backup::STATUS_AWAITING);
        $rc->get_plan()->execute();

        $blocks = backup_general_helper::get_blocks_from_path($extractedpath . '/course');

        $rc->destroy();

        fulldelete($extractedpath);
        fulldelete($archivepath);

        exit;
    } catch (Error $ex) {
        if (!$versionid) {
            delete_course($courseid);
        }
        fulldelete($extractedpath);
        fulldelete($archivepath);
        throw new Exception(get_string('error_cannotrestore', 'block_usp_mcrs') . $ex->getMessage());
    }
}