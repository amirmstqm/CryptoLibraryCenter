/**
 * comparison.js
 * 
 * Manages algorithm comparison functionality
 * - Algorithm selection
 * - Comparison table generation
 * - Export functionality
 */

let allAlgorithms = [];
let selectedAlgorithms = [];
const MAX_COMPARISON = 2;

/**
 * Initialize comparison module
 * @param {Array} libraries - Array of library objects
 */
export function initializeComparison(libraries) {
    extractAlgorithms(libraries);
    setupComparisonModal();
    setupEventListeners();
}

/**
 * Extract unique algorithms from libraries
 */
function extractAlgorithms(libraries) {
    const algorithms = new Map();

    libraries.forEach(lib => {
        if (lib.pqcAlgorithms && Array.isArray(lib.pqcAlgorithms)) {
            lib.pqcAlgorithms.forEach(alg => {
                if (!algorithms.has(alg)) {
                    algorithms.set(alg, {
                        name: alg,
                        type: getAlgorithmType(alg),
                        libraries: []
                    });
                }
                algorithms.get(alg).libraries.push({
                    name: lib.name,
                    developer: lib.developer,
                    language: lib.language,
                    version: lib['latest-version'],
                    release: lib['latest-release']
                });
            });
        }
    });

    allAlgorithms = Array.from(algorithms.values()).sort((a, b) => 
        a.name.localeCompare(b.name)
    );
}

/**
 * Determine algorithm type (Key Encapsulation, Signature, etc.)
 */
function getAlgorithmType(algName) {
    const name = algName.toLowerCase();
    
    if (name.includes('kyber') || name.includes('ntru')) return 'KEM';
    if (name.includes('dilithium') || name.includes('sphincs') || name.includes('falcon')) return 'Signature';
    if (name.includes('pqc unsupported')) return 'Classic';
    
    return 'PQC';
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

    renderAlgorithmList();
}

/**
 * Render available algorithms
 */
function renderAlgorithmList() {
    const algorithmList = document.getElementById('algorithm-list');
    if (!algorithmList) return;

    algorithmList.innerHTML = allAlgorithms
        .map(alg => `
            <div class="algorithm-item" data-algorithm="${alg.name}">
                <div class="algorithm-name">${alg.name}</div>
                <div class="algorithm-type">${alg.type}</div>
            </div>
        `)
        .join('');

    // Add click handlers
    algorithmList.querySelectorAll('.algorithm-item').forEach(item => {
        item.addEventListener('click', () => toggleAlgorithmSelection(item));
    });
}

/**
 * Toggle algorithm selection
 */
function toggleAlgorithmSelection(element) {
    const algorithmName = element.dataset.algorithm;
    const isSelected = element.classList.contains('selected');

    if (isSelected) {
        // Deselect
        selectedAlgorithms = selectedAlgorithms.filter(a => a !== algorithmName);
        element.classList.remove('selected');
    } else {
        // Select (if under limit)
        if (selectedAlgorithms.length < MAX_COMPARISON) {
            selectedAlgorithms.push(algorithmName);
            element.classList.add('selected');
        } else {
            showNotification('Maximum 2 algorithms can be compared', 'warning');
            return;
        }
    }

    updateSelectedList();
    updateAlgorithmList();
    updateComparisonTable();
}

/**
 * Update selected algorithms display
 */
function updateSelectedList() {
    const selectedList = document.getElementById('selected-list');
    const selectedCount = document.getElementById('selected-count');

    if (!selectedList || !selectedCount) return;

    selectedCount.textContent = selectedAlgorithms.length;

    selectedList.innerHTML = selectedAlgorithms
        .map(alg => `
            <div class="selected-item" data-algorithm="${alg}">
                <span class="selected-item-name">${alg}</span>
                <button class="remove-btn" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `)
        .join('');

    // Add remove handlers
    selectedList.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const alg = btn.closest('.selected-item').dataset.algorithm;
            selectedAlgorithms = selectedAlgorithms.filter(a => a !== alg);
            
            // Unselect in algorithm list
            const item = document.querySelector(`.algorithm-item[data-algorithm="${alg}"]`);
            if (item) item.classList.remove('selected');
            
            updateSelectedList();
            updateAlgorithmList();
            updateComparisonTable();
        });
    });
}

