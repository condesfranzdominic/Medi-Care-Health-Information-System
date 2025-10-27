<main style="padding:40px;text-align:center;">
    <h3>Login to Medi-Care System</h3>
    
    <?php if (isset($error) && !empty($error)): ?>
        <div class="alert alert-error" style="margin: 15px auto; max-width: 400px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="" style="max-width: 400px; margin: 0 auto;">
        <div class="form-group">
            <input type="email" name="email" placeholder="Email" required style="width: 100%;">
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required style="width: 100%;">
        </div>
        <button type="submit" class="btn btn-success">Login</button>
    </form>
    <p style="margin-top: 20px;"><a href="/">Back to Home</a></p>
</main>