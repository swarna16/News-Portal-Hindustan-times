<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->file_count = 48;
        $this->file_per_page = 18;
        if (!check_user_permission('add_post')) {
            exit();
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Image Upload
     * --------------------------------------------------------------------------
     */

    //upload multiple images
    public function upload_multiple_images()
    {
        $this->file_model->upload_multiple_images();
    }

    //get images
    public function get_images()
    {
        $images = $this->file_model->get_images($this->file_count);
        foreach ($images as $image):
            echo '<div class="col-file-manager" id="img_col_id_' . $image->id . '">';
            echo '<div class="file-box" data-file-id="' . $image->id . '" data-file-path="' . $image->image_default . '">';
            echo '<div class="image-container">';
            echo '<img src="' . base_url() . $image->image_mid . '" alt="" class="img-responsive">';
            echo '</div></div> </div>';
            $this->session->set_userdata("fm_last_img_id", $image->id);
        endforeach;
    }

    //select image file
    public function select_image_file()
    {
        $file_id = $this->input->post('file_id', true);

        $file = $this->file_model->get_image($file_id);
        if (!empty($file)) {
            echo base_url() . $file->image_mid;
        }
    }

    //load more images
    public function load_more_images()
    {
        $images = $this->file_model->get_more_images($this->session->userdata("fm_last_img_id"), $this->file_per_page);
        foreach ($images as $image):
            echo '<div class="col-file-manager" id="img_col_id_' . $image->id . '">';
            echo '<div class="file-box" data-file-id="' . $image->id . '" data-file-path="' . $image->image_default . '">';
            echo '<div class="image-container">';
            echo '<img src="' . base_url() . $image->image_mid . '" alt="" class="img-responsive">';
            echo '</div></div> </div>';
            $this->session->set_userdata("fm_last_img_id", $image->id);
        endforeach;
    }

    //delete image file
    public function delete_image_file()
    {
        $file_id = $this->input->post('file_id', true);
        $this->file_model->delete_image($file_id);
    }


    /**
     * --------------------------------------------------------------------------
     * CK Image Upload
     * --------------------------------------------------------------------------
     */

    //upload ck multiple images
    public function upload_ck_multiple_images()
    {
        $this->file_model->upload_multiple_images();
    }

    //get ck images
    public function get_ck_images()
    {
        $images = $this->file_model->get_images($this->file_count);
        foreach ($images as $image):
            echo '<div class="col-file-manager" id="ckimg_col_id_' . $image->id . '">';
            echo '<div class="file-box" data-file-id="' . $image->id . '" data-file-path="' . $image->image_default . '">';
            echo '<div class="image-container">';
            echo '<img src="' . base_url() . $image->image_mid . '" alt="" class="img-responsive">';
            echo '</div></div> </div>';
            $this->session->set_userdata("fm_last_ckimg_id", $image->id);
        endforeach;
    }

    //load more ck images
    public function load_more_ckimages()
    {
        $images = $this->file_model->get_more_images($this->session->userdata("fm_last_ckimg_id"), $this->file_per_page);
        foreach ($images as $image):
            echo '<div class="col-file-manager" id="ckimg_col_id_' . $image->id . '">';
            echo '<div class="file-box" data-file-id="' . $image->id . '" data-file-path="' . $image->image_default . '">';
            echo '<div class="image-container">';
            echo '<img src="' . base_url() . $image->image_mid . '" alt="" class="img-responsive">';
            echo '</div></div> </div>';
            $this->session->set_userdata("fm_last_ckimg_id", $image->id);
        endforeach;
    }

    //delete ck image file
    public function delete_ckimage_file()
    {
        $file_id = $this->input->post('file_id', true);
        $this->file_model->delete_image($file_id);
    }


    /**
     * --------------------------------------------------------------------------
     * Audio Upload
     * --------------------------------------------------------------------------
     */

    //upload audio file
    public function upload_audio_file()
    {
        $this->file_model->upload_audio();
        $audios = $this->file_model->get_audios($this->file_count);
        foreach ($audios as $audio):
            echo '<div class="col-sm-2 col-file-manager" id="audio_col_id_' . $audio->id . '">';
            echo '<div class="file-box" data-file-id="' . $audio->id . '">';
            echo '<img src="' . base_url() . 'assets/admin/img/music-file.png" alt="" class="img-responsive file-icon">';
            echo '<p class="file-manager-list-item-name" title="' . $audio->musician . ' - ' . $audio->audio_name . '">' . character_limiter($audio->musician . " - " . $audio->audio_name, 18, '...') . '</p>';
            echo '</div> </div>';
            $this->session->set_userdata("fm_last_audio_id", $audio->id);
        endforeach;
    }

    //select audio file
    public function select_audio_file()
    {
        $file_id = $this->input->post('file_id', true);

        $audio = $this->file_model->get_audio($file_id);
        if (!empty($audio)) {
            echo '<p class="play-list-item play-list-item-' . $audio->id . '"><i class="fa fa-music"></i>&nbsp;';
            echo $audio->audio_name;
            echo '<a href="javascript:void(0)" class="btn btn-xs btn-danger pull-right btn-delete-audio" data-value="' . $audio->id . '">';
            echo trans("delete");
            echo '</a><input type="hidden" name="post_audio_id[]" value="' . $audio->id . '"></p>';
        }
    }

    //load more audios
    public function load_more_audios()
    {
        $audios = $this->file_model->get_more_audios($this->session->userdata("fm_last_audio_id"), $this->file_per_page);

        foreach ($audios as $audio):
            echo '<div class="col-sm-2 col-file-manager" id="audio_col_id_' . $audio->id . '">';
            echo '<div class="file-box" data-file-id="' . $audio->id . '">';
            echo '<img src="' . base_url() . 'assets/admin/img/music-file.png" alt="" class="img-responsive file-icon">';
            echo '<p class="file-manager-list-item-name" title="' . $audio->musician . ' - ' . $audio->audio_name . '">' . character_limiter($audio->musician . " - " . $audio->audio_name, 18, '...') . '</p>';
            echo '</div> </div>';
            $this->session->set_userdata("fm_last_audio_id", $audio->id);
        endforeach;
    }

    //delet audio file
    public function delete_audio_file()
    {
        $file_id = $this->input->post('file_id', true);
        $this->file_model->delete_audio($file_id);
    }

    //search audio file
    public function search_audio_file()
    {
        $search = trim($this->input->post('search', true));
        $audios = $this->file_model->search_audios($search);
        foreach ($audios as $audio):
            echo '<div class="col-sm-2 col-file-manager" id="audio_col_id_' . $audio->id . '">';
            echo '<div class="file-box" data-file-id="' . $audio->id . '">';
            echo '<img src="' . base_url() . 'assets/admin/img/music-file.png" alt="" class="img-responsive file-icon">';
            echo '<p class="file-manager-list-item-name" title="' . $audio->musician . ' - ' . $audio->audio_name . '">' . character_limiter($audio->musician . " - " . $audio->audio_name, 18, '...') . '</p>';
            echo '</div> </div>';
        endforeach;
    }

    /**
     * --------------------------------------------------------------------------
     * Video Upload
     * --------------------------------------------------------------------------
     */

    //select video file
    public function select_video_file()
    {
        $file_id = $this->input->post('file_id', true);
        $video = $this->file_model->get_video($file_id);
        if (!empty($video)) {
            echo ' <video controls class="video-preview">';
            echo '<source src="' . base_url() . $video->video_path . '" type="video/mp4">';
            echo '<source src="' . base_url() . $video->video_path . '" type="video/ogg">';
            echo '</video>';
            echo '<input type="hidden" name="video_path" value="' . $video->video_path . '">';
        }
    }

    //upload video file
    public function upload_video_file()
    {
        $this->file_model->upload_video();
        $videos = $this->file_model->get_videos($this->file_count);
        foreach ($videos as $video):
            echo '<div class="col-sm-2 col-file-manager" id="video_col_id_' . $video->id . '">';
            echo '<div class="file-box" data-file-id="' . $video->id . '">';
            echo '<img src="' . base_url() . 'assets/admin/img/video-file.png" alt="" class="img-responsive file-icon">';
            echo '<p class="file-manager-list-item-name">' . $video->video_name . '</p>';
            echo '</div> </div>';
            $this->session->set_userdata("fm_last_video_id", $video->id);
        endforeach;
    }

    //load more videos
    public function load_more_videos()
    {
        $videos = $this->file_model->get_more_videos($this->session->userdata("fm_last_video_id"), $this->file_per_page);
        foreach ($videos as $video):
            echo '<div class="col-sm-2 col-file-manager" id="video_col_id_' . $video->id . '">';
            echo '<div class="file-box" data-file-id="' . $video->id . '">';
            echo '<img src="' . base_url() . 'assets/admin/img/video-file.png" alt="" class="img-responsive file-icon">';
            echo '<p class="file-manager-list-item-name">' . $video->video_name . '</p>';
            echo '</div> </div>';
            $this->session->set_userdata("fm_last_video_id", $video->id);
        endforeach;
    }

    //delete video file
    public function delete_video_file()
    {
        $file_id = $this->input->post('file_id', true);
        $this->file_model->delete_video($file_id);
    }

    //search video file
    public function search_video_file()
    {
        $search = trim($this->input->post('search', true));
        $videos = $this->file_model->search_videos($search);
        foreach ($videos as $video):
            echo '<div class="col-sm-2 col-file-manager" id="video_col_id_' . $video->id . '">';
            echo '<div class="file-box" data-file-id="' . $video->id . '">';
            echo '<img src="' . base_url() . 'assets/admin/img/video-file.png" alt="" class="img-responsive file-icon">';
            echo '<p class="file-manager-list-item-name">' . $video->video_name . '</p>';
            echo '</div> </div>';
            $this->session->set_userdata("fm_last_video_id", $video->id);
        endforeach;
    }

}
