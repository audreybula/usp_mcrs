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
 * Block usp_mcrs is defined here.
 *
 * @package     block_usp_mcrs
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/blocks/usp_mcrs/lib.php');
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');

class block_usp_mcrs extends block_list
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_usp_mcrs');
    }

    /**
     * Returns the contents.
     *
     * @return stdClass contents of block
     */
    public function get_content() 
    {
        // Set up the globals we need.
        global $DB, $CFG, $USER, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        /* $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $todotext = get_string('todo', 'block_usp_mcrs');
        $this->content->items[] = $todotext;

        // Checking permissions - Admin
        if (has_capability('moodle/site:config', context_system::instance())) {
            $icon = $OUTPUT->pix_icon('i/settings', '');
            $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/admin.php', null), $icon . get_string('adminhome', 'block_usp_mcrs'));
        }

        $cparam = array();
        $icon = $OUTPUT->pix_icon('i/edit', '');
        $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/requestcourse.php', $cparam), $icon . get_string('requestcourse', 'block_usp_mcrs'));

        $cparam = array();
        $icon = $OUTPUT->pix_icon('i/edit', '');
        $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/backup.php', $cparam), $icon . get_string('block_index', 'block_usp_mcrs'));

        $cparam = array();
        $icon = $OUTPUT->pix_icon('i/edit', '');
        $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/delete.php', $cparam), $icon . get_string('block_delete', 'block_usp_mcrs'));

        $cparam = array();
        $icon = $OUTPUT->pix_icon('i/edit', '');
        $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/failed.php', $cparam), $icon . get_string('block_failed', 'block_usp_mcrs'));

        if (has_capability('moodle/site:config', context_system::instance())) {
            $icon = $OUTPUT->pix_icon('i/email', '');
            $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/configemail.php', null), $icon . get_string('configemail', 'block_usp_mcrs'));
        } */

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
        $icons[] = $OUTPUT->pix_icon('i/backup', '', 'moodle', $params);
        $icons[] = $OUTPUT->pix_icon('i/delete', '', 'moodle', $params);
        $icons[] = $OUTPUT->pix_icon('i/risk_xss', '', 'moodle', $params);
        $icons[] = $OUTPUT->pix_icon('i/email', '', 'moodle', $params);
        $icons[] = $OUTPUT->pix_icon('i/calendareventtime', '', 'moodle', $params);

        // Build the list of items.
        $items[] = $this->build_link('requestcourse');
        $items[] = $this->build_link('index');
        $items[] = $this->build_link('delete') . "($numpending)";
        $items[] = $this->build_link('failed') . "($numfailed)";
        $items[] = $this->build_link('configemail');
        $items[] = $statustext;

        // Bring it all together.
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
    public function build_link($page) 
    {
        $url = new moodle_url("/blocks/usp_mcrs/$page.php");
        return html_writer::link($url, get_string("block_$page", 'block_usp_mcrs'));
    }
    
    /**
     * Defines configuration data.
     *
     * The function is called immediatly after init().
     */
    public function specialization()
    {
        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_usp_mcrs');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    function has_config()
    {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats()
    {
        return array(
            'all' => true,
        );
    }
}
