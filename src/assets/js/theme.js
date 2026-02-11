/**
 * Theme JS - Vanilla JavaScript (No jQuery)
 * Clean, modern, lightweight.
 */

(function() {
    'use strict';

    // ========== Mobile Menu (uses sidebar now) ==========
    function initMobileMenu() {
        // Mobile toggle now just triggers the sidebar
        // No separate mobile menu anymore!
    }

    // ========== Sidebar Menu (works for mobile + desktop now) ==========
    function initSidebarMenu() {
        const sidebar = document.querySelector('.sidemenu-wrapper');
        if (!sidebar) return;

        function closeSidebar() {
            sidebar.classList.remove('show');
        }

        function openSidebar() {
            sidebar.classList.add('show');
        }

        // Handle ALL toggle buttons (both desktop .sideMenuToggler and mobile .th-menu-toggle)
        document.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.sideMenuToggler, .th-menu-toggle');
            const closeBtn = e.target.closest('.sideMenuCls');
            
            if (toggleBtn && !sidebar.contains(toggleBtn)) {
                e.preventDefault();
                e.stopPropagation();
                openSidebar();
                return;
            }
            
            if (closeBtn) {
                e.preventDefault();
                closeSidebar();
                return;
            }
        });
        
        // Click outside to close
        sidebar.addEventListener('click', function(e) {
            if (e.target === sidebar) {
                closeSidebar();
            }
        });
        
        // Submenu toggles for sidebar
        const menuItems = sidebar.querySelectorAll('.desktop_side_menu li.menu-item-has-children > a');
        menuItems.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const submenu = parent.querySelector('ul.sub-menu');
                
                if (submenu) {
                    parent.classList.toggle('open');
                }
            });
        });
    }

    // ========== Scroll To Top ==========
    function initScrollToTop() {
        const scrollBtn = document.querySelector('.scroll-top');
        if (!scrollBtn) return;

        // Show/hide button on scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });

        // Smooth scroll to top
        scrollBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ========== Sticky Header ==========
    function initStickyHeader() {
        const header = document.querySelector('.sticky-wrapper');
        if (!header) return;

        let lastScroll = 0;
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.scrollY;
            
            if (currentScroll > 200) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }
            
            lastScroll = currentScroll;
        });
    }

    // ========== News Ticker ==========
    function initNewsTicker() {
        const ticker = document.querySelector('.slick-marquee');
        if (!ticker) return;

        // Duplicate content for seamless loop
        const items = ticker.innerHTML;
        ticker.innerHTML = items + items;
    }

    // ========== Background Images ==========
    function setBackgroundImages() {
        const elements = document.querySelectorAll('[data-bg-src]');
        elements.forEach(el => {
            const bgSrc = el.getAttribute('data-bg-src');
            if (bgSrc) {
                el.style.backgroundImage = `url(${bgSrc})`;
            }
        });
    }

    // ========== Init Everything ==========
    function init() {
        initMobileMenu();
        initSidebarMenu();
        initScrollToTop();
        initStickyHeader();
        initNewsTicker();
        setBackgroundImages();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
