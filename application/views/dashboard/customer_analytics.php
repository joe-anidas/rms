<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Customer Analytics</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customer Analytics</li>
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
                            <a href="<?php echo base_url('dashboard/customer_analytics'); ?>" class="btn btn-secondary ml-2">Clear</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Acquisition Trends -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Customer Acquisition Trends</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="acquisitionTrendsChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers and Geographic Distribution -->
        <div class="row">
            <!-- Top Customers -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Top Customers by Value</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Contact</th>
                                        <th>Properties</th>
                                        <th>Total Value</th>
                                        <th>Total Paid</th>
                                        <th>Payment %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($analytics['top_customers'])): ?>
                                        <?php foreach ($analytics['top_customers'] as $customer): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                                                <td><?php echo htmlspecialchars($customer['contact_details']); ?></td>
                                                <td><?php echo $customer['properties_count']; ?></td>
                                                <td>₹<?php echo number_format($customer['total_value'], 0); ?></td>
                                                <td>₹<?php echo number_format($customer['total_paid'], 0); ?></td>
                                                <td>
                                                    <?php 
                                                    $percentage = $customer['total_value'] > 0 ? ($customer['total_paid'] / $customer['total_value']) * 100 : 0;
                                                    $badge_class = $percentage >= 100 ? 'badge-success' : ($percentage >= 50 ? 'badge-warning' : 'badge-danger');
                                                    ?>
                                                    <span class="badge <?php echo $badge_class; ?>">
                                                        <?php echo round($percentage, 1); ?>%
                                                    </span>
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
            
            <!-- Geographic Distribution -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Geographic Distribution</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="geographicChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <h6>Top Areas:</h6>
                            <?php if (!empty($analytics['geographic_distribution'])): ?>
                                <?php foreach (array_slice($analytics['geographic_distribution'], 0, 5) as $area): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span><?php echo htmlspecialchars($area['area']); ?></span>
                                        <span class="badge badge-primary"><?php echo $area['customer_count']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No geographic data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Lifecycle Analysis -->
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Customer Lifecycle Analysis</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="lifecycleChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Lifecycle Stage</th>
                                            <th>Count</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($analytics['lifecycle'])): ?>
                                            <?php 
                                            $total_customers = array_sum(array_column($analytics['lifecycle'], 'customer_count'));
                                            foreach ($analytics['lifecycle'] as $stage): 
                                                $percentage = $total_customers > 0 ? ($stage['customer_count'] / $total_customers) * 100 : 0;
                                            ?>
                                                <tr>
                                                    <td><?php echo $stage['lifecycle_stage']; ?></td>
                                                    <td><?php echo $stage['customer_count']; ?></td>
                                                    <td><?php echo round($percentage, 1); ?>%</td>
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
            
            <!-- Customer Value Distribution -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Customer Value Distribution</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="valueDistributionChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="text-primary">
                                            <?php 
                                            $high_value = 0;
                                            if (!empty($analytics['top_customers'])) {
                                                foreach ($analytics['top_customers'] as $customer) {
                                                    if ($customer['total_value'] >= 500000) $high_value++;
                                                }
                                            }
                                            echo $high_value;
                                            ?>
                                        </h5>
                                        <p class="mb-0 small">High Value (₹5L+)</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="text-success">
                                            <?php 
                                            $fully_paid = 0;
                                            if (!empty($analytics['top_customers'])) {
                                                foreach ($analytics['top_customers'] as $customer) {
                                                    if ($customer['total_value'] > 0 && ($customer['total_paid'] / $customer['total_value']) >= 1) {
                                                        $fully_paid++;
                                                    }
                                                }
                                            }
                                            echo $fully_paid;
                                            ?>
                                        </h5>
                                        <p class="mb-0 small">Fully Paid</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Analytics data from PHP
const customerData = <?php echo json_encode($analytics); ?>;

// Customer Acquisition Trends Chart
const acquisitionCtx = document.getElementById('acquisitionTrendsChart').getContext('2d');
const acquisitionChart = new Chart(acquisitionCtx, {
    type: 'line',
    data: {
        labels: customerData.acquisition_trends.map(item => item.month),
        datasets: [{
            label: 'New Customers',
            data: customerData.acquisition_trends.map(item => item.new_customers),
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

// Geographic Distribution Chart
const geoCtx = document.getElementById('geographicChart').getContext('2d');
const geoChart = new Chart(geoCtx, {
    type: 'doughnut',
    data: {
        labels: customerData.geographic_distribution.slice(0, 5).map(item => item.area),
        datasets: [{
            data: customerData.geographic_distribution.slice(0, 5).map(item => item.customer_count),
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

// Customer Lifecycle Chart
const lifecycleCtx = document.getElementById('lifecycleChart').getContext('2d');
const lifecycleChart = new Chart(lifecycleCtx, {
    type: 'bar',
    data: {
        labels: customerData.lifecycle.map(item => item.lifecycle_stage),
        datasets: [{
            label: 'Customers',
            data: customerData.lifecycle.map(item => item.customer_count),
            backgroundColor: [
                '#ff6384',
                '#36a2eb',
                '#ffcd56',
                '#4bc0c0',
                '#9966ff',
                '#ff9f40'
            ],
            borderWidth: 1,
            borderColor: '#fff'
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

// Customer Value Distribution Chart
const valueCtx = document.getElementById('valueDistributionChart').getContext('2d');

// Process customer data for value distribution
const valueRanges = {
    '0-1L': 0,
    '1L-5L': 0,
    '5L-10L': 0,
    '10L+': 0
};

customerData.top_customers.forEach(customer => {
    const value = parseFloat(customer.total_value);
    if (value < 100000) valueRanges['0-1L']++;
    else if (value < 500000) valueRanges['1L-5L']++;
    else if (value < 1000000) valueRanges['5L-10L']++;
    else valueRanges['10L+']++;
});

const valueChart = new Chart(valueCtx, {
    type: 'pie',
    data: {
        labels: Object.keys(valueRanges),
        datasets: [{
            data: Object.values(valueRanges),
            backgroundColor: [
                '#4bc0c0',
                '#36a2eb',
                '#ffcd56',
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
</script>

<style>
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