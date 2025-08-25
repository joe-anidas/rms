<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Staff Analytics</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Staff Analytics</li>
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
                            <a href="<?php echo base_url('dashboard/staff_analytics'); ?>" class="btn btn-secondary ml-2">Clear</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Performance Overview -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Staff Performance Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="performanceTable">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Designation</th>
                                        <th>Properties Assigned</th>
                                        <th>Registrations Handled</th>
                                        <th>Revenue Generated</th>
                                        <th>Performance Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($analytics['performance'])): ?>
                                        <?php 
                                        $max_revenue = max(array_column($analytics['performance'], 'revenue_generated'));
                                        foreach ($analytics['performance'] as $staff): 
                                            $performance_score = $max_revenue > 0 ? ($staff['revenue_generated'] / $max_revenue) * 100 : 0;
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($staff['employee_name']); ?></td>
                                                <td><?php echo htmlspecialchars($staff['designation']); ?></td>
                                                <td><?php echo $staff['properties_assigned']; ?></td>
                                                <td><?php echo $staff['registrations_handled']; ?></td>
                                                <td>₹<?php echo number_format($staff['revenue_generated'], 0); ?></td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <?php 
                                                        $score_class = $performance_score >= 80 ? 'bg-success' : ($performance_score >= 50 ? 'bg-warning' : 'bg-danger');
                                                        ?>
                                                        <div class="progress-bar <?php echo $score_class; ?>" style="width: <?php echo $performance_score; ?>%">
                                                            <?php echo round($performance_score, 1); ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No staff performance data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workload Distribution and Assignment History -->
        <div class="row">
            <!-- Workload Distribution -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Staff Workload Distribution</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="workloadChart" height="400"></canvas>
                        
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Staff Member</th>
                                            <th>Sales</th>
                                            <th>Maintenance</th>
                                            <th>Customer Service</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($analytics['workload'])): ?>
                                            <?php foreach ($analytics['workload'] as $staff): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($staff['employee_name']); ?></td>
                                                    <td>
                                                        <span class="badge badge-primary"><?php echo $staff['sales_assignments']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning"><?php echo $staff['maintenance_assignments']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info"><?php echo $staff['service_assignments']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success"><?php echo $staff['total_assignments']; ?></span>
                                                    </td>
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
            
            <!-- Assignment Types Distribution -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Assignment Types</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="assignmentTypesChart" height="300"></canvas>
                        
                        <div class="mt-3">
                            <?php 
                            $total_sales = 0;
                            $total_maintenance = 0;
                            $total_service = 0;
                            
                            if (!empty($analytics['workload'])) {
                                foreach ($analytics['workload'] as $staff) {
                                    $total_sales += $staff['sales_assignments'];
                                    $total_maintenance += $staff['maintenance_assignments'];
                                    $total_service += $staff['service_assignments'];
                                }
                            }
                            ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Sales Assignments</span>
                                <span class="badge badge-primary"><?php echo $total_sales; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Maintenance</span>
                                <span class="badge badge-warning"><?php echo $total_maintenance; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Customer Service</span>
                                <span class="badge badge-info"><?php echo $total_service; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment History Trends -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Assignment History Trends</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="assignmentHistoryChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Top Performers by Revenue</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="topPerformersChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Staff Efficiency Metrics -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Staff Efficiency Metrics</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($analytics['performance'])): ?>
                            <?php 
                            $total_staff = count($analytics['performance']);
                            $active_staff = count(array_filter($analytics['performance'], function($staff) {
                                return $staff['properties_assigned'] > 0;
                            }));
                            $avg_properties = $total_staff > 0 ? array_sum(array_column($analytics['performance'], 'properties_assigned')) / $total_staff : 0;
                            $avg_revenue = $total_staff > 0 ? array_sum(array_column($analytics['performance'], 'revenue_generated')) / $total_staff : 0;
                            ?>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center mb-3">
                                        <h4 class="text-primary"><?php echo $active_staff; ?>/<?php echo $total_staff; ?></h4>
                                        <p class="mb-0">Active Staff</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center mb-3">
                                        <h4 class="text-success"><?php echo round($avg_properties, 1); ?></h4>
                                        <p class="mb-0">Avg Properties/Staff</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-center">
                                        <h4 class="text-info">₹<?php echo number_format($avg_revenue, 0); ?></h4>
                                        <p class="mb-0">Average Revenue per Staff</p>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6>Performance Distribution:</h6>
                            <?php 
                            $high_performers = 0;
                            $medium_performers = 0;
                            $low_performers = 0;
                            
                            foreach ($analytics['performance'] as $staff) {
                                $performance_score = $max_revenue > 0 ? ($staff['revenue_generated'] / $max_revenue) * 100 : 0;
                                if ($performance_score >= 80) $high_performers++;
                                elseif ($performance_score >= 50) $medium_performers++;
                                else $low_performers++;
                            }
                            ?>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>High Performers (80%+)</span>
                                <span class="badge badge-success"><?php echo $high_performers; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Medium Performers (50-79%)</span>
                                <span class="badge badge-warning"><?php echo $medium_performers; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Needs Improvement (<50%)</span>
                                <span class="badge badge-danger"><?php echo $low_performers; ?></span>
                            </div>
                            
                        <?php else: ?>
                            <p class="text-muted">No staff efficiency data available</p>
                        <?php endif; ?>
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
const staffData = <?php echo json_encode($analytics); ?>;

