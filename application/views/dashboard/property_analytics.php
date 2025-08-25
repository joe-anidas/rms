<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Property Analytics</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Property Analytics</li>
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
                            <a href="<?php echo base_url('dashboard/property_analytics'); ?>" class="btn btn-secondary ml-2">Clear</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Status Distribution -->
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Property Status Distribution</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="statusDistributionChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($analytics['status_distribution'])): ?>
                                            <?php foreach ($analytics['status_distribution'] as $status): ?>
                                                <tr>
                                                    <td class="text-capitalize"><?php echo $status['status']; ?></td>
                                                    <td><?php echo $status['count']; ?></td>
                                                    <td><?php echo $status['percentage']; ?>%</td>
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
            
            <!-- Property Type Distribution -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Property Type Analysis</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="typeDistributionChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Total</th>
                                            <th>Sold</th>
                                            <th>Avg Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($analytics['type_distribution'])): ?>
                                            <?php foreach ($analytics['type_distribution'] as $type): ?>
                                                <tr>
                                                    <td class="text-capitalize"><?php echo $type['property_type']; ?></td>
                                                    <td><?php echo $type['count']; ?></td>
                                                    <td><?php echo $type['sold_count']; ?></td>
                                                    <td>₹<?php echo number_format($type['average_price'], 0); ?></td>
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
        </div>

        <!-- Property Trends -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Property Addition Trends</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="propertyTrendsChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Metrics -->
        <?php if (!empty($analytics['sales_metrics'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Sales Performance Metrics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-primary"><?php echo round($analytics['sales_metrics']['avg_days_to_sell']); ?></h4>
                                    <p class="mb-0">Average Days to Sell</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-success"><?php echo $analytics['sales_metrics']['total_sold']; ?></h4>
                                    <p class="mb-0">Total Properties Sold</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-info"><?php echo $analytics['sales_metrics']['fastest_sale']; ?></h4>
                                    <p class="mb-0">Fastest Sale (Days)</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-warning"><?php echo $analytics['sales_metrics']['slowest_sale']; ?></h4>
                                    <p class="mb-0">Slowest Sale (Days)</p>
                                </div>
                            </div>
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
const analyticsData = <?php echo json_encode($analytics); ?>;

// Status Distribution Chart
const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: analyticsData.status_distribution.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
        datasets: [{
            data: analyticsData.status_distribution.map(item => item.count),
            backgroundColor: [
                '#ff6384',
                '#ffcd56',
                '#36a2eb',
                '#4bc0c0'
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

// Type Distribution Chart
const typeCtx = document.getElementById('typeDistributionChart').getContext('2d');
const typeChart = new Chart(typeCtx, {
    type: 'bar',
    data: {
        labels: analyticsData.type_distribution.map(item => item.property_type.charAt(0).toUpperCase() + item.property_type.slice(1)),
        datasets: [{
            label: 'Total Properties',
            data: analyticsData.type_distribution.map(item => item.count),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'Sold Properties',
            data: analyticsData.type_distribution.map(item => item.sold_count),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
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
        }
    }
});

// Property Trends Chart
const trendsCtx = document.getElementById('propertyTrendsChart').getContext('2d');

// Process trends data for chart
const trendsData = {};
analyticsData.trends.forEach(item => {
    if (!trendsData[item.month]) {
        trendsData[item.month] = {
            unsold: 0,
            booked: 0,
            sold: 0
        };
    }
    trendsData[item.month][item.status] = item.properties_added;
});

const months = Object.keys(trendsData).sort();
const trendsChart = new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Unsold',
            data: months.map(month => trendsData[month].unsold || 0),
            borderColor: '#ff6384',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            borderWidth: 2,
            fill: false
        }, {
            label: 'Booked',
            data: months.map(month => trendsData[month].booked || 0),
            borderColor: '#ffcd56',
            backgroundColor: 'rgba(255, 205, 86, 0.1)',
            borderWidth: 2,
            fill: false
        }, {
            label: 'Sold',
            data: months.map(month => trendsData[month].sold || 0),
            borderColor: '#36a2eb',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 2,
            fill: false
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
                position: 'top'
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

.form-inline .form-group {
    align-items: center;
}
</style>