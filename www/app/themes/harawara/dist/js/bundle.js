(window["jbWebpackJsonp"] = window["jbWebpackJsonp"] || []).push([["/www/app/themes/harawara/dist/js/bundle"],{

/***/ "./www/app/themes/harawara/js/main.js":
/*!********************************************!*\
  !*** ./www/app/themes/harawara/js/main.js ***!
  \********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _plugins_niceSelect__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugins/niceSelect */ "./www/app/themes/harawara/js/plugins/niceSelect.js");
/* harmony import */ var _plugins_niceSelect__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_plugins_niceSelect__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _plugins_scroll_to__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./plugins/scroll-to */ "./www/app/themes/harawara/js/plugins/scroll-to.js");
/* harmony import */ var _plugins_scroll_to__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_plugins_scroll_to__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var JBSrc_in_view__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! JBSrc/in-view */ "./www/app/themes/harawara/js/src/in-view.js");
/* harmony import */ var JBSrc_gform_spinner__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! JBSrc/gform-spinner */ "./www/app/themes/harawara/js/src/gform-spinner.js");
/* harmony import */ var JBSrc_gform_spinner__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(JBSrc_gform_spinner__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var JBSrc_gtm_events__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! JBSrc/gtm-events */ "./www/app/themes/harawara/js/src/gtm-events.js");
/* harmony import */ var JBSrc_gtm_events__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(JBSrc_gtm_events__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var JBSrc_registration__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! JBSrc/registration */ "./www/app/themes/harawara/js/src/registration.js");
/* harmony import */ var JBComponent_Header__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! JBComponent/Header */ "./www/app/themes/harawara/src/JuiceBox/Components/Header/index.js");
/* harmony import */ var JBComponent_Searchbox__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! JBComponent/Searchbox */ "./www/app/themes/harawara/src/JuiceBox/Components/Searchbox/index.js");
/* harmony import */ var JBComponent_PageReader__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! JBComponent/PageReader */ "./www/app/themes/harawara/src/JuiceBox/Components/PageReader/index.js");
/* harmony import */ var JBComponent_PageReader__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(JBComponent_PageReader__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var JBModule_BannerCta__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! JBModule/BannerCta */ "./www/app/themes/harawara/src/JuiceBox/Modules/BannerCta/index.js");
/* harmony import */ var JBModule_BannerCta__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(JBModule_BannerCta__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var JBModule_Faq__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! JBModule/Faq */ "./www/app/themes/harawara/src/JuiceBox/Modules/Faq/index.js");
/* harmony import */ var JBModule_Faq__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(JBModule_Faq__WEBPACK_IMPORTED_MODULE_10__);


//import './plugins/fancybox';
//import './plugins/maps';
//import './plugins/newsletter';





//import 'JBSrc/page-transitions';







/***/ }),

/***/ "./www/app/themes/harawara/js/plugins/in-view.js":
/*!*******************************************************!*\
  !*** ./www/app/themes/harawara/js/plugins/in-view.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}
/*!
 * kudago-in-view 2.0.2 - Get notified when a DOM element enters or exits the viewport.
 * Copyright (c) 2017 Yura Trambitskiy <tram.yura@gmail.com> - https://github.com/kudago/in-view
 * License: MIT
 */
