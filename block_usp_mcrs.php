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

defined('MOODLE_INTERNAL') || die();

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
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {
        global $OUTPUT;
        if ($this->content !== null) 
        {
            return $this->content;
        }

        if (empty($this->instance)) 
        {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        
        // Checking permissions - Admin
        if (has_capability('moodle/site:config', context_system::instance())) 
        {
            $icon = $OUTPUT->pix_icon('i/settings', '');
            $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/mcrs_admin.php', null), $icon . get_string('mcrs_admin', 'block_usp_mcrs'));
        }

        if (has_capability('moodle/site:config', context_system::instance())) 
        {
            $icon = $OUTPUT->pix_icon('i/settings', '');
            $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/mcrs_cfl_admin.php', null), $icon . get_string('mcrs_cfl_admin', 'block_usp_mcrs'));
        }

        $cparam = array();
        $icon = $OUTPUT->pix_icon('i/edit', '');
        $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/requestcourse.php', $cparam), $icon . get_string('requestcourse', 'block_usp_mcrs'));
        
        if (has_capability('moodle/site:config', context_system::instance())) 
        {
            $icon = $OUTPUT->pix_icon('i/email', '');
            $this->content->items[] = html_writer::link(new moodle_url('/blocks/usp_mcrs/configemail.php', null), $icon . get_string('configemail', 'block_usp_mcrs'));
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediatly after init().
     */
    public function specialization()
    {
        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) 
        {
            $this->title = get_string('pluginname', 'block_usp_mcrs');
        } 
        else 
        {
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