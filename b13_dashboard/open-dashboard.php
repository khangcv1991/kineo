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

ob_start();
define('AJAX_SCRIPT', false);
define('OPEN_INTERNAL', false);

define('BENCHSTART', microtime(true));
require('../../config.php');
define('BENCHSTOP', microtime(true));

require_once('autoload.php');

global $PAGE, $CFG, $OUTPUT;

require_login();
require_capability('local/b13_dashboard:view', context_system::instance());
require_capability('local/b13_dashboard:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/b13_dashboard/open-dashboard.php'));
$PAGE->set_pagetype('reports');
$PAGE->set_context(context_system::instance());

$action = optional_param('action', null, PARAM_RAW);
if (!empty($action) && strpos($action, '::')) {
    load_class();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo get_string_b13('pluginname') ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link href="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/all-frame.css" rel="stylesheet">
    <link async href="https://fonts.googleapis.com/css?family=Nunito:300,400" rel="stylesheet">

    <script>
        lang_yes = '<?php echo get_string('yes') ?>';
        lang_no = '<?php echo get_string('no') ?>';
        lang_visible = '<?php echo get_string_b13('courses_visible')?>';
        lang_invisible = '<?php echo get_string_b13('courses_invisible')?>';
        lang_active = '<?php echo get_string_b13('notification_status_active')?>';
        lang_inactive = '<?php echo get_string_b13('notification_status_inactive')?>';
        dataTables_oLanguage = {
            sEmptyTable       : "<?php echo get_string_b13('datatables_sEmptyTable') ?>",
            sInfo             : "<?php echo get_string_b13('datatables_sInfo') ?>",
            sInfoEmpty        : "<?php echo get_string_b13('datatables_sInfoEmpty') ?>",
            sInfoFiltered     : "<?php echo get_string_b13('datatables_sInfoFiltered') ?>",
            sInfoPostFix      : "<?php echo get_string_b13('datatables_sInfoPostFix') ?>",
            sInfoThousands    : "<?php echo get_string_b13('datatables_sInfoThousands') ?>",
            sLengthMenu       : "<?php echo get_string_b13('datatables_sLengthMenu') ?>",
            sLoadingRecords   : "<?php echo get_string_b13('datatables_sLoadingRecords') ?>",
            sProcessing       : "<?php echo get_string_b13('datatables_sProcessing') ?>",
            sZeroRecords      : "<?php echo get_string_b13('datatables_sZeroRecords') ?>",
            sSearch           : "<?php echo get_string_b13('datatables_sSearch') ?>",
            oPaginate         : {
                sNext       : "<?php echo get_string_b13('datatables_oPaginate_sNext') ?>",
                sPrevious   : "<?php echo get_string_b13('datatables_oPaginate_sPrevious') ?>",
                sFirst      : "<?php echo get_string_b13('datatables_oPaginate_sFirst') ?>",
                sLast       : "<?php echo get_string_b13('datatables_oPaginate_sLast') ?>"
            },
            oAria             : {
                sSortAscending    : "<?php echo get_string_b13('datatables_oAria_sSortAscending') ?>",
                sSortDescending   : "<?php echo get_string_b13('datatables_oAria_sSortDescending') ?>"
            }
        }
    </script>

    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/bootstrap/bootstrap.min.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dataTables/jquery.dataTables.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dataTables/sorting-numeric-comma.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dataTables/sorting-currency.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dataTables/sorting-date-uk.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dataTables/sorting-file-size.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dataTables/renderer-b13-v2.min.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/moment.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/daterangepicker.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/select2.full.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/validator.min.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/jquery.maskedinput.min.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/jquery.validate-v1.15.0.min.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/js/custom.min.js"></script>

    <?php
    if (get_config('local_b13_dashboard', 'nodejs-status')) {
        echo "<script src=\"<?php echo $CFG->wwwroot ?>/local/b13_dashboard/node/socket.io.js\"></script>
              <script src=\"<?php echo $CFG->wwwroot ?>/local/b13_dashboard/node/app-v2.min.js\"></script>";

        if (get_config('local_b13_dashboard', 'nodejs-ssl')) {
            $url = "https://" . get_config('local_b13_dashboard', 'nodejs-url') . ':' .
                get_config('local_b13_dashboard', 'nodejs-port');
        } else {
            $url = get_config('local_b13_dashboard', 'nodejs-url') . ':' .
                get_config('local_b13_dashboard', 'nodejs-port');
        }

        $userid = intval($USER->id);
        $fullname = '"' . fullname($USER) . '"';
        $servertime = time();
        $urlnode = '"' . $url . '"';

        echo "<script type=\"text/javascript\">
                  startServer ( $userid, $fullname, $servertime, $urlnode, 'z35admin' );
              </script>";
    }
    ?>
    <link rel="icon" href="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/img/favicon.png"/>

</head>
<body class="b13_dashboard_body">
<script>
    if (window != window.top) {
        document.body.className += " in-iframe";
    }
</script>
<div class="all-wrapper">

    <div class="layout-w">

        <div class="menu-w hidden-print">
            <div class="logo-w">
                <img class="normal"
                     src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/img/logo.svg"
                     alt="<?php echo get_string_b13('pluginname') ?>">
                <img class="mobile"
                     src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/img/logo-notext.svg"
                     alt="<?php echo get_string_b13('pluginname') ?>">
            </div>
            <div class="menu-and-user">
                <?php
                echo \local_b13_dashboard\output\menu::create_menu();
                ?>
            </div>
        </div>

        <div class="content-w <?php echo get_path_query() ?>">
            <?php
            load_class();
            ?>
        </div>

    </div>
</div>

<div class="modal fade" id="modal-edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loader"></div>
        </div>
    </div>
</div>

</body>
</html>