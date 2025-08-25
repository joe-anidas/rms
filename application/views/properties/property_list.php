<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Modern Header -->
        <div class="row pt-3 pb-3">
            <div class="col-sm-8">
                <div class="d-flex align-items-center">
                    <div class="page-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                        <i class="icon-home fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="page-title mb-1">Property Management</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 bg-transparent p-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Properties</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('properties/create'); ?>" class="btn btn-primary waves-effect waves-light">
                        <i class="fa fa-plus"></i> Add Property
                    </a>
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?php echo base_url('properties/statistics'); ?>">
                            <i class="fa fa-chart-bar"></i> View Statistics
                        </a>
                        <a class="dropdown-item" href="<?php echo base_url('properties/search'); ?>">
                            <i class="fa fa-search"></i> Advanced Search
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="export-properties">
                            <i class="fa fa-download"></i> Export Data
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="modern-card gradient-card-primary">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-1 font-weight-bold"><?php echo isset($total_properties) ? $total_properties : 0; ?></h3>
                                <p class="mb-0 opacity-75">Total Properties</p>
                            </div>
                            <div class="card-icon">
                                <i class="icon-home fa-2x opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="modern-card gradient-card-warning">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-1 font-weight-bold">
                                    <?php 
                                    $unsold_count = 0;
                                    foreach($properties as $property) {
                                        if($property->status == 'unsold') $unsold_count++;
                                    }
                                    echo $unsold_count;
                                    ?>
                                </h3>
                                <p class="mb-0 opacity-75">Available</p>
                            </div>
                            <div class="card-icon">
                                <i class="icon-clock fa-2x opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <?php 
                            $total = isset($total_properties) ? $total_properties : 1;
                            $unsold_percentage = ($total > 0) ? ($unsold_count / $total) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-white" role="progressbar" style="width: <?php echo $unsold_percentage; ?>%" aria-valuenow="<?php echo $unsold_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="modern-card gradient-card-info">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-1 font-weight-bold">
                                    <?php 
                                    $booked_count = 0;
                                    foreach($properties as $property) {
                                        if($property->status == 'booked') $booked_count++;
                                    }
                                    echo $booked_count;
                                    ?>
                                </h3>
                                <p class="mb-0 opacity-75">Booked</p>
                            </div>
                            <div class="card-icon">
                                <i class="icon-book-open fa-2x opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <?php 
                            $booked_percentage = ($total > 0) ? ($booked_count / $total) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-white" role="progressbar" style="width: <?php echo $booked_percentage; ?>%" aria-valuenow="<?php echo $booked_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="modern-card gradient-card-success">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-1 font-weight-bold">
                                    <?php 
                                    $sold_count = 0;
                                    foreach($properties as $property) {
                                        if($property->status == 'sold') $sold_count++;
                                    }
                                    echo $sold_count;
                                    ?>
                                </h3>
                                <p class="mb-0 opacity-75">Sold</p>
                            </div>
                            <div class="card-icon">
                                <i class="icon-check fa-2x opacity-50"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <?php 
                            $sold_percentage = ($total > 0) ? ($sold_count / $total) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-white" role="progressbar" style="width: <?php echo $sold_percentage; ?>%" aria-valuenow="<?php echo $sold_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-header bg-light border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-filter text-primary mr-2"></i>Filter Properties
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-filters">
                                <i class="fa fa-chevron-down"></i> <span class="filter-text">Show Filters</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body filter-section" style="display: none;">
                        <form method="GET" action="<?php echo base_url('properties'); ?>" id="filter-form">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fa fa-search text-muted mr-1"></i>Search
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" 
                                                   placeholder="Search by name, location..." 
                                                   value="<?php echo isset($current_filters['search']) ? $current_filters['search'] : ''; ?>">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" id="clear-search">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fa fa-tag text-muted mr-1"></i>Status
                                        </label>
                                        <select name="status" class="form-control custom-select">
                                            <option value="">All Status</option>
                                            <?php foreach($statuses as $status): ?>
                                                <option value="<?php echo $status; ?>" 
                                                        <?php echo (isset($current_filters['status']) && $current_filters['status'] == $status) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($status); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fa fa-home text-muted mr-1"></i>Type
                                        </label>
                                        <select name="property_type" class="form-control custom-select">
                                            <option value="">All Types</option>
                                            <?php foreach($property_types as $type): ?>
                                                <option value="<?php echo $type; ?>" 
                                                        <?php echo (isset($current_filters['property_type']) && $current_filters['property_type'] == $type) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($type); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fa fa-user text-muted mr-1"></i>Assigned Staff
                                        </label>
                                        <select name="assigned_staff_id" class="form-control custom-select">
                                            <option value="">All Staff</option>
                                            <option value="unassigned" <?php echo (isset($current_filters['assigned_staff_id']) && $current_filters['assigned_staff_id'] == 'unassigned') ? 'selected' : ''; ?>>
                                                Unassigned Properties
                                            </option>
                                            <?php foreach($staff_list as $staff): ?>
                                                <option value="<?php echo $staff->id; ?>" 
                                                        <?php echo (isset($current_filters['assigned_staff_id']) && $current_filters['assigned_staff_id'] == $staff->id) ? 'selected' : ''; ?>>
                                                    <?php echo $staff->employee_name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary mr-2 flex-fill">
                                                <i class="fa fa-filter"></i> Apply
                                            </button>
                                            <a href="<?php echo base_url('properties'); ?>" class="btn btn-outline-secondary">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Advanced Filters Row -->
                            <div class="row advanced-filters" style="display: none;">
                                <div class="col-12">
                                    <hr class="my-3">
                                    <h6 class="text-muted mb-3">
                                        <i class="fa fa-cogs mr-1"></i>Advanced Filters
                                    </h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Min Price (₹)</label>
                                        <input type="number" name="min_price" class="form-control" 
                                               placeholder="0" step="1000" min="0">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Max Price (₹)</label>
                                        <input type="number" name="max_price" class="form-control" 
                                               placeholder="No limit" step="1000" min="0">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Min Size (Sq Ft)</label>
                                        <input type="number" name="min_size" class="form-control" 
                                               placeholder="0" step="100" min="0">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Max Size (Sq Ft)</label>
                                        <input type="number" name="max_size" class="form-control" 
                                               placeholder="No limit" step="100" min="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-sm btn-link text-primary" id="toggle-advanced">
                                        <i class="fa fa-plus-circle"></i> Show Advanced Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Properties Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Properties List</h5>
                        <div class="card-action">
                            <div class="dropdown">
                                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                                    <i class="icon-options"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?php echo base_url('properties/statistics'); ?>">
                                        <i class="icon-chart"></i> View Statistics
                                    </a>
                                    <a class="dropdown-item" href="<?php echo base_url('properties/search'); ?>">
                                        <i class="icon-magnifier"></i> Advanced Search
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(empty($properties)): ?>
                            <div class="text-center py-4">
                                <i class="icon-home fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No properties found</h5>
                                <p class="text-muted">Start by adding your first property</p>
                                <a href="<?php echo base_url('properties/create'); ?>" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Add Property
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>Property Name</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            <th>Size (Sq Ft)</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Assigned Staff</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($properties as $property): ?>
                                            <tr>
                                                <td><input type="checkbox" class="property-checkbox" value="<?php echo $property->id; ?>"></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($property->garden_name); ?></strong>
                                                    <?php if($property->description): ?>
                                                        <br><small class="text-muted"><?php echo substr(htmlspecialchars($property->description), 0, 50); ?>...</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo ucfirst($property->property_type); ?></span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $location_parts = array_filter(array(
                                                        $property->village_town_name,
                                                        $property->taluk_name,
                                                        $property->district
                                                    ));
                                                    echo implode(', ', $location_parts);
                                                    ?>
                                                </td>
                                                <td><?php echo $property->size_sqft ? number_format($property->size_sqft, 2) : '-'; ?></td>
                                                <td><?php echo $property->price ? '₹' . number_format($property->price, 2) : '-'; ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = '';
                                                    switch($property->status) {
                                                        case 'sold': $status_class = 'badge-success'; break;
                                                        case 'booked': $status_class = 'badge-warning'; break;
                                                        case 'unsold': $status_class = 'badge-secondary'; break;
                                                        default: $status_class = 'badge-light'; break;
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($property->status); ?></span>
                                                </td>
                                                <td>
                                                    <?php if($property->staff_name): ?>
                                                        <small>
                                                            <?php echo htmlspecialchars($property->staff_name); ?>
                                                            <br><span class="text-muted"><?php echo htmlspecialchars($property->staff_designation); ?></span>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Unassigned</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?php echo date('M d, Y', strtotime($property->created_at)); ?></small>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('properties/view/' . $property->id); ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('properties/edit/' . $property->id); ?>" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-property" 
                                                                data-id="<?php echo $property->id; ?>" 
                                                                data-name="<?php echo htmlspecialchars($property->garden_name); ?>" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Enhanced Bulk Actions -->
                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <div class="bulk-actions-panel" style="display: none;">
                                        <div class="alert alert-info border-0 shadow-sm">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fa fa-info-circle fa-lg"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <strong><span id="selected-count">0</span> properties selected</strong>
                                                    <div class="mt-2">
                                                        <div class="btn-group mr-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                                                <i class="fa fa-cog"></i> Bulk Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <h6 class="dropdown-header">Status Changes</h6>
                                                                <a class="dropdown-item bulk-status-change" href="#" data-status="unsold">
                                                                    <i class="fa fa-circle text-secondary mr-2"></i>Mark as Unsold
                                                                </a>
                                                                <a class="dropdown-item bulk-status-change" href="#" data-status="booked">
                                                                    <i class="fa fa-circle text-warning mr-2"></i>Mark as Booked
                                                                </a>
                                                                <a class="dropdown-item bulk-status-change" href="#" data-status="sold">
                                                                    <i class="fa fa-circle text-success mr-2"></i>Mark as Sold
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <h6 class="dropdown-header">Staff Assignment</h6>
                                                                <a class="dropdown-item" href="#" id="bulk-assign-staff">
                                                                    <i class="fa fa-user-plus text-primary mr-2"></i>Assign Staff
                                                                </a>
                                                                <a class="dropdown-item" href="#" id="bulk-unassign-staff">
                                                                    <i class="fa fa-user-times text-warning mr-2"></i>Unassign Staff
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#" id="bulk-delete">
                                                                    <i class="fa fa-trash mr-2"></i>Delete Selected
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-selection">
                                                            <i class="fa fa-times"></i> Clear Selection
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Pagination -->
                                    <?php if($total_pages > 1): ?>
                                        <nav aria-label="Properties pagination">
                                            <ul class="pagination justify-content-end">
                                                <?php if($current_page > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="<?php echo base_url('properties?page=' . ($current_page - 1)); ?>">Previous</a>
                                                    </li>
                                                <?php endif; ?>
                                                
                                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                                    <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                        <a class="page-link" href="<?php echo base_url('properties?page=' . $i); ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                                
                                                <?php if($current_page < $total_pages): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="<?php echo base_url('properties?page=' . ($current_page + 1)); ?>">Next</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
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
                <p>Are you sure you want to delete the property "<span id="property-name"></span>"?</p>
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
    // Enhanced Filter Toggle
    $('#toggle-filters').click(function() {
        var $filterSection = $('.filter-section');
        var $icon = $(this).find('i');
        var $text = $(this).find('.filter-text');
        
        if ($filterSection.is(':visible')) {
            $filterSection.slideUp();
            $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            $text.text('Show Filters');
        } else {
            $filterSection.slideDown();
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            $text.text('Hide Filters');
        }
    });

    // Advanced Filters Toggle
    $('#toggle-advanced').click(function() {
        var $advancedFilters = $('.advanced-filters');
        var $icon = $(this).find('i');
        
        if ($advancedFilters.is(':visible')) {
            $advancedFilters.slideUp();
            $icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
            $(this).html('<i class="fa fa-plus-circle"></i> Show Advanced Filters');
        } else {
            $advancedFilters.slideDown();
            $icon.removeClass('fa-plus-circle').addClass('fa-minus-circle');
            $(this).html('<i class="fa fa-minus-circle"></i> Hide Advanced Filters');
        }
    });

    // Clear Search
    $('#clear-search').click(function() {
        $('input[name="search"]').val('');
    });

    // Enhanced Select All Functionality
    $('#select-all').change(function() {
        $('.property-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    $('.property-checkbox').change(function() {
        updateBulkActions();
        
        // Update select all checkbox state
        var totalCheckboxes = $('.property-checkbox').length;
        var checkedCheckboxes = $('.property-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#select-all').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#select-all').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#select-all').prop('indeterminate', true);
        }
    });

    function updateBulkActions() {
        var checkedCount = $('.property-checkbox:checked').length;
        $('#selected-count').text(checkedCount);
        
        if (checkedCount > 0) {
            $('.bulk-actions-panel').slideDown();
        } else {
            $('.bulk-actions-panel').slideUp();
        }
    }

    // Clear Selection
    $('#clear-selection').click(function() {
        $('.property-checkbox').prop('checked', false);
        $('#select-all').prop('checked', false).prop('indeterminate', false);
        updateBulkActions();
    });

    // Bulk Status Change
    $('.bulk-status-change').click(function(e) {
        e.preventDefault();
        var newStatus = $(this).data('status');
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showNotification('Please select properties first', 'warning');
            return;
        }

        var statusText = $(this).text().trim();
        if (confirm('Are you sure you want to ' + statusText.toLowerCase() + ' for ' + selectedIds.length + ' properties?')) {
            performBulkAction('change_status', {new_status: newStatus}, selectedIds);
        }
    });

    // Bulk Staff Assignment
    $('#bulk-assign-staff').click(function(e) {
        e.preventDefault();
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showNotification('Please select properties first', 'warning');
            return;
        }

        // Load staff modal (you can implement this)
        showStaffAssignmentModal(selectedIds);
    });

    // Bulk Staff Unassignment
    $('#bulk-unassign-staff').click(function(e) {
        e.preventDefault();
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showNotification('Please select properties first', 'warning');
            return;
        }

        if (confirm('Are you sure you want to unassign staff from ' + selectedIds.length + ' properties?')) {
            performBulkAction('unassign_staff', {}, selectedIds);
        }
    });

    // Bulk Delete
    $('#bulk-delete').click(function(e) {
        e.preventDefault();
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showNotification('Please select properties first', 'warning');
            return;
        }

        if (confirm('Are you sure you want to delete ' + selectedIds.length + ' properties? This action cannot be undone.')) {
            performBulkAction('delete', {}, selectedIds);
        }
    });

    function getSelectedIds() {
        return $('.property-checkbox:checked').map(function() {
            return this.value;
        }).get();
    }

    function performBulkAction(action, data, selectedIds) {
        var requestData = {
            bulk_action: action,
            property_ids: selectedIds
        };
        
        // Merge additional data
        $.extend(requestData, data);

        $.ajax({
            url: '<?php echo base_url("properties/bulk_action"); ?>',
            method: 'POST',
            data: requestData,
            dataType: 'json',
            beforeSend: function() {
                showNotification('Processing...', 'info');
            },
            success: function(response) {
                if (response.status === 'success') {
                    showNotification(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showNotification(response.message, 'error');
                }
            },
            error: function() {
                showNotification('An error occurred while processing the request', 'error');
            }
        });
    }

    // Enhanced Delete Property
    var propertyToDelete = null;

    $('.delete-property').click(function(e) {
        e.preventDefault();
        propertyToDelete = $(this).data('id');
        $('#property-name').text($(this).data('name'));
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').click(function() {
        if (propertyToDelete) {
            $.ajax({
                url: '<?php echo base_url("properties/delete/"); ?>' + propertyToDelete,
                method: 'POST',
                dataType: 'json',
                beforeSend: function() {
                    $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.status === 'success') {
                        showNotification(response.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification(response.message, 'error');
                    }
                },
                error: function() {
                    $('#deleteModal').modal('hide');
                    showNotification('An error occurred while deleting the property', 'error');
                },
                complete: function() {
                    $('#confirm-delete').prop('disabled', false).html('Delete');
                }
            });
        }
    });

    // Export Properties
    $('#export-properties').click(function(e) {
        e.preventDefault();
        showNotification('Export functionality will be implemented', 'info');
        // TODO: Implement export functionality
    });

    // Notification System
    function showNotification(message, type) {
        var alertClass = 'alert-info';
        switch(type) {
            case 'success': alertClass = 'alert-success'; break;
            case 'error': alertClass = 'alert-danger'; break;
            case 'warning': alertClass = 'alert-warning'; break;
        }
        
        var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            message + '</div>');
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.alert('close');
        }, 5000);
    }

    // Auto-refresh data every 5 minutes
    setInterval(function() {
        // Only refresh if no bulk actions are active
        if ($('.property-checkbox:checked').length === 0) {
            location.reload();
        }
    }, 300000); // 5 minutes

    // Show filters if any filter is active
    <?php if (!empty($current_filters)): ?>
    $('#toggle-filters').click();
    <?php endif; ?>
});
</script>

<style>
/* Modern Card Styles */
.modern-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.modern-card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

/* Gradient Cards */
.gradient-card-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.gradient-card-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
}

.gradient-card-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
}

.gradient-card-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
}

/* Form Enhancements */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.custom-select, .form-control {
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.custom-select:focus, .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Table Enhancements */
.table {
    border-radius: 8px;
    overflow: hidden;
}

.table thead th {
    background-color: #f8f9fc;
    border-bottom: 2px solid #e3e6f0;
    font-weight: 600;
    color: #5a5c69;
    padding: 1rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
}

/* Button Enhancements */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Badge Enhancements */
.badge {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .modern-card {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
}

/* Loading States */
.loading {
    position: relative;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Checkbox Enhancements */
input[type="checkbox"] {
    transform: scale(1.2);
    margin-right: 0.5rem;
}

/* Progress Bar Enhancements */
.progress {
    border-radius: 10px;
    background-color: rgba(255, 255, 255, 0.2);
}

.progress-bar {
    border-radius: 10px;
}
</style>