/**
 * Scroll Animations
 * Adds smooth scroll-triggered animations to elements
 * No HTML modifications needed - works automatically
 */

(function () {
    'use strict';

    class ScrollAnimations {
        constructor() {
            this.observer = null;
            this.observerOptions = {
                root: null,
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.1
            };
            this.init();
        }

        init() {
            // Load animations CSS dynamically
            this.loadAnimationsCSS();
            
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.setup());
            } else {
                this.setup();
            }
        }

        loadAnimationsCSS() {
            // Check if CSS is already loaded
            if (document.querySelector('link[href*="animations.css"]')) {
                return;
            }

            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'assets/css/main/animations.css?v=1.0.0';
            document.head.appendChild(link);
        }

        setup() {
            // Setup Intersection Observer
            this.setupObserver();
            
            // Auto-detect and animate sections
            this.animateSections();
            
            // Animate cards and lists
            this.animateCards();
            
            // Animate headings
            this.animateHeadings();
            
            // Add hover effects to interactive elements
            this.setupHoverEffects();
        }

        setupObserver() {
            // Check if IntersectionObserver is supported
            if (!('IntersectionObserver' in window)) {
                // Fallback: animate all elements immediately
                this.animateAllElements();
                return;
            }

            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        // Unobserve after animation to improve performance
                        this.observer.unobserve(entry.target);
                    }
                });
            }, this.observerOptions);
        }

        animateSections() {
            // Animate main sections
            const sections = document.querySelectorAll('section');
            sections.forEach((section, index) => {
                // Handle hero section specially (animate content, not section itself)
                if (section.classList.contains('hero-section') || section.classList.contains('hero')) {
                    // Hero section content is animated in animateHeadings
                    return;
                }
                
                // Add animation class based on section type
                if (section.classList.contains('about-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('projects-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('international-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('why-choose-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('news-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('partners-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('cta-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('mission-vision-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('values-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('featured-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('articles-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('jobs-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('contact-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('hero-image-section')) {
                    this.addAnimation(section, 'fade-in', index * 0.1);
                } else if (section.classList.contains('filter-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('featured-project')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('projects-grid')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('article-header')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('article-content')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('related-articles')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('job-details')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('application-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('project-details')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('why-invest-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('tour-section')) {
                    this.addAnimation(section, 'fade-in-up', index * 0.1);
                } else if (section.classList.contains('location-section')) {
                    // Skip animation for location-section as requested
                    return;
                } else {
                    this.addAnimation(section, 'fade-in', index * 0.1);
                }
            });
        }

        animateCards() {
            // Animate project cards
            const projectCards = document.querySelectorAll('.project-card');
            projectCards.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.1);
            });

            // Animate news cards
            const newsCards = document.querySelectorAll('.news-card');
            newsCards.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.1);
            });

            // Animate gallery cards
            const galleryCards = document.querySelectorAll('.gallery-card');
            galleryCards.forEach((card, index) => {
                this.addAnimation(card, 'scale-in', index * 0.1);
            });

            // Animate partner logos
            const partnerLogos = document.querySelectorAll('.partner-logo');
            partnerLogos.forEach((logo, index) => {
                this.addAnimation(logo, 'fade-in', index * 0.05);
            });

            // Animate mission/vision cards (About page)
            const missionCards = document.querySelectorAll('.mission-card');
            missionCards.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.15);
            });

            // Animate value items (About page)
            const valueItems = document.querySelectorAll('.value-item');
            valueItems.forEach((item, index) => {
                this.addAnimation(item, 'fade-in-up', index * 0.1);
            });

            // Animate slides in why-choose-slider (About page)
            const slides = document.querySelectorAll('.why-choose-slider .slide');
            slides.forEach((slide, index) => {
                // Only animate the active slide initially
                if (slide.classList.contains('active')) {
                    this.addAnimation(slide, 'fade-in', 0);
                }
            });

            // Animate featured large article (Blog page)
            const featuredLarge = document.querySelectorAll('.featured-large');
            featuredLarge.forEach((article, index) => {
                this.addAnimation(article, 'fade-in-up', index * 0.1);
            });

            // Animate featured small articles (Blog page)
            const featuredSmall = document.querySelectorAll('.featured-small');
            featuredSmall.forEach((article, index) => {
                this.addAnimation(article, 'fade-in-up', index * 0.15);
            });

            // Animate article cards (Blog page)
            const articleCards = document.querySelectorAll('.article-card');
            articleCards.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.1);
            });

            // Animate pagination (Blog page)
            const pagination = document.querySelectorAll('.pagination');
            pagination.forEach((nav, index) => {
                this.addAnimation(nav, 'fade-in', index * 0.1);
            });

            // Animate job cards (Careers page)
            const jobCards = document.querySelectorAll('.job-card');
            jobCards.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.1);
            });

            // Animate about images (Careers page)
            const aboutImages = document.querySelectorAll('.about-image');
            aboutImages.forEach((img, index) => {
                this.addAnimation(img, 'fade-in-right', index * 0.1);
            });

            // Animate about content (Careers page)
            const aboutContents = document.querySelectorAll('.about-content');
            aboutContents.forEach((content, index) => {
                this.addAnimation(content, 'fade-in-left', index * 0.1);
            });

            // Animate contact form wrapper (Contact page)
            const contactFormWrappers = document.querySelectorAll('.contact-form-wrapper');
            contactFormWrappers.forEach((wrapper, index) => {
                this.addAnimation(wrapper, 'fade-in-up', index * 0.1);
            });

            // Animate social section (Contact page)
            const socialSections = document.querySelectorAll('.social-section');
            socialSections.forEach((section, index) => {
                this.addAnimation(section, 'fade-in-up', index * 0.1);
            });

            // Animate map section (Contact page)
            const mapSections = document.querySelectorAll('.map-section');
            mapSections.forEach((section, index) => {
                this.addAnimation(section, 'fade-in-up', index * 0.1);
            });

            // Animate contact items (Contact page)
            const contactItems = document.querySelectorAll('.contact-item');
            contactItems.forEach((item, index) => {
                this.addAnimation(item, 'fade-in-up', index * 0.1);
            });

            // Animate form groups (Contact page)
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                this.addAnimation(group, 'fade-in-up', index * 0.05);
            });

            // Animate hero image (Contact page)
            const heroImages = document.querySelectorAll('.hero-image-section .hero-image');
            heroImages.forEach((img, index) => {
                this.addAnimation(img, 'fade-in', index * 0.1);
            });

            // Animate featured project card (Projects page)
            const projectCardLarge = document.querySelectorAll('.project-card-large');
            projectCardLarge.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.1);
            });

            // Animate project badges (Projects page)
            const projectBadges = document.querySelectorAll('.project-badge');
            projectBadges.forEach((badge, index) => {
                this.addAnimation(badge, 'fade-in', index * 0.05);
            });

            // Animate hero image (Single blog page)
            const heroImagesSingle = document.querySelectorAll('.hero .hero-image');
            heroImagesSingle.forEach((img, index) => {
                this.addAnimation(img, 'fade-in-right', index * 0.1);
            });

            // Animate related article cards (Single blog page)
            const relatedArticleCards = document.querySelectorAll('.related-article-card');
            relatedArticleCards.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.1);
            });

            // Animate article body content (Single blog page)
            const articleBodies = document.querySelectorAll('.article-body');
            articleBodies.forEach((body, index) => {
                this.addAnimation(body, 'fade-in-up', index * 0.1);
            });

            // Animate article body paragraphs (Single blog page)
            const articleParagraphs = document.querySelectorAll('.article-body p');
            articleParagraphs.forEach((p, index) => {
                this.addAnimation(p, 'fade-in', index * 0.05);
            });

            // Animate article body headings (Single blog page)
            const articleBodyHeadings = document.querySelectorAll('.article-body h2, .article-body h3');
            articleBodyHeadings.forEach((heading, index) => {
                this.addAnimation(heading, 'fade-in-up', index * 0.1);
            });

            // Animate article body lists (Single blog page)
            const articleLists = document.querySelectorAll('.article-body ul, .article-body ol');
            articleLists.forEach((list, index) => {
                this.addAnimation(list, 'fade-in-up', index * 0.1);
            });

            // Animate article body blockquote (Single blog page)
            const articleBlockquotes = document.querySelectorAll('.article-body blockquote');
            articleBlockquotes.forEach((quote, index) => {
                this.addAnimation(quote, 'fade-in-up', index * 0.1);
            });

            // Animate article body tables (Single blog page)
            const articleTables = document.querySelectorAll('.article-body table');
            articleTables.forEach((table, index) => {
                this.addAnimation(table, 'fade-in-up', index * 0.1);
            });

            // Animate article body code blocks (Single blog page)
            const articleCodeBlocks = document.querySelectorAll('.article-body pre');
            articleCodeBlocks.forEach((code, index) => {
                this.addAnimation(code, 'fade-in-up', index * 0.1);
            });

            // Animate hero header (Single careers page)
            const heroHeaders = document.querySelectorAll('.hero-header');
            heroHeaders.forEach((header, index) => {
                this.addAnimation(header, 'fade-in-left', index * 0.1);
            });

            // Animate hero image (Single careers page)
            const heroImagesCareers = document.querySelectorAll('.hero .hero-image img, .hero-image img');
            heroImagesCareers.forEach((img, index) => {
                this.addAnimation(img, 'fade-in-right', index * 0.1);
            });

            // Animate hero location (Single careers page)
            const heroLocations = document.querySelectorAll('.hero-location');
            heroLocations.forEach((location, index) => {
                this.addAnimation(location, 'fade-in', index * 0.1 + 0.2);
            });

            // Animate application wrapper (Single careers page)
            const applicationWrappers = document.querySelectorAll('.application-wrapper');
            applicationWrappers.forEach((wrapper, index) => {
                this.addAnimation(wrapper, 'fade-in-up', index * 0.1);
            });

            // Animate form header (Single careers page)
            const formHeaders = document.querySelectorAll('.form-header');
            formHeaders.forEach((header, index) => {
                this.addAnimation(header, 'fade-in-up', index * 0.1);
            });

            // Animate form container (Single careers page)
            const formContainers = document.querySelectorAll('.form-container');
            formContainers.forEach((container, index) => {
                this.addAnimation(container, 'fade-in-up', index * 0.1 + 0.2);
            });

            // Animate file upload (Single careers page)
            const fileUploads = document.querySelectorAll('.file-upload');
            fileUploads.forEach((upload, index) => {
                this.addAnimation(upload, 'fade-in-up', index * 0.1);
            });

            // Animate hero logo (Single project page)
            const heroLogos = document.querySelectorAll('.hero-logo');
            heroLogos.forEach((logo, index) => {
                this.addAnimation(logo, 'fade-in', index * 0.1);
            });

            // Animate hero background image (Single project page)
            const heroBgs = document.querySelectorAll('.hero .hero-bg img');
            heroBgs.forEach((img, index) => {
                this.addAnimation(img, 'fade-in', index * 0.1);
            });

            // Animate section grid (Single project page)
            const sectionGrids = document.querySelectorAll('.section-grid');
            sectionGrids.forEach((grid, index) => {
                this.addAnimation(grid, 'fade-in-up', index * 0.1);
            });

            // Animate section content (Single project page)
            const sectionContents = document.querySelectorAll('.section-content');
            sectionContents.forEach((content, index) => {
                this.addAnimation(content, 'fade-in-up', index * 0.1 + 0.2);
            });

            // Animate image gallery (Single project page)
            const imageGalleries = document.querySelectorAll('.image-gallery');
            imageGalleries.forEach((gallery, index) => {
                this.addAnimation(gallery, 'fade-in-up', index * 0.1);
            });

            // Animate gallery images (Single project page)
            const galleryImgLarge = document.querySelectorAll('.gallery-img-large');
            galleryImgLarge.forEach((img, index) => {
                this.addAnimation(img, 'fade-in-up', index * 0.1);
            });

            // Animate project header (Single project page)
            const projectHeaders = document.querySelectorAll('.project-header');
            projectHeaders.forEach((header, index) => {
                this.addAnimation(header, 'fade-in-up', index * 0.1);
            });

            // Animate project meta (Single project page)
            const projectMetas = document.querySelectorAll('.project-meta');
            projectMetas.forEach((meta, index) => {
                this.addAnimation(meta, 'fade-in-up', index * 0.1 + 0.2);
            });

            // Animate meta items (Single project page)
            const metaItems = document.querySelectorAll('.meta-item');
            metaItems.forEach((item, index) => {
                this.addAnimation(item, 'fade-in-up', index * 0.1);
            });

            // Animate project actions (Single project page)
            const projectActions = document.querySelectorAll('.project-actions');
            projectActions.forEach((actions, index) => {
                this.addAnimation(actions, 'fade-in-up', index * 0.1 + 0.3);
            });

            // Animate project image (Single project page)
            const projectImagesSingle = document.querySelectorAll('.project-details .project-image');
            projectImagesSingle.forEach((img, index) => {
                this.addAnimation(img, 'fade-in-up', index * 0.1);
            });

            // Animate feature cards (Single project page)
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach((card, index) => {
                this.addAnimation(card, 'fade-in-up', index * 0.1);
            });

            // Animate features list (Single project page)
            const featuresLists = document.querySelectorAll('.features-list');
            featuresLists.forEach((list, index) => {
                this.addAnimation(list, 'fade-in-up', index * 0.1);
            });

            // Animate carousel (Single project page)
            const carousels = document.querySelectorAll('.carousel');
            carousels.forEach((carousel, index) => {
                this.addAnimation(carousel, 'fade-in-up', index * 0.1);
            });

            // Animate carousel footer (Single project page)
            const carouselFooters = document.querySelectorAll('.carousel-footer');
            carouselFooters.forEach((footer, index) => {
                this.addAnimation(footer, 'fade-in-up', index * 0.1);
            });

            // Animate amenities (Single project page)
            const amenities = document.querySelectorAll('.amenities');
            amenities.forEach((amenity, index) => {
                this.addAnimation(amenity, 'fade-in-up', index * 0.1);
            });

            // Animate amenity items (Single project page)
            const amenityItems = document.querySelectorAll('.amenity-item');
            amenityItems.forEach((item, index) => {
                this.addAnimation(item, 'fade-in-up', index * 0.1);
            });

            // Animate contact grid (Single project page)
            const contactGrids = document.querySelectorAll('.contact-grid');
            contactGrids.forEach((grid, index) => {
                this.addAnimation(grid, 'fade-in-up', index * 0.1);
            });

            // Animate contact info (Single project page)
            const contactInfos = document.querySelectorAll('.contact-info');
            contactInfos.forEach((info, index) => {
                this.addAnimation(info, 'fade-in-left', index * 0.1);
            });

            // Animate contact form (Single project page)
            const contactForms = document.querySelectorAll('.contact-form');
            contactForms.forEach((form, index) => {
                this.addAnimation(form, 'fade-in-right', index * 0.1);
            });
        }

        animateHeadings() {
            // Animate section titles
            const sectionTitles = document.querySelectorAll('.section-title');
            sectionTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate section labels
            const sectionLabels = document.querySelectorAll('.section-label');
            sectionLabels.forEach((label, index) => {
                this.addAnimation(label, 'fade-in', index * 0.1);
            });

            // Animate card titles (Mission/Vision, Values)
            const cardTitles = document.querySelectorAll('.card-title, .value-title');
            cardTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate slide titles (Why Choose slider)
            const slideTitles = document.querySelectorAll('.slide-title');
            slideTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in', index * 0.1);
            });

            // Animate hero title and description (Blog page)
            const heroTitles = document.querySelectorAll('.hero-title');
            heroTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1 + 0.2);
            });

            const heroDescriptions = document.querySelectorAll('.hero-description');
            heroDescriptions.forEach((desc, index) => {
                this.addAnimation(desc, 'fade-in-up', index * 0.1 + 0.4);
            });

            // Animate article titles (Blog page)
            const articleTitleLight = document.querySelectorAll('.article-title-light');
            articleTitleLight.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            const articleTitleSmall = document.querySelectorAll('.article-title-small');
            articleTitleSmall.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate article dates (Blog page)
            const articleDates = document.querySelectorAll('.article-date, .article-date-light');
            articleDates.forEach((date, index) => {
                this.addAnimation(date, 'fade-in', index * 0.05);
            });

            // Animate article excerpts (Blog page)
            const articleExcerpts = document.querySelectorAll('.article-excerpt-light, .card-excerpt');
            articleExcerpts.forEach((excerpt, index) => {
                this.addAnimation(excerpt, 'fade-in', index * 0.05);
            });

            // Animate CTA titles and subtitles (Blog page)
            const ctaTitles = document.querySelectorAll('.cta-title');
            ctaTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            const ctaSubtitles = document.querySelectorAll('.cta-subtitle');
            ctaSubtitles.forEach((subtitle, index) => {
                this.addAnimation(subtitle, 'fade-in', index * 0.1 + 0.2);
            });

            // Animate job titles (Careers page)
            const jobTitles = document.querySelectorAll('.job-title');
            jobTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate job types and locations (Careers page)
            const jobTypes = document.querySelectorAll('.job-type');
            jobTypes.forEach((type, index) => {
                this.addAnimation(type, 'fade-in', index * 0.05);
            });

            const jobLocations = document.querySelectorAll('.job-location');
            jobLocations.forEach((location, index) => {
                this.addAnimation(location, 'fade-in', index * 0.05);
            });

            // Animate section descriptions (Careers page)
            const sectionDescriptions = document.querySelectorAll('.section-description');
            sectionDescriptions.forEach((desc, index) => {
                this.addAnimation(desc, 'fade-in', index * 0.1);
            });

            // Animate CTA descriptions (Careers page)
            const ctaDescriptions = document.querySelectorAll('.cta-description');
            ctaDescriptions.forEach((desc, index) => {
                this.addAnimation(desc, 'fade-in', index * 0.1 + 0.2);
            });

            // Animate CTA icons (Careers page)
            const ctaIcons = document.querySelectorAll('.cta-icon');
            ctaIcons.forEach((icon, index) => {
                this.addAnimation(icon, 'scale-in', index * 0.1 + 0.3);
            });

            // Animate contact header (Contact page)
            const contactHeaders = document.querySelectorAll('.contact-header');
            contactHeaders.forEach((header, index) => {
                this.addAnimation(header, 'fade-in-up', index * 0.1);
            });

            // Animate contact navigation (Contact page)
            const contactNavs = document.querySelectorAll('.contact-nav');
            contactNavs.forEach((nav, index) => {
                this.addAnimation(nav, 'fade-in', index * 0.1 + 0.2);
            });

            // Animate form titles (Contact page)
            const formTitles = document.querySelectorAll('.form-title');
            formTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate contact labels and values (Contact page)
            const contactLabels = document.querySelectorAll('.contact-label');
            contactLabels.forEach((label, index) => {
                this.addAnimation(label, 'fade-in', index * 0.05);
            });

            const contactValues = document.querySelectorAll('.contact-value');
            contactValues.forEach((value, index) => {
                this.addAnimation(value, 'fade-in', index * 0.05 + 0.1);
            });

            // Animate map wrapper (Contact page)
            const mapWrappers = document.querySelectorAll('.map-wrapper');
            mapWrappers.forEach((wrapper, index) => {
                this.addAnimation(wrapper, 'fade-in-up', index * 0.1);
            });

            // Animate map address (Contact page)
            const mapAddresses = document.querySelectorAll('.map-address');
            mapAddresses.forEach((address, index) => {
                this.addAnimation(address, 'fade-in-up', index * 0.1 + 0.2);
            });

            // Animate hero subtitle (Projects page)
            const heroSubtitles = document.querySelectorAll('.hero-subtitle');
            heroSubtitles.forEach((subtitle, index) => {
                this.addAnimation(subtitle, 'fade-in-up', index * 0.1 + 0.4);
            });

            // Animate filter title (Projects page)
            const filterTitles = document.querySelectorAll('.filter-title');
            filterTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate filter dropdowns (Projects page)
            const filterDropdowns = document.querySelectorAll('.filter-dropdown-wrapper');
            filterDropdowns.forEach((dropdown, index) => {
                this.addAnimation(dropdown, 'fade-in-up', index * 0.1 + 0.15);
            });

            // Animate search bar (Projects page)
            const searchBars = document.querySelectorAll('.search-bar');
            searchBars.forEach((bar, index) => {
                this.addAnimation(bar, 'fade-in-up', index * 0.1 + 0.3);
            });

            // Animate project title large (Projects page)
            const projectTitleLarge = document.querySelectorAll('.project-title-large');
            projectTitleLarge.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate project location (Projects page)
            const projectLocations = document.querySelectorAll('.project-location');
            projectLocations.forEach((location, index) => {
                this.addAnimation(location, 'fade-in', index * 0.05);
            });

            // Animate project descriptions (Projects page)
            const projectDescriptions = document.querySelectorAll('.project-description, .project-desc');
            projectDescriptions.forEach((desc, index) => {
                this.addAnimation(desc, 'fade-in', index * 0.05);
            });

            // Animate hero text (Single blog page)
            const heroTexts = document.querySelectorAll('.hero-text');
            heroTexts.forEach((text, index) => {
                this.addAnimation(text, 'fade-in-left', index * 0.1);
            });

            // Animate article meta (Single blog page)
            const articleMetas = document.querySelectorAll('.article-meta');
            articleMetas.forEach((meta, index) => {
                this.addAnimation(meta, 'fade-in-up', index * 0.1);
            });

            // Animate article title (Single blog page)
            const articleTitles = document.querySelectorAll('.article-title');
            articleTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate article date (Single blog page)
            const articleDatesSingle = document.querySelectorAll('.article-date');
            articleDatesSingle.forEach((date, index) => {
                this.addAnimation(date, 'fade-in', index * 0.05);
            });

            // Animate card title (Single blog page)
            const cardTitlesSingle = document.querySelectorAll('.related-articles .card-title');
            cardTitlesSingle.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate card date (Single blog page)
            const cardDates = document.querySelectorAll('.card-date');
            cardDates.forEach((date, index) => {
                this.addAnimation(date, 'fade-in', index * 0.05);
            });

            // Animate card excerpt (Single blog page)
            const cardExcerpts = document.querySelectorAll('.card-excerpt');
            cardExcerpts.forEach((excerpt, index) => {
                this.addAnimation(excerpt, 'fade-in', index * 0.05);
            });

            // Animate section header (Single blog page)
            const sectionHeaders = document.querySelectorAll('.section-header');
            sectionHeaders.forEach((header, index) => {
                this.addAnimation(header, 'fade-in-up', index * 0.1);
            });

            // Section labels are already animated above

            // Animate related controls (Single blog page)
            const relatedControls = document.querySelectorAll('.related-controls');
            relatedControls.forEach((control, index) => {
                this.addAnimation(control, 'fade-in-up', index * 0.1);
            });

            // CTA descriptions and icons are already animated above (Careers page)

            // Animate heading (Single project page)
            const headings = document.querySelectorAll('.heading');
            headings.forEach((heading, index) => {
                this.addAnimation(heading, 'fade-in-up', index * 0.1);
            });

            // Animate subheading (Single project page)
            const subheadings = document.querySelectorAll('.subheading');
            subheadings.forEach((subheading, index) => {
                this.addAnimation(subheading, 'fade-in', index * 0.05);
            });

            // Animate body text (Single project page)
            const bodyTexts = document.querySelectorAll('.body-text');
            bodyTexts.forEach((text, index) => {
                this.addAnimation(text, 'fade-in', index * 0.05);
            });

            // Animate meta label (Single project page)
            const metaLabels = document.querySelectorAll('.meta-label');
            metaLabels.forEach((label, index) => {
                this.addAnimation(label, 'fade-in', index * 0.05);
            });

            // Animate meta value (Single project page)
            const metaValues = document.querySelectorAll('.meta-value');
            metaValues.forEach((value, index) => {
                this.addAnimation(value, 'fade-in-up', index * 0.1);
            });

            // Animate feature title (Single project page)
            const featureTitles = document.querySelectorAll('.feature-title');
            featureTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate feature icon (Single project page)
            const featureIcons = document.querySelectorAll('.feature-icon');
            featureIcons.forEach((icon, index) => {
                this.addAnimation(icon, 'scale-in', index * 0.1);
            });

            // Animate amenities title (Single project page)
            const amenitiesTitles = document.querySelectorAll('.amenities-title');
            amenitiesTitles.forEach((title, index) => {
                this.addAnimation(title, 'fade-in-up', index * 0.1);
            });

            // Animate amenity number (Single project page)
            const amenityNumbers = document.querySelectorAll('.amenity-number');
            amenityNumbers.forEach((number, index) => {
                this.addAnimation(number, 'fade-in', index * 0.05);
            });

            // Animate carousel counter (Single project page)
            const carouselCounters = document.querySelectorAll('.carousel-counter');
            carouselCounters.forEach((counter, index) => {
                this.addAnimation(counter, 'fade-in', index * 0.1);
            });
        }

        addAnimation(element, animationType, delay = 0) {
            if (!element) return;
            
            // Add animation class
            element.classList.add(animationType);
            
            // Add delay if specified
            if (delay > 0) {
                const delayClass = this.getDelayClass(delay);
                if (delayClass) {
                    element.classList.add(delayClass);
                } else {
                    element.style.transitionDelay = `${delay}s`;
                }
            }
            
            // Observe element if observer is available
            if (this.observer) {
                this.observer.observe(element);
            } else {
                // Fallback: animate immediately
                setTimeout(() => {
                    element.classList.add('animated');
                }, delay * 1000);
            }
        }

        getDelayClass(delay) {
            if (delay <= 0.1) return 'delay-100';
            if (delay <= 0.2) return 'delay-200';
            if (delay <= 0.3) return 'delay-300';
            if (delay <= 0.4) return 'delay-400';
            if (delay <= 0.5) return 'delay-500';
            return null;
        }

        animateAllElements() {
            // Fallback for browsers without IntersectionObserver
            const animatedElements = document.querySelectorAll('.fade-in, .fade-in-up, .fade-in-down, .fade-in-left, .fade-in-right, .scale-in');
            animatedElements.forEach((element, index) => {
                setTimeout(() => {
                    element.classList.add('animated');
                }, index * 50);
            });
        }

        setupHoverEffects() {
            // Add hover effects to buttons
            const buttons = document.querySelectorAll('.btn-primary, .btn-outline-primary, .btn-outline-secondary, .btn-outline-white');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add smooth transitions to images
            const images = document.querySelectorAll('.project-image, .news-image, .gallery-image, .about-img-1, .about-img-2, .slide-image img, .featured-image, .small-image, .card-image, .about-image, .hero-image-section .hero-image, .project-image-large, .hero-bg, .hero .hero-image, .related-article-card .card-image, .hero-image img, .gallery-img-large, .project-details .project-image img, .amenity-image, .carousel-image, .carousel-thumb, .hero .hero-bg img');
            images.forEach(img => {
                img.style.transition = 'transform 0.5s ease, opacity 0.3s ease';
            });

            // Add hover effects to mission/vision cards
            const missionCards = document.querySelectorAll('.mission-card');
            missionCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add hover effects to value items
            const valueItems = document.querySelectorAll('.value-item');
            valueItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add hover effects to featured articles (Blog page)
            const featuredLargeArticles = document.querySelectorAll('.featured-large');
            featuredLargeArticles.forEach(article => {
                article.addEventListener('mouseenter', function() {
                    const img = this.querySelector('.featured-image');
                    if (img) {
                        img.style.transform = 'scale(1.05)';
                    }
                });
                article.addEventListener('mouseleave', function() {
                    const img = this.querySelector('.featured-image');
                    if (img) {
                        img.style.transform = 'scale(1)';
                    }
                });
            });

            // Add hover effects to featured small articles (Blog page)
            const featuredSmallArticles = document.querySelectorAll('.featured-small');
            featuredSmallArticles.forEach(article => {
                article.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    const img = this.querySelector('.small-image');
                    if (img) {
                        img.style.transform = 'scale(1.05)';
                    }
                });
                article.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    const img = this.querySelector('.small-image');
                    if (img) {
                        img.style.transform = 'scale(1)';
                    }
                });
            });

            // Add hover effects to article cards (Blog page)
            const articleCards = document.querySelectorAll('.article-card');
            articleCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    const img = this.querySelector('.card-image');
                    if (img) {
                        img.style.transform = 'scale(1.05)';
                    }
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    const img = this.querySelector('.card-image');
                    if (img) {
                        img.style.transform = 'scale(1)';
                    }
                });
            });

            // Add hover effects to CTA link (Blog page)
            const ctaLinks = document.querySelectorAll('.cta-link');
            ctaLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1)';
                });
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Add hover effects to job cards (Careers page)
            const jobCards = document.querySelectorAll('.job-card');
            jobCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.15)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });

            // Add hover effects to CTA icon (Careers page)
            const ctaIcons = document.querySelectorAll('.cta-icon');
            ctaIcons.forEach(icon => {
                icon.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1) rotate(5deg)';
                });
                icon.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1) rotate(0deg)';
                });
            });

            // Contact items hover effects are handled by CSS

            // Add hover effects to map address (Contact page)
            const mapAddresses = document.querySelectorAll('.map-address');
            mapAddresses.forEach(address => {
                address.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                    this.style.transition = 'transform 0.3s ease';
                });
                address.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add hover effects to form inputs (Contact page)
            const formInputs = document.querySelectorAll('.form-input, .form-textarea');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
                });
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Add hover effects to submit button (Contact page)
            const submitButtons = document.querySelectorAll('.btn-submit');
            submitButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });

            // Add hover effects to featured project card (Projects page)
            const projectCardLarge = document.querySelectorAll('.project-card-large');
            projectCardLarge.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    const img = this.querySelector('.project-image-large');
                    if (img) {
                        img.style.transform = 'scale(1.05)';
                    }
                });
                card.addEventListener('mouseleave', function() {
                    const img = this.querySelector('.project-image-large');
                    if (img) {
                        img.style.transform = 'scale(1)';
                    }
                });
            });

            // Project cards hover effects are handled by CSS

            // Add hover effects to filter dropdowns (Projects page)
            const filterDropdowns = document.querySelectorAll('.filter-dropdown');
            filterDropdowns.forEach(dropdown => {
                dropdown.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.transition = 'transform 0.3s ease';
                });
                dropdown.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Add hover effects to search button (Projects page)
            const searchButtons = document.querySelectorAll('.search-button');
            searchButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });

            // Related article cards hover effects are handled by CSS

            // File upload label and submit button hover effects are handled by CSS

            // Feature cards, amenity items, and project actions hover effects are handled by CSS
        }
    }

    // Initialize
    const scrollAnimations = new ScrollAnimations();
    
    // Export to global scope if needed
    window.ScrollAnimations = ScrollAnimations;
})();

