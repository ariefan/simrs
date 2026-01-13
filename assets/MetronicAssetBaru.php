<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MetronicAssetBaru extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // "metronic7/assets/vendors/general/tether/dist/css/tether.css",
        "metronic7/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css",
        "metronic7/assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css",
        "metronic7/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css",
        "metronic7/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css",
        "metronic7/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css",
        "metronic7/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css",
        "metronic7/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css",
        // "metronic7/assets/vendors/general/select2/dist/css/select2.css",
        "metronic7/assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css",
        "metronic7/assets/vendors/general/nouislider/distribute/nouislider.css",
        "metronic7/assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css",
        "metronic7/assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css",
        "metronic7/assets/vendors/general/dropzone/dist/dropzone.css",
        "metronic7/assets/vendors/general/summernote/dist/summernote.css",
        "metronic7/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css",
        "metronic7/assets/vendors/general/animate.css/animate.css",
        "metronic7/assets/vendors/general/toastr/build/toastr.css",
        "metronic7/assets/vendors/general/morris.js/morris.css",
        "metronic7/assets/vendors/general/sweetalert2/dist/sweetalert2.css",
        "metronic7/assets/vendors/general/socicon/css/socicon.css",
        "metronic7/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css",
        "metronic7/assets/vendors/custom/vendors/flaticon/flaticon.css",
        "metronic7/assets/vendors/custom/vendors/flaticon2/flaticon.css",
        "metronic7/assets/vendors/custom/vendors/fontawesome5/css/all.min.css",
        'metronic7/assets/demo/demo7/base/style.bundle.css',
        // 'metronic7/assets/media/logos/favicon.ico',
        'css/spinner.css',
        'css/custom_map.css',
    ];
    public $js = [
        // "metronic7/assets/vendors/general/jquery/dist/jquery.js",
        "metronic7/assets/vendors/general/popper.js/dist/umd/popper.js",
        // "metronic7/assets/vendors/general/bootstrap/dist/js/bootstrap.min.js",
        "metronic7/assets/vendors/general/js-cookie/src/js.cookie.js",
        "metronic7/assets/vendors/general/moment/min/moment.min.js",
        "metronic7/assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js",
        "metronic7/assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js",
        "metronic7/assets/vendors/general/sticky-js/dist/sticky.min.js",
        "metronic7/assets/vendors/general/wnumb/wNumb.js",
        "metronic7/assets/vendors/general/jquery-form/dist/jquery.form.min.js",
        "metronic7/assets/vendors/general/block-ui/jquery.blockUI.js",
        "metronic7/assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
        "metronic7/assets/vendors/custom/components/vendors/bootstrap-datepicker/init.js",
        "metronic7/assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js",
        "metronic7/assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
        "metronic7/assets/vendors/custom/components/vendors/bootstrap-timepicker/init.js",
        "metronic7/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js",
        "metronic7/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js",
        "metronic7/assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js",
        "metronic7/assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js",
        "metronic7/assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js",
        "metronic7/assets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js",
        "metronic7/assets/vendors/custom/components/vendors/bootstrap-switch/init.js",
        "metronic7/assets/vendors/general/select2/dist/js/select2.full.js",
        "metronic7/assets/vendors/general/ion-rangeslider/js/ion.rangeSlider.js",
        "metronic7/assets/vendors/general/typeahead.js/dist/typeahead.bundle.js",
        "metronic7/assets/vendors/general/handlebars/dist/handlebars.js",
        "metronic7/assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js",
        "metronic7/assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js",
        "metronic7/assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js",
        "metronic7/assets/vendors/general/nouislider/distribute/nouislider.js",
        "metronic7/assets/vendors/general/owl.carousel/dist/owl.carousel.js",
        "metronic7/assets/vendors/general/autosize/dist/autosize.js",
        "metronic7/assets/vendors/general/clipboard/dist/clipboard.min.js",
        "metronic7/assets/vendors/general/dropzone/dist/dropzone.js",
        "metronic7/assets/vendors/general/summernote/dist/summernote.js",
        "metronic7/assets/vendors/general/markdown/lib/markdown.js",
        "metronic7/assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js",
        "metronic7/assets/vendors/custom/components/vendors/bootstrap-markdown/init.js",
        "metronic7/assets/vendors/general/bootstrap-notify/bootstrap-notify.min.js",
        "metronic7/assets/vendors/custom/components/vendors/bootstrap-notify/init.js",
        "metronic7/assets/vendors/general/jquery-validation/dist/jquery.validate.js",
        "metronic7/assets/vendors/general/jquery-validation/dist/additional-methods.js",
        "metronic7/assets/vendors/custom/components/vendors/jquery-validation/init.js",
        "metronic7/assets/vendors/general/toastr/build/toastr.min.js",
        "metronic7/assets/vendors/general/raphael/raphael.js",
        "metronic7/assets/vendors/general/morris.js/morris.js",
        "metronic7/assets/vendors/general/chart.js/dist/Chart.bundle.js",
        "metronic7/assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js",
        "metronic7/assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js",
        "metronic7/assets/vendors/general/waypoints/lib/jquery.waypoints.js",
        "metronic7/assets/vendors/general/counterup/jquery.counterup.js",
        "metronic7/assets/vendors/general/es6-promise-polyfill/promise.min.js",
        "metronic7/assets/vendors/general/sweetalert2/dist/sweetalert2.min.js",
        "metronic7/assets/vendors/custom/components/vendors/sweetalert2/init.js",
        "metronic7/assets/vendors/general/jquery.repeater/src/lib.js",
        "metronic7/assets/vendors/general/jquery.repeater/src/jquery.input.js",
        "metronic7/assets/vendors/general/jquery.repeater/src/repeater.js",
        "metronic7/assets/vendors/general/dompurify/dist/purify.js",
        "metronic7/assets/demo/demo7/base/scripts.bundle.js",
        "metronic7/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js",
        "metronic7/assets/app/custom/general/dashboard.js",
        "metronic7/assets/app/bundle/app.bundle.js",
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
