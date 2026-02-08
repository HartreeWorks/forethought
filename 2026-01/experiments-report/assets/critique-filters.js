/**
 * Interactive filters and navigation for critique experiment reports.
 *
 * Features:
 * - Paper and variant filter pills for the "All critiques" appendix
 * - Results section paper filter toggle
 * - URL state management (query params and hash)
 * - Sticky filter bar with visibility toggle
 * - Critique card deep linking
 */

// Appendix 3: All critiques filter functionality
(function() {
    const paperFilter = document.getElementById('paper-filter');
    const variantFilter = document.getElementById('variant-filter');
    const uniquenessFilter = document.getElementById('uniqueness-filter');
    const critiqueList = document.getElementById('critique-list');
    const visibleCount = document.getElementById('visible-count');

    if (!paperFilter || !variantFilter || !critiqueList) return;

    const items = critiqueList.querySelectorAll('.critique-item');
    const showAllBtn = document.getElementById('show-all-critiques');
    let allExpanded = false;

    let selectedPaper = 'all';
    let selectedVariant = 'all';
    let selectedUniqueness = 'unique';

    function updateURL() {
        const params = new URLSearchParams();
        if (selectedPaper !== 'all') params.set('paper', selectedPaper);
        if (selectedVariant !== 'all') params.set('variant', selectedVariant);
        if (selectedUniqueness !== 'unique') params.set('uniqueness', selectedUniqueness);
        const queryString = params.toString();
        const newURL = window.location.pathname + (queryString ? '?' + queryString : '') + '#appendix-5-all-critiques';
        history.pushState({ paper: selectedPaper, variant: selectedVariant, uniqueness: selectedUniqueness }, '', newURL);
    }

    const defaultLimit = 20;

    function updateFilters(updateHistory = true) {
        let totalMatch = 0;
        let shownCount = 0;
        items.forEach(item => {
            const matchPaper = selectedPaper === 'all' || item.dataset.paper === selectedPaper;
            const matchVariant = selectedVariant === 'all' || item.dataset.variant === selectedVariant;
            const matchUniqueness = selectedUniqueness === 'all' || item.dataset.uniqueness === 'unique';
            const matchesFilter = matchPaper && matchVariant && matchUniqueness;
            if (matchesFilter) totalMatch++;
            // When not expanded, respect the all-hidden class (first N only)
            const truncated = !allExpanded && item.classList.contains('all-hidden');
            const visible = matchesFilter && !truncated;
            item.style.display = visible ? '' : 'none';
            if (visible) shownCount++;
        });
        if (visibleCount) visibleCount.textContent = shownCount;
        // Update show-all button visibility and text
        if (showAllBtn) {
            if (!allExpanded && totalMatch > shownCount) {
                showAllBtn.textContent = 'Show all ' + totalMatch + ' critiques';
                showAllBtn.classList.remove('hidden');
            } else {
                showAllBtn.classList.add('hidden');
            }
        }
        if (updateHistory) updateURL();
    }

    function setActiveButton(container, value) {
        container.querySelectorAll('.pill').forEach(p => {
            p.classList.toggle('active', p.dataset.value === value);
        });
    }

    function loadFromURL() {
        const params = new URLSearchParams(window.location.search);
        selectedPaper = params.get('paper') || 'all';
        selectedVariant = params.get('variant') || 'all';
        selectedUniqueness = params.get('uniqueness') || 'unique';
        setActiveButton(paperFilter, selectedPaper);
        setActiveButton(variantFilter, selectedVariant);
        if (uniquenessFilter) setActiveButton(uniquenessFilter, selectedUniqueness);
        updateFilters(false);
    }

    function expandAllCritiques() {
        if (!allExpanded) {
            items.forEach(item => item.classList.remove('all-hidden'));
            allExpanded = true;
            updateFilters(false);
        }
    }

    paperFilter.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            selectedPaper = e.target.dataset.value;
            setActiveButton(paperFilter, selectedPaper);
            expandAllCritiques();
            updateFilters();
        }
    });

    variantFilter.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            selectedVariant = e.target.dataset.value;
            setActiveButton(variantFilter, selectedVariant);
            expandAllCritiques();
            updateFilters();
        }
    });

    if (uniquenessFilter) {
        uniquenessFilter.addEventListener('click', e => {
            if (e.target.classList.contains('pill')) {
                selectedUniqueness = e.target.dataset.value;
                setActiveButton(uniquenessFilter, selectedUniqueness);
                expandAllCritiques();
                updateFilters();
            }
        });
    }

    window.addEventListener('popstate', e => {
        if (e.state) {
            selectedPaper = e.state.paper || 'all';
            selectedVariant = e.state.variant || 'all';
            selectedUniqueness = e.state.uniqueness || 'unique';
            setActiveButton(paperFilter, selectedPaper);
            setActiveButton(variantFilter, selectedVariant);
            if (uniquenessFilter) setActiveButton(uniquenessFilter, selectedUniqueness);
            updateFilters(false);
        }
    });

    // Update URL hash when any critique card is opened
    const allCritiqueCards = document.querySelectorAll('.critique-item, .critique-card');
    allCritiqueCards.forEach(card => {
        card.addEventListener('toggle', e => {
            if (card.open && card.id) {
                const newURL = window.location.pathname + window.location.search + '#' + card.id;
                history.replaceState(history.state, '', newURL);
            }
        });
    });

    // Open a specific critique from URL hash
    function openCritiqueFromHash() {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#critique-')) {
            const critiqueEl = document.getElementById(hash.slice(1));
            if (critiqueEl) {
                // Expand truncated list if needed
                if (critiqueEl.classList.contains('all-hidden')) {
                    expandAllCritiques();
                }
                // If it's in the filtered list and hidden, reset filters
                if (critiqueEl.classList.contains('critique-item') && critiqueEl.style.display === 'none') {
                    selectedPaper = 'all';
                    selectedVariant = 'all';
                    selectedUniqueness = 'all';
                    setActiveButton(paperFilter, 'all');
                    setActiveButton(variantFilter, 'all');
                    if (uniquenessFilter) setActiveButton(uniquenessFilter, 'all');
                    updateFilters(false);
                }

                // Open the card and scroll to it
                critiqueEl.open = true;
                setTimeout(() => {
                    critiqueEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }
    }

    if (showAllBtn) {
        showAllBtn.addEventListener('click', expandAllCritiques);
    }

    // Load initial state from URL
    loadFromURL();
    openCritiqueFromHash();

    // If any filter is non-default, expand all (defaults: paper=all, variant=all, uniqueness=unique)
    if (selectedPaper !== 'all' || selectedVariant !== 'all' || selectedUniqueness !== 'unique') {
        expandAllCritiques();
    }

    // Handle hash changes (e.g., clicking internal links)
    window.addEventListener('hashchange', openCritiqueFromHash);
})();

