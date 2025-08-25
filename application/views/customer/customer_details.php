<div class="card mt-3">
    <div class="card-header">Add Customer Details
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
        <form id="customerForm" method="POST" action="<?php echo base_url('submit_customer'); ?>">
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Plot Buyer Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="plot_buyer_name" placeholder="Enter Plot Buyer Name" value="Sample Customer Name" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Father Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="father_name" placeholder="Father Name" value="Sample Father Name">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>District</label>
                        <select class="form-control" name="district">
                            <option value="">Select District</option>
                            <option value="Bangalore Urban" selected>Bangalore Urban</option>
                            <option value="Bangalore Urban">Bangalore Urban</option>
                            <option value="Bangalore Rural">Bangalore Rural</option>
                            <option value="Mysore">Mysore</option>
                            <option value="Mandya">Mandya</option>
                            <option value="Hassan">Hassan</option>
                            <option value="Tumkur">Tumkur</option>
                            <option value="Kolar">Kolar</option>
                            <option value="Chikkaballapur">Chikkaballapur</option>
                            <option value="Ramanagara">Ramanagara</option>
                            <option value="Chitradurga">Chitradurga</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" class="form-control" name="pincode" placeholder="Pincode" value="560001" maxlength="6">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Taluk Name</label>
                        <input type="text" class="form-control" name="taluk_name" placeholder="Taluk Name" value="Bangalore South">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Village/Town Name</label>
                        <input type="text" class="form-control" name="village_town_name" placeholder="Village/Town Name" value="Indiranagar">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Street Address</label>
                        <textarea class="form-control" name="street_address" placeholder="Street Address" rows="1">123 Main Street, Indiranagar, Bangalore</textarea>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Total Plot Bought</label>
                        <input type="text" class="form-control" name="total_plot_bought" placeholder="Total Plot Bought" value="2 acres">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Phone Number 1</label>
                        <input type="text" class="form-control" name="phone_number_1" placeholder="Phone Number 1" value="9876543210" maxlength="10">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Phone Number 2</label>
                        <input type="text" class="form-control" name="phone_number_2" placeholder="Phone Number 2" value="9876543211" maxlength="10">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>ID Proof</label>
                        <select class="form-control" name="id_proof">
                            <option value="Aadhar" selected>Aadhar</option>
                            <option value="PAN">PAN</option>
                            <option value="Driving License">Driving License</option>
                            <option value="Passport">Passport</option>
                            <option value="Voter ID">Voter ID</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Aadhar</label>
                        <input type="text" class="form-control" name="aadhar_number" placeholder="Aadhar" value="123456789012" maxlength="12">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 text-center">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fa fa-save mr-2"></i>Submit Customer Details
                        </button>
                        <button type="reset" class="btn btn-secondary btn-lg px-5 ml-2">
                            <i class="fa fa-refresh mr-2"></i>Reset Form
                        </button>
                        <a href="<?php echo base_url('customer_list'); ?>" class="btn btn-info btn-lg px-5 ml-2">
                            <i class="fa fa-list mr-2"></i>View All Customers
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
    // Customer form submission
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Submitting...';
        submitBtn.disabled = true;
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit form via AJAX
        fetch('<?php echo base_url("submit_customer"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.status === 'success') {
                // Show success message
                showAlert('success', data.message + ' Redirecting to customer list...');
                // Reset form
                this.reset();
                // Redirect to customer list after 2 seconds
                setTimeout(() => {
                    window.location.href = '<?php echo base_url("customer_list"); ?>';
                }, 2000);
            } else {
                // Show error message
                showAlert('error', data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred: ' + error.message);
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Function to show alerts
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        
        // Insert alert before the form
        const form = document.getElementById('customerForm');
        form.parentNode.insertBefore(alertDiv, form);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Toggle password visibility
    document.querySelectorAll('.fa-eye').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // Update custom file input labels
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || "Choose file";
            this.nextElementSibling.textContent = fileName;
        });
    });
</script>