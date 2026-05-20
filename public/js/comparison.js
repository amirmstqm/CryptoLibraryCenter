/**
 * comparison.js
 *
 * Manages library comparison functionality
 * - Library selection by name
 * - Comparison table generation (developer, languages, latest version,
 *   latest release, license, open source, github repository, pqc algorithm)
 * - Export functionality
 */

let allLibraries = [];
let selectedLibraries = []; // stores library names
const MAX_COMPARISON = 3;

/**
 * Initialize comparison module
 * @param {Array} libraries - Array of library objects
 */
export function initializeComparison(libraries) {
    allLibraries = [...libraries].sort((a, b) => a.name.localeCompare(b.name));
    setupComparisonModal();
    setupEventListeners();
}

/**
 * Setup comparison modal in DOM
 */
function setupComparisonModal() {
    const modal = document.getElementById('comparison-modal');
    if (!modal) {
        console.warn('Comparison modal not found in DOM');
        return;
    }
    renderLibraryList();
}

/**
 * Render available libraries
 */
function renderLibraryList() {
    const algorithmList = document.getElementById('algorithm-list');
    if (!algorithmList) return;

    algorithmList.innerHTML = allLibraries
        .map(lib => `
            <div class="algorithm-item" data-algorithm="${escapeAttr(lib.name)}">
                <div class="algorithm-name">${escapeHtml(lib.name)}</div>
            </div>
        `)
        .join('');

    algorithmList.querySelectorAll('.algorithm-item').forEach(item => {
        item.addEventListener('click', () => toggleLibrarySelection(item));
    });
}

/**
 * Toggle library selection
 */
function toggleLibrarySelection(element) {
    const libName = element.dataset.algorithm;
    const isSelected = element.classList.contains('selected');

    if (isSelected) {
        selectedLibraries = selectedLibraries.filter(n => n !== libName);
        element.classList.remove('selected');
    } else {
        if (selectedLibraries.length < MAX_COMPARISON) {
            selectedLibraries.push(libName);
            element.classList.add('selected');
        } else {
            showNotification('Maximum 3 libraries can be compared', 'warning');
            return;
        }
    }

    updateSelectedList();
    updateLibraryList();
    updateComparisonTable();
}

/**
 * Update selected libraries display
 */
function updateSelectedList() {
    const selectedList = document.getElementById('selected-list');
    const selectedCount = document.getElementById('selected-count');

    if (!selectedList || !selectedCount) return;

    selectedCount.textContent = selectedLibraries.length;

    selectedList.innerHTML = selectedLibraries
        .map(name => `
            <div class="selected-item" data-algorithm="${escapeAttr(name)}">
                <span class="selected-item-name">${escapeHtml(name)}</span>
                <button class="remove-btn" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `)
        .join('');

    selectedList.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const name = btn.closest('.selected-item').dataset.algorithm;
            selectedLibraries = selectedLibraries.filter(n => n !== name);

            const item = document.querySelector(`.algorithm-item[data-algorithm="${escapeAttr(name)}"]`);
            if (item) item.classList.remove('selected');

            updateSelectedList();
            updateLibraryList();
            updateComparisonTable();
        });
    });
}

/**
 * Update library list disabled state
 */
function updateLibraryList() {
    const algorithmList = document.getElementById('algorithm-list');
    if (!algorithmList) return;

    algorithmList.querySelectorAll('.algorithm-item').forEach(item => {
        const name = item.dataset.algorithm;
        const isSelected = selectedLibraries.includes(name);
        const canSelect = isSelected || selectedLibraries.length < MAX_COMPARISON;

        item.classList.toggle('selected', isSelected);
        item.classList.toggle('disabled', !canSelect && !isSelected);
        item.style.pointerEvents = (canSelect || isSelected) ? 'auto' : 'none';
    });
}

/**
 * Update comparison table based on selected libraries
 */
function updateComparisonTable() {
    const tableSection = document.getElementById('comparison-table-section');
    const table = document.getElementById('comparison-table');

    if (!tableSection || !table) return;

    if (selectedLibraries.length === 0) {
        tableSection.classList.add('hidden');
        return;
    }

    tableSection.classList.remove('hidden');
    table.innerHTML = buildComparisonTable();
}

/**
 * Build comparison table HTML
 */
function buildComparisonTable() {
    const features = getComparisonFeatures();

    // Header
    let html = '<thead><tr><th>Feature</th>';
    selectedLibraries.forEach(name => {
        html += `<th><div class="algorithm-header"><div class="algorithm-header-name">${escapeHtml(name)}</div></div></th>`;
    });
    html += '</tr></thead>';

    // Body
    html += '<tbody>';
    features.forEach(feature => {
        html += `<tr><td class="feature-name">${escapeHtml(feature.label)}</td>`;
        selectedLibraries.forEach(name => {
            const lib = allLibraries.find(l => l.name === name);
            const raw = lib ? getRawValue(lib, feature.key) : null;
            html += `<td class="feature-value">${formatFeatureValue(feature.key, raw)}</td>`;
        });
        html += '</tr>';
    });
    html += '</tbody>';

    return html;
}

