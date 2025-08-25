<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Financial Analytics</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Financial Analytics</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <button type="button" class="btn btn-outline-primary" onclick="refreshAnalytics()">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="form-inline">
                            <div class="form-group mr-3">
                                <label for="start_date" class="mr-2">From:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="<?php echo isset($date_range['start']) ? $date_range['start'] : ''; ?>">
                            </div>
                            <div class="form-group mr-3">
                                <label for="end_date" class="mr-2">To:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="<?php echo isset($date_range['end']) ? $date_range['end'] : ''; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo base_url('dashboard/financial_analytics'); ?>" class="btn btn-secondary ml-2">Clear</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Overview Cards -->
        <?php if (!empty($analytics['forecast'])): ?>
        <div class="row">
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card gradient-deepblue">
                    <div class="card-body text-white">
                        <h5 class="mb-0">₹<?php echo number_format($analytics['forecast']['potential_revenue'], 0); ?></h5>
                        <p class="mb-0">Potential Revenue</p>
                        <small><?php echo $analytics['forecast']['pending_registrations']; ?> pending registrations</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card gradient-orange">
                    <div class="card-body text-white">
                        <h5 class="mb-0">₹<?php echo number_format($analytics['forecast']['average_outstanding'], 0); ?></h5>
                        <p class="mb-0">Average Outstanding</p>
                        <small>Per registration</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card gradient-ohhappiness">
                    <div class="card-body text-white">
                        <h5 class="mb-0"><?php echo count($analytics['outstanding']); ?></h5>
                        <p class="mb-0">Outstanding Payments</p>
                        <small>Requiring attention</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card gradient-ibiza">
                    <div class="card-body text-white">
                        <h5 class="mb-0"><?php echo count($analytics['payment_methods']); ?></h5>
                        <p class="mb-0">Payment Methods</p>
                        <small>In use</small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Revenue Trends and Payment Methods -->
        <div class="row">
            <!-- Revenue Trends -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Daily Revenue Trends</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueTrendsChart" height="400"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Payment Methods Distribution</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentMethodsChart" height="400"></canvas>
                        
                        <div class="mt-3">
                            <?php if (!empty($analytics['payment_methods'])): ?>
                                <?php foreach ($analytics['payment_methods'] as $method): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-capitalize"><?php echo str_replace('_', ' ', $method['payment_method']); ?></span>
                                        <div>
                                            <span class="badge badge-primary"><?php echo $method['transaction_count']; ?></span>
                                            <span class="badge badge-success">₹<?php echo number_format($method['total_amount'], 0); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Types Analysis -->
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Payment Types Analysis</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentTypesChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Payment Type</th>
                                            <th>Count</th>
                                            <th>Total Amount</th>
                                            <th>Average</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($analytics['payment_types'])): ?>
                                            <?php foreach ($analytics['payment_types'] as $type): ?>
                                                <tr>
                                                    <td class="text-capitalize"><?php echo str_replace('_', ' ', $type['payment_type']); ?></td>
                                                    <td><?php echo $type['transaction_count']; ?></td>
                                                    <td>₹<?php echo number_format($type['total_amount'], 0); ?></td>
                                                    <td>₹<?php echo number_format($type['average_amount'], 0); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Outstanding Payments Summary -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Outstanding Payments Summary</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="outstandingChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <h6>Top Outstanding Payments:</h6>
                            <?php if (!empty($analytics['outstanding'])): ?>
                                <?php foreach (array_slice($analytics['outstanding'], 0, 5) as $outstanding): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                        <div>
                                            <strong><?php echo htmlspecialchars($outstanding['customer_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo $outstanding['registration_number']; ?></small>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-danger">₹<?php echo number_format($outstanding['outstanding_amount'], 0); ?></span><br>
                                            <small class="text-muted"><?php echo $outstanding['days_outstanding']; ?> days</small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No outstanding payments</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outstanding Payments Table -->
        <?php if (!empty($analytics['outstanding'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Detailed Outstanding Payments</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="outstandingTable">
                                <thead>
                                    <tr>
                                        <th>Registration #</th>
                                        <th>Customer</th>
                                        <th>Property</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Outstanding</th>
                                        <th>Days Outstanding</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($analytics['outstanding'] as $payment): ?>
                                        <tr>
                                            <td><?php echo $payment['registration_number']; ?></td>
                                            <td><?php echo htmlspecialchars($payment['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($payment['property_name']); ?></td>
                                            <td>₹<?php echo number_format($payment['total_amount'], 0); ?></td>
                                            <td>₹<?php echo number_format($payment['paid_amount'], 0); ?></td>
                                            <td class="text-danger">₹<?php echo number_format($payment['outstanding_amount'], 0); ?></td>
                                            <td>
                                                <span class="badge <?php echo $payment['days_outstanding'] > 30 ? 'badge-danger' : ($payment['days_outstanding'] > 15 ? 'badge-warning' : 'badge-info'); ?>">
                                                    <?php echo $payment['days_outstanding']; ?> days
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="recordPayment('<?php echo $payment['registration_number']; ?>')">
                                                    Record Payment
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Analytics data from PHP
const financialData = <?php echo json_encode($analytics); ?>;

// Revenue Trends Chart
const revenueCtx = document.getElementById('revenueTrendsChart').getContext('2d');
const revenueLabels = financialData.revenue_trends.map(item => item.date);
const revenueAmounts = financialData.revenue_trends.map(item => parseFloat(item.daily_revenue));

const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Daily Revenue',
            data: revenueAmounts,
            borderColor: '#36a2eb',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Payment Methods Chart
const methodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
const methodsChart = new Chart(methodsCtx, {
    type: 'doughnut',
    data: {
        labels: financialData.payment_methods.map(item => item.payment_method.replace('_', ' ').toUpperCase()),
        datasets: [{
            data: financialData.payment_methods.map(item => item.total_amount),
            backgroundColor: [
                '#ff6384',
                '#36a2eb',
                '#ffcd56',
                '#4bc0c0',
                '#9966ff'
            ],
            borderWidth: 2,
            borderColor: '#fff'
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

// Payment Types Chart
const typesCtx = document.getElementById('paymentTypesChart').getContext('2d');
const typesChart = new Chart(typesCtx, {
    type: 'bar',
    data: {
        labels: financialData.payment_types.map(item => item.payment_type.replace('_', ' ').toUpperCase()),
        datasets: [{
            label: 'Total Amount',
            data: financialData.payment_types.map(item => item.total_amount),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Outstanding Payments Chart
const outstandingCtx = document.getElementById('outstandingChart').getContext('2d');

// Group outstanding by days ranges
const dayRanges = {
    '0-15 days': 0,
    '16-30 days': 0,
    '31-60 days': 0,
    '60+ days': 0
};

financialData.outstanding.forEach(item => {
    const days = parseInt(item.days_outstanding);
    if (days <= 15) dayRanges['0-15 days']++;
    else if (days <= 30) dayRanges['16-30 days']++;
    else if (days <= 60) dayRanges['31-60 days']++;
    else dayRanges['60+ days']++;
});

const outstandingChart = new Chart(outstandingCtx, {
    type: 'pie',
    data: {
        labels: Object.keys(dayRanges),
        datasets: [{
            data: Object.values(dayRanges),
            backgroundColor: [
                '#4bc0c0',
                '#ffcd56',
                '#ff9f40',
                '#ff6384'
            ],
            borderWidth: 2,
            borderColor: '#fff'
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

function refreshAnalytics() {
    location.reload();
}

function recordPayment(registrationNumber) {
    // This would redirect to the payment recording page
    window.location.href = '<?php echo base_url("transactions/record_payment"); ?>?registration=' + registrationNumber;
}
</script>

<style>
.gradient-deepblue {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
}

.gradient-orange {
    background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
}

.gradient-ohhappiness {
    background: linear-gradient(45deg, #00b09b 0%, #96c93d 100%);
}

.gradient-ibiza {
    background: linear-gradient(45deg, #ee0979 0%, #ff6a00 100%);
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid #eee;
    padding: 15px 20px;
}

.card-title {
    margin: 0;
    font-weight: 600;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #666;
}

.badge {
    font-size: 0.75em;
}

.form-inline .form-group {
    align-items: center;
}
</style>