/**
 * Why Choose Us Vertical Slider
 * Implements a vertical slider with twisted progress bar
 */

(function () {
    'use strict';

    const MAX_VISIBLE_INDICATORS = 4;
    const MOBILE_BREAKPOINT = 1024;
    const INIT_DELAY = 100;
    const WHEEL_DEBOUNCE = 300; // Delay between wheel events in ms

    class WhyChooseSlider {
        constructor() {
            this.slider = document.querySelector('.why-choose-slider');
            if (!this.slider) return;

            this.slides = this.slider.querySelectorAll('.slide');
            this.prevBtn = this.slider.querySelector('.slider-btn.prev');
            this.nextBtn = this.slider.querySelector('.slider-btn.next');
            this.currentSlideEl = this.slider.querySelector('.current-slide');
            this.totalSlidesEl = this.slider.querySelector('.total-slides');
            this.progressFill = this.slider.querySelector('.progress-fill');
            this.progressTrack = this.slider.querySelector('.progress-track');
            this.indicators = this.slider.querySelectorAll('.progress-indicator');

            this.currentIndex = 0;
            this.totalSlides = this.slides.length;
            this.lastWheelTime = 0;
            this.isHovered = false;

            if (this.totalSlides === 0) return;

            this.init();
        }

        init() {
            this.setupEventListeners();
            this.setupResizeHandler();
            this.initializeSlider();
        }

        setupEventListeners() {
            // Navigation buttons
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.goToSlide(this.currentIndex - 1));
            }
            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.goToSlide(this.currentIndex + 1));
            }

            // Indicator clicks
            this.indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => this.goToSlide(index));
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => this.handleKeyboard(e));

            // Mouse wheel navigation
            this.setupWheelNavigation();
        }

        handleKeyboard(e) {
            // Check if slider is visible and hovered or focused
            const rect = this.slider.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (!isVisible || (!this.isHovered && !this.slider.contains(document.activeElement))) {
                return;
            }

            // Ignore if user is typing in an input field
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) {
                return;
            }

            if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.goToSlide(this.currentIndex - 1);
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.goToSlide(this.currentIndex + 1);
            }
        }

        handleWheel = (e) => {
            // Check if slider is visible and hovered
            const rect = this.slider.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (!isVisible || !this.isHovered) {
                return;
            }

            // Prevent default is already handled by handleWheelCapture
            // But we keep it here as a safety measure
            e.preventDefault();

            // Debounce wheel events to prevent rapid scrolling
            const now = Date.now();
            if (now - this.lastWheelTime < WHEEL_DEBOUNCE) {
                return;
            }

            // Only handle vertical scrolling (deltaY)
            if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
                // Scroll up (negative deltaY) = go to previous slide
                // Scroll down (positive deltaY) = go to next slide
                if (e.deltaY < 0) {
                    this.goToSlide(this.currentIndex - 1);
                } else if (e.deltaY > 0) {
                    this.goToSlide(this.currentIndex + 1);
                }
                
                this.lastWheelTime = now;
            }
        }

        handleWheelCapture = (e) => {
            // Prevent page scroll when hovering over slider (capture phase)
            // This runs before other handlers to block page scrolling
            if (!this.isHovered) return;
            
            const rect = this.slider.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (!isVisible) return;
            
            // If hovering over slider and it's visible, prevent page scroll for vertical scrolling
            if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
                e.preventDefault();
            }
        }

        setupWheelNavigation() {
            // Track hover state
            this.slider.addEventListener('mouseenter', () => {
                this.isHovered = true;
            });
            this.slider.addEventListener('mouseleave', () => {
                this.isHovered = false;
            });

            // Wheel event listener on slider - prevent page scroll
            this.slider.addEventListener('wheel', this.handleWheel, { passive: false });
            
            // Also listen on document with capture to catch all wheel events
            document.addEventListener('wheel', this.handleWheelCapture, { passive: false, capture: true });
            
            // Prevent touch scroll on mobile/tablet when hovering
            let touchStartY = 0;
            this.slider.addEventListener('touchstart', (e) => {
                if (this.isHovered) {
                    touchStartY = e.touches[0].clientY;
                }
            }, { passive: true });
            
            this.slider.addEventListener('touchmove', (e) => {
                if (this.isHovered) {
                    const touchY = e.touches[0].clientY;
                    const deltaY = touchStartY - touchY;
                    
                    if (Math.abs(deltaY) > 50) {
                        e.preventDefault();
                        if (deltaY > 0) {
                            this.goToSlide(this.currentIndex + 1);
                        } else {
                            this.goToSlide(this.currentIndex - 1);
                        }
                        touchStartY = touchY;
                    }
                }
            }, { passive: false });
        }

        setupResizeHandler() {
            const resizeHandler = () => {
                this.updatePathForLayout();
                this.initializeProgressBar();
                this.updateIndicators();
                this.updatePreview();
            };

            this.resizeHandler = this.debounce(resizeHandler, 250);
            window.addEventListener('resize', this.resizeHandler);
        }

        initializeSlider() {
            if (this.totalSlidesEl) {
                this.totalSlidesEl.textContent = String(this.totalSlides).padStart(2, '0');
            }

            // Wait for SVG to render
            setTimeout(() => {
                this.initializeProgressBar();
                this.updateIndicators();
                this.updateSlide(0);
            }, INIT_DELAY);
        }

        initializeProgressBar() {
            if (!this.progressFill || !this.progressTrack) return;

            this.updatePathForLayout();
            const pathLength = this.progressFill.getTotalLength();
            this.progressFill.style.strokeDasharray = pathLength;
            this.progressFill.style.strokeDashoffset = pathLength;
        }

        updatePathForLayout() {
            if (!this.progressTrack || !this.progressFill) return;

            const svg = this.progressTrack.closest('svg');
            if (!svg) return;

            const isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
            const paths = {
                horizontal: "M0,2 C20,2 40,0 60,2 C80,4 90,2 100,2",
                vertical: "M50,0 C30,40 20,80 50,120 C80,160 70,200 50,240 C30,280 20,320 50,360 C80,400 70,440 50,480 C30,520 20,560 50,600"
            };

            if (isMobile) {
                svg.setAttribute('viewBox', '0 0 100 4');
                this.setPath(paths.horizontal);
            } else {
                svg.setAttribute('viewBox', '0 0 100 600');
                this.setPath(paths.vertical);
            }
        }

        setPath(pathData) {
            this.progressTrack.setAttribute('d', pathData);
            this.progressFill.setAttribute('d', pathData);
        }

        getVisibleIndicatorRange() {
            const maxVisible = MAX_VISIBLE_INDICATORS;
            const total = this.totalSlides;

            if (total <= maxVisible) {
                return { start: 0, end: total - 1 };
            }

            if (this.currentIndex <= 1) {
                return { start: 0, end: maxVisible - 1 };
            }

            if (this.currentIndex >= total - 2) {
                return { start: total - maxVisible, end: total - 1 };
            }

            // Middle range: center current indicator
            let start = Math.max(0, this.currentIndex - 1);
            let end = Math.min(total - 1, start + maxVisible - 1);

            if (end - start < maxVisible - 1) {
                start = end - maxVisible + 1;
            }

            return { start, end };
        }

        updateIndicators() {
            this.updateVisibleIndicators();
            this.updateIndicatorPositions();
        }

        updateVisibleIndicators() {
            const { start, end } = this.getVisibleIndicatorRange();

            this.indicators.forEach((indicator, index) => {
                const isVisible = index >= start && index <= end;
                indicator.classList.toggle('hidden', !isVisible);
                indicator.classList.toggle('active', isVisible && index === this.currentIndex);
            });
        }

        updateIndicatorPositions() {
            if (!this.progressTrack) return;

            const isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
            const path = this.progressTrack;
            const pathLength = path.getTotalLength();
            const { start, end } = this.getVisibleIndicatorRange();
            const visibleCount = end - start + 1;

            for (let i = start; i <= end; i++) {
                const indicator = this.indicators[i];
                if (!indicator) continue;

                const position = visibleCount > 1 ? (i - start) / (visibleCount - 1) : 0.5;
                const point = path.getPointAtLength(position * pathLength);

                const xPercent = (point.x / 100) * 100;
                const yPercent = isMobile ? 50 : (point.y / 600) * 100;

                indicator.style.setProperty('--indicator-x', `${xPercent}%`);
                indicator.style.setProperty('--indicator-y', `${yPercent}%`);
            }
        }

        goToSlide(index) {
            if (index < 0 || index >= this.totalSlides || index === this.currentIndex) {
                return;
            }

            const direction = index > this.currentIndex ? 'next' : 'prev';
            this.updateSlide(index, direction);
        }

        updateSlide(index, direction = 'next') {
            // Update current index
            this.currentIndex = index;

            // Remove all classes from all slides first
            this.slides.forEach(slide => {
                slide.classList.remove('active', 'prev', 'next', 'next-preview');
                // Clear any inline styles
                slide.style.visibility = '';
                slide.style.opacity = '';
                slide.style.pointerEvents = '';
                slide.style.zIndex = '';
                slide.style.transform = '';
            });

            // Reset indicators
            this.indicators.forEach(indicator => indicator.classList.remove('active'));

            // Set active slide - CSS will handle visibility
            const currentSlide = this.slides[this.currentIndex];
            if (currentSlide) {
                currentSlide.classList.add('active');
            }

            // Update all UI elements
            this.updateIndicators();
            this.updatePreview();
            this.updateCounter();
            this.updateButtons();
            this.updateProgress();
        }

        updatePreview() {
            const isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
            const nextIndex = this.currentIndex + 1;

            this.slides.forEach((slide, index) => {
                slide.classList.remove('prev', 'next-preview');
                
                // Clear inline styles to let CSS handle everything
                slide.style.visibility = '';
                slide.style.opacity = '';
                slide.style.pointerEvents = '';
                slide.style.zIndex = '';
                slide.style.transform = '';
                
                // Show next slide preview only on desktop
                if (!isMobile && index === nextIndex && nextIndex < this.totalSlides && index !== this.currentIndex) {
                    slide.classList.add('next-preview');
                }
            });
        }

        updateCounter() {
            if (this.currentSlideEl) {
                this.currentSlideEl.textContent = String(this.currentIndex + 1).padStart(2, '0');
            }
        }

        updateButtons() {
            if (this.prevBtn) {
                this.prevBtn.disabled = this.currentIndex === 0;
            }
            if (this.nextBtn) {
                this.nextBtn.disabled = this.currentIndex === this.totalSlides - 1;
            }
        }

        updateProgress() {
            if (!this.progressFill) return;

            const progress = (this.currentIndex / (this.totalSlides - 1)) * 100;
            const pathLength = this.progressFill.getTotalLength();
            const offset = pathLength * (1 - progress / 100);

            this.progressFill.style.strokeDasharray = pathLength;
            this.progressFill.style.strokeDashoffset = offset;
        }

        debounce(func, wait) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    }

    // Initialize when DOM is ready
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => new WhyChooseSlider());
        } else {
            new WhyChooseSlider();
        }
    }

    init();
})();