// Workload Distribution Chart
const workloadCtx = document.getElementById('workloadChart').getContext('2d');
const workloadChart = new Chart(workloadCtx, {
    type: 'bar',
    data: {
        labels: staffData.workload.map(item => item.employee_name),
        datasets: [{
            label: 'Sales',
            data: staffData.workload.map(item => item.sales_assignments),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'Maintenance',
            data: staffData.workload.map(item => item.maintenance_assignments),
            backgroundColor: 'rgba(255, 205, 86, 0.6)',
            borderColor: 'rgba(255, 205, 86, 1)',
            borderWidth: 1
        }, {
            label: 'Customer Service',
            data: staffData.workload.map(item => item.service_assignments),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true,
                beginAtZero: true
            }
        }
    }
});

// Assignment Types Chart
const typesCtx = document.getElementById('assignmentTypesChart').getContext('2d');

// Calculate totals for assignment types
let totalSales = 0, totalMaintenance = 0, totalService = 0;
staffData.workload.forEach(staff => {
    totalSales += parseInt(staff.sales_assignments);
    totalMaintenance += parseInt(staff.maintenance_assignments);
    totalService += parseInt(staff.service_assignments);
});

const assignmentTypesChart = new Chart(typesCtx, {
    type: 'doughnut',
    data: {
        labels: ['Sales', 'Maintenance', 'Customer Service'],
        datasets: [{
            data: [totalSales, totalMaintenance, totalService],
            backgroundColor: [
                '#36a2eb',
                '#ffcd56',
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

// Assignment History Chart
const historyCtx = document.getElementById('assignmentHistoryChart').getContext('2d');
const assignmentHistoryChart = new Chart(historyCtx, {
    type: 'line',
    data: {
        labels: staffData.assignment_history.map(item => item.month),
        datasets: [{
            label: 'Assignments Made',
            data: staffData.assignment_history.map(item => item.assignments_made),
            borderColor: '#36a2eb',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }, {
            label: 'Staff Involved',
            data: staffData.assignment_history.map(item => item.staff_involved),
            borderColor: '#ff6384',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            borderWidth: 2,
            fill: false,
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
        }
    }
});

// Top Performers Chart
const performersCtx = document.getElementById('topPerformersChart').getContext('2d');
const topPerformers = staffData.performance.slice(0, 5); // Top 5 performers

const topPerformersChart = new Chart(performersCtx, {
    type: 'horizontalBar',
    data: {
        labels: topPerformers.map(item => item.employee_name),
        datasets: [{
            label: 'Revenue Generated',
            data: topPerformers.map(item => item.revenue_generated),
            backgroundColor: [
                '#ff6384',
                '#36a2eb',
                '#ffcd56',
                '#4bc0c0',
                '#9966ff'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
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

.progress {
    border-radius: 10px;
}

.form-inline .form-group {
    align-items: center;
}
</style>