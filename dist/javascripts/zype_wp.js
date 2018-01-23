function ZypeWP(env) {

    var self = this;

    this.env = env;

    this.init = function() {
        this.get_all_ajax();
        this.do_videos();
        this.do_flash_messages();
        this.add_subscriptions_popup_handler();

        return this;
    };

    this.initSubscriptionWidget = function() {
        if (self.env.logged_in || self.env.estWidgetEnabled == false) {
            return;
        }
        var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
        var eventer = window[eventMethod];
        var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

        eventer(messageEvent, function(e) {
            console.log('parent received message!:  ', e.data);
            jQuery.ajax({
                url: self.env.ajax_endpoint,
                type: 'post',
                data: {
                    action: 'zype_authorize_from_widget',
                    authData: e.data
                },
                success: function(data) {
                    try {
                        var res = JSON.parse(data);

                        if (res.logged_in === true) {
                            window.location.reload();
                        }

                    } catch (e) {}
                },
                error: function(data) {}
            });
        }, false);
    }

    this.do_flash_messages = function() {
        try {
            flash = JSON.parse(jQuery.cookie("zype_flash_messages"));
            jQuery('.zype_flash_messages').html(flash.msg);
            jQuery('.zype_flash_messages').show();
            jQuery.cookie("zype_flash_messages", null, { path: '/' });
        } catch (e) {}

    }

    this.zypeAuthMarkupRequest = function(zype_auth_type) {
        jQuery.ajax({
            url: this.env.ajax_endpoint,
            type: 'get',
            data: {
                action: 'zype_auth_markup',
                type: zype_auth_type
            },
            success: function(response) {
                try {
                    jQuery('.content-wrap').replaceWith(response);
                    self.initZypeAjaxForms();
                    self.initZypeAjaxMarkup();
                } catch (e) {
                    console.log(e);
                }
            },
            error: function(data) {}
        });
    }

    this.get_all_ajax = function() {
        jQuery.ajax({
            url: this.env.ajax_endpoint,
            type: 'post',
            data: { action: 'zype_get_all_ajax' },
            context: this,
            success: function(data) {
                try {
                    var res = JSON.parse(data);

                    if (res.on_air == 'yes') {
                        this.fade_in('.zype-on-air');
                    }

                    if (res.logged_in == true) {
                        this.logged_in_actions();
                    } else {
                        this.logged_out_actions();
                    }

                    if (res.subscriber == true) {
                        this.subscriber_actions();
                    } else {
                        this.non_subscriber_actions();
                    }


                } catch (e) {
                    this.logged_out_actions();
                    this.non_subscriber_actions();
                }
            },
            error: function(data) {
                this.logged_out_actions();
                this.non_subscriber_actions();
            },
            complete: function(data) {
                this.fade_in('.zype_loginout_button');
                this.fade_in('.zype_profile_button');
                this.fade_in('.zype_loginout_icon');
                this.fade_in('.zype_subscriber_button');
            }
        });

        this.initZypeAjaxForms();
        this.initZypeAjaxMarkup();
    }

    this.initZypeAjaxForms = function() {
        var zype_ajax_form = jQuery(".zype_ajax_form");

        if (zype_ajax_form.length) {
            zype_ajax_form.off();
            zype_ajax_form.ajaxForm({
                beforeSubmit: function() {},
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        if (response.redirect) {
                            window.location.replace(response.redirect);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        if (response.errors) {
                            zype_ajax_form.find('.error-section').html(response.errors.join(","));
                            this.initZypeAjaxMarkup();
                        } else {
                            zype_ajax_form.find('.error-section').html('Something went wrong...');
                        }
                    }
                }
            });
        }
    }

    this.initZypeAjaxMarkup = function() {
        var zype_auth_markup = jQuery('.zype_auth_markup');
        if (zype_auth_markup.length) {
            zype_auth_markup.off();

            zype_auth_markup.on('click', function(e) {
                e.preventDefault();
                self.zypeAuthMarkupRequest(jQuery(this).data('type'));
            });
        }
    }

    this.is_on_air = function() {
        jQuery.ajax({
            url: this.env.ajax_endpoint,
            type: 'post',
            data: { action: 'zype_is_on_air' },
            context: this,
            success: function(data) {
                try {
                    var res = JSON.parse(data);
                    if (res.on_air == 'yes') {
                        this.fade_in('.zype-on-air');
                    }
                } catch (e) {}
            },
            error: function(data) {}
        });
    }

    this.is_logged_in = function() {
        jQuery.ajax({
            url: this.env.ajax_endpoint,
            type: 'post',
            data: { action: 'zype_logged_in' },
            context: this,
            success: function(data) {
                try {
                    var res = JSON.parse(data);
                    if (res.logged_in == true) {
                        this.logged_in_actions();
                    } else {
                        this.logged_out_actions();
                    }
                } catch (e) {
                    this.logged_out_actions();
                }
            },
            error: function(data) {
                this.logged_out_actions();
            },
            complete: function(data) {
                this.fade_in('.zype_loginout_button');
                this.fade_in('.zype_loginout_icon');
            }
        });
    }

    this.is_subscriber = function() {
        jQuery.ajax({
            url: this.env.ajax_endpoint,
            type: 'post',
            data: { action: 'zype_subscriber' },
            context: this,
            success: function(data) {
                try {
                    var res = JSON.parse(data);
                    if (res.subscriber == true) {
                        this.subscriber_actions();
                    } else {
                        this.non_subscriber_actions();
                        this.show_subscription_plans();
                    }
                } catch (e) {
                    this.non_subscriber_actions();
                }
            },
            error: function(data) {
                this.non_subscriber_actions();
            },
            complete: function(data) {
                this.fade_in('.zype_subscriber_button');
            }
        });
    }

    this.logged_in_actions = function() {
        this.replace_login_button();
        this.fade_in('.zype_contact_button');
    }

    this.replace_login_button = function() {
        jQuery('.zype_loginout_button a').attr('href', this.env.logout_url);
        jQuery('.zype_loginout_button a').html('Sign Out');
        jQuery('.zype_profile_button a').attr('href', this.env.profile_url);
        jQuery('.zype_profile_button a').html('My Account');
    }

    this.replace_login_icon = function() {
        jQuery('.zype_loginout_icon i').removeClass('fa-sign-in');
        jQuery('.zype_loginout_icon i').addClass('fa-sign-out');
        jQuery('.zype_loginout_icon').attr('href', this.env.logout_url);
    }

    this.fade_in = function(thing_to_fade) {
        jQuery(thing_to_fade).css('visibility', 'visible');
        jQuery(thing_to_fade).fadeTo(250, 1);
    }

    this.logged_out_actions = function() {}

    this.subscriber_actions = function() {
        this.replace_subscribe_button();
    }

    this.replace_subscribe_button = function() {
        jQuery('.zype_subscriber_button').attr('href', this.env.profile_url);
        jQuery('.zype_subscriber_button').html('My Account');
    }

    this.non_subscriber_actions = function() {}



    this.do_videos = function() {

        function get_name_browser(){
	        var ua = navigator.userAgent;    

	        if (ua.search(/Chrome/) > 0) return 'Google Chrome';
	        if (ua.search(/Firefox/) > 0) return 'Firefox';
	        if (ua.search(/Opera/) > 0) return 'Opera';
	        if (ua.search(/Safari/) > 0) return 'Safari';
	        if (ua.search(/MSIE/) > 0) return 'Internet Explorer';

	        return false;
        };

        var browser = get_name_browser();
        var self = this;
        jQuery('.zype_player_container').each(function() {
            var t = jQuery(this);
            if (t.data('auto-play') == true) {
                self.get_player(t);
            } else {
                if(browser=="Safari"){
                    self.get_player(t);
  					setTimeout(function () {
  						jQuery(".vjs-big-play-button").click();
  					}, 3000);
                }else{
                    t.children('.play-placeholder').click(function() {
                        self.get_player(t);
                    })
            }
            }
        });
    }
    this.get_player = function(container) {
        var container = container;
        jQuery.ajax({
            url: this.env.ajax_endpoint,
            type: 'post',
            data: {
                action: 'zype_player',
                video_id: container.data('video-id'),
                auth_required: container.data('auth-required'),
                audio_only: container.data('audio-only')
            },
            context: this,
            success: function(data) {
                this.do_embed_success(data, container);
            },
            error: function(data) {
                this.do_embed_error(data, container);
            }
        });
    }

    this.do_embed_success = function(data, container) {
        if (typeof data.embed_url == 'undefined') {
            return;
        }

        embed_url = data.embed_url;

        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = embed_url;
        container.children('.btn-play').remove();
        container.children('.play-placeholder').remove();
        container.children('.zype_player').css('position', 'absolute');
        container.children('.zype_player').css('top', '0px');
        container.children('.zype_player').css('width', '100%');
        container.children('.zype_player').css('height', '100%');
        jQuery('.link--watch-now').remove();
        document.body.appendChild(script);

    }

    this.do_embed_error = function(data, container) {
        container.children('.btn-play').remove();
        this.fade_in(container.children('.player-auth-required'));
    }

    this.show_subscription_plans = function() {
        jQuery('.dialog-modal-init').magnificPopup('open');
    }

    // fires popup subscription modal window
    this.add_subscriptions_popup_handler = function() {
        var modal_btn = jQuery('.dialog-modal-button');
        var modal_init = jQuery('.dialog-modal-init');
        var close = jQuery('.popup-close');

        if (modal_btn.length > 0) {
            modal_init.magnificPopup({
                type: 'inline',
                mainClass: 'mfp-fade',
                closeOnBgClick: true,
                showCloseBtn: false,
                removalDelay: 250
            });

            modal_btn.on('click', function(event) {
                event.preventDefault();
                self.is_subscriber();
                return false;
            });

            close.on('click', function(event) {
                event.preventDefault();
                modal_init.magnificPopup('close');
            });
        }

    }
}

var zype_wp;
jQuery(document).ready(function() {
    zype_wp = new ZypeWP(zype_js_wp_env).init();
});


/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function(factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // CommonJS
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            // If we can't parse the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch (e) {}
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function(key, value, options) {

        // Write

        if (value !== undefined && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if (typeof options.expires === 'number') {
                var days = options.expires,
                    t = options.expires = new Date();
                t.setTime(+t + days * 864e+5);
            }

            return (document.cookie = [
                encode(key), '=', stringifyCookieValue(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // Read

        var result = key ? undefined : {};

        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var i = 0, l = cookies.length; i < l; i++) {
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');

            if (key && key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if (!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function(key, options) {
        if ($.cookie(key) === undefined) {
            return false;
        }

        // Must not alter options, thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, { expires: -1 }));
        return !$.cookie(key);
    };
}));