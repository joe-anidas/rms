<div class="row">
    <div class="col-12">
        <!-- Staff Profile Header -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-user-circle mr-2"></i>
                        <?php echo htmlspecialchars($staff->employee_name); ?>
                        <small class="ml-2">#<?php echo $staff->id; ?></small>
                    </h5>
                    <div>
                        <a href="<?php echo base_url('staff/edit/' . $staff->id); ?>" class="btn btn-light btn-sm">
                            <i class="fa fa-edit mr-1"></i>Edit Profile
                        </a>
                        <a href="<?php echo base_url('staff'); ?>" class="btn btn-outline-light btn-sm">
                            <i class="fa fa-arrow-left mr-1"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="profile-avatar mb-3">
                            <i class="fa fa-user-circle fa-5x text-muted"></i>
                        </div>
                        <h6 class="text-primary"><?php echo htmlspecialchars($staff->designation ?: 'Staff Member'); ?></h6>
                        <p class="text-muted"><?php echo htmlspecialchars($staff->department ?: 'No Department'); ?></p>
                        <?php if($staff->joining_date): ?>
                            <small class="text-muted">
                                <i class="fa fa-calendar mr-1"></i>
                                Joined: <?php echo date('d M Y', strtotime($staff->joining_date)); ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Contact Information</h6>
                                <table class="table table-borderless table-sm">
                                    <?php if($staff->contact_number): ?>
                                        <tr>
                                            <td><i class="fa fa-phone text-primary"></i> Primary:</td>
                                            <td><?php echo htmlspecialchars($staff->contact_number); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($staff->alternate_contact): ?>
                                        <tr>
                                            <td><i class="fa fa-phone text-muted"></i> Alternate:</td>
                                            <td><?php echo htmlspecialchars($staff->alternate_contact); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($staff->email_address): ?>
                                        <tr>
                                            <td><i class="fa fa-envelope text-primary"></i> Email:</td>
                                            <td><?php echo htmlspecialchars($staff->email_address); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Personal Information</h6>
                                <table class="table table-borderless table-sm">
                                    <?php if($staff->father_name): ?>
                                        <tr>
                                            <td>Father's Name:</td>
                                            <td><?php echo htmlspecialchars($staff->father_name); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($staff->date_of_birth): ?>
                                        <tr>
                                            <td>Date of Birth:</td>
                                            <td><?php echo date('d M Y', strtotime($staff->date_of_birth)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($staff->gender): ?>
                                        <tr>
                                            <td>Gender:</td>
                                            <td><?php echo htmlspecialchars($staff->gender); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($staff->blood_group): ?>
                                        <tr>
                                            <td>Blood Group:</td>
                                            <td><?php echo htmlspecialchars($staff->blood_group); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0"><?php echo $performance['active_property_assignments']; ?></h3>
                        <p class="mb-0">Active Property Assignments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0"><?php echo $performance['active_customer_assignments']; ?></h3>
                        <p class="mb-0">Active Customer Assignments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0"><?php echo $performance['transaction_count']; ?></h3>
                        <p class="mb-0">Transactions Handled</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">₹<?php echo number_format($performance['total_transaction_amount'], 0); ?></h3>
                        <p class="mb-0">Transaction Value</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Period Filter -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Performance Period</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">From:</label>
                        <input type="date" class="form-control" name="date_from" value="<?php echo $performance['date_from']; ?>">
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">To:</label>
                        <input type="date" class="form-control" name="date_to" value="<?php echo $performance['date_to']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-refresh mr-1"></i>Update
                    </button>
                </form>
            </div>
        </div>

        <!-- Current Assignments -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fa fa-building mr-2"></i>Property Assignments
                            <span class="badge badge-primary ml-2"><?php echo count($assignments['property_assignments']); ?></span>
                        </h6>
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
                                            <th>Assigned Date</th>
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
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Ended</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?php echo date('d M Y', strtotime($assignment->assigned_date)); ?></small>
                                                    <?php if($assignment->end_date): ?>
                                                        <br><small class="text-muted">Ended: <?php echo date('d M Y', strtotime($assignment->end_date)); ?></small>
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
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fa fa-users mr-2"></i>Customer Assignments
                            <span class="badge badge-success ml-2"><?php echo count($assignments['customer_assignments']); ?></span>
                        </h6>
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
                                            <th>Assigned Date</th>
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
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Ended</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?php echo date('d M Y', strtotime($assignment->assigned_date)); ?></small>
                                                    <?php if($assignment->end_date): ?>
                                                        <br><small class="text-muted">Ended: <?php echo date('d M Y', strtotime($assignment->end_date)); ?></small>
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

        <!-- Assignment History -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fa fa-history mr-2"></i>Assignment History
                    <span class="badge badge-secondary ml-2"><?php echo count($assignment_history); ?></span>
                </h6>
            </div>
            <div class="card-body">
                <?php if(empty($assignment_history)): ?>
                    <p class="text-muted text-center py-3">No assignment history</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($assignment_history as $history): ?>
                                    <tr>
                                        <td>
                                            <small><?php echo date('d M Y', strtotime($history->assigned_date)); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $history->assignment_category === 'property' ? 'primary' : 'success'; ?>">
                                                <?php echo ucfirst($history->assignment_category); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?php echo ucfirst($history->assignment_type); ?></span>
                                        </td>
                                        <td>
                                            <?php if($history->assignment_category === 'property'): ?>
                                                <strong><?php echo htmlspecialchars($history->garden_name); ?></strong>
                                                <br><small class="text-muted"><?php echo htmlspecialchars($history->property_type); ?></small>
                                            <?php else: ?>
                                                <strong><?php echo htmlspecialchars($history->plot_buyer_name); ?></strong>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($history->is_active): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Ended</span>
                                                <?php if($history->end_date): ?>
                                                    <br><small class="text-muted"><?php echo date('d M Y', strtotime($history->end_date)); ?></small>
                                                <?php endif; ?>
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

        <!-- Detailed Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-address-card mr-2"></i>Personal Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr><td><strong>Full Name:</strong></td><td><?php echo htmlspecialchars($staff->employee_name); ?></td></tr>
                            <tr><td><strong>Father's Name:</strong></td><td><?php echo htmlspecialchars($staff->father_name ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Date of Birth:</strong></td><td><?php echo $staff->date_of_birth ? date('d M Y', strtotime($staff->date_of_birth)) : 'N/A'; ?></td></tr>
                            <tr><td><strong>Gender:</strong></td><td><?php echo htmlspecialchars($staff->gender ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Marital Status:</strong></td><td><?php echo htmlspecialchars($staff->marital_status ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Blood Group:</strong></td><td><?php echo htmlspecialchars($staff->blood_group ?: 'N/A'); ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-briefcase mr-2"></i>Employment Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr><td><strong>Designation:</strong></td><td><?php echo htmlspecialchars($staff->designation ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Department:</strong></td><td><?php echo htmlspecialchars($staff->department ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Joining Date:</strong></td><td><?php echo $staff->joining_date ? date('d M Y', strtotime($staff->joining_date)) : 'N/A'; ?></td></tr>
                            <tr><td><strong>Salary:</strong></td><td><?php echo $staff->salary ? '₹' . number_format($staff->salary, 2) : 'N/A'; ?></td></tr>
                            <tr><td><strong>ID Proof:</strong></td><td><?php echo htmlspecialchars($staff->id_proof_type ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>ID Number:</strong></td><td><?php echo htmlspecialchars($staff->id_proof_number ?: 'N/A'); ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-map-marker mr-2"></i>Address Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Permanent Address:</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($staff->permanent_address ?: 'N/A'); ?></p>
                        </div>
                        <div>
                            <strong>Current Address:</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($staff->current_address ?: 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-university mr-2"></i>Banking & Emergency</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr><td><strong>Bank Name:</strong></td><td><?php echo htmlspecialchars($staff->bank_name ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Account Number:</strong></td><td><?php echo htmlspecialchars($staff->bank_account_number ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>IFSC Code:</strong></td><td><?php echo htmlspecialchars($staff->ifsc_code ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>PAN Number:</strong></td><td><?php echo htmlspecialchars($staff->pan_number ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Aadhar Number:</strong></td><td><?php echo htmlspecialchars($staff->aadhar_number ?: 'N/A'); ?></td></tr>
                            <tr><td><strong>Emergency Contact:</strong></td><td><?php echo htmlspecialchars($staff->emergency_contact_name ?: 'N/A'); ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>