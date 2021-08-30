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
 * @created    17/05/17 21:02
 * @package    local_b13_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_b13_dashboard\events;

defined('MOODLE_INTERNAL') || die();

use local_b13_dashboard\output\events\send_events;
use local_b13_dashboard\vo\b13_dashboard_events;

/**
 * Class event_observers
 *
 * @package local_b13_dashboard\events
 */
class event_observers {
    /**
     * @param \core\event\base $event
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function process_event(\core\event\base $event) {
        global $DB;

        if ($event->get_data()['action'] == 'viewed') {
            return;
        }

        $eventname = str_replace('\\\\', '\\', $event->eventname);

        $b13eventss = $DB->get_records('b13_dashboard_events',
            array(
                'event' => $eventname,
                'status' => 1
            ));

        /** @var b13_dashboard_events $b13events */
        foreach ($b13eventss as $b13events) {
            $sendevents = new send_events();
            $sendevents->set_event($event);
            $sendevents->set_b13_dashboard_events($b13events);

            $sendevents->send();
        }
    }
}