/**
 * Projects Filter
 * Handles filtering of project cards based on location and type selections
 */

document.addEventListener('DOMContentLoaded', function() {
    const locationFilter = document.getElementById('filter-location');
    const typeFilter = document.getElementById('filter-type');
    const searchInput = document.querySelector('.search-input input');
    const projectCards = document.querySelectorAll('.project-card');
    const gridContainer = document.querySelector('.grid-container');
    
    if (!locationFilter || !typeFilter || !projectCards.length) {
        return;
    }
    
    /**
     * Filter projects based on selected filters and search query
     */
    function filterProjects() {
        const selectedLocation = locationFilter.value;
        const selectedType = typeFilter.value;
        const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : '';
        
        let visibleCount = 0;
        
        projectCards.forEach(card => {
            const location = card.getAttribute('data-location') || '';
            const type = card.getAttribute('data-type') || '';
            const title = card.querySelector('.project-title')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.project-desc')?.textContent.toLowerCase() || '';
            
            // Check location filter
            const locationMatch = !selectedLocation || location === selectedLocation;
            
            // Check type filter
            const typeMatch = !selectedType || type === selectedType;
            
            // Check search query
            const searchMatch = !searchQuery || 
                title.includes(searchQuery) || 
                description.includes(searchQuery) ||
                location.toLowerCase().includes(searchQuery);
            
            // Show card if all filters match
            if (locationMatch && typeMatch && searchMatch) {
                card.classList.remove('filtered-out');
                visibleCount++;
            } else {
                card.classList.add('filtered-out');
            }
        });
        
        // Show message if no projects found
        showNoResultsMessage(visibleCount === 0);
    }
    
    /**
     * Show or hide "no results" message
     */
    function showNoResultsMessage(show) {
        let noResultsMsg = document.querySelector('.no-results-message');
        
        if (show && !noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.className = 'no-results-message';
            noResultsMsg.innerHTML = '<p>لا توجد مشاريع تطابق المعايير المحددة.</p>';
            gridContainer.appendChild(noResultsMsg);
        } else if (!show && noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    // Event listeners
    if (locationFilter) {
        locationFilter.addEventListener('change', filterProjects);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterProjects);
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterProjects);
        
        // Also trigger on search button click
        const searchButton = document.querySelector('.search-button');
        if (searchButton) {
            searchButton.addEventListener('click', filterProjects);
        }
        
        // Trigger on Enter key press
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterProjects();
            }
        });
    }
});

