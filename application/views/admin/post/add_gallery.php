<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12">
        <!-- form start -->
        <?php echo form_open_multipart('post_controller/add_gallery_post'); ?>
        <div class="row">
            <div class="col-sm-12 form-header">
                <h1 class="form-title"><?php echo trans('add_gallery'); ?></h1>
                <a href="<?php echo admin_url(); ?>posts?lang_id=<?php echo $general_settings->site_lang; ?>"
                   class="btn btn-success btn-add-new pull-right">
                    <i class="fa fa-bars"></i>
                    <?php echo trans('posts'); ?>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-post">
                    <div class="form-post-left">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->load->view("admin/includes/_form_add_post_left", ['show_content_field' => false]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-post-right">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->load->view('admin/includes/_post_image_upload_box'); ?>
                            </div>
                            <div class="col-sm-12">
                                <?php $this->load->view('admin/includes/_sidebar_language_categories'); ?>
                            </div>
                            <div class="col-sm-12">
                                <?php $this->load->view('admin/includes/_post_publish_box', ['post_type' => 'gallery']); ?>
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

