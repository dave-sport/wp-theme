/**
 * Hero Slider
 * Lightweight slider using CSS scroll-snap + pagination dots
 * No dependencies, ~1kb gzipped
 */

(function() {
  'use strict';
  
  const slider = document.querySelector('[data-hero-slider]');
  if (!slider) return;
  
  const track = slider.querySelector('.hero-slider__track');
  const dots = slider.querySelectorAll('.hero-slider__dot');
  
  if (!track || dots.length === 0) return;
  
  // Update active dot based on scroll position
  function updateActiveDot() {
    const scrollLeft = track.scrollLeft;
    const slideWidth = track.offsetWidth;
    const activeIndex = Math.round(scrollLeft / slideWidth);
    
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === activeIndex);
    });
  }
  
  // Scroll to slide on dot click
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      const slideWidth = track.offsetWidth;
      track.scrollTo({
        left: slideWidth * index,
        behavior: 'smooth'
      });
    });
  });
  
  // Update dots on scroll (debounced)
  let scrollTimeout;
  track.addEventListener('scroll', () => {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(updateActiveDot, 100);
  });
  
  // Initial state
  updateActiveDot();
  
})();
