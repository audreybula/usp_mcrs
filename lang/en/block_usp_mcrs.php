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

// Strings for block.
$string['backup_and_delete'] = 'Backup And Delete';
$string['block_index'] = 'Backup';
$string['block_delete'] = 'Delete';
$string['block_pending'] = 'Pending';
$string['block_config'] = 'Config';
$string['block_requestcourse'] = 'Make A Course Request';
$string['block_mcrs_admin'] = 'Moodle Admin Home';
$string['block_configemail'] = 'Modify Email Template';
$string['block_failed'] = 'Failures';
$string['block_large_backups'] = 'Large Backups';
$string['backing_up'] = 'Backing Up';
$string['usp_mcrs_settings'] = 'Backup and Delete Settings';
$string['status_running'] = 'Running for {$a} minute(s)';
$string['cron_backup_error'] = 'Error backing up {$a}';
$string['cron_restore_error'] = 'Error backing up {$a}';
$string['status_not_running'] = 'Not Running';
$string['cron_already_running'] = 'Skipping usp_mcrs, it has already been running for {$a} minute(s)';
$string['backuptask'] = 'Backup job';
$string['restoretask'] = 'Restore job';

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
$string['courseyeargeneral'] = 'Enter year course is to be offered';
$string['coursesemestergeneral'] = 'Enter semester course is to offered';
$string['courseyearf2f'] = 'Enter year course is to be offered';
$string['coursesemesterf2f'] = 'Enter semester course is to offered';
$string['courseyearonline'] = 'Enter year course is to be offered';
$string['coursesemesteronline'] = 'Enter semester course is to offered';
$string['courseyearprint'] = 'Enter year course is to be offered';
$string['coursesemesterprint'] = 'Enter semester course is to offered';
$string['courseyearblended'] = 'Enter year course is to be offered';
$string['coursesemesterblended'] = 'Enter semester course is to offered';
$string['choosecourseshellgeneral'] = 'Choose Course Shell To Be Copied';
$string['choosecourseshell1'] = 'Choose F2F Course Shell To Be Copied';
$string['choosecourseshell2'] = 'Choose Online Course Shell To Be Copied';
$string['choosecourseshell3'] = 'Choose Print Course Shell To Be Copied';
$string['choosecourseshell4'] = 'Choose Blended Course Shell To Be Copied';
$string['additionalinfo'] = 'Additional Information or Comments';
$string['submitbutton'] = 'Submit';

//Strings for support staff
$string['support_staff_name'] = 'Support Staff';
$string['course_count'] ='Course Count';
$string['school'] = 'School';

// Stings shared by pages.
$string['pluginname'] = 'Moodle Course Request';
$string['blockname'] = 'Moodle Course Request';
$string['need_permission'] = 'You do not have permission to view this page';
$string['toggle_all'] = 'Select All/None';

// Strings for index.php.
$string['build_search'] = 'Build Search';
$string['saved_searches'] = 'Saved Searches';
$string['no_searches'] = 'There are no saved searches at this time';
$string['match'] = 'Match';
$string['of_these_constraints'] = 'of these constraints';
$string['build_search_button'] = 'Build Search Query';
$string['search_name'] = 'Search Name';
$string['created_at'] = 'Created At';
$string['run_query_button'] = 'Run Saved Query';
$string['upload_a_file'] = 'Upload A File';
$string['upload'] = 'Upload';
$string['cancel'] = 'Cancel';
$string['delete_queries_link'] = 'Select Saved Queries for Deletion';
$string['course_id'] = 'Course ID #';
$string['is'] = 'is';
$string['is_not'] = 'is not';
$string['contains'] = 'contains';
$string['does_not_contain'] = 'does not contain';
$string['name_missing'] = 'Please select a name for this query';
$string['term_missing'] = 'Please select at least one search term for this constraint';
$string['search_missing'] = 'Please select a saved search';

// Admin Home
$string['moodle_admin'] = 'Moodle Admin Home';
$string['requestid'] = 'ID';
$string['requestsubject'] = 'Subject';
$string['requestdate'] = 'Date';
$string['requestername'] = 'Requester';
$string['requestlecturer'] = 'Lecturer';
$string['requeststatus'] = 'Status';

// Strings for backup.php.
$string['backup'] = 'Select Backup Courses';
$string['backup_button'] = 'Backup Selected Courses';

// Strings for results.php.
$string['search_results'] = 'Search Results';
$string['save_query'] = 'Save Query';
$string['create_new_query'] = 'Create New Query';

// Strings for delete.php.
$string['delete'] = 'Delete?';
$string['delete_header'] = 'Completed Backups';
$string['deleted'] = 'Successfully deleted {$a}';
$string['delete_error'] = ', but there may have been an error, please check';
$string['none_completed'] = 'There are no completed backups at this time';
$string['delete_button'] = 'Delete Selected Courses';

// Strings for delete_queries.php.
$string['delete_queries_header'] = 'Delete Saved Queries';
$string['delete_queries_button'] = 'Delete Selected Queries';
$string['delete_queries_success'] = '{$a} successful query deletion(s)';

// Strings for send_job.php.
$string['job_sent'] = 'Backup Job Sent';
$string['job_sent_body'] = 'Your backup job will start during the next cron run. ' .
    'You will receive an email when all backups are complete.';
$string['already_successful'] = ' was not scheduled for backup because was ' .
    'already successfully backed up but never deleted.';
