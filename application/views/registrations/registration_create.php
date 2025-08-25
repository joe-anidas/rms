<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Create New Registration</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('registrations'); ?>">Registrations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('registrations'); ?>" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="row">
            <div class="col-lg-12">
                <div class="modern-card modern-card-elevated">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title"><i class="fa fa-plus"></i> Registration Details</h5>
                    </div>
                    <div class="modern-card-body">
                        <?php echo form_open_multipart('registrations/store', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                            
                            <div class="row">
                                <!-- Property Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="property_id">Property <span class="text-danger">*</span></label>
                                        <select name="property_id" id="property_id" class="form-control" required>
                                            <option value="">Select Property</option>
                                            <?php foreach ($properties as $property): ?>
                                                <option value="<?php echo $property->id; ?>" 
                                                        data-price="<?php echo $property->price; ?>"
                                                        data-type="<?php echo $property->property_type; ?>"
                                                        data-location="<?php echo $property->district . ', ' . $property->taluk_name; ?>">
                                                    <?php echo $property->garden_name; ?> 
                                                    (<?php echo ucfirst($property->property_type); ?>) - 
                                                    <?php echo $property->district; ?>
                                                    <?php if ($property->price): ?>
                                                        - ₹<?php echo number_format($property->price, 0); ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select a property.</div>
                                    </div>
                                </div>

                                <!-- Customer Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_id">Customer <span class="text-danger">*</span></label>
                                        <select name="customer_id" id="customer_id" class="form-control" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($customers as $customer): ?>
                                                <option value="<?php echo $customer->id; ?>"
                                                        data-phone="<?php echo $customer->phone_number_1; ?>"
                                                        data-address="<?php echo $customer->district . ', ' . $customer->village_town_name; ?>">
                                                    <?php echo $customer->plot_buyer_name; ?>
                                                    <?php if ($customer->phone_number_1): ?>
                                                        - <?php echo $customer->phone_number_1; ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select a customer.</div>
                                        <small class="form-text text-muted">
                                            <a href="<?php echo base_url('customers/create'); ?>" target="_blank">
                                                <i class="fa fa-plus"></i> Add New Customer
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Property Details Display -->
                            <div id="property-details" class="row" style="display: none;">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h6><i class="fa fa-info-circle"></i> Selected Property Details</h6>
                                        <div id="property-info"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Details Display -->
                            <div id="customer-details" class="row" style="display: none;">
                                <div class="col-md-12">
                                    <div class="alert alert-success">
                                        <h6><i class="fa fa-user"></i> Selected Customer Details</h6>
                                        <div id="customer-info"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Registration Date -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="registration_date">Registration Date <span class="text-danger">*</span></label>
                                        <input type="date" name="registration_date" id="registration_date" 
                                               class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                        <div class="invalid-feedback">Please provide a registration date.</div>
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount (₹)</label>
                                        <input type="number" name="total_amount" id="total_amount" 
                                               class="form-control" step="0.01" min="0">
                                        <small class="form-text text-muted">Leave empty to use property price</small>
                                    </div>
                                </div>

                                <!-- Paid Amount -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="paid_amount">Paid Amount (₹)</label>
                                        <input type="number" name="paid_amount" id="paid_amount" 
                                               class="form-control" step="0.01" min="0" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Registration Status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Registration Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="active">Active</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Property Status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="property_status">Update Property Status To</label>
                                        <select name="property_status" id="property_status" class="form-control">
                                            <option value="booked">Booked</option>
                                            <option value="sold">Sold</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Agreement Document -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="agreement_document">Agreement Document</label>
                                        <input type="file" name="agreement_document" id="agreement_document" 
                                               class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <small class="form-text text-muted">
                                            Allowed formats: PDF, DOC, DOCX, JPG, PNG. Maximum size: 5MB
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <button type="submit" class="modern-btn modern-btn-primary">
                                        <i class="fa fa-save"></i> Create Registration
                                    </button>
                                    <a href="<?php echo base_url('registrations'); ?>" class="modern-btn modern-btn-secondary">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript for form interactions -->
<script>
$(document).ready(function() {
    // Property selection change handler
    $('#property_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            var price = selectedOption.data('price');
            var type = selectedOption.data('type');
            var location = selectedOption.data('location');
            
            // Update total amount field
            if (price) {
                $('#total_amount').val(price);
            }
            
            // Show property details
            var propertyInfo = '<strong>' + selectedOption.text() + '</strong><br>';
            propertyInfo += 'Type: ' + type + '<br>';
            propertyInfo += 'Location: ' + location + '<br>';
            if (price) {
                propertyInfo += 'Price: ₹' + new Intl.NumberFormat('en-IN').format(price);
            }
            
            $('#property-info').html(propertyInfo);
            $('#property-details').show();
        } else {
            $('#property-details').hide();
            $('#total_amount').val('');
        }
    });
    
    // Customer selection change handler
    $('#customer_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            var phone = selectedOption.data('phone');
            var address = selectedOption.data('address');
            
            // Show customer details
            var customerInfo = '<strong>' + selectedOption.text().split(' - ')[0] + '</strong><br>';
            if (phone) {
                customerInfo += 'Phone: ' + phone + '<br>';
            }
            if (address) {
                customerInfo += 'Address: ' + address;
            }
            
            $('#customer-info').html(customerInfo);
            $('#customer-details').show();
        } else {
            $('#customer-details').hide();
        }
    });
    
    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    
    // File upload validation
    $('#agreement_document').change(function() {
        var file = this.files[0];
        if (file) {
            var fileSize = file.size / 1024 / 1024; // Convert to MB
            var allowedTypes = ['application/pdf', 'application/msword', 
                              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                              'image/jpeg', 'image/jpg', 'image/png'];
            
            if (fileSize > 5) {
                alert('File size must be less than 5MB');
                $(this).val('');
                return false;
            }
            
            if (allowedTypes.indexOf(file.type) === -1) {
                alert('Invalid file type. Please select PDF, DOC, DOCX, JPG, or PNG file.');
                $(this).val('');
                return false;
            }
        }
    });
});
</script>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')): ?>
    <script>
        $(document).ready(function() {
            toastr.success('<?php echo $this->session->flashdata('success'); ?>');
        });
    </script>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <script>
        $(document).ready(function() {
            toastr.error('<?php echo $this->session->flashdata('error'); ?>');
        });
    </script>
<?php endif; ?>