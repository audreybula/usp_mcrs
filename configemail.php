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
require_once($CFG->dirroot.'/blocks/usp_mcrs/adminlib.php');


require_login();

global $CFG, $USER, $DB;


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
// todo: wrap around cap check
$PAGE->navbar->add(get_string('configemail', 'block_usp_mcrs'));
$PAGE->set_url('/blocks/usp_mcrs/configemail.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('configemail', 'block_usp_mcrs'));
$PAGE->set_title(get_string('configemail', 'block_usp_mcrs'));


$defineurl = $CFG->wwwroot . '/' . $CFG->admin . '/roles/define.php';

echo $OUTPUT->header();



echo $OUTPUT->footer();







