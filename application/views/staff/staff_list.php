<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa fa-users mr-2"></i>Staff Details List</h5>
        <div class="card-action">
            <a href="<?php echo base_url('staff_details'); ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus mr-1"></i>Add New Staff
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($staff)): ?>
            <div class="text-center py-5">
                <i class="fa fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No staff found</h5>
                <p class="text-muted">Start by adding your first staff member</p>
                <a href="<?php echo base_url('staff_details'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>Add Staff
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Contact</th>
                            <th>Joining Date</th>
                            <th>Salary</th>
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
                                    <strong><?php echo htmlspecialchars($employee->employee_name); ?></strong>
                                    <?php if($employee->email_address): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($employee->email_address); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($employee->designation ?: 'N/A'); ?>
                                    <?php if($employee->id_proof_type): ?>
                                        <br><small class="text-muted">ID: <?php echo htmlspecialchars($employee->id_proof_type); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($employee->department ?: 'N/A'); ?>
                                    <?php if($employee->blood_group): ?>
                                        <br><small class="text-muted">Blood: <?php echo htmlspecialchars($employee->blood_group); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($employee->contact_number): ?>
                                        <div><?php echo htmlspecialchars($employee->contact_number); ?></div>
                                    <?php endif; ?>
                                    <?php if($employee->alternate_contact): ?>
                                        <div class="text-muted">Alt: <?php echo htmlspecialchars($employee->alternate_contact); ?></div>
                                    <?php endif; ?>
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
                                    <?php if($employee->salary): ?>
                                        <strong>₹<?php echo number_format($employee->salary, 2); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewStaff(<?php echo $employee->id; ?>)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editStaff(<?php echo $employee->id; ?>)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteStaff(<?php echo $employee->id; ?>)">
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
                        <button class="btn btn-success btn-sm" onclick="exportStaff()">
                            <i class="fa fa-download mr-1"></i>Export Data
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Staff Details Modal -->
<div class="modal fade" id="staffModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Staff Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="staffModalBody">
                <!-- Staff details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// View staff details
function viewStaff(staffId) {
    fetch(`<?php echo base_url('get_staff/'); ?>${staffId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const staff = data.staff;
                document.getElementById('staffModalBody').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Personal Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${staff.employee_name}</td></tr>
                                <tr><td><strong>Father's Name:</strong></td><td>${staff.father_name || 'N/A'}</td></tr>
                                <tr><td><strong>Date of Birth:</strong></td><td>${staff.date_of_birth || 'N/A'}</td></tr>
                                <tr><td><strong>Gender:</strong></td><td>${staff.gender || 'N/A'}</td></tr>
                                <tr><td><strong>Marital Status:</strong></td><td>${staff.marital_status || 'N/A'}</td></tr>
                                <tr><td><strong>Blood Group:</strong></td><td>${staff.blood_group || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Contact Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Contact:</strong></td><td>${staff.contact_number || 'N/A'}</td></tr>
                                <tr><td><strong>Alternate:</strong></td><td>${staff.alternate_contact || 'N/A'}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${staff.email_address || 'N/A'}</td></tr>
                                <tr><td><strong>Permanent Address:</strong></td><td>${staff.permanent_address || 'N/A'}</td></tr>
                                <tr><td><strong>Current Address:</strong></td><td>${staff.current_address || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-primary">Professional Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Designation:</strong></td><td>${staff.designation || 'N/A'}</td></tr>
                                <tr><td><strong>Department:</strong></td><td>${staff.department || 'N/A'}</td></tr>
                                <tr><td><strong>Joining Date:</strong></td><td>${staff.joining_date || 'N/A'}</td></tr>
                                <tr><td><strong>Salary:</strong></td><td>₹${staff.salary ? parseFloat(staff.salary).toLocaleString() : 'N/A'}</td></tr>
                                <tr><td><strong>ID Proof:</strong></td><td>${staff.id_proof_type || 'N/A'}</td></tr>
                                <tr><td><strong>ID Number:</strong></td><td>${staff.id_proof_number || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Banking & Emergency</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Bank:</strong></td><td>${staff.bank_name || 'N/A'}</td></tr>
                                <tr><td><strong>Account:</strong></td><td>${staff.bank_account_number || 'N/A'}</td></tr>
                                <tr><td><strong>IFSC:</strong></td><td>${staff.ifsc_code || 'N/A'}</td></tr>
                                <tr><td><strong>PAN:</strong></td><td>${staff.pan_number || 'N/A'}</td></tr>
                                <tr><td><strong>Aadhar:</strong></td><td>${staff.aadhar_number || 'N/A'}</td></tr>
                                <tr><td><strong>Emergency Contact:</strong></td><td>${staff.emergency_contact_name || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Timestamps</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Created:</strong></td><td>${new Date(staff.created_at).toLocaleString()}</td></tr>
                                <tr><td><strong>Last Updated:</strong></td><td>${new Date(staff.updated_at).toLocaleString()}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                $('#staffModal').modal('show');
            } else {
                alert('Error loading staff details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading staff details');
        });
}

// Edit staff (placeholder function)
function editStaff(staffId) {
    alert('Edit functionality will be implemented in the next version');
}

// Delete staff (placeholder function)
function deleteStaff(staffId) {
    if (confirm('Are you sure you want to delete this staff member?')) {
        alert('Delete functionality will be implemented in the next version');
    }
}

// Export staff data
function exportStaff() {
    // Create CSV content
    const headers = ['ID', 'Employee Name', 'Father Name', 'Date of Birth', 'Gender', 'Marital Status', 'Blood Group', 'Contact Number', 'Email', 'Designation', 'Department', 'Joining Date', 'Salary', 'Bank Name', 'PAN', 'Aadhar', 'Created Date'];
    const csvContent = [
        headers.join(','),
        ...<?php echo json_encode(array_map(function($staff) {
            return [
                $staff->id,
                $staff->employee_name,
                $staff->father_name ?: '',
                $staff->date_of_birth ?: '',
                $staff->gender ?: '',
                $staff->marital_status ?: '',
                $staff->blood_group ?: '',
                $staff->contact_number ?: '',
                $staff->email_address ?: '',
                $staff->designation ?: '',
                $staff->department ?: '',
                $staff->joining_date ?: '',
                $staff->salary ?: '',
                $staff->bank_name ?: '',
                $staff->pan_number ?: '',
                $staff->aadhar_number ?: '',
                $staff->created_at
            ];
        }, $staff)); ?>.map(row => row.map(field => `"${field}"`).join(','))
    ].join('\n');
    
    // Download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'staff_data.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
