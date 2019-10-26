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
 * Functions libraries
 *
 * @package block_usp_mcrs
 * @copyright   2019 IS314 Group 4 <you@example.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Step of extracting file information
const BLOCK_USP_MCRS_STEP_PREPARE = 0;

// Step of when there is no site version difference, or the difference has been accepted
const BLOCK_USP_MCRS_STEP_VERSIONCONFIRMED = 1;

// Step of when there is no plugin version difference, or the difference has been accepted
const BLOCK_USP_MCRS_STEP_PLUGINCONFIRMED = 2;

/**
 * Get maximum file size
 * @return float|int
 * @throws dml_exception
 */
function block_usp_mcrs_getmaxfilesize() {
    $generalmaximum = get_max_upload_file_size();

    if (block_usp_mcrs_infoblockenabled()) {
        $infosettings = get_config('block_usp_mcrs', 'maxfilesize') * 1024 * 1024;

        if ($infosettings > 0) {
            return $generalmaximum < $infosettings ? $generalmaximum : $infosettings;
        }
    }

    return $generalmaximum;
}

/**
 * Get role ID
 * @return int|null
 * @throws coding_exception
 * @throws dml_exception
 */
function block_usp_mcrs_getroleid() {
    global $DB;

    if (!get_config('block_usp_mcrs', 'allowcapabilitychange')) {
        return null;
    }

    $role = $DB->get_record('role', ['shortname' => 'usp_mcrs_user']);
    if (!$role) {
        return create_role('Course Uploader', 'usp_mcrs_user', 'User for hub course upload');
    }

    return $role->id;
}

/**
 * Get plugins information from extracted path
 * @param string $extractedpath
 * @return array
 */
function block_usp_mcrs_getplugins($extractedpath) {
    $result = [
        'mod' => [],
        'blocks' => []
    ];

    $moddirs = block_usp_mcrs_getsubdirectories($extractedpath . '/activities');
    foreach ($moddirs as $moddir) {
        $modpath = $moddir . '/module.xml';
        $xml = simplexml_load_file($modpath);
        if (!$xml || !isset($xml->modulename)) {
            continue;
        }
        $modname = (string)$xml->modulename;
        $version = isset($xml['version']) ? (double)$xml['version'] : 0;

        if (!isset($result['mod'][$modname])) {
            $result['mod'][$modname] = $version;
        }
    }

    $blockdirs = block_usp_mcrs_getsubdirectories($extractedpath . '/course/blocks');
    foreach ($blockdirs as $blockdir) {
        $blockpath = $blockdir . '/block.xml';
        $xml = simplexml_load_file($blockpath);
        if (!$xml || !isset($xml->blockname)) {
            continue;
        }
        $blockname = (string)$xml->blockname;
        $version = isset($xml['version']) ? (double)$xml['version'] : 0;

        if (!isset($result['blocks'][$blockname])) {
            $result['blocks'][$blockname] = $version;
        }
    }

    return $result;
}

/**
 * Check if all plugin dependencies indicated in mbz file is valid in this site
 * @param array $plugins
 * @return bool
 */
function block_usp_mcrs_valid($plugins) {
    $installedmods = core_plugin_manager::instance()->get_plugins_of_type('mod');
    foreach ($plugins['mod'] as $modname => $version) {
        if (!isset($installedmods[$modname]) || $installedmods[$modname]->versiondb != $version) {
            return false;
        }
    }

    $installedblocks = core_plugin_manager::instance()->get_plugins_of_type('block');
    foreach ($plugins['blocks'] as $blockname => $version) {
        if (!isset($installedblocks[$blockname]) || $installedblocks[$blockname]->versiondb != $version) {
            return false;
        }
    }

    return true;
}

/**
 * Reduce info object by removing unnecessary
 * @param stdClass $info
 * @return stdClass
 */
function block_usp_mcrs_reduceinfo($info) {
    $newinfo = new stdClass();
    $newinfo->type = $info->type;
    $newinfo->moodle_version = $info->moodle_version;
    $newinfo->moodle_release = $info->moodle_release;
    $newinfo->original_wwwroot = $info->original_wwwroot;
    $newinfo->original_course_id = $info->original_course_id;

    return $newinfo;
}

function block_usp_mcrs_getbackupdir() {
    global $CFG;
    $backupdir = $CFG->tempdir . '/backup';

    if (!check_dir_exists($backupdir) && !mkdir($backupdir)) {
        return null;
    }
    return $backupdir;
}

/**
 * Get backup path
 * @param string $filename
 * @return string
 */
function block_usp_mcrs_getbackuppath($filename) {
    global $CFG;
    $backupdir = block_usp_mcrs_getbackupdir();
    return $backupdir ? "{$backupdir}/{$filename}" : "{$CFG->tempdir}/backup_{$filename}";
}
