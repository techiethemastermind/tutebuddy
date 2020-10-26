/**
 * File: Global Helper Functions
 * Created At: 7/12/2020
 * Auth: TechieTheMastermind
 */

/**
 * Display Blob picture in front end side
 * @param {*} input - file html element
 * @param {*} target - html element to display picture
 */
function display_image(input, target) {
    var file = input.files[0];
    var reader  = new FileReader();
    
    reader.onload = function(e)  {
        target.attr('src', e.target.result);
    }
    // declear file loading
    reader.readAsDataURL(file);
}

/**
 * Display Video in front end side
 * @param {*} url - video or embeded url
 * @param {*} target - html element to display url
 */
function display_iframe(url, target) {

    // Check video type
    var source = '';

    if(url.includes('youtube')) {
        source = 'youtube';
    }

    if(url.includes('vimeo')) {
        source = 'vimeo';
    }

    switch (source) {
        case 'youtube':
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            if (match && match[2].length == 11) {
                // if need to change the url to embed url then use below line
                target.attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0');
            }
            else {
                target.attr('src', 'Invalid Url');
            }
            break;
        
        case 'vimeo':
            $.ajax({
                url: 'https://vimeo.com/api/oembed.json?url='+url,
                async: false,
                success: function(response) {
                    if(response.video_id) {
                        id = response.video_id;
                    }
                }
            });

            target.attr('src', 'https://player.vimeo.com/video/' + id);
            break;
        
        default:

    }
}

/**
 * Return Error mssage
 * @param {*} err - Ajax callback object
 */
function getErrorMessage(err) {
    var errors = JSON.parse(err.responseText).errors;
    var msg = '';
    $.each(errors, function(key, item){
        msg += item[0] + '\n';
    });

    return msg;
}

/**
 * Get Alert HTML
 * @param {*} title - Alert title
 * @param {*} msg - Alert content
 * @param {*} style - style - primary, warning, error
 */
function getAlert(title, msg, style) {

    return `<div class="alert alert-soft-` + style + ` alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="d-flex flex-wrap align-items-start">
            
            <div class="flex" style="min-width: 180px">
                <small class="text-black-100">
                    <strong> `+ title + ` - </strong> ` + msg + `!
                </small>
            </div>
        </div>
    </div>`;
}

/**
 * 
 * @param {*} length : int - Length
 */
function makeId(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
       result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function convertToSlug(Text)
{
    return Text
        .trim()
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}

function checkValidForm(Form)
{
    var no_empty_eles = Form.find('input[tute-no-empty], textarea[tute-no-empty], select[tute-no-empty]');
    var invalid_found = false;
    $.each(no_empty_eles, function(idx, ele) {
        if ($(ele).val() == '') {
            $(ele).addClass('is-invalid');
            var err_msg = $('<div class="invalid-feedback">Title is required field.</div>');
            err_msg.insertAfter($(ele));
            $(ele).focus();
            invalid_found = true;
        }
    });

    if(invalid_found) return false;
    return (invalid_found) ? false : true;
}

// ===  Global Element Events === //
$(document).on('change', 'input[data-preview]', function() {
    display_image(this, $($(this).attr('data-preview')));
});

$(document).on('change', 'input[data-video-preview]', function() {
    display_iframe($(this).val(), $($(this).attr('data-video-preview')));
});

$(document).on('change', 'input[tute-file]', function(e) {
    var file_name = $(this).val().replace(/C:\\fakepath\\/i, '');
    var id = $(this).attr('id');
    $('div.custom-file').find('label[for="'+ id +'"]').text(file_name);
});

$(document).on('keyup', 'input[tute-no-empty], textarea[tute-no-empty], select[tute-no-empty]', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').find('div.invalid-feedback').remove();
});

$(document).on('submit', 'form', function(e) {

    var Form = $(this);
    var no_empty_eles = Form.find('input[tute-no-empty], textarea[tute-no-empty], select[tute-no-empty]');
    var invalid_found = false;
    $.each(no_empty_eles, function(idx, ele) {
        if ($(ele).val() == '') {
            $(ele).addClass('is-invalid');
            var err_msg = $('<div class="invalid-feedback">Title is required field.</div>');
            err_msg.insertAfter($(ele));
            $(ele).focus();
            invalid_found = true;
        }
    });

    if(!invalid_found) {
        return true;
    } else {
        e.preventDefault();
        return false;
    }
});