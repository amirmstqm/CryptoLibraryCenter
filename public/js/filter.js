import { initializeComparison, openComparisonModal } from './comparison.js';

// Export openComparisonModal to window so the compare button can use it
window.openComparisonModal = openComparisonModal;

// -----------------------------------------------
// Initialise comparison using data injected by Laravel
// -----------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    if (window.__libraries__ && Array.isArray(window.__libraries__)) {
        initializeComparison(window.__libraries__);
    }

    const searchInput            = document.getElementById('search-input');
    const clearSearch            = document.getElementById('clear-search');
    const clearFilters           = document.getElementById('clear-filters');
    const sortSelect             = document.getElementById('sort-select');
    const filterCheckboxes       = document.querySelectorAll('.pqc-filter');
    const languageCheckboxes     = document.querySelectorAll('.language-filter');
    const pqcSupportedCheckboxes = document.querySelectorAll('.pqc-supported-filter');

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (sortSelect) sortSelect.addEventListener('change', applyFilters);

    if (clearSearch) {
        clearSearch.style.display = 'none';
        clearSearch.addEventListener('click', () => {
            searchInput.value = '';
            clearSearch.style.display = 'none';
            applyFilters();
            searchInput.focus();
        });
    }

    if (clearFilters) {
        clearFilters.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            document.querySelectorAll('.pqc-filter, .language-filter, .pqc-supported-filter')
                .forEach(cb => (cb.checked = false));
            if (sortSelect) sortSelect.value = 'az';
            if (clearSearch) clearSearch.style.display = 'none';
            applyFilters();
        });
    }

    filterCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));
    languageCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));
    pqcSupportedCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));

    // Initial count render
    applyFilters();
});

// -----------------------------------------------
// Filter + sort server-rendered cards in the DOM
// -----------------------------------------------
function applyFilters() {
    const searchInput            = document.getElementById('search-input');
    const sortSelect             = document.getElementById('sort-select');
    const filterCheckboxes       = document.querySelectorAll('.pqc-filter');
    const languageCheckboxes     = document.querySelectorAll('.language-filter');
    const pqcSupportedCheckboxes = document.querySelectorAll('.pqc-supported-filter');
    const clearSearch            = document.getElementById('clear-search');

    const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

    if (clearSearch) clearSearch.style.display = query ? 'flex' : 'none';

    const selectedAlgos = Array.from(filterCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value.toLowerCase());

    const selectedLanguages = Array.from(languageCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value.toLowerCase());

    const selectedPqcSupported = Array.from(pqcSupportedCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value.toLowerCase());

    const container = document.getElementById('library-cards');
    if (!container) return;

    const cards = Array.from(container.querySelectorAll('.feature-card'));

    // Determine visibility
    const visible = [];
    cards.forEach(card => {
        const name       = card.dataset.name       || '';
        const developer  = card.dataset.developer  || '';
        const language   = card.dataset.language   || '';
        const pqcRaw     = card.dataset.pqcAlgorithms || '';   // "kyber,dilithium"
        const pqcAlgs    = pqcRaw ? pqcRaw.split(',').map(s => s.trim()) : [];
        const isOpenSource = card.dataset.openSource === 'true';
        const hasPqcUnsupported = pqcAlgs.some(a => a.includes('pqc unsupported'));

        // Search match (name, developer, language, pqc algorithms)
        const nameMatch =
            !query ||
            name.includes(query) ||
            developer.includes(query) ||
            language.toLowerCase().includes(query) ||
            pqcAlgs.some(a => a.includes(query));

        // PQC algorithm checkbox filter
        const algoMatch =
            selectedAlgos.length === 0 ||
            selectedAlgos.some(sel => pqcAlgs.some(a => a.includes(sel)));

        // Language checkbox filter
        const languageMatch =
            selectedLanguages.length === 0 ||
            selectedLanguages.some(lang => language.toLowerCase().includes(lang));

        // PQC supported filter (yes / no checkboxes)
        let pqcSupportedMatch = selectedPqcSupported.length === 0;
        if (selectedPqcSupported.includes('yes')) {
            pqcSupportedMatch = pqcSupportedMatch || !hasPqcUnsupported;
        }
        if (selectedPqcSupported.includes('no')) {
            pqcSupportedMatch = pqcSupportedMatch || hasPqcUnsupported;
        }

        const show = nameMatch && algoMatch && languageMatch && pqcSupportedMatch;
        card.style.display = show ? '' : 'none';

        if (show) visible.push(card);
    });

    // Sort visible cards
    const sortVal = sortSelect ? sortSelect.value : 'az';
    visible.sort((a, b) => {
        const na = a.dataset.name || '';
        const nb = b.dataset.name || '';
        return sortVal === 'za' ? nb.localeCompare(na) : na.localeCompare(nb);
    });
    visible.forEach(card => container.appendChild(card));

    // Update result count
    const resultCount = document.getElementById('result-count');
    if (resultCount) {
        const total = cards.length;
        resultCount.textContent =
            `Showing ${visible.length} of ${total} ${total === 1 ? 'library' : 'libraries'}`;
    }
}

