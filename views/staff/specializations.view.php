<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/staff/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Specializations</span>
        </div>
        <h1 class="page-title">Browse Specializations</h1>
    </div>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<!-- Search and Filter Bar -->
<div class="search-filter-bar-modern">
    <button type="button" class="filter-toggle-btn" onclick="toggleFilterSidebar()">
        <i class="fas fa-filter"></i>
        <span>Filter</span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <form method="GET" style="flex: 1; display: flex; align-items: center; gap: 0.75rem;">
        <div class="search-input-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="search-input-modern" 
                   value="<?= htmlspecialchars($search_query ?? '') ?>" 
                   placeholder="Search Specialization...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
    </div>
</div>

<!-- Specializations List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Specializations</h2>
    </div>
    <?php if (empty($specializations)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="empty-state-text">No specializations found.</div>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; padding: 1.5rem;">
            <?php foreach ($specializations as $spec): ?>
                <div class="card" style="margin: 0;">
                    <div class="card-body">
                        <h3 style="margin: 0 0 0.75rem 0; color: var(--primary-blue); font-size: 1.125rem;"><?= htmlspecialchars($spec['spec_name']) ?></h3>
                        <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0.75rem 0;">
                            <?= htmlspecialchars($spec['spec_description'] ?? 'No description available') ?>
                        </p>
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);">
                            <p style="margin: 0.5rem 0; font-size: 0.875rem; color: var(--text-secondary);">
                                <strong>Doctors:</strong> <?= isset($spec['doctor_count']) ? $spec['doctor_count'] : 0 ?>
                            </p>
                            <?php if (isset($spec['doctor_count']) && $spec['doctor_count'] > 0): ?>
                                <a href="/staff/specialization-doctors?id=<?= $spec['spec_id'] ?>" class="btn btn-sm" style="margin-top: 0.75rem;">
                                    <i class="fas fa-user-md"></i>
                                    <span>Browse <?= htmlspecialchars($spec['spec_name']) ?> Doctors</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-controls">
                <button class="pagination-btn" disabled>
                    <i class="fas fa-angle-double-left"></i>
                </button>
                <button class="pagination-btn" disabled>
                    <i class="fas fa-angle-left"></i>
                </button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">
                    <i class="fas fa-angle-right"></i>
                </button>
                <button class="pagination-btn">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Category tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryTabs = document.querySelectorAll('.category-tab');
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const category = this.dataset.category;
            filterByCategory(category);
        });
    });
});

function filterByCategory(category) {
    if (category === 'all') {
        window.location.href = '/staff/specializations';
    }
}

// Listen for filter events
window.addEventListener('filtersApplied', function(e) {
    const filters = e.detail;
    console.log('Applying filters:', filters);
    // Implement filter logic
});
</script>

<?php require_once __DIR__ . '/../partials/filter-sidebar.php'; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
