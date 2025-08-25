<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | RMS</title>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap5.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/modern-theme.css" rel="stylesheet">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
        }
        .error-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: var(--primary-600);
            line-height: 1;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        .error-message {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary {
            background: var(--primary-600);
            border-color: var(--primary-600);
        }
        .btn-outline-primary {
            color: var(--primary-600);
            border-color: var(--primary-600);
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-card">
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-message">
                <?php echo isset($message) ? htmlspecialchars($message) : 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.'; ?>
            </p>
            <div class="error-actions">
                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
                    <i class="zmdi zmdi-home me-2"></i>Go to Dashboard
                </a>
                <button onclick="history.back()" class="btn btn-outline-primary">
                    <i class="zmdi zmdi-arrow-left me-2"></i>Go Back
                </button>
            </div>
            <?php if (isset($request_id)): ?>
                <div class="mt-3">
                    <small class="text-muted">Request ID: <?php echo htmlspecialchars($request_id); ?></small>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto-redirect after 10 seconds
        setTimeout(function() {
            if (confirm('Would you like to be redirected to the dashboard?')) {
                window.location.href = '<?php echo base_url('dashboard'); ?>';
            }
        }, 10000);
    </script>
</body>
</html>