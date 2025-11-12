<!-- Filter Sidebar -->
<div class="filter-sidebar" id="filterSidebar">
    <div class="filter-sidebar-header">
        <h3 class="filter-sidebar-title">Filters</h3>
        <button type="button" class="filter-sidebar-close" onclick="toggleFilterSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Marital Status Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('maritalStatus')">
            <h4 class="filter-section-title">Marital status</h4>
            <button type="button" class="filter-section-toggle" id="maritalStatusToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="maritalStatusContent">
            <div class="filter-radio-group">
                <div class="filter-radio-item">
                    <input type="radio" name="marital_status" id="marital_single" value="single">
                    <label for="marital_single">Single</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="marital_status" id="marital_married" value="married">
                    <label for="marital_married">Married</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="marital_status" id="marital_divorce" value="divorce">
                    <label for="marital_divorce">Divorce</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gender Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('gender')">
            <h4 class="filter-section-title">Gender</h4>
            <button type="button" class="filter-section-toggle" id="genderToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="genderContent">
            <div class="filter-radio-group">
                <div class="filter-radio-item">
                    <input type="radio" name="gender" id="gender_male" value="male">
                    <label for="gender_male">Male</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="gender" id="gender_female" value="female">
                    <label for="gender_female">Female</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Country Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('country')">
            <h4 class="filter-section-title">Country</h4>
            <button type="button" class="filter-section-toggle" id="countryToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="countryContent">
            <input type="text" class="filter-search-input" placeholder="Search Country" id="countrySearch">
            <div class="filter-radio-group" id="countryList">
                <!-- Countries will be populated dynamically or via PHP -->
                <div class="filter-radio-item">
                    <input type="radio" name="country" id="country_all" value="">
                    <label for="country_all">All Countries</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Age Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('age')">
            <h4 class="filter-section-title">Age</h4>
            <button type="button" class="filter-section-toggle" id="ageToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="ageContent">
            <input type="range" class="filter-range-slider" id="ageRange" min="1" max="100" value="20">
            <div class="filter-range-label">
                <span>1</span>
                <span id="ageValue">20</span>
                <span>100</span>
            </div>
        </div>
    </div>
    
    <!-- Filter Actions -->
    <div class="filter-sidebar-actions">
        <button type="button" class="filter-clear-btn" onclick="clearAllFilters()">Clear all</button>
        <button type="button" class="filter-apply-btn" onclick="applyFilters()">Apply all filter</button>
    </div>
</div>

<script>
function toggleFilterSidebar() {
    const sidebar = document.getElementById('filterSidebar');
    const mainContent = document.querySelector('.main-content');
    const filterBtn = document.querySelector('.filter-toggle-btn');
    
    sidebar.classList.toggle('active');
    if (mainContent) {
        mainContent.classList.toggle('filter-active');
    }
    if (filterBtn) {
        filterBtn.classList.toggle('active');
    }
}

function toggleFilterSection(sectionId) {
    const content = document.getElementById(sectionId + 'Content');
    const toggle = document.getElementById(sectionId + 'Toggle');
    
    if (content && toggle) {
        content.classList.toggle('collapsed');
        const icon = toggle.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-chevron-up');
            icon.classList.toggle('fa-chevron-down');
        }
    }
}

function clearAllFilters() {
    // Clear all radio buttons
    document.querySelectorAll('.filter-sidebar input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Reset age slider
    const ageSlider = document.getElementById('ageRange');
    if (ageSlider) {
        ageSlider.value = 20;
        updateAgeValue();
    }
    
    // Clear country search
    const countrySearch = document.getElementById('countrySearch');
    if (countrySearch) {
        countrySearch.value = '';
    }
}

function applyFilters() {
    // Collect filter values
    const filters = {
        marital_status: document.querySelector('input[name="marital_status"]:checked')?.value || '',
        gender: document.querySelector('input[name="gender"]:checked')?.value || '',
        country: document.querySelector('input[name="country"]:checked')?.value || '',
        age: document.getElementById('ageRange')?.value || '20'
    };
    
    // Apply filters (this will be implemented per view)
    console.log('Applying filters:', filters);
    
    // Close sidebar
    toggleFilterSidebar();
    
    // Trigger custom event for views to handle
    window.dispatchEvent(new CustomEvent('filtersApplied', { detail: filters }));
}

// Update age value display
function updateAgeValue() {
    const slider = document.getElementById('ageRange');
    const display = document.getElementById('ageValue');
    if (slider && display) {
        display.textContent = slider.value;
    }
}

// Initialize age slider
document.addEventListener('DOMContentLoaded', function() {
    const ageSlider = document.getElementById('ageRange');
    if (ageSlider) {
        ageSlider.addEventListener('input', updateAgeValue);
        updateAgeValue();
    }
    
    // Country search functionality
    const countrySearch = document.getElementById('countrySearch');
    if (countrySearch) {
        countrySearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const countryItems = document.querySelectorAll('#countryList .filter-radio-item');
            countryItems.forEach(item => {
                const label = item.querySelector('label');
                if (label) {
                    const text = label.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
                }
            });
        });
    }
});
</script>

