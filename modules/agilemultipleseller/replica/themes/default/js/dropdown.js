/* ========================================================================
 * Bootstrap: agile-dropdown.js v3.1.1
 * http://getbootstrap.com/javascript/#agile-dropdowns
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
    'use strict';

    /** _agile_  agile-dropdown CLASS DEFINITION _agile_  **/
    /** _agile_  ========================= _agile_  **/

    var backdrop = '.agile-dropdown-backdrop'
    var toggle = '[data-toggle=agile-dropdown]'
    var dropdown = function (element) {
        $(element).on('click.bs.agile-dropdown', this.toggle)
    }

    dropdown.prototype.toggle = function (e) {
        var $this = $(this)
        if ($this.is('.disabled, :disabled')) return
        var $parent = getParent($this)
        var isActive = $parent.hasClass('open')

        clearMenus()

        if (!isActive) {
            if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
                /** _agile_  if mobile we use a backdrop because click events don't delegate _agile_  **/
                $('<div class="agile-dropdown-backdrop"/>').insertAfter($(this)).on('click', clearMenus)
            }

            var relatedTarget = { relatedTarget: this }
            $parent.trigger(e = $.Event('show.bs.agile-dropdown', relatedTarget))

            if (e.isDefaultPrevented()) return

            $parent
              .toggleClass('open')
              .trigger('shown.bs.agile-dropdown', relatedTarget)

            $this.focus()
        }

        return false
    }

    dropdown.prototype.keydown = function (e) {
        if (!/(38|40|27)/.test(e.keyCode)) return

        var $this = $(this)
        e.preventDefault()
        e.stopPropagation()

        if ($this.is('.disabled, :disabled')) return

        var $parent = getParent($this)
        var isActive = $parent.hasClass('open')

        if (!isActive || (isActive && e.keyCode == 27)) {
            if (e.which == 27) $parent.find(toggle).focus()
            return $this.click()
        }

        var desc = ' li:not(.divider):visible a'

        var $items = $parent.find('[role=menu]' + desc + ', [role=listbox]' + desc)

        if (!$items.length) return

        var index = $items.index($items.filter(':focus'))

        if (e.keyCode == 38 && index > 0) index--                        /** _agile_  up _agile_  **/
        if (e.keyCode == 40 && index < $items.length - 1) index++                        /** _agile_  down _agile_  **/
        if (!~index) index = 0

        $items.eq(index).focus()
    }

    function clearMenus(e) {
        $(backdrop).remove()
        $(toggle).each(function () {
            var $parent = getParent($(this))
            var relatedTarget = { relatedTarget: this }
            if (!$parent.hasClass('open')) return
            $parent.trigger(e = $.Event('hide.bs.agile-dropdown', relatedTarget))
            if (e.isDefaultPrevented()) return
            $parent.removeClass('open').trigger('hidden.bs.agile-dropdown', relatedTarget)
        })
    }

    function getParent($this) {
        var selector = $this.attr('data-target')

        if (!selector) {
            selector = $this.attr('href')
            selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') /** _agile_ strip for ie7 _agile_  **/
        }

        var $parent = selector && $(selector)

        return $parent && $parent.length ? $parent : $this.parent()
    }


    /** _agile_  agile-dropdown PLUGIN DEFINITION _agile_  **/
    /** _agile_  ========================== _agile_  **/

    var old = $.fn.dropdown

    $.fn.dropdown = function (option) {
        return this.each(function () {
            var $this = $(this)
            var data = $this.data('bs.agile-dropdown')

            if (!data) $this.data('bs.agile-dropdown', (data = new dropdown(this)))
            if (typeof option == 'string') data[option].call($this)
        })
    }

    $.fn.dropdown.Constructor = dropdown


    /** _agile_  dropdown NO CONFLICT _agile_  **/
    /** _agile_  ==================== _agile_  **/

    $.fn.dropdown.noConflict = function () {
        $.fn.dropdown = old
        return this
    }


    /** _agile_  APPLY TO STANDARD dropdown ELEMENTS _agile_  **/
    /** _agile_  =================================== _agile_  **/

    $(document)
      .on('click.bs.agile-dropdown.data-api', clearMenus)
      .on('click.bs.agile-dropdown.data-api', '.agile-dropdown form', function (e) { e.stopPropagation() })
      .on('click.bs.agile-dropdown.data-api', toggle, dropdown.prototype.toggle)
      .on('keydown.bs.agile-dropdown.data-api', toggle + ', [role=menu], [role=listbox]', dropdown.prototype.keydown)

}(jQuery);
