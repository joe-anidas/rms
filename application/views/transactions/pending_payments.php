<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions') ?>">Transactions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pending Payments</li>
                </ol>
            </div>
        </div>

        <!-- Filter -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-filter"></i> Filter Options
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= current_url() ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Days Ahead</label>
                                        <select name="days_ahead" class="form-control">
                                            <option value="7" <?= $days_ahead == 7 ? 'selected' : '' ?>>Next 7 days</option>
                                            <option value="15" <?= $days_ahead == 15 ? 'selected' : '' ?>>Next 15 days</option>
                                            <option value="30" <?= $days_ahead == 30 ? 'selected' : '' ?>>Next 30 days</option>
                                            <option value="60" <?= $days_ahead == 60 ? 'selected' : '' ?>>Next 60 days</option>
                                            <option value="90" <?= $days_ahead == 90 ? 'selected' : '' ?>>Next 90 days</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Filter
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

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?= count($pending_payments) ?></h4>
                                <p class="mb-0">Upcoming Payments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-clock-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?= count($overdue_payments) ?></h4>
                                <p class="mb-0">Overdue Payments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>₹<?php 
                                    $total_pending = 0;
                                    foreach ($pending_payments as $payment) {
                                        $total_pending += $payment['amount'];
                                    }
                                    foreach ($overdue_payments as $payment) {
                                        $total_pending += $payment['amount'];
                                    }
                                    echo number_format($total_pending, 0);
                                ?></h4>
                                <p class="mb-0">Total Amount</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-money fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Payments -->
        <?php if (!empty($overdue_payments)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <i class="fa fa-exclamation-triangle"></i> Overdue Payments
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Due Date</th>
                                            <th>Customer</th>
                                            <th>Contact</th>
                                            <th>Property</th>
                                            <th>Installment #</th>
                                            <th>Amount</th>
                                            <th>Days Overdue</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($overdue_payments as $payment): ?>
                                            <?php
                                            $due_date = new DateTime($payment['due_date']);
                                            $today = new DateTime();
                                            $days_overdue = $today->diff($due_date)->format('%a');
                                            ?>
                                            <tr class="table-danger">
                                                <td><?= date('d/m/Y', strtotime($payment['due_date'])) ?></td>
                                                <td><?= $payment['plot_buyer_name'] ?></td>
                                                <td><?= $payment['contact_details'] ?></td>
                                                <td><?= $payment['garden_name'] ?></td>
                                                <td><?= $payment['installment_number'] ?></td>
                                                <td>₹<?= number_format($payment['amount'], 2) ?></td>
                                                <td>
                                                    <span class="badge badge-danger"><?= $days_overdue ?> days</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?= base_url('transactions/record_payment/' . $payment['registration_id']) ?>" 
                                                           class="btn btn-sm btn-success" title="Record Payment">
                                                            <i class="fa fa-money"></i>
                                                        </a>
                                                        <a href="<?= base_url('transactions/schedule/' . $payment['registration_id']) ?>" 
                                                           class="btn btn-sm btn-info" title="View Schedule">
                                                            <i class="fa fa-calendar"></i>
                                                        </a>
                                                        <a href="tel:<?= $payment['contact_details'] ?>" 
                                                           class="btn btn-sm btn-warning" title="Call Customer">
                                                            <i class="fa fa-phone"></i>
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
                </div>
            </div>
        <?php endif; ?>

        <!-- Upcoming Payments -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-clock-o"></i> Upcoming Payments (Next <?= $days_ahead ?> Days)
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pending_payments)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Due Date</th>
                                            <th>Customer</th>
                                            <th>Contact</th>
                                            <th>Property</th>
                                            <th>Registration #</th>
                                            <th>Installment #</th>
                                            <th>Amount</th>
                                            <th>Days Left</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pending_payments as $payment): ?>
                                            <?php
                                            $due_date = new DateTime($payment['due_date']);
                                            $today = new DateTime();
                                            $days_left = $today->diff($due_date)->format('%r%a');
                                            
                                            $row_class = '';
                                            if ($days_left <= 3) {
                                                $row_class = 'table-warning';
                                            } elseif ($days_left <= 7) {
                                                $row_class = 'table-info';
                                            }
                                            ?>
                                            <tr class="<?= $row_class ?>">
                                                <td><?= date('d/m/Y', strtotime($payment['due_date'])) ?></td>
                                                <td><?= $payment['plot_buyer_name'] ?></td>
                                                <td><?= $payment['contact_details'] ?></td>
                                                <td><?= $payment['garden_name'] ?></td>
                                                <td><?= $payment['registration_number'] ?></td>
                                                <td><?= $payment['installment_number'] ?></td>
                                                <td>₹<?= number_format($payment['amount'], 2) ?></td>
                                                <td>
                                                    <?php if ($days_left == 0): ?>
                                                        <span class="badge badge-warning">Due today</span>
                                                    <?php elseif ($days_left == 1): ?>
                                                        <span class="badge badge-warning">Tomorrow</span>
                                                    <?php elseif ($days_left <= 3): ?>
                                                        <span class="badge badge-warning"><?= $days_left ?> days</span>
                                                    <?php elseif ($days_left <= 7): ?>
                                                        <span class="badge badge-info"><?= $days_left ?> days</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary"><?= $days_left ?> days</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?= base_url('transactions/record_payment/' . $payment['registration_id']) ?>" 
                                                           class="btn btn-sm btn-success" title="Record Payment">
                                                            <i class="fa fa-money"></i>
                                                        </a>
                                                        <a href="<?= base_url('transactions/schedule/' . $payment['registration_id']) ?>" 
                                                           class="btn btn-sm btn-info" title="View Schedule">
                                                            <i class="fa fa-calendar"></i>
                                                        </a>
                                                        <a href="<?= base_url('registrations/view/' . $payment['registration_id']) ?>" 
                                                           class="btn btn-sm btn-secondary" title="View Registration">
                                                            <i class="fa fa-file-text"></i>
                                                        </a>
                                                        <a href="tel:<?= $payment['contact_details'] ?>" 
                                                           class="btn btn-sm btn-warning" title="Call Customer">
                                                            <i class="fa fa-phone"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> No pending payments in the next <?= $days_ahead ?> days.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-bolt"></i> Quick Actions
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?= base_url('transactions/record_payment') ?>" class="btn btn-primary btn-block">
                                    <i class="fa fa-plus"></i> Record New Payment
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('transactions/reports') ?>" class="btn btn-info btn-block">
                                    <i class="fa fa-chart-bar"></i> Financial Reports
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('transactions') ?>" class="btn btn-secondary btn-block">
                                    <i class="fa fa-list"></i> All Transactions
                                </a>
                            </div>
                            <div class="col-md-3">
                                <button onclick="window.print()" class="btn btn-outline-primary btn-block">
                                    <i class="fa fa-print"></i> Print Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@media print {
    .no-print, .btn, .card-header, .breadcrumb {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 12px;
    }
}
</style>