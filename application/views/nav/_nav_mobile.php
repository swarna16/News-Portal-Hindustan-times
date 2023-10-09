<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="mobile-menu" class="mobile-menu">
    <div class="mobile-menu-inner">
        <a href="javascript:void(0)" class="closebtn" onclick="close_mobile_nav();"><i class="icon-close"></i></a>
        <div class="row">

            <div class="col-sm-12 mobile-menu-logo-cnt">
                <a href="<?php echo lang_base_url(); ?>">
                    <img src="<?php echo get_logo($vsettings); ?>" alt="logo" class="logo">
                </a>
            </div>

            <?php if (check_user_permission('add_post')): ?>
                <div class="col-sm-12 m-b-30">
                    <button class="btn btn-custom btn-create btn-block close-menu-click" data-toggle="modal" data-target="#modal_add_post"><i class="icon-plus"></i> <?php echo trans("add_post"); ?></button>
                </div>
            <?php endif; ?>

            <div class="col-sm-12">
                <nav class="navbar">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="<?php echo lang_base_url(); ?>">
                                <?php echo trans("home"); ?>
                            </a>
                        </li>
                        <?php if (!empty($this->menu_links)):
                            foreach ($this->menu_links as $item):
                                if ($item['visibility'] == 1 && $item['lang_id'] == $this->language_id && $item['location'] != "none" && $item['parent_id'] == "0"):
                                    $sub_links = helper_get_sub_menu_links($item['id'], $item['type']);
                                    if (!empty($sub_links)): ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                               aria-haspopup="true" aria-expanded="true">
                                                <?php echo html_escape($item['title']) ?>
                                                <i class="icon-arrow-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php if ($item['type'] == "category"): ?>
                                                    <li>
                                                        <a href="<?php echo lang_base_url(); ?>category/<?php echo html_escape($item['slug']) ?>"><?php echo trans("all"); ?></a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php foreach ($sub_links as $sub):
                                                    if ($sub["visibility"] == 1):?>
                                                        <li>
                                                            <a href="<?php echo $sub['link']; ?>">
                                                                <?php echo html_escape($sub['title']) ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php else: ?>
                                        <li>
                                            <a href="<?php echo $item['link']; ?>">
                                                <?php echo html_escape($item['title']); ?>
                                            </a>
                                        </li>
                                    <?php endif;
                                endif;
                            endforeach;
                        endif; ?>

                        <?php if (auth_check()) : ?>
                            <li class="dropdown profile-dropdown nav-item">
                                <a href="#" class="dropdown-toggle image-profile-drop nav-link" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <?php if (!empty(user()->avatar)) : ?>
                                        <img src="<?php echo base_url() . user()->avatar; ?>"
                                             alt="<?php echo html_escape(user()->username); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo base_url(); ?>assets/img/user.png"
                                             alt="<?php echo html_escape(user()->username); ?>">
                                    <?php endif; ?>
                                    <?php echo html_escape(character_limiter(user()->username, 20, '...')); ?>&nbsp;
                                    <i class="icon-arrow-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if (check_user_permission('admin_panel')): ?>
                                        <li>
                                            <a href="<?php echo admin_url(); ?>"><i class="icon-dashboard"></i>&nbsp;<?php echo trans("admin_panel"); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>profile/<?php echo $this->auth_user->slug; ?>"><i class="icon-user"></i>&nbsp;<?php echo trans("profile"); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo lang_base_url(); ?>reading-list"><i class="icon-star-o"></i>&nbsp;<?php echo html_escape(trans("reading_list")); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>settings"><i class="icon-settings"></i>&nbsp;<?php echo trans("settings"); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>logout"><i class="icon-logout"></i>&nbsp;<?php echo trans("logout"); ?></a>
                                    </li>
                                </ul>
                            </li>
                        <?php else : ?>
                            <?php if ($general_settings->registration_system == 1): ?>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#modal-login" class="close-menu-click btn_open_login_modal"><?php echo trans("login"); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo lang_base_url(); ?>register" class="close-menu-click"><?php echo trans("register"); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <ul class="mobile-menu-social">
                    <!--Include social media links-->
                    <?php $this->load->view('partials/_social_media_links', ['rss_hide' => false]); ?>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?php if ($general_settings->multilingual_system == 1 && count($languages) > 1): ?>
                    <div class="dropdown dropdown-mobile-languages">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="icon-language"></i>
                            <?php echo html_escape($selected_lang->name); ?>&nbsp;<span class="icon-arrow-down"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            foreach ($languages as $language):
                                $lang_url = base_url() . $language->short_form . "/";
                                if ($language->id == $this->general_settings->site_lang) {
                                    $lang_url = base_url();
                                } ?>
                                <li>
                                    <a href="<?php echo $lang_url; ?>" class="<?php echo ($language->id == $selected_lang->id) ? 'selected' : ''; ?> ">
                                        <?php echo html_escape($language->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>