$string['already_scheduled'] = ' was not scheduled for backup because it is ' .
    'already scheduled for backup.';
$string['already_failed'] = ' was not scheduled for backup because it is an ' .
    'unresolved failure. Please fix this.';

// String for failed.php.
$string['failed_header'] = 'Failed Backups';
$string['none_failed'] = 'There are no failed backups at this time';
$string['failed_button'] = 'Reschedule Selected Backups';
$string['failed'] = 'Failed?';
$string['statuses_updated'] = 'Selected courses have been rescheduled for backup';

// Strings for settings.php.
$string['config_path'] = 'Storage Path';
$string['config_path_desc'] = 'Relative to {$a}, include the surrounding slashes.
    Ensure that this directory is created and writable.';
$string['config_pattern'] = 'Archive suffix';
$string['config_pattern_desc'] = 'Data that will be appended onto backup names';
$string['config_roles'] = 'Roles';
$string['config_roles_desc'] = 'Roles to include when naming backup files';
$string['config_size_limit'] = 'Size limit before warning';
$string['config_size_limit_desc'] = 'In megabytes';
$string['path_error'] = 'Error: Please ensure that the path you provided is a ' .
    'writable directory';
$string['sched_config'] = 'Access scheduled backup settings as
    (' . $string['pluginname'] . ') uses these settings.';
$string['here'] = 'here';
$string['config_path_not_exists'] = 'The path you have entered does not exists.';
$string['config_path_not_writable'] = 'The path you have entered is not writable.';
$string['config_path_surround'] = 'Surround the path with slashes.';

// Strings for email.
$string['email_subject'] = 'Backup Job Completed';
$string['email_from'] = 'noreply@lsu.edu';
$string['email_body']  = "The Backup And Delete tool has completed the jobs in it's queue.";

// Capabilities.
$string['usp_mcrs:addinstance'] = 'Add '.$string['pluginname'].' block.';

$string['settings:allowcapabilitychange'] = 'Allow overwriting default capability';
$string['settings:allowcapabilitychange_description'] = 'If checked, capability <i>moodle/restore:restorecourse</i> will be granted to general authorized users.';
$string['settings:autoenableguestenrol'] = 'Auto enable guest enrolment';
$string['settings:autoenableguestenrol_description'] = 'Enable guest enrolment method automatically after course is uploaded';
$string['settings:maxfilesize'] = 'Maximum course file size (MB)';
$string['settings:maxfilesize_description'] = 'Maximum course backup size per file in megabytes (MB)<br><small>*Actual maximum upload size might be limited by server settings in <i>php.ini</i> file.</small>';
$string['settings:defaultcategory'] = 'Default category';
$string['settings:defaultcategory_description'] = 'Default category of newly uploaded course';
$string['settings:autocreateinfoblock'] = 'Create course info block after uploaded';
$string['settings:autocreateinfoblock_decription'] = 'Automatically create a hub course info block instance to uploaded course.';

$string['error_filenotuploaded'] = 'There is no file uploaded.';
$string['error_cannotsaveuploadfile'] = 'Cannot read upload file.';
$string['error_backupisnotcourse'] = 'Backup file is not a course backup.';
$string['error_cannotextractfile'] = 'Cannot extract file.';
$string['error_cannotgetroleinfo'] = 'Cannot get role <i>block_hubcourseupload</i>, please manually create this role with given short name having permission <i>moodle:restore/restorecourse</i>.';
$string['error_cannotrestore'] = 'Cannot perform restore execution.';
$string['error_categorynotfound'] = 'Category not found';

$string['uploadcoursetohub'] = 'Upload Your Course to Hub';

$string['coursefilechoose'] = 'Open file browser…';
$string['draganddrop'] = 'Or you can also drag and drop your <i>.mbz</i> file here…';
$string['nocapability'] = 'You are not allowed to upload file to hub.';
$string['nosignin'] = 'Please sign in to upload your course to hub.';
$string['uploaddescription'] = 'Supported file format: .mbz';
$string['maxfilesize'] = 'Maximum file size: {$a}MB';
$string['pleasewait'] = 'Please wait…';

$string['continueupload'] = 'Continue Upload';

$string['proceedanyway'] = 'Proceed Anyway';

$string['warning_moodleversion'] = '<p><strong>Warning!</strong> Course from your file is originally from newer Moodle version, the demo course on this site might not function correctly.
<br>Do you want to continue?</p>
<p><strong>Your Course Moodle Version:</strong> <span class="text-success">{$a->original}</span><br>
<strong>Moodle Version on this Site:</strong> <span class="text-danger">{$a->current}</span></p>';

$string['warning_pluginversion'] = '<strong>Warning!</strong> Some plugins in your course do not match with the ones in this site. This might causes your course to function improperly in current site.
<br>Please check list below.';
$string['requiredplugin_name'] = 'Plugin Name';
$string['requiredplugin_courseversion'] = 'Version from your course';
$string['requiredplugin_siteversion'] = 'Version in this site';
$string['requiredplugin_status'] = 'Status';
$string['requiredplugin_notinstalled'] = 'Not installed in this site';
$string['requiredplugin_identical'] = 'Identical';
$string['requiredplugin_siteolder'] = 'This site has an older version';
$string['requiredplugin_sitenewer'] = 'This site has a newer version';

$string['initialversion'] = 'Initial version';
