<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Advanced Property Search</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('properties'); ?>">Properties</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Advanced Search</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('properties'); ?>" class="btn btn-outline-secondary waves-effect waves-light">
                        <i class="fa fa-arrow-left"></i> Back to Properties
                    </a>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Search Criteria</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo base_url('properties/search'); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Search Text</label>
                                        <input type="text" name="search_text" class="form-control" 
                                               placeholder="Search by name, description, location..." 
                                               value="<?php echo isset($search_criteria['search_text']) ? $search_criteria['search_text'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <?php foreach($statuses as $status): ?>
                                                <option value="<?php echo $status; ?>" 
                                                        <?php echo (isset($search_criteria['status']) && $search_criteria['status'] == $status) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($status); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Property Type</label>
                                        <select name="property_type" class="form-control">
                                            <option value="">All Types</option>
                                            <?php foreach($property_types as $type): ?>
                                                <option value="<?php echo $type; ?>" 
                                                        <?php echo (isset($search_criteria['property_type']) && $search_criteria['property_type'] == $type) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($type); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Min Price (₹)</label>
                                        <input type="number" name="min_price" class="form-control" 
                                               placeholder="Minimum price" step="0.01" min="0"
                                               value="<?php echo isset($search_criteria['min_price']) ? $search_criteria['min_price'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Price (₹)</label>
                                        <input type="number" name="max_price" class="form-control" 
                                               placeholder="Maximum price" step="0.01" min="0"
                                               value="<?php echo isset($search_criteria['max_price']) ? $search_criteria['max_price'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Min Size (Sq Ft)</label>
                                        <input type="number" name="min_size" class="form-control" 
                                               placeholder="Minimum size" step="0.01" min="0"
                                               value="<?php echo isset($search_criteria['min_size']) ? $search_criteria['min_size'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Size (Sq Ft)</label>
                                        <input type="number" name="max_size" class="form-control" 
                                               placeholder="Maximum size" step="0.01" min="0"
                                               value="<?php echo isset($search_criteria['max_size']) ? $search_criteria['max_size'] : ''; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>District</label>
                                        <select name="district" class="form-control">
                                            <option value="">All Districts</option>
                                            <?php foreach($districts as $district): ?>
                                                <option value="<?php echo $district; ?>" 
                                                        <?php echo (isset($search_criteria['district']) && $search_criteria['district'] == $district) ? 'selected' : ''; ?>>
                                                    <?php echo $district; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Taluk</label>
                                        <select name="taluk_name" class="form-control">
                                            <option value="">All Taluks</option>
                                            <?php foreach($taluks as $taluk): ?>
                                                <option value="<?php echo $taluk; ?>" 
                                                        <?php echo (isset($search_criteria['taluk_name']) && $search_criteria['taluk_name'] == $taluk) ? 'selected' : ''; ?>>
                                                    <?php echo $taluk; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Assigned Staff</label>
                                        <select name="assigned_staff_id" class="form-control">
                                            <option value="">All Staff</option>
                                            <option value="unassigned" <?php echo (isset($search_criteria['unassigned']) && $search_criteria['unassigned']) ? 'selected' : ''; ?>>
                                                Unassigned Properties
                                            </option>
                                            <?php foreach($staff_list as $staff): ?>
                                                <option value="<?php echo $staff->id; ?>" 
                                                        <?php echo (isset($search_criteria['assigned_staff_id']) && $search_criteria['assigned_staff_id'] == $staff->id) ? 'selected' : ''; ?>>
                                                    <?php echo $staff->employee_name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Search Properties
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> Clear Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <?php if(isset($properties)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Search Results 
                            <span class="badge badge-primary"><?php echo count($properties); ?> properties found</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($properties)): ?>
                            <div class="text-center py-4">
                                <i class="icon-magnifier fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No properties found</h5>
                                <p class="text-muted">Try adjusting your search criteria</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Property Name</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            <th>Size (Sq Ft)</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Assigned Staff</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($properties as $property): ?>
                                            <tr>
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
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('properties/view/' . $property->id); ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('properties/edit/' . $property->id); ?>" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
$(document).ready(function() {
    // Reset form functionality
    $('button[type="reset"]').click(function() {
        $('form')[0].reset();
        $('select').val('');
    });

    // Auto-suggest for location fields (can be enhanced with AJAX)
    $('#district, #taluk_name').on('input', function() {
        // Placeholder for auto-suggest functionality
    });
});
</script>