/**
 * Update algorithm list disabled state
 */
function updateAlgorithmList() {
    const algorithmList = document.getElementById('algorithm-list');
    if (!algorithmList) return;

    algorithmList.querySelectorAll('.algorithm-item').forEach(item => {
        const algName = item.dataset.algorithm;
        const isSelected = selectedAlgorithms.includes(algName);
        const canSelect = isSelected || selectedAlgorithms.length < MAX_COMPARISON;

        item.classList.toggle('selected', isSelected);
        item.classList.toggle('disabled', !canSelect && !isSelected);
        item.style.pointerEvents = canSelect || isSelected ? 'auto' : 'none';
    });
}

/**
 * Update comparison table based on selected algorithms
 */
function updateComparisonTable() {
    const tableSection = document.getElementById('comparison-table-section');
    const table = document.getElementById('comparison-table');

    if (!tableSection || !table) return;

    // Show/hide table section
    if (selectedAlgorithms.length === 0) {
        tableSection.classList.add('hidden');
        return;
    }

    tableSection.classList.remove('hidden');

    // Build comparison table
    const features = getComparisonFeatures();
    const html = buildComparisonTable(features);
    table.innerHTML = html;
}

/**
 * Get features to compare
 */
function getComparisonFeatures() {
    return [
        { name: 'Algorithm Type', key: 'type', category: 'general' },
        { name: 'Key Size', key: 'keySize', category: 'security' },
        { name: 'Signature Size', key: 'signatureSize', category: 'security' },
        { name: 'Performance', key: 'performance', category: 'performance' },
        { name: 'NIST Status', key: 'nistStatus', category: 'standardization' },
        { name: 'Security Level', key: 'securityLevel', category: 'security' },
        { name: 'Resource Usage', key: 'resourceUsage', category: 'performance' },
        { name: 'Libraries Count', key: 'librariesCount', category: 'availability' },
        { name: 'Active Development', key: 'activeDev', category: 'maintenance' },
        { name: 'Community Support', key: 'community', category: 'support' }
    ];
}

/**
 * Build comparison table HTML
 */
function buildComparisonTable(features) {
    // Header
    let html = '<thead><tr><th>Feature</th>';
    selectedAlgorithms.forEach(alg => {
        const algData = allAlgorithms.find(a => a.name === alg);
        html += `<th>
            <div class="algorithm-header">
                <div class="algorithm-header-name">${alg}</div>
                <div class="algorithm-header-type">${algData?.type || 'PQC'}</div>
            </div>
        </th>`;
    });
    html += '</tr></thead>';

    // Body
    html += '<tbody>';
    features.forEach(feature => {
        html += `<tr><td class="feature-name">${feature.name}</td>`;
        selectedAlgorithms.forEach(alg => {
            const value = getFeatureValue(alg, feature.key);
            html += `<td class="feature-value">${formatFeatureValue(value)}</td>`;
        });
        html += '</tr>';
    });
    html += '</tbody>';

    return html;
}

/**
 * Get feature value for an algorithm
 */
