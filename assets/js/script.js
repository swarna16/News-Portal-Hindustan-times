jQuery(document).ready(function () {
    //main slider
    $("#featured-slider").owlCarousel({
        autoplay: true,
        loop: true,
        rtl: rtl,
        lazyLoad: true,
        slideSpeed: 3000,
        paginationSpeed: 1000,
        items: 1,
        dots: false,
        nav: true,
        navText: ["<i class='icon-arrow-slider-left random-arrow-prev' aria-hidden='true'></i>", "<i class='icon-arrow-slider-right random-arrow-next' aria-hidden='true'></i>"],
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        onInitialize: function (a) {
            if ($("#owl-random-post-slider .item").length <= 1) {
                this.settings.loop = false
            }
        },
    });

    //random slider
    $("#random-slider").owlCarousel({
        autoplay: true,
        loop: true,
        rtl: rtl,
        lazyLoad: true,
        slideSpeed: 3000,
        paginationSpeed: 1000,
        items: 1,
        dots: false,
        nav: true,
        navText: ["<i class='icon-arrow-left post-detail-arrow-prev' aria-hidden='true'></i>", "<i class='icon-arrow-right post-detail-arrow-next' aria-hidden='true'></i>"],
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        onInitialize: function (a) {
            if ($("#owl-random-post-slider .item").length <= 1) {
                this.settings.loop = false
            }
        },
    });

    $(".main-menu .dropdown").hover(function () {
        $(".li-sub-category").removeClass("active");
        $(".sub-menu-inner").removeClass("active");
        $(".sub-menu-right .filter-all").addClass("active")
    }, function () {
    });
    $(".main-menu .navbar-nav .dropdown-menu").hover(function () {
        var a = $(this).attr("data-mega-ul");
        if (a != undefined) {
            $(".main-menu .navbar-nav .dropdown").removeClass("active");
            $(".mega-li-" + a).addClass("active")
        }
    }, function () {
        $(".main-menu .navbar-nav .dropdown").removeClass("active")
    });

    //mobile memu
    $(document).on('click', '.btn-open-mobile-nav', function () {
        document.getElementById("mobile-menu").style.width = "100%";
        $('html').addClass('disable-body-scroll');
        $('body').addClass('disable-body-scroll');
    });
    $(document).on('click', '.btn-close-mobile-nav', function () {
        document.getElementById("mobile-menu").style.width = "0";
        $('html').removeClass('disable-body-scroll');
        $('body').removeClass('disable-body-scroll');
    });

    $(".li-sub-category").hover(function () {
        var a = $(this).attr("data-category-filter");
        $(".li-sub-category").removeClass("active");
        $(this).addClass("active");
        $(".sub-menu-right .sub-menu-inner").removeClass("active");
        $(".sub-menu-right .filter-" + a).addClass("active")
    }, function () {
    });
    $(".news-ticker ul li").delay(500).fadeIn(500);
    $(".news-ticker").easyTicker({
        direction: "up",
        easing: "swing",
        speed: "fast",
        interval: 4000,
        height: "30",
        visible: 0,
        mousePause: 1,
        controls: {up: ".news-next", down: ".news-prev",}
    });
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $(".scrollup").fadeIn()
        } else {
            $(".scrollup").fadeOut()
        }
    });
    $(".scrollup").click(function () {
        $("html, body").animate({scrollTop: 0}, 700);
        return false
    });
    $("form").submit(function () {
        $("input[name='" + csfr_token_name + "']").val($.cookie(csfr_cookie_name))
    });
    $(document).ready(function () {
        $('[data-toggle-tool="tooltip"]').tooltip()
    })
});
$("#form_validate").validate();
$("#search_validate").validate();

//post slider
$(window).load(function () {
    $("#post-detail-slider").owlCarousel({
        navigation: true,
        rtl: rtl,
        slideSpeed: 3000,
        paginationSpeed: 1000,
        items: 1,
        dots: false,
        nav: true,
        autoHeight: true,
        navText: ["<i class='icon-arrow-left post-detail-arrow-prev' aria-hidden='true'></i>", "<i class='icon-arrow-right post-detail-arrow-next' aria-hidden='true'></i>"],
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        onInitialize: function (a) {
            if ($("#owl-random-post-slider .item").length <= 1) {
                this.settings.loop = false
            }
        },
    })
});

//show on page load
$(window).load(function () {
    $(".show-on-page-load").css("visibility", "visible")
});