!function (t, e) {
  'object' == ( false ? undefined : _typeof(exports)) && 'object' == ( false ? undefined : _typeof(module)) ? module.exports = e() :  true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (e),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
}(this, function () {
  return function (t) {
    function e(r) {
      if (n[r]) return n[r].exports;
      var o = n[r] = {
        exports: {},
        id: r,
        loaded: !1
      };
      return t[r].call(o.exports, o, o.exports, e), o.loaded = !0, o.exports;
    }
    var n = {};
    return e.m = t, e.c = n, e.p = '', e(0);
  }([function (t, e, n) {
    'use strict';

    function r(t) {
      return t && t.__esModule ? t : {
        "default": t
      };
    }
    Object.defineProperty(e, '__esModule', {
      value: !0
    });
    var o = n(2),
      i = r(o),
      u = n(3),
      f = n(8),
      s = r(f),
      c = function c() {
        if ('undefined' != typeof window) {
          var t = 100,
            e = ['scroll', 'resize', 'load'],
            n = {
              history: []
            },
            r = {
              offset: {},
              threshold: 0,
              test: u.inViewport
            },
            o = (0, s["default"])(function () {
              n.history.forEach(function (t) {
                n[t].check();
              });
            }, t);
          e.forEach(function (t) {
            return addEventListener(t, o);
          }), window.MutationObserver && addEventListener('DOMContentLoaded', function () {
            new MutationObserver(o).observe(document.body, {
              attributes: !0,
              childList: !0,
              subtree: !0
            });
          });
          var f = function f(t) {
            if ('string' == typeof t) {
              var e = [].slice.call(document.querySelectorAll(t));
              return n.history.indexOf(t) > -1 ? n[t].elements = e : (n[t] = (0, i["default"])(e, r), n.history.push(t)), n[t];
            }
          };
          return f.offset = function (t) {
            if (void 0 === t) return r.offset;
            var e = function e(t) {
              return 'number' == typeof t;
            };
            return ['top', 'right', 'bottom', 'left'].forEach(e(t) ? function (e) {
              return r.offset[e] = t;
            } : function (n) {
              return e(t[n]) ? r.offset[n] = t[n] : null;
            }), r.offset;
          }, f.threshold = function (t) {
            return 'number' == typeof t && t >= 0 && t <= 1 ? r.threshold = t : r.threshold;
          }, f.test = function (t) {
            return 'function' == typeof t ? r.test = t : r.test;
          }, f.is = function (t) {
            return r.test(t, r);
          }, f.offset(0), f;
        }
      };
    e["default"] = c;
  }, function (t, e) {
    function n(t) {
      var e = _typeof(t);
      return null != t && ('object' == e || 'function' == e);
    }
    t.exports = n;
  }, function (t, e) {
    'use strict';

    function n(t, e) {
      if (!(t instanceof e)) throw new TypeError('Cannot call a class as a function');
    }
    Object.defineProperty(e, '__esModule', {
      value: !0
    });
    var r = function () {
        function t(t, e) {
          for (var n = 0; n < e.length; n++) {
            var r = e[n];
            r.enumerable = r.enumerable || !1, r.configurable = !0, 'value' in r && (r.writable = !0), Object.defineProperty(t, r.key, r);
          }
        }
        return function (e, n, r) {
          return n && t(e.prototype, n), r && t(e, r), e;
        };
      }(),
      o = function () {
        function t(e, r) {
          n(this, t), this.options = r, this.elements = e, this.current = [], this.handlers = {
            enter: [],
            exit: []
          }, this.singles = {
            enter: [],
            exit: []
          };
        }
        return r(t, [{
          key: 'check',
          value: function value() {
            var t = this;
            return this.elements.forEach(function (e) {
              var n = t.options.test(e, t.options),
                r = t.current.indexOf(e),
                o = r > -1,
                i = n && !o,
                u = !n && o;
              i && (t.current.push(e), t.emit('enter', e)), u && (t.current.splice(r, 1), t.emit('exit', e));
            }), this;
          }
        }, {
          key: 'on',
          value: function value(t, e) {
            return this.handlers[t].push(e), this;
          }
        }, {
          key: 'once',
          value: function value(t, e) {
            return this.singles[t].unshift(e), this;
          }
        }, {
          key: 'off',
          value: function value(t, e) {
            return this.handlers[t] = this.handlers[t].filter(function (t) {
              return t !== e;
            }), this;
          }
        }, {
          key: 'emit',
          value: function value(t, e) {
            for (; this.singles[t].length;) this.singles[t].pop()(e);
            for (var n = this.handlers[t].length; --n > -1;) this.handlers[t][n](e);
            return this;
          }
        }]), t;
      }();
    e["default"] = function (t, e) {
      return new o(t, e);
    };
  }, function (t, e) {
    'use strict';

    function n(t, e) {
      var n = t.getBoundingClientRect(),
        r = n.top,
        o = n.right,
        i = n.bottom,
        u = n.left,
        f = n.width,
        s = n.height,
        c = {
          t: i,
          r: window.innerWidth - u,
          b: window.innerHeight - r,
          l: o
        },
        a = {
          x: e.threshold * f,
          y: e.threshold * s
        },
        l = c.t > e.offset.top + a.y && c.b > e.offset.bottom + a.y || c.t < -e.offset.top && c.b < -e.offset.bottom,
        h = c.r > e.offset.right + a.x && c.l > e.offset.left + a.x || c.r < -e.offset.right && c.l < -e.offset.left;
      return l && h;
    }
    Object.defineProperty(e, '__esModule', {
      value: !0
    }), e.inViewport = n;
  }, function (t, e) {
    (function (e) {
      var n = 'object' == _typeof(e) && e && e.Object === Object && e;
      t.exports = n;
    }).call(e, function () {
      return this;
    }());
  }, function (t, e, n) {
    var r = n(4),
      o = 'object' == (typeof self === "undefined" ? "undefined" : _typeof(self)) && self && self.Object === Object && self,
      i = r || o || Function('return this')();
    t.exports = i;
  }, function (t, e, n) {
    function r(t, e, n) {
      function r(e) {
        var n = m,
          r = x;
        return m = x = void 0, E = e, w = t.apply(r, n);
      }
      function a(t) {
        return E = t, j = setTimeout(d, e), M ? r(t) : w;
      }
      function l(t) {
        var n = t - O,
          r = t - E,
          o = e - n;
        return k ? c(o, g - r) : o;
      }
      function h(t) {
        var n = t - O,
          r = t - E;
        return void 0 === O || n >= e || n < 0 || k && r >= g;
      }
      function d() {
        var t = i();
        return h(t) ? p(t) : void (j = setTimeout(d, l(t)));
      }
      function p(t) {
        return j = void 0, T && m ? r(t) : (m = x = void 0, w);
      }
      function v() {
        void 0 !== j && clearTimeout(j), E = 0, m = O = x = j = void 0;
      }
      function y() {
        return void 0 === j ? w : p(i());
      }
      function b() {
        var t = i(),
          n = h(t);
        if (m = arguments, x = this, O = t, n) {
          if (void 0 === j) return a(O);
          if (k) return j = setTimeout(d, e), r(O);
        }
        return void 0 === j && (j = setTimeout(d, e)), w;
      }
      var m,
        x,
        g,
        w,
        j,
        O,
        E = 0,
        M = !1,
        k = !1,
        T = !0;
      if ('function' != typeof t) throw new TypeError(f);
      return e = u(e) || 0, o(n) && (M = !!n.leading, k = 'maxWait' in n, g = k ? s(u(n.maxWait) || 0, e) : g, T = 'trailing' in n ? !!n.trailing : T), b.cancel = v, b.flush = y, b;
    }
    var o = n(1),
      i = n(7),
      u = n(9),
      f = 'Expected a function',
      s = Math.max,
      c = Math.min;
    t.exports = r;
  }, function (t, e, n) {
    var r = n(5),
      o = function o() {
        return r.Date.now();
      };
    t.exports = o;
  }, function (t, e, n) {
    function r(t, e, n) {
      var r = !0,
        f = !0;
      if ('function' != typeof t) throw new TypeError(u);
      return i(n) && (r = 'leading' in n ? !!n.leading : r, f = 'trailing' in n ? !!n.trailing : f), o(t, e, {
        leading: r,
        maxWait: e,
        trailing: f
      });
    }
    var o = n(6),
      i = n(1),
      u = 'Expected a function';
    t.exports = r;
  }, function (t, e) {
    function n(t) {
      return t;
    }
    t.exports = n;
  }]);
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../../../../node_modules/webpack/buildin/module.js */ "./node_modules/webpack/buildin/module.js")(module)))

