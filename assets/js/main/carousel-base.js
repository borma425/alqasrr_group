/**
 * Base Carousel Class
 * Shared functionality for all carousels
 */

(function () {
  'use strict';

  class BaseCarousel {
    constructor(config) {
      this.config = config;
      this.elements = this.getElements();
      if (!this.validateElements()) return;

      this.state = {
        currentIndex: 0,
        isDragging: false,
        isDown: false,
        startX: 0,
        scrollLeftStart: 0,
        isHovered: false,
      };

      this.isRTL = window.Utils?.isRTL
        ? window.Utils.isRTL(this.elements.carousel)
        : this.detectRTL();
      this.init();
    }

    // Abstract methods - must be implemented by child classes
    getElements() {
      throw new Error('getElements() must be implemented by child class');
    }

    validateElements() {
      throw new Error('validateElements() must be implemented by child class');
    }

    setupCarousel() {
      // Override in child class if needed
    }

    // RTL Detection fallback
    detectRTL() {
      const element = this.elements.carousel;
      return (
        window.getComputedStyle(element).direction === 'rtl' ||
        document.documentElement.dir === 'rtl' ||
        element.closest('[dir="rtl"]') !== null
      );
    }

    // Calculations
    getCardsPerView() {
      const { cards, carousel } = this.elements;
      if (cards.length === 0) return 1;

      const cardWidth = cards[0].offsetWidth || this.config.DEFAULT_CARD_WIDTH || 308;
      const containerWidth = carousel.offsetWidth;
      const cardsPerView = Math.floor(
        (containerWidth + this.config.CARD_GAP) / (cardWidth + this.config.CARD_GAP)
      );
      return Math.max(1, cardsPerView);
    }

    getMaxIndex() {
      const cardsPerView = this.getCardsPerView();
      return Math.max(0, this.elements.cards.length - cardsPerView);
    }

    findMostVisibleCard() {
      const { carousel, cards } = this.elements;
      const carouselRect = carousel.getBoundingClientRect();
      let bestIndex = 0;
      let bestVisibility = 0;

      cards.forEach((card, index) => {
        const cardRect = card.getBoundingClientRect();
        const overlap = Math.max(
          0,
          Math.min(cardRect.right, carouselRect.right) -
            Math.max(cardRect.left, carouselRect.left)
        );
        const visibility = overlap / cardRect.width;

        if (visibility > bestVisibility) {
          bestVisibility = visibility;
          bestIndex = index;
        }
      });

      return bestIndex;
    }

    // Actions
    scrollToIndex(index) {
      const { cards } = this.elements;
      const maxIndex = this.getMaxIndex();
      const clamp = window.Utils?.clamp || this.clamp;
      const targetIndex = clamp(index, 0, maxIndex);

      if (!cards[targetIndex]) return;

      this.state.currentIndex = targetIndex;
      cards[targetIndex].scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'start',
      });

      const debounce = window.Utils?.debounce || this.debounce;
      setTimeout(() => this.update(), this.config.SCROLL_ANIMATION_DELAY);
    }

    update() {
      this.updateProgress();
      this.updateButtons();
    }

    updateProgress() {
      const { progressFill } = this.elements;
      if (!progressFill) return;

      const maxIndex = this.getMaxIndex();
      if (maxIndex === 0) {
        progressFill.style.width = '100%';
        return;
      }

      const progress = (this.state.currentIndex / maxIndex) * 100;
      progressFill.style.width = `${progress}%`;
    }

    updateButtons() {
      const { prevBtn, nextBtn } = this.elements;
      const maxIndex = this.getMaxIndex();

      if (prevBtn) {
        prevBtn.classList.toggle('disabled', this.state.currentIndex === 0);
      }
      if (nextBtn) {
        nextBtn.classList.toggle(
          'disabled',
          this.state.currentIndex >= maxIndex
        );
      }
    }

    // Event Handlers
    handlePrevClick = (e) => {
      e.preventDefault();
      if (this.state.currentIndex > 0) {
        this.scrollToIndex(this.state.currentIndex - 1);
      }
    };

    handleNextClick = (e) => {
      e.preventDefault();
      const maxIndex = this.getMaxIndex();
      if (this.state.currentIndex < maxIndex) {
        this.scrollToIndex(this.state.currentIndex + 1);
      }
    };

    handleProgressClick = (e) => {
      const { progressBar } = this.elements;
      if (!progressBar) return;

      const rect = progressBar.getBoundingClientRect();
      const clamp = window.Utils?.clamp || this.clamp;
      let percentage = clamp((e.clientX - rect.left) / rect.width, 0, 1);

      if (this.isRTL) percentage = 1 - percentage;

      const maxIndex = this.getMaxIndex();
      const targetIndex = Math.round(percentage * maxIndex);
      this.scrollToIndex(targetIndex);
    };

    handleWheel = (e) => {
      if (Math.abs(e.deltaY) <= Math.abs(e.deltaX)) return;
      e.preventDefault();

      const { carousel } = this.elements;
      const scrollAmount = this.isRTL ? -e.deltaY : e.deltaY;
      carousel.scrollLeft += scrollAmount;
    };

    handleDragStart = (e) => {
      if (this.shouldIgnoreDrag(e)) return;

      const { carousel } = this.elements;
      this.state.isDown = true;
      this.state.startX = e.pageX;
      this.state.scrollLeftStart = carousel.scrollLeft;

      carousel.style.cursor = 'grabbing';
      carousel.style.userSelect = 'none';
      e.preventDefault();
    };

    handleDragMove = (e) => {
      if (!this.state.isDown) return;
      e.preventDefault();

      const { carousel } = this.elements;
      const walk = (e.pageX - this.state.startX) * this.config.DRAG_SPEED;
      carousel.scrollLeft = this.state.scrollLeftStart - walk;
    };

    handleDragEnd = () => {
      if (!this.state.isDown) return;

      const { carousel } = this.elements;
      this.state.isDown = false;
      carousel.style.cursor = 'grab';
      carousel.style.userSelect = '';
    };

    handleScroll = () => {
      const debounce = window.Utils?.debounce || this.debounce;
      const debouncedHandler = debounce(() => {
        const bestIndex = this.findMostVisibleCard();
        if (bestIndex !== this.state.currentIndex && bestIndex >= 0) {
          this.state.currentIndex = bestIndex;
          this.update();
        }
      }, this.config.SCROLL_DEBOUNCE);
      debouncedHandler();
    };

    handleResize = () => {
      const debounce = window.Utils?.debounce || this.debounce;
      const debouncedHandler = debounce(() => {
        this.update();
      }, this.config.RESIZE_DEBOUNCE);
      debouncedHandler();
    };

    handleKeyboard = (e) => {
      const { carousel } = this.elements;
      
      // Check if carousel is visible and in viewport
      const rect = carousel.getBoundingClientRect();
      const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
      
      // Only handle keyboard if carousel is visible and (hovered or focused)
      if (!isVisible || (!this.state.isHovered && !carousel.contains(document.activeElement))) {
        return;
      }

      // Ignore if user is typing in an input field
      if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) {
        return;
      }

      // Handle arrow keys
      if (e.key === 'ArrowLeft') {
        e.preventDefault();
        // In RTL, left arrow should go to next, in LTR it should go to prev
        if (this.isRTL) {
          this.handleNextClick(e);
        } else {
          this.handlePrevClick(e);
        }
      } else if (e.key === 'ArrowRight') {
        e.preventDefault();
        // In RTL, right arrow should go to prev, in LTR it should go to next
        if (this.isRTL) {
          this.handlePrevClick(e);
        } else {
          this.handleNextClick(e);
        }
      }
    };

    // Helpers
    shouldIgnoreDrag(e) {
      return (
        e.target.closest('.carousel-btn') ||
        e.target.closest('.carousel-progress') ||
        e.target.closest('.news-progress') ||
        e.target.closest('a') ||
        e.target.closest('button')
      );
    }

    // Event Listeners
    attachEventListeners() {
      const { carousel, prevBtn, nextBtn, progressBar } = this.elements;

      // Navigation buttons
      if (prevBtn) prevBtn.addEventListener('click', this.handlePrevClick);
      if (nextBtn) nextBtn.addEventListener('click', this.handleNextClick);

      // Progress bar
      if (progressBar) {
        progressBar.addEventListener('mousedown', (e) => {
          this.state.isDragging = true;
          this.handleProgressClick(e);
          e.preventDefault();
        });

        document.addEventListener('mousemove', (e) => {
          if (this.state.isDragging) this.handleProgressClick(e);
        });

        document.addEventListener('mouseup', () => {
          this.state.isDragging = false;
        });

        // Touch support
        progressBar.addEventListener('touchstart', (e) => {
          this.state.isDragging = true;
          this.handleProgressClick(e.touches[0]);
          e.preventDefault();
        });

        document.addEventListener('touchmove', (e) => {
          if (this.state.isDragging) {
            this.handleProgressClick(e.touches[0]);
            e.preventDefault();
          }
        });

        document.addEventListener('touchend', () => {
          this.state.isDragging = false;
        }, { passive: true });
      }

      // Wheel scrolling
      carousel.addEventListener('wheel', this.handleWheel, { passive: false });

      // Drag scrolling
      carousel.addEventListener('mousedown', this.handleDragStart);
      carousel.addEventListener('mouseleave', this.handleDragEnd);
      document.addEventListener('mousemove', this.handleDragMove);
      document.addEventListener('mouseup', this.handleDragEnd);

      // Track hover state for keyboard navigation
      carousel.addEventListener('mouseenter', () => {
        this.state.isHovered = true;
      }, { passive: true });
      carousel.addEventListener('mouseleave', () => {
        this.state.isHovered = false;
      }, { passive: true });

      // Scroll tracking
      carousel.addEventListener('scroll', this.handleScroll, { passive: true });

      // Keyboard navigation
      document.addEventListener('keydown', this.handleKeyboard);

      // Resize
      window.addEventListener('resize', this.handleResize, { passive: true });
    }

    init() {
      this.setupCarousel();
      this.attachEventListeners();
      this.update();
    }

    // Fallback utility methods (if Utils not loaded)
    debounce(func, wait) {
      let timeout;
      return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
      };
    }

    clamp(value, min, max) {
      return Math.max(min, Math.min(max, value));
    }
  }

  // Export to global scope
  window.BaseCarousel = BaseCarousel;
})();

