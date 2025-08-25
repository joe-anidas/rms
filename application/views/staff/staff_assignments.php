<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-tasks mr-2"></i>Staff Assignment Management
                        <?php if(isset($staff) && !is_array($staff)): ?>
                            - <?php echo htmlspecialchars($staff->employee_name); ?>
                        <?php endif; ?>
                    </h5>
                    <div>
                        <a href="<?php echo base_url('staff'); ?>" class="btn btn-light btn-sm">
                            <i class="fa fa-users mr-1"></i>All Staff
                        </a>
                        <a href="<?php echo base_url('staff/workload'); ?>" class="btn btn-outline-light btn-sm">
                            <i class="fa fa-chart-bar mr-1"></i>Workload Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($staff) && !is_array($staff)): ?>
            <!-- Individual Staff Assignments -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <!-- Property Assignments -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fa fa-building mr-2"></i>Property Assignments
                                    <span class="badge badge-primary ml-2"><?php echo count($assignments['property_assignments']); ?></span>
                                </h6>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#assignPropertyModal">
                                    <i class="fa fa-plus mr-1"></i>Assign Property
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(empty($assignments['property_assignments'])): ?>
                                <p class="text-muted text-center py-3">No property assignments</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Property</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($assignments['property_assignments'] as $assignment): ?>
                                                <tr class="<?php echo $assignment->is_active ? '' : 'text-muted'; ?>">
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($assignment->garden_name); ?></strong>
                                                        <br><small><?php echo htmlspecialchars($assignment->location_details); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info"><?php echo ucfirst($assignment->assignment_type); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if($assignment->is_active): ?>
                                                            <span class="badge badge-success">Active</span>
                                                            <br><small class="text-muted">Since: <?php echo date('d M Y', strtotime($assignment->assigned_date)); ?></small>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">Ended</span>
                                                            <br><small class="text-muted">Ended: <?php echo date('d M Y', strtotime($assignment->end_date)); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($assignment->is_active): ?>
                                                            <button class="btn btn-danger btn-sm" onclick="endAssignment('property', <?php echo $assignment->id; ?>)">
                                                                <i class="fa fa-stop"></i>
                                                            </button>
                                                        <?php endif; ?>
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
                
                <div class="col-md-6">
                    <!-- Customer Assignments -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fa fa-users mr-2"></i>Customer Assignments
                                    <span class="badge badge-success ml-2"><?php echo count($assignments['customer_assignments']); ?></span>
                                </h6>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#assignCustomerModal">
                                    <i class="fa fa-plus mr-1"></i>Assign Customer
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(empty($assignments['customer_assignments'])): ?>
                                <p class="text-muted text-center py-3">No customer assignments</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($assignments['customer_assignments'] as $assignment): ?>
                                                <tr class="<?php echo $assignment->is_active ? '' : 'text-muted'; ?>">
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($assignment->plot_buyer_name); ?></strong>
                                                        <br><small><?php echo htmlspecialchars($assignment->contact_details); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info"><?php echo ucfirst($assignment->assignment_type); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if($assignment->is_active): ?>
                                                            <span class="badge badge-success">Active</span>
                                                            <br><small class="text-muted">Since: <?php echo date('d M Y', strtotime($assignment->assigned_date)); ?></small>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">Ended</span>
                                                            <br><small class="text-muted">Ended: <?php echo date('d M Y', strtotime($assignment->end_date)); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($assignment->is_active): ?>
                                                            <button class="btn btn-danger btn-sm" onclick="endAssignment('customer', <?php echo $assignment->id; ?>)">
                                                                <i class="fa fa-stop"></i>
                                                            </button>
                                                        <?php endif; ?>
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
        <?php else: ?>
            <!-- All Staff Assignments Overview -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fa fa-list mr-2"></i>All Staff Assignments Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Staff Member</th>
                                    <th>Designation</th>
                                    <th>Property Assignments</th>
                                    <th>Customer Assignments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($staff as $employee): ?>
                                    <?php $employee_assignments = $this->Staff_model->get_staff_assignments($employee->id); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($employee->employee_name); ?></strong>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($employee->contact_number); ?></small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($employee->designation ?: 'N/A'); ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($employee->department ?: 'N/A'); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?php echo count($employee_assignments['property_assignments']); ?></span>
                                            <?php if(count($employee_assignments['property_assignments']) > 0): ?>
                                                <br><small class="text-muted">Active assignments</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-success"><?php echo count($employee_assignments['customer_assignments']); ?></span>
                                            <?php if(count($employee_assignments['customer_assignments']) > 0): ?>
                                                <br><small class="text-muted">Active assignments</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo base_url('staff/assignments/' . $employee->id); ?>" class="btn btn-info btn-sm" title="Manage Assignments">
                                                    <i class="fa fa-tasks"></i>
                                                </a>
                                                <a href="<?php echo base_url('staff/profile/' . $employee->id); ?>" class="btn btn-warning btn-sm" title="View Profile">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if(isset($staff) && !is_array($staff)): ?>
    <!-- Assign Property Modal -->
    <div class="modal fade" id="assignPropertyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Property to <?php echo htmlspecialchars($staff->employee_name); ?></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="assignPropertyForm">
                    <div class="modal-body">
                        <input type="hidden" name="staff_id" value="<?php echo $staff->id; ?>">
                        
                        <div class="form-group">
                            <label>Property</label>
                            <select class="form-control" name="property_id" required>
                                <option value="">Select Property</option>
                                <?php foreach($properties as $property): ?>
                                    <option value="<?php echo $property->id; ?>">
                                        <?php echo htmlspecialchars($property->garden_name); ?> - 
                                        <?php echo htmlspecialchars($property->location_details); ?>
                                        (<?php echo ucfirst($property->status); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Assignment Type</label>
                            <select class="form-control" name="assignment_type" required>
                                <option value="">Select Type</option>
                                <option value="sales">Sales</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="customer_service">Customer Service</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Assigned Date</label>
                            <input type="date" class="form-control" name="assigned_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Property</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Customer Modal -->
    <div class="modal fade" id="assignCustomerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Customer to <?php echo htmlspecialchars($staff->employee_name); ?></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="assignCustomerForm">
                    <div class="modal-body">
                        <input type="hidden" name="staff_id" value="<?php echo $staff->id; ?>">
                        
                        <div class="form-group">
                            <label>Customer</label>
                            <select class="form-control" name="customer_id" required>
                                <option value="">Select Customer</option>
                                <?php foreach($customers as $customer): ?>
                                    <option value="<?php echo $customer->id; ?>">
                                        <?php echo htmlspecialchars($customer->plot_buyer_name); ?> - 
                                        <?php echo htmlspecialchars($customer->contact_details); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Assignment Type</label>
                            <select class="form-control" name="assignment_type" required>
                                <option value="">Select Type</option>
                                <option value="sales">Sales</option>
                                <option value="support">Support</option>
                                <option value="relationship_manager">Relationship Manager</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Assigned Date</label>
                            <input type="date" class="form-control" name="assigned_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Optional notes about this assignment"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Assign Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
// Assign Property Form
document.getElementById('assignPropertyForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i>Assigning...';
    submitBtn.disabled = true;
    
    fetch('<?php echo base_url("staff/assign_property"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            $('#assignPropertyModal').modal('hide');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while assigning property');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Assign Customer Form
document.getElementById('assignCustomerForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i>Assigning...';
    submitBtn.disabled = true;
    
    fetch('<?php echo base_url("staff/assign_customer"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            $('#assignCustomerModal').modal('hide');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while assigning customer');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// End Assignment
function endAssignment(type, assignmentId) {
    if (confirm('Are you sure you want to end this assignment?')) {
        const formData = new FormData();
        formData.append('assignment_type', type);
        formData.append('assignment_id', assignmentId);
        formData.append('end_date', new Date().toISOString().split('T')[0]);
        
        fetch('<?php echo base_url("staff/end_assignment"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while ending assignment');
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

// Reset forms when modals are closed
$('#assignPropertyModal, #assignCustomerModal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
});
</script>