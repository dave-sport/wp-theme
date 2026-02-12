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
  const prevBtn = slider.querySelector('.hero-slider__prev');
  const nextBtn = slider.querySelector('.hero-slider__next');
  
  if (!track || dots.length === 0) return;

  const slides = Array.from(track.children);

  // Calculate active slide index based on scroll
  function updateActiveDot() {
    const scrollLeft = track.scrollLeft;
    const slideWidth = slides[0].offsetWidth; // Use first slide width
    const activeIndex = Math.round(scrollLeft / slideWidth);
    
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === activeIndex);
    });
  }

  // Scroll to a specific slide
  function goToSlide(index) {
    const slideWidth = slides[0].offsetWidth;
    track.scrollTo({
      left: slideWidth * index,
      behavior: 'smooth'
    });
  }

  // Dots click
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => goToSlide(index));
  });

  // Arrow clicks
  prevBtn?.addEventListener('click', () => {
    const activeIndex = Array.from(dots).findIndex(dot => dot.classList.contains('active'));
    const newIndex = activeIndex > 0 ? activeIndex - 1 : slides.length - 1;
    goToSlide(newIndex);
  });

  nextBtn?.addEventListener('click', () => {
    const activeIndex = Array.from(dots).findIndex(dot => dot.classList.contains('active'));
    const newIndex = activeIndex < slides.length - 1 ? activeIndex + 1 : 0;
    goToSlide(newIndex);
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