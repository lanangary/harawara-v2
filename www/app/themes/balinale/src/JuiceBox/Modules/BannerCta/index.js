class BannerCta {
    constructor() {
        this.modules = document.querySelectorAll('.module.banner-cta');
    }

    init() {
        if (!this.modules.length) {
            return;
        }

        this.countdown();
    }

    countdown() {
        this.modules.forEach(module => {
            const timerElement = module.querySelector('.banner-cta-timer');
            if (!timerElement) return;

            const targetTime = timerElement.getAttribute('data-timer');
            // Parse the date manually if the format is not ISO 8601
            const [day, month, year, time, period] = targetTime
                .match(/(\d{2})\/(\d{2})\/(\d{4}) (\d{1,2}:\d{2}) (am|pm)/i)
                .slice(1);
            const [hours, minutes] = time.split(':');
            const targetDate = new Date(
                `${year}-${month}-${day}T${
                    period.toLowerCase() === 'pm' && hours !== '12' ? +hours + 12 : hours
                }:${minutes}:00`
            );

            const updateTimer = () => {
                const now = new Date();
                const timeDiff = targetDate - now;

                if (timeDiff <= 0) {
                    timerElement.innerHTML = '<div class="expired">Expired</div>';
                    return;
                }

                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor(
                    (timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                );
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

                timerElement.querySelector(
                    '[data-set="days"] .banner-cta-timer-set-number-value:nth-child(1)'
                ).textContent = Math.floor(days / 10);
                timerElement.querySelector(
                    '[data-set="days"] .banner-cta-timer-set-number-value:nth-child(2)'
                ).textContent = days % 10;

                timerElement.querySelector(
                    '[data-set="hours"] .banner-cta-timer-set-number-value:nth-child(1)'
                ).textContent = Math.floor(hours / 10);
                timerElement.querySelector(
                    '[data-set="hours"] .banner-cta-timer-set-number-value:nth-child(2)'
                ).textContent = hours % 10;

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
}

const BannerCtainit = new BannerCta();
BannerCtainit.init();
