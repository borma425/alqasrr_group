(function () {
    'use strict';

    if (typeof window === 'undefined') {
        return;
    }

    var forms = document.querySelectorAll('.contact-form');
    if (!forms.length) {
        return;
    }

    var ajaxSettings = window.AlQasrGroupContact || {};

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitForm(form, ajaxSettings);
        });
    });

    function submitForm(form, settings) {
        var messageBox = form.querySelector('.form-message');
        if (!messageBox) {
            messageBox = document.createElement('div');
            messageBox.className = 'form-message';
            form.insertBefore(messageBox, form.firstChild);
        }

        var submitButton = form.querySelector('button[type="submit"], .btn-submit');
        toggleLoading(submitButton, true);
        showMessage(messageBox, '', '');

        var formData = new FormData(form);
        if (!formData.has('action')) {
            formData.append('action', 'AlQasrGroup_contact_form');
        }
        if (!formData.has('nonce') && settings.nonce) {
            formData.append('nonce', settings.nonce);
        }
        if (!formData.has('current_language')) {
            var htmlLang = (document.documentElement.getAttribute('lang') || 'ar').toLowerCase();
            formData.append('current_language', htmlLang.indexOf('en') === 0 ? 'en' : 'ar');
        }

        var ajaxUrl = settings.ajax_url || '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(function (data) {
            if (data && data.success) {
                var successText = typeof data.data === 'string' && data.data.trim() ? data.data : getFallbackMessage(formData.get('current_language'), true);
                showMessage(messageBox, successText, 'success');
                form.reset();
            } else {
                var errorText = data && typeof data.data === 'string' && data.data.trim() ? data.data : getFallbackMessage(formData.get('current_language'), false);
                showMessage(messageBox, errorText, 'error');
            }
        }).catch(function () {
            showMessage(messageBox, getFallbackMessage(formData.get('current_language'), false), 'error');
        }).finally(function () {
            toggleLoading(submitButton, false);
        });
    }

    function toggleLoading(button, isLoading) {
        if (!button) {
            return;
        }
        var submitText = button.querySelector('.submit-text');
        var loadingText = button.querySelector('.loading-text');

        if (submitText) {
            submitText.style.display = isLoading ? 'none' : '';
        }
        if (loadingText) {
            loadingText.style.display = isLoading ? '' : 'none';
        }
        button.disabled = !!isLoading;
    }

    function showMessage(box, text, status) {
        if (!box) {
            return;
        }
        box.textContent = text || '';
        box.style.display = text ? 'block' : 'none';
        box.classList.remove('form-message--success', 'form-message--error');
        if (status === 'success') {
            box.classList.add('form-message--success');
        } else if (status === 'error') {
            box.classList.add('form-message--error');
        }
    }

    function getFallbackMessage(language, isSuccess) {
        var lang = (language || '').toLowerCase();
        var successMessages = {
            en: 'Your message has been sent successfully!',
            ar: 'تم إرسال رسالتك بنجاح!'
        };
        var errorMessages = {
            en: 'Something went wrong. Please try again.',
            ar: 'حدث خطأ ما، يرجى المحاولة مرة أخرى.'
        };

        if (isSuccess) {
            return lang === 'en' ? successMessages.en : successMessages.ar;
        }
        return lang === 'en' ? errorMessages.en : errorMessages.ar;
    }
})();
