/**
 * Critique card deep linking.
 *
 * Updates the URL hash when a critique card is opened, and opens + scrolls
 * to a card on page load if the URL contains a #critique-* hash.
 *
 * Works on any page that contains .critique-item or .critique-card elements
 * with id attributes.
 */
(function() {
    var cards = document.querySelectorAll('.critique-item, .critique-card');
    if (!cards.length) return;

    // Update URL hash when a card is toggled open
    cards.forEach(function(card) {
        card.addEventListener('toggle', function() {
            if (card.open && card.id) {
                var newURL = window.location.pathname + window.location.search + '#' + card.id;
                history.replaceState(history.state, '', newURL);
            }
        });
    });

    // Open a specific critique from URL hash
    function openFromHash() {
        var hash = window.location.hash;
        if (hash && hash.indexOf('#critique-') === 0) {
            var el = document.getElementById(hash.slice(1));
            if (el) {
                // Unhide if hidden by show-all logic
                el.classList.remove('all-hidden');
                el.classList.remove('unique-hidden');

                el.open = true;
                setTimeout(function() {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }
    }

    openFromHash();
    window.addEventListener('hashchange', openFromHash);
})();
