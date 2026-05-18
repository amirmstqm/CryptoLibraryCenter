let libraries = [];
const libraryCardsContainer = document.getElementById("library-cards");

export function setLibraries(data) {
  libraries = data;
  applyFilters(); // Initial render
}

function applyFilters() {
  const searchInput = document.getElementById("search-input");
  const filterCheckboxes = document.querySelectorAll(".pqc-filter");
  const languageCheckboxes = document.querySelectorAll(".language-filter");
  const licenseFilter = document.getElementById("filter-license");
  const openSourceFilter = document.getElementById("filter-open-source");
  const pqcSupportedCheckboxes = document.querySelectorAll(".pqc-supported-filter");

  const query = searchInput ? searchInput.value.toLowerCase().trim() : "";

  // Show/hide the clear-search × button
  const clearSearch = document.getElementById("clear-search");
  if (clearSearch) clearSearch.style.display = query ? "flex" : "none";

  const selectedFilters = Array.from(filterCheckboxes)
    .filter((cb) => cb.checked)
    .map((cb) => cb.value.toLowerCase());

  const selectedLanguages = Array.from(languageCheckboxes)
    .filter((cb) => cb.checked)
    .map((cb) => cb.value.toLowerCase());

  const selectedPqcSupported = Array.from(pqcSupportedCheckboxes)
    .filter((cb) => cb.checked)
    .map((cb) => cb.value.toLowerCase());

  const selectedLicense = licenseFilter ? licenseFilter.value : "";
  const isOpenSource = openSourceFilter ? openSourceFilter.checked : false;

  const filtered = libraries.filter((lib) => {
    // Search across name, developer, language, and PQC algorithms
    const nameMatch =
      !query ||
      lib.name.toLowerCase().includes(query) ||
      (lib.developer && lib.developer.toLowerCase().includes(query)) ||
      (lib.language && lib.language.toLowerCase().includes(query)) ||
      lib.pqcAlgorithms?.some((alg) => alg.toLowerCase().includes(query));

    const algoMatch =
      selectedFilters.length === 0 ||
      selectedFilters.some((filter) =>
        lib.pqcAlgorithms?.some((alg) => alg.toLowerCase().includes(filter))
      );

    const languageMatch =
      selectedLanguages.length === 0 ||
      (lib.language &&
        selectedLanguages.some((lang) =>
          lib.language.toLowerCase().includes(lang)
        ));

    const licenseMatch =
      !selectedLicense ||
      (lib.license &&
        lib.license.toLowerCase().includes(selectedLicense.toLowerCase()));

    const openSourceMatch = !isOpenSource || lib["open-source"] === true;

    const hasPqcUnsupported =
      lib.pqcAlgorithms &&
      lib.pqcAlgorithms.some((alg) =>
        alg.toLowerCase().includes("pqc unsupported")
      );

    let pqcSupportedMatch = selectedPqcSupported.length === 0;
    if (selectedPqcSupported.includes("yes")) {
      pqcSupportedMatch = pqcSupportedMatch || !hasPqcUnsupported;
    }
    if (selectedPqcSupported.includes("no")) {
      pqcSupportedMatch = pqcSupportedMatch || hasPqcUnsupported;
    }

    return nameMatch && algoMatch && languageMatch && licenseMatch && openSourceMatch && pqcSupportedMatch;
  });

  // Sort
  const sortSelect = document.getElementById("sort-select");
  const sortVal = sortSelect ? sortSelect.value : "az";
  filtered.sort((a, b) =>
    sortVal === "za"
      ? b.name.localeCompare(a.name)
      : a.name.localeCompare(b.name)
  );

  renderLibraries(filtered);
}

