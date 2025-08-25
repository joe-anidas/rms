<!-- Modern RMS Dashboard -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/modern-rms.css'); ?>">

<div class="modern-content modern-fade-in">
    <!-- Modern Header -->
    <div class="modern-header">
        <div>
            <h1 class="modern-header-title">Dashboard</h1>
            <div class="modern-breadcrumb">
                <div class="modern-breadcrumb-item">
                    <a href="<?php echo base_url('dashboard'); ?>" class="modern-breadcrumb-link">Home</a>
                </div>
                <span class="modern-breadcrumb-separator">›</span>
                <div class="modern-breadcrumb-item">Dashboard</div>
            </div>
        </div>
        <div class="modern-header-actions">
            <button class="modern-btn modern-btn-outline" onclick="refreshDashboard()">
                <i class="fa fa-refresh"></i> Refresh
            </button>
            <button class="modern-btn modern-btn-primary" onclick="exportDashboard()">
                <i class="fa fa-download"></i> Export
            </button>
        </div>
    </div>

    <?php if (!isset($database_ready) || !$database_ready): ?>
        <!-- Database Setup Alert -->
        <div class="modern-alert modern-alert-warning">
            <div class="d-flex align-items-center">
                <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">Database Setup Required</h5>
                    <p class="mb-2">
                        <?php echo isset($installation_message) ? $installation_message : 'Database tables are not set up properly.'; ?>
                        <?php if (isset($error_message)): ?>
                            <br><strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                        <?php endif; ?>
                    </p>
                    <div class="mt-3">
                        <a href="<?php echo base_url('install/database_installer.php'); ?>" class="modern-btn modern-btn-primary">
                            <i class="fa fa-database"></i> Run Database Installer
                        </a>
                        <button class="modern-btn modern-btn-outline" onclick="window.location.reload()">
                            <i class="fa fa-refresh"></i> Retry Connection
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Key Performance Indicators -->
    <div class="modern-grid modern-grid-4 modern-mb-4">
        <!-- Properties KPI -->
        <div class="modern-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="modern-stat-value" style="color: white;"><?php echo $metrics['properties']['total']; ?></div>
                    <div class="modern-stat-label" style="color: rgba(255,255,255,0.8);">Total Properties</div>
                </div>
                <div style="opacity: 0.5;">
                    <i class="fa fa-building fa-3x"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between text-sm" style="color: rgba(255,255,255,0.9);">
                    <span><?php echo $metrics['properties']['by_status']['sold']; ?> Sold</span>
                    <span><?php echo $metrics['properties']['by_status']['booked']; ?> Booked</span>
                    <span><?php echo $metrics['properties']['by_status']['unsold']; ?> Available</span>
                </div>
                <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.2);">
                    <div class="progress-bar" style="background: rgba(255,255,255,0.8); width: <?php echo ($metrics['properties']['total'] > 0) ? ($metrics['properties']['by_status']['sold'] / $metrics['properties']['total']) * 100 : 0; ?>%;"></div>
                </div>
            </div>
        </div>

        <!-- Customers KPI -->
        <div class="modern-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="modern-stat-value" style="color: white;"><?php echo $metrics['customers']['total']; ?></div>
                    <div class="modern-stat-label" style="color: rgba(255,255,255,0.8);">Total Customers</div>
                </div>
                <div style="opacity: 0.5;">
                    <i class="fa fa-users fa-3x"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between text-sm" style="color: rgba(255,255,255,0.9);">
                    <span><?php echo $metrics['customers']['active']; ?> Active</span>
                    <span><?php echo $metrics['customers']['new_this_month']; ?> New</span>
                </div>
                <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.2);">
                    <div class="progress-bar" style="background: rgba(255,255,255,0.8); width: <?php echo ($metrics['customers']['total'] > 0) ? ($metrics['customers']['active'] / $metrics['customers']['total']) * 100 : 0; ?>%;"></div>
                </div>
            </div>
        </div>

        <!-- Revenue KPI -->
        <div class="modern-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="modern-stat-value" style="color: white;">₹<?php echo number_format($metrics['revenue']['total_collected'] / 100000, 1); ?>L</div>
                    <div class="modern-stat-label" style="color: rgba(255,255,255,0.8);">Revenue Collected</div>
                </div>
                <div style="opacity: 0.5;">
                    <i class="fa fa-money fa-3x"></i>
                </div>
            </div>
            <div class="mt-3">
                <?php 
                $total_potential = $metrics['revenue']['total_collected'] + $metrics['revenue']['pending'];
                $collection_percentage = $total_potential > 0 ? ($metrics['revenue']['total_collected'] / $total_potential) * 100 : 0;
                ?>
                <div class="d-flex justify-content-between text-sm" style="color: rgba(255,255,255,0.9);">
                    <span>₹<?php echo number_format($metrics['revenue']['pending'] / 100000, 1); ?>L Pending</span>
                    <span><?php echo round($collection_percentage, 1); ?>% Collected</span>
                </div>
                <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.2);">
                    <div class="progress-bar" style="background: rgba(255,255,255,0.8); width: <?php echo $collection_percentage; ?>%;"></div>
                </div>
            </div>
        </div>

        <!-- Staff KPI -->
        <div class="modern-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="modern-stat-value" style="color: white;"><?php echo $metrics['staff']['total']; ?></div>
                    <div class="modern-stat-label" style="color: rgba(255,255,255,0.8);">Total Staff</div>
                </div>
                <div style="opacity: 0.5;">
                    <i class="fa fa-user-tie fa-3x"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between text-sm" style="color: rgba(255,255,255,0.9);">
                    <span><?php echo $metrics['staff']['assigned']; ?> Assigned</span>
                    <span><?php echo $metrics['staff']['total'] - $metrics['staff']['assigned']; ?> Available</span>
                </div>
                <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.2);">
                    <div class="progress-bar" style="background: rgba(255,255,255,0.8); width: <?php echo ($metrics['staff']['total'] > 0) ? ($metrics['staff']['assigned'] / $metrics['staff']['total']) * 100 : 0; ?>%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="modern-grid modern-grid-2 modern-mb-4">
        <!-- Property Status Chart -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">Property Status Distribution</h3>
                <div class="d-flex gap-2">
                    <button class="modern-btn modern-btn-sm modern-btn-outline" onclick="toggleChartType('propertyChart', 'doughnut')">Pie</button>
                    <button class="modern-btn modern-btn-sm modern-btn-outline" onclick="toggleChartType('propertyChart', 'bar')">Bar</button>
                </div>
            </div>
            <div class="modern-card-body">
                <canvas id="propertyChart" height="300"></canvas>
            </div>
            <div class="modern-card-footer">
                <div class="modern-grid modern-grid-3">
                    <div class="modern-text-center">
                        <div class="modern-badge modern-badge-error"><?php echo $metrics['properties']['by_status']['unsold']; ?></div>
                        <div class="modern-text-sm modern-mt-1">Unsold</div>
                    </div>
                    <div class="modern-text-center">
                        <div class="modern-badge modern-badge-warning"><?php echo $metrics['properties']['by_status']['booked']; ?></div>
                        <div class="modern-text-sm modern-mt-1">Booked</div>
                    </div>
                    <div class="modern-text-center">
                        <div class="modern-badge modern-badge-success"><?php echo $metrics['properties']['by_status']['sold']; ?></div>
                        <div class="modern-text-sm modern-mt-1">Sold</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Trends Chart -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">Revenue Trends</h3>
                <div class="d-flex gap-2">
                    <button class="modern-btn modern-btn-sm modern-btn-outline active" onclick="setRevenueView('monthly')">Monthly</button>
                    <button class="modern-btn modern-btn-sm modern-btn-outline" onclick="setRevenueView('daily')">Daily</button>
                </div>
            </div>
            <div class="modern-card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
            <div class="modern-card-footer">
                <div class="modern-grid modern-grid-2">
                    <div class="modern-text-center">
                        <div class="modern-text-2xl modern-font-bold" style="color: var(--success-500);">₹<?php echo number_format($metrics['revenue']['total_collected']); ?></div>
                        <div class="modern-text-sm">Total Collected</div>
                    </div>
                    <div class="modern-text-center">
                        <div class="modern-text-2xl modern-font-bold" style="color: var(--warning-500);">₹<?php echo number_format($metrics['revenue']['pending']); ?></div>
                        <div class="modern-text-sm">Pending</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="modern-grid modern-grid-3 modern-mb-4">
        <!-- Recent Transactions -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">Recent Transactions</h3>
                <a href="<?php echo base_url('transactions'); ?>" class="modern-btn modern-btn-sm modern-btn-primary">View All</a>
            </div>
            <div class="modern-card-body">
                <?php if (isset($database_ready) && $database_ready): ?>
                    <div id="recentTransactions">
                        <div class="modern-loading">
                            <div class="modern-spinner"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="modern-text-center modern-text-sm" style="color: var(--secondary-500);">
                        <i class="fa fa-database fa-2x modern-mb-2"></i>
                        <p>Database setup required to view transactions</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">Quick Actions</h3>
            </div>
            <div class="modern-card-body">
                <div class="d-flex flex-column gap-3">
                    <a href="<?php echo base_url('properties/create'); ?>" class="modern-btn modern-btn-primary">
                        <i class="fa fa-plus"></i> Add Property
                    </a>
                    <a href="<?php echo base_url('customers/create'); ?>" class="modern-btn modern-btn-secondary">
                        <i class="fa fa-user-plus"></i> Add Customer
                    </a>
                    <a href="<?php echo base_url('registrations/create'); ?>" class="modern-btn modern-btn-success">
                        <i class="fa fa-file-text"></i> New Registration
                    </a>
                    <a href="<?php echo base_url('transactions/record-payment'); ?>" class="modern-btn modern-btn-warning">
                        <i class="fa fa-money"></i> Record Payment
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="modern-card-title">System Status</h3>
            </div>
            <div class="modern-card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Database</span>
                        <span class="modern-badge <?php echo (isset($database_ready) && $database_ready) ? 'modern-badge-success' : 'modern-badge-error'; ?>">
                            <?php echo (isset($database_ready) && $database_ready) ? 'Connected' : 'Error'; ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Properties</span>
                        <span class="modern-badge modern-badge-info"><?php echo $metrics['properties']['total']; ?> Total</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Customers</span>
                        <span class="modern-badge modern-badge-info"><?php echo $metrics['customers']['total']; ?> Total</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Staff</span>
                        <span class="modern-badge modern-badge-info"><?php echo $metrics['staff']['total']; ?> Total</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <?php if (isset($database_ready) && $database_ready): ?>
    <div class="modern-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Performance Overview</h3>
            <div class="d-flex gap-2">
                <button class="modern-btn modern-btn-sm modern-btn-outline" onclick="refreshPerformanceData()">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
                <a href="<?php echo base_url('reports'); ?>" class="modern-btn modern-btn-sm modern-btn-primary">
                    <i class="fa fa-chart-bar"></i> Detailed Reports
                </a>
            </div>
        </div>
        <div class="modern-card-body">
            <div class="modern-grid modern-grid-4">
                <div class="modern-text-center">
                    <?php 
                    $sales_rate = $metrics['properties']['total'] > 0 ? ($metrics['properties']['by_status']['sold'] / $metrics['properties']['total']) * 100 : 0;
                    ?>
                    <div class="modern-text-3xl modern-font-bold" style="color: var(--primary-600);"><?php echo round($sales_rate, 1); ?>%</div>
                    <div class="modern-text-sm">Sales Rate</div>
                    <div class="modern-stat-change <?php echo $sales_rate >= 50 ? 'positive' : 'negative'; ?>">
                        <i class="fa fa-<?php echo $sales_rate >= 50 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                        <?php echo $sales_rate >= 50 ? 'Good' : 'Needs Improvement'; ?>
                    </div>
                </div>
                <div class="modern-text-center">
                    <div class="modern-text-3xl modern-font-bold" style="color: var(--success-500);"><?php echo round($collection_percentage, 1); ?>%</div>
                    <div class="modern-text-sm">Collection Rate</div>
                    <div class="modern-stat-change <?php echo $collection_percentage >= 70 ? 'positive' : 'negative'; ?>">
                        <i class="fa fa-<?php echo $collection_percentage >= 70 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                        <?php echo $collection_percentage >= 70 ? 'Excellent' : 'Average'; ?>
                    </div>
                </div>
                <div class="modern-text-center">
                    <?php 
                    $staff_efficiency = $metrics['staff']['total'] > 0 ? ($metrics['staff']['assigned'] / $metrics['staff']['total']) * 100 : 0;
                    ?>
                    <div class="modern-text-3xl modern-font-bold" style="color: var(--info-500);"><?php echo round($staff_efficiency, 1); ?>%</div>
                    <div class="modern-text-sm">Staff Utilization</div>
                    <div class="modern-stat-change <?php echo $staff_efficiency >= 80 ? 'positive' : 'negative'; ?>">
                        <i class="fa fa-<?php echo $staff_efficiency >= 80 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                        <?php echo $staff_efficiency >= 80 ? 'Optimal' : 'Can Improve'; ?>
                    </div>
                </div>
                <div class="modern-text-center">
                    <?php 
                    $overall_score = ($sales_rate + $collection_percentage + $staff_efficiency) / 3;
                    ?>
                    <div class="modern-text-3xl modern-font-bold" style="color: var(--warning-500);"><?php echo round($overall_score, 1); ?>%</div>
                    <div class="modern-text-sm">Overall Score</div>
                    <div class="modern-stat-change <?php echo $overall_score >= 70 ? 'positive' : 'negative'; ?>">
                        <i class="fa fa-<?php echo $overall_score >= 70 ? 'trophy' : 'exclamation-triangle'; ?>"></i>
                        <?php echo $overall_score >= 80 ? 'Excellent' : ($overall_score >= 60 ? 'Good' : 'Needs Work'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modern JavaScript for Dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

function initializeDashboard() {
    <?php if (isset($database_ready) && $database_ready): ?>
        initializeCharts();
        loadRecentTransactions();
        startRealTimeUpdates();
    <?php else: ?>
        console.log('Database not ready, skipping chart initialization');
    <?php endif; ?>
}

function initializeCharts() {
    // Property Status Chart
    const propertyCtx = document.getElementById('propertyChart');
    if (propertyCtx) {
        new Chart(propertyCtx, {
            type: 'doughnut',
            data: {
                labels: ['Unsold', 'Booked', 'Sold'],
                datasets: [{
                    data: [
                        <?php echo $metrics['properties']['by_status']['unsold']; ?>,
                        <?php echo $metrics['properties']['by_status']['booked']; ?>,
                        <?php echo $metrics['properties']['by_status']['sold']; ?>
                    ],
                    backgroundColor: [
                        '#ef4444',
                        '#f59e0b',
                        '#10b981'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($metrics['revenue']['monthly'] ?? [], 'month')); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode(array_column($metrics['revenue']['monthly'] ?? [], 'revenue')); ?>,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + (value / 100000).toFixed(1) + 'L';
                            }
                        }
                    }
                }
            }
        });
    }
}

