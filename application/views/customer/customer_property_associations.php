<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-link mr-2"></i>Customer-Property Association Management
                    </h5>
                    <div class="card-action">
                        <button class="btn btn-primary btn-sm" onclick="showCreateAssociationModal()">
                            <i class="fa fa-plus mr-1"></i>New Association
                        </button>
                        <button class="btn btn-info btn-sm" onclick="refreshAssociations()">
                            <i class="fa fa-refresh mr-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fa fa-search mr-2"></i>Search & Filter Associations</h6>
        </div>
        <div class="card-body">
            <form id="associationSearchForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" class="form-control" id="searchCustomer" placeholder="Search by customer name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Property Name</label>
                            <input type="text" class="form-control" id="searchProperty" placeholder="Search by property name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Registration Status</label>
                            <select class="form-control" id="filterStatus">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Property Type</label>
                            <select class="form-control" id="filterPropertyType">
                                <option value="">All Types</option>
                                <option value="garden">Garden</option>
                                <option value="plot">Plot</option>
                                <option value="house">House</option>
                                <option value="flat">Flat</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" onclick="searchAssociations()">
                            <i class="fa fa-search mr-1"></i>Search
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="clearSearch()">
                            <i class="fa fa-refresh mr-1"></i>Clear
                        </button>
                        <button type="button" class="btn btn-success" onclick="exportAssociations()">
                            <i class="fa fa-download mr-1"></i>Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Associations List -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fa fa-list mr-2"></i>Customer-Property Associations</h6>
        </div>
        <div class="card-body">
            <div id="associationsContainer">
                <div class="text-center py-4">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Loading associations...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Association Modal -->
<div class="modal fade" id="createAssociationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Customer-Property Association</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createAssociationForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Customer <span class="text-danger">*</span></label>
                                <select class="form-control" id="customerId" name="customer_id" required>
                                    <option value="">Choose Customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Property <span class="text-danger">*</span></label>
                                <select class="form-control" id="propertyId" name="property_id" required>
                                    <option value="">Choose Property</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Registration Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="registration_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="total_amount" placeholder="Enter total amount" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Initial Payment</label>
                                <input type="number" class="form-control" name="initial_payment" placeholder="Enter initial payment" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Agreement Path</label>
                                <input type="text" class="form-control" name="agreement_path" placeholder="Path to agreement document">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Enter any additional notes"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createAssociation()">
                    <i class="fa fa-save mr-1"></i>Create Association
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Association Modal -->
<div class="modal fade" id="editAssociationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer-Property Association</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAssociationForm">
                    <input type="hidden" id="editAssociationId" name="association_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer</label>
                                <input type="text" class="form-control" id="editCustomerName" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Property</label>
                                <input type="text" class="form-control" id="editPropertyName" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Registration Date</label>
                                <input type="date" class="form-control" id="editRegistrationDate" name="registration_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Amount</label>
                                <input type="number" class="form-control" id="editTotalAmount" name="total_amount" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Paid Amount</label>
                                <input type="number" class="form-control" id="editPaidAmount" name="paid_amount" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="editStatus" name="status">
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Agreement Path</label>
                                <input type="text" class="form-control" id="editAgreementPath" name="agreement_path">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateAssociation()">
                    <i class="fa fa-save mr-1"></i>Update Association
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Load associations on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAssociations();
    loadCustomers();
    loadProperties();
});

// Load all associations
function loadAssociations(filters = {}) {
    fetch('<?php echo base_url('customers/get_associations'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filters)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            displayAssociations(data.associations);
        } else {
            document.getElementById('associationsContainer').innerHTML = 
                '<div class="text-center py-4"><i class="fa fa-exclamation-triangle fa-2x text-warning"></i><p class="mt-2">Error loading associations</p></div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('associationsContainer').innerHTML = 
            '<div class="text-center py-4"><i class="fa fa-exclamation-triangle fa-2x text-danger"></i><p class="mt-2">Error loading associations</p></div>';
    });
}

// Display associations in table
function displayAssociations(associations) {
    if (!associations || associations.length === 0) {
        document.getElementById('associationsContainer').innerHTML = `
            <div class="text-center py-5">
                <i class="fa fa-link fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No associations found</h5>
                <p class="text-muted">Create a new customer-property association to get started.</p>
                <button class="btn btn-primary" onclick="showCreateAssociationModal()">
                    <i class="fa fa-plus mr-2"></i>Create Association
                </button>
            </div>
        `;
        return;
    }

    let html = `
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Registration #</th>
                        <th>Customer</th>
                        <th>Property</th>
                        <th>Registration Date</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;

    associations.forEach(association => {
        const paidPercentage = association.total_amount > 0 ? 
            ((association.paid_amount || 0) / association.total_amount) * 100 : 0;
        
        html += `
            <tr>
                <td>
                    <strong>${association.registration_number}</strong>
                </td>
                <td>
                    <strong>${association.customer_name}</strong><br>
                    <small class="text-muted">${association.customer_phone || 'N/A'}</small>
                </td>
                <td>
                    <strong>${association.property_name}</strong><br>
                    <small class="text-muted">${association.property_type} - ${association.property_location || 'N/A'}</small>
                </td>
                <td>
                    ${new Date(association.registration_date).toLocaleDateString()}
                </td>
                <td>
                    <strong>₹${parseFloat(association.total_amount || 0).toLocaleString()}</strong><br>
                    <small class="text-success">Paid: ₹${parseFloat(association.paid_amount || 0).toLocaleString()}</small>
                </td>
                <td>
                    <div class="progress mb-1" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: ${paidPercentage}%">
                            ${Math.round(paidPercentage)}%
                        </div>
                    </div>
                    <small class="text-warning">Pending: ₹${parseFloat((association.total_amount || 0) - (association.paid_amount || 0)).toLocaleString()}</small>
                </td>
                <td>
                    <span class="badge badge-${association.status === 'active' ? 'success' : (association.status === 'completed' ? 'primary' : 'secondary')}">
                        ${association.status.charAt(0).toUpperCase() + association.status.slice(1)}
                    </span>
                </td>
                <td>
                    <div class="btn-group-vertical btn-group-sm">
                        <button class="btn btn-info btn-sm" onclick="viewAssociation(${association.id})" title="View Details">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="editAssociation(${association.id})" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-success btn-sm" onclick="recordPayment(${association.id})" title="Record Payment">
                            <i class="fa fa-money"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteAssociation(${association.id})" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    html += '</tbody></table></div>';
    document.getElementById('associationsContainer').innerHTML = html;
}