function getFeatureValue(algorithmName, featureKey) {
    const algorithmFeatures = {
        'Kyber': {
            type: 'KEM (Key Encapsulation)',
            keySize: '768-1568 bytes',
            signatureSize: 'N/A',
            performance: 'Fast',
            nistStatus: 'Standardized (FIPS 203)',
            securityLevel: '256-bit',
            resourceUsage: 'Low-Medium',
            librariesCount: '15+',
            activeDev: 'Yes',
            community: 'Excellent'
        },
        'Dilithium': {
            type: 'Digital Signature',
            keySize: '1312-2544 bytes',
            signatureSize: '2044-4595 bytes',
            performance: 'Fast',
            nistStatus: 'Standardized (FIPS 204)',
            securityLevel: '128-256-bit',
            resourceUsage: 'Low-Medium',
            librariesCount: '18+',
            activeDev: 'Yes',
            community: 'Excellent'
        },
        'SPHINCS+': {
            type: 'Digital Signature (Stateless)',
            keySize: '32-64 bytes',
            signatureSize: '17,088-35,664 bytes',
            performance: 'Slow',
            nistStatus: 'Standardized (FIPS 205)',
            securityLevel: '128-256-bit',
            resourceUsage: 'Medium',
            librariesCount: '12+',
            activeDev: 'Yes',
            community: 'Good'
        },
        'Falcon': {
            type: 'Digital Signature',
            keySize: '897-1793 bytes',
            signatureSize: '666-1280 bytes',
            performance: 'Very Fast',
            nistStatus: 'Standardized (FIPS 205)',
            securityLevel: '128-256-bit',
            resourceUsage: 'Low',
            librariesCount: '8+',
            activeDev: 'Moderate',
            community: 'Good'
        },
        'NTRU': {
            type: 'KEM (Key Encapsulation)',
            keySize: '600 bytes',
            signatureSize: 'N/A',
            performance: 'Very Fast',
            nistStatus: 'Finalist',
            securityLevel: '128-256-bit',
            resourceUsage: 'Very Low',
            librariesCount: '10+',
            activeDev: 'Yes',
            community: 'Good'
        }
    };

    return algorithmFeatures[algorithmName]?.[featureKey] || 'N/A';
}

/**
 * Format feature value for display
 */
function formatFeatureValue(value) {
    if (value === 'N/A') return `<span class="badge pending">N/A</span>`;
    if (value === 'Yes') return `<span class="badge yes">Yes</span>`;
    if (value === 'No') return `<span class="badge no">No</span>`;
    
    // Check for performance indicators
    if (['Very Fast', 'Fast', 'Slow', 'Very Slow'].includes(value)) {
        const badgeClass = value === 'Very Fast' || value === 'Fast' ? 'yes' : 'pending';
        return `<span class="badge ${badgeClass}">${value}</span>`;
    }

    return value;
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

    if (closeBtn) {
        closeBtn.addEventListener('click', closeComparisonModal);
    }

    if (searchInput) {
        searchInput.addEventListener('input', handleAlgorithmSearch);
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', resetComparison);
    }

    if (exportBtn) {
        exportBtn.addEventListener('click', exportComparison);
    }

    // Close on outside click
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeComparisonModal();
            }
        });
    }
}

/**
 * Handle algorithm search
 */
function handleAlgorithmSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    const algorithmList = document.getElementById('algorithm-list');

    if (!algorithmList) return;

    algorithmList.querySelectorAll('.algorithm-item').forEach(item => {
        const algName = item.dataset.algorithm.toLowerCase();
        const isMatch = algName.includes(searchTerm);
        item.style.display = isMatch ? 'block' : 'none';
    });
}

/**
 * Reset comparison
 */
function resetComparison() {
    selectedAlgorithms = [];
    document.querySelectorAll('.algorithm-item').forEach(item => {
        item.classList.remove('selected');
    });
    updateSelectedList();
    updateAlgorithmList();
    updateComparisonTable();
    const searchInput = document.getElementById('comparison-search');
    if (searchInput) searchInput.value = '';
}

/**
 * Export comparison as CSV
 */
function exportComparison() {
    if (selectedAlgorithms.length === 0) {
        showNotification('No algorithms selected for export', 'warning');
        return;
    }

    const features = getComparisonFeatures();
    let csv = 'Feature,' + selectedAlgorithms.join(',') + '\n';

    features.forEach(feature => {
        const row = [feature.name];
        selectedAlgorithms.forEach(alg => {
            row.push(getFeatureValue(alg, feature.key));
        });
        csv += row.join(',') + '\n';
    });

    // Download
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `algorithm-comparison-${new Date().getTime()}.csv`;
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
    // Simple notification (can be enhanced with a toast library)
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
