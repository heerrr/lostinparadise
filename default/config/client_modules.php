<?php

return array(
    "lip-json2" => array(
        "js" => array("libs" . DS . "json2.js"),
    ),
    "lip-excanvas" => array(
        "js" => array("libs" . DS . "excanvas.min.js"),
    ),
    "lip-canvas-to-blob" => array(
        "js" => array("libs" . DS . "canvas-to-blob.min.js"),
    ),
    "lip-load-image" => array(
        "js" => array("libs" . DS . "load-image.min.js"),
    ),
    "lip-tmpl" => array(
        "js" => array("libs" . DS . "tmpl.min.js"),
    ),
    "lip-jquery-2.1.1" => array(
        "js" => array("libs/jquery-2.1.1.min.js"),
    ),
    "lip-bootstrap-switch" => array(
        "js" => array("plugins" . DS . "bootstrap-switch" . DS . "bootstrap-switch.js"),
        "css" => array("plugins" . DS . "bootstrap-switch" . DS . "bootstrap-switch.css"),
    ),
    "lip-font-awesome" => array(
        "css" => array("font-awesome.css"),
    ),
    "lip-font-awesome-4.5.0" => array(
        "css" => array("plugins/font-awesome/font-awesome 4.5.0.min.css"),
    ),
    "lip-jquery.ui" => array(
        "js" => array("libs" . DS . "jquery.ui.custom.js"),
        "css" => array("plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery-ui.css", "plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery.ui.theme.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-jquery-ui-1.12.1" => array(
        "js" => array("libs" . DS . "jquery-ui-1.12.1.min.js"),
        "css" => array("plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery-ui-1.12.1.min.css", "plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery-ui-1.12.1.theme.min.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-jquery-ui-1.12.1.custom" => array(
        "js" => array("libs" . DS . "jquery-ui-1.12.1.custom" . DS . "jquery-ui.min.js"),
        "css" => array(
            "plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery-ui-1.12.1.min.css", 
            "plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery-ui-1.12.1.theme.min.css",
            "plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery-ui.css",
            "plugins" . DS . "jquery-ui" . DS . "smoothness" . DS . "jquery.ui.theme.css"
        ),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-jquery.lazyload" => array(
        "js" => array("plugins" . DS . "lazyload" . DS . "jquery.lazyload.min.js" ),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-jquery.dialog2" => array(
        "js" => array("plugins" . DS . "dialog2" . DS . "jquery.dialog2.js", "plugins" . DS . "dialog2" . DS . "jquery.dialog2.helpers.js"),
        "css" => array("plugins" . DS . "dialog2" . DS . "jquery.dialog2.css"),
        "requirements" => array("lip-jquery-2.1.1", "lip-bootstrap-3.3.5"),
    ),
    "lip-jquery.datatable" => array(
        "js" => array(
            "plugins" . DS . "datatable" . DS . "jquery.dataTables.js",
            "plugins" . DS . "datatable" . DS . "TableTools.min.js",
            "plugins" . DS . "datatable" . DS . "ColReorder.min.js",
            "plugins" . DS . "datatable" . DS . "ColVis.min.js",
            "plugins" . DS . "datatable" . DS . "jquery.dataTables.columnFilter.js",
        ),
        "requirements" => array("lip-jquery-2.1.1", "lip-bootstrap-3.3.5"),
    ),
    "lip-jquery.datatable.tabletools" => array(
        "js" => array(
            "plugins" . DS . "datatable" . DS . "TableTools.min.js",
        ),
        "requirements" => array("lip-jquery.datatable"),
    ),
    "lip-jquery.datatable.colreorder" => array(
        "js" => array(
            "plugins" . DS . "datatable" . DS . "ColReorder.min.js",
        ),
        "requirements" => array("lip-jquery.datatable"),
    ),
    "lip-jquery.datatable.colvis" => array(
        "js" => array(
            "plugins" . DS . "datatable" . DS . "ColVis.min.js",
        ),
        "requirements" => array("lip-jquery.datatable"),
    ),
    "lip-jquery.datatable.columnfilter" => array(
        "js" => array(
            "plugins" . DS . "datatable" . DS . "jquery.dataTables.columnFilter.js",
        ),
        "requirements" => array("lip-jquery.datatable"),
    ),
    "lip-chosen" => array(
        "js" => array("plugins" . DS . "chosen" . DS . "chosen.jquery.min.js"),
        "css" => array("plugins" . DS . "chosen" . DS . "chosen.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-jquery.nestable" => array(
        "js" => array("plugins" . DS . "nestable" . DS . "jquery.nestable.js"),
        "css" => array("plugins" . DS . "nestable" . DS . "jquery.nestable.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-cresenity" => array(
        "js" => array(
            "cresenity.func.js",
            "cresenity.js",
            "cresenity.item_batch.js",
            "cresenity.pricing_detail.js",
        ),
        "css" => array(
            "cresenity.colors.css",
            "cresenity.main.css",
            "cresenity.responsive.css",
            "cresenity.pos.css",
            "cresenity.retail.css",
            "cresenity.widget.css",
            "cresenity.table.css",
            "cresenity.css",
        ),
    ),
    "lip-vkeyboard" => array(
        "js" => array("plugins" . DS . "vkeyboard" . DS . "bootstrap-vkeyboard.js"),
        "css" => array("plugins" . DS . "vkeyboard" . DS . "bootstrap-vkeyboard.css"),
    ),
    "lip-mockjax" => array(
        "js" => array("plugins" . DS . "mockjax" . DS . "jquery.mockjax.js"),
    ),
    "lip-jquery-autocomplete" => array(
        "js" => array("plugins" . DS . "jquery-autocomplete" . DS . "jquery-autocomplete.js"),
    ),
    "lip-fileupload" => array(
        "js" => array("plugins" . DS . "fileupload" . DS . "bootstrap-fileupload.min.js"),
    ),
    "lip-jquery-fileupload" => array(
        "js" => array(
            "plugins" . DS . "jquery-fileupload" . DS . "jquery.ui.widget.js",
            "plugins" . DS . "jquery-fileupload" . DS . "jquery.iframe-transport.js",
            "plugins" . DS . "jquery-fileupload" . DS . "jquery.fileupload.js",
        ),
    ),
    "lip-peity" => array(
        "js" => array("plugins" . DS . "peity" . DS . "jquery.peity.min.js"),
    ),
    "lip-flot" => array(
        "js" => array(
            "plugins" . DS . "flot" . DS . "jquery.flot.min.js",
            "plugins" . DS . "flot" . DS . "jquery.flot.bar.order.min.js",
            "plugins" . DS . "flot" . DS . "jquery.flot.pie.min.js",
            "plugins" . DS . "flot" . DS . "jquery.flot.resize.min.js",
            "plugins" . DS . "flot" . DS . "jquery.flot.stack.js",
        ),
    ),
    "lip-colorpicker" => array(
        "js" => array("plugins" . DS . "colorpicker" . DS . "bootstrap-colorpicker.js"),
        "css" => array("plugins" . DS . "colorpicker" . DS . "colorpicker.css"),
    ),
    "lip-wysihtml5" => array(
        "js" => array(
            "libs" . DS . "wysihtml5-0.3.0.js",
            "plugins" . DS . "wysihtml5" . DS . "bootstrap-wysihtml5.js",
        ),
        "css" => array("plugins" . DS . "wysihtml5" . DS . "bootstrap-wysihtml5.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-notify" => array(
        "js" => array("plugins" . DS . "notify" . DS . "bootstrap-notify.js"),
        "css" => array(
            "plugins" . DS . "notify" . DS . "bootstrap-notify.css",
            "plugins" . DS . "notify" . DS . "bootstrap-notify-alert-backgloss.css",
        ),
    ),
    "lip-bootbox" => array(
        "js" => array("plugins" . DS . "bootbox" . DS . "jquery.bootbox.js"),
    ),
    "lip-bootbox4.4.0" => array(
        "js" => array("plugins" . DS . "bootbox" . DS . "bootboxbootstrap3.min.js"),
    ),
    "lip-form" => array(
        "js" => array("plugins" . DS . "form" . DS . "jquery.form.js"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-controls" => array(
        "js" => array("plugins" . DS . "controls" . DS . "jquery.controls.js"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-event" => array(
        "js" => array(
            "plugins" . DS . "event" . DS . "jquery.event.move.js",
            "plugins" . DS . "event" . DS . "jquery.event.swipe.js"
        ),
    ),
    "lip-slimscroll" => array(
        "js" => array(
            "plugins" . DS . "slimscroll" . DS . "jquery.slimscroll.js",
            "plugins" . DS . "slimscroll" . DS . "jquery.slimscroll-horizontal.js",
        ),
    ),
    "lip-effects" => array(
        "js" => array(
            "plugins" . DS . "effects" . DS . "jquery.effects.core.js",
            "plugins" . DS . "effects" . DS . "jquery.effects.slide.js",
        ),
    ),
    "lip-validation" => array(
        "js" => array(
            "plugins" . DS . "validation-engine" . DS . "jquery.validationEngine-2.6.2.js",
            "plugins" . DS . "validation-engine" . DS . "languages" . DS . "jquery.validationEngine-en.js",
        ),
        "css" => array("plugins" . DS . "validation-engine" . DS . "jquery.validationEngine.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-ckeditor" => array(
        "js" => array("plugins" . DS . "ckeditor" . DS . "ckeditor.js"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-ckeditor-4" => array(
        "js" => array("plugins" . DS . "ckeditor" . DS . "4.5.9" . DS . "ckeditor.js"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-isotope" => array(
        "js" => array("plugins" . DS . "isotope" . DS . "jquery.isotope.min.js"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-easing" => array(
        "js" => array("plugins" . DS . "easing" . DS . "jquery-easing-1.3.js"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-plupload" => array(
        "js" => array(
            "plugins" . DS . "plupload" . DS . "plupload.full.js",
            "plugins" . DS . "plupload" . DS . "jquery.plupload.queue.js",
        ),
        "css" => array("plugins" . DS . "plupload" . DS . "jquery.plupload.queue.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-servertime" => array(
        "js" => array("plugins" . DS . "servertime" . DS . "jquery.servertime.js"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-uniform" => array(
        "js" => array("plugins" . DS . "uniform" . DS . "jquery-uniform.js"),
        "css" => array("plugins" . DS . "uniform" . DS . "jquery-uniform.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-select2_v4" => array(
        "js" => array("plugins" . DS . "select2" . DS . "select2_v4.js"),
        "css" => array("plugins" . DS . "select2" . DS . "select2_v4.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-select2" => array(
        "js" => array("plugins" . DS . "select2" . DS . "select2.js"),
        "css" => array("plugins" . DS . "select2" . DS . "select2.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-select2-4.0" => array(
        "js" => array("plugins" . DS . "select2" . DS . "select2.full.js"),
        "css" => array("plugins" . DS . "select2" . DS . "select2-4.0.0.min.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-datepicker" => array(
        "js" => array("plugins" . DS . "datepicker" . DS . "bootstrap-datepicker.js"),
        "css" => array("plugins" . DS . "datepicker" . DS . "datepicker.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-bootstrap3-datepicker" => array(
        "js" => array("plugins" . DS . "datepicker" . DS . "bootstrap3-datepicker.js"),
        "css" => array("plugins" . DS . "datepicker" . DS . "datepicker.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-timepicker" => array(
        "js" => array("plugins" . DS . "timepicker" . DS . "bootstrap-timepicker.min.js"),
        "css" => array("plugins" . DS . "timepicker" . DS . "bootstrap-timepicker.min.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-image-gallery" => array(
        "css" => array("plugins" . DS . "image-gallery" . DS . "bootstrap-image-gallery.min.css"),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-modernizr" => array(
        "js" => array("libs" . DS . "modernizr.custom.js"),
    ),
    "lip-multiselect" => array(
        "js" => array("plugins" . DS . "multiselect" . DS . "jquery.multi-select.js"),
        "css" => array("plugins" . DS . "multiselect" . DS . "multi-select.css"),
    ),
    "lip-terminal" => array(
        "js" => array(
            "plugins" . DS . "terminal" . DS . "jquery.mousewheel-min.js",
            "plugins" . DS . "terminal" . DS . "jquery.terminal-min.js",
        ),
    ),
    "lip-elfinder" => array(
        "js" => array("plugins" . DS . "elfinder" . DS . "elfinder.min.js"),
        "css" => array("plugins" . DS . "elfinder" . DS . "elfinder.min.css"),
    ),
    "lip-prettify" => array(
        "js" => array("plugins" . DS . "google-code-prettify" . DS . "prettify.js"),
        "css" => array("plugins" . DS . "google-code-prettify" . DS . "prettify.css"),
    ),
    "lip-jstree" => array(
        "js" => array("plugins" . DS . "jstree" . DS . "jstree.min.js"),
        "css" => array("plugins" . DS . "jstree" . DS . "style.min.css"),
    ),
    "lip-dropzone" => array(
        "js" => array("plugins" . DS . "dropzone" . DS . "dropzone.js"),
        "css" => array("plugins" . DS . "dropzone" . DS . "dropzone.css"),
    ),
    "lip-dhtmlxgantt" => array(
        "js" => array("plugins" . DS . "dhtmlxgantt" . DS . "dhtmlxgantt.js?v=1"),
//            "js" => array("http://docs.dhtmlx.com/gantt/codebase/dhtmlxgantt.js"),
        "css" => array("plugins" . DS . "dhtmlxgantt" . DS . "dhtmlxgantt.css"),
    ),
    "lip-bootstrap-3.3.5" => array(
        "css" => array(
            "plugins/bootstrap-3.3.5/bootstrap.min.css",
        ),
        "js" => array(
            "libs/bootstrap-3.3.5/bootstrap.js",
        ),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    
    "lip-bootstrap-dropdown" => array(
        "css" => array(
            "bootstrap-dropdown.css",
        ),
        "js" => array(
            "libs" . DS . "bootstrap" . DS . "bootstrap-dropdown.js"
        ),
        "requirements" => array("lip-jquery-2.1.1", "lip-bootstrap-3.3.5"),
    ),
    "lip-jquery.datatable-bootstrap3" => array(
        "css" => array(
            "plugins" . DS . "datatable" . DS . "dataTables.bootstrap.min.css",
            "plugins" . DS . "datatable" . DS . "responsive.bootstrap.min.css",
        ),
        "js" => array(
            "plugins" . DS . "datatable" . DS . "jquery.dataTables.js",
            "plugins" . DS . "datatable" . DS . "dataTables.bootstrap.js",
            "plugins" . DS . "datatable" . DS . "dataTables.responsive.2.0.2.min.js",
//                "plugins" . DS . "datatable" . DS . "TableTools.min.js",
//                "plugins" . DS . "datatable" . DS . "ColReorder.min.js",
//                "plugins" . DS . "datatable" . DS . "ColVis.min.js",
//                "plugins" . DS . "datatable" . DS . "jquery.dataTables.columnFilter.js",
        ),
        "requirements" => array("lip-jquery-2.1.1", "lip-bootstrap-3.3.5"),
    ),
    "lip-bootstrap-slider" => array(
        "css" => array(
            "bootstrap-slider.css",
        ),
        "js" => array(
            "libs/bootstrap/bootstrap-slider.js",
        ),
        "requirements" => array("lip-jquery-2.1.1", "lip-bootstrap-3.3.5"),
    ),
    "lip-materialize" => array(
        "css" => array(
            "materialize/materialize.min.css",
            // "materialize/bootstrap-material-design.min.css",
            "materialize/material-icons.css",
            "materialize/swiper/swiper.min.css",
            "materialize/materialize.clockpicker.css",
            // "materialize/ripples.min.css",
            "materialize/materialize.css",
        ),
        "js" => array(
            // "materialize/hammer.min.js",
            "materialize/materialize.min.js",
            // "materialize/material.min.js",
            // "materialize/materialize.amd.js",
            // "materialize/ripples.min.js",
            "materialize/swiper/swiper.jquery.js",
            "materialize/materialize.clockpicker.js",
            "materialize/jscroll/jquery.jscroll.js",
            "require.js",
            "materialize/material_main.js",
            "materialize/dlmenu/jquery.dlmenu.js",
            "materialize/dlmenu/modernizr.custom.js",
        ),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-moment" => array(
        "js" => array(
            "plugins/momentjs/moment.js",
            "plugins/momentjs/moment-with-locales.min.js",
        ),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-slick" => array(
        "js" => array(
            "plugins/slick/slick.min.js",
        ),
        "css" => array(
            "plugins/slick/slick.css",
            "plugins/slick/slick-theme.css",
        ),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-icheck" => array(
        "js" => array(
            "plugins/icheck/icheck.min.js",
        ),
        "css" => array(
            "plugins/iCheck1.0.1/all.css",
        ),
        "requirements" => array("lip-jquery-2.1.1"),
    ),
    "lip-datepicker_material" => array(
        "css" => array(
            "plugins/datepicker_material/bootstrap-material-datetimepicker.css",
        ),
        "js" => array(
            "plugins/datepicker_material/bootstrap-material-datetimepicker.js",
        ),
        "requirements" => array("lip-jquery-2.1.1", "lip-bootstrap-3.3.5", "lip-materialize"),
    ),
    "lip-fullcalendar" => array(
        "css" => array(
            "plugins/fullcalendar/fullcalendar.min.css",
//                "plugins/fullcalendar/fullcalendar.print.css",
        ),
        "js" => array(
            "plugins/fullcalendar/fullcalendar.min.js",
        ),
        "requirements" => array("lip-jquery-2.1.1", "lip-moment"),
    ),
    "lip-iapp" => array(
        "css" => array(
            "sidebar.less",
//            "skins/skin-blue.min.css",
//            "skins/skin-blue-light.min.css",
            "skins/_all-skins.min.css",
            "iapp.min.css",
        )
    ),
    "lip-swiper" => array(
        "css" => array(
            "materialize/swiper/swiper.min.css",
        ),
        "js" => array(
            "materialize/swiper/swiper.jquery.js",
        ),

    ),
    "lip-cf" => array(
        "css" => array(
            "cf.css",
        ),
        "requirements" => array("lip-jquery-2.1.1","lip-bootstrap-3.3.5"),
    ),
);
