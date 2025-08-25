<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions') ?>">Transactions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transaction Details</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?= base_url('transactions/receipt/' . $transaction['id']) ?>" 
                       class="btn btn-success" target="_blank">
                        <i class="fa fa-print"></i> Print Receipt
                    </a>
                    <a href="<?= base_url('transactions/edit/' . $transaction['id']) ?>" 
                       class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Transaction Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-info-circle"></i> Transaction Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Receipt Number:</strong></td>
                                        <td><?= $transaction['receipt_number'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registration Number:</strong></td>
                                        <td><?= $transaction['registration_number'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer:</strong></td>
                                        <td><?= $transaction['plot_buyer_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Property:</strong></td>
                                        <td><?= $transaction['garden_name'] ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Payment Date:</strong></td>
                                        <td><?= date('d/m/Y', strtotime($transaction['payment_date'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount:</strong></td>
                                        <td class="text-success"><strong>₹<?= number_format($transaction['amount'], 2) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Type:</strong></td>
                                        <td>
                                            <span class="badge badge-<?= $transaction['payment_type'] == 'full_payment' ? 'success' : ($transaction['payment_type'] == 'advance' ? 'warning' : 'info') ?>">
                                                <?= ucfirst(str_replace('_', ' ', $transaction['payment_type'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                        <td><?= ucfirst(str_replace('_', ' ', $transaction['payment_method'])) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <?php if (!empty($transaction['notes'])): ?>
                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <h6><strong>Notes:</strong></h6>
                                    <p class="text-muted"><?= nl2br(htmlspecialchars($transaction['notes'])) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Balance Information -->
            <div class="col-lg-4">
                <?php if (isset($balance_info) && !isset($balance_info['error'])): ?>
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-calculator"></i> Balance Information
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>₹<?= number_format($balance_info['total_amount'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Paid:</strong></td>
                                    <td>₹<?= number_format($balance_info['total_paid'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Balance:</strong></td>
                                    <td class="<?= $balance_info['balance'] > 0 ? 'text-danger' : 'text-success' ?>">
                                        <strong>₹<?= number_format($balance_info['balance'], 2) ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-<?= $balance_info['payment_status'] == 'fully_paid' ? 'success' : ($balance_info['payment_status'] == 'partially_paid' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst(str_replace('_', ' ', $balance_info['payment_status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-bolt"></i> Quick Actions
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('transactions/record_payment/' . $transaction['registration_id']) ?>" 
                               class="btn btn-primary btn-block">
                                <i class="fa fa-plus"></i> Record Another Payment
                            </a>
                            <a href="<?= base_url('transactions/schedule/' . $transaction['registration_id']) ?>" 
                               class="btn btn-info btn-block">
                                <i class="fa fa-calendar"></i> View Payment Schedule
                            </a>
                            <a href="<?= base_url('registrations/view/' . $transaction['registration_id']) ?>" 
                               class="btn btn-secondary btn-block">
                                <i class="fa fa-file-text"></i> View Registration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Schedule -->
        <?php if (!empty($payment_schedule)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-calendar"></i> Payment Schedule
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Installment #</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Paid Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payment_schedule as $schedule): ?>
                                            <tr class="<?= $schedule['status'] == 'overdue' ? 'table-danger' : ($schedule['status'] == 'paid' ? 'table-success' : '') ?>">
                                                <td><?= $schedule['installment_number'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($schedule['due_date'])) ?></td>
                                                <td>₹<?= number_format($schedule['amount'], 2) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $schedule['status'] == 'paid' ? 'success' : ($schedule['status'] == 'overdue' ? 'danger' : 'warning') ?>">
                                                        <?= ucfirst($schedule['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= $schedule['paid_date'] ? date('d/m/Y', strtotime($schedule['paid_date'])) : '-' ?></td>
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