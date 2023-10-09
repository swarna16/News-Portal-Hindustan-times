/**
 ******************************************************************************************************
 * Image File Manager
 *******************************************************************************************************
 */
$(document).on('click', '#image_file_manager .file-box', function () {
    $('.file-manager .file-box').removeClass('selected');
    $(this).addClass('selected');
    var val = $(this).attr('data-file-id');
    $('#selected_img_file_id').val(val);
    $('#btn_img_delete').show();
    $('#btn_img_select').show();
});

$(document).on('change', '.img_file_manager_input', function () {
    setTimeout(
        function () {
            $('#btn_file_manager_image_upload').show();
        }, 200);
});

$(document).on('click', '#btn_file_manager_image_upload', function () {
    $("#form_image_file_manager").ajaxSubmit({
        beforeSubmit: function (inputs, $form, options) {
            inputs.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
            $("#btn_file_manager_image_upload").hide();
            $('.loader-file-manager').show();
            $("#btn_img_upload").attr("disabled", true);
            $(".img_file_manager_input").attr("disabled", true);
        },
        complete: function () {
            $('.loader-file-manager').hide();
            $('#MultidvPreview').empty();
            $("#btn_img_upload").attr("disabled", false);
            $(".img_file_manager_input").attr("disabled", false);
            $(".img_file_manager_input").val('');
            $('#btn_img_delete').hide();
            $('#btn_img_select').hide();
            refresh_images();
        },
        error: function (response) {
            if (response.responseText.indexOf("Maximum execution time") >= 0) {
                $(".file-manager-upload-error").html("<br><b>Fatal error:</b> Maximum execution time of 10 seconds exceeded. Please increase max_execution_time value from PHP settings.");
            } else {
                $(".file-manager-upload-error").html(response.responseText);
            }
            $('#btn_img_delete').hide();
            $('#btn_img_select').hide();
        }
    });
});

//refresh images
function refresh_images() {
    var data = {};
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/get_images",
        data: data,
        success: function (response) {
            document.getElementById("image_file_upload_response").innerHTML = response;
        }
    });
}

//delete image file
$(document).on('click', '#image_file_manager #btn_img_delete', function () {
    var file_id = $('#selected_img_file_id').val();
    $('#img_col_id_' + file_id).remove();
    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "file_controller/delete_image_file",
        data: data,
        success: function (response) {
            $('#btn_img_delete').hide();
            $('#btn_img_select').hide();
        }
    });

});

//select image file
$(document).on('click', '#image_file_manager #btn_img_select', function () {
    select_image();
});

//select image file on double click
$(document).on('dblclick', '#image_file_manager .file-box', function () {
    select_image();
});

function select_image() {
    $('#image_file_manager').modal('toggle');
    var file_id = $('#selected_img_file_id').val();
    var type = $('#selected_image_type').val();

    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/select_image_file",
        data: data,
        success: function (response) {
            if (type == "image") {
                $('input[name=post_image_id]').val(file_id);
                $('#selected_image_file').attr('src', response);

                if ($("#video_thumbnail_url").length) {
                    $('#video_thumbnail_url').val('');
                }
            } else if (type == "additional_image") {
                var image = '<div class="additional-item additional-item-' + file_id + '"><img class="img-additional" src="' + response + '" alt="">' +
                    '<input type="hidden" name="additional_post_image_id[]" value="' + file_id + '">' +
                    '<a class="btn btn-danger btn-sm btn-delete-additional-image" data-value="' + file_id + '">' +
                    '<i class="fa fa-times"></i> ' +
                    '</a>' +
                    '</div>';
                $('.additional-image-list').append(image);
            }
            $('#image_file_manager .file-box').removeClass('selected');
            $('#btn_img_delete').hide();
            $('#btn_img_select').hide();
        }
    });
}

//delete additional image
$(document).on('click', '.btn-delete-additional-image', function () {
    var item_id = $(this).attr("data-value");
    $('.additional-item-' + item_id).remove();
});