// Unique critiques Show all button (separate from filter logic)
(function() {
    const uniqueBtn = document.getElementById('show-all-unique');
    if (uniqueBtn) {
        uniqueBtn.addEventListener('click', function() {
            document.querySelectorAll('#unique-critiques-list .unique-hidden').forEach(el => {
                el.classList.remove('unique-hidden');
            });
            uniqueBtn.classList.add('hidden');
        });
    }
})();

// Sticky filter bar detection and visibility
(function() {
    const filterBar = document.getElementById('results-filter-bar');
    const discussionHeading = document.getElementById('discussion');
    if (!filterBar) return;

    // Use IntersectionObserver to detect when filter is stuck
    const sentinel = document.createElement('div');
    sentinel.style.height = '1px';
    sentinel.style.position = 'absolute';
    sentinel.style.top = '0';
    sentinel.style.left = '0';
    sentinel.style.right = '0';
    sentinel.style.pointerEvents = 'none';
    filterBar.parentNode.insertBefore(sentinel, filterBar);

    const stuckObserver = new IntersectionObserver(
        ([entry]) => {
            filterBar.classList.toggle('is-stuck', !entry.isIntersecting);
        },
        { threshold: 0, rootMargin: '-1px 0px 0px 0px' }
    );
    stuckObserver.observe(sentinel);

    // Hide filter bar when Discussion heading is at or above the top of viewport
    if (discussionHeading) {
        const hideObserver = new IntersectionObserver(
            ([entry]) => {
                // Hide when Discussion heading reaches the top of the viewport
                filterBar.classList.toggle('filter-hidden', entry.boundingClientRect.top <= 50);
            },
            { threshold: 0, rootMargin: '-50px 0px 0px 0px' }
        );
        hideObserver.observe(discussionHeading);
    }
})();

// Results section paper filter
(function() {
    const resultsFilterBar = document.getElementById('results-paper-filter');
    if (!resultsFilterBar) return;

    const resultsSections = document.querySelectorAll('.results-paper-section');
    let selectedResultsPaper = 'all';

    function updateResultsFilter(paper) {
        selectedResultsPaper = paper;

        // Update button states
        resultsFilterBar.querySelectorAll('.pill').forEach(p => {
            p.classList.toggle('active', p.dataset.value === paper);
        });

        // Show/hide sections
        resultsSections.forEach(section => {
            const sectionPaper = section.dataset.resultsPaper;
            if (sectionPaper === paper) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
            }
        });

        // Update URL without changing hash
        const params = new URLSearchParams(window.location.search);
        if (paper !== 'all') {
            params.set('results-paper', paper);
        } else {
            params.delete('results-paper');
        }
        const hash = window.location.hash || '';
        const queryString = params.toString();
        const newURL = window.location.pathname + (queryString ? '?' + queryString : '') + hash;
        history.replaceState(null, '', newURL);
    }

    // Handle clicks on filter pills
    resultsFilterBar.addEventListener('click', e => {
        if (e.target.classList.contains('pill')) {
            updateResultsFilter(e.target.dataset.value);
        }
    });

    // Load initial state from URL
    const params = new URLSearchParams(window.location.search);
    const initialPaper = params.get('results-paper') || 'all';
    if (initialPaper !== 'all') {
        updateResultsFilter(initialPaper);
    }
})();
