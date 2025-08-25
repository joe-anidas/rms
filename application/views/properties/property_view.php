<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Property Details</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('properties'); ?>">Properties</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($property->garden_name); ?></li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('properties/edit/' . $property->id); ?>" class="btn btn-outline-warning waves-effect waves-light">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="<?php echo base_url('properties'); ?>" class="btn btn-outline-secondary waves-effect waves-light">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Property Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <?php echo htmlspecialchars($property->garden_name); ?>
                            <?php
                            $status_class = '';
                            switch($property->status) {
                                case 'sold': $status_class = 'badge-success'; break;
                                case 'booked': $status_class = 'badge-warning'; break;
                                case 'unsold': $status_class = 'badge-secondary'; break;
                                default: $status_class = 'badge-light'; break;
                            }
                            ?>
                            <span class="badge <?php echo $status_class; ?> ml-2"><?php echo ucfirst($property->status); ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Property Type:</strong></td>
                                        <td>
                                            <span class="badge badge-info"><?php echo ucfirst($property->property_type); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>District:</strong></td>
                                        <td><?php echo $property->district ? htmlspecialchars($property->district) : '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Taluk:</strong></td>
                                        <td><?php echo $property->taluk_name ? htmlspecialchars($property->taluk_name) : '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Village/Town:</strong></td>
                                        <td><?php echo $property->village_town_name ? htmlspecialchars($property->village_town_name) : '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Size:</strong></td>
                                        <td><?php echo $property->size_sqft ? number_format($property->size_sqft, 2) . ' sq ft' : '-'; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Price:</strong></td>
                                        <td>
                                            <?php if($property->price): ?>
                                                <span class="h5 text-success">₹<?php echo number_format($property->price, 2); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Not specified</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                                    Change Status
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item change-status" href="#" data-status="unsold">
                                                        <span class="badge badge-secondary">Unsold</span>
                                                    </a>
                                                    <a class="dropdown-item change-status" href="#" data-status="booked">
                                                        <span class="badge badge-warning">Booked</span>
                                                    </a>
                                                    <a class="dropdown-item change-status" href="#" data-status="sold">
                                                        <span class="badge badge-success">Sold</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td><?php echo date('M d, Y \a\t g:i A', strtotime($property->created_at)); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td><?php echo date('M d, Y \a\t g:i A', strtotime($property->updated_at)); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <?php if($property->description): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Description</h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($property->description)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Location Map Placeholder -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Location</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-map-marker"></i>
                                    <strong>Location:</strong>
                                    <?php 
                                    $location_parts = array_filter(array(
                                        $property->village_town_name,
                                        $property->taluk_name,
                                        $property->district
                                    ));
                                    echo implode(', ', $location_parts);
                                    ?>
                                </div>
                                <!-- Map integration can be added here -->
                                <div class="bg-light p-4 text-center" style="min-height: 200px;">
                                    <i class="fa fa-map fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Map integration can be added here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Enhanced Staff Assignment -->
                <div class="modern-card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-users text-primary mr-2"></i>Staff Assignment
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if($property->staff_name): ?>
                            <div class="staff-assignment-card bg-light rounded p-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3">
                                        <i class="fa fa-user text-white fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 font-weight-bold"><?php echo htmlspecialchars($property->staff_name); ?></h6>
                                        <p class="mb-0 text-muted"><?php echo htmlspecialchars($property->staff_designation); ?></p>
                                        <small class="text-success">
                                            <i class="fa fa-check-circle mr-1"></i>Currently Assigned
                                        </small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" id="change-staff">
                                                <i class="fa fa-edit text-warning mr-2"></i>Change Assignment
                                            </a>
                                            <a class="dropdown-item" href="#" id="view-staff-details">
                                                <i class="fa fa-eye text-info mr-2"></i>View Staff Details
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#" id="unassign-staff">
                                                <i class="fa fa-times mr-2"></i>Unassign Staff
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fa fa-user-times fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-2">No Staff Assigned</h6>
                                <p class="text-muted mb-3">Assign a staff member to manage this property</p>
                                <button type="button" class="btn btn-primary" id="assign-staff">
                                    <i class="fa fa-plus mr-2"></i>Assign Staff Member
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Assignment History -->
                        <div class="mt-3">
                            <h6 class="text-muted mb-2">
                                <i class="fa fa-history mr-1"></i>Assignment History
                            </h6>
                            <div class="assignment-history">
                                <!-- This can be populated via AJAX -->
                                <small class="text-muted">Loading assignment history...</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="<?php echo base_url('properties/edit/' . $property->id); ?>" class="list-group-item list-group-item-action">
                                <i class="fa fa-edit text-warning"></i> Edit Property
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="duplicate-property">
                                <i class="fa fa-copy text-info"></i> Duplicate Property
                            </a>
                            <a href="#" class="list-group-item list-group-item-action text-danger" id="delete-property">
                                <i class="fa fa-trash"></i> Delete Property
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Property Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Property Info</h5>
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
                                    <?php echo $property->size_sqft ? number_format($property->size_sqft / 1000, 1) . 'K' : '-'; ?>
                                </h4>
                                <p class="mb-0 text-muted">Size (K sq ft)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Staff Assignment Modal -->