/**
 * Features to compare
 */
function getComparisonFeatures() {
    return [
        { label: 'Developer',          key: 'developer' },
        { label: 'Languages',          key: 'language' },
        { label: 'Latest Version',     key: 'latest-version' },
        { label: 'Latest Release',     key: 'latest-release' },
        { label: 'License',            key: 'license' },
        { label: 'Open Source',        key: 'open-source' },
        { label: 'GitHub Repository',  key: 'github' },
        { label: 'PQC Algorithm',      key: 'pqcAlgorithms' },
    ];
}

/**
 * Extract raw value from a library object for a given feature key
 */
function getRawValue(lib, key) {
    if (key === 'pqcAlgorithms') {
        return lib.pqcAlgorithms && lib.pqcAlgorithms.length > 0
            ? lib.pqcAlgorithms
            : null;
    }
    const value = lib[key];
    return (value === undefined || value === null || value === '') ? null : value;
}

/**
 * Format a raw value for display in the comparison table
 */
function formatFeatureValue(key, value) {
    if (value === null || value === undefined) {
        return '<span class="badge pending">N/A</span>';
    }

    if (key === 'open-source') {
        return value === true || value === 'true' || value === 1
            ? '<span class="badge yes">Yes</span>'
            : '<span class="badge no">No</span>';
    }

    if (key === 'github') {
        const url = String(value).trim();
        if (!url) return '<span class="badge pending">N/A</span>';
        return `<a href="${escapeAttr(url)}" target="_blank" rel="noopener noreferrer" class="github-link">
                    <i class="fab fa-github"></i> View Repository
                </a>`;
    }

    if (key === 'pqcAlgorithms' && Array.isArray(value)) {
        return value.map(alg => `<span class="badge pqc-badge">${escapeHtml(alg)}</span>`).join(' ');
    }

    return escapeHtml(String(value));
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    const modal = document.getElementById('comparison-modal');
    const closeBtn = document.getElementById('comparison-close-btn');
    const searchInput = document.getElementById('comparison-search');
    const resetBtn = document.getElementById('reset-comparison-btn');
    const exportBtn = document.getElementById('export-comparison-btn');

    if (closeBtn) closeBtn.addEventListener('click', closeComparisonModal);
    if (searchInput) searchInput.addEventListener('input', handleLibrarySearch);
    if (resetBtn) resetBtn.addEventListener('click', resetComparison);
    if (exportBtn) exportBtn.addEventListener('click', exportComparison);

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeComparisonModal();
        });
    }
}

/**
 * Handle library search
 */
function handleLibrarySearch(e) {
    const term = e.target.value.toLowerCase();
    const algorithmList = document.getElementById('algorithm-list');
    if (!algorithmList) return;

    algorithmList.querySelectorAll('.algorithm-item').forEach(item => {
        const name = item.dataset.algorithm.toLowerCase();
        const dev = item.querySelector('.algorithm-type')?.textContent.toLowerCase() || '';
        item.style.display = (name.includes(term) || dev.includes(term)) ? '' : 'none';
    });
}

/**
 * Reset comparison
 */
function resetComparison() {
    selectedLibraries = [];
    document.querySelectorAll('.algorithm-item').forEach(item => item.classList.remove('selected'));
    updateSelectedList();
    updateLibraryList();
    updateComparisonTable();
    const searchInput = document.getElementById('comparison-search');
    if (searchInput) searchInput.value = '';
}

/**
 * Export comparison as CSV
 */
function exportComparison() {
    if (selectedLibraries.length === 0) {
        showNotification('No libraries selected for export', 'warning');
        return;
    }

    const features = getComparisonFeatures();
    const header = ['Feature', ...selectedLibraries].join(',');
    const rows = features.map(feature => {
        const cells = selectedLibraries.map(name => {
            const lib = allLibraries.find(l => l.name === name);
            const raw = lib ? getRawValue(lib, feature.key) : null;
            if (raw === null) return 'N/A';
            if (Array.isArray(raw)) return `"${raw.join(', ')}"`;
            return `"${String(raw).replace(/"/g, '""')}"`;
        });
        return [feature.label, ...cells].join(',');
    });

    const csv = [header, ...rows].join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `library-comparison-${Date.now()}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);

    showNotification('Comparison exported successfully', 'success');
}

/**
 * Open comparison modal
 */
export function openComparisonModal() {
    const modal = document.getElementById('comparison-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('active');
    }
}

/**
 * Close comparison modal
 */
function closeComparisonModal() {
    const modal = document.getElementById('comparison-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('active');
    }
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#dcfce7' : type === 'warning' ? '#fef3c7' : '#dbeafe'};
        color: ${type === 'success' ? '#166534' : type === 'warning' ? '#92400e' : '#0c4a6e'};
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/* ── Helpers ──────────────────────────────────────────────── */

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function escapeAttr(str) {
    return String(str).replace(/"/g, '&quot;');
}
