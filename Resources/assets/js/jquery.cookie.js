$(function() {
    var $cookieContainer = $('#cookiesLegalMessage');

    if ($cookieContainer.length > 0) {
        $cookieContainer.c2isCookie();
    }
});

;(function ($, window, document, undefined) {
    "use strict";

    var pluginName = "c2isCookie",
        defaults = {
            on_closed: false,
            on_accepted: false
        };

    function C2iSCookie(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    $.extend(C2iSCookie.prototype, {
        init: function() {
            this.bindUiActions();
        },
        bindUiActions: function() {
            var $container = $(this.element);

            $container.on('click', '.close', function(event) {
                $.get(Routing.generate('c2is_cookie_close'), function(data) {
                    $container.hide();
                    $container.trigger('cookie_closed', [ data ]);
                });
            });

            $container.on('click', '.cookie-accept', function(event) {
                $.get(Routing.generate('c2is_cookie_accept'), function(data) {
                    $container.hide();
                    $container.trigger('cookie_accepted', [ data ]);
                });
            });

            $container.on('cookie_closed', this.settings['on_closed']);
            $container.on('cookie_accepted', this.settings['on_accepted']);
        },
        close: function() {

        }
    });

    $.fn[pluginName] = function (options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new C2iSCookie(this, options));
            }
        });
    };
})(jQuery, window, document);
