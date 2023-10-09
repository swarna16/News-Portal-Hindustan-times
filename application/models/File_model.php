<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model
{
    //upload image
    public function upload_image($file_input_name)
    {
        $this->load->model('upload_model');
        $temp_data = $this->upload_model->upload_temp_image($file_input_name, 'array');
        if (!empty($temp_data)) {
            $temp_path = $temp_data['full_path'];
            $data = array(
                'image_big' => "",
                'image_default' => "",
                'image_slider' => "",
                'image_mid' => "",
                'image_small' => "",
                'image_mime' => "jpg",
                'user_id' => $this->auth_user->id
            );
            if ($temp_data['image_type'] == 'gif') {
                $gif_path = $this->upload_model->post_gif_image_upload($temp_data['file_name']);
                $data["image_big"] = $gif_path;
                $data["image_default"] = $gif_path;
                $data["image_slider"] = $gif_path;
                $data["image_mid"] = $gif_path;
                $data["image_small"] = $gif_path;
                $data["image_mime"] = 'gif';
            } else {
                $data["image_big"] = $this->upload_model->post_big_image_upload($temp_path);
                $data["image_default"] = $this->upload_model->post_default_image_upload($temp_path);
                $data["image_slider"] = $this->upload_model->post_slider_image_upload($temp_path);
                $data["image_mid"] = $this->upload_model->post_mid_image_upload($temp_path);
                $data["image_small"] = $this->upload_model->post_small_image_upload($temp_path);
            }
            $this->db->insert('images', $data);
            $this->upload_model->delete_temp_image($temp_path);
            return $data;
        }
    }

    //upload multiple images
    public function upload_multiple_images()
    {
        if (!empty($_FILES['files'])) {
            $this->load->model('upload_model');
            $file_count = count($_FILES['files']['name']);
            for ($i = 0; $i < $file_count; $i++) {
                if (isset($_FILES['files']['name'])) {
                    //file
                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];
                    //upload
                    $temp_data = $this->upload_model->upload_temp_image('file', 'array');
                    if (!empty($temp_data)) {
                        $temp_path = $temp_data['full_path'];
                        if ($temp_data['image_type'] == 'gif') {
                            $gif_path = $this->upload_model->post_gif_image_upload($temp_data['file_name']);
                            $data["image_big"] = $gif_path;
                            $data["image_default"] = $gif_path;
                            $data["image_slider"] = $gif_path;
                            $data["image_mid"] = $gif_path;
                            $data["image_small"] = $gif_path;
                            $data["image_mime"] = 'gif';
                        } else {
                            $data["image_big"] = $this->upload_model->post_big_image_upload($temp_path);
                            $data["image_default"] = $this->upload_model->post_default_image_upload($temp_path);
                            $data["image_slider"] = $this->upload_model->post_slider_image_upload($temp_path);
                            $data["image_mid"] = $this->upload_model->post_mid_image_upload($temp_path);
                            $data["image_small"] = $this->upload_model->post_small_image_upload($temp_path);
                            $data["image_mime"] = 'jpg';
                        }
                    }
                    $this->insert_image($data);
                    $this->upload_model->delete_temp_image($temp_path);
                }
            }
        }
    }


    //upload audio
    public function upload_audio()
    {
        $this->load->model('upload_model');
        $path = $this->upload_model->audio_upload('audio_file');
        if (!empty($path)) {
            $data = array(
                'audio_path' => $path,
                'audio_name' => $this->input->post('audio_name', true),
                'musician' => $this->input->post('musician', true),
                'download_button' => $this->input->post('download_button', true),
                'user_id' => $this->auth_user->id
            );
            $this->db->insert('audios', $data);
        }
    }

    //upload video
    public function upload_video()
    {
        $this->load->model('upload_model');
        $path = $this->upload_model->video_upload('video_file');
        if (!empty($path)) {
            $data = array(
                'video_path' => $path,
                'video_name' => $this->input->post('video_name', true),
                'user_id' => $this->auth_user->id
            );
            $this->db->insert('videos', $data);
        }
    }

    //get image
    public function get_image($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('images');
        return $query->row();
    }

    //get audio
    public function get_audio($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('audios');
        return $query->row();
    }

    //get video
    public function get_video($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('videos');
        return $query->row();
    }

    //get images
    public function get_images($count)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->order_by('id', 'DESC');
        $this->db->limit($count);
        $query = $this->db->get('images');
        return $query->result();
    }

    //get more images
    public function get_more_images($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->where('id <', $last_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('images');
        return $query->result();
    }

    //get audios
    public function get_audios($count)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->order_by('audios.id', 'DESC');
        $this->db->limit($count);
        $query = $this->db->get('audios');
        return $query->result();
    }

    //search audios
    public function search_audios($search)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->order_by('audios.id', 'DESC');
        $this->db->like('audio_name', $search);
        $this->db->or_like('musician', $search);
        $query = $this->db->get('audios');
        return $query->result();
    }

    //get more audios
    public function get_more_audios($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->where('id <', $last_id);
        $this->db->order_by('audios.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('audios');
        return $query->result();
    }

    //get videos
    public function get_videos($count)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->order_by('videos.id', 'DESC');
        $this->db->limit($count);
        $query = $this->db->get('videos');
        return $query->result();
    }

    //search videos
    public function search_videos($search)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->order_by('videos.id', 'DESC');
        $this->db->like('video_name', $search);
        $query = $this->db->get('videos');
        return $query->result();
    }

    //get more videos
    public function get_more_videos($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $this->db->where('user_id', $this->auth_user->id);
        }
        $this->db->where('id <', $last_id);
        $this->db->order_by('videos.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('videos');
        return $query->result();
    }

    //delete image
    public function delete_image($id)
    {
        $image = $this->get_image($id);
        if (!empty($image)) {
            //delete image from server
            delete_file_from_server($image->image_big);
            delete_file_from_server($image->image_default);
            delete_file_from_server($image->image_slider);
            delete_file_from_server($image->image_mid);
            delete_file_from_server($image->image_small);
            $this->db->where('id', $id);
            $this->db->delete('images');
        }
    }

    //delete audio
    public function delete_audio($id)
    {
        $audio = $this->get_audio($id);

        if (!empty($audio)) {
            //delete from folder
            delete_file_from_server($audio->audio_path);

            $this->db->where('id', $id);
            $this->db->delete('audios');
        }
    }

    //delete video
    public function delete_video($id)
    {
        $video = $this->get_video($id);

        if (!empty($video)) {
            //delete from folder
            delete_file_from_server($video->video_path);

            $this->db->where('id', $id);
            $this->db->delete('videos');
        }
    }

    //insert image
    function insert_image($data)
    {
        $ci =& get_instance();
        $ci->load->database();
        // Connect to the database
        $mysqli = new mysqli($ci->db->hostname, $ci->db->username, $ci->db->password, $ci->db->database);

        $image_big = $ci->security->xss_clean($data["image_big"]);
        $image_default = $ci->security->xss_clean($data["image_default"]);
        $image_slider = $ci->security->xss_clean($data["image_slider"]);
        $image_mid = $ci->security->xss_clean($data["image_mid"]);
        $image_small = $ci->security->xss_clean($data["image_small"]);
        $image_mime = $ci->security->xss_clean($data["image_mime"]);
        // Check for errors
        if (!mysqli_connect_errno()) {
            $mysqli->query("INSERT INTO `images` (`image_big`, `image_default`, `image_slider`, `image_mid`, `image_small`, `image_mime`, `user_id`) VALUES ('" . $image_big . "','" . $image_default . "','" . $image_slider . "','" . $image_mid . "','" . $image_small . "','" . $image_mime . "','" . $this->auth_user->id . "');");
        }
        // Close the connection
        $mysqli->close();
    }
}