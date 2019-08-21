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
 * Plugin strings are defined here.
 *
 * @package     block_usp_mcrs
 * @category    string
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $CFG, $USER, $DB;


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('adminhome', 'block_usp_mcrs'), new moodle_url('/blocks/usp_mcrs/admin.php'));

$PAGE->set_url('/blocks/usp_mcrs/admin.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('adminhome', 'block_usp_mcrs'));
$PAGE->set_title(get_string('adminhome', 'block_usp_mcrs'));
echo $OUTPUT->header();

class admin extends moodleform
{
    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('adminhome', 'block_usp_mcrs');
    }

        /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {
       

        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

       $text = '';
       $text .= "Admin Home";
       $this->content->text = $text;

        return $this->content;
    }
}
