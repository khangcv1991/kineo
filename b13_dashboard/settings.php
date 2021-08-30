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
 * @created    23/05/17 17:59
 * @package    local_b13_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if (!$PAGE->requires->is_head_done()) {
    $PAGE->requires->css('/local/b13_dashboard/assets/style.css');
}

if ($hassiteconfig) {
    $settings = new admin_settingpage('b13_dashboard', get_string('pluginname', 'local_b13_dashboard'));
    $ADMIN->add('localplugins', $settings);

    if (!$ADMIN->locate('integracaoroot')) {
        $ADMIN->add('root', new admin_category('integracaoroot', get_string('integracaoroot', 'local_b13_dashboard')));
    }

    $ADMIN->add('integracaoroot',
        new admin_externalpage(
            'local_b13_dashboard',
            get_string('modulename', 'local_b13_dashboard'),
            $CFG->wwwroot . '/local/b13_dashboard/open.php?classname=dashboard&method=start'
        )
    );
}

if ($ADMIN->fulltree) {

    $open_itens = array(
        'internal' => get_string('b13_dashboard_open_internal', 'local_b13_dashboard'),
        'popup'    => get_string('b13_dashboard_open_popup', 'local_b13_dashboard'),
        '_top'     => get_string('b13_dashboard_open_top', 'local_b13_dashboard'),
        '_blank'   => get_string('b13_dashboard_open_blank', 'local_b13_dashboard'),
    );

    $settings->add(
        new admin_setting_configselect('b13_dashboard_open',
            get_string('b13_dashboard_open', 'local_b13_dashboard'),
            get_string('b13_dashboard_open_desc', 'local_b13_dashboard'),
            'internal',
            $open_itens
        )
    );
}