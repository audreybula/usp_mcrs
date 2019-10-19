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
 * Definition of block_usp_mcrs tasks.
 *
 * @package    block_usp_mcrs
 * @category   task
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

require_login();

// Ensure only site admins can use the Backup and Delete system.
if (!is_siteadmin($USER->id)) {
    print_error('need_permission', 'block_usp_mcrs');
}

// Page Setup.
$blockname = get_string('pluginname', 'block_usp_mcrs');
$header = get_string('job_sent', 'block_usp_mcrs');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->navbar->add($header);
$PAGE->set_title($blockname);
$PAGE->set_heading($SITE->shortname . ': ' . $blockname);
$PAGE->set_url('/blocks/usp_mcrs/backup.php');

// Begin outputting the page.
echo $OUTPUT->header();
echo $OUTPUT->heading($header);

// Set up the courses to back up.
//$backupids = required_param_array('backup', PARAM_INT);
$backupids = $_SESSION['courseid'];

// Check for duplicates.
$currentids = $DB->get_fieldset_select('block_usp_mcrs_statuses', 'coursesid', '');
$dupes = array_intersect($currentids, $backupids);
$dupes = !$dupes ? array() : $dupes;

// Remove the duplicates.
$newbackupids = array_diff($backupids, $dupes);

// Insert the records into the DB for courses to back up.
foreach ($newbackupids as $id) {
    $status = new StdClass;
    $status->coursesid = $id;
    $status->status = 'BACKUP';
    $DB->insert_record('block_usp_mcrs_statuses', $status);
}

// Let the admin know that the job has been sent and will be run.
echo '<br />' . get_string('job_sent_body', 'block_usp_mcrs');

// If the user is trying to backup duplicate courses....
if ($dupes) {
    echo '<div style = "text-align:center" class = "error">';
    $select = 'coursesid IN(' . implode(', ', $dupes) . ')';
    $statuses = $DB->get_records_select('block_usp_mcrs_statuses', $select);
    $statusmap = array(
        'SUCCESS' => get_string('already_successful', 'block_usp_mcrs'),
        'BACKUP' => get_string('already_scheduled', 'block_usp_mcrs'),
        'FAIL' => get_string('already_failed', 'block_usp_mcrs')
    );

    foreach ($statuses as $s) {
        $params = array('id' => $s->coursesid);
        $shortname = $DB->get_field('course', 'shortname', $params);
        echo $shortname . ' ' . $statusmap[$s->status] . '<br />';
    }
    echo '</div>';
}

echo $OUTPUT->footer();
