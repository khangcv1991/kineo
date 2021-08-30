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
 * @created    16/11/18 02:01
 * @package    local_b13_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

ob_start();
define('AJAX_SCRIPT', false);
define('OPEN_INTERNAL', true);

require('../../config.php');
require('autoload.php');

global $PAGE, $CFG, $OUTPUT;

require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('local/b13_dashboard:view', context_system::instance());
require_capability('local/b13_dashboard:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/b13_dashboard/open-internal.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagetype('admin-setting');
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string_b13('modulename'));
$PAGE->set_heading(get_string_b13('modulename'));

$PAGE->requires->jquery();
$PAGE->requires->css('/local/b13_dashboard/assets/style.css');
$PAGE->requires->css('/local/b13_dashboard/assets/all-internal.css');

$PAGE->requires->jquery();

$PAGE->requires->js_amd_inline("
    lang_yes = '" . get_string('yes') . "';
    lang_no = '" . get_string('no') . "';
    lang_visible = '" . get_string_b13('courses_visible') . "';
    lang_invisible = '" . get_string_b13('courses_invisible') . "';
    lang_active = '" . get_string_b13('notification_status_active') . "';
    lang_inactive = '" . get_string_b13('notification_status_inactive') . "';
    dataTables_oLanguage = {
        sEmptyTable       : '" . get_string_b13('datatables_sEmptyTable') . "',
        sInfo             : '" . get_string_b13('datatables_sInfo') . "',
        sInfoEmpty        : '" . get_string_b13('datatables_sInfoEmpty') . "',
        sInfoFiltered     : '" . get_string_b13('datatables_sInfoFiltered') . "',
        sInfoPostFix      : '" . get_string_b13('datatables_sInfoPostFix') . "',
        sInfoThousands    : '" . get_string_b13('datatables_sInfoThousands') . "',
        sLengthMenu       : '" . get_string_b13('datatables_sLengthMenu') . "',
        sLoadingRecords   : '" . get_string_b13('datatables_sLoadingRecords') . "',
        sProcessing       : '" . get_string_b13('datatables_sProcessing') . "',
        sZeroRecords      : '" . get_string_b13('datatables_sZeroRecords') . "',
        sSearch           : '" . get_string_b13('datatables_sSearch') . "',
        oPaginate         : {
            sNext       : '" . get_string_b13('datatables_oPaginate_sNext') . "',
            sPrevious   : '" . get_string_b13('datatables_oPaginate_sPrevious') . "',
            sFirst      : '" . get_string_b13('datatables_oPaginate_sFirst') . "',
            sLast       : '" . get_string_b13('datatables_oPaginate_sLast') . "'
        },
        oAria             : {
            sSortAscending    : '" . get_string_b13('datatables_oAria_sSortAscending') . "',
            sSortDescending   : '" . get_string_b13('datatables_oAria_sSortDescending') . "'
        }
    }
");

require_once "{$CFG->dirroot}/theme/{$CFG->theme}/version.php";
$theme_boost = false;
if (isset($plugin->dependencies['theme_boost'])) {
    $theme_boost = true;
}

if ($theme_boost) {
    $dashboard_menu_html = \local_b13_dashboard\output\menu::create_menu();
} else {
    $dashboard_menu_html = "<div id=\"inst0\" class=\"block\">
            <!--div class=\"header dashboard_menu_html\">
                <div class=\"title\" >
                    <h2 id=\"instance-4-header\">" . get_string_b13('modulename') . "</h2>
                </div>
            </div-->
            <div class=\"dashboard_menu_html logo\">
                <div class=\"logo-w\">
                    <img class=\"normal\"
                         src=\"{$CFG->wwwroot}/local/b13_dashboard/assets/dashboard/img/logo-notext.svg\"
                         alt=\"{get_string_b13('pluginname')}\">
                </div>
            </div>
            <div class=\"content\">
                " . \local_b13_dashboard\output\menu::create_menu() . "
            </div>
        </div>";
}
$dashboard_menu_html = str_replace("'", '"', $dashboard_menu_html);

$PAGE->navbar->add(get_string_b13('modulename'), new moodle_url('local/b13_dashboard/open-internal.php'));


load_class();
$htmlApp = ob_get_contents();
ob_clean();

echo $OUTPUT->header();
?>
    <script>
        lang_yes = '<?php echo get_string('yes') ?>';
        lang_no = '<?php echo get_string('no') ?>';
        lang_visible = '<?php echo get_string_b13('courses_visible')?>';
        lang_invisible = '<?php echo get_string_b13('courses_invisible')?>';
        lang_active = '<?php echo get_string_b13('notification_status_active')?>';
        lang_inactive = '<?php echo get_string_b13('notification_status_inactive')?>';
        dataTables_oLanguage = {
            sEmptyTable     : "<?php echo get_string_b13('datatables_sEmptyTable') ?>",
            sInfo           : "<?php echo get_string_b13('datatables_sInfo') ?>",
            sInfoEmpty      : "<?php echo get_string_b13('datatables_sInfoEmpty') ?>",
            sInfoFiltered   : "<?php echo get_string_b13('datatables_sInfoFiltered') ?>",
            sInfoPostFix    : "<?php echo get_string_b13('datatables_sInfoPostFix') ?>",
            sInfoThousands  : "<?php echo get_string_b13('datatables_sInfoThousands') ?>",
            sLengthMenu     : "<?php echo get_string_b13('datatables_sLengthMenu') ?>",
            sLoadingRecords : "<?php echo get_string_b13('datatables_sLoadingRecords') ?>",
            sProcessing     : "<?php echo get_string_b13('datatables_sProcessing') ?>",
            sZeroRecords    : "<?php echo get_string_b13('datatables_sZeroRecords') ?>",
            sSearch         : "<?php echo get_string_b13('datatables_sSearch') ?>",
            oPaginate       : {
                sNext     : "<?php echo get_string_b13('datatables_oPaginate_sNext') ?>",
                sPrevious : "<?php echo get_string_b13('datatables_oPaginate_sPrevious') ?>",
                sFirst    : "<?php echo get_string_b13('datatables_oPaginate_sFirst') ?>",
                sLast     : "<?php echo get_string_b13('datatables_oPaginate_sLast') ?>"
            },
            oAria           : {
                sSortAscending  : "<?php echo get_string_b13('datatables_oAria_sSortAscending') ?>",
                sSortDescending : "<?php echo get_string_b13('datatables_oAria_sSortDescending') ?>"
            }
        }
    </script>

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

<?php if (get_config('local_b13_dashboard', 'nodejs-status')) { ?>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/node/socket.io.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/b13_dashboard/node/app-v2.min.js"></script>
    <?php
}
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
echo "<link rel=\"icon\" href=\"<?php echo $CFG->wwwroot ?>/local/b13_dashboard/assets/dashboard/img/favicon.png\"/>";


echo "<div id='b13_dashboard_div'>
         <div class=\"menu-w hidden-print dashboard_menu_html-content\">
            <div class=\"menu-and-user\">
                {$dashboard_menu_html}
            </div>
        </div>
        <div class='content-w'>
            {$htmlApp}
        </div>

        <div class=\"modal fade\" id=\"modal-edit\" role=\"dialog\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"loader\"></div>
                </div>
            </div>
        </div>
    </div>";

echo $OUTPUT->footer();

if (!$theme_boost) {
    $html = ob_get_contents();
    ob_clean();

    if (strpos($html, 'role="navigation"')) {
        $dashboard_menu_html .= "<style>.dashboard_menu_html-content{display:none !important}</style>";
        echo preg_replace('/(.*)(<div.*?class="block_navigation.*)/', "$1{$dashboard_menu_html}$2", $html);
    } else {
        echo $html;
    }
}
