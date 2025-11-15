/**
 * Related Articles Carousel
 * Extends BaseCarousel for Related Articles section in single blog page
 */

(function () {
  'use strict';

  const CONFIG = {
    CARD_GAP: 20,
    SCROLL_DEBOUNCE: 50,
    RESIZE_DEBOUNCE: 250,
    SCROLL_ANIMATION_DELAY: 300,
    DRAG_SPEED: 1.0,
    DEFAULT_CARD_WIDTH: 400,
  };

  class RelatedArticlesCarousel extends window.BaseCarousel {
    constructor() {
      super(CONFIG);
    }

    getElements() {
      return {
        carousel: document.querySelector('.related-articles-grid'),
        prevBtn: document.querySelector('.related-controls .carousel-btn.prev'),
        nextBtn: document.querySelector('.related-controls .carousel-btn.next'),
        progressBar: document.querySelector('.related-progress-bar'),
        progressFill: document.querySelector('.related-progress-fill'),
        cards: document.querySelectorAll('.related-article-card'),
      };
    }

    validateElements() {
      const { carousel, prevBtn, nextBtn, cards } = this.elements;
      if (!carousel || !prevBtn || !nextBtn) {
        console.warn('Related articles carousel elements not found');
        return false;
      }
      if (cards.length === 0) {
        console.warn('No related article cards found');
        return false;
      }
      return true;
    }

    setupCarousel() {
      const { carousel } = this.elements;
      // Add carousel class to activate CSS
      carousel.classList.add('related-carousel-active');
    }
  }

  // Initialize
  function init() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => new RelatedArticlesCarousel());
    } else {
      new RelatedArticlesCarousel();
    }
  }

  init();
})();

