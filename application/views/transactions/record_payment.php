<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions') ?>">Transactions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Record Payment</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-money"></i> Record New Payment
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

                        <form method="POST" action="<?= current_url() ?>" id="paymentForm">
                            
                            <div class="form-group">
                                <label for="registration_id">Registration *</label>
                                <select name="registration_id" id="registration_id" class="form-control" required>
                                    <option value="">Select Registration</option>
                                    <?php if (isset($registrations)): ?>
                                        <?php foreach ($registrations as $registration): ?>
                                            <option value="<?= $registration['id'] ?>" 
                                                    <?= (isset($registration) && $registration['id'] == set_value('registration_id')) ? 'selected' : '' ?>>
                                                <?= $registration['registration_number'] ?> - 
                                                <?= $registration['plot_buyer_name'] ?> - 
                                                <?= $registration['garden_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount">Amount *</label>
                                        <input type="number" name="amount" id="amount" class="form-control" 
                                               step="0.01" min="0.01" value="<?= set_value('amount') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_date">Payment Date *</label>
                                        <input type="date" name="payment_date" id="payment_date" class="form-control" 
                                               value="<?= set_value('payment_date', date('Y-m-d')) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type *</label>
                                        <select name="payment_type" id="payment_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="advance" <?= set_select('payment_type', 'advance') ?>>Advance</option>
                                            <option value="installment" <?= set_select('payment_type', 'installment') ?>>Installment</option>
                                            <option value="full_payment" <?= set_select('payment_type', 'full_payment') ?>>Full Payment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method *</label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">Select Method</option>
                                            <option value="cash" <?= set_select('payment_method', 'cash') ?>>Cash</option>
                                            <option value="cheque" <?= set_select('payment_method', 'cheque') ?>>Cheque</option>
                                            <option value="bank_transfer" <?= set_select('payment_method', 'bank_transfer') ?>>Bank Transfer</option>
                                            <option value="online" <?= set_select('payment_method', 'online') ?>>Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                          placeholder="Optional payment notes..."><?= set_value('notes') ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Record Payment
                                </button>
                                <a href="<?= base_url('transactions') ?>" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Back to Transactions
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card" id="balanceCard" style="display: none;">
                    <div class="card-header">
                        <i class="fa fa-calculator"></i> Balance Information
                    </div>
                    <div class="card-body" id="balanceInfo">
                        <!-- Balance information will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    $('#registration_id').change(function() {
        var registrationId = $(this).val();
        if (registrationId) {
            $.post('<?= base_url('transactions/ajax_get_balance') ?>', {
                registration_id: registrationId
            }, function(response) {
                var data = JSON.parse(response);
                if (data.error) {
                    $('#balanceCard').hide();
                } else {
                    var html = '<table class="table table-sm">';
                    html += '<tr><td><strong>Total Amount:</strong></td><td>₹' + parseFloat(data.total_amount).toLocaleString() + '</td></tr>';
                    html += '<tr><td><strong>Paid Amount:</strong></td><td>₹' + parseFloat(data.total_paid).toLocaleString() + '</td></tr>';
                    html += '<tr><td><strong>Balance:</strong></td><td class="' + (data.balance > 0 ? 'text-danger' : 'text-success') + '">₹' + parseFloat(data.balance).toLocaleString() + '</td></tr>';
                    html += '<tr><td><strong>Status:</strong></td><td><span class="badge badge-' + (data.payment_status == 'fully_paid' ? 'success' : (data.payment_status == 'partially_paid' ? 'warning' : 'danger')) + '">' + data.payment_status.replace('_', ' ').toUpperCase() + '</span></td></tr>';
                    html += '</table>';
                    
                    $('#balanceInfo').html(html);
                    $('#balanceCard').show();
                }
            });
        } else {
            $('#balanceCard').hide();
        }
    });
});
</script>