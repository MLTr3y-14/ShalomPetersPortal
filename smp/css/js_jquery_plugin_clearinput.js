/*
 * clearingInput: a jQuery plugin
 *
 * clearingInput is a simple jQuery plugin that provides example/label text
 * inside text inputs that automatically clears when the input is focused.
 * Common uses are for a hint/example, or as a label when space is limited.
 *
 * For usage and examples, visit:
 * http://github.com/alexrabarts/jquery-clearinginput
 *
 * Licensed under the MIT:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright (c) 2008 Stateless Systems (http://statelesssystems.com)
 *
 * @author   Alex Rabarts (alexrabarts -at- gmail -dawt- com)
 * @requires jQuery v1.2 or later
 * @version  0.1.2
 */

(function($){$.extend($.fn,{clearingInput:function(d){var e={blurClass:'blur'};d=$.extend(e,d);return this.each(function(){var a=$(this).addClass(d.blurClass);var b=a.parents('form:first');var c,text;text=d.text||textFromLabel()||a.val();if(text){a.val(text);a.blur(function(){if(a.val()===''){a.addClass(d.blurClass).val(text)}}).focus(function(){if(a.val()===text){a.val('')}a.removeClass(d.blurClass)});b.submit(function(){if(a.hasClass(d.blurClass)){a.val('')}});a.blur()}function textFromLabel(){c=b.find('label[for='+a.attr('id')+']');return c?c.css({position:'absolute',left:'-9999px'}).text():''}})}})})(jQuery);