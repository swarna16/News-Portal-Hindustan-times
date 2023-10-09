<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_item_model extends CI_Model
{

    /*
    *-------------------------------------------------------------------------------------------------
    * GALLERY ITEMS
    *-------------------------------------------------------------------------------------------------
    */

    //add gallery post item
    public function add_gallery_post_item($post_id, $item_order)
    {
        $data = array(
            'post_id' => $post_id,
            'title' => "",
            'content' => "",
            'image' => "",
            'image_large' => "",
            'image_description' => "",
            'item_order' => $item_order,
            'is_collapsed' => 0
        );

        if ($this->db->insert('post_gallery_items', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    //update gallery post item
    public function update_gallery_post_item()
    {
        $id = $this->input->post('id', true);
        $post_item = $this->get_gallery_post_item($id);
        if (!empty($post_item)) {
            $data = array(
                'title' => $this->input->post('title', true),
                'content' => $this->input->post('content', false),
                'image_description' => $this->input->post('image_description', true),
                'item_order' => $this->input->post('item_order', true)
            );
            $image_id = $this->input->post('image_id', true);
            $image = $this->file_model->get_image($image_id);
            if (!empty($image)) {
                $data['image'] = $image->image_big;
                $data['image_large'] = $image->image_default;
            }

            $this->db->where('id', $id);
            return $this->db->update('post_gallery_items', $data);
        }
        return false;
    }

    //get gallery post item
    public function get_gallery_post_item($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('post_gallery_items');
        return $query->row();
    }

    //get gallery post items
    public function get_gallery_post_items($post_id)
    {
        $this->db->where('post_id', $post_id);
        $this->db->order_by('item_order');
        $query = $this->db->get('post_gallery_items');
        return $query->result();
    }

    //get gallery post items count
    public function get_gallery_post_items_count($post_id)
    {
        $this->db->where('post_id', $post_id);
        $query = $this->db->get('post_gallery_items');
        return $query->num_rows();
    }

    //get gallery post first item
    public function get_gallery_post_first_item($post_id)
    {
        $this->db->where('post_id', $post_id);
        $this->db->order_by('item_order');
        $query = $this->db->get('post_gallery_items');
        return $query->row();
    }

    //get gallery post item by order
    public function get_gallery_post_item_by_order($post_id, $order)
    {
        $this->db->where('post_id', $post_id);
        $this->db->order_by('item_order');
        $query = $this->db->get('post_gallery_items');
        return $query->row($order);
    }

    //set gallery post item collapsed
    public function set_gallery_item_box_collapsed($id)
    {
        $post_item = $this->get_gallery_post_item($id);
        if ($post_item) {
            $data = array();
            if ($post_item->is_collapsed == 0) {
                $data['is_collapsed'] = 1;
            } else {
                $data['is_collapsed'] = 0;
            }
            $this->db->where('id', $id);
            $this->db->update('post_gallery_items', $data);
        }
    }

    //delete gallery post item
    public function delete_gallery_post_item($id)
    {
        $item = $this->get_gallery_post_item($id);
        if (!empty($item)) {
            $this->db->where('id', $id);
            return $this->db->delete('post_gallery_items');
        } else {
            return false;
        }
    }

    //delete gallery post items
    public function delete_gallery_post_items($post_id)
    {
        $items = $this->get_gallery_post_items($post_id);

        if (!empty($items)) {
            foreach ($items as $item) {
                $this->db->where('id', $item->id);
                $this->db->delete('post_gallery_items');
            }
        }
    }


    /*
    *-------------------------------------------------------------------------------------------------
    * ORDERED LIST ITEMS
    *-------------------------------------------------------------------------------------------------
    */

    //add ordered list item
    public function add_ordered_list_item($post_id, $item_order)
    {
        $data = array(
            'post_id' => $post_id,
            'title' => "",
            'content' => "",
            'image' => "",
            'image_large' => "",
            'image_description' => "",
            'item_order' => $item_order,
            'is_collapsed' => 0
        );

        if ($this->db->insert('post_ordered_list_items', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    //update ordered list item
    public function update_ordered_list_item()
    {
        $id = $this->input->post('id', true);
        $post_item = $this->get_ordered_list_item($id);
        if (!empty($post_item)) {
            $data = array(
                'title' => $this->input->post('title', true),
                'content' => $this->input->post('content', false),
                'image_description' => $this->input->post('image_description', true),
                'item_order' => $this->input->post('item_order', true)
            );
            $image_id = $this->input->post('image_id', true);
            $image = $this->file_model->get_image($image_id);
            if (!empty($image)) {
                $data['image'] = $image->image_big;
                $data['image_large'] = $image->image_default;
            }

            $this->db->where('id', $id);
            return $this->db->update('post_ordered_list_items', $data);
        }
        return false;
    }

    //get  ordered list item
    public function get_ordered_list_item($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('post_ordered_list_items');
        return $query->row();
    }

    //get ordered list items
    public function get_ordered_list_items($post_id)
    {
        $this->db->where('post_id', $post_id);
        $this->db->order_by('item_order');
        $query = $this->db->get('post_ordered_list_items');
        return $query->result();
    }

    //set ordered list item collapsed
    public function set_ordered_list_box_collapsed($id)
    {
        $post_item = $this->get_ordered_list_item($id);
        if ($post_item) {
            $data = array();
            if ($post_item->is_collapsed == 0) {
                $data['is_collapsed'] = 1;
            } else {
                $data['is_collapsed'] = 0;
            }
            $this->db->where('id', $id);
            $this->db->update('post_ordered_list_items', $data);
        }
    }

    //delete ordered list item
    public function delete_ordered_list_item($id)
    {
        $item = $this->get_ordered_list_item($id);
        if (!empty($item)) {
            $this->db->where('id', $id);
            return $this->db->delete('post_ordered_list_items');
        } else {
            return false;
        }
    }

    //delete ordered list items
    public function delete_ordered_list_items($post_id)
    {
        $items = $this->get_ordered_list_items($post_id);

        if (!empty($items)) {
            foreach ($items as $item) {
                $this->db->where('id', $item->id);
                $this->db->delete('post_ordered_list_items');
            }
        }
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * COMMON
    *-------------------------------------------------------------------------------------------------
    */

    //set gallery post item collapsed
    public function update_post_items_options($id)
    {
        $post = $this->post_admin_model->get_post($id);
        if ($post) {
            $data = array(
                'show_item_numbers' => $this->input->post('show_item_numbers', true)
            );
            if (empty($data['show_item_numbers'])) {
                $data['show_item_numbers'] = 0;
            }
            $this->db->where('id', $id);
            return $this->db->update('posts', $data);
        }
    }


}