/**
 * Created by Yarmaliuk Mikhail on 21.05.2018.
 *
 * @module MPToggleColumn
 */

/**
 * MPToggleColumn
 *
 * @author Mikhail Yarmaliuk
 *
 * @param {MPToggleColumn} app
 * @param {jQuery} $
 */
var MPToggleColumn = (function (app, $) {

    /**
     * Options module
     *
     * @type {{}}
     */
    var options = {
        loadingClass: 'tg-loading',
    };

    /**
     * Toggle button state
     *
     * @return {undefined}
     */
    var toggleButton = function () {
        var button = $(this);
        var buttonOpt = button.data('arToggle');

        if (button.hasClass(options.loadingClass)) {
            return false;
        }

        button.addClass(options.loadingClass);

        $.post(buttonOpt['url'], {
            id: button.data('id'),
            value: button.data('value'),
            mpDataARToggle: buttonOpt['mpDataARToggle'],
        })
            .done(function (response) {
                if (typeof response === 'object' && response.result === true) {
                    button.data('value', response.value);
                    button.html(buttonOpt['values'][response.value]);
                }

                button.removeClass(options.loadingClass);
            });
    };

    /**
     * Add toggle column button(s)
     *
     * @param {string} selector
     * @param {Object} moduleOpt
     *
     * @return {bool}
     */
    app.add = function (selector, moduleOpt) {
        var buttons = $(selector);

        if (!buttons.length || buttons.data('arToggle') !== undefined) {
            return false;
        }

        var moduleOptions = $.extend({}, options, moduleOpt || {});

        return buttons
            .data('arToggle', moduleOptions)
            .on('click.MPToggle', toggleButton)
            .length ? true : false;
    };

    /**
     * Remove toggle column button(s)
     *
     * @param {string} selector
     *
     * @return {bool}
     */
    app.remove = function (selector) {
        var buttons = $(selector);

        if (!buttons.length) {
            return false;
        }

        buttons.off('.MPToggle');
        buttons.removeData('arToggle');

        return true;
    };

    /**
     * Init MPToggleColumn
     *
     * @param {Object} opt
     *
     * @return {undefined}
     */
    app.init = function (opt) {
        options = $.extend({}, options, opt);
    };

    return app;
}(MPToggleColumn || {}, jQuery));