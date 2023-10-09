<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modal -->
<div id="audio_file_manager" class="modal fade modal-file-manager" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-audio-manager" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo trans('file_manager'); ?></h4>
                <div class="file-manager-search">
                    <input type="text" id="input_search_audio" class="form-control" placeholder="<?php echo trans("search") ?>">
                </div>
            </div>
            <div class="modal-body">
                <div class="file-manager">

                    <div class="file-manager-left">

                        <div id="add_audio_form">
                            <div class="form-group">
                                <label class="control-label"><?php echo trans('audio_name'); ?></label>
                                <input type="text" id="audio_name" class="form-control" placeholder="<?php echo trans('audio_name'); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="control-label"><?php echo trans('musician'); ?></label>
                                        <input type="text" id="musician" class="form-control" placeholder="<?php echo trans('musician'); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label><?php echo trans('download_button'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="radio" id="rb_download_button_1" name="audio_download_button" value="1" class="square-purple" checked>&nbsp;&nbsp;
                                        <label for="rb_download_button_1" class="cursor-pointer"><?php echo trans('show'); ?></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="radio" id="rb_download_button_2" name="audio_download_button" value="0" class="square-purple">&nbsp;&nbsp;
                                        <label for="rb_download_button_2" class="cursor-pointer"><?php echo trans('hide'); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php echo trans('audio_file'); ?></label>
                                <div class="row">
                                    <div class="col-sm-12 m-b-10">
                                        <a class='btn btn-sm bg-olive'>
                                            <?php echo trans('select_file'); ?>
                                            <input type="file" id="audio_file_input" name="file" class="upload-file-input input-post-image-file" accept=".mp3, .wav" onchange="$('#input_audio_file_label').html($(this).val().replace(/.*[\/\\]/, '..'));">
                                        </a>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="input-file-label" id="input_audio_file_label"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <a id="btn_audio_upload" class='btn btn-md bg-purple btn-upload'>
                                        <i class="fa fa-cloud-upload"></i>&nbsp;&nbsp;
                                        <?php echo trans('upload'); ?>
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 m-t-5">
                                    <div class="loader-file-manager">
                                        <div class="sk-fading-circle">
                                            <div class="sk-circle1 sk-circle"></div>
                                            <div class="sk-circle2 sk-circle"></div>
                                            <div class="sk-circle3 sk-circle"></div>
                                            <div class="sk-circle4 sk-circle"></div>
                                            <div class="sk-circle5 sk-circle"></div>
                                            <div class="sk-circle6 sk-circle"></div>
                                            <div class="sk-circle7 sk-circle"></div>
                                            <div class="sk-circle8 sk-circle"></div>
                                            <div class="sk-circle9 sk-circle"></div>
                                            <div class="sk-circle10 sk-circle"></div>
                                            <div class="sk-circle11 sk-circle"></div>
                                            <div class="sk-circle12 sk-circle"></div>
                                        </div>
                                        <p class="file-manager-uploading-text"><?php echo trans("uploading"); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="file-manager-right">
                        <div class="file-manager-content">
                            <div id="audio_file_upload_response">
                                <?php foreach ($audios as $audio): ?>
                                    <div class="col-sm-2 col-file-manager" id="audio_col_id_<?php echo $audio->id; ?>">
                                        <div class="file-box" data-file-id="<?php echo $audio->id; ?>">
                                            <img src="<?php echo base_url(); ?>assets/admin/img/music-file.png" alt="" class="img-responsive file-icon">
                                            <p class="file-manager-list-item-name" title="<?php echo $audio->musician . " - " . $audio->audio_name; ?>"><?php echo character_limiter($audio->musician . " - " . $audio->audio_name, 18, '...'); ?></p>
                                        </div>
                                    </div>
                                    <?php $this->session->set_userdata("fm_last_audio_id", $audio->id); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="selected_audio_file_id">

                </div>
            </div>
            <div class="modal-footer">
                <div class="file-manager-footer">
                    <button type="button" id="btn_audio_delete" class="btn btn-danger pull-left btn-file-delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?php echo trans('delete'); ?></button>
                    <button type="button" id="btn_audio_select" class="btn bg-olive btn-file-select"><i class="fa fa-check"></i>&nbsp;&nbsp;<?php echo trans('select_audio'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('close'); ?></button>
                </div>
            </div>

        </div>

    </div>
</div>
