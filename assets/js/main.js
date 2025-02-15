document.addEventListener('DOMContentLoaded', function() {
    var menuToggle = document.getElementById('menu-toggle');
    var primaryMenu = document.getElementById('site-navigation-mobile');

    menuToggle.addEventListener('click', function() {
        var expanded = this.getAttribute('aria-expanded') === 'true' || false;
        this.setAttribute('aria-expanded', !expanded);
        primaryMenu.classList.toggle('active');
    });
});

// Header effects
document.addEventListener('DOMContentLoaded', function() {
    var header = document.getElementById('masthead');
    var scrollTrigger = 0.3; // 30% of viewport height

    // Calculate the trigger point
    function calculateTriggerPoint() {
        return window.innerHeight * scrollTrigger;
    }

    // Handle scroll events
    function handleScroll() {
        var triggerPoint = calculateTriggerPoint();
        
        if (window.pageYOffset > triggerPoint) {
            header.classList.add('shrink');
        } else {
            header.classList.remove('shrink');
        }
    }

    // Add scroll event listener
    window.addEventListener('scroll', handleScroll);
    
    // Add resize event listener to recalculate trigger point
    window.addEventListener('resize', () => {
        handleScroll();
    });

    // Initial check
    handleScroll();
});