<div class="row">
    <div class="col-12">
        <!-- Staff Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['total_staff']; ?></h4>
                                <p class="mb-0">Total Staff</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['active_property_assignments']; ?></h4>
                                <p class="mb-0">Property Assignments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['active_customer_assignments']; ?></h4>
                                <p class="mb-0">Customer Assignments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-user-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['staff_with_property_assignments'] + $stats['staff_with_customer_assignments']; ?></h4>
                                <p class="mb-0">Active Staff</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa fa-user-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa fa-search mr-2"></i>Search & Filter Staff</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('staff'); ?>" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($filters['name']); ?>" placeholder="Search by name">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Designation</label>
                                <select class="form-control" name="designation">
                                    <option value="">All Designations</option>
                                    <?php foreach($designations as $designation): ?>
                                        <option value="<?php echo htmlspecialchars($designation); ?>" <?php echo ($filters['designation'] === $designation) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($designation); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Department</label>
                                <select class="form-control" name="department">
                                    <option value="">All Departments</option>
                                    <?php foreach($departments as $department): ?>
                                        <option value="<?php echo htmlspecialchars($department); ?>" <?php echo ($filters['department'] === $department) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($department); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" class="form-control" name="contact" value="<?php echo htmlspecialchars($filters['contact']); ?>" placeholder="Phone/Email">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Assignments</label>
                                <select class="form-control" name="has_assignments">
                                    <option value="">All Staff</option>
                                    <option value="yes" <?php echo ($filters['has_assignments'] === 'yes') ? 'selected' : ''; ?>>With Assignments</option>
                                    <option value="no" <?php echo ($filters['has_assignments'] === 'no') ? 'selected' : ''; ?>>Without Assignments</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Staff List -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-users mr-2"></i>Staff Management</h5>
                    <div>
                        <a href="<?php echo base_url('staff/workload'); ?>" class="btn btn-info btn-sm mr-2">
                            <i class="fa fa-chart-bar mr-1"></i>Workload Dashboard
                        </a>
                        <a href="<?php echo base_url('staff/assignments'); ?>" class="btn btn-warning btn-sm mr-2">
                            <i class="fa fa-tasks mr-1"></i>Manage Assignments
                        </a>
                        <a href="<?php echo base_url('staff/create'); ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus mr-1"></i>Add New Staff
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if(empty($staff)): ?>
                    <div class="text-center py-5">
                        <i class="fa fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No staff found</h5>
                        <p class="text-muted">
                            <?php if(array_filter($filters)): ?>
                                No staff members match your search criteria. Try adjusting your filters.
                            <?php else: ?>
                                Start by adding your first staff member.
                            <?php endif; ?>
                        </p>
                        <?php if(!array_filter($filters)): ?>
                            <a href="<?php echo base_url('staff/create'); ?>" class="btn btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add Staff
                            </a>
                        <?php else: ?>
                            <a href="<?php echo base_url('staff'); ?>" class="btn btn-secondary">
                                <i class="fa fa-times mr-2"></i>Clear Filters
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>
                                        <a href="?<?php echo http_build_query(array_merge($filters, ['sort_by' => 'id', 'sort_order' => $filters['sort_order'] === 'ASC' ? 'DESC' : 'ASC'])); ?>" class="text-white text-decoration-none">
                                            ID <?php if($filters['sort_by'] === 'id'): ?><i class="fa fa-sort-<?php echo $filters['sort_order'] === 'ASC' ? 'up' : 'down'; ?>"></i><?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?<?php echo http_build_query(array_merge($filters, ['sort_by' => 'employee_name', 'sort_order' => $filters['sort_order'] === 'ASC' ? 'DESC' : 'ASC'])); ?>" class="text-white text-decoration-none">
                                            Employee Name <?php if($filters['sort_by'] === 'employee_name'): ?><i class="fa fa-sort-<?php echo $filters['sort_order'] === 'ASC' ? 'up' : 'down'; ?>"></i><?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?<?php echo http_build_query(array_merge($filters, ['sort_by' => 'designation', 'sort_order' => $filters['sort_order'] === 'ASC' ? 'DESC' : 'ASC'])); ?>" class="text-white text-decoration-none">
                                            Designation <?php if($filters['sort_by'] === 'designation'): ?><i class="fa fa-sort-<?php echo $filters['sort_order'] === 'ASC' ? 'up' : 'down'; ?>"></i><?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Contact</th>
                                    <th>Assignments</th>
                                    <th>
                                        <a href="?<?php echo http_build_query(array_merge($filters, ['sort_by' => 'joining_date', 'sort_order' => $filters['sort_order'] === 'ASC' ? 'DESC' : 'ASC'])); ?>" class="text-white text-decoration-none">
                                            Joining Date <?php if($filters['sort_by'] === 'joining_date'): ?><i class="fa fa-sort-<?php echo $filters['sort_order'] === 'ASC' ? 'up' : 'down'; ?>"></i><?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($staff as $employee): ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary">#<?php echo $employee->id; ?></span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($employee->employee_name); ?></strong>
                                                <?php if($employee->email_address): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($employee->email_address); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php echo htmlspecialchars($employee->designation ?: 'N/A'); ?>
                                                <?php if($employee->department): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($employee->department); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($employee->contact_number): ?>
                                                <div><i class="fa fa-phone text-primary"></i> <?php echo htmlspecialchars($employee->contact_number); ?></div>
                                            <?php endif; ?>
                                            <?php if($employee->alternate_contact): ?>
                                                <div><i class="fa fa-phone text-muted"></i> <?php echo htmlspecialchars($employee->alternate_contact); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <?php if($employee->active_property_assignments > 0): ?>
                                                    <span class="badge badge-success mr-1" title="Property Assignments">
                                                        <i class="fa fa-building"></i> <?php echo $employee->active_property_assignments; ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($employee->active_customer_assignments > 0): ?>
                                                    <span class="badge badge-info mr-1" title="Customer Assignments">
                                                        <i class="fa fa-user"></i> <?php echo $employee->active_customer_assignments; ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($employee->active_property_assignments == 0 && $employee->active_customer_assignments == 0): ?>
                                                    <span class="badge badge-secondary">No Assignments</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($employee->joining_date): ?>
                                                <small class="text-muted">
                                                    <?php echo date('d M Y', strtotime($employee->joining_date)); ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo base_url('staff/profile/' . $employee->id); ?>" class="btn btn-sm btn-info" title="View Profile">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo base_url('staff/edit/' . $employee->id); ?>" class="btn btn-sm btn-warning" title="Edit Staff">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?php echo base_url('staff/assignments/' . $employee->id); ?>" class="btn btn-sm btn-success" title="Manage Assignments">
                                                    <i class="fa fa-tasks"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteStaff(<?php echo $employee->id; ?>)" title="Delete Staff">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted">
                                    Showing <strong><?php echo count($staff); ?></strong> staff member(s)
                                </p>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="btn-group">
                                    <a href="<?php echo base_url('staff/export?format=csv&' . http_build_query($filters)); ?>" class="btn btn-success btn-sm">
                                        <i class="fa fa-file-csv mr-1"></i>Export CSV
                                    </a>
                                    <a href="<?php echo base_url('staff/export?format=excel&' . http_build_query($filters)); ?>" class="btn btn-success btn-sm">
                                        <i class="fa fa-file-excel mr-1"></i>Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Delete staff function
function deleteStaff(staffId) {
    if (confirm('Are you sure you want to delete this staff member? This action cannot be undone.')) {
        fetch(`<?php echo base_url('staff/delete/'); ?>${staffId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while deleting the staff member');
        });
    }
}

// Show alert function
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.querySelector('.row').insertBefore(alertDiv, document.querySelector('.row').firstChild);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Auto-submit form on filter change
document.querySelectorAll('#filterForm select').forEach(select => {
    select.addEventListener('change', () => {
        document.getElementById('filterForm').submit();
    });
});
</script>