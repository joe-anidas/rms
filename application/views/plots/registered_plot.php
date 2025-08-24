<div class="container mt-3">
    <!-- Page 1: Plot Details -->
    <form id="registeredPlotForm">
      <div class="card">
        <div class="card-header">Register Plot Details
          <div class="card-action">
            <div class="dropdown">
              <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                <i class="icon-options"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="javascript:void();">Action</a>
                <a class="dropdown-item" href="javascript:void();">Another action</a>
                <a class="dropdown-item" href="javascript:void();">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void();">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="reg-0026" placeholder="S.No" value="1" readonly>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control" id="garden-name" name="garden_id" required>
                  <option value="">Select Nagar/Garden</option>
                  <option value="1" selected>Sample Garden</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="plot-no" name="plot_no" placeholder="Select Plot No" value="A001" required>
              </div>
            </div>
          </div>

          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="plot-extension" name="plot_extension" placeholder="Total Plot Extension in Sqft/Sqmt" value="1200" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="doc-number" name="doc_number" placeholder="Plot Registration Document Number" value="DOC001" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="date" class="form-control" id="reg-date" name="reg_date" placeholder="Plot Registration Date" value="2024-01-15" required>
              </div>
            </div>
          </div>

          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="patta-chitta" name="patta_chitta" placeholder="Patta/Chitta No" value="P123456" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="ts-no" name="ts_no" placeholder="T.S.No" value="TS001" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="ward-block" name="ward_block" placeholder="Ward/Block" value="Ward 1" required>
              </div>
            </div>
          </div>

          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="plot-rate" name="plot_rate" placeholder="Plot Rate/Sqft" value="5000" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control" id="employee" name="employee" required>
                  <option value="">Name Refered By</option>
                  <option value="John Doe" selected>John Doe</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="alt-phone" placeholder="Alternative Phone Number" value="9876543210">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">Enter Plot Four Side Extension Value in Sqft/Sqmt</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dynamicTable" class="table align-items-center table-flush table-borderless">
              <thead>
                <tr>
               
                  <th>PLOT NO</th>
                  <th>PLOT EXTENSION</th>
                  <th>NORTH</th>
                  <th>EAST</th>
                  <th>WEST</th>
                  <th>SOUTH</th>
                  <th>PLOT VALUE</th>
                  <th>STATUS</th>
                </tr>
              </thead>
              <tbody>
                <tr>
         
                  <td><input type="text" class="form-control form-control-sm" name="plot_no" value="A001" readonly></td>
                  <td><input type="text" class="form-control form-control-sm" name="plot_extension" value="1200" readonly></td>
                  <td><input type="text" class="form-control form-control-sm" name="north" value="30" required></td>
                  <td><input type="text" class="form-control form-control-sm" name="east" value="40" required></td>
                  <td><input type="text" class="form-control form-control-sm" name="west" value="30" required></td>
                  <td><input type="text" class="form-control form-control-sm" name="south" value="40" required></td>
                  <td><input type="text" class="form-control form-control-sm plot-value" name="plot_value" value="6000000" readonly></td>
                  <td><input type="text" class="form-control form-control-sm" placeholder="UnSold" value="Registered" readonly></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">REGISTRATION OFFICE DETAILS</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-items-center table-flush table-borderless">
              <tbody>
                <tr>
                  <td>Registration District</td>
                  <td><input type="text" class="form-control" value="Chennai" readonly></td>
                </tr>
                <tr>
                  <td>Registration Sub-District</td>
                  <td><input type="text" class="form-control" value="Tambaram" readonly></td>
                </tr>
                <tr>
                  <td>Town/Village</td>
                  <td><input type="text" class="form-control" value="Tambaram" readonly></td>
                </tr>
                <tr>
                  <td>Revenue Taluk</td>
                  <td><input type="text" class="form-control" value="Tambaram" readonly></td>
                </tr>
                <tr>
                  <td>Sub Registrar</td>
                  <td><input type="text" class="form-control" value="Sub Registrar Office" readonly></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    
    <!-- Page 2: Customer Details -->
      <div class="card mt-3">
        <div class="card-header">Customer Details</div>
        <div class="card-body">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="customer_name" placeholder="Enter Plot Buyer Name" value="Ramesh Kumar" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="father_name" placeholder="Enter Father Name" value="Suresh Kumar" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control" name="customer_district" required>
                  <option value="">Select District</option>
                  <option value="Chennai" selected>Chennai</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="customer_pincode" placeholder="Pincode" value="600045" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="customer_taluk" placeholder="Taluk Name" value="Tambaram" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="customer_village_town" placeholder="Village/Town Name" value="Tambaram" required>
              </div>
            </div>
          </div>
          
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="customer_address" placeholder="Street Address" value="123 Main Street" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="customer_phone" placeholder="Phone Number 1" value="9876543210" required>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="customer_phone2" placeholder="Phone Number 2" value="8765432109">
              </div>
            </div>
          </div>
          
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control" name="id_proof_type" required>
                  <option value="">Id Proof</option>
                  <option value="Aadhar Card" selected>Aadhar Card</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" name="id_proof_number" placeholder="Aadhar" value="123456789012" required>
              </div>
            </div>
          </div>
        </div>
      </div>
    
    <!-- Page 3: Payment Details -->
      <div class="card mt-3">
        <div class="card-header">Payment Details</div>
        <div class="card-body">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control" name="payment_method" required>
                  <option value="">Mode of Payment</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque" selected>Cheque</option>
                  <option value="UPI">UPI</option>
                  <option value="NEFT/RTGS">NEFT/RTGS</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Amount" value="6000000" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
    
    <!-- Page 4: Upload Documents -->
      <div class="card mt-3">
        <div class="card-header">Upload Documents</div>
        <div class="card-body">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-6 border-light">
              <div class="card-body">
                <label>Upload Registered Title Deed Document</label>
                <input type="file" class="form-control" name="title_deed">
              </div>
            </div>
            <div class="col-12 col-lg-6 border-light">
              <div class="card-body">
                <label>Upload Plot Sketch</label>
                <input type="file" class="form-control" name="plot_sketch">
              </div>
            </div>
          </div>
          
          <div class="row mt-3">
            <div class="col-12">
              <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea class="form-control" name="notes" rows="3" placeholder="Enter any additional notes here..."></textarea>
              </div>
            </div>
          </div>
          
          <div class="row mt-3">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary px-5">
                <i class="fa fa-save mr-2"></i>Submit Plot Registration
              </button>
              <a href="<?php echo base_url('plots/overview'); ?>" class="btn btn-secondary px-5 ml-2">
                <i class="fa fa-eye mr-2"></i>View All Plots
              </a>
            </div>
          </div>
        </div>
      </div>
    </form>
</div>

<script>
document.getElementById('registeredPlotForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Submitting...';
    submitBtn.disabled = true;
    
    fetch('<?php echo base_url('plots/submit-registered'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Plot registered successfully!');
            // Redirect to plots overview
            window.location.href = '<?php echo base_url('plots/overview'); ?>';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting plot registration');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Auto-calculate plot value based on extension and rate
document.getElementById('plot-extension').addEventListener('input', function() {
    const extension = parseFloat(this.value) || 0;
    const rate = parseFloat(document.getElementById('plot-rate').value) || 0;
    const plotValue = extension * rate;
    
    document.querySelector('input[name="plot_value"]').value = plotValue.toLocaleString();
});

document.getElementById('plot-rate').addEventListener('input', function() {
    const extension = parseFloat(document.getElementById('plot-extension').value) || 0;
    const rate = parseFloat(this.value) || 0;
    const plotValue = extension * rate;
    
    document.querySelector('input[name="plot_value"]').value = plotValue.toLocaleString();
});
</script>