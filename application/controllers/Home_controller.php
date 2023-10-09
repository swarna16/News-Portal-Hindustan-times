<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page
     */
    public function index()
    {
        $data['title'] = $this->settings->home_title;
        $data['description'] = $this->settings->site_description;
        $data['keywords'] = $this->settings->keywords;
        $data['home_title'] = $this->settings->home_title;
        $data['visible_posts_count'] = $this->post_load_more_count;
        $data['post_load_more_count'] = $this->post_load_more_count;
        $data['language_settings'] = $this->selected_lang->id;
        $data['total_posts_count'] = $this->total_posts_count;

        //feature posts
        $data['featured_posts'] = get_cached_data('featured_posts');
        if (empty($data['featured_posts'])) {
            $data['featured_posts'] = $this->post_model->get_featured_posts();
            set_cache_data('featured_posts', $data['featured_posts']);
        }
        //slider posts
        $data['slider_posts'] = get_cached_data('slider_posts');
        if (empty($data['slider_posts'])) {
            $data['slider_posts'] = $this->post_model->get_slider_posts();
            set_cache_data('slider_posts', $data['slider_posts']);
        }
        //latest posts
        $data['latest_posts'] = get_cached_data('latest_posts');
        if (empty($data['latest_posts'])) {
            $data['latest_posts'] = $this->post_model->get_last_posts($this->selected_lang->id, $this->latest_post_count);
            set_cache_data('latest_posts', $data['latest_posts']);
        }
        //breaking news
        $data['breaking_news'] = get_cached_data('breaking_news');
        if (empty($data['breaking_news'])) {
            $data['breaking_news'] = $this->post_model->get_breaking_news();
            set_cache_data('breaking_news', $data['breaking_news']);
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('index', $data);
        $this->load->view('partials/_footer', $data);
    }


    /**
     * Posts Page
     */
    public function posts()
    {
        $data['title'] = trans("posts");
        $data['description'] = trans("posts") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("posts") . "," . $this->settings->application_name;

        //set paginated
        $pagination = $this->paginate(lang_base_url() . "posts", $this->total_posts_count);

        $data['posts'] = get_cached_data('posts_page_' . $pagination['current_page']);
        if (empty($data['posts'])) {
            $data['posts'] = $this->post_model->get_paginated_posts($pagination['per_page'], $pagination['offset']);
            set_cache_data('posts_page_' . $pagination['current_page'], $data['posts']);
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('post/posts', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Gallery Page
     */
    public function gallery()
    {
        $data['page'] = $this->page_model->get_page_by_lang('gallery', $this->selected_lang->id);

        //check page exists
        $this->is_page_exists($data['page']);

        //check page auth
        $this->checkPageAuth($data['page']);

        if ($data['page']->visibility == 0) {
            $this->error_404();
        } else {

            $data['title'] = get_page_title($data['page']);
            $data['description'] = get_page_description($data['page']);
            $data['keywords'] = get_page_keywords($data['page']);

            //get gallery categories
            $data['gallery_albums'] = $this->gallery_category_model->get_albums_by_selected_lang();
            $data['page_gallery'] = true;

            $this->load->view('partials/_header', $data);
            $this->load->view('gallery/gallery', $data);
            $this->load->view('partials/_footer');
        }

    }

    /**
     * Gallery Album Page
     */
    public function gallery_album($id)
    {
        $data['page'] = $this->page_model->get_page_by_lang('gallery', $this->selected_lang->id);

        //check page exists
        $this->is_page_exists($data['page']);

        //check page auth
        $this->checkPageAuth($data['page']);

        if ($data['page']->visibility == 0) {
            $this->error_404();
        } else {

            $data['title'] = get_page_title($data['page']);
            $data['description'] = get_page_description($data['page']);
            $data['keywords'] = get_page_keywords($data['page']);

            //get album
            $data['album'] = $this->gallery_category_model->get_album($id);
            if (empty($data['album'])) {
                redirect($this->agent->referrer());
            }
            //get gallery images
            $data['gallery_images'] = $this->gallery_model->get_images_by_album($data['album']->id);
            $data['gallery_categories'] = $this->gallery_category_model->get_categories_by_album($data['album']->id);
            $data['page_gallery'] = true;

            $this->load->view('partials/_header', $data);
            $this->load->view('gallery/gallery_album', $data);
            $this->load->view('partials/_footer');
        }
    }

    /**
     * Category Page
     */
    public function category($slug)
    {
        $slug = clean_slug($slug);

        $data['category'] = $this->category_model->get_category_by_slug($slug);

        //check category exists
        if (empty($data['category'])) {
            redirect(lang_base_url());
        }

        $category_id = $data['category']->id;
        $data['title'] = $data['category']->name;
        $data['description'] = $data['category']->description;
        $data['keywords'] = $data['category']->keywords;

        //category type
        $data['category_type'] = "";
        if ($data['category']->parent_id == 0) {
            $data['category_type'] = "parent";
        } else {
            $data['category_type'] = "sub";
        }

        $count_key = 'posts_count_category' . $data['category']->id;
        $posts_key = 'posts_category' . $data['category']->id;

        //category posts count
        $total_rows = get_cached_data($count_key);
        if (empty($total_rows)) {
            $total_rows = $this->post_model->get_post_count_by_category($data['category_type'], $category_id);
            set_cache_data($count_key, $total_rows);
        }
        //set paginated
        $pagination = $this->paginate(lang_base_url() . 'category/' . $slug, $total_rows);
        $data['posts'] = get_cached_data($posts_key . '_page' . $pagination['current_page']);
        if (empty($data['posts'])) {
            $data['posts'] = $this->post_model->get_paginated_category_posts($data['category_type'], $category_id, $pagination['per_page'], $pagination['offset']);
            set_cache_data($posts_key . '_page' . $pagination['current_page'], $data['posts']);
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('category', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Tag Page
     */
    public function tag($tag_slug)
    {
        $tag_slug = clean_slug($tag_slug);

        $data['tag'] = $this->tag_model->get_tag($tag_slug);

        //check tag exists
        if (empty($data['tag'])) {
            redirect(lang_base_url());
        }
        $data['title'] = $data['tag']->tag;
        $data['description'] = trans("tag") . ': ' . $data['tag']->tag;
        $data['keywords'] = trans("tag") . ', ' . $data['tag']->tag;
        //set paginated
        $pagination = $this->paginate(lang_base_url() . 'tag/' . $tag_slug, $this->post_model->get_post_count_by_tag($tag_slug));
        $data['posts'] = $this->post_model->get_paginated_tag_posts($tag_slug, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('tag', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Redirect Post to Any
     */
    public function redirect_post_to_any($slug)
    {
        $slug = clean_slug($slug);
        redirect(lang_base_url() . $slug);
    }


    /**
     * Dynamic URL by Slug
     */
    public function any($slug)
    {
        $slug = clean_slug($slug);
        //index page
        if (empty($slug)) {
            redirect(lang_base_url());
        }
        $data['page'] = $this->page_model->get_page_by_lang($slug, $this->selected_lang->id);
        if (!empty($data['page'])) {
            if ($data['page']->visibility == 0) {
                $this->error_404();
            } else {
                //check page auth
                $this->checkPageAuth($data['page']);

                $data['title'] = $data['page']->title;
                $data['description'] = $data['page']->description;
                $data['keywords'] = $data['page']->keywords;
                $data['language_settings'] = $this->selected_lang->id;

                $this->load->view('partials/_header', $data);
                $this->load->view('page', $data);
                $this->load->view('partials/_footer');
            }

        } else {
            $this->post($slug);
        }
    }


    /**
     * Post Page
     */
    public function post($slug)
    {
        $data['post'] = $this->post_model->get_post($slug);
        //check if post exists
        if (empty($data['post'])) {
            $this->error_404();
        } else {
            $id = $data['post']->id;
            if (!auth_check() && $data['post']->need_auth == 1) {
                $this->session->set_flashdata('error', trans("message_post_auth"));
                redirect(lang_base_url() . "register");
            }
            $data["category"] = $this->category_model->get_category($data['post']->category_id);
            $data["subcategory"] = $this->category_model->get_category($data['post']->subcategory_id);
            $data['post_tags'] = $this->tag_model->get_post_tags($id);
            $data['post_image_count'] = $this->post_file_model->get_post_additional_image_count($id);
            $data['post_images'] = $this->post_file_model->get_post_additional_images($id);
            $data['post_user'] = $this->auth_model->get_user($data['post']->user_id);

            $data['comment_count'] = $this->comment_model->post_comment_count($data["post"]->id);
            $data['comments'] = $this->comment_model->get_comments($data["post"]->id, $this->comment_limit);
            $data['comment_limit'] = $this->comment_limit;

            $data['related_posts'] = $this->post_model->get_related_posts($data['post']->category_id, $id);
            $data['previous_post'] = $this->post_model->get_previous_post($id);
            $data['next_post'] = $this->post_model->get_next_post($id);

            $data['is_reading_list'] = $this->reading_list_model->is_post_in_reading_list($id);

            $data['post_type'] = $data['post']->post_type;

            if (!empty($data['post']->feed_id)) {
                $data['feed'] = $this->rss_model->get_feed($data['post']->feed_id);
            }

            $data['title'] = $data['post']->title;
            $data['description'] = $data['post']->summary;
            $data['keywords'] = $data['post']->keywords;

            $data['og_title'] = $data['post']->title;
            $data['og_description'] = $data['post']->summary;
            $data['og_type'] = "article";
            $data['og_url'] = post_url($data['post']);

            if (!empty($data['post']->image_url)) {
                $data['og_image'] = $data['post']->image_url;
            } else {
                $data['og_image'] = base_url() . $data['post']->image_default;
            }
            $data['og_width'] = "750";
            $data['og_height'] = "500";
            $data['og_creator'] = $data['post_user']->username;
            $data['og_author'] = $data['post_user']->username;
            $data['og_published_time'] = $data['post']->created_at;
            $data['og_modified_time'] = $data['post']->created_at;
            $data['og_tags'] = $data['post_tags'];

            $this->reaction_model->set_voted_reactions_session($id);
            $data["reactions"] = $this->reaction_model->get_reaction($id);
            $data["emoji_lang"] = $this->selected_lang->folder_name;

            //gallery post
            if ($data["post"]->post_type == "gallery") {
                $data['gallery_post_total_item_count'] = $this->post_item_model->get_gallery_post_items_count($data["post"]->id);
                $data['gallery_post_item'] = $this->post_item_model->get_gallery_post_item_by_order($data["post"]->id, 0);
                $data['gallery_post_item_order'] = 1;
            }
            //ordered list post
            if ($data["post"]->post_type == "ordered_list") {
                $data['ordered_list_items'] = $this->post_item_model->get_ordered_list_items($data["post"]->id);
            }

            $this->load->view('partials/_header', $data);
            $this->load->view('post/post', $data);
            $this->load->view('partials/_footer', $data);

            //increase post hit
            $this->post_model->increase_post_hit($data['post']);

        }
    }

    /**
     * Gallery Post Page
     */
    public function gallery_post($slug, $order)
    {
        $data['post'] = $this->post_model->get_post($slug);
        //check if post exists
        if (empty($data['post'])) {
            $this->error_404();
        } else {
            $id = $data['post']->id;
            if (!auth_check() && $data['post']->need_auth == 1) {
                $this->session->set_flashdata('error', trans("message_post_auth"));
                redirect(lang_base_url() . "register");
            }
            $data["category"] = $this->category_model->get_category($data['post']->category_id);
            $data["subcategory"] = $this->category_model->get_category($data['post']->subcategory_id);
            $data['post_tags'] = $this->tag_model->get_post_tags($id);
            $data['post_user'] = $this->auth_model->get_user($data['post']->user_id);

            $data['comment_count'] = $this->comment_model->post_comment_count($data["post"]->id);
            $data['comments'] = $this->comment_model->get_comments($data["post"]->id, $this->comment_limit);
            $data['comment_limit'] = $this->comment_limit;

            $data['related_posts'] = $this->post_model->get_related_posts($data['post']->category_id, $id);
            $data['previous_post'] = $this->post_model->get_previous_post($id);
            $data['next_post'] = $this->post_model->get_next_post($id);

            $data['is_reading_list'] = $this->reading_list_model->is_post_in_reading_list($id);

            $data['post_type'] = $data['post']->post_type;

            $data['title'] = $data['post']->title;
            $data['description'] = $data['post']->summary;
            $data['keywords'] = $data['post']->keywords;

            $data['og_title'] = $data['post']->title;
            $data['og_description'] = $data['post']->summary;
            $data['og_type'] = "article";
            $data['og_url'] = post_url($data['post']);
            $data['og_image'] = base_url() . $data['post']->image_default;
            $data['og_width'] = "750";
            $data['og_height'] = "500";
            $data['og_creator'] = $data['post_user']->username;
            $data['og_author'] = $data['post_user']->username;
            $data['og_published_time'] = $data['post']->created_at;
            $data['og_modified_time'] = $data['post']->created_at;
            $data['og_tags'] = $data['post_tags'];

            $this->reaction_model->set_voted_reactions_session($id);
            $data["reactions"] = $this->reaction_model->get_reaction($id);
            $data["emoji_lang"] = $this->selected_lang->folder_name;

            //gallery post item
            $data['gallery_post_total_item_count'] = $this->post_item_model->get_gallery_post_items_count($data["post"]->id);
            $data['gallery_post_item'] = $this->post_item_model->get_gallery_post_item_by_order($data["post"]->id, ($order - 1));

            if ($order <= 0) {
                redirect(post_url($data["post"]) . "/1");
            }
            if ($order > $data['gallery_post_total_item_count']) {
                redirect(post_url($data["post"]) . "/" . $data['gallery_post_total_item_count']);
            }
            $data['gallery_post_item_order'] = $order;

            $this->load->view('partials/_header', $data);
            $this->load->view('post/post', $data);
            $this->load->view('partials/_footer', $data);

        }
    }

    /**
     * Contact Page
     */
    public function contact()
    {
        $data['page'] = $this->page_model->get_page_by_lang('contact', $this->selected_lang->id);
        //check page auth
        $this->checkPageAuth($data['page']);

        if ($data['page']->visibility == 0) {
            $this->error_404();
        } else {
            $data['title'] = get_page_title($data['page']);
            $data['description'] = get_page_description($data['page']);
            $data['keywords'] = get_page_keywords($data['page']);
            $data['page_contact'] = true;

            $this->load->view('partials/_header', $data);
            $this->load->view('contact', $data);
            $this->load->view('partials/_footer');
        }

    }


    /**
     * Contact Page Post
     */
    public function contact_post()
    {
        //validate inputs
        $this->form_validation->set_rules('name', trans("placeholder_name"), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('email', trans("placeholder_email"), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('message', trans("placeholder_message"), 'required|xss_clean|max_length[5000]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->contact_model->input_values());
            redirect($this->agent->referrer());
        } else {
            if (!$this->recaptcha_verify_request()) {
                $this->session->set_flashdata('form_data', $this->contact_model->input_values());
                $this->session->set_flashdata('error', trans("msg_recaptcha"));
                redirect($this->agent->referrer());
            } else {
                if ($this->contact_model->add_contact_message()) {
                    $this->session->set_flashdata('success', trans("message_contact_success"));
                    redirect($this->agent->referrer());
                } else {
                    $this->session->set_flashdata('form_data', $this->contact_model->input_values());
                    $this->session->set_flashdata('error', trans("message_contact_error"));
                    redirect($this->agent->referrer());
                }
            }
        }
    }


    /**
     * Search Page
     */
    public function search()
    {
        $q = trim($this->input->get('q', TRUE));

        $data['q'] = $q;
        $data['title'] = trans("search") . ': ' . $q;
        $data['description'] = trans("search") . ': ' . $q;
        $data['keywords'] = trans("search") . ', ' . $q;
        //set paginated
        $pagination = $this->paginate(lang_base_url() . 'search', $this->post_model->get_search_post_count($q));
        $data['posts'] = $this->post_model->get_paginated_search_posts($q, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('search', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Rss Page
     */
    public function rss_feeds()
    {
        if ($this->general_settings->show_rss == 0) {
            $this->error_404();
        } else {

            $data['title'] = trans("rss_feeds");
            $data['description'] = trans("rss_feeds") . " - " . $this->settings->site_title;
            $data['keywords'] = trans("rss_feeds") . "," . $this->settings->application_name;

            $this->load->view('partials/_header', $data);
            $this->load->view('rss/rss_feeds', $data);
            $this->load->view('partials/_footer');

        }
    }

    /**
     * Rss All Posts
     */
    public function rss_latest_posts()
    {
        //load the library
        $this->load->helper('xml');
        if ($this->general_settings->show_rss == 1):
            $data['feed_name'] = $this->settings->site_title . " - " . trans("latest_posts");
            $data['encoding'] = 'utf-8';
            $data['feed_url'] = lang_base_url() . "rss/latest-posts";
            $data['page_description'] = $this->settings->site_title . " - " . trans("latest_posts");
            $data['page_language'] = $this->selected_lang->short_form;
            $data['creator_email'] = '';
            $data['posts'] = $this->post_model->get_rss_latest_posts(30);
            header("Content-Type: application/rss+xml; charset=utf-8");

            $this->load->view('rss/rss', $data);
        endif;
    }


    /**
     * Rss By Category
     */
    public function rss_by_category($slug)
    {
        $slug = clean_slug($slug);
        //load the library
        $this->load->helper('xml');
        if ($this->general_settings->show_rss == 1):
            $category = $this->category_model->get_category_by_slug($slug);
            $category_id = $category->id;
            $data['category'] = $this->category_model->get_category($category_id);
            if (empty($data['category'])) {
                redirect(lang_base_url() . 'rss-feeds');
            }

            $data['feed_name'] = $this->settings->site_title . " - " . trans("title_category") . ": " . $data['category']->name;
            $data['encoding'] = 'utf-8';
            $data['feed_url'] = lang_base_url() . "rss/category/" . $data['category']->name_slug;
            $data['page_description'] = $this->settings->site_title . " - " . trans("title_category") . ": " . $data['category']->name;
            $data['page_language'] = $this->selected_lang->short_form;
            $data['creator_email'] = '';
            $data['posts'] = $this->post_model->get_rss_posts_by_category($data['category']);
            header("Content-Type: application/rss+xml; charset=utf-8");

            $this->load->view('rss/rss', $data);
        endif;
    }


    /**
     * Rss By User
     */
    public function rss_by_user($slug)
    {
        $slug = clean_slug($slug);

        //load the library
        $this->load->helper('xml');
        if ($this->general_settings->show_rss == 1):
            $user = $this->auth_model->get_user_by_slug($slug);
            if (empty($user)) {
                redirect(lang_base_url() . 'rss-feeds');
            }
            $user_id = $user->id;
            $data['feed_name'] = $this->settings->site_title . " - " . $user->username;
            $data['encoding'] = 'utf-8';
            $data['feed_url'] = lang_base_url() . "rss/author/" . $user->slug;
            $data['page_description'] = $this->settings->site_title . " - " . $user->username;
            $data['page_language'] = $this->selected_lang->short_form;
            $data['creator_email'] = '';
            $data['posts'] = $this->post_model->get_rss_posts_by_user($user_id);
            header("Content-Type: application/rss+xml; charset=utf-8");

            $this->load->view('rss/rss', $data);
        endif;
    }

    /**
     * Add Comment
     */
    public function add_comment_post()
    {

        if ($this->general_settings->comment_system != 1) {
            exit();
        }
        $limit = $this->input->post('limit', true);
        $post_id = $this->input->post('post_id', true);

        if (auth_check()) {
            $this->comment_model->add_comment();
        } else {
            if ($this->recaptcha_verify_request()) {
                $this->comment_model->add_comment();
            }
        }

        $data["post"] = $this->post_admin_model->get_post($post_id);
        $data['comment_count'] = $this->comment_model->post_comment_count($post_id);
        $data['comments'] = $this->comment_model->get_comments($post_id, $limit);
        $data['comment_limit'] = $limit;

        $this->load->view('post/_comments', $data);
    }

    //delete comment
    public function delete_comment_post()
    {
        $id = $this->input->post('id', true);
        $post_id = $this->input->post('post_id', true);
        $limit = $this->input->post('limit', true);

        $comment = $this->comment_model->get_comment($id);
        if (auth_check() && !empty($comment)) {
            if (user()->role == "admin" || user()->id == $comment->user_id) {
                $this->comment_model->delete_comment($id);
            }
        }

        $data["post"] = $this->post_admin_model->get_post($post_id);
        $data['comments'] = $this->comment_model->get_comments($post_id, $limit);
        $data['comment_count'] = $this->comment_model->post_comment_count($post_id);
        $data['comment_limit'] = $limit;

        $this->load->view('post/_comments', $data);
    }

    //load subcomment box
    public function load_subcomment_box()
    {
        $comment_id = $this->input->post('comment_id', true);
        $limit = $this->input->post('limit', true);
        $data["parent_comment"] = $this->comment_model->get_comment($comment_id);
        $data["comment_limit"] = $limit;
        $this->load->view('post/_make_subcomment', $data);
    }

    //load more comment
    public function load_more_comment()
    {
        $post_id = $this->input->post('post_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->comment_limit;
        $data["post"] = $this->post_admin_model->get_post($post_id);
        $data["comments"] = $this->comment_model->get_comments($post_id, $new_limit);
        $data['comment_count'] = $this->comment_model->post_comment_count($post_id);
        $data['comment_limit'] = $new_limit;

        $this->load->view('post/_comments', $data);
    }


    /**
     * Like Comment
     */
    public function like_comment_post()
    {
        $comment_id = $this->input->post('id', true);
        if ($this->comment_model->like_comment($comment_id)) {
            $comment = $this->comment_model->get_comment($comment_id);
            $data = array(
                'result' => 1,
                'like_count' => $comment->like_count
            );
        } else {
            $data = array(
                'result' => 0,
                'like_count' => 0
            );
        }
        echo json_encode($data);
    }

    /**
     * Dislike Comment
     */
    public function dislike_comment_post()
    {
        $comment_id = $this->input->post('id', true);
        if ($this->comment_model->dislike_comment($comment_id)) {
            $comment = $this->comment_model->get_comment($comment_id);
            $data = array(
                'result' => 1,
                'like_count' => $comment->like_count
            );
        } else {
            $data = array(
                'result' => 0,
                'like_count' => 0
            );
        }
        echo json_encode($data);
    }


    /**
     * Add Poll Vote
     */
    public function add_vote()
    {
        $poll_id = $this->input->post('poll_id', true);
        $vote_permission = $this->input->post('vote_permission', true);
        $option = $this->input->post('option', true);
        if (is_null($option)) {
            echo "required";
        } else {
            if ($vote_permission == "all") {
                $result = $this->poll_model->add_unregistered_vote($poll_id, $option);
                if ($result == "success") {
                    $data["poll"] = $this->poll_model->get_poll($poll_id);
                    $this->load->view('partials/_poll_results', $data);
                } else {
                    echo "voted";
                }
            } else {
                $user_id = user()->id;
                $result = $this->poll_model->add_registered_vote($poll_id, $user_id, $option);
                if ($result == "success") {
                    $data["poll"] = $this->poll_model->get_poll($poll_id);
                    $this->load->view('partials/_poll_results', $data);
                } else {
                    echo "voted";
                }
            }
        }
    }


    /**
     * Add to Newsletter
     */
    public function add_to_newsletter()
    {
        //input values
        $email = $this->input->post('email', true);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('news_error', trans("message_invalid_email"));
        } else {
            if ($email) {
                //check if email exists
                if (empty($this->newsletter_model->get_subscriber($email))) {
                    //addd
                    if ($this->newsletter_model->add_subscriber($email)) {
                        $this->session->set_flashdata('news_success', trans("message_newsletter_success"));
                    }
                } else {
                    $this->session->set_flashdata('news_error', trans("message_newsletter_error"));
                }
            }

        }

        redirect($this->agent->referrer() . "#newsletter");
    }


    /**
     * Reading List Page
     */
    public function reading_list()
    {
        $data['title'] = trans("reading_list");
        $data['description'] = trans("reading_list") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("reading_list") . "," . $this->settings->application_name;

        //set paginated
        $pagination = $this->paginate(lang_base_url() . 'reading-list', $this->reading_list_model->get_reading_list_count());
        $data['posts'] = $this->reading_list_model->get_paginated_reading_list($pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('reading_list', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Load More Posts
     */
    public function load_more_posts()
    {
        $skip = $this->input->post("visible_posts_count");
        $lang_id = $this->input->post("index_visible_posts_lang_id");

        $data['latest_posts'] = get_cached_data('latest_posts' . $lang_id);
        if (empty($data['latest_posts'])) {
            $data['latest_posts'] = $this->post_model->get_last_posts($lang_id, $this->latest_post_count);
            set_cache_data_by_lang('latest_posts', $data['latest_posts'], $lang_id);
        }

        $data['total_posts_count'] = get_cached_data('total_posts_count' . $lang_id);
        if (empty($data['total_posts_count'])) {
            $data['total_posts_count'] = $this->post_model->get_post_count_by_lang($lang_id);
            set_cache_data_by_lang('total_posts_count', $data['total_posts_count'], $lang_id);
        }

        $data['skip'] = $skip;
        $data['visible_posts_count'] = $skip + $this->post_load_more_count;

		//set language
		if($this->general_settings->site_lang != $lang_id){
			$lang = $this->language_model->get_language($lang_id);
			if (!empty($lang)) {
				$this->lang_base_url = base_url() . $lang->short_form . "/";
			}
		}

        $this->load->view('post/_index_latest_posts', $data);
    }


    /**
     * Add or Delete Reading List
     */
    public function add_delete_reading_list_post()
    {
        $post_id = $this->input->post('post_id');

        if (empty($post_id)) {
            redirect($this->agent->referrer());
        }

        $is_post_in_reading_list = $this->reading_list_model->is_post_in_reading_list($post_id);

        //delete from list
        if ($is_post_in_reading_list == true) {
            $this->reading_list_model->delete_from_reading_list($post_id);
        } else {
            //add to list
            $this->reading_list_model->add_to_reading_list($post_id);
        }

        redirect($this->agent->referrer());
    }


    /**
     * Make Reaction
     */
    public function save_reaction()
    {
        $post_id = $this->input->post('post_id');
        $reaction = $this->input->post('reaction');
        $data["emoji_lang"] = $this->input->post('lang');

        $this->config->set_item('language', $data["emoji_lang"]);
        $this->lang->load("site_lang", $data["emoji_lang"]);

        $data["post"] = $this->post_admin_model->get_post($post_id);

        if (!empty($data["post"])) {
            $this->reaction_model->save_reaction($post_id, $reaction);
        }

        $data["reactions"] = $this->reaction_model->get_reaction($post_id);
        $this->load->view('partials/_emoji_reactions', $data);
    }

    /**
     * Download Audio
     */
    public function download_audio()
    {
        $this->load->helper('download');

        $id = $this->input->post('audio_id', true);
        $audio = $this->post_file_model->get_audio($id);
        force_download(FCPATH . $audio->audio_path, NULL);
    }

    //check page exits
    public function is_page_exists($page)
    {
        if (empty($page)) {
            redirect(lang_base_url());
        }
    }

    //cookies warning
    public function cookies_warning()
    {
        setcookie('vr_cookies', '1', time() + (86400 * 10), "/"); //10 days
    }

    //check page auth
    public function checkPageAuth($page)
    {
        if (!auth_check() && $page->need_auth == 1) {
            $this->session->set_flashdata('error', trans("message_page_auth"));
            redirect(lang_base_url() . "register");
        }
    }

    //error 404
    public function error_404()
    {
        $data['title'] = "Error 404";
        $data['description'] = "Error 404";
        $data['keywords'] = "error,404";

        $this->load->view('partials/_header', $data);
        $this->load->view('errors/error_404');
        $this->load->view('partials/_footer');
    }

}
