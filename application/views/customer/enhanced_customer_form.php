<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$is_edit = isset($customer) && !empty($customer);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fa fa-<?php echo $is_edit ? 'edit' : 'plus'; ?> mr-2"></i>
                <?php echo $is_edit ? 'Edit Customer Details' : 'Add New Customer'; ?>
            </h5>
            <div class="card-action">
                <a href="<?php echo base_url('customers'); ?>" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i>Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form id="customerForm" method="POST" action="<?php echo base_url($is_edit ? 'customers/update/' . $customer->id : 'customers/store'); ?>">
                
                <!-- Personal Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fa fa-user mr-2"></i>Personal Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="plot_buyer_name" 
                                           placeholder="Enter customer name" 
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->plot_buyer_name) : ''; ?>" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Father's Name</label>
                                    <input type="text" class="form-control" name="father_name" 
                                           placeholder="Enter father's name"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->father_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="email_address" 
                                           placeholder="Enter email address"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->email_address) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Occupation</label>
                                    <input type="text" class="form-control" name="occupation" 
                                           placeholder="Enter occupation"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->occupation) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Annual Income</label>
                                    <input type="number" class="form-control" name="annual_income" 
                                           placeholder="Enter annual income" step="0.01"
                                           value="<?php echo $is_edit ? $customer->annual_income : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Reference Source</label>
                                    <select class="form-control" name="reference_source">
                                        <option value="">Select reference source</option>
                                        <option value="Advertisement" <?php echo ($is_edit && $customer->reference_source == 'Advertisement') ? 'selected' : ''; ?>>Advertisement</option>
                                        <option value="Friend/Family" <?php echo ($is_edit && $customer->reference_source == 'Friend/Family') ? 'selected' : ''; ?>>Friend/Family</option>
                                        <option value="Agent" <?php echo ($is_edit && $customer->reference_source == 'Agent') ? 'selected' : ''; ?>>Agent</option>
                                        <option value="Online" <?php echo ($is_edit && $customer->reference_source == 'Online') ? 'selected' : ''; ?>>Online</option>
                                        <option value="Walk-in" <?php echo ($is_edit && $customer->reference_source == 'Walk-in') ? 'selected' : ''; ?>>Walk-in</option>
                                        <option value="Other" <?php echo ($is_edit && $customer->reference_source == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fa fa-phone mr-2"></i>Contact Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Primary Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="phone_number_1" 
                                           placeholder="Enter primary phone number" maxlength="15"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->phone_number_1) : ''; ?>" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Secondary Phone</label>
                                    <input type="text" class="form-control" name="phone_number_2" 
                                           placeholder="Enter secondary phone number" maxlength="15"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->phone_number_2) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer Status</label>
                                    <select class="form-control" name="customer_status">
                                        <option value="active" <?php echo ($is_edit && $customer->customer_status == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($is_edit && $customer->customer_status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="blacklisted" <?php echo ($is_edit && $customer->customer_status == 'blacklisted') ? 'selected' : ''; ?>>Blacklisted</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Emergency Contact -->
                        <h6 class="text-info mt-3 mb-3">Emergency Contact</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Emergency Contact Name</label>
                                    <input type="text" class="form-control" name="emergency_contact_name" 
                                           placeholder="Enter emergency contact name"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->emergency_contact_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Emergency Contact Phone</label>
                                    <input type="text" class="form-control" name="emergency_contact_phone" 
                                           placeholder="Enter emergency contact phone" maxlength="15"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->emergency_contact_phone) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Relation</label>
                                    <select class="form-control" name="emergency_contact_relation">
                                        <option value="">Select relation</option>
                                        <option value="Father" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Father') ? 'selected' : ''; ?>>Father</option>
                                        <option value="Mother" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Mother') ? 'selected' : ''; ?>>Mother</option>
                                        <option value="Spouse" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Spouse') ? 'selected' : ''; ?>>Spouse</option>
                                        <option value="Son" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Son') ? 'selected' : ''; ?>>Son</option>
                                        <option value="Daughter" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Daughter') ? 'selected' : ''; ?>>Daughter</option>
                                        <option value="Brother" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Brother') ? 'selected' : ''; ?>>Brother</option>
                                        <option value="Sister" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Sister') ? 'selected' : ''; ?>>Sister</option>
                                        <option value="Friend" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Friend') ? 'selected' : ''; ?>>Friend</option>
                                        <option value="Other" <?php echo ($is_edit && $customer->emergency_contact_relation == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fa fa-map-marker mr-2"></i>Address Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select class="form-control" name="district">
                                        <option value="">Select District</option>
                                        <option value="Bangalore Urban" <?php echo ($is_edit && $customer->district == 'Bangalore Urban') ? 'selected' : ''; ?>>Bangalore Urban</option>
                                        <option value="Bangalore Rural" <?php echo ($is_edit && $customer->district == 'Bangalore Rural') ? 'selected' : ''; ?>>Bangalore Rural</option>
                                        <option value="Mysore" <?php echo ($is_edit && $customer->district == 'Mysore') ? 'selected' : ''; ?>>Mysore</option>
                                        <option value="Mandya" <?php echo ($is_edit && $customer->district == 'Mandya') ? 'selected' : ''; ?>>Mandya</option>
                                        <option value="Hassan" <?php echo ($is_edit && $customer->district == 'Hassan') ? 'selected' : ''; ?>>Hassan</option>
                                        <option value="Tumkur" <?php echo ($is_edit && $customer->district == 'Tumkur') ? 'selected' : ''; ?>>Tumkur</option>
                                        <option value="Kolar" <?php echo ($is_edit && $customer->district == 'Kolar') ? 'selected' : ''; ?>>Kolar</option>
                                        <option value="Chikkaballapur" <?php echo ($is_edit && $customer->district == 'Chikkaballapur') ? 'selected' : ''; ?>>Chikkaballapur</option>
                                        <option value="Ramanagara" <?php echo ($is_edit && $customer->district == 'Ramanagara') ? 'selected' : ''; ?>>Ramanagara</option>
                                        <option value="Chitradurga" <?php echo ($is_edit && $customer->district == 'Chitradurga') ? 'selected' : ''; ?>>Chitradurga</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Taluk Name</label>
                                    <input type="text" class="form-control" name="taluk_name" 
                                           placeholder="Enter taluk name"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->taluk_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Village/Town Name</label>
                                    <input type="text" class="form-control" name="village_town_name" 
                                           placeholder="Enter village/town name"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->village_town_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pincode</label>
                                    <input type="text" class="form-control" name="pincode" 
                                           placeholder="Enter pincode" maxlength="6"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->pincode) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Street Address</label>
                                    <textarea class="form-control" name="street_address" rows="3" 
                                              placeholder="Enter street address"><?php echo $is_edit ? htmlspecialchars($customer->street_address) : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alternate Address</label>
                                    <textarea class="form-control" name="alternate_address" rows="3" 
                                              placeholder="Enter alternate address"><?php echo $is_edit ? htmlspecialchars($customer->alternate_address) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ID & Financial Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fa fa-id-card mr-2"></i>ID & Financial Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID Proof Type</label>
                                    <select class="form-control" name="id_proof">
                                        <option value="">Select ID proof</option>
                                        <option value="Aadhar" <?php echo ($is_edit && $customer->id_proof == 'Aadhar') ? 'selected' : ''; ?>>Aadhar</option>
                                        <option value="PAN" <?php echo ($is_edit && $customer->id_proof == 'PAN') ? 'selected' : ''; ?>>PAN</option>
                                        <option value="Driving License" <?php echo ($is_edit && $customer->id_proof == 'Driving License') ? 'selected' : ''; ?>>Driving License</option>
                                        <option value="Passport" <?php echo ($is_edit && $customer->id_proof == 'Passport') ? 'selected' : ''; ?>>Passport</option>
                                        <option value="Voter ID" <?php echo ($is_edit && $customer->id_proof == 'Voter ID') ? 'selected' : ''; ?>>Voter ID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Aadhar Number</label>
                                    <input type="text" class="form-control" name="aadhar_number" 
                                           placeholder="Enter aadhar number" maxlength="12"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->aadhar_number) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>PAN Number</label>
                                    <input type="text" class="form-control" name="pan_number" 
                                           placeholder="Enter PAN number" maxlength="10" style="text-transform: uppercase;"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->pan_number) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Details -->
                        <h6 class="text-info mt-3 mb-3">Bank Details</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name" 
                                           placeholder="Enter bank name"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->bank_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" name="bank_account_number" 
                                           placeholder="Enter account number"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->bank_account_number) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>IFSC Code</label>
                                    <input type="text" class="form-control" name="ifsc_code" 
                                           placeholder="Enter IFSC code" maxlength="11" style="text-transform: uppercase;"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->ifsc_code) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fa fa-info-circle mr-2"></i>Additional Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Total Plot Bought</label>
                                    <input type="text" class="form-control" name="total_plot_bought" 
                                           placeholder="Enter plot details (e.g., 2 acres, Plot No. 123)"
                                           value="<?php echo $is_edit ? htmlspecialchars($customer->total_plot_bought) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea class="form-control" name="notes" rows="4" 
                                              placeholder="Enter any additional notes about the customer"><?php echo $is_edit ? htmlspecialchars($customer->notes) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fa fa-save mr-2"></i><?php echo $is_edit ? 'Update Customer' : 'Save Customer'; ?>
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg px-5 ml-2">
                                    <i class="fa fa-refresh mr-2"></i>Reset Form
                                </button>
                                <a href="<?php echo base_url('customers'); ?>" class="btn btn-info btn-lg px-5 ml-2">
                                    <i class="fa fa-list mr-2"></i>View All Customers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Enhanced customer form submission
