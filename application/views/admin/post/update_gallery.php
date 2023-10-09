<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-sm-12">
        <!-- form start -->
        <?php echo form_open_multipart('post_controller/update_post_post'); ?>
        <input type="hidden" name="post_type" value="post">
        <div class="row">
            <div class="col-sm-12 form-header">
                <h1 class="form-title"><?php echo trans('update_gallery'); ?></h1>
                <a href="<?php echo admin_url(); ?>posts" class="btn btn-success btn-add-new pull-right">
                    <i class="fa fa-bars"></i>
                    <?php echo trans('posts'); ?>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-post">
                    <div class="form-post-left">
                        <?php $this->load->view("admin/includes/_form_update_post_left", ['show_content_field' => false]); ?>
                    </div>
                    <div class="form-post-right">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->load->view('admin/includes/_post_image_edit_box'); ?>
                            </div>

                            <?php if (check_user_permission('manage_all_posts')): ?>
                                <div class="col-sm-12">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <div class="left">
                                                <h3 class="box-title"><?php echo trans('post_owner'); ?></h3>
                                            </div>
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label><?php echo trans("post_owner"); ?></label>
                                                <select name="user_id" class="form-control">
                                                    <?php foreach ($users as $user): ?>
                                                        <option value="<?php echo $user->id; ?>" <?php echo ($post->user_id == $user->id) ? 'selected' : ''; ?>><?php echo $user->username; ?>&nbsp;(<?php echo $user->role; ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="user_id" value="<?php echo $post->user_id; ?>">
                            <?php endif; ?>
                            <div class="col-sm-12">
                                <?php $this->load->view('admin/includes/_sidebar_language_categories_edit'); ?>
                            </div>
                            <div class="col-sm-12">
                                <?php $this->load->view('admin/includes/_post_publish_edit_box', ['post_type' => 'gallery']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?><!-- form end -->
    </div>
</div>

<style>
    .row-image-description {
        visibility: hidden;
        height: 0;
        overflow: hidden;
    }
    .row-optional-url {
        visibility: hidden;
        height: 0;
        overflow: hidden;
    }
</style>

<?php $this->load->view("admin/includes/_file_manager_image"); ?>