/***/ }),

/***/ "./www/app/themes/harawara/js/plugins/niceSelect.js":
/*!**********************************************************!*\
  !*** ./www/app/themes/harawara/js/plugins/niceSelect.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*  jQuery Nice Select - v1.1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */

(function ($) {
  $.fn.niceSelect = function (method) {
    // Methods
    if (typeof method == 'string') {
      if (method == 'update') {
        this.each(function () {
          var $select = $(this);
          var $dropdown = $(this).next('.nice-select');
          var open = $dropdown.hasClass('open');
          if ($dropdown.length) {
            $dropdown.remove();
            create_nice_select($select);
            if (open) {
              $select.next().trigger('click');
            }
          }
        });
      } else if (method == 'destroy') {
        this.each(function () {
          var $select = $(this);
          var $dropdown = $(this).next('.nice-select');
          if ($dropdown.length) {
            $dropdown.remove();
            $select.css('display', '');
          }
        });
        if ($('.nice-select').length == 0) {
          $(document).off('.nice_select');
        }
      } else {
        console.log('Method "' + method + '" does not exist.');
      }
      return this;
    }

    // Hide native select
    this.hide();

    // Create custom markup
    this.each(function () {
      var $select = $(this);
      if (!$select.next().hasClass('nice-select')) {
        create_nice_select($select);
      }
    });
    function create_nice_select($select) {
      $select.after($('<div></div>').addClass('nice-select').addClass($select.attr('class') || '').addClass($select.attr('disabled') ? 'disabled' : '').attr('tabindex', $select.attr('disabled') ? null : '0').html('<span class="current"></span><ul class="list"></ul>'));
      var $dropdown = $select.next();
      var $options = $select.find('option');
      var $selected = $select.find('option:selected');
      $dropdown.find('.current').html($selected.data('display') || $selected.text());
      $options.each(function (i) {
        var $option = $(this);
        var display = $option.data('display');
        $dropdown.find('ul').append($('<li></li>').attr('data-value', $option.val()).attr('data-display', display || null).addClass('option' + ($option.is(':selected') ? ' selected' : '') + ($option.is(':disabled') ? ' disabled' : '')).html($option.text()));
      });
    }

    /* Event listeners */

    // Unbind existing events in case that the plugin has been initialized before
    $(document).off('.nice_select');

    // Open/close
    $(document).on('click.nice_select', '.nice-select', function (event) {
      var $dropdown = $(this);
      $('.nice-select').not($dropdown).removeClass('open');
      $dropdown.toggleClass('open');
      if ($dropdown.hasClass('open')) {
        $dropdown.find('.option');
        $dropdown.find('.focus').removeClass('focus');
        $dropdown.find('.selected').addClass('focus');
      } else {
        $dropdown.focus();
      }
    });

    // Close when clicking outside
    $(document).on('click.nice_select', function (event) {
      if ($(event.target).closest('.nice-select').length === 0) {
        $('.nice-select').removeClass('open').find('.option');
      }
    });

    // Option click
    $(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function (event) {
      var $option = $(this);
      var $dropdown = $option.closest('.nice-select');
      $dropdown.find('.selected').removeClass('selected');
      $option.addClass('selected');
      var text = $option.data('display') || $option.text();
      $dropdown.find('.current').text(text);
      $dropdown.prev('select').val($option.data('value')).trigger('change');
    });

    // Keyboard events
    $(document).on('keydown.nice_select', '.nice-select', function (event) {
      var $dropdown = $(this);
      var $focused_option = $($dropdown.find('.focus') || $dropdown.find('.list .option.selected'));

      // Space or Enter
      if (event.keyCode == 32 || event.keyCode == 13) {
        if ($dropdown.hasClass('open')) {
          $focused_option.trigger('click');
        } else {
          $dropdown.trigger('click');
        }
        return false;
        // Down
      } else if (event.keyCode == 40) {
        if (!$dropdown.hasClass('open')) {
          $dropdown.trigger('click');
        } else {
          var $next = $focused_option.nextAll('.option:not(.disabled)').first();
          if ($next.length > 0) {
            $dropdown.find('.focus').removeClass('focus');
            $next.addClass('focus');
          }
        }
        return false;
        // Up
      } else if (event.keyCode == 38) {
        if (!$dropdown.hasClass('open')) {
          $dropdown.trigger('click');
        } else {
          var $prev = $focused_option.prevAll('.option:not(.disabled)').first();
          if ($prev.length > 0) {
            $dropdown.find('.focus').removeClass('focus');
            $prev.addClass('focus');
          }
        }
        return false;
        // Esc
      } else if (event.keyCode == 27) {
        if ($dropdown.hasClass('open')) {
          $dropdown.trigger('click');
        }
        // Tab
      } else if (event.keyCode == 9) {
        if ($dropdown.hasClass('open')) {
          return false;
        }
      }
    });

    // Detect CSS pointer-events support, for IE <= 10. From Modernizr.
    var style = document.createElement('a').style;
    style.cssText = 'pointer-events:auto';
    if (style.pointerEvents !== 'auto') {
      $('html').addClass('no-csspointerevents');
    }
    return this;
  };
})(jQuery);

