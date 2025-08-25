<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-chart-bar mr-2"></i>Staff Workload Distribution Dashboard
                    </h5>
                    <div>
                        <a href="<?php echo base_url('staff'); ?>" class="btn btn-dark btn-sm">
                            <i class="fa fa-users mr-1"></i>All Staff
                        </a>
                        <a href="<?php echo base_url('staff/assignments'); ?>" class="btn btn-outline-dark btn-sm">
                            <i class="fa fa-tasks mr-1"></i>Manage Assignments
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['total_staff']; ?></h4>
                                <p class="mb-0">Total Staff</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['staff_with_property_assignments']; ?></h4>
                                <p class="mb-0">Staff with Property Assignments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['staff_with_customer_assignments']; ?></h4>
                                <p class="mb-0">Staff with Customer Assignments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-user-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['active_property_assignments'] + $stats['active_customer_assignments']; ?></h4>
                                <p class="mb-0">Total Active Assignments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-tasks fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workload Distribution Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fa fa-chart-pie mr-2"></i>Property Assignment Distribution
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="propertyAssignmentChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fa fa-chart-pie mr-2"></i>Customer Assignment Distribution
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="customerAssignmentChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Comparison Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fa fa-chart-bar mr-2"></i>Staff Performance Comparison
                </h6>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="100"></canvas>
            </div>
        </div>

        <!-- Workload Distribution Table -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fa fa-table mr-2"></i>Detailed Workload Distribution
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Staff Member</th>
                                <th>Designation</th>
                                <th>Property Assignments</th>
                                <th>Customer Assignments</th>
                                <th>Total Workload</th>
                                <th>Workload Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Sort workload by total assignments (descending)
                            usort($workload, function($a, $b) {
                                $totalA = $a['property_count'] + $a['customer_count'];
                                $totalB = $b['property_count'] + $b['customer_count'];
                                return $totalB - $totalA;
                            });
                            
                            foreach($workload as $staff_workload): 
                                $total_assignments = $staff_workload['property_count'] + $staff_workload['customer_count'];
                                
                                // Determine workload status
                                if ($total_assignments == 0) {
                                    $status = 'No Assignments';
                                    $status_class = 'secondary';
                                } elseif ($total_assignments <= 2) {
                                    $status = 'Light Load';
                                    $status_class = 'success';
                                } elseif ($total_assignments <= 5) {
                                    $status = 'Moderate Load';
                                    $status_class = 'warning';
                                } else {
                                    $status = 'Heavy Load';
                                    $status_class = 'danger';
                                }
                            ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($staff_workload['employee_name']); ?></strong>
                                        <br><small class="text-muted">ID: #<?php echo $staff_workload['staff_id']; ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($staff_workload['designation'] ?: 'N/A'); ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary badge-lg">
                                            <?php echo $staff_workload['property_count']; ?>
                                        </span>
                                        <?php if($staff_workload['property_count'] > 0): ?>
                                            <br><small class="text-muted">Properties</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success badge-lg">
                                            <?php echo $staff_workload['customer_count']; ?>
                                        </span>
                                        <?php if($staff_workload['customer_count'] > 0): ?>
                                            <br><small class="text-muted">Customers</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info badge-lg">
                                            <?php echo $total_assignments; ?>
                                        </span>
                                        <br><small class="text-muted">Total</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $status_class; ?>">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/profile/' . $staff_workload['staff_id']); ?>" 
                                               class="btn btn-info btn-sm" title="View Profile">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="<?php echo base_url('staff/assignments/' . $staff_workload['staff_id']); ?>" 
                                               class="btn btn-warning btn-sm" title="Manage Assignments">
                                                <i class="fa fa-tasks"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Department-wise Distribution -->
        <?php if(!empty($stats['by_department'])): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fa fa-sitemap mr-2"></i>Department-wise Staff Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach($stats['by_department'] as $dept): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="text-primary"><?php echo htmlspecialchars($dept->department ?: 'No Department'); ?></h6>
                                                <h4 class="mb-0"><?php echo $dept->count; ?></h4>
                                                <small class="text-muted">Staff Members</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fa fa-building-o fa-2x text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Prepare data for charts
