<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$last_item_id = 0;
$is_post_item_exists = false;
$edit_item_id = $this->input->get('edit_item_id', true);
if (!empty($post_items)):
    foreach ($post_items as $post_item):
        if ($edit_item_id == $post_item->id) {
            $is_post_item_exists = true;
        }
        $last_item_id = $post_item->id;
    endforeach;
endif;
if (!$is_post_item_exists) {
    $edit_item_id = $last_item_id;
}
?>

<div class="row">
    <div class="col-sm-12 form-header">
        <?php if ($post->status == 0): ?>
            <h1 class="form-title"><?php echo trans('add_gallery'); ?></h1>
        <?php else: ?>
            <h1 class="form-title"><?php echo trans('update_gallery'); ?></h1>
        <?php endif; ?>
        <a href="<?php echo admin_url(); ?>posts?lang_id=<?php echo $general_settings->site_lang; ?>"
           class="btn btn-success btn-add-new pull-right">
            <i class="fa fa-bars"></i>
            <?php echo trans('posts'); ?>
        </a>
    </div>
</div>
<?php if (!empty($this->session->flashdata("msg_post_published"))):
    $this->load->view('admin/includes/_messages');
endif; ?>
<div class="row">
    <div class="col-sm-8">
        <div class="post-items-item-preview">
            <div class="image">
                <?php if (!empty($post->image_url)): ?>
                    <img src="<?php echo $post->image_url; ?>" alt="" class="img-responsive">
                <?php else: ?>
                    <img src="<?php echo base_url() . $post->image_big; ?>" alt="" class="img-responsive">
                <?php endif; ?>
            </div>
            <h2 class="title"><?php echo html_escape($post->title); ?></h2>
            <p class="description m-b-15"><?php echo html_escape($post->summary); ?></p>
            <a href="<?php echo admin_url(); ?>update-post/<?php echo $post->id ?>" class="btn btn-default m-t-5"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("edit_post_details"); ?></a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('options'); ?></h3>
            </div>
            <?php echo form_open('post_controller/update_post_items_options_post'); ?>
            <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("msg_post_options"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <input type="checkbox" name="show_item_numbers" value="1" id="show_item_numbers_checkbox" class="square-purple" <?php echo ($post->show_item_numbers == 1) ? 'checked' : ''; ?>>&nbsp;&nbsp;
                    <label for="show_item_numbers_checkbox" class="option-label cursor-pointer"><?php echo trans('show_item_numbers'); ?></label>
                </div>
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>

                <?php echo form_close(); ?><!-- form end -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 form-header">
        <h2 class="form-title"><?php echo trans('gallery_post_items'); ?></h2>
    </div>
</div>

<div class="post-items-container">
    <?php
    $count = 1;
    if (!empty($post_items)):
        foreach ($post_items as $post_item):
            if ($edit_item_id == $post_item->id):?>
                <!-- form start -->
                <?php echo form_open_multipart('post_controller/update_gallery_post_item_post'); ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>#<?php echo $count; ?></strong></h3>
                        <div class="post-item-message">
                            <?php if (!empty($this->session->flashdata("msg_post_item" . $post_item->id))):
                                $this->load->view('admin/includes/_messages');
                            endif; ?>
                        </div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="post-items-item">
                            <div class="left">
                                <input type="hidden" name="id" value="<?php echo $post_item->id; ?>">
                                <div class="form-group" id="item<?php echo $post_item->id; ?>">
                                    <label class="control-label"><?php echo trans('title'); ?></label>
                                    <input type="text" class="form-control" name="title" placeholder="<?php echo trans('title'); ?>"
                                           value="<?php echo html_escape($post_item->title); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label"><?php echo trans('content'); ?></label>
                                            <div class="row">
                                                <div class="col-sm-12 ckeditor-buttons">
                                                    <button type="button" class="btn btn-sm btn-success btn_ck_add_image"><i class="fa fa-image"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("add_image"); ?></button>
                                                    <button type="button" class="btn btn-sm bg-purple btn_ck_embed_media"><i class="fa fa-file"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("embed_media"); ?></button>
                                                    <button type="button" class="btn btn-sm btn-info btn_ck_add_video"><i class="fa fa-video-camera"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("add_video"); ?></button>
                                                    <button type="button" class="btn btn-sm btn-warning btn_ck_add_iframe"><i class="fa fa-code"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("add_iframe"); ?></button>
                                                </div>
                                            </div>
                                            <textarea class="form-control ckeditor" id="ckEditor" name="content"><?php echo $post_item->content; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="form-group m0">
                                    <label class="control-label"><?php echo trans('image'); ?></label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a class='btn btn-sm bg-purple btn_post_item_select_image' data-toggle="modal" data-target="#image_file_manager" data-tab-id="<?php echo $post_item->id; ?>" onclick="$('#selected_image_type').val('image');">
                                                <?php echo trans('select_image'); ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 m-t-15">
                                            <?php if (!empty($post_item->image)): ?>
                                                <img id="selected_image_file_<?php echo $post_item->id; ?>" src="<?php echo base_url() . $post_item->image; ?>" alt="" class="img-responsive"/>
                                            <?php else: ?>
                                                <img id="selected_image_file_<?php echo $post_item->id; ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="" class="img-responsive"/>
                                            <?php endif; ?>
                                            <input type="hidden" id="selected_image_id_<?php echo $post_item->id; ?>" name="image_id" value="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 m-t-15">
                                            <label class="control-label"><?php echo trans('image_description'); ?></label>
                                            <input type="text" class="form-control" name="image_description" placeholder="<?php echo trans('image_description'); ?>" maxlength="300" value="<?php echo html_escape($post_item->image_description); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 m-t-15">
                                            <label class="control-label"><?php echo trans('order_1'); ?></label>
                                            <input type="number" class="form-control" name="item_order" min="1" placeholder="<?php echo trans('order_1'); ?>" min="1" max="30000" value="<?php echo $post_item->item_order; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right" style="padding-top: 0;">
                        <button type="button" class="btn btn-md btn-danger m-r-5" onclick="delete_gallery_post_item('<?php echo $post_item->id; ?>','<?php echo trans("confirm_item"); ?>');"><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("delete"); ?></button>
                        <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("save_changes"); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?><!-- form end -->
            <?php else: ?>
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="post-items-item post-items-item-table">
                            <div class="number"><strong>#<?php echo $count; ?></strong></div>
                            <div class="details">
                                <?php if (!empty($post_item->image)): ?>
                                    <img id="selected_image_file_<?php echo $post_item->id; ?>" src="<?php echo base_url() . $post_item->image; ?>" alt="" class="img-responsive"/>
                                <?php else: ?>
                                    <img id="selected_image_file_<?php echo $post_item->id; ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="" class="img-responsive"/>
                                <?php endif; ?>
                                <h4 class="title"><?php echo html_escape($post_item->title); ?></h4>
                            </div>
                            <div class="buttons">
                                <a href="<?php echo admin_url(); ?>gallery-post-items/<?php echo $post_item->post_id; ?>?edit_item_id=<?php echo $post_item->id; ?>" class="btn btn-sm btn-warning m-r-5"><i class="fa fa-pencil"></i>&nbsp;&nbsp;<?php echo trans("edit"); ?></a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="delete_gallery_post_item('<?php echo $post_item->id; ?>','<?php echo trans("confirm_item"); ?>');"><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("delete"); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif;
            $count++;
        endforeach;
    endif; ?>
</div>

<?php echo form_open_multipart('post_controller/add_gallery_post_item_post'); ?>
<div class="form-group text-center" style="margin: 20px 0;">
    <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
    <input type="hidden" name="item_order" value="<?php echo $count; ?>">
    <button type="submit" class="btn btn-md btn-success btn-add-post-item"><i class="fa fa-plus"></i><?php echo trans("add_new_item"); ?></button>
</div>
<?php echo form_close(); ?><!-- form end -->

<?php if ($post->status == 0): ?>
    <?php echo form_open_multipart('post_controller/publish_draft_post'); ?>
    <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
    <div class="row" style="margin-bottom: 100px;">
        <div class="col-sm-8"></div>
        <div class="col-sm-4">
            <div class="box">
                <div class="box-header with-border">
                    <div class="left">
                        <h3 class="box-title"><?php echo trans('publish'); ?></h3>
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5 col-sm-12 col-xs-12">
                                <label class="control-label"><?php echo trans('scheduled_post'); ?></label>
                            </div>
                            <div class="col-md-7 col-sm-12 col-xs-12 text-right">
                                <input type="checkbox" name="scheduled_post" value="1" id="cb_scheduled" class="square-purple" <?php echo ($post->is_scheduled == 1) ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5 col-sm-12 col-xs-12 col-lang">
                                <label><?php echo trans('date_publish'); ?></label>
                            </div>
                            <div class="col-md-7 col-sm-12 col-xs-12 col-lang">
                                <div class='input-group date' id='datetimepicker'>
                                    <input type='text' class="form-control" name="date_published" placeholder="<?php echo trans("date_publish"); ?>" value="<?php echo $post->created_at; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php if ($post->status == 0): ?>
                            <button type="submit" name="publish" value="1" class="btn btn-warning pull-right m-l-10"><?php echo trans('publish'); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->
<?php endif; ?>

<input type="hidden" id="post_item_image_button_id">


<?php $this->load->view("admin/includes/_file_manager_image"); ?>
<?php $this->load->view("admin/includes/_file_manager_ckeditor"); ?>

<style>
    #cke_1_contents {
        min-height: 200px !important;
    }

    .box {
        margin-bottom: 10px;
    }

    .box-header {
        padding: 15px;
    }

    .btn-box-tool {
        font-size: 16px;
    }

    .post-item-message {
        position: relative;
        margin-top: 5px;
    }

    .post-item-message .m-b-15 {
        margin-bottom: 0;
    }

</style>

<script>
    $(document).on('click', '.btn_post_item_select_image', function () {
        var item_tab_id = $(this).attr('data-tab-id');
        $("#post_item_image_button_id").val(item_tab_id);
    });
    $(document).on('click', '#image_file_manager #btn_img_select', function () {
        var file_id = $('#selected_img_file_id').val();
        select_post_item_image(file_id);
    });
    $(document).on('dblclick', '#image_file_manager .file-box', function () {
        var file_id = $('#selected_img_file_id').val();
        select_post_item_image(file_id);
    });
</script>

