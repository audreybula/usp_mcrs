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
 * A scheduled task for usp_mcrs.
 *
 * @package    block_usp_mcrs
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_usp_mcrs\task;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/blocks/usp_mcrs/block_usp_mcrs.php');

/**
 * A scheduled task class for Backing up courses using the LSU usp_mcrs Block.
 */
class backup_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('backuptask', 'block_usp_mcrs');
    }

    /**
     * Run backups
     */
    public function execute() {
        global $CFG;
        begin_backup_task();
    }

}

/**
 * Set up the backup task
 */
function begin_backup_task() {
    global $DB, $CFG;

    mtrace('***********************************');
    mtrace('*****BEGIN BACKUP FOR USP_MCRS*****');
    mtrace('***********************************');
    
    // Grab the running status.
    $running = get_config('block_usp_mcrs', 'running');

    // If the task is running, let the user know how long it's been running.
    if ($running) {
        $minutesrun = round((time() - $running) / 60);
        echo "\n" . get_string('cron_already_running', 'block_usp_mcrs', $minutesrun) . "\n";
        return;
    }

    // Set up the params.
    $params = array('status' => 'BACKUP');

    // Return true for courses where status = BACKUP.
    if (!$backups = $DB->get_records('block_usp_mcrs_statuses', $params)) {
        return true;
    }

    $error = false;
    $errorlog = '';

    // Set the running status to now.
    set_config('running', time(), 'block_usp_mcrs');

    // Loop through the courses to get backed up.
    foreach ($backups as $b) {
        $course = $DB->get_record('course', array('id' => $b->coursesid));
        echo "\n" . get_string('backing_up', 'block_usp_mcrs') . ' ' . $course->shortname . "\n";

        // Log any failures.
        if (!usp_mcrs_backup_course($course)) {
            $error = true;
            $errorlog .= get_string('cron_backup_error', 'block_usp_mcrs', $course->shortname) . "\n";
        }

        // Convert the status to the acceptable FAIL / SUCCESS keyword.
        $b->status = $error ? 'FAIL' : 'SUCCESS';

        // Update the DB with the appropriate status
        $DB->update_record('block_usp_mcrs_statuses', $b);
    }

    // Clear the running flag
    set_config('running', '', 'block_usp_mcrs');

    // Email the admins about the backup status
    usp_mcrs_email_admins($errorlog);

    return true;
}
