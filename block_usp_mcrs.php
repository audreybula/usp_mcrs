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

defined('MOODLE_INTERNAL') || die();
// Get the requisite dependencies.
require_once($CFG->dirroot . '/blocks/usp_mcrs/lib.php');
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
require_once($CFG->dirroot . '/blocks/usp_mcrs/classes/courseupload_form.php');
/**
 * Main class for setting up the block.
 * @uses block_list
 * @package block_usp_mcrs
 */
class block_usp_mcrs extends block_list {
    /**
     * Init.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_usp_mcrs');
    }
    /**
     * Locations where block can be displayed.
     *
     * @return array
     */
    public function applicable_formats() {
        return array('site' => true, 'my' => true, 'course' => false);
    }
    /**
     * Block has configuration.
     *
     * @return true
     */
    public function has_config() {
        return true;
    }
    /**
     * Returns the contents.
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        // Set up the globals we need.
        global $DB, $CFG, $USER, $OUTPUT;
        $context = context_system::instance();
        // Check to make sure the Admin is using the block.
        if (!is_siteadmin($USER->id)) {
            return $this->content;
        }
        // Return the content if there is any.
        if ($this->content !== null) {
            return $this->content;
        }
        // Set up the table.
        $table = 'block_usp_mcrs_statuses';
        // Get the number of pending and failed backups.
        $numpending = $DB->count_records_select($table, "status='SUCCESS'");
        $numfailed = $DB->count_records_select($table, "status='FAIL'");
        // Set the $running varuable to the backup status.
        $running = get_config('block_usp_mcrs', 'running');
        // Give the admin the running / not status.
        if (!$running) {
            $statustext = get_string('status_not_running', 'block_usp_mcrs');
        } else {
            $minutesrun = round((time() - $running) / 60);
            $statustext = get_string('status_running', 'block_usp_mcrs', $minutesrun);
        }
        // Build the block itself.
        $icons = array();
        $items = array();
        $params = array('class' => 'icon');



        // Build the icon list.
        $icons[] = $OUTPUT->pix_icon('i/edit', '', 'moodle', $params);
        // Build the list of items.
        $items[] = $this->build_link('requestcourse');



            if (has_capability('block/usp_mcrs:approverequest', $context)) {
                $icons[] = $OUTPUT->pix_icon('i/settings', '', 'moodle', $params);
                /*  $icons[] = $OUTPUT->pix_icon('i/backup', '', 'moodle', $params); */
                // Build the list of items.
                $items[] = $this->build_link('mcrs_admin');
            }
        


        $icons[] = $OUTPUT->pix_icon('i/delete', '', 'moodle', $params);
        // Build the list of items.
        /* $items[] = $this->build_link('index'); */
        $items[] = $this->build_link('delete') . "($numpending)";
        /* $icons[] = $OUTPUT->pix_icon('i/risk_xss', '', 'moodle', $params); */



            if (has_capability('block/usp_mcrs:approverequest', $context)) {
                $icons[] = $OUTPUT->pix_icon('i/email', '', 'moodle', $params);
                $items[] = $this->build_link('configemail');
                /* $icons[] = $OUTPUT->pix_icon('i/calendareventtime', '', 'moodle', $params); */
            }

        /* $items[] = $this->build_link('failed') . "($numfailed)"; */

        /* $items[] = $statustext; */
        // Bring it all together.

        $this->page->requires->jquery();
        $this->page->requires->js(new moodle_url('/blocks/usp_mcrs/script.js'));
        $this->page->requires->strings_for_js(['coursefilechoose', 'draganddrop', 'pleasewait'], 'block_usp_mcrs');

        $uploader = new courseupload_form(new moodle_url('/blocks/usp_mcrs/restore.php'));

        $html = $uploader->render();

        $this->content = new stdClass;
        $this->content->icons = $icons;
        $this->content->items = $items;
        $this->content->footer = '';
        // Return the block.
        return $this->content;
    }
    /**
     * Set up the page link
     *
     * @return link
     */
    public function build_link($page) {
        $url = new moodle_url("/blocks/usp_mcrs/$page.php");
        return html_writer::link($url, get_string("block_$page", 'block_usp_mcrs'));
    }
}