document.getElementById('customerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Processing...';
    submitBtn.disabled = true;
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit form via AJAX
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            showAlert('success', data.message + ' Redirecting...');
            setTimeout(() => {
                window.location.href = '<?php echo base_url("customers"); ?>';
            }, 2000);
        } else {
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
    
    // Insert alert at the top of the container
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Format PAN and IFSC to uppercase
document.querySelector('input[name="pan_number"]').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

document.querySelector('input[name="ifsc_code"]').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

// Validate phone numbers (only digits)
document.querySelectorAll('input[name^="phone"], input[name*="phone"]').forEach(input => {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

// Validate Aadhar number (only digits, max 12)
document.querySelector('input[name="aadhar_number"]').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '').substring(0, 12);
});

// Validate pincode (only digits, max 6)
document.querySelector('input[name="pincode"]').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
});

// Form validation
function validateForm() {
    const requiredFields = ['plot_buyer_name', 'phone_number_1'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`input[name="${fieldName}"]`);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    // Validate email format if provided
    const emailField = document.querySelector('input[name="email_address"]');
    if (emailField.value && !isValidEmail(emailField.value)) {
        emailField.classList.add('is-invalid');
        isValid = false;
    } else {
        emailField.classList.remove('is-invalid');
    }
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Add validation on form submission
document.getElementById('customerForm').addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault();
        showAlert('error', 'Please fill in all required fields correctly.');
        return false;
    }
});
</script>