
<div class="card mt-3">
    <div class="card-header">Add Staff Details
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
        <form id="staffForm" method="POST" action="<?php echo base_url('submit_staff'); ?>">
            <!-- Personal Information -->
            <h5 class="text-primary mb-3"><i class="fa fa-user mr-2"></i>Personal Information</h5>
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Employee Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="employee_name" value="Sample Employee Name" required>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Father's Name</label>
                        <input type="text" class="form-control" name="father_name" value="Sample Father Name">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="1990-01-01">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" selected>Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Marital Status</label>
                        <select class="form-control" name="marital_status">
                            <option value="">Select Status</option>
                            <option value="Single" selected>Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select class="form-control" name="blood_group">
                            <option value="">Select Blood Group</option>
                            <option value="A+" selected>A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <h5 class="text-primary mb-3 mt-4"><i class="fa fa-phone mr-2"></i>Contact Information</h5>
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" name="contact_number" value="9876543210" maxlength="10">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Alternate Contact</label>
                        <input type="text" class="form-control" name="alternate_contact" value="9876543211" maxlength="10">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" class="form-control" name="email_address" value="sample.employee@example.com">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label>Permanent Address</label>
                        <textarea class="form-control" name="permanent_address" rows="2">123 Sample Street, Sample City, Sample State - 123456</textarea>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label>Current Address</label>
                        <textarea class="form-control" name="current_address" rows="2">456 Current Street, Current City, Current State - 654321</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Emergency Contact -->
            <h5 class="text-primary mb-3 mt-4"><i class="fa fa-exclamation-triangle mr-2"></i>Emergency Contact</h5>
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Emergency Contact Name</label>
                        <input type="text" class="form-control" name="emergency_contact_name" value="Emergency Contact Person">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Emergency Contact Phone</label>
                        <input type="text" class="form-control" name="emergency_contact_phone" value="9876543212" maxlength="10">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Relation</label>
                        <input type="text" class="form-control" name="emergency_contact_relation" value="Father">
                    </div>
                </div>
            </div>
            
            <!-- Professional Information -->
            <h5 class="text-primary mb-3 mt-4"><i class="fa fa-briefcase mr-2"></i>Professional Information</h5>
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Designation</label>
                        <input type="text" class="form-control" name="designation" value="Software Developer">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" class="form-control" name="department" value="IT Department">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Joining Date</label>
                        <input type="date" class="form-control" name="joining_date" value="2023-01-01">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Salary</label>
                        <input type="number" class="form-control" name="salary" value="50000" step="0.01">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>ID Proof Type</label>
                        <select class="form-control" name="id_proof_type">
                            <option value="">Select ID Proof</option>
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
                        <label>ID Proof Number</label>
                        <input type="text" class="form-control" name="id_proof_number" value="123456789012">
                    </div>
                </div>
            </div>
            
            <!-- Banking Information -->
            <h5 class="text-primary mb-3 mt-4"><i class="fa fa-university mr-2"></i>Banking Information</h5>
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" value="Sample Bank">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Bank Account Number</label>
                        <input type="text" class="form-control" name="bank_account_number" value="1234567890">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>IFSC Code</label>
                        <input type="text" class="form-control" name="ifsc_code" value="SMPL0001234">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>PAN Number</label>
                        <input type="text" class="form-control" name="pan_number" value="ABCDE1234F">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Aadhar Number</label>
                        <input type="text" class="form-control" name="aadhar_number" value="123456789012" maxlength="12">
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="row row-group m-0 mt-4">
                <div class="col-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fa fa-save mr-2"></i>Submit Staff Details
                        </button>
                        <button type="reset" class="btn btn-secondary btn-lg px-5 ml-2">
                            <i class="fa fa-refresh mr-2"></i>Reset Form
                        </button>
                        <a href="<?php echo base_url('staff_list'); ?>" class="btn btn-info btn-lg px-5 ml-2">
                            <i class="fa fa-list mr-2"></i>View All Staff
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Staff form submission
document.getElementById('staffForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Submitting...';
    submitBtn.disabled = true;
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit form via AJAX
    fetch('<?php echo base_url("submit_staff"); ?>', {
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
            showAlert('success', data.message + ' Redirecting to staff list...');
            // Reset form
            this.reset();
            // Redirect to staff list after 2 seconds
            setTimeout(() => {
                window.location.href = '<?php echo base_url("staff_list"); ?>';
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
    const form = document.getElementById('staffForm');
    form.parentNode.insertBefore(alertDiv, form);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>