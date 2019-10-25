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
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/../../backup/util/includes/restore_includes.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->libdir.'/filestorage/zip_packer.php');

$systemcontext = context_system::instance();
$usercontext = context_user::instance($USER->id);

$step = optional_param('step', BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE, PARAM_INT);
$versionid = optional_param('version', 0, PARAM_INT);

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE) 
{
    /*$courseuploadform = new courseupload_form();

     if ($courseuploadform->is_submitted()) {
        // Upload new course
        $courseuploaddata = $courseuploadform->get_data();
        $mbzfilename = $courseuploadform->get_new_filename('coursefile');
        $archivename = restore_controller::get_tempdir_name(0, $USER->id);
        $archivepath = block_hubcourseupload_getbackuppath($archivename);
        if (!$courseuploadform->save_file('coursefile', $archivepath)) {
            throw new Exception(get_string('error_cannotsaveuploadfile', 'block_hubcourseupload'));
        }
    }
 */

    $info = backup_general_helper::get_backup_information_from_mbz($archivepath);
    if ($info->type != 'course') {
        fulldelete($archivepath);
        throw new Exception(get_string('error_backupisnotcourse', 'block_hubcourseupload'));
    }

    if ($CFG->version < $info->moodle_version) {
        $PAGE->set_context($systemcontext);
        $PAGE->set_pagelayout('standard');
        $PAGE->set_title(get_string('pluginname', 'block_hubcourseupload'));
        $PAGE->set_heading(get_string('pluginname', 'block_hubcourseupload'));
        echo $OUTPUT->header();
        $versionconfirm = new versionconfirm_form(
            "{$info->moodle_version} - {$info->moodle_release}",
            "{$CFG->version} - {$CFG->release}", [
            'archivename' => $archivename,
            'info' => block_hubcourseupload_reduceinfo($info),
            'step' => BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED,
            'mbzfilename' => $mbzfilename,
            'version' => $versionid
        ]);
        $versionconfirm->display();
        echo $OUTPUT->footer();
        exit;
    }

    $step = BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED;
}

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED) {

    $extractedname = restore_controller::get_tempdir_name($systemcontext->id, $USER->id);
    $extractedpath = block_hubcourseupload_getbackuppath($extractedname);
    $fb = get_file_packer('application/vnd.moodle.backup');
    if (!$fb->extract_to_pathname($archivepath, $extractedpath, null)) {
        throw new Exception(get_string('error_cannotextractfile', 'block_hubcourseupload'));
    }

    $step = BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED;
}

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED) {

    /* if ($version && block_hubcourseupload_infoblockenabled()) {
        // Apply Version
        $coursecontext = $hubcoursecontext->get_course_context();
        $courseid = $coursecontext->instanceid;
    } else { */
        // New Course
        $category = get_config('block_hubcourseupload', 'defaultcategory');

        if (!$DB->get_record('course_categories', ['id' => $category])) {
            throw new Exception(get_string('error_categorynotfound', 'block_hubcourseupload'));
        }

        list($fullname, $shortname) = restore_dbops::calculate_course_names(0, get_string('restoringcourse', 'backup'), get_string('restoringcourseshortname', 'backup'));
        $courseid = restore_dbops::create_new_course($fullname, $shortname, $category);
    //}

    $course = $DB->get_record('course', ['id' => $courseid]);

    raise_memory_limit(MEMORY_EXTRA);

    try {
        $coursecontext = context_course::instance($courseid);

        if (!has_capability('moodle/restore:restorecourse', $coursecontext)) {
            $roleid = block_hubcourseupload_getroleid();
            if (!$roleid) {
                throw new Exception(get_string('error_cannotgetroleinfo', 'block_hubcourseupload'));
            }

            role_assign($roleid, $USER->id, $coursecontext->id);
            assign_capability('moodle/restore:restorecourse', CAP_ALLOW, $roleid, $coursecontext->id, true);
            $coursecontext->mark_dirty();
        }

        if ($versionid && block_hubcourseupload_infoblockenabled()) {
            block_hubcourseinfo_clearcontents($course);
        }

        $rc = ($versionid && block_hubcourseupload_infoblockenabled()) ?
            new restore_controller($extractedname, $courseid, backup::INTERACTIVE_NO, backup::MODE_GENERAL, $USER->id, backup::TARGET_EXISTING_DELETING) :
            new restore_controller($extractedname, $courseid, backup::INTERACTIVE_NO, backup::MODE_GENERAL, $USER->id, backup::TARGET_NEW_COURSE);
        $rc->set_status(backup::STATUS_AWAITING);
        $rc->get_plan()->execute();

        $blocks = backup_general_helper::get_blocks_from_path($extractedpath . '/course');

        $rc->destroy();

        if ($versionid && block_hubcourseupload_infoblockenabled()) {
            // Apply version
            $hubcourse = block_hubcourseinfo_gethubcoursefromcourseid($courseid);
            $hubcourse->stableversion = $version->id;
            $hubcourse->timemodified = time();
            $DB->update_record('block_hubcourses', $hubcourse);

            $hubcourseid = $hubcourse->id;

            block_hubcourseinfo_enableguestenrol($courseid);
        } else {
            // New course
            $hubcourseid = 0;
            if (block_hubcourseupload_infoblockenabled()) {
                $hubcourseid = block_hubcourseinfo_afterrestore($courseid, $info, $mbzfilename, $archivepath, $plugins);
            }
        }

        fulldelete($extractedpath);
        fulldelete($archivepath);

        if (block_hubcourseupload_infoblockenabled() && $hubcourseid && !$versionid) {
            redirect(new moodle_url('/blocks/hubcourseinfo/metadata/edit.php', ['id' => $hubcourseid, 'new' => 1]));
        } else {
            redirect(new moodle_url('/course/view.php', ['id' => $courseid]));
        }
        exit;
//    } catch (Exception $ex) {
//        if (!$versionid) {
//            delete_course($courseid);
//        }
//        fulldelete($extractedpath);
//        fulldelete($archivepath);
//        throw new Exception(get_string('error_cannotrestore', 'block_hubcourseupload') . $ex->getMessage());
    } catch (Error $ex) {
        if (!$versionid) {
            delete_course($courseid);
        }
        fulldelete($extractedpath);
        fulldelete($archivepath);
        throw new Exception(get_string('error_cannotrestore', 'block_hubcourseupload') . $ex->getMessage());
    }
}