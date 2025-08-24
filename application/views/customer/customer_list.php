<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa fa-users mr-2"></i>Customer Details List</h5>
        <div class="card-action">
            <a href="<?php echo base_url('customer_details'); ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus mr-1"></i>Add New Customer
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($customers)): ?>
            <div class="text-center py-5">
                <i class="fa fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No customers found</h5>
                <p class="text-muted">Start by adding your first customer</p>
                <a href="<?php echo base_url('customer_details'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>Add Customer
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Father Name</th>
                            <th>District</th>
                            <th>Phone</th>
                            <th>Plot Details</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $customer): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-primary">#<?php echo $customer->id; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($customer->plot_buyer_name); ?></strong>
                                    <?php if($customer->aadhar_number): ?>
                                        <br><small class="text-muted">Aadhar: <?php echo htmlspecialchars($customer->aadhar_number); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($customer->father_name ?: 'N/A'); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($customer->district ?: 'N/A'); ?>
                                    <?php if($customer->pincode): ?>
                                        <br><small class="text-muted">PIN: <?php echo htmlspecialchars($customer->pincode); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($customer->phone_number_1): ?>
                                        <div><?php echo htmlspecialchars($customer->phone_number_1); ?></div>
                                    <?php endif; ?>
                                    <?php if($customer->phone_number_2): ?>
                                        <div class="text-muted"><?php echo htmlspecialchars($customer->phone_number_2); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($customer->total_plot_bought ?: 'N/A'); ?></strong>
                                    <?php if($customer->taluk_name || $customer->village_town_name): ?>
                                        <br><small class="text-muted">
                                            <?php 
                                            $location = array_filter([$customer->taluk_name, $customer->village_town_name]);
                                            echo htmlspecialchars(implode(', ', $location));
                                            ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d M Y', strtotime($customer->created_at)); ?>
                                        <br><?php echo date('h:i A', strtotime($customer->created_at)); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewCustomer(<?php echo $customer->id; ?>)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editCustomer(<?php echo $customer->id; ?>)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteCustomer(<?php echo $customer->id; ?>)">
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
                            Showing <strong><?php echo count($customers); ?></strong> customer(s)
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success btn-sm" onclick="exportCustomers()">
                            <i class="fa fa-download mr-1"></i>Export Data
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Customer Details Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="customerModalBody">
                <!-- Customer details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// View customer details
function viewCustomer(customerId) {
    fetch(`<?php echo base_url('get_customer/'); ?>${customerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const customer = data.customer;
                document.getElementById('customerModalBody').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Personal Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Name:</strong></td><td>${customer.plot_buyer_name}</td></tr>
                                <tr><td><strong>Father's Name:</strong></td><td>${customer.father_name || 'N/A'}</td></tr>
                                <tr><td><strong>ID Proof:</strong></td><td>${customer.id_proof || 'N/A'}</td></tr>
                                <tr><td><strong>Aadhar:</strong></td><td>${customer.aadhar_number || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Contact Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Phone 1:</strong></td><td>${customer.phone_number_1 || 'N/A'}</td></tr>
                                <tr><td><strong>Phone 2:</strong></td><td>${customer.phone_number_2 || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-primary">Location Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>District:</strong></td><td>${customer.district || 'N/A'}</td></tr>
                                <tr><td><strong>Taluk:</strong></td><td>${customer.taluk_name || 'N/A'}</td></tr>
                                <tr><td><strong>Village/Town:</strong></td><td>${customer.village_town_name || 'N/A'}</td></tr>
                                <tr><td><strong>Pincode:</strong></td><td>${customer.pincode || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Plot Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Total Plot:</strong></td><td>${customer.total_plot_bought || 'N/A'}</td></tr>
                                <tr><td><strong>Address:</strong></td><td>${customer.street_address || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Timestamps</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Created:</strong></td><td>${new Date(customer.created_at).toLocaleString()}</td></tr>
                                <tr><td><strong>Last Updated:</strong></td><td>${new Date(customer.updated_at).toLocaleString()}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                $('#customerModal').modal('show');
            } else {
                alert('Error loading customer details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading customer details');
        });
}

// Edit customer (placeholder function)
function editCustomer(customerId) {
    alert('Edit functionality will be implemented in the next version');
}

// Delete customer (placeholder function)
function deleteCustomer(customerId) {
    if (confirm('Are you sure you want to delete this customer?')) {
        alert('Delete functionality will be implemented in the next version');
    }
}

// Export customers data
function exportCustomers() {
    // Create CSV content
    const headers = ['ID', 'Customer Name', 'Father Name', 'District', 'Pincode', 'Taluk', 'Village/Town', 'Address', 'Plot Size', 'Phone 1', 'Phone 2', 'ID Proof', 'Aadhar', 'Created Date'];
    const csvContent = [
        headers.join(','),
        ...<?php echo json_encode(array_map(function($customer) {
            return [
                $customer->id,
                $customer->plot_buyer_name,
                $customer->father_name ?: '',
                $customer->district ?: '',
                $customer->pincode ?: '',
                $customer->taluk_name ?: '',
                $customer->village_town_name ?: '',
                $customer->street_address ?: '',
                $customer->total_plot_bought ?: '',
                $customer->phone_number_1 ?: '',
                $customer->phone_number_2 ?: '',
                $customer->id_proof ?: '',
                $customer->aadhar_number ?: '',
                $customer->created_at
            ];
        }, $customers)); ?>.map(row => row.map(field => `"${field}"`).join(','))
    ].join('\n');
    
    // Download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'customers_data.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