function loadRecentTransactions() {
    const container = document.getElementById('recentTransactions');
    if (!container) return;

    fetch('<?php echo base_url("dashboard/get_recent_transactions"); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.transactions) {
                let html = '';
                data.transactions.forEach(transaction => {
                    html += `
                        <div class="d-flex justify-content-between align-items-center modern-mb-2 p-2 rounded" style="background: var(--secondary-50);">
                            <div>
                                <div class="modern-font-medium">${transaction.customer_name}</div>
                                <div class="modern-text-sm" style="color: var(--secondary-600);">${transaction.payment_date}</div>
                            </div>
                            <div class="modern-badge modern-badge-success">₹${parseFloat(transaction.amount).toLocaleString()}</div>
                        </div>
                    `;
                });
                container.innerHTML = html || '<p class="modern-text-center modern-text-sm" style="color: var(--secondary-500);">No recent transactions</p>';
            } else {
                container.innerHTML = '<p class="modern-text-center modern-text-sm" style="color: var(--secondary-500);">Unable to load transactions</p>';
            }
        })
        .catch(error => {
            console.error('Error loading transactions:', error);
            container.innerHTML = '<p class="modern-text-center modern-text-sm" style="color: var(--error-500);">Error loading transactions</p>';
        });
}

function startRealTimeUpdates() {
    // Update dashboard every 5 minutes
    setInterval(function() {
        refreshDashboard();
    }, 300000);
}