//delete additional image from database
$(document).on('click', '.btn-delete-additional-image-database', function () {
    var item_id = $(this).attr("data-value");
    $('.additional-item-' + item_id).remove();
    var data = {
        "file_id": item_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/delete_post_additional_image",
        data: data,
        success: function (response) {
        }
    });
});

//load more images
jQuery(function ($) {
    $('#image_file_manager .file-manager-content').on('scroll', function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            var data = {};
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "file_controller/load_more_images",
                data: data,
                success: function (response) {
                    $("#image_file_upload_response").append(response);
                }
            });
        }
    })
});

//update images
$('#image_file_manager').on('show.bs.modal', function (e) {
    var data = {};
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/get_images",
        data: data,
        success: function (response) {
            document.getElementById("image_file_upload_response").innerHTML = response;
        }
    });
});


/**
 ******************************************************************************************************
 * CKEditor File Manager
 *******************************************************************************************************
 */
$(document).on('click', '#ck_file_manager .file-box', function () {
    $('.file-manager .file-box').removeClass('selected');
    $(this).addClass('selected');
    var val_id = $(this).attr('data-file-id');
    var val_path = $(this).attr('data-file-path');
    $('#selected_ckimg_file_id').val(val_id);
    $('#selected_ckimg_file_path').val(val_path);

    $('#btn_ckimg_delete').show();
    $('#btn_ckimg_select').show();
});

$(document).on('change', '.img_ck_file_manager_input', function () {
    setTimeout(
        function () {
            $('#btn_ck_file_manager_image_uplaod').show();
        }, 200);
});

$(document).on('click', '#btn_ck_file_manager_image_uplaod', function () {
    $("#form_image_ck_file_manager").ajaxSubmit({
        beforeSubmit: function (inputs, $form, options) {
            inputs.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
            $("#btn_ck_file_manager_image_uplaod").hide();
            $('.loader-file-manager').show();
            $("#btn_ckimg_upload").attr("disabled", true);
            $(".img_ck_file_manager_input").attr("disabled", true);
        },
        complete: function () {
            $('.loader-file-manager').hide();
            $('#ckMultidvPreview').empty();
            $("#btn_ckimg_upload").attr("disabled", false);
            $(".img_ck_file_manager_input").attr("disabled", false);
            $(".img_ck_file_manager_input").val('');
            $('#btn_img_delete').hide();
            $('#btn_img_select').hide();
            refresh_ck_images();
        },
        error: function (response) {
            if (response.responseText.indexOf("Maximum execution time") >= 0) {
                $(".file-manager-upload-error").html("<br><b>Fatal error:</b> Maximum execution time of 10 seconds exceeded. Please increase max_execution_time value from PHP settings.");
            } else {
                $(".file-manager-upload-error").html(response.responseText);
            }
            $('#btn_ckimg_delete').hide();
            $('#btn_ckimg_select').hide();
        }
    });
});

//refresh ck images
function refresh_ck_images() {
    var data = {};
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/get_ck_images",
        data: data,
        success: function (response) {
            document.getElementById("ckimage_file_upload_response").innerHTML = response;
        }
    });
}

//select image file
$(document).on('click', '#ck_file_manager #btn_ckimg_select', function () {
    var imgUrl = $('#selected_ckimg_file_path').val();
    window.parent.CKEDITOR.tools.callFunction('1', base_url + imgUrl);
    $('#ck_file_manager').modal('toggle');
});

//select image file on double click
$(document).on('dblclick', '#ck_file_manager .file-box', function () {
    var imgUrl = $('#selected_ckimg_file_path').val();

    window.parent.CKEDITOR.tools.callFunction('1', base_url + imgUrl);
    $('#ck_file_manager').modal('toggle');
});

//delete image file
$(document).on('click', '#ck_file_manager #btn_ckimg_delete', function () {

    var file_id = $('#selected_ckimg_file_id').val();

    $('#ckimg_col_id_' + file_id).remove();

    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "file_controller/delete_ckimage_file",
        data: data,
        success: function (response) {
            $('#btn_ckimg_delete').hide();
            $('#btn_ckimg_select').hide();
        }
    });

});

