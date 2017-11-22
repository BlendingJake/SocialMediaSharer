function openFacebookDialog() {
    FB.ui({
            method: 'share',
            href: window.location.href
        }, function(response){}
    );
}