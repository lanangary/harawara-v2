(window["jbWebpackJsonp"] = window["jbWebpackJsonp"] || []).push([["/www/app/themes/harawara/dist/js/admin"],{

/***/ "./www/app/themes/harawara/js/admin.js":
/*!*********************************************!*\
  !*** ./www/app/themes/harawara/js/admin.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * ACF module screenshots
 */
jQuery(function ($) {
  function snakeToCamel(s) {
    return capitalizeFirstLetter(s.replace(/(\_\w)/g, function (m) {
      return m[1].toUpperCase();
    }));
  }
  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
  $(document.body).on({
    mouseenter: function mouseenter() {
      var $item = $(this),
        nameSpace = snakeToCamel($item.find('a').data('layout'));
      if (!$item.find('img').length) {
        var image = acfJpgData.themeUri + '/src/JuiceBox/Modules/' + nameSpace + '/screenshot.png';
        image = $('<img src="' + image + '" class="acf-fc-jpg" />');
        image.on('error', function () {
          $(this).attr('src', acfJpgData.themeUri + '/src/JuiceBox/Modules/__template/screenshot.png');
        });
        image.hide(0).css({
          position: 'absolute',
          right: 'calc(100% + 20px)',
          width: '800px',
          top: '50%',
          transform: 'translateY(-50%)',
          boxShadow: '0px 2px 10px 3px rgba(0,0,0,0.2)'
        });
        $item.find('a').append(image);
      } else {
        $item.find('img').fadeIn(300);
      }
    },
    mouseleave: function mouseleave() {
      $('.acf-fc-popup li img').hide(0);
    }
  }, '.acf-fc-popup li');
});

/***/ }),

/***/ 1:
/*!***************************************************!*\
  !*** multi ./www/app/themes/harawara/js/admin.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\laragon\fresh-theme\www\app\themes\harawara\js\admin.js */"./www/app/themes/harawara/js/admin.js");


/***/ })

},[[1,"/www/app/themes/harawara/dist/js/manifest"]]]);