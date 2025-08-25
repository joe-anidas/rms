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
                    <li class="breadcrumb-item active" aria-current="page">Delete Transaction</li>
                </ol>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <i class="fa fa-trash"></i> Delete Transaction
                    </div>
                    <div class="card-body">
                        
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Warning:</strong> You are about to delete this transaction. This action cannot be undone and will affect the registration balance calculations.
                        </div>

                        <h5>Transaction Details:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Receipt Number:</strong></td>
                                        <td><?= $transaction['receipt_number'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registration:</strong></td>
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
                                        <td><strong>Amount:</strong></td>
                                        <td class="text-danger"><strong>₹<?= number_format($transaction['amount'], 2) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Date:</strong></td>
                                        <td><?= date('d/m/Y', strtotime($transaction['payment_date'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Type:</strong></td>
                                        <td><?= ucfirst(str_replace('_', ' ', $transaction['payment_type'])) ?></td>
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

                        <hr>

                        <form method="POST" action="<?= current_url() ?>">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="confirm_delete" name="confirm_delete" value="1" required>
                                    <label class="form-check-label" for="confirm_delete">
                                        I understand that this action cannot be undone and will permanently delete this transaction.
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                                    <i class="fa fa-trash"></i> Delete Transaction
                                </button>
                                <a href="<?= base_url('transactions/view/' . $transaction['id']) ?>" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    $('#confirm_delete').change(function() {
        if ($(this).is(':checked')) {
            $('#deleteBtn').prop('disabled', false);
        } else {
            $('#deleteBtn').prop('disabled', true);
        }
    });
});
</script>