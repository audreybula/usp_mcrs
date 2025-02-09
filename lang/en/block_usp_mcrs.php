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
 * @package    block_usp_mcrs
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// Strings for block
$string['pluginname'] = 'Moodle Course Request';
$string['requestcourse'] = 'Make A Course Request';
$string['mcrs_admin'] = 'Moodle Admin Home';
$string['mcrs_cfl_admin'] = 'CFL Admin Home';
$string['configemail'] = 'Modify Email Template';
$string['usp_mcrs_settings'] = 'USP MCRS Settings';
$string['cron_backup_error'] = 'Error backing up {$a}';
$string['status_not_running'] = 'Not Running';
$string['cron_already_running'] = 'Skipping usp_mcrs, it has already been ' . 'running for {$a} minute(s)';
$string['backuptask'] = 'Backup job';

// Strings for support staff
$string['support_staff_name'] = 'Support Staff';
$string['course_count'] ='Course Count';
$string['school'] = 'School';

// Course Request Form Strings
$string['requestcourse'] = 'Make A Course Request';
$string['courserequestform'] = 'Course Request Form';
$string['coursecode'] = 'Course Code';
$string['coursename'] = 'Course Name';
$string['coursefaculty'] = 'Course Faculty';
$string['courseschool'] = 'Course School';
$string['courselecturer'] = 'Course Lecturer';
$string['courselecturer_help'] = 'By default, we have placed your email. Please enter the respective lecturer\'s email if you are not the course lecturer';
$string['courseshellnew'] = 'Blank';
$string['courseshellexisting'] = 'Existing';
$string['courseshellsingle'] = 'Single';
$string['courseshellmultiple'] = 'Multiple';
$string['f2f'] = 'Face To Face';
$string['online'] = 'Online';
$string['print'] = 'Print';
$string['blended'] = 'Blended';
$string['choosecoursecode'] = 'Choose Course Code';
$string['choosecoursename'] = 'Choose Course Name';
$string['choosecourseschool'] = 'Course School';
$string['choosecourseshellgeneral'] = 'Choose Course Shell To Be Copied';
$string['choosecourseshell1'] = 'Choose F2F Course Shell To Be Copied';
$string['choosecourseshell2'] = 'Choose Online Course Shell To Be Copied';
$string['choosecourseshell3'] = 'Choose Print Course Shell To Be Copied';
$string['choosecourseshell4'] = 'Choose Blended Course Shell To Be Copied';
$string['additionalinfo'] = 'Additional Information or Comments';
$string['submitbutton'] = 'Submit';

// Admin Home
$string['moodle_admin'] = 'Moodle Admin Home';
$string['requestid'] = 'ID';
$string['requestsubject'] = 'Subject';
$string['requestdate'] = 'Date';
$string['requestername'] = 'Requester';
$string['requestlecturer'] = 'Lecturer';
$string['requeststatus'] = 'Status';

// Backup
$string['backup'] = 'Backup';
$string['backing_up'] = 'Backing Up';
$string['continue'] = 'Continue';

// Settings
$string['config_path'] = 'Storage Path';
$string['config_path_desc'] = 'Relative to {$a}, include the surrounding slashes. Ensure that this directory is created and writable.';
$string['config_roles'] = 'Roles';
$string['config_size_limit'] = 'Size limit before warning';
$string['config_size_limit_desc'] = 'In megabytes';
$string['path_error'] = 'Error: Please ensure that the path you provided is a ' . 'writable directory';
$string['sched_config'] = 'Access scheduled backup settings as (' . $string['pluginname'] . ') uses these settings.';
$string['here'] = 'here';
$string['config_path_not_exists'] = 'The path you have entered does not exists.';
$string['config_path_not_writable'] = 'The path you have entered is not writable.';
$string['config_path_surround'] = 'Surround the path with slashes.';

// Strings for email.
$string['email_subject'] = 'Backup Job Completed';
$string['email_from'] = 'noreply@lsu.edu';
$string['email_body']  = "The Backup And Delete tool has completed the jobs in it's queue.";

// Capabilities.
$string['usp_mcrs:addinstance'] = 'Add usp_mcrs block.';
$string['usp_mcrs:myaddinstance'] = 'Create a new instance in my page';
$string['usp_mcrs:view'] = 'View usp_mcrs block';

// Restore
$string['settings:maxfilesize'] = 'Maximum course file size (MB)';
$string['settings:maxfilesize_description'] = 'Maximum course backup size per file in megabytes (MB)<br><small>*Actual maximum upload size might be limited by server settings in <i>php.ini</i> file.</small>';
$string['settings:defaultcategory'] = 'Default category';
$string['settings:defaultcategory_description'] = 'Default category of newly uploaded course';

$string['error_filenotuploaded'] = 'There is no file uploaded.';
$string['error_cannotsaveuploadfile'] = 'Cannot read upload file.';
$string['error_backupisnotcourse'] = 'Backup file is not a course backup.';
$string['error_cannotextractfile'] = 'Cannot extract file.';
$string['error_cannotrestore'] = 'Cannot perform restore execution.';
$string['error_categorynotfound'] = 'Category not found';