//full screen
$(document).ready(function () {
    $("iframe").attr("allowfullscreen", "")
});
//custom scrollbar
var custom_scrollbar = $('.custom-scrollbar');
if (custom_scrollbar.length) {
    var ps = new PerfectScrollbar('.custom-scrollbar', {
        wheelPropagation: true,
        suppressScrollX: true
    });
}

//custom scrollbar
var custom_scrollbar = $('.custom-scrollbar-followers');
if (custom_scrollbar.length) {
    var ps = new PerfectScrollbar('.custom-scrollbar-followers', {
        wheelPropagation: true,
        suppressScrollX: true
    });
}

//search
$(".search-icon").click(function () {
    if ($(".search-form").hasClass("open")) {
        $(".search-form").removeClass("open");
        $(".search-icon i").removeClass("icon-times");
        $(".search-icon i").addClass("icon-search")
    } else {
        $(".search-form").addClass("open");
        $(".search-icon i").removeClass("icon-search");
        $(".search-icon i").addClass("icon-times")
    }
});

//login form
$(document).ready(function () {
    $("#form-login").submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var serializedData = form.serializeArray();
        serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
        $.ajax({
            url: base_url + "auth_controller/login_post",
            type: "post",
            data: serializedData,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.result == 1) {
                    location.reload();
                } else if (obj.result == 0) {
                    document.getElementById("result-login").innerHTML = obj.error_message;
                }
            }
        });
    })
});


//make reaction
function make_reaction(post_id, reaction, lang) {
    var data = {
        post_id: post_id,
        reaction: reaction,
        lang: lang
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        method: "POST",
        url: base_url + "home_controller/save_reaction",
        data: data
    }).done(function (response) {
        document.getElementById("reactions_result").innerHTML = response
    })
}

$(document).ready(function () {
    //make registered comment
    $("#make_comment_registered").submit(function (event) {
        event.preventDefault();
        var form_values = $(this).serializeArray();
        var data = {};
        var submit = true;
        $(form_values).each(function (i, field) {
            if ($.trim(field.value).length < 1) {
                $("#make_comment_registered [name='" + field.name + "']").addClass("is-invalid");
                submit = false;
            } else {
                $("#make_comment_registered [name='" + field.name + "']").removeClass("is-invalid");
                data[field.name] = field.value;
            }
        });
        data['limit'] = $('#post_comment_limit').val();
        data['lang_folder'] = lang_folder;
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        if (submit == true) {
            $.ajax({
                type: "POST",
                url: base_url + "home_controller/add_comment_post",
                data: data,
                success: function (response) {
                    document.getElementById("comment-result").innerHTML = response;
                    $("#make_comment_registered")[0].reset();
                }
            });
        }

    });

    //make comment
    $("#make_comment").submit(function (event) {
        event.preventDefault();
        var form_values = $(this).serializeArray();
        var data = {};
        var submit = true;
        $(form_values).each(function (i, field) {
            if ($.trim(field.value).length < 1) {
                $("#make_comment [name='" + field.name + "']").addClass("is-invalid");
                submit = false;
            } else {
                $("#make_comment [name='" + field.name + "']").removeClass("is-invalid");
                data[field.name] = field.value;
            }
        });
        data['limit'] = $('#post_comment_limit').val();
        data['lang_folder'] = lang_folder;
        data[csfr_token_name] = $.cookie(csfr_cookie_name);

        if (is_recaptcha_enabled == true) {
            if (typeof data['g-recaptcha-response'] === 'undefined') {
                $('.g-recaptcha').addClass("is-recaptcha-invalid");
                submit = false;
            } else {
                $('.g-recaptcha').removeClass("is-recaptcha-invalid");
            }
        }

        if (submit == true) {
            $('.g-recaptcha').removeClass("is-recaptcha-invalid");
            $.ajax({
                type: "POST",
                url: base_url + "home_controller/add_comment_post",
                data: data,
                success: function (response) {
                    if (is_recaptcha_enabled == true) {
                        grecaptcha.reset();
                    }
                    document.getElementById("comment-result").innerHTML = response;
                    $("#make_comment")[0].reset();
                }
            });
        }
    });

});

//make registered subcomment
$(document).on('click', '.btn-subcomment-registered', function () {
    var comment_id = $(this).attr("data-comment-id");
    var data = {};
    data['lang_folder'] = lang_folder;
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#make_subcomment_registered_" + comment_id).ajaxSubmit({
        beforeSubmit: function () {
            var form = $("#make_subcomment_registered_" + comment_id).serializeArray();
            var comment = $.trim(form[0].value);
            if (comment.length < 1) {
                $(".form-comment-text").addClass("is-invalid");
                return false;
            } else {
                $(".form-comment-text").removeClass("is-invalid");
            }
        },
        type: "POST",
        url: base_url + "home_controller/add_comment_post",
        data: data,
        success: function (response) {
            document.getElementById("comment-result").innerHTML = response;
        }
    })
});

