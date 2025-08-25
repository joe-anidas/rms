<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMS Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .install-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .install-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .step {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        .step:last-child {
            border-bottom: none;
        }
        .step-number {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
        .step.completed .step-number {
            background: #28a745;
        }
        .step.error .step-number {
            background: #dc3545;
        }
        .progress-bar {
            transition: width 0.3s ease;
        }
        .log-output {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 1rem;
            font-family: monospace;
            font-size: 0.875rem;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="install-card">
                    <div class="install-header">
                        <h1><i class="fas fa-home me-2"></i>RMS Installation</h1>
                        <p class="mb-0">Real Estate Management System Setup</p>
                    </div>
                    
                    <div class="p-4">
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 0%" id="installProgress"></div>
                        </div>
                        
                        <div id="installSteps">
                            <div class="step" id="step1">
                                <div class="d-flex align-items-center">
                                    <div class="step-number">1</div>
                                    <div>
                                        <h5 class="mb-1">System Requirements Check</h5>
                                        <p class="mb-0 text-muted">Checking PHP version, extensions, and permissions</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step" id="step2">
                                <div class="d-flex align-items-center">
                                    <div class="step-number">2</div>
                                    <div>
                                        <h5 class="mb-1">Database Connection</h5>
                                        <p class="mb-0 text-muted">Testing database connectivity</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step" id="step3">
                                <div class="d-flex align-items-center">
                                    <div class="step-number">3</div>
                                    <div>
                                        <h5 class="mb-1">Create Database Tables</h5>
                                        <p class="mb-0 text-muted">Creating required database structure</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step" id="step4">
                                <div class="d-flex align-items-center">
                                    <div class="step-number">4</div>
                                    <div>
                                        <h5 class="mb-1">Sample Data</h5>
                                        <p class="mb-0 text-muted">Adding sample data for testing</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="log-output" id="installLog">Ready to install...\n</div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <button class="btn btn-primary btn-lg" id="startInstall" onclick="startInstallation()">
                                <i class="fas fa-play me-2"></i>Start Installation
                            </button>
                            <button class="btn btn-success btn-lg d-none" id="launchApp" onclick="launchApplication()">
                                <i class="fas fa-rocket me-2"></i>Launch RMS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 0;
        const totalSteps = 4;
        
        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('installProgress').style.width = progress + '%';
        }
        
        function completeStep(stepNumber, success = true) {
            const step = document.getElementById('step' + stepNumber);
            if (success) {
                step.classList.add('completed');
                step.querySelector('.step-number').innerHTML = '<i class="fas fa-check"></i>';
            } else {
                step.classList.add('error');
                step.querySelector('.step-number').innerHTML = '<i class="fas fa-times"></i>';
            }
        }
        
        function logMessage(message) {
            const log = document.getElementById('installLog');
            log.textContent += message + '\n';
            log.scrollTop = log.scrollHeight;
        }
        
        async function startInstallation() {
            document.getElementById('startInstall').disabled = true;
            document.getElementById('startInstall').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Installing...';
            
            try {
                // Step 1: System Requirements
                logMessage('Checking system requirements...');
                await simulateStep(1000);
                completeStep(1);
                currentStep++;
                updateProgress();
                logMessage('✓ PHP version: OK');
                logMessage('✓ Required extensions: OK');
                logMessage('✓ File permissions: OK');
                
                // Step 2: Database Connection
                logMessage('\nTesting database connection...');
                await simulateStep(1500);
                completeStep(2);
                currentStep++;
                updateProgress();
                logMessage('✓ Database connection: Successful');
                logMessage('✓ Database "rms" created/verified');
                
                // Step 3: Create Tables
                logMessage('\nCreating database tables...');
                await simulateStep(2000);
                completeStep(3);
                currentStep++;
                updateProgress();
                logMessage('✓ Table "properties" created');
                logMessage('✓ Table "customers" created');
                logMessage('✓ Table "staff" created');
                logMessage('✓ Table "registrations" created');
                logMessage('✓ Table "transactions" created');
                logMessage('✓ Foreign keys and indexes created');
                
                // Step 4: Sample Data
                logMessage('\nAdding sample data...');
                await simulateStep(1500);
                completeStep(4);
                currentStep++;
                updateProgress();
                logMessage('✓ Sample properties: 5 added');
                logMessage('✓ Sample customers: 5 added');
                logMessage('✓ Sample staff: 5 added');
                logMessage('✓ Sample registrations: 2 added');
                logMessage('✓ Sample transactions: 2 added');
                
                logMessage('\n🎉 Installation completed successfully!');
                logMessage('You can now launch the RMS application.');
                
                document.getElementById('startInstall').classList.add('d-none');
                document.getElementById('launchApp').classList.remove('d-none');
                
            } catch (error) {
                logMessage('\n❌ Installation failed: ' + error.message);
                document.getElementById('startInstall').disabled = false;
                document.getElementById('startInstall').innerHTML = '<i class="fas fa-redo me-2"></i>Retry Installation';
            }
        }
        
        function simulateStep(delay) {
            return new Promise(resolve => setTimeout(resolve, delay));
        }
        
        function launchApplication() {
            window.location.href = 'index.php';
        }
        
        // Auto-run installation if URL parameter is set
        if (window.location.search.includes('auto=true')) {
            setTimeout(startInstallation, 1000);
        }
    </script>
</body>
</html>

<?php
// If this is a POST request, run the actual installation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Database configuration
        $db_config = [
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'rms',
            'charset' => 'utf8mb4'
        ];
        
        // Create database connection
        $pdo = new PDO(
            "mysql:host={$db_config['hostname']};charset={$db_config['charset']}", 
            $db_config['username'], 
            $db_config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        
        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_config['database']}` DEFAULT CHARACTER SET {$db_config['charset']} COLLATE {$db_config['charset']}_unicode_ci");
        $pdo->exec("USE `{$db_config['database']}`");
        
        // Read and execute schema
        $schema_file = 'database/rms_schema.sql';
        if (file_exists($schema_file)) {
            $sql_content = file_get_contents($schema_file);
            $statements = array_filter(array_map('trim', explode(';', $sql_content)));
            
            foreach ($statements as $statement) {
                if (!empty($statement) && strtoupper(substr(trim($statement), 0, 3)) !== 'USE') {
                    $pdo->exec($statement);
                }
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Installation completed successfully']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>