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

// ===  Global Element Events === //

$('input[type="file"]').on('change', function() {
    var file = this.files[0];
    var reader  = new FileReader();
    var target = $($(this).attr('data-preview'));

    reader.onload = function(e)  {
        target.attr('src', e.target.result);
    }
    // declear file loading
    reader.readAsDataURL(file);
});