const workloadData = <?php echo json_encode($workload); ?>;
const performanceData = <?php echo json_encode($performance_data); ?>;

// Property Assignment Distribution Chart
const propertyLabels = workloadData.map(staff => staff.employee_name);
const propertyData = workloadData.map(staff => staff.property_count);

const propertyChart = new Chart(document.getElementById('propertyAssignmentChart'), {
    type: 'doughnut',
    data: {
        labels: propertyLabels,
        datasets: [{
            data: propertyData,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Property Assignments by Staff'
            }
        }
    }
});

// Customer Assignment Distribution Chart
const customerData = workloadData.map(staff => staff.customer_count);

const customerChart = new Chart(document.getElementById('customerAssignmentChart'), {
    type: 'doughnut',
    data: {
        labels: propertyLabels,
        datasets: [{
            data: customerData,
            backgroundColor: [
                '#36A2EB', '#FF6384', '#4BC0C0', '#FFCE56', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Customer Assignments by Staff'
            }
        }
    }
});

// Performance Comparison Chart
const performanceChart = new Chart(document.getElementById('performanceChart'), {
    type: 'bar',
    data: {
        labels: performanceData.labels,
        datasets: [
            {
                label: 'Property Assignments',
                data: performanceData.property_assignments,
                backgroundColor: '#36A2EB',
                borderColor: '#36A2EB',
                borderWidth: 1
            },
            {
                label: 'Customer Assignments',
                data: performanceData.customer_assignments,
                backgroundColor: '#4BC0C0',
                borderColor: '#4BC0C0',
                borderWidth: 1
            },
            {
                label: 'Transaction Value (₹ in thousands)',
                data: performanceData.transaction_amounts.map(amount => amount / 1000),
                backgroundColor: '#FFCE56',
                borderColor: '#FFCE56',
                borderWidth: 1,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Staff Performance Metrics'
            },
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Number of Assignments'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Transaction Value (₹ thousands)'
                },
                grid: {
                    drawOnChartArea: false,
                }
            }
        }
    }
});

// Add click handlers for chart interactions
propertyChart.options.onClick = function(event, elements) {
    if (elements.length > 0) {
        const index = elements[0].index;
        const staffId = workloadData[index].staff_id;
        window.location.href = `<?php echo base_url('staff/profile/'); ?>${staffId}`;
    }
};

customerChart.options.onClick = function(event, elements) {
    if (elements.length > 0) {
        const index = elements[0].index;
        const staffId = workloadData[index].staff_id;
        window.location.href = `<?php echo base_url('staff/profile/'); ?>${staffId}`;
    }
};

performanceChart.options.onClick = function(event, elements) {
    if (elements.length > 0) {
        const index = elements[0].index;
        const staffId = workloadData[index].staff_id;
        window.location.href = `<?php echo base_url('staff/profile/'); ?>${staffId}`;
    }
};

// Auto-refresh data every 5 minutes
setInterval(() => {
    location.reload();
}, 300000);

// Export functionality
function exportWorkloadData() {
    const csvContent = [
        ['Staff Name', 'Designation', 'Property Assignments', 'Customer Assignments', 'Total Workload', 'Status'].join(','),
        ...workloadData.map(staff => [
            staff.employee_name,
            staff.designation || 'N/A',
            staff.property_count,
            staff.customer_count,
            staff.property_count + staff.customer_count,
            getWorkloadStatus(staff.property_count + staff.customer_count)
        ].map(field => `"${field}"`).join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'staff_workload_distribution.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function getWorkloadStatus(total) {
    if (total == 0) return 'No Assignments';
    if (total <= 2) return 'Light Load';
    if (total <= 5) return 'Moderate Load';
    return 'Heavy Load';
}

// Add export button to page
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.createElement('button');
    exportBtn.className = 'btn btn-success btn-sm float-right';
    exportBtn.innerHTML = '<i class="fa fa-download mr-1"></i>Export Data';
    exportBtn.onclick = exportWorkloadData;
    
    const cardHeader = document.querySelector('.card:last-child .card-header');
    if (cardHeader) {
        cardHeader.appendChild(exportBtn);
    }
});
</script>