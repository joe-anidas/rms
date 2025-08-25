<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Dashboard</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <button type="button" class="btn btn-outline-primary" onclick="refreshDashboard()">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="exportDashboard()">
                        <i class="fa fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced Key Performance Indicators -->
        <div class="row">
            <!-- Properties KPI -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card gradient-deepblue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-white mb-0"><?php echo $metrics['properties']['total']; ?></h5>
                                <p class="text-white mb-2 opacity-75">Total Properties</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-building fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <div class="progress-bar bg-white" style="width:<?php echo ($metrics['properties']['by_status']['sold'] / max($metrics['properties']['total'], 1)) * 100; ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span><?php echo $metrics['properties']['by_status']['sold']; ?> Sold</span>
                            <span><?php echo $metrics['properties']['by_status']['booked']; ?> Booked</span>
                            <span><?php echo $metrics['properties']['by_status']['unsold']; ?> Available</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customers KPI -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card gradient-orange">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-white mb-0"><?php echo $metrics['customers']['total']; ?></h5>
                                <p class="text-white mb-2 opacity-75">Total Customers</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-users fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <div class="progress-bar bg-white" style="width:<?php echo ($metrics['customers']['active'] / max($metrics['customers']['total'], 1)) * 100; ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span><?php echo $metrics['customers']['active']; ?> Active</span>
                            <span><?php echo $metrics['customers']['new_this_month']; ?> New This Month</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Revenue KPI -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card gradient-ohhappiness">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-white mb-0">₹<?php echo number_format($metrics['revenue']['total_collected'] / 100000, 1); ?>L</h5>
                                <p class="text-white mb-2 opacity-75">Revenue Collected</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-money fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <?php 
                            $total_potential = $metrics['revenue']['total_collected'] + $metrics['revenue']['pending'];
                            $collection_percentage = $total_potential > 0 ? ($metrics['revenue']['total_collected'] / $total_potential) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-white" style="width:<?php echo $collection_percentage; ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span>₹<?php echo number_format($metrics['revenue']['pending'] / 100000, 1); ?>L Pending</span>
                            <span><?php echo round($collection_percentage, 1); ?>% Collected</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Staff KPI -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card gradient-ibiza">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-white mb-0"><?php echo $metrics['staff']['total']; ?></h5>
                                <p class="text-white mb-2 opacity-75">Total Staff</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-user-tie fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <div class="progress-bar bg-white" style="width:<?php echo ($metrics['staff']['assigned'] / max($metrics['staff']['total'], 1)) * 100; ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span><?php echo $metrics['staff']['assigned']; ?> Assigned</span>
                            <span><?php echo $metrics['staff']['total'] - $metrics['staff']['assigned']; ?> Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional KPI Row -->
        <div class="row">
            <!-- Transaction Metrics -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card gradient-purple">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-white mb-0"><?php echo $metrics['transactions']['total']; ?></h5>
                                <p class="text-white mb-2 opacity-75">Total Transactions</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-exchange fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <div class="progress-bar bg-white" style="width:85%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span><?php echo $metrics['transactions']['recent']['count']; ?> This Month</span>
                            <span>₹<?php echo number_format($metrics['transactions']['recent']['amount'] / 100000, 1); ?>L</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Property Value -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card gradient-blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-white mb-0">₹<?php echo number_format($metrics['properties']['values']['total_value'] / 10000000, 1); ?>Cr</h5>
                                <p class="text-white mb-2 opacity-75">Total Property Value</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-chart-line fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <?php 
                            $sold_percentage = $metrics['properties']['values']['total_value'] > 0 ? 
                                ($metrics['properties']['values']['sold_value'] / $metrics['properties']['values']['total_value']) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-white" style="width:<?php echo $sold_percentage; ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span>₹<?php echo number_format($metrics['properties']['values']['sold_value'] / 10000000, 1); ?>Cr Sold</span>
                            <span><?php echo round($sold_percentage, 1); ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Average Property Price -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-white mb-0">₹<?php echo number_format($metrics['properties']['values']['average_price'] / 100000, 1); ?>L</h5>
                                <p class="text-white mb-2 opacity-75">Average Price</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-calculator fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <div class="progress-bar bg-white" style="width:70%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span>Per Property</span>
                            <span>Market Rate</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Performance Score -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="card metric-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <?php 
                                $performance_score = 0;
                                if ($metrics['properties']['total'] > 0) {
                                    $sales_rate = ($metrics['properties']['by_status']['sold'] / $metrics['properties']['total']) * 40;
                                    $collection_rate = $collection_percentage * 0.4;
                                    $staff_efficiency = ($metrics['staff']['assigned'] / max($metrics['staff']['total'], 1)) * 20;
                                    $performance_score = $sales_rate + $collection_rate + $staff_efficiency;
                                }
                                ?>
                                <h5 class="text-white mb-0"><?php echo round($performance_score); ?>%</h5>
                                <p class="text-white mb-2 opacity-75">Performance Score</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fa fa-trophy fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress my-3" style="height:4px;">
                            <div class="progress-bar bg-white" style="width:<?php echo $performance_score; ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white small">
                            <span><?php echo $performance_score >= 80 ? 'Excellent' : ($performance_score >= 60 ? 'Good' : 'Needs Improvement'); ?></span>
                            <span>Overall</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Interactive Charts Row -->
        <div class="row">
            <!-- Property Status Distribution with Trends -->
            <div class="col-12 col-lg-6">
                <div class="card chart-card">
                    <div class="card-header">
                        <h6 class="card-title">Property Status Distribution & Trends</h6>
                        <div class="card-action">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleChartType('propertyStatusChart', 'doughnut')">Pie</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleChartType('propertyStatusChart', 'bar')">Bar</button>
                            </div>
                            <a href="<?php echo base_url('dashboard/property_analytics'); ?>" class="btn btn-sm btn-primary ml-2">View Details</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="propertyStatusChart"></canvas>
                        </div>
                        <div class="chart-legend mt-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="legend-item">
                                        <div class="legend-color" style="background: #ff6384;"></div>
                                        <span class="legend-label">Unsold</span>
                                        <div class="legend-value"><?php echo $metrics['properties']['by_status']['unsold']; ?></div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="legend-item">
                                        <div class="legend-color" style="background: #ffcd56;"></div>
                                        <span class="legend-label">Booked</span>
                                        <div class="legend-value"><?php echo $metrics['properties']['by_status']['booked']; ?></div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="legend-item">
                                        <div class="legend-color" style="background: #36a2eb;"></div>
                                        <span class="legend-label">Sold</span>
                                        <div class="legend-value"><?php echo $metrics['properties']['by_status']['sold']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Revenue Trends with Forecasting -->
            <div class="col-12 col-lg-6">
                <div class="card chart-card">
                    <div class="card-header">
                        <h6 class="card-title">Revenue Trends & Forecasting</h6>
                        <div class="card-action">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active" onclick="setRevenueView('monthly')">Monthly</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setRevenueView('daily')">Daily</button>
                            </div>
                            <a href="<?php echo base_url('dashboard/financial_analytics'); ?>" class="btn btn-sm btn-primary ml-2">View Details</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                        <div class="revenue-summary mt-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="summary-item">
                                        <span class="summary-label">Total Revenue</span>
                                        <div class="summary-value text-success">₹<?php echo number_format($metrics['revenue']['total_collected']); ?></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-item">
                                        <span class="summary-label">Pending Amount</span>
                                        <div class="summary-value text-warning">₹<?php echo number_format($metrics['revenue']['pending']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Analytics and Staff Performance Row -->
        <div class="row">
            <!-- Customer Acquisition Trends -->
            <div class="col-12 col-lg-8">
                <div class="card chart-card">
                    <div class="card-header">
                        <h6 class="card-title">Customer Acquisition & Geographic Distribution</h6>
                        <div class="card-action">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active" onclick="setCustomerView('acquisition')">Acquisition</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setCustomerView('geographic')">Geographic</button>
                            </div>
                            <a href="<?php echo base_url('dashboard/customer_analytics'); ?>" class="btn btn-sm btn-primary ml-2">View Details</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="customerChart"></canvas>
                        </div>
                        <div class="customer-insights mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="insight-item">
                                        <div class="insight-value text-primary"><?php echo $metrics['customers']['total']; ?></div>
                                        <div class="insight-label">Total Customers</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="insight-item">
                                        <div class="insight-value text-success"><?php echo $metrics['customers']['active']; ?></div>
                                        <div class="insight-label">Active Customers</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="insight-item">
                                        <div class="insight-value text-info"><?php echo $metrics['customers']['new_this_month']; ?></div>
                                        <div class="insight-label">New This Month</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="insight-item">
                                        <div class="insight-value text-warning"><?php echo round(($metrics['customers']['active'] / max($metrics['customers']['total'], 1)) * 100, 1); ?>%</div>
                                        <div class="insight-label">Engagement Rate</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Staff Performance Overview -->
            <div class="col-12 col-lg-4">
                <div class="card chart-card">
                    <div class="card-header">
                        <h6 class="card-title">Staff Performance Overview</h6>
                        <div class="card-action">
                            <a href="<?php echo base_url('dashboard/staff_analytics'); ?>" class="btn btn-sm btn-primary">View Details</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="staffPerformanceChart"></canvas>
                        </div>
                        <div class="staff-summary mt-3">
                            <h6 class="mb-3">Top Performers</h6>
                            <?php if (!empty($metrics['staff']['workload'])): ?>
                                <?php foreach (array_slice($metrics['staff']['workload'], 0, 3) as $index => $staff): ?>
                                    <div class="performer-item mb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="performer-info">
                                                <span class="performer-rank">#<?php echo $index + 1; ?></span>
                                                <span class="performer-name"><?php echo htmlspecialchars($staff['employee_name']); ?></span>
                                            </div>
                                            <div class="performer-score">
                                                <span class="badge badge-<?php echo $index == 0 ? 'success' : ($index == 1 ? 'warning' : 'info'); ?>">
                                                    <?php echo $staff['assignment_count']; ?> assignments
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No staff data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Analysis Row -->
        <div class="row">
            <!-- Payment Methods -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Payment Methods</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentMethodChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Transaction Summary (Last 30 Days)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-primary"><?php echo $metrics['transactions']['recent']['count']; ?></h4>
                                    <p class="mb-0">Total Transactions</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-success">₹<?php echo number_format($metrics['transactions']['recent']['amount'], 0); ?></h4>
                                    <p class="mb-0">Total Amount</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-info">₹<?php echo $metrics['transactions']['recent']['count'] > 0 ? number_format($metrics['transactions']['recent']['amount'] / $metrics['transactions']['recent']['count'], 0) : 0; ?></h4>
                                    <p class="mb-0">Average Transaction</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Transaction Types Breakdown -->
                        <div class="mt-4">
                            <h6>Payment Types Breakdown:</h6>
                            <?php if (!empty($metrics['transactions']['by_type'])): ?>
                                <?php foreach ($metrics['transactions']['by_type'] as $type => $data): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-capitalize"><?php echo str_replace('_', ' ', $type); ?></span>
                                        <div>
                                            <span class="badge badge-primary"><?php echo $data['count']; ?> transactions</span>
                                            <span class="badge badge-success">₹<?php echo number_format($data['amount'], 0); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No transaction data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Analytics and Reports Row -->
        <div class="row">
            <!-- Quick Reports Access -->
            <div class="col-12 col-lg-4">
                <div class="card chart-card">
                    <div class="card-header">
                        <h6 class="card-title">Quick Reports</h6>
                        <div class="card-action">
                            <a href="<?php echo base_url('reports'); ?>" class="btn btn-sm btn-primary">All Reports</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="quick-reports">
                            <div class="report-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="report-info">
                                        <h6 class="mb-1">Property Performance</h6>
                                        <small class="text-muted">Sales & booking analysis</small>
                                    </div>
                                    <div class="report-actions">
                                        <a href="<?php echo base_url('dashboard/property_analytics'); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="<?php echo base_url('dashboard/export_dashboard_data?type=properties'); ?>" class="btn btn-sm btn-outline-secondary">Export</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="report-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="report-info">
                                        <h6 class="mb-1">Financial Summary</h6>
                                        <small class="text-muted">Revenue & payment analysis</small>
                                    </div>
                                    <div class="report-actions">
                                        <a href="<?php echo base_url('dashboard/financial_analytics'); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="<?php echo base_url('dashboard/export_dashboard_data?type=financial'); ?>" class="btn btn-sm btn-outline-secondary">Export</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="report-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="report-info">
                                        <h6 class="mb-1">Customer Analytics</h6>
                                        <small class="text-muted">Customer behavior & trends</small>
                                    </div>
                                    <div class="report-actions">
                                        <a href="<?php echo base_url('dashboard/customer_analytics'); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="<?php echo base_url('dashboard/export_dashboard_data?type=customers'); ?>" class="btn btn-sm btn-outline-secondary">Export</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="report-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="report-info">
                                        <h6 class="mb-1">Staff Performance</h6>
                                        <small class="text-muted">Workload & efficiency metrics</small>
                                    </div>
                                    <div class="report-actions">
                                        <a href="<?php echo base_url('dashboard/staff_analytics'); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="<?php echo base_url('dashboard/export_dashboard_data?type=staff'); ?>" class="btn btn-sm btn-outline-secondary">Export</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Staff Workload Distribution -->
            <div class="col-12 col-lg-8">
                <div class="card chart-card">
                    <div class="card-header">
                        <h6 class="card-title">Staff Workload Distribution & Performance</h6>
                        <div class="card-action">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active" onclick="setStaffView('workload')">Workload</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setStaffView('performance')">Performance</button>
                            </div>
                            <a href="<?php echo base_url('dashboard/staff_analytics'); ?>" class="btn btn-sm btn-primary ml-2">View Details</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="staffWorkloadChart"></canvas>
                        </div>
                        
                        <div class="staff-details mt-3">
                            <?php if (!empty($metrics['staff']['workload'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Staff Member</th>
                                                <th>Assignments</th>
                                                <th>Efficiency</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($metrics['staff']['workload'], 0, 5) as $staff): ?>
                                                <tr>
                                                    <td>
                                                        <div class="staff-info">
                                                            <strong><?php echo htmlspecialchars($staff['employee_name']); ?></strong>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary"><?php echo $staff['assignment_count']; ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="height: 20px; width: 100px;">
                                                            <?php 
                                                            $max_assignments = max(array_column($metrics['staff']['workload'], 'assignment_count'));
                                                            $percentage = $max_assignments > 0 ? ($staff['assignment_count'] / $max_assignments) * 100 : 0;
                                                            $efficiency_class = $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                                                            ?>
                                                            <div class="progress-bar <?php echo $efficiency_class; ?>" style="width: <?php echo $percentage; ?>%">
                                                                <?php echo round($percentage); ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $status = $staff['assignment_count'] > 0 ? 'Active' : 'Available';
                                                        $status_class = $staff['assignment_count'] > 0 ? 'badge-success' : 'badge-secondary';
                                                        ?>
                                                        <span class="badge <?php echo $status_class; ?>"><?php echo $status; ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No staff workload data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Alerts and Notifications -->
        <div class="row">
            <div class="col-12">
                <div class="card chart-card">
                    <div class="card-header">
                        <h6 class="card-title">System Alerts & Notifications</h6>
                        <div class="card-action">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshAlerts()">
                                <i class="fa fa-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="dashboard-alerts" class="alerts-container">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading alerts...</span>
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
// Dashboard data from PHP
const dashboardData = <?php echo json_encode($metrics); ?>;

// Chart instances for global access
let propertyChart, revenueChart, customerChart, staffPerformanceChart, paymentChart;

// Initialize all charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startAutoRefresh();
});

function initializeCharts() {
    // Property Status Chart
    const propertyCtx = document.getElementById('propertyStatusChart').getContext('2d');
    propertyChart = new Chart(propertyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Unsold', 'Booked', 'Sold'],
            datasets: [{
                data: [
                    dashboardData.properties.by_status.unsold || 0,
                    dashboardData.properties.by_status.booked || 0,
                    dashboardData.properties.by_status.sold || 0
                ],
                backgroundColor: [
                    '#ff6384',
                    '#ffcd56',
                    '#36a2eb'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderWidth: 5,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });

    // Revenue Trends Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueLabels = dashboardData.revenue.monthly.map(item => item.month);
    const revenueData = dashboardData.revenue.monthly.map(item => parseFloat(item.revenue));

    revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Monthly Revenue',
                data: revenueData,
                borderColor: '#36a2eb',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#36a2eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '₹' + (value / 100000).toFixed(1) + 'L';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#36a2eb',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: ₹' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Customer Chart (initially showing acquisition trends)
    const customerCtx = document.getElementById('customerChart').getContext('2d');
    customerChart = new Chart(customerCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], // Sample data
            datasets: [{
                label: 'New Customers',
                data: [12, 19, 15, 25, 22, 30], // Sample data
                borderColor: '#4bc0c0',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                borderWidth: 3,
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
                    beginAtZero: true
                }
            }
        }
    });

    // Staff Performance Chart
    const staffCtx = document.getElementById('staffPerformanceChart').getContext('2d');
    const staffNames = dashboardData.staff.workload.slice(0, 5).map(staff => staff.employee_name.split(' ')[0]);
    const staffAssignments = dashboardData.staff.workload.slice(0, 5).map(staff => staff.assignment_count);

    staffPerformanceChart = new Chart(staffCtx, {
        type: 'radar',
        data: {
            labels: staffNames,
            datasets: [{
                label: 'Assignments',
                data: staffAssignments,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: '#ff6384',
                borderWidth: 2,
                pointBackgroundColor: '#ff6384',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
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
                r: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

    // Staff Workload Chart
    const staffWorkloadCtx = document.getElementById('staffWorkloadChart').getContext('2d');
    const workloadNames = dashboardData.staff.workload.slice(0, 8).map(staff => staff.employee_name.split(' ')[0]);
    const workloadData = dashboardData.staff.workload.slice(0, 8).map(staff => staff.assignment_count);

    const staffWorkloadChart = new Chart(staffWorkloadCtx, {
        type: 'bar',
        data: {
            labels: workloadNames,
            datasets: [{
                label: 'Assignments',
                data: workloadData,
                backgroundColor: workloadData.map((value, index) => {
                    const colors = ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9f40', '#ff6384', '#c9cbcf'];
                    return colors[index % colors.length];
                }),
                borderColor: '#fff',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' assignments';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Payment Methods Chart (if transaction data exists)
    if (dashboardData.transactions.by_type) {
        const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
        const paymentTypes = Object.keys(dashboardData.transactions.by_type);
        const paymentCounts = paymentTypes.map(type => dashboardData.transactions.by_type[type].count);
        
        paymentChart = new Chart(paymentCtx, {
            type: 'pie',
            data: {
                labels: paymentTypes.map(type => type.replace('_', ' ').toUpperCase()),
                datasets: [{
                    data: paymentCounts,
                    backgroundColor: [
                        '#ff6384',
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
    }
}

// Chart interaction functions
function toggleChartType(chartId, newType) {
    if (chartId === 'propertyStatusChart') {
        propertyChart.config.type = newType;
        propertyChart.update();
    }
}

function setRevenueView(viewType) {
    // Update button states
    document.querySelectorAll('[onclick*="setRevenueView"]').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    if (viewType === 'daily') {
        // Switch to daily view (would need AJAX call for real data)
        revenueChart.data.labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        revenueChart.data.datasets[0].data = [50000, 75000, 60000, 90000, 80000, 45000, 30000];
        revenueChart.update();
    } else {
        // Switch back to monthly view
        const revenueLabels = dashboardData.revenue.monthly.map(item => item.month);
        const revenueData = dashboardData.revenue.monthly.map(item => parseFloat(item.revenue));
        revenueChart.data.labels = revenueLabels;
        revenueChart.data.datasets[0].data = revenueData;
        revenueChart.update();
    }
}

function setCustomerView(viewType) {
    // Update button states
    document.querySelectorAll('[onclick*="setCustomerView"]').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    if (viewType === 'geographic') {
        // Switch to geographic distribution
        customerChart.config.type = 'doughnut';
        customerChart.data.labels = ['Mumbai', 'Pune', 'Nashik', 'Aurangabad', 'Others'];
        customerChart.data.datasets[0] = {
            data: [35, 25, 20, 15, 5],
            backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff'],
            borderWidth: 2,
            borderColor: '#fff'
        };
        customerChart.update();
    } else {
        // Switch back to acquisition trends
        customerChart.config.type = 'line';
        customerChart.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        customerChart.data.datasets[0] = {
            label: 'New Customers',
            data: [12, 19, 15, 25, 22, 30],
            borderColor: '#4bc0c0',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        };
        customerChart.update();
    }
}

// Auto-refresh functionality
function startAutoRefresh() {
    setInterval(function() {
        // Check for updates every 5 minutes
        fetch('<?php echo base_url("dashboard/get_dashboard_data"); ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateDashboardData(data.data);
                }
            })
            .catch(error => {
                console.log('Auto-refresh failed:', error);
            });
    }, 300000); // 5 minutes
}

function updateDashboardData(newData) {
    // Update property chart
    propertyChart.data.datasets[0].data = [
        newData.properties.by_status.unsold || 0,
        newData.properties.by_status.booked || 0,
        newData.properties.by_status.sold || 0
    ];
    propertyChart.update();
    
    // Update revenue chart
    const newRevenueLabels = newData.revenue.monthly.map(item => item.month);
    const newRevenueData = newData.revenue.monthly.map(item => parseFloat(item.revenue));
    revenueChart.data.labels = newRevenueLabels;
    revenueChart.data.datasets[0].data = newRevenueData;
    revenueChart.update();
    
    // Update KPI cards
    updateKPICards(newData);
}

function updateKPICards(data) {
    // Update property values in legend
    document.querySelector('.legend-item:nth-child(1) .legend-value').textContent = data.properties.by_status.unsold;
    document.querySelector('.legend-item:nth-child(2) .legend-value').textContent = data.properties.by_status.booked;
    document.querySelector('.legend-item:nth-child(3) .legend-value').textContent = data.properties.by_status.sold;
    
    // Update revenue summary
    document.querySelector('.summary-value.text-success').textContent = '₹' + data.revenue.total_collected.toLocaleString();
    document.querySelector('.summary-value.text-warning').textContent = '₹' + data.revenue.pending.toLocaleString();
    
    // Show update notification
    showNotification('Dashboard updated successfully', 'success');
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} notification-alert fade-in`;
    notification.innerHTML = `
        <strong>${type === 'success' ? 'Success!' : 'Info!'}</strong> ${message}
        <button type="button" class="close" onclick="this.parentElement.remove()">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Export functions
function exportDashboardToPDF() {
    // This would require a PDF library like jsPDF
    showNotification('PDF export feature coming soon!', 'info');
}

function exportDashboardToExcel() {
    // This would require a library like SheetJS
    showNotification('Excel export feature coming soon!', 'info');
}

// Real-time data simulation (for demo purposes)
function simulateRealTimeUpdates() {
    setInterval(() => {
        // Simulate small changes in data
        const randomChange = Math.floor(Math.random() * 3) - 1; // -1, 0, or 1
        
        if (propertyChart && Math.random() > 0.7) { // 30% chance of update
            const currentData = propertyChart.data.datasets[0].data;
            const newData = currentData.map(value => Math.max(0, value + randomChange));
            propertyChart.data.datasets[0].data = newData;
            propertyChart.update('none'); // Update without animation
        }
    }, 10000); // Every 10 seconds
}

function setStaffView(viewType) {
    // Update button states
    document.querySelectorAll('[onclick*="setStaffView"]').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    const staffWorkloadChart = Chart.getChart('staffWorkloadChart');
    
    if (viewType === 'performance') {
        // Switch to performance view (efficiency percentages)
        const performanceData = dashboardData.staff.workload.slice(0, 8).map(staff => {
            const maxAssignments = Math.max(...dashboardData.staff.workload.map(s => s.assignment_count));
            return maxAssignments > 0 ? (staff.assignment_count / maxAssignments) * 100 : 0;
        });
        
        staffWorkloadChart.data.datasets[0].data = performanceData;
        staffWorkloadChart.data.datasets[0].label = 'Performance %';
        staffWorkloadChart.options.plugins.tooltip.callbacks.label = function(context) {
            return 'Performance: ' + Math.round(context.parsed.y) + '%';
        };
        staffWorkloadChart.update();
    } else {
        // Switch back to workload view
        const workloadData = dashboardData.staff.workload.slice(0, 8).map(staff => staff.assignment_count);
        staffWorkloadChart.data.datasets[0].data = workloadData;
        staffWorkloadChart.data.datasets[0].label = 'Assignments';
        staffWorkloadChart.options.plugins.tooltip.callbacks.label = function(context) {
            return 'Assignments: ' + context.parsed.y;
        };
        staffWorkloadChart.update();
    }
}

// Load dashboard alerts
function loadDashboardAlerts() {
    fetch('<?php echo base_url("dashboard/get_dashboard_alerts"); ?>')
        .then(response => response.json())
        .then(data => {
            const alertsContainer = document.getElementById('dashboard-alerts');
            
            if (data.success && data.data.length > 0) {
                let alertsHtml = '';
                data.data.forEach(alert => {
                    const alertClass = alert.type === 'warning' ? 'alert-warning' : 
                                     alert.type === 'danger' ? 'alert-danger' : 'alert-info';
                    const iconClass = alert.type === 'warning' ? 'fa-exclamation-triangle' : 
                                     alert.type === 'danger' ? 'fa-times-circle' : 'fa-info-circle';
                    
                    alertsHtml += `
                        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                            <i class="fa ${iconClass} mr-2"></i>
                            <strong>${alert.title}:</strong> ${alert.message}
                            ${alert.action_url ? `<a href="${alert.action_url}" class="alert-link ml-2">Take Action</a>` : ''}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    `;
                });
                alertsContainer.innerHTML = alertsHtml;
            } else {
                alertsContainer.innerHTML = `
                    <div class="alert alert-success" role="alert">
                        <i class="fa fa-check-circle mr-2"></i>
                        <strong>All Good!</strong> No alerts at this time. System is running smoothly.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading alerts:', error);
            document.getElementById('dashboard-alerts').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <i class="fa fa-exclamation-triangle mr-2"></i>
                    <strong>Error:</strong> Unable to load system alerts.
                </div>
            `;
        });
}

function refreshAlerts() {
    document.getElementById('dashboard-alerts').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading alerts...</span>
            </div>
        </div>
    `;
    loadDashboardAlerts();
}

// Load alerts on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(loadDashboardAlerts, 1000); // Load alerts after charts are initialized
});

// Initialize real-time updates (commented out for production)
// simulateRealTimeUpdates();

// Dashboard Functions
function refreshDashboard() {
    location.reload();
}

function exportDashboard() {
    window.open('<?php echo base_url("dashboard/export_dashboard_data"); ?>', '_blank');
}

// Auto-refresh dashboard every 5 minutes
setInterval(function() {
    // You can implement AJAX refresh here if needed
    console.log('Dashboard auto-refresh check');
}, 300000);
</script>

<style>
/* Enhanced Dashboard Styles */
.metric-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.metric-card .card-body {
    padding: 25px;
}

.metric-card h5 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.metric-card p {
    font-size: 0.9rem;
    margin-bottom: 0;
}

.metric-icon {
    opacity: 0.3;
}

.gradient-deepblue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-orange {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.gradient-ohhappiness {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
}

.gradient-ibiza {
    background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
}

.gradient-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-blue {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
}

.chart-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

.chart-card:hover {
    box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12);
}

.chart-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-card .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.chart-card .card-body {
    padding: 25px;
}

.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 15px;
}

.chart-legend {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.legend-item {
    text-align: center;
}

.legend-color {
    width: 20px;
    height: 4px;
    border-radius: 2px;
    margin: 0 auto 8px;
}

.legend-label {
    display: block;
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 4px;
}

.legend-value {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
}

.revenue-summary, .customer-insights {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.summary-item, .insight-item {
    text-align: center;
    padding: 10px;
}

.summary-label, .insight-label {
    display: block;
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 4px;
}

.summary-value, .insight-value {
    font-size: 1.4rem;
    font-weight: 600;
}

.staff-summary h6 {
    color: #495057;
    font-weight: 600;
}

.performer-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
}

.performer-rank {
    background: #007bff;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    margin-right: 10px;
}

.performer-name {
    font-weight: 500;
    color: #333;
}

.btn-group .btn {
    border-radius: 6px;
    font-size: 0.8rem;
    padding: 4px 12px;
}

.btn-group .btn.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.progress {
    height: 4px;
    border-radius: 2px;
    background-color: rgba(255, 255, 255, 0.2);
}

.progress-bar {
    border-radius: 2px;
}

.badge {
    font-size: 0.75rem;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.badge-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.badge-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    border: none;
}

.badge-warning {
    background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    border: none;
}

.badge-danger {
    background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
    border: none;
}

.badge-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
    border: none;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #666;
}

.opacity-75 {
    opacity: 0.75;
}

.opacity-50 {
    opacity: 0.5;
}

/* Quick Reports Styles */
.quick-reports {
    padding: 10px 0;
}

.report-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.report-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.report-item h6 {
    color: #333;
    font-weight: 600;
    margin-bottom: 4px;
}

.report-actions .btn {
    margin-left: 5px;
    font-size: 0.8rem;
    padding: 4px 8px;
}

/* Staff Details Styles */
.staff-info strong {
    color: #333;
    font-size: 0.9rem;
}

.staff-details .table {
    font-size: 0.9rem;
}

.staff-details .progress {
    border-radius: 10px;
}

/* Alerts Container */
.alerts-container {
    min-height: 100px;
}

.alerts-container .alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 15px;
}

.alerts-container .alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
}

.alerts-container .alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #fab1a0 100%);
    color: #721c24;
}

.alerts-container .alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #74b9ff 100%);
    color: #0c5460;
}

.alerts-container .alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #00b894 100%);
    color: #155724;
}

.alert-link {
    font-weight: 600;
    text-decoration: underline;
}

/* Loading Spinner */
.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Enhanced Table Styles */
.table-sm th,
.table-sm td {
    padding: 8px 12px;
    vertical-align: middle;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Button Group Enhancements */
.btn-group .btn-sm {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 4px;
}

.btn-group .btn.active {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-color: #0056b3;
    color: white;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .metric-card .card-body {
        padding: 20px;
    }
    
    .metric-card h5 {
        font-size: 2rem;
    }
    
    .chart-card .card-header {
        padding: 15px 20px;
        flex-direction: column;
        align-items: stretch;
    }
    
    .chart-card .card-action {
        margin-top: 10px;
    }
    
    .chart-container {
        height: 250px;
    }
    
    .btn-group {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .btn-group .btn {
        flex: 1;
    }
}

/* Notification Alerts */
.notification-alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    min-width: 300px;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-up {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>