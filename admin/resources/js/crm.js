var timeoutFlag;
var timeoutSize = 300;
var time_click;
var intervalOnline = false;
var CrmUtilities = {
    adjustScreen : function (data) {
        windowHeight = $(window).height();
        var wrapperHeight = windowHeight;
        var wrapperContent = $('#wrapper-full');
        wrapperContent.height(wrapperHeight);
        var sectionContent = $('.section-content');
        var offsetTopContent = sectionContent.offset().top;
        sectionContent.height(wrapperHeight - offsetTopContent - 10);
    }
};
var Class = function (methods) {
    var klass = function () {
        this.initialize.apply(this, arguments);
    };

    for (var property in methods) {
        klass.prototype[property] = methods[property];
    }

    if (!klass.prototype.initialize) klass.prototype.initialize = function () {
    };

    return klass;
};
//multi windows : Mindows
var ActiveMindow;
var Mindows = Class({
    wrapper: '#m-window',
    overlay: '#overlay',
    width: '',
    height: '',
    top: '',
    left: '',
    resize: false,
    id: '',
    zIndex: 0,
    container: '',
    initialize: function () {
        var wrapper = $(this.wrapper);
        this.id = this.uniqueID();
        wrapper.append('<div class="mwindow" id="' + this.id + '"></div>');
        this.container = $('#' + this.id);
        //xác định zindex
        this.zIndex = wrapper.find('.mwindow').length + 1;
        ActiveMindow = this;
    },
    calculatePosition: function () {
        if (!this.top) {
            this.top = $(this.overlay).height() / 2 - this.height / 2;
            this.left = $(this.overlay).width() / 2 - this.width / 2;
        }
    },
    uniqueID: function () {
        return 'mwindow_' + Math.random().toString(36).substr(2, 9) + (new Date).getTime();
    },
    open: function (url_load, data) {
        $(this.wrapper).show();
        $(this.overlay).show();
        $this = this;
        this.calculatePosition();
        $this.container.css({
            width: this.width,
            height: this.height,
            zIndex: this.zIndex,
            top: this.top,
            left: this.left
        });
        $.ajax({
            type: 'post',
            url: url_load,
            data: data,
            success: function (html) {
                $this.container.html(html);
                $this.container.find('.mwindow-close').unbind('click').click(function () {
                    //console.log($this);
                    $this.close();
                });
                //cấp phát draggable
                if ($this.resize == true) {
                    $this.container.draggable({
                        handle: '.mwindow-header',
                        containment: '#m-window'
                    });
                }
            }
        })
    },
    openStatic: function (content, callback) {
        $(this.wrapper).show();
        $(this.overlay).show();
        $this = this;
        this.calculatePosition();
        $this.container.css({
            width: this.width,
            height: this.height,
            zIndex: this.zIndex,
            top: this.top,
            left: this.left
        });
        //load content
        $this.container.html(content);
        if (typeof callback == 'function') {
            window[callback()];
        } else {
            if (typeof callback == 'string' && callback != '') {
                window[callback]();
            }
        }
        $this.container.find('.mwindow-close').unbind('click').click(function () {
            $this.close();
        });
        //cấp phát draggable
        if ($this.resize == true) {
            $this.container.draggable({
                handle: '.mwindow-header',
                containment: '#m-window'
            });
        }
    },
    iframe: function (url, title, data) {
        $(this.wrapper).show();
        $(this.overlay).show();
        $this = this;
        this.calculatePosition();
        $this.container.css({
            width: this.width,
            height: this.height,
            zIndex: this.zIndex,
            top: this.top,
            left: this.left
        });

        if (typeof data != 'undefined') {
            url += '?';
            for (var i in data) {
                url += i + '=' + data[i] + '&';
            }
        }

        $.ajax({
            type: 'post',
            url: '/admin/ajax/loadMindow',
            data: {url: url, title: title},
            success: function (html) {
                loadingProgress('hide');
                $this.container.html(html);
                $this.container.find('.mwindow-close').unbind('click').click(function () {
                    //console.log($this);
                    $this.close();
                });
                //cấp phát draggable
                if ($this.resize == true) {
                    $this.container.draggable({
                        handle: '.mwindow-header',
                        containment: '#m-window'
                    });
                }
            },
            beforeSend: function () {
                loadingProgress('show');
            }
        })
    },
    close: function () {
        if ($(this.wrapper).find('.mwindow').length <= 1) {
            $(this.wrapper).hide();
            $(this.overlay).hide();
        }
        $('#' + this.id).remove();
    }
});