//load more ckimages
jQuery(function ($) {
    $('#ck_file_manager .file-manager-content').on('scroll', function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

            var data = {};
            data[csfr_token_name] = $.cookie(csfr_cookie_name);

            $.ajax({
                type: "POST",
                url: base_url + "file_controller/load_more_ckimages",
                data: data,
                success: function (response) {
                    $("#ckimage_file_upload_response").append(response);
                }
            });
        }
    })
});

//update images
$('#ck_file_manager').on('show.bs.modal', function (e) {
    var data = {};
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/get_ck_images",
        data: data,
        success: function (response) {
            document.getElementById("ckimage_file_upload_response").innerHTML = response;
        }
    });
});


/**
 ******************************************************************************************************
 * Audio File Manager
 *******************************************************************************************************
 */

//audio file manager
$(document).on('click', '#audio_file_manager .file-box', function () {
    $('#audio_file_manager .file-box').removeClass('selected');
    $(this).addClass('selected');
    var val = $(this).attr('data-file-id');
    $('#selected_audio_file_id').val(val);

    $('#btn_audio_delete').show();
    $('#btn_audio_select').show();
});

$(document).on('click', '#btn_audio_upload', function () {

    if ($('#add_audio_form #audio_name').val() == '') {
        $('#add_audio_form #audio_name').css("border-color", "#A3122F");
        return;
    } else {
        $('#add_audio_form #audio_name').css("border-color", "#d2d6de");
    }
    if ($('#add_audio_form #audio_file_input').prop('files')[0]) {

        $(".loader-file-manager").show();
        $("#btn_audio_upload").prop("disabled", true);

        var form_data = new FormData();
        form_data.append('audio_file', $('#add_audio_form #audio_file_input').prop('files')[0]);
        form_data.append('audio_name', $('#add_audio_form #audio_name').val());
        form_data.append('musician', $('#add_audio_form #musician').val());
        form_data.append('download_button', $('input[name=audio_download_button]:checked').val());
        form_data.append(csfr_token_name, $.cookie(csfr_cookie_name));

        $.ajax({
            method: 'POST',
            url: base_url + "file_controller/upload_audio_file",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                document.getElementById("audio_file_upload_response").innerHTML = response;
                $(".loader-file-manager").hide();
                $("#btn_audio_upload").prop("disabled", false);
                $('#add_audio_form #audio_name').val('');
                $('#add_audio_form #audio_file_input').val('');
                $('#add_audio_form #musician').val('');
                $('#input_audio_file_label').html('');
            },
            error: function (response) {
            }
        });
    }

});

//select audio file
$(document).on('click', '#audio_file_manager #btn_audio_select', function () {
    select_audio();
});

//select image file on double click
$(document).on('dblclick', '#audio_file_manager .file-box', function () {
    select_audio();
});

//select audio file
function select_audio() {
    $('#audio_file_manager').modal('toggle');
    var file_id = $('#selected_audio_file_id').val();
    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/select_audio_file",
        data: data,
        success: function (response) {
            $('.audio-list').append(response);
            $('#audio_file_manager .file-box').removeClass('selected');
            $('#btn_audio_delete').hide();
            $('#btn_audio_select').hide();
            $('.play-list-empty').hide();
        }
    });

};

//delete audio file
$(document).on('click', '#audio_file_manager #btn_audio_delete', function () {
    var file_id = $('#selected_audio_file_id').val();
    $('#audio_col_id_' + file_id).remove();
    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/delete_audio_file",
        data: data,
        success: function (response) {
            $('#btn_audio_delete').hide();
            $('#btn_audio_select').hide();
        }
    });

});

//delete audio
$(document).on('click', '.btn-delete-audio', function () {
    var item_id = $(this).attr("data-value");
    $('.play-list-item-' + item_id).remove();

});

//load more audios
jQuery(function ($) {
    $('#audio_file_manager .file-manager-content').on('scroll', function () {
        var search = $.trim($("#input_search_audio").val());
        if (search.length < 1) {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                var data = {};
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: "POST",
                    url: base_url + "file_controller/load_more_audios",
                    data: data,
                    success: function (response) {
                        $("#audio_file_upload_response").append(response);
                    }
                });
            }
        }

    })
});