//make subcomment
$(document).on('click', '.btn-subcomment', function () {
    var comment_id = $(this).attr("data-comment-id");
    var data = {};
    data['lang_folder'] = lang_folder;
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    data['limit'] = $('#post_comment_limit').val();
    var form_id = "#make_subcomment_" + comment_id;
    $(form_id).ajaxSubmit({
        beforeSubmit: function () {
            var form_values = $("#make_subcomment_" + comment_id).serializeArray();
            var submit = true;
            $(form_values).each(function (i, field) {
                if ($.trim(field.value).length < 1) {
                    $(form_id + " [name='" + field.name + "']").addClass("is-invalid");
                    submit = false;
                } else {
                    $(form_id + " [name='" + field.name + "']").removeClass("is-invalid");
                    data[field.name] = field.value;
                }
            });

            if (is_recaptcha_enabled == true) {
                if (typeof data['g-recaptcha-response'] === 'undefined') {
                    $(form_id + ' .g-recaptcha').addClass("is-recaptcha-invalid");
                    submit = false;
                } else {
                    $(form_id + ' .g-recaptcha').removeClass("is-recaptcha-invalid");
                }
            }

            if (submit == false) {
                return false;
            }
        },
        type: "POST",
        url: base_url + "home_controller/add_comment_post",
        data: data,
        success: function (response) {
            if (is_recaptcha_enabled == true) {
                grecaptcha.reset();
            }
            document.getElementById("comment-result").innerHTML = response;
        }
    })
});

//load more comment
function load_more_comment(post_id) {
    var limit = parseInt($("#post_comment_limit").val());
    var data = {
        "post_id": post_id,
        "limit": limit
    };
    data['lang_folder'] = lang_folder;
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#load_comment_spinner").show();
    $.ajax({
        type: "POST",
        url: base_url + "home_controller/load_more_comment",
        data: data,
        success: function (response) {
            setTimeout(function () {
                $("#load_comment_spinner").hide();
                document.getElementById("comment-result").innerHTML = response;
            }, 1000)
        }
    });
}

//delete comment
function delete_comment(comment_id, post_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var limit = parseInt($("#post_comment_limit").val());
            var data = {
                "id": comment_id,
                "post_id": post_id,
                "limit": limit
            };
            data['lang_folder'] = lang_folder;
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "home_controller/delete_comment_post",
                data: data,
                success: function (response) {
                    document.getElementById("comment-result").innerHTML = response;
                }
            });
        }
    });
}

//show comment box
function show_comment_box(comment_id) {
    $('.visible-sub-comment').empty();
    var limit = parseInt($("#post_comment_limit").val());
    var data = {
        "comment_id": comment_id,
        "limit": limit
    };
    data['lang_folder'] = lang_folder;
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "home_controller/load_subcomment_box",
        data: data,
        success: function (response) {
            $('#sub_comment_form_' + comment_id).append(response);
        }
    });
}

//like comment
function like_comment(id) {
    var limit = parseInt($("#post_comment_limit").val());
    var data = {
        "id": id,
        "limit": limit
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "home_controller/like_comment_post",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("lbl_comment_like_count_" + id).innerHTML = obj.like_count;
            }
        }
    });
}

//dislike comment
function dislike_comment(id) {
    var limit = parseInt($("#post_comment_limit").val());
    var data = {
        "id": id,
        "limit": limit
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "home_controller/dislike_comment_post",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("lbl_comment_like_count_" + id).innerHTML = obj.like_count;
            }
        }
    });
}

//view poll results
function view_poll_results(a) {
    $("#poll_" + a + " .question").hide();
    $("#poll_" + a + " .result").show()
}

//view poll option
function view_poll_options(a) {
    $("#poll_" + a + " .result").hide();
    $("#poll_" + a + " .question").show()
}

//poll
$(document).ready(function () {
    var a;
    $(".poll-form").submit(function (d) {
        d.preventDefault();
        if (a) {
            a.abort()
        }
        var b = $(this);
        var c = b.find("input, select, button, textarea");
        var f = b.serializeArray();
        f.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
        var e = $(this).attr("data-form-id");
        a = $.ajax({url: base_url + "home_controller/add_vote", type: "post", data: f,});
        a.done(function (g) {
            c.prop("disabled", false);
            if (g == "required") {
                $("#poll-required-message-" + e).show();
                $("#poll-error-message-" + e).hide();
            } else if (g == "voted") {
                $("#poll-required-message-" + e).hide();
                $("#poll-error-message-" + e).show();
            } else {
                document.getElementById("poll-results-" + e).innerHTML = g;
                $("#poll_" + e + " .result").show();
                $("#poll_" + e + " .question").hide()
            }
        })
    })
});