/***/ }),

/***/ "./www/app/themes/harawara/js/plugins/scroll-to.js":
/*!*********************************************************!*\
  !*** ./www/app/themes/harawara/js/plugins/scroll-to.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(function ($) {
  var header_offset = $('.header').css('position') == 'sticky' || $('.header').css('position') == 'fixed' ? parseInt($('.header').css('height')) : 0;
  var wpadmin = $('#wpadminbar').length ? parseInt($('#wpadminbar').css('height')) : 0;
  header_offset = (header_offset + wpadmin) * 1.05;
  window.scrollAnchorTo = function (item, speed) {
    var item_offset = 0;
    var clicked_item = $('[href="' + item + '"]');
    if (speed == undefined) {
      speed = 800;
    }
    if ($(item).length > 0) {
      item_offset = $(item).offset() !== null ? $(item).offset().top : 0;
      if (item_offset >= 0) {
        $('html,body').animate({
          scrollTop: $(item).offset().top - header_offset
        }, speed, function () {
          if (clicked_item.length) {
            if (item.substring(0, 1) === '#') {
              window.location.hash = item;
              window.scrollTo(0, $(item).offset().top - header_offset);
            } else {
              window.history.pushState(null, clicked_item.attr('title'), clicked_item.attr('href'));
            }
          }
        });
      }
    }
  };
  $(document).on('click', '.scrollTo', function (e) {
    e.preventDefault();
    var hash = $(this).attr('href');
    var $speed = $(this).data('speed') ? $(this).data('speed') : 800;
    window.scrollAnchorTo(hash, $speed);
  });
  $(document).on('click', 'a[href^="#"]', function (e) {
    e.preventDefault();
    var hash = $(this).attr('href');
    var $speed = $(this).data('speed') ? $(this).data('speed') : 800;
    window.scrollAnchorTo(hash, $speed);
  });
  if (window.location.hash) {
    var window_hash = window.location.hash;
    window.scrollAnchorTo(window_hash, 800);
  }
});

/***/ }),

/***/ "./www/app/themes/harawara/js/src/constants.js":
/*!*****************************************************!*\
  !*** ./www/app/themes/harawara/js/src/constants.js ***!
  \*****************************************************/
/*! exports provided: Breakpoints, TransitionSpeeds */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Breakpoints", function() { return Breakpoints; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TransitionSpeeds", function() { return TransitionSpeeds; });
var Breakpoints = {
  xs: '500',
  sm: '768',
  md: '1025',
  lg: '1200',
  xlg: '1366'
};
var TransitionSpeeds = {
  slow: 0.8,
  "default": 0.5,
  fast: 0.3
};

/***/ }),

/***/ "./www/app/themes/harawara/js/src/gform-spinner.js":
/*!*********************************************************!*\
  !*** ./www/app/themes/harawara/js/src/gform-spinner.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(function ($) {
  $(document.body).on('submit', '.gform_wrapper form', function () {
    var span = $('<span>').addClass('gform-spinner__inner'); // add a throwaway span element to the button and style that, rather than potentially overwriting any psuedo elements on the button
    $(this).find('button').last().append(span).addClass('gform-spinner');
  });

  // Google reCaptcha v2 (CAPTCHA field needs to be added to each form)
  $('.ginput_recaptcha').each(function () {
    $(this).before('<p class="ginput_recaptcha_terms">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="blank">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="blank">Terms of Service</a> apply.</p>');
    $(this).closest('.form-group').find('.gfield_label').removeAttr('for');
  });

  // Google reCaptcha v3 (we automatically add the text for the form) - needs Gravity Forms - reCaptcha addon
  $('.ginput_recaptchav3').each(function () {
    $(this).closest('form').find('.gform_footer').before('<p class="ginput_recaptcha_terms">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="blank">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="blank">Terms of Service</a> apply.</p>');
  });
});

/***/ }),

/***/ "./www/app/themes/harawara/js/src/gtm-events.js":
/*!******************************************************!*\
  !*** ./www/app/themes/harawara/js/src/gtm-events.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(function ($) {
  window.dataLayer = window.dataLayer || [];

  //On AJAX submit do we have a Gravity Form confirmation? If so trigger dataLayer Event
  $(document).bind('gform_confirmation_loaded', function (event, formID) {
    var formTitle = $('#gform_' + formID).find('.gform_heading .gform_title').length ? $('#gform_' + formID).find('.gform_heading .gform_title').text() : '';
    window.dataLayer.push({
      event: 'formSubmission',
      eventCategory: formTitle != '' ? formTitle + ' Form Submission' : 'Form Submission',
      eventAction: 'Submit',
      eventLabel: window.location.href,
      formTitle: formTitle,
      formID: formID
    });
  });

  //On load do we have a Gravity Form confirmation? If so trigger dataLayer Event
  $('.gform_confirmation_wrapper .gform_confirmation_message').each(function () {
    var formID = $(this).attr('id').replace('gform_confirmation_message_', '');
    window.dataLayer.push({
      event: 'formSubmission',
      eventCategory: 'Form Submission',
      eventAction: 'Submit',
      eventLabel: window.location.href,
      formID: formID
    });
  });
});

/***/ }),

