<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Start Footer Section -->
<footer id="footer">
    <div class="container">
        <div class="row footer-widgets">

            <!-- footer widget about-->
            <div class="col-sm-4 col-xs-12">
                <div class="footer-widget f-widget-about">
                    <div class="col-sm-12">
                        <div class="row">
                            <p class="footer-logo">
                                <img src="<?php echo get_logo_footer($vsettings); ?>" alt="logo" class="logo">
                            </p>
                            <p>
                                <?php echo html_escape($settings->about_footer); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div><!-- /.col-sm-4 -->

            <!-- footer widget random posts-->
            <div class="col-sm-4 col-xs-12">
                <!--Include footer random posts partial-->
                <?php $this->load->view('partials/_footer_random_posts'); ?>
            </div><!-- /.col-sm-4 -->


            <!-- footer widget follow us-->
            <div class="col-sm-4 col-xs-12">
                <div class="col-sm-12 footer-widget f-widget-follow">
                    <div class="row">
                        <h4 class="title"><?php echo html_escape(trans("footer_follow")); ?></h4>
                        <ul>
                            <!--Include social media links-->
                            <?php $this->load->view('partials/_social_media_links', ['rss_hide' => false]); ?>
                        </ul>
                    </div>
                </div>
                <?php if ($general_settings->newsletter == 1): ?>
                    <!-- newsletter -->
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="widget-newsletter">
                                <p><?php echo html_escape(trans("footer_newsletter")); ?></p>
                                <?php echo form_open('home_controller/add_to_newsletter'); ?>
                                <div class="newsletter">
                                    <div class="left">
                                        <input type="email" name="email" id="newsletter_email" maxlength="199"
                                               placeholder="<?php echo html_escape(trans("placeholder_email")); ?>"
                                               required <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                                    </div>
                                    <div class="right">
                                        <input type="submit" value="<?php echo html_escape(trans("subscribe")); ?>"
                                               class="newsletter-button">
                                    </div>
                                </div>
                                <p id="newsletter">
                                    <?php
                                    if ($this->session->flashdata('news_error')):
                                        echo '<span class="text-danger">' . $this->session->flashdata('news_error') . '</span>';
                                    endif;

                                    if ($this->session->flashdata('news_success')):
                                        echo '<span class="text-success">' . $this->session->flashdata('news_success') . '</span>';
                                    endif;
                                    ?>
                                </p>

                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>


                <?php endif; ?>
            </div>
            <!-- .col-md-3 -->
        </div>
        <!-- .row -->

        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-bottom-left">
                        <p><?php echo html_escape($settings->copyright); ?></p>
                    </div>

                    <div class="footer-bottom-right">
                        <ul class="nav-footer">
                            <?php if (!empty($this->menu_links)):
                                foreach ($this->menu_links as $item): ?>
                                    <?php if ($item['visibility'] == 1 && $item['lang_id'] == $this->language_id && $item['location'] == "footer"): ?>
                                        <li>
                                            <a href="<?php echo $item['link']; ?>"><?php echo html_escape($item['title']); ?> </a>
                                        </li>
                                    <?php endif;
                                endforeach;
                            endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- .row -->
        </div>
    </div>
</footer>
<!-- End Footer Section -->
<?php if (!isset($_COOKIE["vr_cookies"]) && $settings->cookies_warning): ?>
    <div class="cookies-warning">
        <div class="text"><?php echo $this->settings->cookies_warning_text; ?></div>
        <a href="javascript:void(0)" onclick="hide_cookies_warning();" class="icon-cl"> <i class="icon-close"></i></a>
    </div>
<?php endif; ?>
<script>
    var base_url = '<?php echo base_url(); ?>';
    var fb_app_id = '<?php echo $this->general_settings->facebook_app_id; ?>';
    var csfr_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csfr_cookie_name = '<?php echo $this->config->item('csrf_cookie_name'); ?>';
    var lang_folder = '<?php echo $this->selected_lang->folder_name; ?>';
    var is_recaptcha_enabled = false;
    <?php if ($recaptcha_status == true): ?>is_recaptcha_enabled = true;<?php endif; ?>
