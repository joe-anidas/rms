<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions') ?>">Transactions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payment Schedule</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?= base_url('transactions/record_payment/' . $registration['id']) ?>" 
                       class="btn btn-primary">
                        <i class="fa fa-plus"></i> Record Payment
                    </a>
                    <?php if (empty($payment_schedule)): ?>
                        <a href="<?= base_url('transactions/create_schedule/' . $registration['id']) ?>" 
                           class="btn btn-info">
                            <i class="fa fa-calendar"></i> Create Schedule
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Registration Info -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-info-circle"></i> Registration Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Registration Number:</strong><br>
                                <?= $registration['registration_number'] ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Customer:</strong><br>
                                <?= $registration['plot_buyer_name'] ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Property:</strong><br>
                                <?= $registration['garden_name'] ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Registration Date:</strong><br>
                                <?= date('d/m/Y', strtotime($registration['registration_date'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Information -->
        <?php if (isset($balance_info) && !isset($balance_info['error'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-calculator"></i> Balance Summary
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-primary">₹<?= number_format($balance_info['total_amount'], 2) ?></h4>
                                        <small class="text-muted">Total Amount</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-success">₹<?= number_format($balance_info['total_paid'], 2) ?></h4>
                                        <small class="text-muted">Total Paid</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="<?= $balance_info['balance'] > 0 ? 'text-danger' : 'text-success' ?>">
                                            ₹<?= number_format($balance_info['balance'], 2) ?>
                                        </h4>
                                        <small class="text-muted">Balance</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <span class="badge badge-<?= $balance_info['payment_status'] == 'fully_paid' ? 'success' : ($balance_info['payment_status'] == 'partially_paid' ? 'warning' : 'danger') ?> p-2">
                                            <?= ucfirst(str_replace('_', ' ', $balance_info['payment_status'])) ?>
                                        </span>
                                        <br><small class="text-muted">Status</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Payment Schedule -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-calendar"></i> Payment Schedule
                    </div>
                    <div class="card-body">
                        <?php if (!empty($payment_schedule)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Installment #</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Paid Date</th>
                                            <th>Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payment_schedule as $schedule): ?>
                                            <?php
                                            $due_date = new DateTime($schedule['due_date']);
                                            $today = new DateTime();
                                            $days_diff = $today->diff($due_date)->format('%r%a');
                                            
                                            $row_class = '';
                                            if ($schedule['status'] == 'overdue') {
                                                $row_class = 'table-danger';
                                            } elseif ($schedule['status'] == 'paid') {
                                                $row_class = 'table-success';
                                            } elseif ($days_diff <= 7 && $days_diff >= 0) {
                                                $row_class = 'table-warning';
                                            }
                                            ?>
                                            <tr class="<?= $row_class ?>">
                                                <td><?= $schedule['installment_number'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($schedule['due_date'])) ?></td>
                                                <td>₹<?= number_format($schedule['amount'], 2) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $schedule['status'] == 'paid' ? 'success' : ($schedule['status'] == 'overdue' ? 'danger' : 'warning') ?>">
                                                        <?= ucfirst($schedule['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= $schedule['paid_date'] ? date('d/m/Y', strtotime($schedule['paid_date'])) : '-' ?></td>
                                                <td>
                                                    <?php if ($schedule['status'] == 'paid'): ?>
                                                        <span class="text-success">Paid</span>
                                                    <?php elseif ($days_diff < 0): ?>
                                                        <span class="text-danger"><?= abs($days_diff) ?> days overdue</span>
                                                    <?php elseif ($days_diff == 0): ?>
                                                        <span class="text-warning">Due today</span>
                                                    <?php else: ?>
                                                        <span class="text-info"><?= $days_diff ?> days left</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No payment schedule found for this registration.
                                <a href="<?= base_url('transactions/create_schedule/' . $registration['id']) ?>" class="btn btn-sm btn-info ml-2">
                                    <i class="fa fa-calendar"></i> Create Schedule
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-history"></i> Payment History
                    </div>
                    <div class="card-body">
                        <?php if (!empty($transactions)): ?>
                            <div class="timeline">
                                <?php foreach ($transactions as $transaction): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-<?= $transaction['payment_type'] == 'full_payment' ? 'success' : 'info' ?>"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">₹<?= number_format($transaction['amount'], 2) ?></h6>
                                            <p class="mb-1 text-muted">
                                                <?= ucfirst(str_replace('_', ' ', $transaction['payment_type'])) ?> - 
                                                <?= ucfirst(str_replace('_', ' ', $transaction['payment_method'])) ?>
                                            </p>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($transaction['payment_date'])) ?>
                                                <br>Receipt: <?= $transaction['receipt_number'] ?>
                                            </small>
                                            <div class="mt-1">
                                                <a href="<?= base_url('transactions/view/' . $transaction['id']) ?>" 
                                                   class="btn btn-xs btn-outline-info">View</a>
                                                <a href="<?= base_url('transactions/receipt/' . $transaction['id']) ?>" 
                                                   class="btn btn-xs btn-outline-success" target="_blank">Receipt</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No payments recorded yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row">
            <div class="col-12">
                <a href="<?= base_url('transactions') ?>" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Transactions
                </a>
            </div>
        </div>

    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 17px;
    width: 2px;
    height: calc(100% + 5px);
    background-color: #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}

.btn-xs {
    padding: 2px 6px;
    font-size: 11px;
}
</style>