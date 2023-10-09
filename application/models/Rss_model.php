<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rss_model extends CI_Model
{
    //input values
    public function input_values()
    {
        $data = array(
            'lang_id' => $this->input->post('lang_id', true),
            'feed_name' => $this->input->post('feed_name', true),
            'feed_url' => $this->input->post('feed_url', true),
            'post_limit' => $this->input->post('post_limit', true),
            'category_id' => $this->input->post('category_id', true),
            'subcategory_id' => $this->input->post('subcategory_id', true),
            'auto_update' => $this->input->post('auto_update', true),
            'read_more_button' => $this->input->post('read_more_button', true),
            'read_more_button_text' => $this->input->post('read_more_button_text', true),
            'add_posts_as_draft' => $this->input->post('add_posts_as_draft', true)
        );
        return $data;
    }

    //add feed
    public function add_feed()
    {
        $data = $this->input_values();

        //upload image
        $image = $this->file_model->upload_image('file');
        $data["image_big"] = $image["image_big"];
        $data["image_default"] = $image["image_default"];
        $data["image_slider"] = $image["image_slider"];
        $data["image_mid"] = $image["image_mid"];
        $data["image_small"] = $image["image_small"];
        $data["image_mime"] = $image["image_mime"];

        $data["user_id"] = user()->id;
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->db->insert('rss_feeds', $data);
    }

    //update feed
    public function update_feed($id)
    {
        $id = sql_escape_number($id);
        $feed = $this->get_feed($id);

        if (!empty($feed)) {
            $data = $this->input_values();

            //upload image
            $image = $this->file_model->upload_image('file');
            if (!empty($image)) {
                $data["image_big"] = $image["image_big"];
                $data["image_default"] = $image["image_default"];
                $data["image_slider"] = $image["image_slider"];
                $data["image_mid"] = $image["image_mid"];
                $data["image_small"] = $image["image_small"];
                $data["image_mime"] = $image["image_mime"];
            }

            $this->db->where('id', $id);
            return $this->db->update('rss_feeds', $data);
        } else {
            return false;
        }
    }

    //update feed posts button
    public function update_feed_posts_button($feed_id)
    {
        $feed_id = sql_escape_number($feed_id);
        $feed = $this->get_feed($feed_id);

        if (!empty($feed)) {

            $posts = $this->post_admin_model->get_posts_by_feed_id($feed_id);
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $data = array(
                        'show_post_url' => $feed->read_more_button
                    );

                    $this->db->where('id', $post->id);
                    $this->db->update('posts', $data);
                }
            }
        }
    }

    //get feed
    public function get_feed($id)
    {
        $id = sql_escape_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('rss_feeds');
        return $query->row();
    }

    //get feeds
    public function get_feeds()
    {
        $query = $this->db->get('rss_feeds');
        return $query->result();
    }

    //get feed posts
    public function get_feed_posts($feed_id)
    {
        $feed_id = sql_escape_number($feed_id);
        $this->db->where('feed_id', $feed_id);
        $query = $this->db->get('feed_posts');
        return $query->result();
    }

    //delete feed image
    public function delete_feed_image($id)
    {
        $id = sql_escape_number($id);
        $feed = $this->get_feed($id);

        if (!empty($feed)) {
            $data["image_big"] = "";
            $data["image_default"] = "";
            $data["image_slider"] = "";
            $data["image_mid"] = "";
            $data["image_small"] = "";
            $data["image_mime"] = "";

            $this->db->where('id', $id);
            $this->db->update('rss_feeds', $data);
        }
    }

    //delete feed
    public function delete_feed($id)
    {
        $id = sql_escape_number($id);
        $feed = $this->get_feed($id);

        if (!empty($feed)) {
            $this->db->where('id', $id);
            return $this->db->delete('rss_feeds');
        } else {
            return false;
        }
    }

    //add rss feed posts
    public function add_feed_posts($feed_id)
    {
        $this->load->library('rss_parser');
        $feed = $this->get_feed($feed_id);
        if (!empty($feed)) {
            $response = $this->rss_parser->get_feeds($feed->feed_url);
            $i = 0;
            if (!empty($response['item'])) {
                foreach ($response['item'] as $item) {
                    if ($feed->post_limit == $i) {
                        break;
                    }
                    $title_hash = md5($item['title']);
                    if ($this->post_admin_model->check_is_post_exists($item['title'], $title_hash) == false) {
                        $data = array();
                        $data['lang_id'] = $feed->lang_id;
                        $data['title'] = $this->clear_chars(trim($item['title']));
                        $data['title_slug'] = str_slug($item['title']);
                        $data['title_hash'] = $title_hash;
                        $data['keywords'] = "";
                        $data['summary'] = $item['description'];
                        if (empty($item['summary'])) {
                            $summary = trim($this->clear_chars(strip_tags($item['content'])));
                            $data['summary'] = trim(character_limiter($summary, 240, '...'));
                        }
                        $data['content'] = $item['content'];
                        $data['category_id'] = $feed->category_id;
                        $data['subcategory_id'] = $feed->subcategory_id;
                        $data['image_big'] = $feed->image_big;
                        $data['image_default'] = $feed->image_default;
                        $data['image_slider'] = $feed->image_slider;
                        $data['image_mid'] = $feed->image_mid;
                        $data['image_small'] = $feed->image_small;
                        $data['hit'] = 0;
                        $data['optional_url'] = "";
                        $data['need_auth'] = 0;
                        $data['is_slider'] = 0;
                        $data['slider_order'] = 1;
                        $data['is_featured'] = 0;
                        $data['featured_order'] = 1;
                        $data['is_recommended'] = 0;
                        $data['is_breaking'] = 0;
                        $data['is_scheduled'] = 0;
                        $data['visibility'] = 1;
                        $data['show_right_column'] = 1;
                        $data['post_type'] = "post";
                        $data['video_path'] = "";
                        $data['video_embed_code'] = "";
                        $data['image_url'] = $this->rss_parser->get_image($item);
                        $data['user_id'] = $feed->user_id;
                        $data['feed_id'] = $feed->id;
                        $data['post_url'] = $item['link'];
                        $data['show_post_url'] = $feed->read_more_button;
                        $data['image_description'] = "";
                        $data['created_at'] = date('Y-m-d H:i:s');

                        if ($feed->add_posts_as_draft == 1) {
                            $data['status'] = 0;
                        } else {
                            $data['status'] = 1;
                        }

                        $this->db->insert('posts', $data);
                        $this->post_admin_model->update_slug($this->db->insert_id());
                    }
                    $i++;
                }
                return true;
            }
        }
    }

    public function clear_chars($str)
    {
        $str = htmlspecialchars_decode($str, ENT_QUOTES | ENT_XML1);
        return $str;
    }

}