// Start Menu Toggle Functionality
(function () {
  "use strict";

  class MenuManager {
    constructor() {
      this.menu = document.querySelector(".menu");
      this.menuToggle = document.querySelector(".menu-icon");
      this.closeBtn = document.querySelector(".menu .close-btn");

      if (!this.menu || !this.menuToggle || !this.closeBtn) {
        console.warn("Menu elements not found");
        return;
      }

      this.init();
    }

    init() {
      // Ensure menu is hidden on initialization
      if (!this.menu.classList.contains("menu-hidden")) {
        this.menu.classList.add("menu-hidden");
      }

      // Open menu on toggle click
      this.menuToggle.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.openMenu();
      });

      // Close menu on close button click
      this.closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.closeMenu();
      });

      // Close menu on Escape key
      document.addEventListener("keydown", (e) => {
        if (
          e.key === "Escape" &&
          !this.menu.classList.contains("menu-hidden")
        ) {
          this.closeMenu();
        }
      });

      // Prevent body scroll when menu is open
      this.handleBodyScroll();
    }

    openMenu() {
      this.menu.classList.remove("menu-hidden");
      this.menu.classList.add("menu-active");
      document.body.style.overflow = "hidden";
    }

    closeMenu() {
      this.menu.classList.remove("menu-active");
      this.menu.classList.add("menu-hidden");
      document.body.style.overflow = "";
    }

    handleBodyScroll() {
      // Ensure body scroll is managed when menu opens/closes
      const observer = new MutationObserver(() => {
        if (this.menu.classList.contains("menu-active")) {
          document.body.style.overflow = "hidden";
        } else {
          document.body.style.overflow = "";
        }
      });

      observer.observe(this.menu, {
        attributes: true,
        attributeFilter: ["class"],
      });
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      new MenuManager();
    });
  } else {
    new MenuManager();
  }
})();
// End Menu Toggle Functionality

// Load Scroll Animations
(function() {
  'use strict';
  
  // Load animations.js dynamically
  const script = document.createElement('script');
  script.src = 'assets/js/main/animations.js?v=1.0.0';
  script.async = true;
  document.head.appendChild(script);
})();

