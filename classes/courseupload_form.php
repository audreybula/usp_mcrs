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
 * Class of form for uploading an mbz file
 *
 * @package block_usp_mcrs
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

/**
 * Class courseupload_form
 * @package block_usp_mcrs
 */
class courseupload_form extends moodleform {

    /**
     * Form definition
     * @throws coding_exception
     */
    public function definition() {
        $maxsize = block_usp_mcrs_getmaxfilesize();

        $form = &$this->_form;

        $form->addElement('filepicker', 'coursefile', '', null,
            array('maxbytes' => $maxsize, 'accepted_types' => '.mbz'));

        $form->addElement('html', get_string('maxfilesize', 'block_usp_mcrs', $maxsize / 1024 / 1024));

        $this->add_action_buttons(false, get_string('continueupload', 'block_usp_mcrs'));
    }
}