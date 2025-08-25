<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions') ?>">Transactions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Financial Reports</li>
                </ol>
            </div>
        </div>

        <!-- Report Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-filter"></i> Report Filters
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= current_url() ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" class="form-control" 
                                               value="<?= $params['start_date'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" class="form-control" 
                                               value="<?= $params['end_date'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Group By</label>
                                        <select name="group_by" class="form-control">
                                            <option value="day" <?= $params['group_by'] == 'day' ? 'selected' : '' ?>>Daily</option>
                                            <option value="week" <?= $params['group_by'] == 'week' ? 'selected' : '' ?>>Weekly</option>
                                            <option value="month" <?= $params['group_by'] == 'month' ? 'selected' : '' ?>>Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($report)): ?>
            
            <!-- Summary Cards -->
            <?php if (!empty($report['summary'])): ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?= $report['summary']['total_transactions'] ?: 0 ?></h4>
                                        <p class="mb-0">Total Transactions</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa fa-list fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>₹<?= number_format($report['summary']['total_revenue'] ?: 0, 0) ?></h4>
                                        <p class="mb-0">Total Revenue</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa fa-money fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>₹<?= number_format($report['summary']['average_transaction'] ?: 0, 0) ?></h4>
                                        <p class="mb-0">Average Transaction</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa fa-calculator fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>₹<?= number_format($report['summary']['max_transaction'] ?: 0, 0) ?></h4>
                                        <p class="mb-0">Largest Transaction</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa fa-arrow-up fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Revenue Timeline Chart -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-line-chart"></i> Revenue Timeline
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Payment Type Distribution -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-pie-chart"></i> Payment Types
                        </div>
                        <div class="card-body">
                            <canvas id="paymentTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Payment Method Distribution -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-bar-chart"></i> Payment Methods
                        </div>
                        <div class="card-body">
                            <?php if (!empty($report['by_payment_method'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Payment Method</th>
                                                <th>Count</th>
                                                <th>Total Amount</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total_revenue = $report['summary']['total_revenue'] ?: 1;
                                            foreach ($report['by_payment_method'] as $method): 
                                                $percentage = ($method['total_amount'] / $total_revenue) * 100;
                                            ?>
                                                <tr>
                                                    <td><?= ucfirst(str_replace('_', ' ', $method['payment_method'])) ?></td>
                                                    <td><?= $method['count'] ?></td>
                                                    <td>₹<?= number_format($method['total_amount'], 2) ?></td>
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar" role="progressbar" 
                                                                 style="width: <?= $percentage ?>%">
                                                                <?= number_format($percentage, 1) ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">No payment method data available</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Top Properties -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-trophy"></i> Top Properties by Revenue
                        </div>
                        <div class="card-body">
                            <?php if (!empty($report['top_properties'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Property</th>
                                                <th>Transactions</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($report['top_properties'] as $property): ?>
                                                <tr>
                                                    <td><?= $property['garden_name'] ?></td>
                                                    <td><?= $property['transaction_count'] ?></td>
                                                    <td>₹<?= number_format($property['total_revenue'], 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">No property data available</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <?php if (!empty($report['pending_payments'])): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-clock-o"></i> Upcoming Payments (Next 30 Days)
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Due Date</th>
                                                <th>Customer</th>
                                                <th>Property</th>
                                                <th>Installment #</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($report['pending_payments'] as $payment): ?>
                                                <?php
                                                $due_date = new DateTime($payment['due_date']);
                                                $today = new DateTime();
                                                $days_diff = $today->diff($due_date)->format('%r%a');
                                                
                                                $row_class = '';
                                                if ($days_diff < 0) {
                                                    $row_class = 'table-danger';
                                                } elseif ($days_diff <= 7) {
                                                    $row_class = 'table-warning';
                                                }
                                                ?>
                                                <tr class="<?= $row_class ?>">
                                                    <td><?= date('d/m/Y', strtotime($payment['due_date'])) ?></td>
                                                    <td><?= $payment['plot_buyer_name'] ?></td>
                                                    <td><?= $payment['garden_name'] ?></td>
                                                    <td><?= $payment['installment_number'] ?></td>
                                                    <td>₹<?= number_format($payment['amount'], 2) ?></td>
                                                    <td>
                                                        <?php if ($days_diff < 0): ?>
                                                            <span class="badge badge-danger"><?= abs($days_diff) ?> days overdue</span>
                                                        <?php elseif ($days_diff == 0): ?>
                                                            <span class="badge badge-warning">Due today</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-info"><?= $days_diff ?> days left</span>
                                                        <?php endif; ?>
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

        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No data available for the selected date range.
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    
    // Revenue Timeline Chart
    <?php if (!empty($report['timeline'])): ?>
        var timelineCtx = document.getElementById('revenueChart').getContext('2d');
        var timelineChart = new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: [<?php foreach ($report['timeline'] as $item): ?>'<?= $item['period'] ?>',<?php endforeach; ?>],
                datasets: [{
                    label: 'Revenue',
                    data: [<?php foreach ($report['timeline'] as $item): ?><?= $item['total_amount'] ?>,<?php endforeach; ?>],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Transactions',
                    data: [<?php foreach ($report['timeline'] as $item): ?><?= $item['count'] ?>,<?php endforeach; ?>],
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    yAxisID: 'y1',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    <?php endif; ?>

    // Payment Type Chart
    <?php if (!empty($report['by_payment_type'])): ?>
        var paymentTypeCtx = document.getElementById('paymentTypeChart').getContext('2d');
        var paymentTypeChart = new Chart(paymentTypeCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php foreach ($report['by_payment_type'] as $type): ?>'<?= ucfirst(str_replace('_', ' ', $type['payment_type'])) ?>',<?php endforeach; ?>],
                datasets: [{
                    data: [<?php foreach ($report['by_payment_type'] as $type): ?><?= $type['total_amount'] ?>,<?php endforeach; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    <?php endif; ?>

});
</script>