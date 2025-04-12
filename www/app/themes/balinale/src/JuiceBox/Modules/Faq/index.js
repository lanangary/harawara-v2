class Faq {
    constructor() {
        this.modules = document.querySelectorAll('.module.faq');
    }

    init() {
        if (!this.modules.length) {
            return;
        }

        this.faqfunction();
    }

    faqfunction() {
        this.modules.forEach(module => {
            const items = module.querySelectorAll('.faq-content-wrap-item');

            items.forEach(item => {
                const title = item.querySelector('.faq-content-wrap-item-title');
                const content = item.querySelector('.faq-content-wrap-item-content');

                // Initially hide the content
                content.style.height = '0';
                content.style.overflow = 'hidden';
                content.style.transition = 'height 0.3s ease';

                title.addEventListener('click', () => {
                    const isOpen = item.classList.contains('open');

                    // Close all other items
                    items.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('open');
                            const otherContent = otherItem.querySelector(
                                '.faq-content-wrap-item-content'
                            );
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
}

const Faqinit = new Faq();
Faqinit.init();
