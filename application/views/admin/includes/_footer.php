<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<footer id="footer" class="main-footer">
    <div class="pull-right hidden-xs">
        <strong style="font-weight: 600;"><?php echo $settings->copyright; ?>&nbsp;</strong>
    </div>
    <b>Version</b> 1.6.3
</footer>
</div><!-- ./wrapper -->
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/admin/js/adminlte.min.js"></script>
<!-- DataTables js -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- Lazy Load js -->
<script src="<?php echo base_url(); ?>assets/admin/js/lazysizes.min.js"></script>
<!-- iCheck js -->
<script src="<?php echo base_url(); ?>assets/vendor/icheck/icheck.min.js"></script>
<!-- Ckeditor js -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<!-- Pace -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/pace/pace.min.js"></script>
<!-- File Manager -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/file-manager/file-manager.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<!-- Bootstrap Toggle js -->
<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap-toggle.min.js"></script>
<!-- Plugins js -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugins.js"></script>
<!-- Color Picker js -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- Datepicker js -->
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
<!-- Custom js -->
<script src="<?php echo base_url(); ?>assets/admin/js/custom-1.6.min.js"></script>
<!-- Ckeditor -->
<script>
    var ckEditor = document.getElementById('ckEditor');
    if (ckEditor != undefined && ckEditor != null) {
        CKEDITOR.replace('ckEditor', {
            language: 'en',
            filebrowserBrowseUrl: 'path',
            removeButtons: 'Save',
            allowedContent: true,
            extraPlugins: 'videoembed,oembed'
        });
    }

    function selectFile(fileUrl) {
        window.opener.CKEDITOR.tools.callFunction(1, fileUrl);
    }

    CKEDITOR.on('dialogDefinition', function (ev) {
        var editor = ev.editor;
        var dialogDefinition = ev.data.definition;

        // This function will be called when the user will pick a file in file manager
        var cleanUpFuncRef = CKEDITOR.tools.addFunction(function (a) {
            $('#ck_file_manager').modal('hide');
            CKEDITOR.tools.callFunction(1, a, "");
        });
        var tabCount = dialogDefinition.contents.length;
        for (var i = 0; i < tabCount; i++) {
            var browseButton = dialogDefinition.contents[i].get('browse');
            if (browseButton !== null) {
                browseButton.onClick = function (dialog, i) {
                    editor._.filebrowserSe = this;
                    var iframe = $('#ck_file_manager').find('iframe').attr({
                        src: editor.config.filebrowserBrowseUrl + '&CKEditor=body&CKEditorFuncNum=' + cleanUpFuncRef + '&langCode=en'
                    });
                    $('#ck_file_manager').appendTo('body').modal('show');
                }
            }
        }
    });
    CKEDITOR.on('instanceReady', function (evt) {
        $(document).on('click', '.btn_ck_add_image', function () {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('image');
            }
        });
        $(document).on('click', '.btn_ck_embed_media', function () {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('oembed');
            }
        });
        $(document).on('click', '.btn_ck_add_video', function () {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('videoembed');
            }
        });
        $(document).on('click', '.btn_ck_add_iframe', function () {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('iframe');
            }
        });
    });
</script>

<?php if ($site_lang->text_direction == "rtl"): ?>
    <script type="text/javascript">
        jQuery(function () {
            if (typeof (CKEDITOR) != "undefined") {
                CKEDITOR.config.contentsLangDirection = 'rtl';
            }
        });
    </script>
<?php endif; ?>
<style>.cke_button__iframe {display: none !important;}</style>

<?php if (isset($lang_search_column)): ?>
    <script>
        var table = $('#cs_datatable_lang').DataTable({
            dom: 'l<"#table_dropdown">frtip',
            "order": [[0, "desc"]],
            "aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
        });
        //insert a label
        $('<label class="table-label"><label/>').text('Language').appendTo('#table_dropdown');

        //insert the select and some options
        $select = $('<select class="form-control input-sm"><select/>').appendTo('#table_dropdown');

        $('<option/>').val('').text('<?php echo trans("all"); ?>').appendTo($select);
        <?php foreach ($languages as $lang): ?>
        $('<option/>').val('<?php echo $lang->name; ?>').text('<?php echo $lang->name; ?>').appendTo($select);
        <?php endforeach; ?>


        $("#table_dropdown select").change(function () {
            table.column(<?php echo $lang_search_column; ?>).search($(this).val()).draw();
        });
    </script>
<?php endif; ?>
</body>
</html>

