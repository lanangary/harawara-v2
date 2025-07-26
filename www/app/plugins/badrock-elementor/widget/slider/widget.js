function initBadrockSwiper($scope) {
  var $swiper = $scope.find('.swiper');
  if ($swiper.length && typeof Swiper !== 'undefined') {
    // Destroy existing Swiper instance if any
    if ($swiper[0].swiper) {
      $swiper[0].swiper.destroy(true, true);
    }

    // Check if arrows should be hidden
    var hideArrows = $scope.hasClass('hide-arrows');

    new Swiper($swiper[0], {
      direction: 'horizontal',
      loop: true,
      effect: 'fade',
      fadeEffect: { crossFade: true },
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      pagination: {
        el: $scope.find('.swiper-pagination')[0],
      },
      navigation: hideArrows
        ? false
        : {
            nextEl: $scope.find('.swiper-button-next')[0],
            prevEl: $scope.find('.swiper-button-prev')[0],
          },
      scrollbar: {
        el: $scope.find('.swiper-scrollbar')[0],
      },
    });
  }
}

// Frontend (normal page load)
document.addEventListener('DOMContentLoaded', function () {
  var $scope = window.jQuery ? window.jQuery('body') : null;
  if ($scope) {
    initBadrockSwiper($scope);
  }
});

// Elementor editor live preview
if (window.elementorFrontend) {
  window.elementorFrontend.hooks.addAction('frontend/element_ready/badrock_slider_block.default', function($scope) {
    initBadrockSwiper($scope);
  });
}