function refreshDashboard() {
    // Show loading state
    const refreshBtn = document.querySelector('[onclick="refreshDashboard()"]');
    if (refreshBtn) {
        refreshBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Refreshing...';
        refreshBtn.disabled = true;
    }

    // Reload the page to get fresh data
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

function exportDashboard() {
    window.open('<?php echo base_url("dashboard/export_dashboard_data"); ?>', '_blank');
}

function toggleChartType(chartId, type) {
    // This would require chart.js instance management
    console.log('Toggle chart type:', chartId, type);
}

function setRevenueView(view) {
    // Update revenue chart view
    console.log('Set revenue view:', view);
    
    // Update button states
    document.querySelectorAll('[onclick*="setRevenueView"]').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

function refreshPerformanceData() {
    // Refresh performance metrics
    console.log('Refreshing performance data...');
    
    fetch('<?php echo base_url("dashboard/get_performance_score"); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Performance data updated:', data.data);
                // Update performance display
            }
        })
        .catch(error => {
            console.error('Error refreshing performance data:', error);
        });
}

// Utility functions
function formatCurrency(amount) {
    return '₹' + parseFloat(amount).toLocaleString('en-IN');
}

function formatNumber(number) {
    return parseFloat(number).toLocaleString('en-IN');
}
</script>

<style>
/* Additional dashboard-specific styles */
.progress {
    height: 4px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.align-items-center {
    align-items: center;
}

.align-items-start {
    align-items: flex-start;
}

.flex-column {
    flex-direction: column;
}

.gap-2 {
    gap: 0.5rem;
}

.gap-3 {
    gap: 1rem;
}

.text-sm {
    font-size: 0.875rem;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-3 {
    margin-top: 1rem;
}

.p-2 {
    padding: 0.5rem;
}

.rounded {
    border-radius: 0.375rem;
}

.active {
    background-color: var(--primary-600) !important;
    color: white !important;
}
</style>