<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Edit Property</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('properties'); ?>">Properties</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('properties/view/' . $property->id); ?>"><?php echo htmlspecialchars($property->garden_name); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('properties/view/' . $property->id); ?>" class="btn btn-outline-secondary waves-effect waves-light">
                        <i class="fa fa-arrow-left"></i> Back to View
                    </a>
                </div>
            </div>
        </div>

        <!-- Property Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Property Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form id="property-form" method="POST" action="<?php echo base_url('properties/update/' . $property->id); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="garden_name">Property Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="garden_name" name="garden_name" 
                                               placeholder="Enter property name" required 
                                               value="<?php echo set_value('garden_name', $property->garden_name); ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="property_type">Property Type <span class="text-danger">*</span></label>
                                        <select class="form-control" id="property_type" name="property_type" required>
                                            <option value="">Select Property Type</option>
                                            <?php foreach($property_types as $type): ?>
                                                <option value="<?php echo $type; ?>" 
                                                        <?php echo set_select('property_type', $type, ($property->property_type == $type)); ?>>
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
                                               value="<?php echo set_value('district', $property->district); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="taluk_name">Taluk</label>
                                        <input type="text" class="form-control" id="taluk_name" name="taluk_name" 
                                               placeholder="Enter taluk" 
                                               value="<?php echo set_value('taluk_name', $property->taluk_name); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="village_town_name">Village/Town</label>
                                        <input type="text" class="form-control" id="village_town_name" name="village_town_name" 
                                               placeholder="Enter village or town" 
                                               value="<?php echo set_value('village_town_name', $property->village_town_name); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="size_sqft">Size (Square Feet)</label>
                                        <input type="number" class="form-control" id="size_sqft" name="size_sqft" 
                                               placeholder="Enter size in sq ft" step="0.01" min="0"
                                               value="<?php echo set_value('size_sqft', $property->size_sqft); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Price (₹)</label>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               placeholder="Enter price" step="0.01" min="0"
                                               value="<?php echo set_value('price', $property->price); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="unsold" <?php echo set_select('status', 'unsold', ($property->status == 'unsold')); ?>>Unsold</option>
                                            <option value="booked" <?php echo set_select('status', 'booked', ($property->status == 'booked')); ?>>Booked</option>
                                            <option value="sold" <?php echo set_select('status', 'sold', ($property->status == 'sold')); ?>>Sold</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" 
                                                  placeholder="Enter property description"><?php echo set_value('description', $property->description); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Property
                                </button>
                                <a href="<?php echo base_url('properties/view/' . $property->id); ?>" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Current Staff Assignment -->
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
                                    <option value="<?php echo $staff->id; ?>" 
                                            <?php echo set_select('assigned_staff_id', $staff->id, ($property->assigned_staff_id == $staff->id)); ?>>
                                        <?php echo htmlspecialchars($staff->employee_name); ?> 
                                        (<?php echo htmlspecialchars($staff->designation); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">
                                Current assignment: 
                                <?php if($property->staff_name): ?>
                                    <strong><?php echo htmlspecialchars($property->staff_name); ?></strong>
                                <?php else: ?>
                                    <em>Unassigned</em>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Property History -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Property History</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Property Created</h6>
                                    <small class="text-muted"><?php echo date('M d, Y \a\t g:i A', strtotime($property->created_at)); ?></small>
                                </div>
                            </div>
                            <?php if($property->updated_at != $property->created_at): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Last Updated</h6>
                                        <small class="text-muted"><?php echo date('M d, Y \a\t g:i A', strtotime($property->updated_at)); ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Property Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Property Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h4 class="mb-1 text-primary"><?php echo $property->id; ?></h4>
                                    <p class="mb-0 text-muted">Property ID</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-1 text-success">
                                    <?php 
                                    $status_class = '';
                                    switch($property->status) {
                                        case 'sold': $status_class = 'text-success'; break;
                                        case 'booked': $status_class = 'text-warning'; break;
                                        case 'unsold': $status_class = 'text-secondary'; break;
                                        default: $status_class = 'text-muted'; break;
                                    }
                                    ?>
                                    <span class="<?php echo $status_class; ?>"><?php echo ucfirst($property->status); ?></span>
                                </h4>
                                <p class="mb-0 text-muted">Current Status</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Tips</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fa fa-info-circle text-info"></i>
                                <strong>Status Change:</strong> Changing status will affect property availability
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-user text-warning"></i>
                                <strong>Staff Assignment:</strong> Reassigning staff will update property management
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-save text-success"></i>
                                <strong>Auto-save:</strong> Changes are saved when you click Update Property
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
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    
                    // Redirect to property view
                    setTimeout(function() {
                        window.location.href = '<?php echo base_url("properties/view/" . $property->id); ?>';
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
                toastr.error('An error occurred while updating the property');
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

    // Warn about unsaved changes
    var formChanged = false;
    $('#property-form input, #property-form select, #property-form textarea').on('change', function() {
        formChanged = true;
    });

    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Don't warn when form is submitted
    $('#property-form').on('submit', function() {
        formChanged = false;
    });
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -31px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}
</style>