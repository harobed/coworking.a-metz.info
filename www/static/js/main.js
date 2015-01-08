$(document).ready(function() {
    $('body').anchor({
        'selector': '.anchor',
        'className': 'anchor-handler',
        'content': '&nbsp;<i class="fa fa-link"></i>',
        'position': 'append'
    });

    $.get('http://membres.coworking.a-metz.info/website_banner/public', function(data) {
        if (data != '') {
            $('#website_banner').show();
            $('#website_banner_inner').html(data);
        }
    })
});
