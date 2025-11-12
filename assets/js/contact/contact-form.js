/**
 * AJAX Contact Form Handler
 * Supports all forms with the `.contact-form` class.
 */
(function () {
    'use strict';

    const ACTION = 'AlQasrGroup_contact_form';

    const config = {
        ajaxUrl: (window.AlQasrGroupContact && window.AlQasrGroupContact.ajax_url) ||
            (window.ajaxurl ? window.ajaxurl : `${window.location.origin}/wp-admin/admin-ajax.php`),
        nonce: window.AlQasrGroupContact && window.AlQasrGroupContact.nonce ? window.AlQasrGroupContact.nonce : ''
    };

    /**
     * Toggle submit button loading state.
     * @param {HTMLFormElement} form
     * @param {boolean} isLoading
     */
    function toggleLoading(form, isLoading) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (!submitButton) {
            return;
        }

        const submitText = submitButton.querySelector('.submit-text');
        const loadingText = submitButton.querySelector('.loading-text');

        if (isLoading) {
            submitButton.disabled = true;
            submitButton.classList.add('is-loading');
            if (submitText) {
                submitText.style.display = 'none';
            }
            if (loadingText) {
                loadingText.style.display = 'inline-block';
            }
        } else {
            submitButton.disabled = false;
            submitButton.classList.remove('is-loading');
            if (submitText) {
                submitText.style.display = '';
            }
            if (loadingText) {
                loadingText.style.display = 'none';
            }
        }
    }

    /**
     * Display message inside the form.
     * @param {HTMLElement|null} messageElement
     * @param {string} message
     * @param {boolean} isSuccess
     */
    function showMessage(messageElement, message, isSuccess) {
        if (!messageElement) {
            return;
        }

        messageElement.style.display = 'block';
        messageElement.textContent = message;

        messageElement.classList.remove('alert-success', 'alert-danger', 'success', 'error');
        if (isSuccess) {
            messageElement.classList.add('alert-success', 'success');
            messageElement.style.backgroundColor = '#d4edda';
            messageElement.style.color = '#155724';
            messageElement.style.border = '1px solid #c3e6cb';
        } else {
            messageElement.classList.add('alert-danger', 'error');
            messageElement.style.backgroundColor = '#f8d7da';
            messageElement.style.color = '#721c24';
            messageElement.style.border = '1px solid #f5c6cb';
        }
    }

    /**
     * Determine current language.
     * @param {HTMLFormElement} form
     * @returns {string}
     */
    function resolveLanguage(form) {
        const formLanguage = form.getAttribute('data-language');
        if (formLanguage) {
            return formLanguage;
        }

        const htmlLang = document.documentElement.lang || '';
        if (htmlLang.toLowerCase().startsWith('en')) {
            return 'en';
        }

        if (htmlLang.toLowerCase().startsWith('ar')) {
            return 'ar';
        }

        return (document.body && document.body.classList.contains('lang-en')) ? 'en' : 'ar';
    }

    /**
     * Handle form submission.
     * @param {SubmitEvent} event
     */
    async function handleSubmit(event) {
        event.preventDefault();

        const form = event.currentTarget;
        const messageElement = form.querySelector('.form-message') || form.querySelector('.alert');

        if (messageElement) {
            messageElement.style.display = 'none';
            messageElement.textContent = '';
        }

        toggleLoading(form, true);

        try {
            const formData = new FormData(form);

            if (!formData.has('nonce') && config.nonce) {
                formData.append('nonce', config.nonce);
            }

            if (!formData.has('current_language')) {
                formData.append('current_language', resolveLanguage(form));
            }

            formData.append('action', ACTION);

            const response = await fetch(config.ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            });

            const result = await response.json();

            if (result && result.success) {
                const successMessage = typeof result.data === 'string' && result.data.length > 0
                    ? result.data
                    : (resolveLanguage(form) === 'en'
                        ? 'Your message has been sent successfully!'
                        : 'تم إرسال رسالتك بنجاح!');

                showMessage(messageElement, successMessage, true);
                form.reset();
            } else {
                const errorMessage = result && result.data
                    ? result.data
                    : (resolveLanguage(form) === 'en'
                        ? 'An error occurred while sending your message. Please try again.'
                        : 'حدث خطأ أثناء إرسال رسالتك. يرجى المحاولة مرة أخرى.');
                showMessage(messageElement, errorMessage, false);
            }
        } catch (error) {
            showMessage(
                form.querySelector('.form-message') || form.querySelector('.alert'),
                resolveLanguage(form) === 'en'
                    ? 'Unable to send your message at the moment. Please try again later.'
                    : 'تعذّر إرسال رسالتك في الوقت الحالي. يرجى المحاولة لاحقاً.',
                false
            );
            console.error('Contact form submission failed:', error);
        } finally {
            toggleLoading(form, false);
        }
    }

    /**
     * Initialize contact forms on DOM ready.
     */
    function init() {
        const forms = document.querySelectorAll('.contact-form');
        if (!forms.length) {
            return;
        }

        forms.forEach(form => {
            if (form.dataset.contactFormBound === 'true') {
                return;
            }
            form.dataset.contactFormBound = 'true';
            form.addEventListener('submit', handleSubmit);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();


