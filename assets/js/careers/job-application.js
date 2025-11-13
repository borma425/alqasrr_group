(function () {
    'use strict';

    if (typeof AlQasrGroupJobApplication === 'undefined') {
        return;
    }

    var form = document.getElementById('applicationForm');
    if (!form) {
        return;
    }

    var submitButton = form.querySelector('button[type="submit"]');
    var feedbackEl = document.getElementById('applicationFeedback');
    var fileInput = form.querySelector('input[type="file"][name="cv_file"]');
    var fileNameEl = document.getElementById('cvFileName');
    var uploadLabel = document.getElementById('cvUploadLabel');
    var isSubmitting = false;
    var maxFileSize = parseInt(AlQasrGroupJobApplication.max_file_size, 10) || 5242880;
    var allowedMimes = AlQasrGroupJobApplication.allowed_mime_types || {};

    function setFeedback(message, type) {
        if (!feedbackEl) {
            return;
        }
        feedbackEl.textContent = message || '';
        feedbackEl.classList.remove('is-success', 'is-error');
        if (type) {
            feedbackEl.classList.add(type);
        }
    }

    function resetFeedback() {
        setFeedback('', null);
    }

    if (fileInput && fileNameEl) {
        fileInput.addEventListener('change', function () {
            if (!fileInput.files || !fileInput.files.length) {
                fileNameEl.textContent = '';
                if (uploadLabel) {
                    uploadLabel.classList.remove('has-file');
                }
                resetFeedback();
                return;
            }
            var file = fileInput.files[0];
            fileNameEl.textContent = file.name;
            if (uploadLabel) {
                uploadLabel.classList.add('has-file');
            }

            if (uploadLabel) {
                var promptEl = uploadLabel.querySelector('.file-upload-prompt');
                if (promptEl && AlQasrGroupJobApplication.file_selected_message) {
                    promptEl.textContent = AlQasrGroupJobApplication.file_selected_message.replace('%s', file.name);
                }
            }
        });
    }

    function validateFile() {
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            setFeedback(AlQasrGroupJobApplication.file_required_message, 'is-error');
            return false;
        }

        var file = fileInput.files[0];
        if (file.size > maxFileSize) {
            setFeedback(AlQasrGroupJobApplication.file_size_error, 'is-error');
            return false;
        }

        var allowedMimeValues = Object.keys(allowedMimes).map(function (key) {
            return allowedMimes[key];
        });

        if (allowedMimeValues.length && allowedMimeValues.indexOf(file.type) === -1) {
            setFeedback(AlQasrGroupJobApplication.file_type_error, 'is-error');
            return false;
        }

        return true;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (isSubmitting) {
            return;
        }

        resetFeedback();

        if (!validateFile()) {
            return;
        }

        if (!form.checkValidity()) {
            setFeedback(AlQasrGroupJobApplication.validation_error_message, 'is-error');
            form.reportValidity();
            return;
        }

        var formData = new FormData(form);
        formData.append('action', 'AlQasrGroup_job_application');
        formData.append('nonce', AlQasrGroupJobApplication.nonce);
        formData.append('current_language', AlQasrGroupJobApplication.current_language);

        isSubmitting = true;
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('is-loading');
        }

        fetch(AlQasrGroupJobApplication.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData,
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (data) {
                if (data && data.success) {
                    setFeedback(data.data || AlQasrGroupJobApplication.success_message, 'is-success');
                    form.reset();
                    if (fileNameEl) {
                        fileNameEl.textContent = '';
                    }
                    if (uploadLabel) {
                        uploadLabel.classList.remove('has-file');
                    }
                } else {
                    var errorMessage = (data && data.data) || AlQasrGroupJobApplication.generic_error_message;
                    setFeedback(errorMessage, 'is-error');
                }
            })
            .catch(function () {
                setFeedback(AlQasrGroupJobApplication.generic_error_message, 'is-error');
            })
            .finally(function () {
                isSubmitting = false;
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('is-loading');
                }
            });
    });
})();