/***/ "./www/app/themes/harawara/js/src/in-view.js":
/*!***************************************************!*\
  !*** ./www/app/themes/harawara/js/src/in-view.js ***!
  \***************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _plugins_in_view__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../plugins/in-view */ "./www/app/themes/harawara/js/plugins/in-view.js");
/* harmony import */ var _plugins_in_view__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_plugins_in_view__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var JBSrc_constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! JBSrc/constants */ "./www/app/themes/harawara/js/src/constants.js");
function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}
function _classCallCheck(a, n) {
  if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}
function _defineProperties(e, r) {
  for (var t = 0; t < r.length; t++) {
    var o = r[t];
    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o);
  }
}
function _createClass(e, r, t) {
  return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
    writable: !1
  }), e;
}
function _toPropertyKey(t) {
  var i = _toPrimitive(t, "string");
  return "symbol" == _typeof(i) ? i : i + "";
}
function _toPrimitive(t, r) {
  if ("object" != _typeof(t) || !t) return t;
  var e = t[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t, r || "default");
    if ("object" != _typeof(i)) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t);
}


var JBInView = /*#__PURE__*/function () {
  function JBInView() {
    _classCallCheck(this, JBInView);
  }
  return _createClass(JBInView, null, [{
    key: "init",
    value: function init() {
      var inViewDefault = _plugins_in_view__WEBPACK_IMPORTED_MODULE_0___default()();
      var inViewInit = _plugins_in_view__WEBPACK_IMPORTED_MODULE_0___default()();
      inViewDefault.threshold(0.2);
      // Dont add init class if already in viewport.
      jQuery('.jb-scroll').each(function (i, el) {
        if (!inViewInit.is(el)) {
          jQuery(el).addClass('jb-scroll-init');
        }
      });
      inViewDefault('.jb-scroll').on('enter', function (el) {
        if (!el.inViewDone) {
          jQuery(el).addClass('in-view');
        }
      }).on('exit', function (el) {
        return el.inViewDone = true;
      });
    }
  }]);
}();
jQuery(function ($) {
  if ($(window).width() > JBSrc_constants__WEBPACK_IMPORTED_MODULE_1__["Breakpoints"].sm) {
    // Delay allows time for page to load itself in properly.
    setTimeout(JBInView.init, 500);
  }
});

/***/ }),

/***/ "./www/app/themes/harawara/js/src/registration.js":
/*!********************************************************!*\
  !*** ./www/app/themes/harawara/js/src/registration.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}
function _classCallCheck(a, n) {
  if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}
function _defineProperties(e, r) {
  for (var t = 0; t < r.length; t++) {
    var o = r[t];
    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o);
  }
}
function _createClass(e, r, t) {
  return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
    writable: !1
  }), e;
}
function _toPropertyKey(t) {
  var i = _toPrimitive(t, "string");
  return "symbol" == _typeof(i) ? i : i + "";
}
function _toPrimitive(t, r) {
  if ("object" != _typeof(t) || !t) return t;
  var e = t[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t, r || "default");
    if ("object" != _typeof(i)) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t);
}
/**
 * Registration Form Validation and UX
 */
var RegistrationForm = /*#__PURE__*/function () {
  function RegistrationForm() {
    _classCallCheck(this, RegistrationForm);
    this.form = document.querySelector('.registration-form');
    if (!this.form) return;
    this.init();
  }
  return _createClass(RegistrationForm, [{
    key: "init",
    value: function init() {
      this.bindEvents();
      this.setupPasswordValidation();
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this = this;
      this.form.addEventListener('submit', this.handleSubmit.bind(this));

      // Real-time validation
      var inputs = this.form.querySelectorAll('input[required]');
      inputs.forEach(function (input) {
        input.addEventListener('blur', _this.validateField.bind(_this, input));
        input.addEventListener('input', _this.clearFieldError.bind(_this, input));
      });
    }
  }, {
    key: "setupPasswordValidation",
    value: function setupPasswordValidation() {
      var _this2 = this;
      var password = this.form.querySelector('#password');
      var confirmPassword = this.form.querySelector('#confirm_password');
      if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function () {
          _this2.validatePasswordMatch(password, confirmPassword);
        });
      }
    }
  }, {
    key: "validateField",
    value: function validateField(field) {
      var value = field.value.trim();
      var isValid = true;
      var errorMessage = '';

      // Remove existing error styling
      this.clearFieldError(field);

      // Field-specific validation
      switch (field.name) {
        case 'username':
          if (value.length < 3) {
            isValid = false;
            errorMessage = 'Username must be at least 3 characters long.';
          } else if (!/^[a-zA-Z0-9_-]+$/.test(value)) {
            isValid = false;
            errorMessage = 'Username can only contain letters, numbers, underscores, and hyphens.';
          }
          break;
        case 'email':
          if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address.';
          }
          break;
        case 'password':
          if (value.length < 8) {
            isValid = false;
            errorMessage = 'Password must be at least 8 characters long.';
          }
          break;
        case 'confirm_password':
          var password = this.form.querySelector('#password').value;
          if (value !== password) {
            isValid = false;
            errorMessage = 'Passwords do not match.';
          }
          break;
        case 'phone_number':
          if (value && !/^[\+]?[1-9][\d]{0,15}$/.test(value.replace(/[\s\-\(\)]/g, ''))) {
            isValid = false;
            errorMessage = 'Please enter a valid phone number.';
          }
          break;
      }
      if (!isValid) {
        this.showFieldError(field, errorMessage);
      }
      return isValid;
    }
  }, {
    key: "validatePasswordMatch",
    value: function validatePasswordMatch(password, confirmPassword) {
      if (confirmPassword.value && password.value !== confirmPassword.value) {
        this.showFieldError(confirmPassword, 'Passwords do not match.');
        return false;
      } else {
        this.clearFieldError(confirmPassword);
        return true;
      }
    }
  }, {
    key: "showFieldError",
    value: function showFieldError(field, message) {
      field.classList.add('error');

      // Remove existing error message
      var existingError = field.parentNode.querySelector('.field-error');
      if (existingError) {
        existingError.remove();
      }

      // Add new error message
      var errorDiv = document.createElement('div');
      errorDiv.className = 'field-error';
      errorDiv.style.cssText = 'color: #dc3545; font-size: 14px; margin-top: 5px;';
      errorDiv.textContent = message;
      field.parentNode.appendChild(errorDiv);
    }
  }, {
    key: "clearFieldError",
    value: function clearFieldError(field) {
      field.classList.remove('error');
      var errorDiv = field.parentNode.querySelector('.field-error');
      if (errorDiv) {
        errorDiv.remove();
      }
    }
  }, {
    key: "validateForm",
    value: function validateForm() {
      var _this3 = this;
      var requiredFields = this.form.querySelectorAll('input[required]');
      var isValid = true;
      requiredFields.forEach(function (field) {
        if (!_this3.validateField(field)) {
          isValid = false;
        }
      });
      return isValid;
    }
  }, {
    key: "handleSubmit",
    value: function handleSubmit(e) {
      if (!this.validateForm()) {
        e.preventDefault();
        return false;
      }

      // Show loading state
      this.showLoadingState();
    }
  }, {
    key: "showLoadingState",
    value: function showLoadingState() {
      this.form.classList.add('loading');
      var submitBtn = this.form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.textContent = 'Creating Account...';
        submitBtn.disabled = true;
      }
    }
  }]);
}(); // Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
  new RegistrationForm();
});
/* harmony default export */ __webpack_exports__["default"] = (RegistrationForm);

