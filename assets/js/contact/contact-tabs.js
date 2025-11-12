/**
 * Contact Page Tabs Navigation
 * Manages switching between contact form sections
 */

(function () {
    'use strict';

    class ContactTabs {
        constructor() {
            this.navLinks = document.querySelectorAll('.contact-nav .nav-link');
            this.sections = {
                form: document.getElementById('form'),
                social: document.getElementById('social'),
                map: document.getElementById('map')
            };
            this.navIndicator = document.querySelector('.nav-indicator');
            this.activeSection = 'form';

            if (this.navLinks.length === 0) return;

            this.init();
        }

        init() {
            // Set initial active section
            this.showSection(this.activeSection);
            this.updateIndicator();

            // Add event listeners
            this.navLinks.forEach(link => {
                link.addEventListener('click', this.handleNavClick.bind(this));
            });

            // Handle hash in URL on page load
            this.handleHashChange();
            window.addEventListener('hashchange', this.handleHashChange.bind(this));
            
            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    this.handleResize();
                }, 250);
            });
        }

        handleNavClick(e) {
            e.preventDefault();
            const target = e.currentTarget.getAttribute('href');
            if (target && target.startsWith('#')) {
                const sectionId = target.substring(1);
                this.setActiveSection(sectionId);
                // Update URL without scrolling
                window.history.pushState(null, '', target);
            }
        }

        handleHashChange() {
            const hash = window.location.hash.substring(1);
            if (hash && this.sections[hash]) {
                this.setActiveSection(hash);
            } else {
                this.setActiveSection('form');
            }
        }

        setActiveSection(sectionId) {
            if (!this.sections[sectionId]) return;

            this.activeSection = sectionId;
            this.showSection(sectionId);
            this.updateActiveNav();
            this.updateIndicator();
        }

        showSection(sectionId) {
            // Hide all sections
            Object.values(this.sections).forEach(section => {
                if (section) {
                    section.style.display = 'none';
                    section.classList.remove('active');
                }
            });

            // Show selected section
            if (this.sections[sectionId]) {
                this.sections[sectionId].style.display = 'flex';
                this.sections[sectionId].classList.add('active');
            }
        }

        updateActiveNav() {
            this.navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href === `#${this.activeSection}`) {
                    link.classList.add('active');
                }
            });
        }

        updateIndicator() {
            if (!this.navIndicator) return;

            const activeLink = Array.from(this.navLinks).find(link => 
                link.classList.contains('active')
            );

            if (activeLink) {
                const navContainer = activeLink.closest('.contact-nav');
                if (navContainer) {
                    const navTop = navContainer.getBoundingClientRect().top;
                    const linkTop = activeLink.getBoundingClientRect().top;
                    const linkHeight = activeLink.offsetHeight;
                    
                    // Calculate indicator position relative to nav container
                    const indicatorTop = (linkTop - navTop) + (linkHeight / 2) - 15;
                    this.navIndicator.style.top = `${indicatorTop}px`;
                }
            }
        }

        handleResize() {
            // Recalculate indicator position on window resize
            this.updateIndicator();
        }
    }

    // Initialize when DOM is ready
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => new ContactTabs());
        } else {
            new ContactTabs();
        }
    }

    init();
})();

