<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($status_code) ? $status_code : '500'; ?> - Error | RMS</title>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap5.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/modern-theme.css" rel="stylesheet">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--danger-50) 0%, var(--danger-100) 100%);
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
            font-size: 4rem;
            font-weight: 700;
            color: var(--danger-600);
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
        .error-icon {
            font-size: 3rem;
            color: var(--danger-500);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-card">
            <div class="error-icon">
                <i class="zmdi zmdi-alert-triangle"></i>
            </div>
            <div class="error-code"><?php echo isset($status_code) ? htmlspecialchars($status_code) : '500'; ?></div>
            <h1 class="error-title"><?php echo isset($heading) ? htmlspecialchars($heading) : 'An Error Occurred'; ?></h1>
            <p class="error-message">
                <?php echo isset($message) ? htmlspecialchars($message) : 'We apologize for the inconvenience. Please try again later or contact support if the problem persists.'; ?>
            </p>
            <div class="error-actions">
                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
                    <i class="zmdi zmdi-home me-2"></i>Go to Dashboard
                </a>
                <button onclick="location.reload()" class="btn btn-outline-primary">
                    <i class="zmdi zmdi-refresh me-2"></i>Try Again
                </button>
            </div>
            <?php if (isset($request_id)): ?>
                <div class="mt-3">
                    <small class="text-muted">Request ID: <?php echo htmlspecialchars($request_id); ?></small>
                </div>
            <?php endif; ?>
            <?php if (isset($timestamp)): ?>
                <div class="mt-1">
                    <small class="text-muted">Time: <?php echo htmlspecialchars($timestamp); ?></small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>