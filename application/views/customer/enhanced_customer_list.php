<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <!-- Search and Filter Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-search mr-2"></i>Customer Search & Filters</h5>
        </div>
        <div class="card-body">
            <form id="searchForm" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Search by name" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="phone" placeholder="Search by phone" value="<?php echo isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Search by email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" class="form-control" name="location" placeholder="District/Taluk/Village" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer Status</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                <option value="blacklisted" <?php echo (isset($_GET['status']) && $_GET['status'] == 'blacklisted') ? 'selected' : ''; ?>>Blacklisted</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Property Type</label>
                            <select class="form-control" name="property_type">
                                <option value="">All Types</option>
                                <option value="garden" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'garden') ? 'selected' : ''; ?>>Garden</option>
                                <option value="plot" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'plot') ? 'selected' : ''; ?>>Plot</option>
                                <option value="house" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'house') ? 'selected' : ''; ?>>House</option>
                                <option value="flat" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'flat') ? 'selected' : ''; ?>>Flat</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date From</label>
                            <input type="date" class="form-control" name="date_from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date To</label>
                            <input type="date" class="form-control" name="date_to" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search mr-1"></i>Search
                        </button>
                        <a href="<?php echo current_url(); ?>" class="btn btn-secondary">
                            <i class="fa fa-refresh mr-1"></i>Clear
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportCustomers()">
                            <i class="fa fa-download mr-1"></i>Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer List Section -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-users mr-2"></i>Enhanced Customer Management</h5>
            <div class="card-action">
                <a href="<?php echo base_url('customers/analytics'); ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-chart-bar mr-1"></i>Analytics
                </a>
                <a href="<?php echo base_url('customers/associations'); ?>" class="btn btn-info btn-sm">
                    <i class="fa fa-link mr-1"></i>Associations
                </a>
                <a href="<?php echo base_url('customer_details'); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus mr-1"></i>Add Customer
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if(empty($customers)): ?>
                <div class="text-center py-5">
                    <i class="fa fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No customers found</h5>
                    <p class="text-muted">Try adjusting your search criteria or add a new customer</p>
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
                                <th>Customer Details</th>
                                <th>Contact Information</th>
                                <th>Location</th>
                                <th>Properties</th>
                                <th>Investment</th>
                                <th>Status</th>
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
                                        <div>
                                            <strong><?php echo htmlspecialchars($customer->plot_buyer_name); ?></strong>
                                            <?php if(!empty($customer->father_name)): ?>
                                                <br><small class="text-muted">S/O: <?php echo htmlspecialchars($customer->father_name); ?></small>
                                            <?php endif; ?>
                                            <?php if(!empty($customer->aadhar_number)): ?>
                                                <br><small class="text-muted">Aadhar: <?php echo htmlspecialchars($customer->aadhar_number); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if(!empty($customer->phone_number_1)): ?>
                                            <div><i class="fa fa-phone text-success"></i> <?php echo htmlspecialchars($customer->phone_number_1); ?></div>
                                        <?php endif; ?>
                                        <?php if(!empty($customer->phone_number_2)): ?>
                                            <div><i class="fa fa-phone text-muted"></i> <?php echo htmlspecialchars($customer->phone_number_2); ?></div>
                                        <?php endif; ?>
                                        <?php if(!empty($customer->email_address)): ?>
                                            <div><i class="fa fa-envelope text-info"></i> <?php echo htmlspecialchars($customer->email_address); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($customer->district)): ?>
                                            <div><strong><?php echo htmlspecialchars($customer->district); ?></strong></div>
                                        <?php endif; ?>
                                        <?php if(!empty($customer->taluk_name)): ?>
                                            <div class="text-muted"><?php echo htmlspecialchars($customer->taluk_name); ?></div>
                                        <?php endif; ?>
                                        <?php if(!empty($customer->village_town_name)): ?>
                                            <div class="text-muted"><?php echo htmlspecialchars($customer->village_town_name); ?></div>
                                        <?php endif; ?>
                                        <?php if(!empty($customer->pincode)): ?>
                                            <div><small class="badge badge-secondary"><?php echo htmlspecialchars($customer->pincode); ?></small></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(isset($customer->total_properties) && $customer->total_properties > 0): ?>
                                            <span class="badge badge-success"><?php echo $customer->total_properties; ?> Properties</span>
                                            <?php if(!empty($customer->last_purchase_date)): ?>
                                                <br><small class="text-muted">Last: <?php echo date('d M Y', strtotime($customer->last_purchase_date)); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">No Properties</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(isset($customer->total_investment) && $customer->total_investment > 0): ?>
                                            <div><strong>₹<?php echo number_format($customer->total_investment, 2); ?></strong></div>
                                            <?php if(isset($customer->total_paid) && $customer->total_paid > 0): ?>
                                                <small class="text-success">Paid: ₹<?php echo number_format($customer->total_paid, 2); ?></small>
                                                <?php 
                                                $pending = $customer->total_investment - $customer->total_paid;
                                                if($pending > 0): ?>
                                                    <br><small class="text-warning">Pending: ₹<?php echo number_format($pending, 2); ?></small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">No Investment</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = isset($customer->customer_status) ? $customer->customer_status : 'active';
                                        $status_class = '';
                                        switch($status) {
                                            case 'active': $status_class = 'success'; break;
                                            case 'inactive': $status_class = 'warning'; break;
                                            case 'blacklisted': $status_class = 'danger'; break;
                                            default: $status_class = 'secondary';
                                        }
                                        ?>
                                        <span class="badge badge-<?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                                        <br><small class="text-muted"><?php echo date('d M Y', strtotime($customer->created_at)); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical" role="group">
                                            <a href="<?php echo base_url('customers/profile/' . $customer->id); ?>" class="btn btn-sm btn-info" title="View Full Profile">
                                                <i class="fa fa-user"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" onclick="viewCustomerProperties(<?php echo $customer->id; ?>)" title="View Properties">
                                                <i class="fa fa-home"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="viewCustomerTransactions(<?php echo $customer->id; ?>)" title="View Transactions">
                                                <i class="fa fa-money"></i>
                                            </button>
                                            <a href="<?php echo base_url('customers/edit/' . $customer->id); ?>" class="btn btn-sm btn-warning" title="Edit Customer">
                                                <i class="fa fa-edit"></i>
                                            </a>
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
                            <!-- Pagination will be added here -->
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Customer Profile Modal -->
<div class="modal fade" id="customerProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Profile</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="customerProfileBody">
                <!-- Customer profile will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Properties Modal -->
