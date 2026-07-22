(() => {
    const body = document.body;
    const toggle = document.querySelector('.mobile-menu-toggle');
    const menu = document.getElementById('mobileMenu');
    const backdrop = document.querySelector('[data-mobile-backdrop]');
    const closeBtn = document.querySelector('[data-mobile-close]') || null;
    const accordionBtn = document.querySelector('[data-mobile-accordion]');
    const accordionPanel = document.querySelector('[data-mobile-panel]');
    const accordionLinks = Array.from(accordionPanel.querySelectorAll('a'));
    const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';
    let lastFocusedElement = null;

    if (!toggle || !menu || !backdrop || !accordionBtn || !accordionPanel) {
        return;
    }

    const setAccordionLinksEnabled = (enabled) => {
        accordionLinks.forEach((link) => {
            if (enabled) {
                link.removeAttribute('tabindex');
            } else {
                link.setAttribute('tabindex', '-1');
            }
        });
    };

    const setMenuOpen = (open, restoreFocus = true) => {
        body.classList.toggle('mobile-menu-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        menu.setAttribute('aria-hidden', String(!open));
        backdrop.setAttribute('aria-hidden', String(!open));
        menu.inert = !open;
        backdrop.inert = !open;

        if (open) {
            lastFocusedElement = document.activeElement;
            const firstFocusable = menu.querySelector(focusableSelector);
            window.setTimeout(() => (firstFocusable || closeBtn).focus(), 0);
            return;
        }

        accordionBtn.setAttribute('aria-expanded', 'false');
        accordionPanel.classList.remove('is-open');
        accordionPanel.inert = true;
        setAccordionLinksEnabled(false);

        if (restoreFocus) {
            window.setTimeout(() => toggle.focus(), 0);
        } else if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement = null;
        }
    };

    const toggleMenu = () => setMenuOpen(!body.classList.contains('mobile-menu-open'));

    const toggleAccordion = () => {
        const isOpen = accordionBtn.getAttribute('aria-expanded') === 'true';
        accordionBtn.setAttribute('aria-expanded', String(!isOpen));
        accordionPanel.classList.toggle('is-open', !isOpen);
        accordionPanel.inert = isOpen;
        setAccordionLinksEnabled(!isOpen);
    };

    const handleKeydown = (event) => {
        if (!body.classList.contains('mobile-menu-open')) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            setMenuOpen(false);
            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        const focusables = Array.from(menu.querySelectorAll(focusableSelector))
            .filter((element) => element.offsetParent !== null);

        if (focusables.length === 0) {
            event.preventDefault();
            return;
        }

        const first = focusables[0];
        const last = focusables[focusables.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    };

    toggle.addEventListener('click', toggleMenu);
    if (closeBtn && closeBtn !== toggle) {
        closeBtn.addEventListener('click', () => setMenuOpen(false));
    }
    accordionBtn.addEventListener('click', toggleAccordion);
    menu.addEventListener('keydown', handleKeydown);

    menu.querySelectorAll('[data-mobile-link]').forEach((link) => {
        link.addEventListener('click', () => setMenuOpen(false));
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992 && body.classList.contains('mobile-menu-open')) {
            setMenuOpen(false, false);
        }
    });

    accordionPanel.inert = true;
    setAccordionLinksEnabled(false);
    menu.inert = true;
    backdrop.inert = true;
})();
