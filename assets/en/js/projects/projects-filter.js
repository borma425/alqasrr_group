/**
 * Projects Filter - submits the filter form to trigger server-side queries
 */
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('projects-filter-form');
    if (!filterForm) {
        return;
    }

    const locationFilter = filterForm.querySelector('#filter-location');
    const typeFilter = filterForm.querySelector('#filter-type');

    const submitForm = () => {
        if (typeof filterForm.requestSubmit === 'function') {
            filterForm.requestSubmit();
        } else {
            filterForm.submit();
        }
    };

    if (locationFilter) {
        locationFilter.addEventListener('change', submitForm);
    }

    if (typeFilter) {
        typeFilter.addEventListener('change', submitForm);
    }
});
