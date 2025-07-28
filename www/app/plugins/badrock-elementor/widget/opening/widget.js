function initBadrockOpening($scope) {
    var $overlay = $scope.find('.badrock-opening-overlay');
    var $button = $scope.find('.badrock-opening-button');
    
    if ($overlay.length && $button.length) {
        var animationType = $overlay.data('animation') || 'fade';
        var animationDuration = parseInt($overlay.data('duration')) || 800;
        
        console.log('Initializing Badrock Opening:', {
            animationType: animationType,
            animationDuration: animationDuration,
            overlay: $overlay.length,
            button: $button.length
        });
        
        // Set the animation duration dynamically for the content
        var $content = $overlay.find('.badrock-opening-content');
        var transitionProperty = '';
        
        // Set appropriate transition based on animation type
        switch(animationType) {
            case 'fade':
                transitionProperty = 'opacity ' + animationDuration + 'ms ease';
                break;
            case 'slide-up':
            case 'slide-down':
            case 'slide-left':
            case 'slide-right':
            case 'zoom-out':
                transitionProperty = 'transform ' + animationDuration + 'ms ease';
                break;
            default:
                transitionProperty = 'all ' + animationDuration + 'ms ease';
        }
        
        $content.css('transition', transitionProperty);
        $overlay.css('transition', 'opacity ' + animationDuration + 'ms ease, visibility ' + animationDuration + 'ms ease');
        
        // Button click handler
        $button.off('click.badrock-opening').on('click.badrock-opening', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Button clicked - starting closing animation');
            
            // Add closing class for animation
            $overlay.addClass('closing');
            
            // Remove the overlay after animation completes
            setTimeout(function() {
                console.log('Animation complete - removing overlay');
                $overlay.remove();
                // Restore body scroll if it was disabled
                document.body.style.overflow = '';
            }, animationDuration + 50); // Add small buffer
        });
        
        // Optional: Close on overlay click (outside content area)
        $overlay.off('click.badrock-opening').on('click.badrock-opening', function(e) {
            if (e.target === this) {
                console.log('Overlay clicked - triggering close');
                $button.trigger('click');
            }
        });
        
        // Optional: Close on Escape key
        $(document).off('keydown.badrock-opening').on('keydown.badrock-opening', function(e) {
            if (e.keyCode === 27) { // Escape key
                console.log('Escape key pressed - triggering close');
                $button.trigger('click');
                $(document).off('keydown.badrock-opening');
            }
        });
        
        // Disable body scroll when overlay is open
        document.body.style.overflow = 'hidden';
        
        // Show overlay initially
        $overlay.show();
        
        console.log('Badrock Opening initialized successfully');
    } else {
        console.log('Badrock Opening init failed:', {
            overlay: $overlay.length,
            button: $button.length,
            scope: $scope.length
        });
    }
}

// Function to handle opening widget initialization for multiple instances
function initAllBadrockOpenings() {
    $('.badrock-opening-overlay').each(function() {
        var $this = $(this);
        var $scope = $this.closest('.elementor-element');
        if ($scope.length) {
            initBadrockOpening($scope);
        } else {
            // Fallback if not in Elementor context
            initBadrockOpening($this.parent());
        }
    });
}

// Frontend (normal page load)
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - checking for jQuery and initializing');
    if (window.jQuery) {
        window.jQuery(document).ready(function($) {
            console.log('jQuery ready - initializing all openings');
            setTimeout(initAllBadrockOpenings, 100); // Small delay to ensure elements are ready
        });
    } else {
        console.log('jQuery not available - waiting for it');
        // Fallback if jQuery loads after DOM
        setTimeout(function() {
            if (window.jQuery) {
                console.log('jQuery now available - initializing');
                initAllBadrockOpenings();
            }
        }, 500);
    }
});

// Elementor editor live preview
if (window.elementorFrontend) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/badrock_opening.default', function($scope) {
        console.log('Elementor element ready - initializing opening widget');
        setTimeout(function() {
            initBadrockOpening($scope);
        }, 100);
    });
} else {
    console.log('Elementor frontend not available - using fallback initialization');
}

// Additional support for dynamic content loading
if (window.jQuery) {
    window.jQuery(document).on('elementor/popup/show', function() {
        console.log('Elementor popup shown - reinitializing openings');
        setTimeout(initAllBadrockOpenings, 100);
    });
}

// Fallback initialization after a delay
setTimeout(function() {
    if (window.jQuery && window.jQuery('.badrock-opening-overlay').length > 0) {
        console.log('Fallback initialization triggered');
        initAllBadrockOpenings();
    }
}, 1000);