    </div> <!-- End padding div -->
<?php if (isset($_SESSION['user_id'])): ?>
</div> <!-- End main-content -->
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>
<footer style="margin-left: 260px; padding: 20px 30px; text-align: center; background: white; border-top: 1px solid #e0e0e0; color: #7f8c8d;">
    <p style="margin: 0;">&copy; <?= date('Y') ?> Medi-Care Health Information System. All rights reserved.</p>
</footer>
<?php endif; ?>

</body>
</html>