// Load customers for dropdown
function loadCustomers() {
    fetch('<?php echo base_url('customers/get_all_for_dropdown'); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('customerId');
                select.innerHTML = '<option value="">Choose Customer</option>';
                data.customers.forEach(customer => {
                    select.innerHTML += `<option value="${customer.id}">${customer.plot_buyer_name} - ${customer.phone_number_1 || 'N/A'}</option>`;
                });
            }
        })
        .catch(error => console.error('Error loading customers:', error));
}

// Load properties for dropdown
function loadProperties() {
    fetch('<?php echo base_url('properties/get_available_for_dropdown'); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('propertyId');
                select.innerHTML = '<option value="">Choose Property</option>';
                data.properties.forEach(property => {
                    select.innerHTML += `<option value="${property.id}">${property.garden_name} - ${property.property_type}</option>`;
                });
            }
        })
        .catch(error => console.error('Error loading properties:', error));
}

// Show create association modal
function showCreateAssociationModal() {
    document.getElementById('createAssociationForm').reset();
    document.querySelector('input[name="registration_date"]').value = new Date().toISOString().split('T')[0];
    $('#createAssociationModal').modal('show');
}

// Create new association
function createAssociation() {
    const formData = new FormData(document.getElementById('createAssociationForm'));
    
    fetch('<?php echo base_url('registrations/store'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            $('#createAssociationModal').modal('hide');
            showAlert('success', 'Association created successfully!');
            loadAssociations();
        } else {
            showAlert('error', data.message || 'Error creating association');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Error creating association');
    });
}

// Edit association
function editAssociation(id) {
    // Load association details and show edit modal
    fetch(`<?php echo base_url('registrations/get/'); ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const association = data.association;
                document.getElementById('editAssociationId').value = association.id;
                document.getElementById('editCustomerName').value = association.customer_name;
                document.getElementById('editPropertyName').value = association.property_name;
                document.getElementById('editRegistrationDate').value = association.registration_date;
                document.getElementById('editTotalAmount').value = association.total_amount;
                document.getElementById('editPaidAmount').value = association.paid_amount || 0;
                document.getElementById('editStatus').value = association.status;
                document.getElementById('editAgreementPath').value = association.agreement_path || '';
                
                $('#editAssociationModal').modal('show');
            } else {
                showAlert('error', 'Error loading association details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error loading association details');
        });
}

// Update association
function updateAssociation() {
    const formData = new FormData(document.getElementById('editAssociationForm'));
    const id = document.getElementById('editAssociationId').value;
    
    fetch(`<?php echo base_url('registrations/update/'); ?>${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            $('#editAssociationModal').modal('hide');
            showAlert('success', 'Association updated successfully!');
            loadAssociations();
        } else {
            showAlert('error', data.message || 'Error updating association');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Error updating association');
    });
}

// View association details
function viewAssociation(id) {
    window.location.href = `<?php echo base_url('registrations/view/'); ?>${id}`;
}

// Record payment
function recordPayment(id) {
    window.location.href = `<?php echo base_url('transactions/create?registration_id='); ?>${id}`;
}

// Delete association
function deleteAssociation(id) {
    if (confirm('Are you sure you want to delete this association? This action cannot be undone.')) {
        fetch(`<?php echo base_url('registrations/delete/'); ?>${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAlert('success', 'Association deleted successfully!');
                loadAssociations();
            } else {
                showAlert('error', data.message || 'Error deleting association');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error deleting association');
        });
    }
}

// Search associations
function searchAssociations() {
    const filters = {
        customer: document.getElementById('searchCustomer').value,
        property: document.getElementById('searchProperty').value,
        status: document.getElementById('filterStatus').value,
        property_type: document.getElementById('filterPropertyType').value
    };
    
    loadAssociations(filters);
}

// Clear search
function clearSearch() {
    document.getElementById('associationSearchForm').reset();
    loadAssociations();
}

// Refresh associations
function refreshAssociations() {
    loadAssociations();
}

// Export associations
function exportAssociations() {
    window.location.href = '<?php echo base_url('customers/export_associations'); ?>';
}

// Show alert function
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    // Insert alert at the top of the container
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<style>
.card-action {
    margin-left: auto;
}

.progress {
    background-color: #e9ecef;
}

.btn-group-vertical .btn {
    margin-bottom: 2px;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}
</style>