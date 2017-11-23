function openFacebookDialog() {
    FB.ui({
            method: 'share',
            href: window.location.href
        }, function(response){}
    );
}

function openWindowDialog(self, event, title, width=500, height=300) {
    event.preventDefault();

    var left = (window.innerWidth - width) / 2 + window.screenLeft;
    var top = (window.innerHeight - height) / 2 + window.screenTop;

    window.open(
        jQuery(self).attr("href"),
        title,
        'toolbar=no,' +
        'status=no,' +
        'menubar=no,' +
        'scrollbars=yes,' +
        'left=' + left + ',' +
        'top=' + top + ',' +
        'resizeable=yes,' +
        'width=' + width + ',' +
        'height=' + height
    );
    return false;
}