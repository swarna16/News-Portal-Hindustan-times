<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model
{
    //add comment
    public function add_comment()
    {
        $data = array(
            'parent_id' => $this->input->post('parent_id', true),
            'post_id' => $this->input->post('post_id', true),
            'user_id' => $this->input->post('user_id', true),
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'comment' => $this->input->post('comment', true),
            'ip_address' => 0,
        );
        if (!empty($data['post_id']) && !empty(trim($data['comment']))) {
            if ($data['user_id'] != 0) {
                $user = $this->auth_model->get_user($data['user_id']);
                if (!empty($user)) {
                    $data['name'] = $user->username;
                    $data['email'] = $user->email;
                }
            }
            $ip = $this->input->ip_address();
            if (!empty($ip)) {
                $data['ip_address'] = $ip;
            }
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('comments', $data);
            $last_id = $this->db->insert_id();
            setcookie('vr_added_comment_id_' . $last_id, 1, time() + (86400 * 60), "/");
        }
    }

    //like comment
    public function like_comment($comment_id)
    {
        $comment_id = sql_escape_number($comment_id);
        //check comment owner
        if (isset($_COOKIE['vr_added_comment_id_' . $comment_id])) {
            return false;
        }

        $cookie_vote = 'vr_comment_vote_' . $comment_id;
        if (!isset($_COOKIE[$cookie_vote])) {
            $comment = $this->get_comment($comment_id);
            if (!empty($comment)) {
                $data = array(
                    'like_count' => $comment->like_count + 1
                );
                $this->db->where('id', $comment->id);
                if ($this->db->update('comments', $data)) {
                    setcookie($cookie_vote, 'plus', time() + (86400 * 60), "/");
                    return true;
                }
            }
        } else {
            if ($_COOKIE[$cookie_vote] == 'minus') {
                $comment = $this->get_comment($comment_id);
                if (!empty($comment)) {
                    $data = array(
                        'like_count' => $comment->like_count + 1
                    );
                    $this->db->where('id', $comment->id);
                    if ($this->db->update('comments', $data)) {
                        setcookie($cookie_vote, "", time() - 3600, "/");
                        return true;
                    }
                }
            }
        }
        return false;
    }

    //dislike comment
    public function dislike_comment($comment_id)
    {
        $comment_id = sql_escape_number($comment_id);
        //check comment owner
        if (isset($_COOKIE['vr_added_comment_id_' . $comment_id])) {
            return false;
        }

        $cookie_vote = 'vr_comment_vote_' . $comment_id;
        if (!isset($_COOKIE[$cookie_vote])) {
            $comment = $this->get_comment($comment_id);
            if (!empty($comment)) {
                $data = array(
                    'like_count' => $comment->like_count - 1
                );
                $this->db->where('id', $comment->id);
                if ($this->db->update('comments', $data)) {
                    setcookie($cookie_vote, 'minus', time() + (86400 * 60), "/");
                    return true;
                }
            }
        } else {
            if ($_COOKIE[$cookie_vote] == 'plus') {
                $comment = $this->get_comment($comment_id);
                if (!empty($comment)) {
                    $data = array(
                        'like_count' => $comment->like_count - 1
                    );
                    $this->db->where('id', $comment->id);
                    if ($this->db->update('comments', $data)) {
                        setcookie($cookie_vote, "", time() - 3600, "/");
                        return true;
                    }
                }
            }
        }
        return false;
    }

    //get comment
    public function get_comment($id)
    {
        $id = sql_escape_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('comments');
        return $query->row();
    }

    //post comment count
    public function post_comment_count($post_id)
    {
        $post_id = sql_escape_number($post_id);
        $this->db->join('posts', 'comments.post_id = posts.id');
        $this->db->where('post_id', $post_id);
        $this->db->where('parent_id', 0);
        $query = $this->db->get('comments');
        return $query->num_rows();
    }

    //get comments
    public function get_comments($post_id, $limit)
    {
        $post_id = sql_escape_number($post_id);
        $limit = sql_escape_number($limit);
        $this->db->join('posts', 'comments.post_id = posts.id');
        $this->db->where('post_id', $post_id);
        $this->db->where('parent_id', 0);
        $this->db->select('comments.*');
        $this->db->limit($limit);
        $this->db->order_by('comments.id', 'DESC');
        $query = $this->db->get('comments');
        return $query->result();
    }

    //get all comments
    public function get_all_comments()
    {
        $this->db->join('posts', 'comments.post_id = posts.id');
        $this->db->select('comments.*,posts.title as post_title ');
		$this->db->order_by('comments.created_at', 'DESC');
        $query = $this->db->get('comments');
        return $query->result();
    }

    //get last comments
    public function get_last_comments($limit)
    {
        $limit = sql_escape_number($limit);
        $this->db->join('posts', 'comments.post_id = posts.id');
        $this->db->select('comments.* , posts.title_slug as post_slug, ');
        $this->db->order_by('comments.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('comments');
        return $query->result();
    }

    //get subcomments
    public function get_subcomments($comment_id)
    {
        $comment_id = sql_escape_number($comment_id);
        $this->db->join('posts', 'comments.post_id = posts.id');
        $this->db->where('parent_id', $comment_id);
        $this->db->select('comments.*');
        $this->db->order_by('comments.id', 'DESC');
        $query = $this->db->get('comments');
        return $query->result();
    }

    //get post comment count
    public function get_post_comment_count($post_id)
    {
        $post_id = sql_escape_number($post_id);
        $this->db->where('post_id', $post_id);
        $this->db->where('parent_id', 0);
        $query = $this->db->get('comments');
        return $query->num_rows();
    }

    //get comment count
    public function get_comment_count()
    {
        $query = $this->db->get('comments');
        return $query->num_rows();
    }

    //delete comment
    public function delete_comment($id)
    {
        $id = sql_escape_number($id);
        $subcomments = $this->get_subcomments($id);
        if (!empty($subcomments)) {
            foreach ($subcomments as $comment) {
                $this->delete_subcomments($comment->id);
                $this->db->where('id', $comment->id);
                $this->db->delete('comments');
            }
        }

        $this->db->where('id', $id);
        return $this->db->delete('comments');
    }

    //delete sub comments
    public function delete_subcomments($id)
    {
        $id = sql_escape_number($id);
        $subcomments = $this->get_subcomments($id);

        if (!empty($subcomments)) {
            foreach ($subcomments as $comment) {
                $this->db->where('id', $comment->id);
                $this->db->delete('comments');
            }
        }

    }

    //delete multi post
    public function delete_multi_comments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                $subcomments = $this->get_subcomments($id);

                if (!empty($subcomments)) {
                    foreach ($subcomments as $comment) {
                        $this->delete_subcomments($comment->id);
                        $this->db->where('id', $comment->id);
                        $this->db->delete('comments');
                    }
                }

                $this->db->where('id', $id);
                $this->db->delete('comments');
            }
        }
    }


}
