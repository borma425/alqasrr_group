/**
 * Projects Carousel
 * Extends BaseCarousel for Projects section
 */

(function () {
  'use strict';

  const CONFIG = {
    CARD_GAP: 23,
    SCROLL_DEBOUNCE: 50,
    RESIZE_DEBOUNCE: 250,
    SCROLL_ANIMATION_DELAY: 300,
    DRAG_SPEED: 1.0,
    DEFAULT_CARD_WIDTH: 350,
  };

  class ProjectsCarousel extends window.BaseCarousel {
    constructor() {
      super(CONFIG);
    }

    getElements() {
      return {
        carousel: document.querySelector('.projects-carousel'),
        prevBtn: document.querySelector('.carousel-btn.prev'),
        nextBtn: document.querySelector('.carousel-btn.next'),
        progressBar: document.querySelector('.carousel-progress-bar'),
        progressFill: document.querySelector('.carousel-progress-fill'),
        cards: document.querySelectorAll('.project-card'),
      };
    }

    validateElements() {
      const { carousel, prevBtn, nextBtn, cards } = this.elements;
      if (!carousel || !prevBtn || !nextBtn) {
        console.warn('Projects carousel elements not found');
        return false;
      }
      if (cards.length === 0) {
        console.warn('No project cards found');
        return false;
      }
      return true;
    }
  }

  // Initialize
  function init() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => new ProjectsCarousel());
    } else {
      new ProjectsCarousel();
    }
  }

  init();
})();

