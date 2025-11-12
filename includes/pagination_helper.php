<?php
/**
 * Pagination Helper Function
 * Generates pagination HTML for tables
 */
function renderPagination($page, $total_pages, $total_items, $items_per_page, $offset) {
    if ($total_pages <= 1) {
        return '';
    }
    
    $query_params = $_GET;
    
    ob_start();
    ?>
    <div class="pagination">
        <div class="pagination-controls">
            <a href="?<?= http_build_query(array_merge($query_params, ['page' => 1])) ?>" class="pagination-btn" <?= $page <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                <i class="fas fa-angle-double-left"></i>
            </a>
            <a href="?<?= http_build_query(array_merge($query_params, ['page' => max(1, $page - 1)])) ?>" class="pagination-btn" <?= $page <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                <i class="fas fa-angle-left"></i>
            </a>
            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            for ($i = $start_page; $i <= $end_page; $i++):
            ?>
                <a href="?<?= http_build_query(array_merge($query_params, ['page' => $i])) ?>" class="pagination-btn <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <a href="?<?= http_build_query(array_merge($query_params, ['page' => min($total_pages, $page + 1)])) ?>" class="pagination-btn" <?= $page >= $total_pages ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                <i class="fas fa-angle-right"></i>
            </a>
            <a href="?<?= http_build_query(array_merge($query_params, ['page' => $total_pages])) ?>" class="pagination-btn" <?= $page >= $total_pages ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                <i class="fas fa-angle-double-right"></i>
            </a>
        </div>
        <div class="pagination-info" style="margin-top: 1rem; text-align: center; color: var(--text-secondary); font-size: 0.875rem;">
            Showing <?= $offset + 1 ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> items
        </div>
    </div>
    <?php
    return ob_get_clean();
}