/***/ }),

/***/ "./www/app/themes/harawara/scss/admin.scss":
/*!*************************************************!*\
  !*** ./www/app/themes/harawara/scss/admin.scss ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./www/app/themes/harawara/scss/editor-style.scss":
/*!********************************************************!*\
  !*** ./www/app/themes/harawara/scss/editor-style.scss ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./www/app/themes/harawara/scss/login.scss":
/*!*************************************************!*\
  !*** ./www/app/themes/harawara/scss/login.scss ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./www/app/themes/harawara/scss/main.scss":
/*!************************************************!*\
  !*** ./www/app/themes/harawara/scss/main.scss ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./www/app/themes/harawara/src/JuiceBox/Components/Header/index.js":
/*!*************************************************************************!*\
  !*** ./www/app/themes/harawara/src/JuiceBox/Components/Header/index.js ***!
  \*************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var enquire_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! enquire.js */ "./node_modules/enquire.js/src/index.js");
/* harmony import */ var enquire_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(enquire_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var JBSrc_constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! JBSrc/constants */ "./www/app/themes/harawara/js/src/constants.js");


jQuery(function ($) {
  var $burger = $('.hamburger');
  var $navContainer = $('.header__nav');
  var $toggle = $('.nav__toggle');
  $burger.on('click', function () {
    document.body.classList.toggle('nav-active');
  });
  $toggle.on('click', function (event) {
    var $this = $(event.currentTarget);
    $this.parent().toggleClass('nav__li--submenu-open').end().next('.nav__submenu').slideToggle();
  });
  enquire_js__WEBPACK_IMPORTED_MODULE_0___default.a.register("screen and (max-width: ".concat(JBSrc_constants__WEBPACK_IMPORTED_MODULE_1__["Breakpoints"].sm, "px)"), {
    match: function match() {
      $navContainer.detach().insertAfter('.page-wrap');
    },
    unmatch: function unmatch() {
      $navContainer.detach().insertAfter('.header__logo-wrapper');
    }
  });

  /**
   * JuiceBox Keyboard accessibility smooth scroll
   * each anchor tag in template.twig should have a href attribute
   * to tell this code where to skip to
   */
  var $keyboardNavs = $('.keyboard-nav a');
  $keyboardNavs.on('click', function (e) {
    e.preventDefault();
    var hash = $(e.target).attr('href');
    var $speed = $(e.target).data('speed') ? $(e.target).data('speed') : 800;
    window.scrollAnchorTo(hash, $speed);
  });
  var mainHeader = document.querySelector('#main-header');

  // add scroll class to header when scrolling up and remove when scrolling down and at top add top class

  if (mainHeader) {
    console.log('mainHeader', mainHeader);
    var lastScroll = 0;
    window.addEventListener('scroll', function () {
      var currentScroll = window.scrollY;
      if (currentScroll > lastScroll) {
        mainHeader.classList.add('scroll');
      } else {
        mainHeader.classList.remove('scroll');
      }
      if (currentScroll === 0) {
        mainHeader.classList.add('top');
      } else {
        mainHeader.classList.remove('top');
      }
      lastScroll = currentScroll;
    });
  }

  // header slide js
});

/***/ }),

/***/ "./www/app/themes/harawara/src/JuiceBox/Components/PageReader/index.js":
/*!*****************************************************************************!*\
  !*** ./www/app/themes/harawara/src/JuiceBox/Components/PageReader/index.js ***!
  \*****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(function ($) {
  $(function () {
    var pageProgress = $('.page-reader-progress__inner');
    function pageReader() {
      if (pageProgress.length) {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = winScroll / height * 100;
        pageProgress.width(scrolled + '%');
      }
    }
    $(document).on('scroll resize', function () {
      pageReader();
    });
    if (pageProgress.length) {
      $('body').addClass('has-page-reader');
    }
  });
});

/***/ }),

