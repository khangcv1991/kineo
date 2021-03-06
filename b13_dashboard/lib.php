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

defined ( 'MOODLE_INTERNAL' ) || die();

function local_b13_dashboard_extends_navigation ( global_navigation $nav )
{
    local_b13_dashboard_extend_navigation ( $nav );
}

function local_b13_dashboard_extend_navigation ( global_navigation $nav )
{
    global $CFG, $PAGE, $USER, $DB;

    try {
        $menus = $DB->get_records ( 'b13_dashboard_menu',null,'title ASC' );

        /** @var \local_b13_dashboard\vo\b13_dashboard_menu $menu */
        foreach ( $menus as $menu ) {

            $pages = $DB->get_records ( 'b13_dashboard_webpages', [ 'menuid' => $menu->id, 'visible' => 1 ] );

            if ( $pages ) {
                $node = $nav->add (
                    $menu->title,
                    new moodle_url( $CFG->wwwroot . '/local/b13_dashboard/?menu=' . $menu->link ),
                    navigation_node::TYPE_CUSTOM,
                    null,
                    'b13_dashboard-' . $menu->id,
                    new pix_icon( 'webpages', $menu->title, 'local_b13_dashboard' )
                );

                $node->showinflatnavigation = true;

                /** @var \local_b13_dashboard\vo\b13_dashboard_webpages $page */
                foreach ( $pages as $page ) {
                    $node->add (
                        $page->title,
                        new moodle_url( $CFG->wwwroot . '/local/b13_dashboard/?p=' . $page->link ),
                        navigation_node::TYPE_CUSTOM,
                        null,
                        'b13_dashboard-page-' . $page->id,
                        new pix_icon( 'webpages', $page->title, 'local_b13_dashboard' )
                    );
                }
            }
        }
    } catch ( Exception $e ) {
        // Se der problema, n??o precisa fazer nada
    }

    if ( isloggedin () ) {
        $context = context_system::instance ();
        if ( has_capability ( 'local/b13_dashboard:view', $context ) ||
            has_capability ( 'local/b13_dashboard:manage', $context ) ) {

            $node = $nav->add (
                get_string ( 'pluginname', 'local_b13_dashboard' ),
                new moodle_url( $CFG->wwwroot . '/local/b13_dashboard/open.php?classname=dashboard&method=start' ),
                navigation_node::TYPE_CUSTOM,
                null,
                'b13_dashboard',
                new pix_icon( 'icon', get_string ( 'pluginname', 'local_b13_dashboard' ), 'local_b13_dashboard' )
            );

            $node->showinflatnavigation = true;
        }

        if ( get_config ( 'local_b13_dashboard', 'nodejs-status' ) ) {

            $PAGE->requires->jquery ();
            $PAGE->requires->js ( new moodle_url( $CFG->wwwroot . '/local/b13_dashboard/node/socket.io.js' ), true );
            $PAGE->requires->js ( new moodle_url( $CFG->wwwroot . '/local/b13_dashboard/node/app-v2.js' ), true );

            if ( get_config ( 'local_b13_dashboard', 'nodejs-ssl' ) ) {
                $url = "https://" . get_config ( 'local_b13_dashboard', 'nodejs-url' ) . ':' .
                    get_config ( 'local_b13_dashboard', 'nodejs-port' );
            } else {
                $url = get_config ( 'local_b13_dashboard', 'nodejs-url' ) . ':' .
                    get_config ( 'local_b13_dashboard', 'nodejs-port' );
            }

            $userid     = intval ( $USER->id );
            $fullname   = '"' . fullname ( $USER ) . '"';
            $servertime = time ();
            $urlnode    = '"' . $url . '"';

            $PAGE->requires->js_init_code ( "startServer( $userid, $fullname, $servertime, $urlnode )" );
        }
    }
}
