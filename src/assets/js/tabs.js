/**
 * Simple tab switcher for homepage category sections.
 * Replaces Isotope.js (~25kb) with ~20 lines of vanilla JS.
 */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.section-category__tabs').forEach(function(tabGroup) {
        var section = tabGroup.closest('.section-category');
        var tabs = tabGroup.querySelectorAll('.section-category__tab');
        var contents = section.querySelectorAll('.section-category__content');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var filter = tab.getAttribute('data-filter');

                tabs.forEach(function(t) { t.classList.remove('active'); });
                tab.classList.add('active');

                contents.forEach(function(c) {
                    c.style.display = c.classList.contains(filter.replace('.', '')) ? '' : 'none';
                });
            });
        });
    });
});