//search
$(document).on('input', '#input_search_audio', function () {
    var search = $(this).val();
    var data = {
        "search": search
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/search_audio_file",
        data: data,
        success: function (response) {
            document.getElementById("audio_file_upload_response").innerHTML = response;
            $('#audio_file_manager .file-box').removeClass('selected');
            $('#btn_audio_delete').hide();
            $('#btn_audio_select').hide();
        }
    });
});

/**
 ******************************************************************************************************
 * Video File Manager
 *******************************************************************************************************
 */

//video file manager
$(document).on('click', '#video_file_manager .file-box', function () {
    $('#video_file_manager .file-box').removeClass('selected');
    $(this).addClass('selected');
    var val = $(this).attr('data-file-id');
    $('#selected_video_file_id').val(val);

    $('#btn_video_delete').show();
    $('#btn_video_select').show();
});

//upload video
$(document).on('click', '#btn_video_upload', function () {

    if ($('#add_video_form #video_name').val() == '') {
        $('#add_video_form #video_name').css("border-color", "#A3122F");
        return;
    } else {
        $('#add_video_form #video_name').css("border-color", "#d2d6de");
    }
    if ($('#add_video_form #video_file_input').prop('files')[0]) {

        $(".loader-file-manager").show();
        $("#btn_video_upload").prop("disabled", true);

        var form_data = new FormData();
        form_data.append('video_file', $('#add_video_form #video_file_input').prop('files')[0]);
        form_data.append('video_name', $('#add_video_form #video_name').val());
        form_data.append(csfr_token_name, $.cookie(csfr_cookie_name));

        $.ajax({
            method: 'POST',
            url: base_url + "file_controller/upload_video_file",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                document.getElementById("video_file_upload_response").innerHTML = response;
                $(".loader-file-manager").hide();
                $("#btn_video_upload").prop("disabled", false);
                $('#add_video_form #video_name').val('');
                $('#add_video_form #video_file_input').val('');
                $('#input_video_file_label').html('');
            },
            error: function (response) {
            }
        });
    }

});


//delete video file
$(document).on('click', '#video_file_manager #btn_video_delete', function () {

    var file_id = $('#selected_video_file_id').val();

    $('#video_col_id_' + file_id).remove();

    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "file_controller/delete_video_file",
        data: data,
        success: function (response) {
            $('#btn_video_delete').hide();
            $('#btn_video_select').hide();
        }
    });

});

//select video file
$(document).on('click', '#video_file_manager #btn_video_select', function () {
    select_video();
});

//select video file on double click
$(document).on('dblclick', '#video_file_manager .file-box', function () {
    select_video();
});

//select video file
function select_video() {
    $('#video_file_manager').modal('toggle');

    var file_id = $('#selected_video_file_id').val();

    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "file_controller/select_video_file",
        data: data,
        success: function (response) {
            document.getElementById("post_selected_video").innerHTML = response;
            $('#video_file_manager .file-box').removeClass('selected');
            $('#btn_video_delete').hide();
            $('#btn_video_select').hide();
        }
    });

};

//load more videos
jQuery(function ($) {
    $('#video_file_manager .file-manager-content').on('scroll', function () {
        var search = $.trim($("#input_search_video").val());
        if (search.length < 1) {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                var data = {};
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: "POST",
                    url: base_url + "file_controller/load_more_videos",
                    data: data,
                    success: function (response) {
                        $("#video_file_upload_response").append(response);
                    }
                });
            }
        }

    })
});

//search
$(document).on('input', '#input_search_video', function () {
    var search = $(this).val();
    var data = {
        "search": search
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/search_video_file",
        data: data,
        success: function (response) {
            document.getElementById("video_file_upload_response").innerHTML = response;
            $('#video_file_manager .file-box').removeClass('selected');
            $('#btn_video_delete').hide();
            $('#btn_video_select').hide();
        }
    });
});


