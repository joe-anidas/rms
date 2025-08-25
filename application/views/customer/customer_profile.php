<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <!-- Customer Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-user mr-2"></i>Customer Profile - <?php echo htmlspecialchars($customer->plot_buyer_name); ?>
                    </h5>
                    <div class="card-action">
                        <a href="<?php echo base_url('customers'); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left mr-1"></i>Back to List
                        </a>
                        <a href="<?php echo base_url('customers/edit/' . $customer->id); ?>" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit mr-1"></i>Edit Customer
                        </a>
                        <button class="btn btn-info btn-sm" onclick="printProfile()">
                            <i class="fa fa-print mr-1"></i>Print Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information Cards -->
    <div class="row mb-4">
        <!-- Personal Information -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fa fa-user mr-2"></i>Personal Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Customer ID:</strong></td>
                            <td><span class="badge badge-primary">#<?php echo $customer->id; ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td><?php echo htmlspecialchars($customer->plot_buyer_name); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Father's Name:</strong></td>
                            <td><?php echo htmlspecialchars($customer->father_name ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><?php echo htmlspecialchars($customer->email_address ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Occupation:</strong></td>
                            <td><?php echo htmlspecialchars($customer->occupation ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Annual Income:</strong></td>
                            <td><?php echo $customer->annual_income ? '₹' . number_format($customer->annual_income, 2) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <?php 
                                $status = $customer->customer_status ?: 'active';
                                $status_class = '';
                                switch($status) {
                                    case 'active': $status_class = 'success'; break;
                                    case 'inactive': $status_class = 'warning'; break;
                                    case 'blacklisted': $status_class = 'danger'; break;
                                    default: $status_class = 'secondary';
                                }
                                ?>
                                <span class="badge badge-<?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Reference Source:</strong></td>
                            <td><?php echo htmlspecialchars($customer->reference_source ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Joined Date:</strong></td>
                            <td><?php echo date('d M Y', strtotime($customer->created_at)); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fa fa-phone mr-2"></i>Contact Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Primary Phone:</strong></td>
                            <td>
                                <?php if($customer->phone_number_1): ?>
                                    <a href="tel:<?php echo $customer->phone_number_1; ?>" class="text-success">
                                        <i class="fa fa-phone"></i> <?php echo htmlspecialchars($customer->phone_number_1); ?>
                                    </a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Secondary Phone:</strong></td>
                            <td>
                                <?php if($customer->phone_number_2): ?>
                                    <a href="tel:<?php echo $customer->phone_number_2; ?>" class="text-info">
                                        <i class="fa fa-phone"></i> <?php echo htmlspecialchars($customer->phone_number_2); ?>
                                    </a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Emergency Contact:</strong></td>
                            <td><?php echo htmlspecialchars($customer->emergency_contact_name ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Emergency Phone:</strong></td>
                            <td>
                                <?php if($customer->emergency_contact_phone): ?>
                                    <a href="tel:<?php echo $customer->emergency_contact_phone; ?>" class="text-warning">
                                        <i class="fa fa-phone"></i> <?php echo htmlspecialchars($customer->emergency_contact_phone); ?>
                                    </a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Relation:</strong></td>
                            <td><?php echo htmlspecialchars($customer->emergency_contact_relation ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>District:</strong></td>
                            <td><?php echo htmlspecialchars($customer->district ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Taluk:</strong></td>
                            <td><?php echo htmlspecialchars($customer->taluk_name ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Village/Town:</strong></td>
                            <td><?php echo htmlspecialchars($customer->village_town_name ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Pincode:</strong></td>
                            <td><?php echo htmlspecialchars($customer->pincode ?: 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Address & ID Information -->
    <div class="row mb-4">
        <!-- Address Information -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fa fa-map-marker mr-2"></i>Address Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Street Address:</strong></td>
                            <td><?php echo htmlspecialchars($customer->street_address ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Alternate Address:</strong></td>
                            <td><?php echo htmlspecialchars($customer->alternate_address ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Complete Address:</strong></td>
                            <td>
                                <?php 
                                $address_parts = array_filter([
                                    $customer->street_address,
                                    $customer->village_town_name,
                                    $customer->taluk_name,
                                    $customer->district,
                                    $customer->pincode
                                ]);
                                echo !empty($address_parts) ? implode(', ', $address_parts) : 'N/A';
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- ID & Financial Information -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fa fa-id-card mr-2"></i>ID & Financial Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID Proof Type:</strong></td>
                            <td><?php echo htmlspecialchars($customer->id_proof ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Aadhar Number:</strong></td>
                            <td><?php echo $customer->aadhar_number ? 'XXXX-XXXX-' . substr($customer->aadhar_number, -4) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>PAN Number:</strong></td>
                            <td><?php echo $customer->pan_number ? substr($customer->pan_number, 0, 3) . 'XXXXX' . substr($customer->pan_number, -2) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Bank Name:</strong></td>
                            <td><?php echo htmlspecialchars($customer->bank_name ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Account Number:</strong></td>
                            <td><?php echo $customer->bank_account_number ? 'XXXXXX' . substr($customer->bank_account_number, -4) : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>IFSC Code:</strong></td>
                            <td><?php echo htmlspecialchars($customer->ifsc_code ?: 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Investment Summary -->
    <?php if(isset($customer->registration_summary)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fa fa-chart-bar mr-2"></i>Investment Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo $customer->registration_summary->total_registrations ?: '0'; ?></h3>
                                    <p class="mb-0">Total Properties</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>₹<?php echo number_format($customer->registration_summary->total_investment ?: 0, 0); ?></h3>
                                    <p class="mb-0">Total Investment</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>₹<?php echo number_format($customer->registration_summary->total_paid_amount ?: 0, 0); ?></h3>
                                    <p class="mb-0">Amount Paid</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <?php $pending = ($customer->registration_summary->total_investment ?: 0) - ($customer->registration_summary->total_paid_amount ?: 0); ?>
                                    <h3>₹<?php echo number_format($pending, 0); ?></h3>
                                    <p class="mb-0">Pending Amount</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Properties & Transactions Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="properties-tab" data-toggle="tab" href="#properties" role="tab">
                                <i class="fa fa-home mr-2"></i>Properties (<?php echo count($customer->properties ?: []); ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab">
                                <i class="fa fa-money mr-2"></i>Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" role="tab">
                                <i class="fa fa-history mr-2"></i>Activity Log
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="customerTabsContent">
                        <!-- Properties Tab -->
                        <div class="tab-pane fade show active" id="properties" role="tabpanel">
                            <?php if(!empty($customer->properties)): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Property Details</th>
                                                <th>Registration</th>
                                                <th>Amount</th>
                                                <th>Payment Status</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($customer->properties as $property): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($property->garden_name); ?></strong><br>
                                                        <small class="text-muted">
                                                            <?php echo ucfirst($property->property_type); ?> - 
                                                            <?php echo $property->size_sqft; ?> sqft
                                                        </small><br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($property->district ?: ''); ?></small>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($property->registration_number); ?></strong><br>
                                                        <small class="text-muted"><?php echo date('d M Y', strtotime($property->registration_date)); ?></small>
                                                    </td>
                                                    <td>
                                                        <strong>₹<?php echo number_format($property->total_amount ?: 0, 2); ?></strong>
                                                    </td>
                                                    <td>
                                                        <div class="progress mb-1" style="height: 20px;">
                                                            <?php 
                                                            $paid_percentage = $property->total_amount > 0 ? (($property->paid_amount ?: 0) / $property->total_amount) * 100 : 0;
                                                            ?>
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $paid_percentage; ?>%">
                                                                <?php echo round($paid_percentage, 1); ?>%
                                                            </div>
                                                        </div>
                                                        <small class="text-success">Paid: ₹<?php echo number_format($property->paid_amount ?: 0, 2); ?></small><br>
                                                        <small class="text-warning">Pending: ₹<?php echo number_format(($property->total_amount ?: 0) - ($property->paid_amount ?: 0), 2); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-<?php echo $property->registration_status === 'active' ? 'success' : ($property->registration_status === 'completed' ? 'primary' : 'secondary'); ?>">
                                                            <?php echo ucfirst($property->registration_status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group-vertical btn-group-sm">
                                                            <button class="btn btn-info btn-sm" onclick="viewPropertyDetails(<?php echo $property->id; ?>)">
                                                                <i class="fa fa-eye"></i> View
                                                            </button>
                                                            <button class="btn btn-success btn-sm" onclick="recordPayment(<?php echo $property->registration_id ?? $property->id; ?>)">
                                                                <i class="fa fa-money"></i> Payment
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fa fa-home fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No properties found</h5>
                                    <p class="text-muted">This customer has not purchased any properties yet.</p>
                                    <a href="<?php echo base_url('registrations/create?customer_id=' . $customer->id); ?>" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>Register Property
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Transactions Tab -->
                        <div class="tab-pane fade" id="transactions" role="tabpanel">
                            <div id="transactionsContent">
                                <div class="text-center py-4">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Loading transactions...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Log Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div id="activityContent">
                                <div class="text-center py-4">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Loading activity log...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    <?php if(!empty($customer->notes)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fa fa-sticky-note mr-2"></i>Notes</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <?php echo nl2br(htmlspecialchars($customer->notes)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Load transactions when tab is clicked
document.getElementById('transactions-tab').addEventListener('click', function() {
    loadTransactions();
});

// Load activity log when tab is clicked
document.getElementById('activity-tab').addEventListener('click', function() {
    loadActivityLog();
});

// Load customer transactions
function loadTransactions() {
    const customerId = <?php echo $customer->id; ?>;
    
    fetch(`<?php echo base_url('customers/get_transactions/'); ?>${customerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('transactionsContent').innerHTML = generateTransactionsHTML(data.transactions);
            } else {
                document.getElementById('transactionsContent').innerHTML = '<div class="text-center py-4"><i class="fa fa-exclamation-triangle fa-2x text-warning"></i><p class="mt-2">Error loading transactions</p></div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('transactionsContent').innerHTML = '<div class="text-center py-4"><i class="fa fa-exclamation-triangle fa-2x text-danger"></i><p class="mt-2">Error loading transactions</p></div>';
        });
}

// Load activity log
function loadActivityLog() {
    const customerId = <?php echo $customer->id; ?>;
    
    // For now, show a placeholder
    document.getElementById('activityContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fa fa-info-circle fa-2x text-info"></i>
            <h5 class="mt-2">Activity Log</h5>
            <p class="text-muted">Activity logging feature will be implemented in the next version.</p>
        </div>
    `;
}

// Generate transactions HTML
function generateTransactionsHTML(transactions) {
    if (!transactions || transactions.length === 0) {
        return `
            <div class="text-center py-5">
                <i class="fa fa-money fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No transactions found</h5>
                <p class="text-muted">This customer has not made any payments yet.</p>
            </div>
        `;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Property</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Method</th>
                        <th>Receipt</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
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
                <td>
                    <button class="btn btn-sm btn-primary" onclick="viewReceipt('${transaction.receipt_number}')">
                        <i class="fa fa-file-pdf-o"></i> Receipt
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    return html;
}

// View property details
function viewPropertyDetails(propertyId) {
    window.location.href = `<?php echo base_url('properties/view/'); ?>${propertyId}`;
}

// Record payment
function recordPayment(registrationId) {
    window.location.href = `<?php echo base_url('transactions/create?registration_id='); ?>${registrationId}`;
}

// View receipt
function viewReceipt(receiptNumber) {
    if (receiptNumber && receiptNumber !== 'N/A') {
        window.open(`<?php echo base_url('transactions/receipt/'); ?>${receiptNumber}`, '_blank');
    } else {
        alert('Receipt not available');
    }
}

// Print profile
function printProfile() {
    window.print();
}

// Load transactions on page load if transactions tab is active
document.addEventListener('DOMContentLoaded', function() {
    // Auto-load transactions if there are properties
    <?php if(!empty($customer->properties)): ?>
    setTimeout(loadTransactions, 1000);
    <?php endif; ?>
});
</script>

<style>
@media print {
    .card-action, .btn, .nav-tabs {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .tab-content {
        display: block !important;
    }
    
    .tab-pane {
        display: block !important;
    }
}

.progress {
    background-color: #e9ecef;
}

.card-action {
    margin-left: auto;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}

.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}
</style>