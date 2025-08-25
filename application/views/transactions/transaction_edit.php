<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions') ?>">Transactions</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions/view/' . $transaction['id']) ?>">Transaction Details</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Transaction</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-edit"></i> Edit Transaction
                    </div>
                    <div class="card-body">
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i> <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger">
                                <?= validation_errors() ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= current_url() ?>">
                            
                            <!-- Transaction Info (Read-only) -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Receipt Number</label>
                                        <input type="text" class="form-control" value="<?= $transaction['receipt_number'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Registration Number</label>
                                        <input type="text" class="form-control" value="<?= $transaction['registration_number'] ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer</label>
                                        <input type="text" class="form-control" value="<?= $transaction['plot_buyer_name'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Property</label>
                                        <input type="text" class="form-control" value="<?= $transaction['garden_name'] ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Editable Fields -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount">Amount *</label>
                                        <input type="number" name="amount" id="amount" class="form-control" 
                                               step="0.01" min="0.01" value="<?= set_value('amount', $transaction['amount']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_date">Payment Date *</label>
                                        <input type="date" name="payment_date" id="payment_date" class="form-control" 
                                               value="<?= set_value('payment_date', $transaction['payment_date']) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type *</label>
                                        <select name="payment_type" id="payment_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="advance" <?= set_select('payment_type', 'advance', $transaction['payment_type'] == 'advance') ?>>Advance</option>
                                            <option value="installment" <?= set_select('payment_type', 'installment', $transaction['payment_type'] == 'installment') ?>>Installment</option>
                                            <option value="full_payment" <?= set_select('payment_type', 'full_payment', $transaction['payment_type'] == 'full_payment') ?>>Full Payment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method *</label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">Select Method</option>
                                            <option value="cash" <?= set_select('payment_method', 'cash', $transaction['payment_method'] == 'cash') ?>>Cash</option>
                                            <option value="cheque" <?= set_select('payment_method', 'cheque', $transaction['payment_method'] == 'cheque') ?>>Cheque</option>
                                            <option value="bank_transfer" <?= set_select('payment_method', 'bank_transfer', $transaction['payment_method'] == 'bank_transfer') ?>>Bank Transfer</option>
                                            <option value="online" <?= set_select('payment_method', 'online', $transaction['payment_method'] == 'online') ?>>Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                          placeholder="Optional payment notes..."><?= set_value('notes', $transaction['notes']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Transaction
                                </button>
                                <a href="<?= base_url('transactions/view/' . $transaction['id']) ?>" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Cancel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-info-circle"></i> Edit Information
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Important:</strong> Editing this transaction will recalculate the registration balance and may affect payment schedules.
                        </div>
                        
                        <h6>Original Transaction:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Amount:</strong> ₹<?= number_format($transaction['amount'], 2) ?></li>
                            <li><strong>Date:</strong> <?= date('d/m/Y', strtotime($transaction['payment_date'])) ?></li>
                            <li><strong>Type:</strong> <?= ucfirst(str_replace('_', ' ', $transaction['payment_type'])) ?></li>
                            <li><strong>Method:</strong> <?= ucfirst(str_replace('_', ' ', $transaction['payment_method'])) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>