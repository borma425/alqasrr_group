/**
 * Tour Carousel
 * Simple image carousel for tour section
 */

(function () {
    'use strict';

    class TourCarousel {
        constructor() {
            this.carousel = document.getElementById('tour-carousel');
            if (!this.carousel) return;

            this.images = this.carousel.querySelectorAll('.carousel-image');
            this.prevBtn = document.getElementById('carousel-prev');
            this.nextBtn = document.getElementById('carousel-next');
            this.prevBtnFooter = document.getElementById('carousel-prev-footer');
            this.nextBtnFooter = document.getElementById('carousel-next-footer');
            this.prevThumb = this.carousel.querySelector('.carousel-thumb-prev');
            this.nextThumb = this.carousel.querySelector('.carousel-thumb-next');
            this.currentSpan = document.querySelector('.carousel-counter .current');
            this.totalSpan = document.querySelector('.carousel-counter .total');

            this.currentIndex = 0;
            this.totalImages = this.images.length;
            this.isHovered = false;

            if (this.totalImages === 0) return;

            this.init();
        }

        init() {
            // Set total count
            if (this.totalSpan) {
                this.totalSpan.textContent = `/ ${String(this.totalImages).padStart(2, '0')}`;
            }

            // Attach event listeners
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.goToPrev());
            }

            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.goToNext());
            }

            if (this.prevBtnFooter) {
                this.prevBtnFooter.addEventListener('click', () => this.goToPrev());
            }

            if (this.nextBtnFooter) {
                this.nextBtnFooter.addEventListener('click', () => this.goToNext());
            }

            // Keyboard navigation
            this.setupKeyboardNavigation();

            // Update initial state
            this.updateCarousel();
        }

        goToPrev() {
            this.currentIndex = (this.currentIndex - 1 + this.totalImages) % this.totalImages;
            this.updateCarousel();
        }

        goToNext() {
            this.currentIndex = (this.currentIndex + 1) % this.totalImages;
            this.updateCarousel();
        }

        goToIndex(index) {
            if (index >= 0 && index < this.totalImages) {
                this.currentIndex = index;
                this.updateCarousel();
            }
        }

        handleKeyboard = (e) => {
            // Check if carousel is visible and in viewport
            const rect = this.carousel.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            // Only handle keyboard if carousel is visible and (hovered or focused)
            if (!isVisible || (!this.isHovered && !this.carousel.contains(document.activeElement))) {
                return;
            }

            // Ignore if user is typing in an input field
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) {
                return;
            }

            // Handle arrow keys
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.goToPrev();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.goToNext();
            }
        }

        setupKeyboardNavigation() {
            // Track hover state for keyboard navigation
            this.carousel.addEventListener('mouseenter', () => {
                this.isHovered = true;
            });
            this.carousel.addEventListener('mouseleave', () => {
                this.isHovered = false;
            });

            // Keyboard navigation
            document.addEventListener('keydown', this.handleKeyboard);
        }

        updateCarousel() {
            // Update images
            this.images.forEach((img, index) => {
                if (index === this.currentIndex) {
                    img.classList.add('active');
                } else {
                    img.classList.remove('active');
                }
            });

            // Update counter
            if (this.currentSpan) {
                this.currentSpan.textContent = String(this.currentIndex + 1).padStart(2, '0');
            }

            // Update thumbnails
            const prevIndex = (this.currentIndex - 1 + this.totalImages) % this.totalImages;
            const nextIndex = (this.currentIndex + 1) % this.totalImages;

            if (this.prevThumb && this.images[prevIndex]) {
                this.prevThumb.src = this.images[prevIndex].src;
            }

            if (this.nextThumb && this.images[nextIndex]) {
                this.nextThumb.src = this.images[nextIndex].src;
            }
        }
    }

    // Initialize
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => new TourCarousel());
        } else {
            new TourCarousel();
        }
    }

    init();
})();