<div class="modal fade" id="staffModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Staff Member</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="staff_select">Select Staff Member</label>
                    <select class="form-control" id="staff_select">
                        <option value="">Choose staff member...</option>
                        <!-- Staff options will be loaded via AJAX -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-assign">Assign</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this property?</p>
                <p class="text-warning"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var propertyId = <?php echo $property->id; ?>;

    // Change status functionality
    $('.change-status').click(function(e) {
        e.preventDefault();
        var newStatus = $(this).data('status');
        
        $.ajax({
            url: '<?php echo base_url("properties/change_status"); ?>',
            method: 'POST',
            data: {
                property_id: propertyId,
                status: newStatus
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while updating status');
            }
        });
    });

    // Staff assignment functionality
    $('#assign-staff, #change-staff').click(function() {
        // Load staff list
        $.ajax({
            url: '<?php echo base_url("staff/get_all"); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var options = '<option value="">Choose staff member...</option>';
                    response.staff.forEach(function(staff) {
                        options += '<option value="' + staff.id + '">' + staff.employee_name + ' (' + staff.designation + ')</option>';
                    });
                    $('#staff_select').html(options);
                    $('#staffModal').modal('show');
                }
            },
            error: function() {
                toastr.error('Failed to load staff list');
            }
        });
    });

    $('#confirm-assign').click(function() {
        var staffId = $('#staff_select').val();
        if (!staffId) {
            toastr.error('Please select a staff member');
            return;
        }

        $.ajax({
            url: '<?php echo base_url("properties/assign_staff"); ?>',
            method: 'POST',
            data: {
                property_id: propertyId,
                staff_id: staffId
            },
            dataType: 'json',
            success: function(response) {
                $('#staffModal').modal('hide');
                if (response.status === 'success') {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                $('#staffModal').modal('hide');
                toastr.error('An error occurred while assigning staff');
            }
        });
    });

    // Unassign staff
    $('#unassign-staff').click(function() {
        if (confirm('Are you sure you want to unassign the staff member?')) {
            $.ajax({
                url: '<?php echo base_url("properties/unassign_staff"); ?>',
                method: 'POST',
                data: {
                    property_id: propertyId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while unassigning staff');
                }
            });
        }
    });

    // Delete property
    $('#delete-property').click(function(e) {
        e.preventDefault();
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').click(function() {
        $.ajax({
            url: '<?php echo base_url("properties/delete/" . $property->id); ?>',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.status === 'success') {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = '<?php echo base_url("properties"); ?>';
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                $('#deleteModal').modal('hide');
                toastr.error('An error occurred while deleting the property');
            }
        });
    });

    // Duplicate property
    $('#duplicate-property').click(function(e) {
        e.preventDefault();
        // Redirect to create page with pre-filled data
        window.location.href = '<?php echo base_url("properties/create?duplicate=" . $property->id); ?>';
    });
});
</script>