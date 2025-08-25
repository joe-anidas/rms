<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-<?php echo $action === 'edit' ? 'edit' : 'plus'; ?> mr-2"></i>
                        <?php echo $action === 'edit' ? 'Edit Staff Member' : 'Add New Staff Member'; ?>
                    </h5>
                    <a href="<?php echo base_url('staff'); ?>" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left mr-1"></i>Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="staffForm" method="POST" action="<?php echo base_url('staff/save'); ?>">
                    <?php if($action === 'edit'): ?>
                        <input type="hidden" name="staff_id" value="<?php echo $staff->id; ?>">
                    <?php endif; ?>
                    
                    <!-- Personal Information -->
                    <div class="form-section mb-4">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-user mr-2"></i>Personal Information
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employee Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="employee_name" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->employee_name) : ''; ?>" 
                                           required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Father's Name</label>
                                    <input type="text" class="form-control" name="father_name" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->father_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" 
                                           value="<?php echo isset($staff) ? $staff->date_of_birth : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo (isset($staff) && $staff->gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (isset($staff) && $staff->gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo (isset($staff) && $staff->gender === 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Marital Status</label>
                                    <select class="form-control" name="marital_status">
                                        <option value="">Select Status</option>
                                        <option value="Single" <?php echo (isset($staff) && $staff->marital_status === 'Single') ? 'selected' : ''; ?>>Single</option>
                                        <option value="Married" <?php echo (isset($staff) && $staff->marital_status === 'Married') ? 'selected' : ''; ?>>Married</option>
                                        <option value="Divorced" <?php echo (isset($staff) && $staff->marital_status === 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                        <option value="Widowed" <?php echo (isset($staff) && $staff->marital_status === 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Blood Group</label>
                                    <select class="form-control" name="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" <?php echo (isset($staff) && $staff->blood_group === 'A+') ? 'selected' : ''; ?>>A+</option>
                                        <option value="A-" <?php echo (isset($staff) && $staff->blood_group === 'A-') ? 'selected' : ''; ?>>A-</option>
                                        <option value="B+" <?php echo (isset($staff) && $staff->blood_group === 'B+') ? 'selected' : ''; ?>>B+</option>
                                        <option value="B-" <?php echo (isset($staff) && $staff->blood_group === 'B-') ? 'selected' : ''; ?>>B-</option>
                                        <option value="AB+" <?php echo (isset($staff) && $staff->blood_group === 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                        <option value="AB-" <?php echo (isset($staff) && $staff->blood_group === 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                        <option value="O+" <?php echo (isset($staff) && $staff->blood_group === 'O+') ? 'selected' : ''; ?>>O+</option>
                                        <option value="O-" <?php echo (isset($staff) && $staff->blood_group === 'O-') ? 'selected' : ''; ?>>O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="form-section mb-4">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-phone mr-2"></i>Contact Information
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->contact_number) : ''; ?>" 
                                           maxlength="10" pattern="[0-9]{10}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Alternate Contact</label>
                                    <input type="text" class="form-control" name="alternate_contact" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->alternate_contact) : ''; ?>" 
                                           maxlength="10" pattern="[0-9]{10}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="email_address" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->email_address) : ''; ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Permanent Address</label>
                                    <textarea class="form-control" name="permanent_address" rows="3"><?php echo isset($staff) ? htmlspecialchars($staff->permanent_address) : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Current Address</label>
                                    <textarea class="form-control" name="current_address" rows="3"><?php echo isset($staff) ? htmlspecialchars($staff->current_address) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Emergency Contact -->
                    <div class="form-section mb-4">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-exclamation-triangle mr-2"></i>Emergency Contact
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Emergency Contact Name</label>
                                    <input type="text" class="form-control" name="emergency_contact_name" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->emergency_contact_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Emergency Contact Phone</label>
                                    <input type="text" class="form-control" name="emergency_contact_phone" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->emergency_contact_phone) : ''; ?>" 
                                           maxlength="10" pattern="[0-9]{10}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Relation</label>
                                    <input type="text" class="form-control" name="emergency_contact_relation" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->emergency_contact_relation) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Professional Information -->
                    <div class="form-section mb-4">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-briefcase mr-2"></i>Professional Information
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <input type="text" class="form-control" name="designation" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->designation) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" class="form-control" name="department" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->department) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Joining Date</label>
                                    <input type="date" class="form-control" name="joining_date" 
                                           value="<?php echo isset($staff) ? $staff->joining_date : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salary</label>
                                    <input type="number" class="form-control" name="salary" 
                                           value="<?php echo isset($staff) ? $staff->salary : ''; ?>" 
                                           step="0.01" min="0">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID Proof Type</label>
                                    <select class="form-control" name="id_proof_type">
                                        <option value="">Select ID Proof</option>
                                        <option value="Aadhar" <?php echo (isset($staff) && $staff->id_proof_type === 'Aadhar') ? 'selected' : ''; ?>>Aadhar</option>
                                        <option value="PAN" <?php echo (isset($staff) && $staff->id_proof_type === 'PAN') ? 'selected' : ''; ?>>PAN</option>
                                        <option value="Driving License" <?php echo (isset($staff) && $staff->id_proof_type === 'Driving License') ? 'selected' : ''; ?>>Driving License</option>
                                        <option value="Passport" <?php echo (isset($staff) && $staff->id_proof_type === 'Passport') ? 'selected' : ''; ?>>Passport</option>
                                        <option value="Voter ID" <?php echo (isset($staff) && $staff->id_proof_type === 'Voter ID') ? 'selected' : ''; ?>>Voter ID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID Proof Number</label>
                                    <input type="text" class="form-control" name="id_proof_number" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->id_proof_number) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Banking Information -->
                    <div class="form-section mb-4">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-university mr-2"></i>Banking Information
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->bank_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bank Account Number</label>
                                    <input type="text" class="form-control" name="bank_account_number" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->bank_account_number) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>IFSC Code</label>
                                    <input type="text" class="form-control" name="ifsc_code" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->ifsc_code) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PAN Number</label>
                                    <input type="text" class="form-control" name="pan_number" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->pan_number) : ''; ?>" 
                                           maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Aadhar Number</label>
                                    <input type="text" class="form-control" name="aadhar_number" 
                                           value="<?php echo isset($staff) ? htmlspecialchars($staff->aadhar_number) : ''; ?>" 
                                           maxlength="12" pattern="[0-9]{12}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fa fa-save mr-2"></i>
                                    <?php echo $action === 'edit' ? 'Update Staff' : 'Save Staff'; ?>
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg px-4 ml-2">
                                    <i class="fa fa-refresh mr-2"></i>Reset Form
                                </button>
                            </div>
                            <div>
                                <?php if($action === 'edit'): ?>
                                    <a href="<?php echo base_url('staff/profile/' . $staff->id); ?>" class="btn btn-info btn-lg px-4">
                                        <i class="fa fa-eye mr-2"></i>View Profile
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo base_url('staff'); ?>" class="btn btn-outline-secondary btn-lg px-4 ml-2">
                                    <i class="fa fa-list mr-2"></i>View All Staff
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Staff form validation and submission
document.getElementById('staffForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous validation states
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Saving...';
    submitBtn.disabled = true;
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit form via AJAX
    fetch('<?php echo base_url("staff/save"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showAlert('success', data.message);
            
            <?php if($action === 'create'): ?>
                // Reset form for new entry
                this.reset();
                // Redirect to staff profile after 2 seconds
                setTimeout(() => {
                    window.location.href = '<?php echo base_url("staff/profile/"); ?>' + data.staff_id;
                }, 2000);
            <?php else: ?>
                // Redirect to staff profile
                setTimeout(() => {
                    window.location.href = '<?php echo base_url("staff/profile/" . (isset($staff) ? $staff->id : "")); ?>';
                }, 1500);
            <?php endif; ?>
        } else {
            showAlert('error', data.message);
            
            // Show field-specific validation errors
            if (data.message.includes('Employee Name')) {
                showFieldError('employee_name', 'Employee name is required');
            }
            if (data.message.includes('Contact Number')) {
                showFieldError('contact_number', 'Please enter a valid 10-digit contact number');
            }
            if (data.message.includes('Email')) {
                showFieldError('email_address', 'Please enter a valid email address');
            }
            if (data.message.includes('Salary')) {
                showFieldError('salary', 'Please enter a valid salary amount');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while saving staff details');
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Show field validation error
function showFieldError(fieldName, message) {
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (field) {
        field.classList.add('is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.textContent = message;
        }
    }
}

// Show alert function
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
    const card = document.querySelector('.card');
    card.parentNode.insertBefore(alertDiv, card);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Real-time validation
document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"]').forEach(input => {
    input.addEventListener('blur', function() {
        validateField(this);
    });
});

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let message = '';
    
    // Remove existing validation state
    field.classList.remove('is-invalid', 'is-valid');
    
    switch(field.name) {
        case 'employee_name':
            if (!value) {
                isValid = false;
                message = 'Employee name is required';
            }
            break;
        case 'contact_number':
        case 'alternate_contact':
        case 'emergency_contact_phone':
            if (value && !/^[0-9]{10}$/.test(value)) {
                isValid = false;
                message = 'Please enter a valid 10-digit phone number';
            }
            break;
        case 'email_address':
            if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address';
            }
            break;
        case 'salary':
            if (value && (isNaN(value) || parseFloat(value) < 0)) {
                isValid = false;
                message = 'Please enter a valid salary amount';
            }
            break;
        case 'pan_number':
            if (value && !/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value)) {
                isValid = false;
                message = 'Please enter a valid PAN number (e.g., ABCDE1234F)';
            }
            break;
        case 'aadhar_number':
            if (value && !/^[0-9]{12}$/.test(value)) {
                isValid = false;
                message = 'Please enter a valid 12-digit Aadhar number';
            }
            break;
    }
    
    if (!isValid) {
        field.classList.add('is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.textContent = message;
        }
    } else if (value) {
        field.classList.add('is-valid');
    }
}
</script>