<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Add New Property</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('properties'); ?>">Properties</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Property</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('properties'); ?>" class="btn btn-outline-secondary waves-effect waves-light">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Enhanced Property Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="modern-card">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-home mr-2"></i>Property Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form id="property-form" method="POST" action="<?php echo base_url('properties/store'); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="garden_name">Property Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="garden_name" name="garden_name" 
                                               placeholder="Enter property name" required 
                                               value="<?php echo set_value('garden_name'); ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="property_type">Property Type <span class="text-danger">*</span></label>
                                        <select class="form-control" id="property_type" name="property_type" required>
                                            <option value="">Select Property Type</option>
                                            <?php foreach($property_types as $type): ?>
                                                <option value="<?php echo $type; ?>" <?php echo set_select('property_type', $type); ?>>
                                                    <?php echo ucfirst($type); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="district">District</label>
                                        <input type="text" class="form-control" id="district" name="district" 
                                               placeholder="Enter district" 
                                               value="<?php echo set_value('district'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="taluk_name">Taluk</label>
                                        <input type="text" class="form-control" id="taluk_name" name="taluk_name" 
                                               placeholder="Enter taluk" 
                                               value="<?php echo set_value('taluk_name'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="village_town_name">Village/Town</label>
                                        <input type="text" class="form-control" id="village_town_name" name="village_town_name" 
                                               placeholder="Enter village or town" 
                                               value="<?php echo set_value('village_town_name'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="size_sqft">Size (Square Feet)</label>
                                        <input type="number" class="form-control" id="size_sqft" name="size_sqft" 
                                               placeholder="Enter size in sq ft" step="0.01" min="0"
                                               value="<?php echo set_value('size_sqft'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Price (₹)</label>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               placeholder="Enter price" step="0.01" min="0"
                                               value="<?php echo set_value('price'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="unsold" <?php echo set_select('status', 'unsold', true); ?>>Unsold</option>
                                            <option value="booked" <?php echo set_select('status', 'booked'); ?>>Booked</option>
                                            <option value="sold" <?php echo set_select('status', 'sold'); ?>>Sold</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" 
                                                  placeholder="Enter property description"><?php echo set_value('description'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create Property
                                </button>
                                <a href="<?php echo base_url('properties'); ?>" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Staff Assignment Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Staff Assignment</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="assigned_staff_id">Assign Staff Member</label>
                            <select class="form-control" id="assigned_staff_id" name="assigned_staff_id" form="property-form">
                                <option value="">No Assignment</option>
                                <?php foreach($staff_list as $staff): ?>
                                    <option value="<?php echo $staff->id; ?>" <?php echo set_select('assigned_staff_id', $staff->id); ?>>
                                        <?php echo htmlspecialchars($staff->employee_name); ?> 
                                        (<?php echo htmlspecialchars($staff->designation); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">
                                You can assign a staff member to manage this property
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Help & Tips</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fa fa-info-circle text-info"></i>
                                <strong>Property Name:</strong> Use a descriptive name that helps identify the property
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-map-marker text-success"></i>
                                <strong>Location:</strong> Provide complete location details for better searchability
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-user text-warning"></i>
                                <strong>Staff Assignment:</strong> Assign a staff member for better property management
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-tag text-primary"></i>
                                <strong>Status:</strong> Set initial status based on current property state
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    // Form validation and submission
    $('#property-form').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Creating...').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    
                    // Redirect to property view or list
                    setTimeout(function() {
                        if (response.property_id) {
                            window.location.href = '<?php echo base_url("properties/view/"); ?>' + response.property_id;
                        } else {
                            window.location.href = '<?php echo base_url("properties"); ?>';
                        }
                    }, 1500);
                } else {
                    // Show error message
                    toastr.error(response.message);
                    
                    // Show validation errors if any
                    if (response.errors) {
                        var errors = response.errors.split('\n');
                        errors.forEach(function(error) {
                            if (error.trim()) {
                                toastr.error(error.trim());
                            }
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred while creating the property');
                console.error('Error:', error);
            },
            complete: function() {
                // Restore button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Real-time validation
    $('#garden_name').on('blur', function() {
        if ($(this).val().trim() === '') {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Property name is required');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    $('#property_type').on('change', function() {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Property type is required');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Price and size validation
    $('#price, #size_sqft').on('input', function() {
        var value = parseFloat($(this).val());
        if ($(this).val() !== '' && (isNaN(value) || value < 0)) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Please enter a valid positive number');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Format price input
    $('#price').on('blur', function() {
        var value = parseFloat($(this).val());
        if (!isNaN(value) && value >= 0) {
            $(this).val(value.toFixed(2));
        }
    });
    
    // Format size input
    $('#size_sqft').on('blur', function() {
        var value = parseFloat($(this).val());
        if (!isNaN(value) && value >= 0) {
            $(this).val(value.toFixed(2));
        }
    });
});
</script>