<div class="modal fade" id="customerPropertiesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Properties</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="customerPropertiesBody">
                <!-- Customer properties will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Transactions Modal -->
<div class="modal fade" id="customerTransactionsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Transactions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="customerTransactionsBody">
                <!-- Customer transactions will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// View customer profile with comprehensive details
function viewCustomerProfile(customerId) {
    fetch(`<?php echo base_url('customers/get_profile/'); ?>${customerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const customer = data.customer;
                document.getElementById('customerProfileBody').innerHTML = generateCustomerProfileHTML(customer);
                $('#customerProfileModal').modal('show');
            } else {
                alert('Error loading customer profile');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading customer profile');
        });
}

// View customer properties
function viewCustomerProperties(customerId) {
    fetch(`<?php echo base_url('customers/get_properties/'); ?>${customerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('customerPropertiesBody').innerHTML = generatePropertiesHTML(data.properties);
                $('#customerPropertiesModal').modal('show');
            } else {
                alert('Error loading customer properties');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading customer properties');
        });
}

// View customer transactions
function viewCustomerTransactions(customerId) {
    fetch(`<?php echo base_url('customers/get_transactions/'); ?>${customerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('customerTransactionsBody').innerHTML = generateTransactionsHTML(data.transactions);
                $('#customerTransactionsModal').modal('show');
            } else {
                alert('Error loading customer transactions');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading customer transactions');
        });
}

// Generate customer profile HTML
function generateCustomerProfileHTML(customer) {
    return `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary">Personal Information</h6>
                <table class="table table-borderless">
                    <tr><td><strong>Name:</strong></td><td>${customer.plot_buyer_name}</td></tr>
                    <tr><td><strong>Father's Name:</strong></td><td>${customer.father_name || 'N/A'}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>${customer.email_address || 'N/A'}</td></tr>
                    <tr><td><strong>Occupation:</strong></td><td>${customer.occupation || 'N/A'}</td></tr>
                    <tr><td><strong>Annual Income:</strong></td><td>${customer.annual_income ? '₹' + parseFloat(customer.annual_income).toLocaleString() : 'N/A'}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge badge-success">${customer.customer_status || 'Active'}</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary">Contact Information</h6>
                <table class="table table-borderless">
                    <tr><td><strong>Phone 1:</strong></td><td>${customer.phone_number_1 || 'N/A'}</td></tr>
                    <tr><td><strong>Phone 2:</strong></td><td>${customer.phone_number_2 || 'N/A'}</td></tr>
                    <tr><td><strong>Emergency Contact:</strong></td><td>${customer.emergency_contact_name || 'N/A'}</td></tr>
                    <tr><td><strong>Emergency Phone:</strong></td><td>${customer.emergency_contact_phone || 'N/A'}</td></tr>
                    <tr><td><strong>Relation:</strong></td><td>${customer.emergency_contact_relation || 'N/A'}</td></tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h6 class="text-primary">Address Information</h6>
                <table class="table table-borderless">
                    <tr><td><strong>District:</strong></td><td>${customer.district || 'N/A'}</td></tr>
                    <tr><td><strong>Taluk:</strong></td><td>${customer.taluk_name || 'N/A'}</td></tr>
                    <tr><td><strong>Village/Town:</strong></td><td>${customer.village_town_name || 'N/A'}</td></tr>
                    <tr><td><strong>Pincode:</strong></td><td>${customer.pincode || 'N/A'}</td></tr>
                    <tr><td><strong>Street Address:</strong></td><td>${customer.street_address || 'N/A'}</td></tr>
                    <tr><td><strong>Alternate Address:</strong></td><td>${customer.alternate_address || 'N/A'}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary">Financial & ID Information</h6>
                <table class="table table-borderless">
                    <tr><td><strong>Aadhar:</strong></td><td>${customer.aadhar_number || 'N/A'}</td></tr>
                    <tr><td><strong>PAN:</strong></td><td>${customer.pan_number || 'N/A'}</td></tr>
                    <tr><td><strong>Bank Name:</strong></td><td>${customer.bank_name || 'N/A'}</td></tr>
                    <tr><td><strong>Account Number:</strong></td><td>${customer.bank_account_number || 'N/A'}</td></tr>
                    <tr><td><strong>IFSC Code:</strong></td><td>${customer.ifsc_code || 'N/A'}</td></tr>
                    <tr><td><strong>Reference Source:</strong></td><td>${customer.reference_source || 'N/A'}</td></tr>
                </table>
            </div>
        </div>
        ${customer.registration_summary ? `
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary">Investment Summary</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>${customer.registration_summary.total_registrations || 0}</h4>
                                <small>Total Properties</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>₹${customer.registration_summary.total_investment ? parseFloat(customer.registration_summary.total_investment).toLocaleString() : '0'}</h4>
                                <small>Total Investment</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>₹${customer.registration_summary.total_paid_amount ? parseFloat(customer.registration_summary.total_paid_amount).toLocaleString() : '0'}</h4>
                                <small>Amount Paid</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>₹${customer.registration_summary.total_investment && customer.registration_summary.total_paid_amount ? parseFloat(customer.registration_summary.total_investment - customer.registration_summary.total_paid_amount).toLocaleString() : '0'}</h4>
                                <small>Pending Amount</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ` : ''}
        ${customer.notes ? `
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary">Notes</h6>
                <div class="alert alert-info">
                    ${customer.notes}
                </div>
            </div>
        </div>
        ` : ''}
    `;
}

// Generate properties HTML
function generatePropertiesHTML(properties) {
    if (!properties || properties.length === 0) {
        return '<div class="text-center py-4"><i class="fa fa-home fa-3x text-muted mb-3"></i><h5 class="text-muted">No properties found</h5></div>';
    }
    
    let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Property</th><th>Registration</th><th>Amount</th><th>Status</th></tr></thead><tbody>';
    
    properties.forEach(property => {
        html += `
            <tr>
                <td>
                    <strong>${property.garden_name}</strong><br>
                    <small class="text-muted">${property.property_type} - ${property.size_sqft} sqft</small><br>
                    <small class="text-muted">${property.district || ''}</small>
                </td>
                <td>
                    <strong>${property.registration_number}</strong><br>
                    <small class="text-muted">${new Date(property.registration_date).toLocaleDateString()}</small>
                </td>
                <td>
                    <strong>₹${parseFloat(property.total_amount || 0).toLocaleString()}</strong><br>
                    <small class="text-success">Paid: ₹${parseFloat(property.paid_amount || 0).toLocaleString()}</small>
                </td>
                <td>
                    <span class="badge badge-${property.registration_status === 'active' ? 'success' : property.registration_status === 'completed' ? 'primary' : 'secondary'}">${property.registration_status}</span>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    return html;
}

// Generate transactions HTML
function generateTransactionsHTML(transactions) {
    if (!transactions || transactions.length === 0) {
        return '<div class="text-center py-4"><i class="fa fa-money fa-3x text-muted mb-3"></i><h5 class="text-muted">No transactions found</h5></div>';
    }
    
    let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Date</th><th>Property</th><th>Amount</th><th>Type</th><th>Method</th><th>Receipt</th></tr></thead><tbody>';
    
    transactions.forEach(transaction => {
        html += `
            <tr>
                <td>${new Date(transaction.payment_date).toLocaleDateString()}</td>
                <td>
                    <strong>${transaction.garden_name}</strong><br>
                    <small class="text-muted">${transaction.registration_number}</small>
                </td>
                <td><strong>₹${parseFloat(transaction.amount).toLocaleString()}</strong></td>
                <td><span class="badge badge-info">${transaction.payment_type}</span></td>
                <td><span class="badge badge-secondary">${transaction.payment_method}</span></td>
                <td>${transaction.receipt_number || 'N/A'}</td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    return html;
}

// Edit customer (placeholder)
function editCustomer(customerId) {
    window.location.href = `<?php echo base_url('customers/edit/'); ?>${customerId}`;
}

// Export customers data
function exportCustomers() {
    const form = document.getElementById('searchForm');
    const formData = new FormData(form);
    formData.append('export', '1');
    
    const params = new URLSearchParams(formData);
    window.location.href = `<?php echo base_url('customers/export'); ?>?${params.toString()}`;
}
</script>