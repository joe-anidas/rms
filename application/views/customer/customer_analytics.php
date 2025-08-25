<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <!-- Analytics Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-chart-bar mr-2"></i>Customer Analytics Dashboard</h5>
                    <div class="card-action">
                        <button class="btn btn-primary btn-sm" onclick="refreshAnalytics()">
                            <i class="fa fa-refresh mr-1"></i>Refresh Data
                        </button>
                        <button class="btn btn-success btn-sm" onclick="exportAnalytics()">
                            <i class="fa fa-download mr-1"></i>Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fa fa-users fa-3x mb-3"></i>
                    <h2 id="totalCustomers"><?php echo isset($statistics['total_customers']) ? $statistics['total_customers'] : '0'; ?></h2>
                    <p class="mb-0">Total Customers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fa fa-user-check fa-3x mb-3"></i>
                    <h2 id="activeCustomers"><?php echo isset($statistics['active_customers']) ? $statistics['active_customers'] : '0'; ?></h2>
                    <p class="mb-0">Active Customers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fa fa-rupee fa-3x mb-3"></i>
                    <h2 id="avgInvestment">₹<?php echo isset($statistics['average_investment']) ? number_format($statistics['average_investment'], 0) : '0'; ?></h2>
                    <p class="mb-0">Avg Investment</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fa fa-calendar fa-3x mb-3"></i>
                    <h2 id="monthlyGrowth">+<?php echo isset($statistics['acquisition_trends']) ? count($statistics['acquisition_trends']) : '0'; ?>%</h2>
                    <p class="mb-0">Monthly Growth</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Customer Status Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-pie-chart mr-2"></i>Customer Status Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Geographic Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-map-marker mr-2"></i>Geographic Distribution (Top 10)</h6>
                </div>
                <div class="card-body">
                    <canvas id="geoChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Acquisition Trends -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-line-chart mr-2"></i>Customer Acquisition Trends (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <canvas id="acquisitionChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-star mr-2"></i>Top Customers by Investment</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Properties</th>
                                    <th>Total Investment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="topCustomersTable">
                                <?php if(isset($statistics['top_customers']) && !empty($statistics['top_customers'])): ?>
                                    <?php foreach($statistics['top_customers'] as $index => $customer): ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-<?php echo $index < 3 ? 'gold' : 'secondary'; ?>">
                                                    #<?php echo $index + 1; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($customer->plot_buyer_name); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($customer->phone_number_1); ?></td>
                                            <td>
                                                <span class="badge badge-info"><?php echo $customer->properties_count; ?></span>
                                            </td>
                                            <td>
                                                <strong>₹<?php echo number_format($customer->total_investment, 2); ?></strong>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="viewCustomerProfile(<?php echo $customer->id; ?>)">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No customer data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-info-circle mr-2"></i>Customer Insights</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Key Statistics</h6>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-success"></i> <strong><?php echo isset($statistics['active_customers']) ? $statistics['active_customers'] : '0'; ?></strong> customers have active properties</li>
                                <li><i class="fa fa-map-marker text-info"></i> Customers from <strong><?php echo isset($statistics['geographic_distribution']) ? count($statistics['geographic_distribution']) : '0'; ?></strong> different districts</li>
                                <li><i class="fa fa-calendar text-warning"></i> <strong><?php echo isset($statistics['acquisition_trends']) ? array_sum(array_column($statistics['acquisition_trends'], 'count')) : '0'; ?></strong> customers acquired in last 12 months</li>
                                <li><i class="fa fa-rupee text-success"></i> Average investment per customer: <strong>₹<?php echo isset($statistics['average_investment']) ? number_format($statistics['average_investment'], 2) : '0'; ?></strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-cogs mr-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo base_url('customers/create'); ?>" class="btn btn-primary btn-block">
                            <i class="fa fa-plus mr-2"></i>Add New Customer
                        </a>
                        <a href="<?php echo base_url('customers'); ?>" class="btn btn-info btn-block">
                            <i class="fa fa-list mr-2"></i>View All Customers
                        </a>
                        <button class="btn btn-success btn-block" onclick="exportCustomerReport()">
                            <i class="fa fa-download mr-2"></i>Export Customer Report
                        </button>
                        <button class="btn btn-warning btn-block" onclick="generateCustomerInsights()">
                            <i class="fa fa-lightbulb-o mr-2"></i>Generate Insights
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data from PHP
const statisticsData = <?php echo json_encode($statistics); ?>;

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Customer Status Distribution Chart
    if (statisticsData.status_distribution) {
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusLabels = statisticsData.status_distribution.map(item => item.customer_status.charAt(0).toUpperCase() + item.customer_status.slice(1));
        const statusData = statisticsData.status_distribution.map(item => item.count);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Geographic Distribution Chart
    if (statisticsData.geographic_distribution) {
        const geoCtx = document.getElementById('geoChart').getContext('2d');
        const geoLabels = statisticsData.geographic_distribution.map(item => item.district);
        const geoData = statisticsData.geographic_distribution.map(item => item.count);
        
        new Chart(geoCtx, {
            type: 'bar',
            data: {
                labels: geoLabels,
                datasets: [{
                    label: 'Customers',
                    data: geoData,
                    backgroundColor: '#007bff',
                    borderColor: '#0056b3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Customer Acquisition Trends Chart
    if (statisticsData.acquisition_trends) {
        const acquisitionCtx = document.getElementById('acquisitionChart').getContext('2d');
        const acquisitionLabels = statisticsData.acquisition_trends.map(item => {
            const date = new Date(item.month + '-01');
            return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        });
        const acquisitionData = statisticsData.acquisition_trends.map(item => item.count);
        
        new Chart(acquisitionCtx, {
            type: 'line',
            data: {
                labels: acquisitionLabels,
                datasets: [{
                    label: 'New Customers',
                    data: acquisitionData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
}

// Refresh analytics data
function refreshAnalytics() {
    showLoading();
    fetch('<?php echo base_url('customers/analytics'); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload(); // Simple reload for now
            } else {
                alert('Error refreshing analytics data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error refreshing analytics data');
        })
        .finally(() => {
            hideLoading();
        });
}

// Export analytics report
function exportAnalytics() {
    window.location.href = '<?php echo base_url('customers/export_analytics'); ?>';
}

// Export customer report
function exportCustomerReport() {
    window.location.href = '<?php echo base_url('customers/export_report'); ?>';
}

// Generate customer insights
function generateCustomerInsights() {
    alert('Customer insights feature will be implemented in the next version');
}

// View customer profile
function viewCustomerProfile(customerId) {
    window.location.href = `<?php echo base_url('customers/profile/'); ?>${customerId}`;
}

// Show loading indicator
function showLoading() {
    // Add loading overlay
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loadingOverlay';
    loadingDiv.className = 'loading-overlay';
    loadingDiv.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
    document.body.appendChild(loadingDiv);
}

// Hide loading indicator
function hideLoading() {
    const loadingDiv = document.getElementById('loadingOverlay');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}
</script>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.badge-gold {
    background-color: #ffd700;
    color: #000;
}

.card-action {
    margin-left: auto;
}

.d-grid {
    display: grid;
}

.gap-2 {
    gap: 0.5rem;
}

.btn-block {
    width: 100%;
    margin-bottom: 0.5rem;
}
</style>