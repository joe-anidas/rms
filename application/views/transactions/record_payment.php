<div class="row">
  <div class="col-12 col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Record Payment</h5>
      </div>
      <div class="card-body">
        <form id="paymentForm" method="post">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="plot_id">Select Plot/Property *</label>
                <select class="form-control" id="plot_id" name="plot_id" required>
                  <option value="">Choose Plot/Property</option>
                  <?php if(isset($plots) && is_array($plots)): ?>
                    <?php foreach($plots as $plot): ?>
                      <option value="<?php echo $plot->id; ?>" 
                              data-value="<?php echo $plot->plot_value; ?>"
                              data-customer="<?php echo $plot->customer_name; ?>"
                              data-status="<?php echo $plot->status; ?>">
                        <?php echo $plot->garden_name . ' - Plot ' . $plot->plot_no . ' (' . ucfirst($plot->status) . ')'; ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label for="customer_id">Customer (Optional)</label>
                <select class="form-control" id="customer_id" name="customer_id">
                  <option value="">Select Customer</option>
                  <?php if(isset($customers) && is_array($customers)): ?>
                    <?php foreach($customers as $customer): ?>
                      <option value="<?php echo $customer->id; ?>">
                        <?php echo $customer->plot_buyer_name . ' - ' . $customer->phone_number_1; ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="transaction_type">Transaction Type *</label>
                <select class="form-control" id="transaction_type" name="transaction_type" required>
                  <option value="">Select Type</option>
                  <option value="advance">Advance Payment</option>
                  <option value="installment">Installment Payment</option>
                  <option value="full_payment">Full Payment</option>
                  <option value="refund">Refund</option>
                </select>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group">
                <label for="amount">Amount *</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group">
                <label for="payment_date">Payment Date *</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="payment_method">Payment Method *</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                  <option value="">Select Method</option>
                  <option value="cash">Cash</option>
                  <option value="cheque">Cheque</option>
                  <option value="bank_transfer">Bank Transfer</option>
                  <option value="online">Online Payment</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group">
                <label for="receipt_number">Receipt Number</label>
                <input type="text" class="form-control" id="receipt_number" name="receipt_number">
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group">
                <label for="reference_number">Reference Number</label>
                <input type="text" class="form-control" id="reference_number" name="reference_number">
              </div>
            </div>
          </div>

          <div class="row" id="cheque_details" style="display: none;">
            <div class="col-md-6">
              <div class="form-group">
                <label for="cheque_number">Cheque Number</label>
                <input type="text" class="form-control" id="cheque_number" name="cheque_number">
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label for="bank_name">Bank Name</label>
                <input type="text" class="form-control" id="bank_name" name="bank_name">
              </div>
            </div>
          </div>

          <div class="row" id="installment_details" style="display: none;">
            <div class="col-md-6">
              <div class="form-group">
                <label for="installment_number">Installment Number</label>
                <input type="number" class="form-control" id="installment_number" name="installment_number" min="1">
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label for="total_installments">Total Installments</label>
                <input type="number" class="form-control" id="total_installments" name="total_installments" min="1">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">Record Payment</button>
              <button type="reset" class="btn btn-secondary">Reset</button>
              <a href="<?php echo base_url('transactions'); ?>" class="btn btn-info">Back to Transactions</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Plot Information Card -->
<div class="row" id="plot_info" style="display: none;">
  <div class="col-12 col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Plot Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <strong>Plot Value:</strong> <span id="plot_value">-</span>
          </div>
          <div class="col-md-4">
            <strong>Customer:</strong> <span id="plot_customer">-</span>
          </div>
          <div class="col-md-4">
            <strong>Status:</strong> <span id="plot_status">-</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Set default date to today
    $('#payment_date').val(new Date().toISOString().split('T')[0]);
    
    // Show/hide cheque details based on payment method
    $('#payment_method').change(function() {
        if ($(this).val() === 'cheque') {
            $('#cheque_details').show();
        } else {
            $('#cheque_details').hide();
        }
    });
    
    // Show/hide installment details based on transaction type
    $('#transaction_type').change(function() {
        if ($(this).val() === 'installment') {
            $('#installment_details').show();
        } else {
            $('#installment_details').hide();
        }
    });
    
    // Show plot information when plot is selected
    $('#plot_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var plotValue = selectedOption.data('value');
        var customer = selectedOption.data('customer');
        var status = selectedOption.data('status');
        
        if (plotValue || customer || status) {
            $('#plot_value').text(plotValue ? '₹' + plotValue : 'N/A');
            $('#plot_customer').text(customer || 'N/A');
            $('#plot_status').text(status ? status.charAt(0).toUpperCase() + status.slice(1) : 'N/A');
            $('#plot_info').show();
        } else {
            $('#plot_info').hide();
        }
    });
    
    // Form submission
    $('#paymentForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo base_url("transactions/submit_payment"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Payment recorded successfully!');
                    window.location.href = '<?php echo base_url("transactions"); ?>';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
