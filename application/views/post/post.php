<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Section: wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <!-- breadcrumb -->
            <div class="col-sm-12 page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo lang_base_url(); ?>"><?php echo trans("breadcrumb_home"); ?></a>
                    </li>
                    <?php if (!empty($category)): ?>
                        <li class="breadcrumb-item">
                            <a href="<?php echo lang_base_url(); ?>category/<?php echo html_escape($category->name_slug); ?>">
                                <?php echo html_escape($category->name); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($subcategory)): ?>
                        <li class="breadcrumb-item">
                            <a href="<?php echo lang_base_url(); ?>category/<?php echo html_escape($subcategory->name_slug); ?>">
                                <?php echo html_escape($subcategory->name); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active"> <?php echo html_escape(character_limiter($post->title, 160, '...')); ?></li>
                </ol>
            </div>

            <div id="content" class="<?php echo ($post->show_right_column == 1) ? 'col-sm-8' : 'col-sm-12'; ?> col-xs-12">

                <div class="post-content">

                    <?php if (!empty($subcategory)): ?>

                        <p class="m-0">
                            <a href="<?php echo lang_base_url(); ?>category/<?php echo html_escape($subcategory->name_slug) ?>">
                                <label class="category-label"
                                       style="background-color: <?php echo html_escape($subcategory->color); ?>">
                                    <?php echo html_escape($subcategory->name); ?>
                                </label>
                            </a>
                        </p>

                    <?php else: ?>

                        <?php if (!empty($category)): ?>
                            <p class="m-0">
                                <a href="<?php echo lang_base_url(); ?>category/<?php echo html_escape($category->name_slug) ?>">
                                    <label class="category-label"
                                           style="background-color: <?php echo html_escape($category->color); ?>">
                                        <?php echo html_escape($category->name); ?>
                                    </label>
                                </a>
                            </p>
                        <?php endif; ?>

                    <?php endif; ?>

                    <h1 class="title"><?php echo html_escape($post->title); ?></h1>
                    <?php if (!empty($post->summary)): ?>
                        <div class="post-summary">
                            <h2>
                                <?php echo $post->summary; ?>
                            </h2>
                        </div>
                    <?php endif; ?>
                    <p class="post-meta">
                        <?php if ($general_settings->show_post_author == 1): ?>
                            <span class="post-author-meta sp-left">
                                <a href="<?php echo lang_base_url(); ?>profile/<?php echo html_escape($post->user_slug); ?>" class="m-r-0">
                                    <img src="<?php echo get_user_avatar_by_id($post->user_id); ?>"
                                         alt="<?php echo html_escape($post->username); ?>">
                                    <?php echo html_escape($post->username); ?>
                                </a>
                            </span>
                        <?php endif; ?>

                        <?php if ($general_settings->show_post_date == 1): ?>
                            <span class="sp-left"><?php echo helper_date_format($post->created_at); ?>&nbsp;<?php echo formatted_hour($post->created_at); ?></span>
                        <?php endif; ?>

                        <?php if ($general_settings->show_hits): ?>
                            <span class="sp-right"><i class="icon-eye"></i><?php echo get_post_hit_count($post->id); ?></span>
                        <?php endif; ?>

                        <?php if ($general_settings->comment_system == 1): ?>
                            <span class="sp-right"><i
                                        class="icon-comment"></i><?php echo get_post_comment_count($post->id); ?></span>
                        <?php endif; ?>
                    </p>

                    <div class="post-share">
                        <!--include Social Share -->
                        <?php $this->load->view('post/_post_share_box'); ?>
                    </div>

                    <?php if ($post->post_type == "video"):
                        $this->load->view('post/_post_details_video', ['post' => $post]);
                    elseif ($post->post_type == "audio"):
                        $this->load->view('post/_post_details_audio', ['post' => $post]);
                    elseif ($post->post_type == "gallery"):
                        $this->load->view('post/_post_details_gallery.php', ['post' => $post]);
                    elseif ($post->post_type == "ordered_list"):
                        $this->load->view('post/_post_details_ordered_list.php', ['post' => $post]);
                    else:
                        $this->load->view('post/_post_details', ['post' => $post]);
                    endif; ?>

                    <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "post_top", "class" => "bn-p-t-20"]); ?>

                    <div class="post-text">
                        <?php echo $post->content; ?>
                    </div>

                    <!--Optional Url Button -->
                    <?php if (!empty($post->optional_url)) : ?>
                        <div class="optional-url-cnt">
                            <a href="<?php echo html_escape($post->optional_url); ?>" class="btn btn-md btn-custom" target="_blank">
                                <?php echo html_escape($settings->optional_url_button_name); ?>&nbsp;&nbsp;&nbsp;<i class="icon-long-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!--Optional Url Button -->
                    <?php if (!empty($feed) && !empty($post->show_post_url)) : ?>
                        <div class="optional-url-cnt">
                            <a href="<?php echo $post->post_url; ?>" class="btn btn-md btn-custom" target="_blank">
                                <?php echo htmlspecialchars($feed->read_more_button_text); ?>&nbsp;&nbsp;&nbsp;<i class="icon-long-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="post-tags">
                        <?php if (!empty($post_tags)): ?>
                            <h2 class="tags-title"><?php echo trans("post_tags"); ?></h2>
                            <ul class="tag-list">
                                <?php foreach ($post_tags as $tag) : ?>
                                    <li>
                                        <a href="<?php echo lang_base_url() . 'tag/' . html_escape($tag->tag_slug); ?>">
                                            <?php echo html_escape($tag->tag); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                </div>

                <!--include next previous post -->
                <?php $this->load->view('post/_post_next_prev', ['previous_post' => $previous_post, 'next_post' => $next_post]); ?>

                <?php if ($general_settings->emoji_reactions == 1): ?>
                    <div class="col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="reactions noselect">
                                <h4 class="title-reactions"><?php echo trans("whats_your_reaction"); ?></h4>
                                <div id="reactions_result">
                                    <?php $this->load->view('partials/_emoji_reactions'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!--Include banner-->
                <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "post_bottom", "class" => "bn-p-b"]); ?>

                <!--include about author -->
                <?php
                if ($general_settings->show_post_author == 1): ?>
                    <?php $this->load->view('post/_post_about_author', ['post_user' => $post_user]); ?>
                <?php endif; ?>

                <section class="section section-related-posts">
                    <div class="section-head">
                        <h4 class="title"><?php echo trans("related_posts"); ?></h4>
                    </div>

                    <div class="section-content">
                        <div class="row">
                            <?php $i = 0; ?>
                            <?php foreach ($related_posts as $item): ?>

                                <?php if ($i > 0 && $i % 3 == 0): ?>
                                    <div class="col-sm-12"></div>
                                <?php endif; ?>

                                <!--include post item-->
                                <div class="col-sm-4 col-xs-12">
                                    <?php $this->load->view("post/_post_item_mid", ["post" => $item]); ?>
                                </div>

                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <?php if ($general_settings->comment_system == 1 || $general_settings->facebook_comment_active == 1): ?>
                    <section id="comments" class="section">
                        <div class="col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="comment-section">
                                    <?php if ($general_settings->comment_system == 1 || $general_settings->facebook_comment_active == 1): ?>
                                        <ul class="nav nav-tabs">
                                            <?php if ($general_settings->comment_system == 1): ?>
                                                <li class="active"><a data-toggle="tab" href="#site_comments"><?php echo trans("comments"); ?></a></li>
                                            <?php endif; ?>
                                            <?php if ($general_settings->facebook_comment_active == 1): ?>
                                                <li class="<?php echo ($general_settings->comment_system != 1) ? 'active' : ''; ?>"><a data-toggle="tab" href="#facebook_comments"><?php echo trans("facebook_comments"); ?></a></li>
                                            <?php endif; ?>
                                        </ul>

                                        <div class="tab-content">
                                            <?php if ($general_settings->comment_system == 1): ?>
                                                <div id="site_comments" class="tab-pane fade in active">
                                                    <!-- include comments -->
                                                    <?php $this->load->view('post/_make_comment', ['post' => $post]); ?>
                                                    <div id="comment-result">
                                                        <?php $this->load->view('post/_comments'); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($general_settings->facebook_comment_active == 1): ?>
                                                <div id="facebook_comments" class="tab-pane fade <?php echo ($general_settings->comment_system != 1) ? 'in active' : ''; ?>">
                                                    <div class="fb-comments" data-href="<?php echo current_url(); ?>" data-width="100%" data-numposts="5"
                                                         data-colorscheme="light"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

            </div>

            <?php if ($post->show_right_column == 1): ?>
                <div id="sidebar" class="col-sm-4 col-xs-12">
                    <!--include sidebar -->
                    <?php $this->load->view('partials/_sidebar'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


</div>
<!-- /.Section: wrapper -->

<?php if (!empty($post->feed_id)): ?>
    <style>
        .post-text img {
            display: none;
        }

        .post-content .post-summary {
            display: none;
        }
    </style>
<?php endif; ?>