/***/ "./www/app/themes/harawara/src/JuiceBox/Components/Searchbox/index.js":
/*!****************************************************************************!*\
  !*** ./www/app/themes/harawara/src/JuiceBox/Components/Searchbox/index.js ***!
  \****************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash_throttle__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash/throttle */ "./node_modules/lodash/throttle.js");
/* harmony import */ var lodash_throttle__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash_throttle__WEBPACK_IMPORTED_MODULE_0__);

jQuery(function ($) {
  var $trigger = $('.header__search');
  var $close = $('.searchbox__close');
  var $input = $('.searchbox__form__input');
  var $suggestions = $('.searchbox__fuzzy');
  function search(event) {
    var $this = $(event.currentTarget);
    var value = $this.val();

    // Clear suggestions when there is no search string
    if (!value.length) {
      $suggestions.empty();
    }

    // Wait till we have 3 or more character before we search
    if (value.length < 3) {
      return;
    }
    $.get('/', {
      s: value
    }, function (data) {
      $suggestions.empty();
      if (!data.length) {
        $suggestions.append('<p class="bold searchbox__fuzzy__no-results">Sorry, there are no results for that search query.<p>');
      } else {
        var links = [];
        links = data.map(function (link) {
          return "<a href=\"".concat(link.url, "\" class=\"bold block searchbox__fuzzy__link\">").concat(link.title, "</a>");
        });
        $suggestions.append(links.join(''));
      }
    }, 'json');
  }
  $trigger.on('click', function () {
    $(document.body).addClass('search-active');
    $input.focus();
  });
  $close.on('click', function () {
    $(document.body).removeClass('search-active');
  });
  $input.on('keyup', lodash_throttle__WEBPACK_IMPORTED_MODULE_0___default()(search, 500));
  $(document).on('keydown', function (event) {
    if (!$(document.body).hasClass('search-active')) {
      return;
    }
    if (event.keyCode === 27) {
      $(document.body).removeClass('search-active');
    }
  });
});

/***/ }),

/***/ "./www/app/themes/harawara/src/JuiceBox/Modules/BannerCta/index.js":
/*!*************************************************************************!*\
  !*** ./www/app/themes/harawara/src/JuiceBox/Modules/BannerCta/index.js ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}
function _slicedToArray(r, e) {
  return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest();
}
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
function _unsupportedIterableToArray(r, a) {
  if (r) {
    if ("string" == typeof r) return _arrayLikeToArray(r, a);
    var t = {}.toString.call(r).slice(8, -1);
    return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0;
  }
}
function _arrayLikeToArray(r, a) {
  (null == a || a > r.length) && (a = r.length);
  for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
  return n;
}
function _iterableToArrayLimit(r, l) {
  var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
  if (null != t) {
    var e,
      n,
      i,
      u,
      a = [],
      f = !0,
      o = !1;
    try {
      if (i = (t = t.call(r)).next, 0 === l) {
        if (Object(t) !== t) return;
        f = !1;
      } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0);
    } catch (r) {
      o = !0, n = r;
    } finally {
      try {
        if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return;
      } finally {
        if (o) throw n;
      }
    }
    return a;
  }
}
function _arrayWithHoles(r) {
  if (Array.isArray(r)) return r;
}
function _classCallCheck(a, n) {
  if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}
function _defineProperties(e, r) {
  for (var t = 0; t < r.length; t++) {
    var o = r[t];
    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o);
  }
}
function _createClass(e, r, t) {
  return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
    writable: !1
  }), e;
}
function _toPropertyKey(t) {
  var i = _toPrimitive(t, "string");
  return "symbol" == _typeof(i) ? i : i + "";
}
function _toPrimitive(t, r) {
  if ("object" != _typeof(t) || !t) return t;
  var e = t[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t, r || "default");
    if ("object" != _typeof(i)) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t);
}
var BannerCta = /*#__PURE__*/function () {
  function BannerCta() {
    _classCallCheck(this, BannerCta);
    this.modules = document.querySelectorAll('.module.banner-cta');
  }
  return _createClass(BannerCta, [{
    key: "init",
    value: function init() {
      if (!this.modules.length) {
        return;
      }
      this.countdown();
    }
  }, {
    key: "countdown",
    value: function countdown() {
      this.modules.forEach(function (module) {
        var timerElement = module.querySelector('.banner-cta-timer');
        if (!timerElement) return;
        var targetTime = timerElement.getAttribute('data-timer');
        // Parse the date manually if the format is not ISO 8601
        var _targetTime$match$sli = targetTime.match(/(\d{2})\/(\d{2})\/(\d{4}) (\d{1,2}:\d{2}) (am|pm)/i).slice(1),
          _targetTime$match$sli2 = _slicedToArray(_targetTime$match$sli, 5),
          day = _targetTime$match$sli2[0],
          month = _targetTime$match$sli2[1],
          year = _targetTime$match$sli2[2],
          time = _targetTime$match$sli2[3],
          period = _targetTime$match$sli2[4];
        var _time$split = time.split(':'),
          _time$split2 = _slicedToArray(_time$split, 2),
          hours = _time$split2[0],
          minutes = _time$split2[1];
        var targetDate = new Date("".concat(year, "-").concat(month, "-").concat(day, "T").concat(period.toLowerCase() === 'pm' && hours !== '12' ? +hours + 12 : hours, ":").concat(minutes, ":00"));
        var updateTimer = function updateTimer() {
          var now = new Date();
          var timeDiff = targetDate - now;
          if (timeDiff <= 0) {
            timerElement.innerHTML = '<div class="expired">Expired</div>';
            return;
          }
          var days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
          var hours = Math.floor(timeDiff % (1000 * 60 * 60 * 24) / (1000 * 60 * 60));
          var minutes = Math.floor(timeDiff % (1000 * 60 * 60) / (1000 * 60));
          var seconds = Math.floor(timeDiff % (1000 * 60) / 1000);
          timerElement.querySelector('[data-set="days"] .banner-cta-timer-set-number-value:nth-child(1)').textContent = Math.floor(days / 10);
          timerElement.querySelector('[data-set="days"] .banner-cta-timer-set-number-value:nth-child(2)').textContent = days % 10;
          timerElement.querySelector('[data-set="hours"] .banner-cta-timer-set-number-value:nth-child(1)').textContent = Math.floor(hours / 10);
          timerElement.querySelector('[data-set="hours"] .banner-cta-timer-set-number-value:nth-child(2)').textContent = hours % 10;

          // minutes and second
          // timerElement.querySelector(
          //     '[data-set="minutes"] .banner-cta-timer-set-number-value:nth-child(1)'
          // ).textContent = Math.floor(minutes / 10);
          // timerElement.querySelector(
          //     '[data-set="minutes"] .banner-cta-timer-set-number-value:nth-child(2)'
          // ).textContent = minutes % 10;

          // timerElement.querySelector(
          //     '[data-set="seconds"] .banner-cta-timer-set-number-value:nth-child(1)'
          // ).textContent = Math.floor(seconds / 10);
          // timerElement.querySelector(
          //     '[data-set="seconds"] .banner-cta-timer-set-number-value:nth-child(2)'
          // ).textContent = seconds % 10;
        };
        updateTimer();
        setInterval(updateTimer, 1000);
      });
    }
  }]);
}();
var BannerCtainit = new BannerCta();
BannerCtainit.init();

