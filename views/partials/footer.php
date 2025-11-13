    </div> <!-- End content-container -->
<?php if (isset($_SESSION['user_id'])): ?>
</div> <!-- End main-content -->
<?php endif; ?>
</div> <!-- End app-container -->

<script>
// User Menu Dropdown - Moved to sidebar

// Dark Mode Toggle
function toggleDarkMode(event) {
    event.stopPropagation();
    const html = document.documentElement;
    const toggle = document.getElementById('darkModeToggle');
    const currentTheme = html.getAttribute('data-theme');
    
    if (currentTheme === 'dark') {
        html.setAttribute('data-theme', 'light');
        toggle.classList.remove('active');
        localStorage.setItem('theme', 'light');
    } else {
        html.setAttribute('data-theme', 'dark');
        toggle.classList.add('active');
        localStorage.setItem('theme', 'dark');
    }
}

// Initialize dark mode from localStorage
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    const html = document.documentElement;
    const toggle = document.getElementById('darkModeToggle');
    
    html.setAttribute('data-theme', savedTheme);
    if (savedTheme === 'dark') {
        toggle.classList.add('active');
    }
});

// Header Button Functions
function openSearch() {
    alert('Search functionality coming soon!');
}

function openNotifications() {
    alert('Notifications panel coming soon!');
}
</script>

<script src="/public/js/confirm-modal.js"></script>

</body>
</html>