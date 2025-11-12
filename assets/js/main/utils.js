/**
 * Shared Utilities
 * Common helper functions used across the project
 */

(function () {
  'use strict';

  window.Utils = {
    /**
     * Debounce function - delays execution until after wait time
     * @param {Function} func - Function to debounce
     * @param {number} wait - Wait time in milliseconds
     * @returns {Function} Debounced function
     */
    debounce(func, wait) {
      let timeout;
      return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
      };
    },

    /**
     * Check if element is in RTL mode
     * @param {HTMLElement} element - Element to check
     * @returns {boolean} True if RTL
     */
    isRTL(element) {
      return (
        window.getComputedStyle(element).direction === 'rtl' ||
        document.documentElement.dir === 'rtl' ||
        element.closest('[dir="rtl"]') !== null
      );
    },

    /**
     * Clamp value between min and max
     * @param {number} value - Value to clamp
     * @param {number} min - Minimum value
     * @param {number} max - Maximum value
     * @returns {number} Clamped value
     */
    clamp(value, min, max) {
      return Math.max(min, Math.min(max, value));
    },
  };
})();