//modal
var Modal = Class({
    type: '',
    leftname: '',
    rightname: '',
    href: '',
    width: '',
    height: '',
    container: '#modal',
    overlay: '#overlay',
    closeBtnClass: '.modal-close',
    initialize: function (type, href, name, width, height, rightname) {
        this.type = type;
        this.href = href;
        this.leftname = name;
        if (rightname) {
            this.rightname = rightname;
        }
        this.width = width;
        this.height = height;
    },
    showModal: function () {
        var overlay = this.overlay;
        var container = this.container;
        $(overlay).show();
        if (this.width && this.height) {
            $(container).show().css({
                width: this.width,
                height: this.height,
                top: '50%',
                marginLeft: -(this.width / 2),
                marginTop: -(this.height / 2)
            });
        } else {
            $(container).removeAttr('style').show();
        }
    },
    closeModal: function () {
        var overlay = this.overlay;
        var container = this.container;
        $(container).html('').hide();
        $(overlay).hide();
    },
    load: function () {
        var container = this.container;
        var _this = this;
        $.ajax({
            type: 'post',
            url: '/admin/ajax/loadModal',
            data: {href: this.href, type: this.type, modal_name: this.leftname, modal_rightname: this.rightname},
            success: function (html) {
                loadingProgress('hide');
                _this.showModal();
                $(container).html(html).find('.modal-close,[modal-control="modal-close"]').unbind('click').click(function () {
                    _this.closeModal();
                });
            },
            beforeSend: function () {
                loadingProgress('show');
            }
        })
    },
    miniLoad: function (url_load, data) {
        var _this = this;
        var container = this.container;
        $.ajax({
            type: 'post',
            url: url_load,
            data: data,
            success: function (html) {
                loadingProgress('hide');
                _this.showModal();
                $(container).html(html).find('.modal-close,[modal-control="modal-close"]').click(function () {
                    _this.closeModal();
                });
                //giới hạn kích thước content
                var modal_content = $(container).find('.modal-mini-content');
                //console.log(modal_content);
                if (modal_content.length) {
                    modal_content.height($(container).height() - 60);
                }
            },
            beforeSend: function () {
                loadingProgress('show');
            }
        })
    }
});
function loadingProgress(type) {
    if (type == 'show') {
        //khai báo trong beforeSend
        time_click = Date.now();
        timeoutFlag = setTimeout(function () {
            $('#loading').show();
        }, timeoutSize);
    }
    else {
        var last_click = Date.now();
        if ((last_click - time_click) < timeoutSize) {
            clearTimeout(timeoutFlag);
        } else {
            var hide = setTimeout(function () {
                $('#loading').hide();
            }, 500);
        }
    }
}


function closeModal() {
    $('#modal').html('').hide();
    $('#overlay').hide();
}

function isOnline(callback_online, callback_offline) {
    return false;
    setInterval(function () {
        $.ajax({
            type: 'get',
            cache: 'false',
            url: 'http://static.khang.vn:8080/pictures/dotted.gif?t=' + Date.now(),
            success: function (resp) {
                callback_online();
            },
            error: function () {
                callback_offline();
            }
        })
    }, 3000);
}

$(function () {
    $('a[rel="modal"]').click(function (e) {
        e.preventDefault();
        //ẩn menu
        $('.menu-function').removeClass('active');
        var modal_type = $(this).attr('modal-type');
        var modal_href = $(this).attr('href');
        var modal_leftname = $(this).attr('modal-name');
        var modal_rightname = $(this).attr('modal-right-column-name');
        var modal;
        switch (modal_type) {
            case 'medium':
                modal = new Modal(modal_type, modal_href, modal_leftname, 0, 0, modal_rightname);
                //console.log(modal.width);
                modal.load();
                break;
            case 'small':
                modal = new Modal(modal_type, modal_href, modal_leftname, 450, 400, modal_rightname);
                modal.width = 450;
                modal.height = 400;
                //console.log(modal);
                modal.load();
                break;
            case 'setup':
                modal = new Modal(modal_type, modal_href, modal_leftname, 0, 0, modal_rightname);
                modal.width = 700;
                modal.height = 400;
                //console.log(modal);
                modal.load();
                break;

        }

        return false;
    });
    // hiện thị kiểu mwindow
    $('a[rel="mwindow"]').unbind('click').click(function (e) {
        e.preventDefault();
        var mwindow = new Mindows();
        var url = $(this).attr('href');
        var title = $(this).attr('title');
        var mtype = $(this).attr('mtype');
        var array_type_size = {
            small: {
                width: 650,
                height: 450
            },
            medium: {
                width : 900,
                height : 450
            },
            large : {
                width : 1000,
                height : 600
            }
        };

        mwindow.width = $(this).attr('mwidth') ? $(this).attr('mwidth') : array_type_size[mtype].width;
        mwindow.height = $(this).attr('mheight') ? $(this).attr('mheight') : array_type_size[mtype].height;
        mwindow.resize = true;
        mwindow.iframe(url, title);

        /*
        switch (mtype) {
            case 'small':
                mwindow.resize = true;
                mwindow.iframe(url, title);
                break;
            case  'medium':
                mwindow.resize = true;
                mwindow.iframe(url, title);
                break;
            case  'large':
            default :
                mwindow.resize = true;
                mwindow.iframe(url, title);
                break;
        }
        */
    });

    $('a[rel="iframe"]').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('.menu-function').removeClass('active');
        $('#main-content').find('iframe').attr('src', url);
        //active navigate
        var navigate = $(this).closest('.navigate-top');
        if (navigate.length) {
            $('.navigate-top a').removeClass('active');
            $(this).addClass('active');
        }
        return false;
    })
});

