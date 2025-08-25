<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?= $title ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('transactions') ?>">Transactions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Payment Schedule</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-calendar"></i> Create Payment Schedule
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

                        <!-- Registration Info -->
                        <div class="alert alert-info">
                            <h6><i class="fa fa-info-circle"></i> Registration Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Registration Number:</strong> <?= $registration['registration_number'] ?><br>
                                    <strong>Customer:</strong> <?= $registration['plot_buyer_name'] ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Property:</strong> <?= $registration['garden_name'] ?><br>
                                    <strong>Total Amount:</strong> ₹<?= number_format($registration['total_amount'], 2) ?>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="<?= current_url() ?>" id="scheduleForm">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount *</label>
                                        <input type="number" name="total_amount" id="total_amount" class="form-control" 
                                               step="0.01" min="0.01" value="<?= set_value('total_amount', $registration['total_amount']) ?>" required>
                                        <small class="form-text text-muted">This will be divided into equal installments</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="installment_count">Number of Installments *</label>
                                        <select name="installment_count" id="installment_count" class="form-control" required>
                                            <option value="">Select Installments</option>
                                            <?php for ($i = 2; $i <= 60; $i++): ?>
                                                <option value="<?= $i ?>" <?= set_select('installment_count', $i) ?>><?= $i ?> Installments</option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">First Installment Date *</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" 
                                               value="<?= set_value('start_date', date('Y-m-d', strtotime('+1 month'))) ?>" required>
                                        <small class="form-text text-muted">Subsequent installments will be monthly</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Installment Amount</label>
                                        <input type="text" id="installment_amount" class="form-control" readonly>
                                        <small class="form-text text-muted">Calculated automatically</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create Payment Schedule
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
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-info-circle"></i> Schedule Preview
                    </div>
                    <div class="card-body">
                        <div id="schedulePreview">
                            <p class="text-muted">Select total amount and number of installments to see preview</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-lightbulb-o"></i> Tips
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-check text-success"></i> Installments are calculated monthly</li>
                            <li><i class="fa fa-check text-success"></i> Each installment will have the same amount</li>
                            <li><i class="fa fa-check text-success"></i> You can modify individual installments later</li>
                            <li><i class="fa fa-check text-success"></i> Overdue installments will be marked automatically</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    function updatePreview() {
        var totalAmount = parseFloat($('#total_amount').val()) || 0;
        var installmentCount = parseInt($('#installment_count').val()) || 0;
        var startDate = $('#start_date').val();

        if (totalAmount > 0 && installmentCount > 0) {
            var installmentAmount = totalAmount / installmentCount;
            $('#installment_amount').val('₹' + installmentAmount.toFixed(2));

            if (startDate) {
                var html = '<h6>Schedule Preview:</h6>';
                html += '<div class="table-responsive">';
                html += '<table class="table table-sm">';
                html += '<thead><tr><th>#</th><th>Due Date</th><th>Amount</th></tr></thead>';
                html += '<tbody>';

                var currentDate = new Date(startDate);
                for (var i = 1; i <= Math.min(installmentCount, 5); i++) {
                    html += '<tr>';
                    html += '<td>' + i + '</td>';
                    html += '<td>' + currentDate.toLocaleDateString() + '</td>';
                    html += '<td>₹' + installmentAmount.toFixed(2) + '</td>';
                    html += '</tr>';
                    currentDate.setMonth(currentDate.getMonth() + 1);
                }

                if (installmentCount > 5) {
                    html += '<tr><td colspan="3" class="text-center text-muted">... and ' + (installmentCount - 5) + ' more</td></tr>';
                }

                html += '</tbody></table></div>';
                html += '<p class="text-muted small">Total: ₹' + totalAmount.toFixed(2) + ' in ' + installmentCount + ' installments</p>';

                $('#schedulePreview').html(html);
            }
        } else {
            $('#installment_amount').val('');
            $('#schedulePreview').html('<p class="text-muted">Select total amount and number of installments to see preview</p>');
        }
    }

    $('#total_amount, #installment_count, #start_date').on('input change', updatePreview);
    
    // Initial calculation
    updatePreview();
});
</script>