</script>
<!-- Scroll Up Link -->
<a href="#" class="scrollup"><i class="icon-arrow-up"></i></a>
<!-- Plugins-->
<script src="<?php echo base_url(); ?>assets/js/plugins-1.6.js"></script>
<?php if (isset($post) && $post->post_type == "audio"): ?>
    <script src="<?php echo base_url(); ?>assets/vendor/audio-player/js/amplitude.min.js"></script>
    <script type="text/javascript">
        Amplitude.init({
            "songs": [
                <?php foreach (get_post_audios($post->id) as $audio): ?>
                {
                    "name": '<?php echo html_escape($audio->audio_name);  ?>',
                    "artist": '<?php echo html_escape($audio->musician);  ?>',
                    "url": base_url + '<?php echo $audio->audio_path;  ?>',
                    "cover_art_url": base_url + '<?php echo $post->image_default;  ?>',
                },
                <?php endforeach; ?>
            ]
        });
    </script>
<?php endif; ?>
<?php if (isset($post_type) && $post_type == "video"): ?>
    <script src="<?php echo base_url(); ?>assets/vendor/video-player/videojs-ie8.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/video-player/video.min.js"></script>
<?php endif; ?>
<!-- Custom-->
<script>
    jQuery(document).ready(function(){$("#featured-slider").owlCarousel({autoplay:true,loop:true,rtl:rtl,lazyLoad:true,lazyLoadEager:2,slideSpeed:3000,paginationSpeed:1000,items:1,dots:false,nav:true,navText:["<i class='icon-arrow-slider-left random-arrow-prev' aria-hidden='true'></i>","<i class='icon-arrow-slider-right random-arrow-next' aria-hidden='true'></i>"],itemsDesktop:false,itemsDesktopSmall:false,itemsTablet:false,itemsMobile:false,onInitialize:function(b){if($("#owl-random-post-slider .item").length<=1){this.settings.loop=false}},});$("#random-slider").owlCarousel({autoplay:true,loop:true,rtl:rtl,lazyLoad:true,slideSpeed:3000,paginationSpeed:1000,items:1,dots:false,nav:true,navText:["<i class='icon-arrow-left post-detail-arrow-prev' aria-hidden='true'></i>","<i class='icon-arrow-right post-detail-arrow-next' aria-hidden='true'></i>"],itemsDesktop:false,itemsDesktopSmall:false,itemsTablet:false,itemsMobile:false,onInitialize:function(b){if($("#owl-random-post-slider .item").length<=1){this.settings.loop=false}},});$(".main-menu .dropdown").hover(function(){$(".li-sub-category").removeClass("active");$(".sub-menu-inner").removeClass("active");$(".sub-menu-right .filter-all").addClass("active")},function(){});$(".main-menu .navbar-nav .dropdown-menu").hover(function(){var b=$(this).attr("data-mega-ul");if(b!=undefined){$(".main-menu .navbar-nav .dropdown").removeClass("active");$(".mega-li-"+b).addClass("active")}},function(){$(".main-menu .navbar-nav .dropdown").removeClass("active")});$(document).on("click",".btn-open-mobile-nav",function(){document.getElementById("mobile-menu").style.width="100%";$("html").addClass("disable-body-scroll");$("body").addClass("disable-body-scroll")});$(document).on("click",".btn-close-mobile-nav",function(){document.getElementById("mobile-menu").style.width="0";$("html").removeClass("disable-body-scroll");$("body").removeClass("disable-body-scroll")});$(".li-sub-category").hover(function(){var b=$(this).attr("data-category-filter");$(".li-sub-category").removeClass("active");$(this).addClass("active");$(".sub-menu-right .sub-menu-inner").removeClass("active");$(".sub-menu-right .filter-"+b).addClass("active")},function(){});$(".news-ticker ul li").delay(500).fadeIn(500);$(".news-ticker").easyTicker({direction:"up",easing:"swing",speed:"fast",interval:4000,height:"30",visible:0,mousePause:1,controls:{up:".news-next",down:".news-prev",}});$(window).scroll(function(){if($(this).scrollTop()>100){$(".scrollup").fadeIn()}else{$(".scrollup").fadeOut()}});$(".scrollup").click(function(){$("html, body").animate({scrollTop:0},700);return false});$("form").submit(function(){$("input[name='"+csfr_token_name+"']").val($.cookie(csfr_cookie_name))});$(document).ready(function(){$('[data-toggle-tool="tooltip"]').tooltip()})});$("#form_validate").validate();$("#search_validate").validate();$(window).load(function(){$("#post-detail-slider").owlCarousel({navigation:true,rtl:rtl,slideSpeed:3000,paginationSpeed:1000,items:1,dots:false,nav:true,autoHeight:true,navText:["<i class='icon-arrow-left post-detail-arrow-prev' aria-hidden='true'></i>","<i class='icon-arrow-right post-detail-arrow-next' aria-hidden='true'></i>"],itemsDesktop:false,itemsDesktopSmall:false,itemsTablet:false,itemsMobile:false,onInitialize:function(b){if($("#owl-random-post-slider .item").length<=1){this.settings.loop=false}},})});$(window).load(function(){$(".show-on-page-load").css("visibility","visible")});$(document).ready(function(){$("iframe").attr("allowfullscreen","")});var custom_scrollbar=$(".custom-scrollbar");if(custom_scrollbar.length){var ps=new PerfectScrollbar(".custom-scrollbar",{wheelPropagation:true,suppressScrollX:true})}var custom_scrollbar=$(".custom-scrollbar-followers");if(custom_scrollbar.length){var ps=new PerfectScrollbar(".custom-scrollbar-followers",{wheelPropagation:true,suppressScrollX:true})}$(".search-icon").click(function(){if($(".search-form").hasClass("open")){$(".search-form").removeClass("open");$(".search-icon i").removeClass("icon-times");$(".search-icon i").addClass("icon-search")}else{$(".search-form").addClass("open");$(".search-icon i").removeClass("icon-search");$(".search-icon i").addClass("icon-times")}});$(document).ready(function(){$("#form-login").submit(function(a){a.preventDefault();var b=$(this);var c=b.serializeArray();c.push({name:csfr_token_name,value:$.cookie(csfr_cookie_name)});$.ajax({url:base_url+"auth_controller/login_post",type:"post",data:c,success:function(e){var d=JSON.parse(e);if(d.result==1){location.reload()}else{if(d.result==0){document.getElementById("result-login").innerHTML=d.error_message}}}})})});function make_reaction(c,d,b){var a={post_id:c,reaction:d,lang:b};a[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({method:"POST",url:base_url+"home_controller/save_reaction",data:a}).done(function(e){document.getElementById("reactions_result").innerHTML=e})}$(document).ready(function(){$("#make_comment_registered").submit(function(b){b.preventDefault();var c=$(this).serializeArray();var a={};var d=true;$(c).each(function(f,e){if($.trim(e.value).length<1){$("#make_comment_registered [name='"+e.name+"']").addClass("is-invalid");d=false}else{$("#make_comment_registered [name='"+e.name+"']").removeClass("is-invalid");a[e.name]=e.value}});a.limit=$("#post_comment_limit").val();a.lang_folder=lang_folder;a[csfr_token_name]=$.cookie(csfr_cookie_name);if(d==true){$.ajax({type:"POST",url:base_url+"home_controller/add_comment_post",data:a,success:function(e){document.getElementById("comment-result").innerHTML=e;$("#make_comment_registered")[0].reset()}})}});$("#make_comment").submit(function(b){b.preventDefault();var c=$(this).serializeArray();var a={};var d=true;$(c).each(function(f,e){if($.trim(e.value).length<1){$("#make_comment [name='"+e.name+"']").addClass("is-invalid");d=false}else{$("#make_comment [name='"+e.name+"']").removeClass("is-invalid");a[e.name]=e.value}});a.limit=$("#post_comment_limit").val();a.lang_folder=lang_folder;a[csfr_token_name]=$.cookie(csfr_cookie_name);if(is_recaptcha_enabled==true){if(typeof a["g-recaptcha-response"]==="undefined"){$(".g-recaptcha").addClass("is-recaptcha-invalid");d=false}else{$(".g-recaptcha").removeClass("is-recaptcha-invalid")}}if(d==true){$(".g-recaptcha").removeClass("is-recaptcha-invalid");$.ajax({type:"POST",url:base_url+"home_controller/add_comment_post",data:a,success:function(e){if(is_recaptcha_enabled==true){grecaptcha.reset()}document.getElementById("comment-result").innerHTML=e;$("#make_comment")[0].reset()}})}})});$(document).on("click",".btn-subcomment-registered",function(){var a=$(this).attr("data-comment-id");var b={};b.lang_folder=lang_folder;b[csfr_token_name]=$.cookie(csfr_cookie_name);$("#make_subcomment_registered_"+a).ajaxSubmit({beforeSubmit:function(){var d=$("#make_subcomment_registered_"+a).serializeArray();var c=$.trim(d[0].value);if(c.length<1){$(".form-comment-text").addClass("is-invalid");return false}else{$(".form-comment-text").removeClass("is-invalid")}},type:"POST",url:base_url+"home_controller/add_comment_post",data:b,success:function(c){document.getElementById("comment-result").innerHTML=c}})});$(document).on("click",".btn-subcomment",function(){var a=$(this).attr("data-comment-id");var b={};b.lang_folder=lang_folder;b[csfr_token_name]=$.cookie(csfr_cookie_name);b.limit=$("#post_comment_limit").val();var c="#make_subcomment_"+a;$(c).ajaxSubmit({beforeSubmit:function(){var d=$("#make_subcomment_"+a).serializeArray();var e=true;$(d).each(function(g,f){if($.trim(f.value).length<1){$(c+" [name='"+f.name+"']").addClass("is-invalid");e=false}else{$(c+" [name='"+f.name+"']").removeClass("is-invalid");b[f.name]=f.value}});if(is_recaptcha_enabled==true){if(typeof b["g-recaptcha-response"]==="undefined"){$(c+" .g-recaptcha").addClass("is-recaptcha-invalid");e=false}else{$(c+" .g-recaptcha").removeClass("is-recaptcha-invalid")}}if(e==false){return false}},type:"POST",url:base_url+"home_controller/add_comment_post",data:b,success:function(d){if(is_recaptcha_enabled==true){grecaptcha.reset()}document.getElementById("comment-result").innerHTML=d}})});function load_more_comment(c){var b=parseInt($("#post_comment_limit").val());var a={post_id:c,limit:b};a.lang_folder=lang_folder;a[csfr_token_name]=$.cookie(csfr_cookie_name);$("#load_comment_spinner").show();$.ajax({type:"POST",url:base_url+"home_controller/load_more_comment",data:a,success:function(d){setTimeout(function(){$("#load_comment_spinner").hide();document.getElementById("comment-result").innerHTML=d},1000)}})}function delete_comment(a,c,b){swal({text:b,icon:"warning",buttons:true,dangerMode:true,}).then(function(f){if(f){var e=parseInt($("#post_comment_limit").val());var d={id:a,post_id:c,limit:e};d.lang_folder=lang_folder;d[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({type:"POST",url:base_url+"home_controller/delete_comment_post",data:d,success:function(g){document.getElementById("comment-result").innerHTML=g}})}})}function show_comment_box(a){$(".visible-sub-comment").empty();var c=parseInt($("#post_comment_limit").val());var b={comment_id:a,limit:c};b.lang_folder=lang_folder;b[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({type:"POST",url:base_url+"home_controller/load_subcomment_box",data:b,success:function(d){$("#sub_comment_form_"+a).append(d)}})}function like_comment(b){var c=parseInt($("#post_comment_limit").val());var a={id:b,limit:c};a[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({type:"POST",url:base_url+"home_controller/like_comment_post",data:a,success:function(e){var d=JSON.parse(e);if(d.result==1){document.getElementById("lbl_comment_like_count_"+b).innerHTML=d.like_count}}})}function dislike_comment(b){var c=parseInt($("#post_comment_limit").val());var a={id:b,limit:c};a[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({type:"POST",url:base_url+"home_controller/dislike_comment_post",data:a,success:function(e){var d=JSON.parse(e);if(d.result==1){document.getElementById("lbl_comment_like_count_"+b).innerHTML=d.like_count}}})}function view_poll_results(b){$("#poll_"+b+" .question").hide();$("#poll_"+b+" .result").show()}function view_poll_options(b){$("#poll_"+b+" .result").hide();$("#poll_"+b+" .question").show()}$(document).ready(function(){var b;$(".poll-form").submit(function(h){h.preventDefault();if(b){b.abort()}var a=$(this);var g=a.find("input, select, button, textarea");var j=a.serializeArray();j.push({name:csfr_token_name,value:$.cookie(csfr_cookie_name)});var i=$(this).attr("data-form-id");b=$.ajax({url:base_url+"home_controller/add_vote",type:"post",data:j,});b.done(function(c){g.prop("disabled",false);if(c=="required"){$("#poll-required-message-"+i).show();$("#poll-error-message-"+i).hide()}else{if(c=="voted"){$("#poll-required-message-"+i).hide();$("#poll-error-message-"+i).show()}else{document.getElementById("poll-results-"+i).innerHTML=c;$("#poll_"+i+" .result").show();$("#poll_"+i+" .question").hide()}}})})});function open_mobile_nav(){document.getElementById("mobile-menu").style.width="100%";$("html").addClass("disable-body-scroll");$("body").addClass("disable-body-scroll")}function close_mobile_nav(){document.getElementById("mobile-menu").style.width="0";$("html").removeClass("disable-body-scroll");$("body").removeClass("disable-body-scroll")}$(".close-menu-click").click(function(){document.getElementById("mobile-menu").style.width="0"});function add_delete_from_reading_list(d){$(".tooltip").hide();var c={post_id:d,};c[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({method:"POST",url:base_url+"home_controller/add_delete_reading_list_post",data:c}).done(function(a){location.reload()})}function load_more_posts(){$(".btn-load-more").prop("disabled",true);var c=parseInt($("#post_load_more_count").val());var b={visible_posts_count:$("#index_visible_posts_count").val(),index_visible_posts_lang_id:$("#index_visible_posts_lang_id").val()};b[csfr_token_name]=$.cookie(csfr_cookie_name);$("#load_posts_spinner").show();$.ajax({method:"POST",url:base_url+"home_controller/load_more_posts",data:b}).done(function(a){setTimeout(function(){$("#load_posts_spinner").hide();$("#last_posts_content").append(a);$(".btn-load-more").prop("disabled",false)},500);var d=parseInt($("#index_visible_posts_count").val());$("#index_visible_posts_count").val((d+c).toString())})}function load_more_comments(f){var e=parseInt($("#vr_comment_limit").val());var d={post_id:f,limit:e,};d[csfr_token_name]=$.cookie(csfr_cookie_name);$("#load_comments_spinner").show();$.ajax({method:"POST",url:base_url+"home_controller/load_more_comments",data:d}).done(function(a){setTimeout(function(){$("#load_comments_spinner").hide();$("#vr_comment_limit").val(e+5);document.getElementById("comment-result").innerHTML=a},500)})}function hide_cookies_warning(){$(".cookies-warning").hide();var a={};a[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({type:"POST",url:base_url+"home_controller/cookies_warning",data:a,success:function(b){}})}$("#print_post").on("click",function(){$(".post-content .title, .post-content .post-meta, .post-content .post-image, .post-content .post-text").printThis({importCSS:true,})});$(document).ajaxStop(function(){function d(a){$("#poll_"+a+" .question").hide();$("#poll_"+a+" .result").show()}function c(a){$("#poll_"+a+" .result").hide();$("#poll_"+a+" .question").show()}});$(document).ready(function(){$(".validate_terms").submit(function(a){if(!$(".checkbox_terms_conditions").is(":checked")){a.preventDefault();$(".custom-checkbox .checkbox-icon").addClass("is-invalid")}else{$(".custom-checkbox .checkbox-icon").removeClass("is-invalid")}})});$(document).ready(function(){$(".post-content .post-text table").each(function(){table=$(this);tableRow=table.find("tr");table.find("td").each(function(){tdIndex=$(this).index();if($(tableRow).find("th").eq(tdIndex).attr("data-label")){thText=$(tableRow).find("th").eq(tdIndex).data("label")}else{thText=$(tableRow).find("th").eq(tdIndex).text()}$(this).attr("data-label",thText)})})});$(document).ready(function(){$(".gallery-post-buttons a").css("opacity","1")});$(document).ready(function(b){b(".image-popup-single").magnificPopup({type:"image",titleSrc:function(a){return a.el.attr("title")+"<small></small>"},image:{verticalFit:true,},gallery:{enabled:false,navigateByImgClick:true,preload:[0,1]},removalDelay:100,fixedContentPos:true,})});
</script>
<?php if (isset($page_gallery)): ?>
    <script src="<?php echo base_url(); ?>assets/vendor/masonry-filter/imagesloaded.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/masonry-filter/masonry-3.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/masonry-filter/masonry.filter.js"></script>
    <script>
        $(document).ready(function(t){t(".image-popup").magnificPopup({type:"image",titleSrc:function(t){return t.el.attr("title")+"<small></small>"},image:{verticalFit:!0},gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]},removalDelay:100,fixedContentPos:!0})}),$(document).ready(function(){$(document).on("click touchstart",".filters .btn",function(){$(".filters .btn").removeClass("active"),$(this).addClass("active")}),$(function(){var i=$("#masonry");i.imagesLoaded(function(){i.masonry({gutterWidth:0,isAnimated:!0,itemSelector:".gallery-item"})}),$(".filters .btn").click(function(t){t.preventDefault();var e=$(this).attr("data-filter");i.masonryFilter({filter:function(){return!e||$(this).attr("data-filter")==e}})})})});
    </script>
<?php endif; ?>
<?php if (isset($page_contact)): ?>
    <script>
        var iframe = document.getElementById("contact_iframe");
        if (iframe) {
            iframe.src = iframe.src;
            iframe.src = iframe.src;
        }
    </script>
<?php endif; ?>
<?php if (isset($page_confirm)): ?>
    <script>
        $(document).ready(function () {
            $('.circle-loader').toggleClass('load-complete');
            $('.checkmark').toggle();
        });
    </script>
<?php endif; ?>
<?php if (isset($post_type)): ?>
    <?php echo $general_settings->facebook_comment; ?>
    <script>
        $(function () {
            $('.post-text iframe').wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
            $('.post-text iframe').addClass('embed-responsive-item');
        });
        $(".fb-comments").attr("data-href", window.location.href);
    </script>
<?php endif; ?>
<?php echo $general_settings->google_analytics; ?>
</body>
</html>
