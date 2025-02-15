document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const searchToggle = document.getElementById('search-toggle');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = searchOverlay.querySelector('.search-close');
    const searchInput = searchOverlay.querySelector('input[type="search"]');

    // Function to open search overlay
    function openSearchOverlay() {
        searchOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        searchToggle.setAttribute('aria-expanded', 'true');
        // Focus on search input after animation
        setTimeout(() => {
            searchInput.focus();
        }, 300);
    }

    // Function to close search overlay
    function closeSearchOverlay() {
        searchOverlay.classList.remove('active');
        document.body.style.overflow = '';
        searchToggle.setAttribute('aria-expanded', 'false');
    }

    // Event listeners
    searchToggle.addEventListener('click', openSearchOverlay);
    searchClose.addEventListener('click', closeSearchOverlay);

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            closeSearchOverlay();
        }
    });

    // Close on click outside search form
    searchOverlay.addEventListener('click', function(e) {
        if (e.target === searchOverlay) {
            closeSearchOverlay();
        }
    });
});