/***/ }),

/***/ "./www/app/themes/harawara/src/JuiceBox/Modules/Faq/index.js":
/*!*******************************************************************!*\
  !*** ./www/app/themes/harawara/src/JuiceBox/Modules/Faq/index.js ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}
function _classCallCheck(a, n) {
  if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}
function _defineProperties(e, r) {
  for (var t = 0; t < r.length; t++) {
    var o = r[t];
    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o);
  }
}
function _createClass(e, r, t) {
  return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
    writable: !1
  }), e;
}
function _toPropertyKey(t) {
  var i = _toPrimitive(t, "string");
  return "symbol" == _typeof(i) ? i : i + "";
}
function _toPrimitive(t, r) {
  if ("object" != _typeof(t) || !t) return t;
  var e = t[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t, r || "default");
    if ("object" != _typeof(i)) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t);
}
var Faq = /*#__PURE__*/function () {
  function Faq() {
    _classCallCheck(this, Faq);
    this.modules = document.querySelectorAll('.module.faq');
  }
  return _createClass(Faq, [{
    key: "init",
    value: function init() {
      if (!this.modules.length) {
        return;
      }
      this.faqfunction();
    }
  }, {
    key: "faqfunction",
    value: function faqfunction() {
      this.modules.forEach(function (module) {
        var items = module.querySelectorAll('.faq-content-wrap-item');
        items.forEach(function (item) {
          var title = item.querySelector('.faq-content-wrap-item-title');
          var content = item.querySelector('.faq-content-wrap-item-content');

          // Initially hide the content
          content.style.height = '0';
          content.style.overflow = 'hidden';
          content.style.transition = 'height 0.3s ease';
          title.addEventListener('click', function () {
            var isOpen = item.classList.contains('open');

            // Close all other items
            items.forEach(function (otherItem) {
              if (otherItem !== item) {
                otherItem.classList.remove('open');
                var otherContent = otherItem.querySelector('.faq-content-wrap-item-content');
                otherContent.style.height = '0';
              }
            });

            // Toggle the clicked item
            if (isOpen) {
              item.classList.remove('open');
              content.style.height = '0';
            } else {
              item.classList.add('open');
              content.style.height = content.scrollHeight + 'px';
            }
          });
        });
      });
    }
  }]);
}();
var Faqinit = new Faq();
Faqinit.init();

/***/ }),

/***/ 0:
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** multi ./www/app/themes/harawara/js/main.js ./www/app/themes/harawara/scss/editor-style.scss ./www/app/themes/harawara/scss/admin.scss ./www/app/themes/harawara/scss/login.scss ./www/app/themes/harawara/scss/main.scss ***!
  \********************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! D:\laragon\fresh-theme\www\app\themes\harawara\js\main.js */"./www/app/themes/harawara/js/main.js");
__webpack_require__(/*! D:\laragon\fresh-theme\www\app\themes\harawara\scss\editor-style.scss */"./www/app/themes/harawara/scss/editor-style.scss");
__webpack_require__(/*! D:\laragon\fresh-theme\www\app\themes\harawara\scss\admin.scss */"./www/app/themes/harawara/scss/admin.scss");
__webpack_require__(/*! D:\laragon\fresh-theme\www\app\themes\harawara\scss\login.scss */"./www/app/themes/harawara/scss/login.scss");
module.exports = __webpack_require__(/*! D:\laragon\fresh-theme\www\app\themes\harawara\scss\main.scss */"./www/app/themes/harawara/scss/main.scss");


/***/ })

},[[0,"/www/app/themes/harawara/dist/js/manifest","/www/app/themes/harawara/dist/js/vendor"]]]);