chrome.app.runtime.onLaunched.addListener(function(launchData) {
    var wH = window.innerHeight;
    var wW = window.innerWidth;
    switch (launchData) {
        case 'home':
        default :
            chrome.app.window.create('window.html', {
                'id' : 'crm-window',
                'bounds': {
                    'width': wW,
                    'height': wH
                },
                innerBounds : {
                    'width' : wW,
                    'height' : wH
                }
            });
    }
});