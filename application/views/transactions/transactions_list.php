<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?= base_url('transactions/record_payment') ?>" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Record Payment
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-filter"></i> Filters
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= base_url('transactions') ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" class="form-control" 
                                               value="<?= isset($filters['start_date']) ? $filters['start_date'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" class="form-control" 
                                               value="<?= isset($filters['end_date']) ? $filters['end_date'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Payment Type</label>
                                        <select name="payment_type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="advance" <?= (isset($filters['payment_type']) && $filters['payment_type'] == 'advance') ? 'selected' : '' ?>>Advance</option>
                                            <option value="installment" <?= (isset($filters['payment_type']) && $filters['payment_type'] == 'installment') ? 'selected' : '' ?>>Installment</option>
                                            <option value="full_payment" <?= (isset($filters['payment_type']) && $filters['payment_type'] == 'full_payment') ? 'selected' : '' ?>>Full Payment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select name="payment_method" class="form-control">
                                            <option value="">All Methods</option>
                                            <option value="cash" <?= (isset($filters['payment_method']) && $filters['payment_method'] == 'cash') ? 'selected' : '' ?>>Cash</option>
                                            <option value="cheque" <?= (isset($filters['payment_method']) && $filters['payment_method'] == 'cheque') ? 'selected' : '' ?>>Cheque</option>
                                            <option value="bank_transfer" <?= (isset($filters['payment_method']) && $filters['payment_method'] == 'bank_transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                                            <option value="online" <?= (isset($filters['payment_method']) && $filters['payment_method'] == 'online') ? 'selected' : '' ?>>Online</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-info">
                                                <i class="fa fa-search"></i> Filter
                                            </button>
                                            <a href="<?= base_url('transactions') ?>" class="btn btn-secondary">
                                                <i class="fa fa-refresh"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Transaction History
                    </div>
                    <div class="card-body">
                        <?php if (!empty($transactions)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Receipt #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Property</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Method</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr>
                                                <td><?= $transaction['receipt_number'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($transaction['payment_date'])) ?></td>
                                                <td><?= $transaction['plot_buyer_name'] ?: 'N/A' ?></td>
                                                <td><?= $transaction['garden_name'] ?: 'N/A' ?></td>
                                                <td>₹<?= number_format($transaction['amount'], 2) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $transaction['payment_type'] == 'full_payment' ? 'success' : ($transaction['payment_type'] == 'advance' ? 'warning' : 'info') ?>">
                                                        <?= ucfirst(str_replace('_', ' ', $transaction['payment_type'])) ?>
                                                    </span>
                                                </td>
                                                <td><?= ucfirst(str_replace('_', ' ', $transaction['payment_method'])) ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?= base_url('transactions/view/' . $transaction['id']) ?>" 
                                                           class="btn btn-sm btn-info" title="View Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="<?= base_url('transactions/receipt/' . $transaction['id']) ?>" 
                                                           class="btn btn-sm btn-success" title="Print Receipt" target="_blank">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                        <a href="<?= base_url('transactions/edit/' . $transaction['id']) ?>" 
                                                           class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No transactions found matching your criteria.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>