//mobile nav
function open_mobile_nav() {
    document.getElementById("mobile-menu").style.width = "100%";
    $('html').addClass('disable-body-scroll');
    $('body').addClass('disable-body-scroll');
}

//mobile nav
function close_mobile_nav() {
    document.getElementById("mobile-menu").style.width = "0";
    $('html').removeClass('disable-body-scroll');
    $('body').removeClass('disable-body-scroll');
}

//close menu
$(".close-menu-click").click(function () {
    document.getElementById("mobile-menu").style.width = "0"
});

//add remove reading list
function add_delete_from_reading_list(b) {
    $(".tooltip").hide();
    var a = {post_id: b,};
    a[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        method: "POST",
        url: base_url + "home_controller/add_delete_reading_list_post",
        data: a
    }).done(function (c) {
        location.reload()
    })
}

//load more posts
function load_more_posts() {
    $(".btn-load-more").prop("disabled", true);
    var post_load_more_count = parseInt($("#post_load_more_count").val());
    var a = {
        'visible_posts_count': $("#index_visible_posts_count").val(),
        'index_visible_posts_lang_id': $("#index_visible_posts_lang_id").val()
    };
    a[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#load_posts_spinner").show();
    $.ajax({method: "POST", url: base_url + "home_controller/load_more_posts", data: a}).done(function (b) {
        setTimeout(function () {
            $("#load_posts_spinner").hide();
            $("#last_posts_content").append(b);
            $(".btn-load-more").prop("disabled", false)
        }, 500);

        var x = parseInt($("#index_visible_posts_count").val());
        $("#index_visible_posts_count").val((x + post_load_more_count).toString());
    })
}

//load more comments
function load_more_comments(c) {
    var b = parseInt($("#vr_comment_limit").val());
    var a = {post_id: c, limit: b,};
    a[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#load_comments_spinner").show();
    $.ajax({method: "POST", url: base_url + "home_controller/load_more_comments", data: a}).done(function (d) {
        setTimeout(function () {
            $("#load_comments_spinner").hide();
            $("#vr_comment_limit").val(b + 5);
            document.getElementById("comment-result").innerHTML = d
        }, 500)
    })
}

//hide cookies warning
function hide_cookies_warning() {
    $(".cookies-warning").hide();
    var data = {};
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "home_controller/cookies_warning",
        data: data,
        success: function (response) {
        }
    });
}

//print
$("#print_post").on("click", function () {
    $(".post-content .title, .post-content .post-meta, .post-content .post-image, .post-content .post-text").printThis({importCSS: true,})
});
//on ajax stop
$(document).ajaxStop(function () {
    function b(c) {
        $("#poll_" + c + " .question").hide();
        $("#poll_" + c + " .result").show()
    }

    function a(c) {
        $("#poll_" + c + " .result").hide();
        $("#poll_" + c + " .question").show()
    }
});
$(document).ready(function () {
    $('.validate_terms').submit(function (e) {
        if (!$(".checkbox_terms_conditions").is(":checked")) {
            e.preventDefault();
            $('.custom-checkbox .checkbox-icon').addClass('is-invalid');
        } else {
            $('.custom-checkbox .checkbox-icon').removeClass('is-invalid');
        }
    });
});
//responsive table
$(document).ready(function () {
    $('.post-content .post-text table').each(function () {
        table = $(this);
        tableRow = table.find('tr');
        table.find('td').each(function () {
            tdIndex = $(this).index();
            if ($(tableRow).find('th').eq(tdIndex).attr('data-label')) {
                thText = $(tableRow).find('th').eq(tdIndex).data('label');
            } else {
                thText = $(tableRow).find('th').eq(tdIndex).text();
            }
            $(this).attr('data-label', thText);
        });
    });
});
//show gallery buttons on page load
$(document).ready(function () {
    $(".gallery-post-buttons a").css('opacity', '1');
});

//magnificPopup
$(document).ready(function (a) {
    a(".image-popup-single").magnificPopup({
        type: "image", titleSrc: function (b) {
            return b.el.attr("title") + "<small></small>"
        }, image: {verticalFit: true,}, gallery: {enabled: false, navigateByImgClick: true, preload: [0, 1]}, removalDelay: 100, fixedContentPos: true,
    })
});
