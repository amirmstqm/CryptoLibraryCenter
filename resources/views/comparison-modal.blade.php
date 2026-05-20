<!-- Comparison Modal -->
<div id="comparison-modal" class="comparison-modal hidden">
    <div class="comparison-modal-content">
        <div class="comparison-modal-header">
            <h2>Algorithm Comparison</h2>
            <button class="comparison-close-btn" id="comparison-close-btn">&times;</button>
        </div>

        <div class="comparison-modal-body">
            <!-- Library Selection Section -->
            <div class="comparison-selection">
                <div class="selection-header">
                    <h3>Select Libraries to Compare</h3>
                    <p class="selection-hint">Choose up to 3 libraries for comparison</p>
                </div>

                <div class="algorithm-selector">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="comparison-search" placeholder="Search libraries...">
                    </div>
                    <div id="algorithm-list" class="algorithm-list">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <div class="selected-algorithms">
                    <h4>Selected for Comparison (<span id="selected-count">0</span>/3)</h4>
                    <div id="selected-list" class="selected-list">
                        <!-- Selected libraries will appear here -->
                    </div>
                </div>
            </div>

            <!-- Comparison Table Section -->
            <div id="comparison-table-section" class="comparison-table-section hidden">
                <div class="comparison-toolbar">
                    <h3>Feature Comparison</h3>
                    <div class="toolbar-buttons">
                        <button id="reset-comparison-btn" class="btn-secondary">
                            <i class="fas fa-redo"></i> Start Over
                        </button>
                        <button id="export-comparison-btn" class="btn-secondary">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>

                <div class="comparison-table-wrapper">
                    <table id="comparison-table" class="comparison-table">
                        <!-- Table populated by JavaScript -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
