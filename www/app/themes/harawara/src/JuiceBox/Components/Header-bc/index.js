import mq from 'enquire.js';
import { Breakpoints } from 'JBSrc/constants';

jQuery($ => {
    const $burger = $('.hamburger');
    const $navContainer = $('.header__nav');
    const $toggle = $('.nav__toggle');

    $burger.on('click', () => {
        document.body.classList.toggle('nav-active');
    });

    $toggle.on('click', event => {
        const $this = $(event.currentTarget);

        $this
            .parent()
            .toggleClass('nav__li--submenu-open')
            .end()
            .next('.nav__submenu')
            .slideToggle();
    });

    mq.register(`screen and (max-width: ${Breakpoints.sm}px)`, {
        match: () => {
            $navContainer.detach().insertAfter('.page-wrap');
        },
        unmatch: () => {
            $navContainer.detach().insertAfter('.header__logo-wrapper');
        }
    });

    /**
     * JuiceBox Keyboard accessibility smooth scroll
     * each anchor tag in template.twig should have a href attribute
     * to tell this code where to skip to
     */
    const $keyboardNavs = $('.keyboard-nav a');

    $keyboardNavs.on('click', function(e) {
        e.preventDefault();
        const hash = $(e.target).attr('href');
        const $speed = $(e.target).data('speed') ? $(e.target).data('speed') : 800;

        window.scrollAnchorTo(hash, $speed);
    });
});
