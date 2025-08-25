<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Registration Details</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('registrations'); ?>">Registrations</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $registration->registration_number; ?></li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('registrations/edit/' . $registration->id); ?>" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="<?php echo base_url('registrations'); ?>" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Registration Overview -->
        <div class="row">
            <div class="col-lg-8">
                <div class="modern-card modern-card-elevated">
                    <div class="modern-card-header">
                        <h6 class="modern-card-title"><i class="fa fa-file-text"></i> Registration Information</h6>
                        <span class="float-right">
                            <?php 
                            $status_class = '';
                            switch($registration->status) {
                                case 'active': $status_class = 'badge-warning'; break;
                                case 'completed': $status_class = 'badge-success'; break;
                                case 'cancelled': $status_class = 'badge-danger'; break;
                                default: $status_class = 'badge-secondary';
                            }
                            ?>
                            <span class="modern-badge modern-badge-<?php echo str_replace('badge-', '', $status_class); ?>">
                                <?php echo ucfirst($registration->status); ?>
                            </span>
                        </span>
                    </div>
                    <div class="modern-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Registration Number:</strong></td>
                                        <td><?php echo $registration->registration_number; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registration Date:</strong></td>
                                        <td><?php echo date('d-m-Y', strtotime($registration->registration_date)); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo ucfirst($registration->status); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($registration->created_at)); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Total Amount:</strong></td>
                                        <td>₹<?php echo number_format($registration->total_amount, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Paid Amount:</strong></td>
                                        <td class="text-success">₹<?php echo number_format($registration->paid_amount, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pending Amount:</strong></td>
                                        <td class="<?php echo ($registration->total_amount - $registration->paid_amount) > 0 ? 'text-danger' : 'text-success'; ?>">
                                            ₹<?php echo number_format($registration->total_amount - $registration->paid_amount, 2); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Progress:</strong></td>
                                        <td>
                                            <?php 
                                            $progress = $registration->total_amount > 0 ? 
                                                      ($registration->paid_amount / $registration->total_amount) * 100 : 0;
                                            ?>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: <?php echo $progress; ?>%" 
                                                     aria-valuenow="<?php echo $progress; ?>" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    <?php echo number_format($progress, 1); ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Registration Status Workflow -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h6 class="font-semibold mb-3">Registration Workflow</h6>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="text-center">
                                        <div class="rounded-full bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa fa-check"></i>
                                        </div>
                                        <div class="text-xs mt-2 font-medium">Created</div>
                                    </div>
                                    <div class="flex-grow-1 mx-3">
                                        <div class="border-top border-2 <?php echo in_array($registration->status, ['active', 'completed']) ? 'border-success' : 'border-secondary'; ?>"></div>
                                    </div>
                                    <div class="text-center">
                                        <div class="rounded-full <?php echo $registration->status == 'active' ? 'bg-warning' : ($registration->status == 'completed' ? 'bg-success' : 'bg-secondary'); ?> text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa <?php echo $registration->status == 'active' ? 'fa-clock-o' : ($registration->status == 'completed' ? 'fa-check' : 'fa-times'); ?>"></i>
                                        </div>
                                        <div class="text-xs mt-2 font-medium">Active</div>
                                    </div>
                                    <div class="flex-grow-1 mx-3">
                                        <div class="border-top border-2 <?php echo $registration->status == 'completed' ? 'border-success' : 'border-secondary'; ?>"></div>
                                    </div>
                                    <div class="text-center">
                                        <div class="rounded-full <?php echo $registration->status == 'completed' ? 'bg-success' : 'bg-secondary'; ?> text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa fa-flag-checkered"></i>
                                        </div>
                                        <div class="text-xs mt-2 font-medium">Completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agreement Document -->
                        <?php if (!empty($registration->agreement_path)): ?>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-file-pdf-o"></i> 
                                        <strong>Agreement Document Available</strong>
                                        <a href="<?php echo base_url('registrations/download_agreement/' . $registration->id); ?>" 
                                           class="modern-btn modern-btn-success btn-sm float-right">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Status Update Panel -->
            <div class="col-lg-4">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h6 class="modern-card-title"><i class="fa fa-cog"></i> Status Management</h6>
                    </div>
                    <div class="modern-card-body">
                        <?php echo form_open('registrations/update_status/' . $registration->id); ?>
                            <div class="form-group">
                                <label for="status">Update Status</label>
                                <select name="status" id="status" class="form-control">
                                    <?php if ($registration->status == 'active'): ?>
                                        <option value="completed">Mark as Completed</option>
                                        <option value="cancelled">Cancel Registration</option>
                                    <?php elseif ($registration->status == 'completed'): ?>
                                        <option value="cancelled">Cancel Registration</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <?php if ($registration->status != 'cancelled'): ?>
                                <button type="submit" class="modern-btn modern-btn-primary w-100" 
                                        onclick="return confirm('Are you sure you want to update the status?')">
                                    <i class="fa fa-save"></i> Update Status
                                </button>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i> 
                                    This registration has been cancelled and cannot be modified.
                                </div>
                            <?php endif; ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Details -->
        <div class="row">
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h6 class="modern-card-title"><i class="fa fa-home"></i> Property Details</h6>
                    </div>
                    <div class="modern-card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Property Name:</strong></td>
                                <td><?php echo $registration->garden_name; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td><?php echo ucfirst($registration->property_type); ?></td>
                            </tr>
                            <tr>
                                <td><strong>District:</strong></td>
                                <td><?php echo $registration->district; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Taluk:</strong></td>
                                <td><?php echo $registration->taluk_name; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Village/Town:</strong></td>
                                <td><?php echo $registration->village_town_name; ?></td>
                            </tr>
                            <?php if ($registration->size_sqft): ?>
                                <tr>
                                    <td><strong>Size:</strong></td>
                                    <td><?php echo number_format($registration->size_sqft, 2); ?> sq ft</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><strong>Property Price:</strong></td>
                                <td>₹<?php echo number_format($registration->property_price, 2); ?></td>
                            </tr>
                        </table>
                        
                        <?php if ($registration->property_description): ?>
                            <div class="mt-3">
                                <strong>Description:</strong>
                                <p class="text-muted"><?php echo nl2br($registration->property_description); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h6 class="modern-card-title"><i class="fa fa-user"></i> Customer Details</h6>
                    </div>
                    <div class="modern-card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td><?php echo $registration->plot_buyer_name; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Father's Name:</strong></td>
                                <td><?php echo $registration->father_name; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phone 1:</strong></td>
                                <td><?php echo $registration->phone_number_1; ?></td>
                            </tr>
                            <?php if ($registration->phone_number_2): ?>
                                <tr>
                                    <td><strong>Phone 2:</strong></td>
                                    <td><?php echo $registration->phone_number_2; ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><strong>District:</strong></td>
                                <td><?php echo $registration->customer_district; ?></td>
                            </tr>
                            <?php if ($registration->aadhar_number): ?>
                                <tr>
                                    <td><strong>Aadhar Number:</strong></td>
                                    <td><?php echo $registration->aadhar_number; ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        
                        <?php if ($registration->street_address): ?>
                            <div class="mt-3">
                                <strong>Address:</strong>
                                <p class="text-muted"><?php echo nl2br($registration->street_address); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <?php if (!empty($transactions)): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h6 class="modern-card-title"><i class="fa fa-money"></i> Transaction History</h6>
                        </div>
                        <div class="modern-card-body">
                            <div class="modern-table">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Receipt #</th>
                                            <th>Amount</th>
                                            <th>Payment Type</th>
                                            <th>Payment Method</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($transaction->payment_date)); ?></td>
                                                <td><?php echo $transaction->receipt_number; ?></td>
                                                <td>₹<?php echo number_format($transaction->amount, 2); ?></td>
                                                <td><?php echo ucfirst(str_replace('_', ' ', $transaction->payment_type)); ?></td>
                                                <td><?php echo ucfirst(str_replace('_', ' ', $transaction->payment_method)); ?></td>
                                                <td><?php echo $transaction->notes; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')): ?>
    <script>
        $(document).ready(function() {
            toastr.success('<?php echo $this->session->flashdata('success'); ?>');
        });
    </script>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <script>
        $(document).ready(function() {
            toastr.error('<?php echo $this->session->flashdata('error'); ?>');
        });
    </script>
<?php endif; ?>