function renderLibraries(libs) {
  if (!libraryCardsContainer) return;

  // Update result count
  const resultCount = document.getElementById("result-count");
  if (resultCount) {
    resultCount.textContent = `Showing ${libs.length} of ${libraries.length} ${libraries.length === 1 ? 'library' : 'libraries'}`;
  }

  libraryCardsContainer.innerHTML = libs
    .map(
      (lib) => `
        <div class="feature-card"
             data-kyber="${lib.pqcAlgorithms?.includes("Kyber") || false}"
             data-dilithium="${lib.pqcAlgorithms?.includes("Dilithium") || false}"
             data-sphincs="${lib.pqcAlgorithms?.includes("SPHINCS+") || false}"
             data-falcon="${lib.pqcAlgorithms?.includes("Falcon") || false}"
             data-type="${lib.pqcAlgorithms?.length ? "pqc" : "classic"}"
             data-language="${lib.language || ""}"
             data-license="${lib.license || ""}"
             data-open-source="${lib["open-source"] || false}"
             data-name="${lib.normalizedName || lib.name.toLowerCase()}">

            <h3>${lib.name}</h3>
            <div class="library-details">
                <p><strong>Developer:</strong> ${lib.developer || "N/A"}</p>
                <p><strong>Languages:</strong> ${lib.language || "N/A"}</p>
                <p><strong>Latest Version:</strong> ${lib["latest-version"] || "N/A"} (${lib["latest-release"] || "N/A"})</p>
                <p><strong>License:</strong> ${lib.license || "N/A"}</p>
                <p><strong>Open Source:</strong> ${lib["open-source"] ? "Yes" : "No"}</p>
            </div>
            ${
              lib.pqcAlgorithms?.length
                ? `<div class="algorithm-badges">
                    ${lib.pqcAlgorithms.map((alg) => `<span class="algorithm-badge">${alg}</span>`).join("")}
                   </div>`
                : ""
            }
        </div>
      `
    )
    .join("");

  // Navigate to Laravel details route on card click
  document.querySelectorAll(".feature-card").forEach((card) => {
    const libId = libraries.find(
      (lib) => lib.name === card.querySelector("h3").textContent
    )?.id;

    if (libId) {
      card.addEventListener("click", () => {
        // Laravel route: /libraries/details?id=xxx
        window.location.href = `/libraries/details?id=${libId}`;
      });
    }
  });
}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("search-input");
  const clearSearch = document.getElementById("clear-search");
  const clearFilters = document.getElementById("clear-filters");
  const sortSelect = document.getElementById("sort-select");
  const filterCheckboxes = document.querySelectorAll(".pqc-filter");
  const languageCheckboxes = document.querySelectorAll(".language-filter");
  const licenseFilter = document.getElementById("filter-license");
  const openSourceFilter = document.getElementById("filter-open-source");
  const pqcSupportedCheckboxes = document.querySelectorAll(".pqc-supported-filter");

  if (searchInput) searchInput.addEventListener("input", applyFilters);
  if (sortSelect) sortSelect.addEventListener("change", applyFilters);

  if (clearSearch) {
    clearSearch.style.display = "none";
    clearSearch.addEventListener("click", () => {
      searchInput.value = "";
      clearSearch.style.display = "none";
      applyFilters();
      searchInput.focus();
    });
  }

  if (clearFilters) {
    clearFilters.addEventListener("click", () => {
      if (searchInput) searchInput.value = "";
      document.querySelectorAll(".pqc-filter, .language-filter, .pqc-supported-filter").forEach(cb => cb.checked = false);
      if (licenseFilter) licenseFilter.value = "";
      if (openSourceFilter) openSourceFilter.checked = false;
      if (sortSelect) sortSelect.value = "az";
      if (clearSearch) clearSearch.style.display = "none";
      applyFilters();
    });
  }

  filterCheckboxes.forEach((cb) => cb.addEventListener("change", applyFilters));
  languageCheckboxes.forEach((cb) => cb.addEventListener("change", applyFilters));
  pqcSupportedCheckboxes.forEach((cb) => cb.addEventListener("change", applyFilters));
  if (licenseFilter) licenseFilter.addEventListener("change", applyFilters);
  if (openSourceFilter) openSourceFilter.addEventListener("change", applyFilters);
});
