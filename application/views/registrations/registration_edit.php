<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Edit Registration</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('registrations'); ?>">Registrations</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('registrations/view/' . $registration->id); ?>"><?php echo $registration->registration_number; ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('registrations/view/' . $registration->id); ?>" class="btn btn-info">
                        <i class="fa fa-eye"></i> View Details
                    </a>
                    <a href="<?php echo base_url('registrations'); ?>" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Registration Edit Form -->
        <div class="row">
            <div class="col-lg-12">
                <div class="modern-card modern-card-elevated">
                    <div class="modern-card-header">
                        <h5 class="modern-card-title"><i class="fa fa-edit"></i> Edit Registration - <?php echo $registration->registration_number; ?></h5>
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
                        <?php if ($registration->status == 'cancelled'): ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i> 
                                <strong>Warning:</strong> This registration has been cancelled. Some fields may not be editable.
                            </div>
                        <?php endif; ?>

                        <?php echo form_open_multipart('registrations/update/' . $registration->id, ['class' => 'needs-validation', 'novalidate' => '']); ?>
                            
                            <!-- Current Property and Customer Info -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <h6><i class="fa fa-home"></i> Current Property</h6>
                                        <strong><?php echo $registration->garden_name; ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo ucfirst($registration->property_type); ?> - 
                                            <?php echo $registration->district; ?>, <?php echo $registration->taluk_name; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-success">
                                        <h6><i class="fa fa-user"></i> Current Customer</h6>
                                        <strong><?php echo $registration->plot_buyer_name; ?></strong><br>
                                        <small class="text-muted"><?php echo $registration->phone_number_1; ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Registration Date -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="registration_date">Registration Date <span class="text-danger">*</span></label>
                                        <input type="date" name="registration_date" id="registration_date" 
                                               class="form-control" 
                                               value="<?php echo $registration->registration_date; ?>" 
                                               <?php echo ($registration->status == 'cancelled') ? 'readonly' : ''; ?> required>
                                        <div class="invalid-feedback">Please provide a registration date.</div>
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount (₹) <span class="text-danger">*</span></label>
                                        <input type="number" name="total_amount" id="total_amount" 
                                               class="form-control" step="0.01" min="0" 
                                               value="<?php echo $registration->total_amount; ?>"
                                               <?php echo ($registration->status == 'cancelled') ? 'readonly' : ''; ?> required>
                                        <div class="invalid-feedback">Please provide a valid total amount.</div>
                                    </div>
                                </div>

                                <!-- Paid Amount -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="paid_amount">Paid Amount (₹)</label>
                                        <input type="number" name="paid_amount" id="paid_amount" 
                                               class="form-control" step="0.01" min="0" 
                                               value="<?php echo $registration->paid_amount; ?>"
                                               <?php echo ($registration->status == 'cancelled') ? 'readonly' : ''; ?>>
                                        <small class="form-text text-muted">
                                            Current pending: ₹<?php echo number_format($registration->total_amount - $registration->paid_amount, 2); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Progress Display -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Payment Progress</label>
                                        <?php 
                                        $progress = $registration->total_amount > 0 ? 
                                                  ($registration->paid_amount / $registration->total_amount) * 100 : 0;
                                        ?>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?php echo $progress; ?>%" 
                                                 aria-valuenow="<?php echo $progress; ?>" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?php echo number_format($progress, 1); ?>% 
                                                (₹<?php echo number_format($registration->paid_amount, 0); ?> / ₹<?php echo number_format($registration->total_amount, 0); ?>)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Agreement Document -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="agreement_document">Agreement Document</label>
                                        
                                        <?php if (!empty($registration->agreement_path)): ?>
                                            <div class="alert alert-info">
                                                <i class="fa fa-file-pdf-o"></i> 
                                                Current document available
                                                <a href="<?php echo base_url('registrations/download_agreement/' . $registration->id); ?>" 
                                                   class="btn btn-sm btn-success float-right">
                                                    <i class="fa fa-download"></i> Download Current
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <input type="file" name="agreement_document" id="agreement_document" 
                                               class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                               <?php echo ($registration->status == 'cancelled') ? 'disabled' : ''; ?>>
                                        <small class="form-text text-muted">
                                            Upload new document to replace existing one. Allowed formats: PDF, DOC, DOCX, JPG, PNG. Maximum size: 5MB
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Registration Information -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Registration Information</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Registration Number:</strong><br>
                                                    <span class="text-muted"><?php echo $registration->registration_number; ?></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Current Status:</strong><br>
                                                    <span class="badge <?php echo $status_class; ?>">
                                                        <?php echo ucfirst($registration->status); ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Created:</strong><br>
                                                    <span class="text-muted"><?php echo date('d-m-Y H:i', strtotime($registration->created_at)); ?></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Last Updated:</strong><br>
                                                    <span class="text-muted"><?php echo date('d-m-Y H:i', strtotime($registration->updated_at)); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <?php if ($registration->status != 'cancelled'): ?>
                                        <button type="submit" class="modern-btn modern-btn-primary">
                                            <i class="fa fa-save"></i> Update Registration
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?php echo base_url('registrations/view/' . $registration->id); ?>" class="modern-btn modern-btn-info">
                                        <i class="fa fa-eye"></i> View Details
                                    </a>
                                    <a href="<?php echo base_url('registrations'); ?>" class="modern-btn modern-btn-secondary">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript for form interactions -->
<script>
$(document).ready(function() {
    // Update payment progress when amounts change
    function updatePaymentProgress() {
        var totalAmount = parseFloat($('#total_amount').val()) || 0;
        var paidAmount = parseFloat($('#paid_amount').val()) || 0;
        
        if (totalAmount > 0) {
            var progress = (paidAmount / totalAmount) * 100;
            var pendingAmount = totalAmount - paidAmount;
            
            $('.progress-bar').css('width', progress + '%');
            $('.progress-bar').attr('aria-valuenow', progress);
            $('.progress-bar').text(progress.toFixed(1) + '% (₹' + 
                                  new Intl.NumberFormat('en-IN').format(paidAmount) + ' / ₹' + 
                                  new Intl.NumberFormat('en-IN').format(totalAmount) + ')');
            
            // Update pending amount display
            $('#paid_amount').next('.form-text').html(
                'Current pending: ₹' + new Intl.NumberFormat('en-IN', {minimumFractionDigits: 2}).format(pendingAmount)
            );
        }
    }
    
    $('#total_amount, #paid_amount').on('input', updatePaymentProgress);
    
    // Validate paid amount doesn't exceed total amount
    $('#paid_amount').on('input', function() {
        var totalAmount = parseFloat($('#total_amount').val()) || 0;
        var paidAmount = parseFloat($(this).val()) || 0;
        
        if (paidAmount > totalAmount) {
            $(this).addClass('is-invalid');
            $(this).next('.form-text').addClass('text-danger').html(
                'Paid amount cannot exceed total amount!'
            );
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.form-text').removeClass('text-danger');
        }
    });
    
    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    var totalAmount = parseFloat($('#total_amount').val()) || 0;
                    var paidAmount = parseFloat($('#paid_amount').val()) || 0;
                    
                    if (paidAmount > totalAmount) {
                        event.preventDefault();
                        event.stopPropagation();
                        toastr.error('Paid amount cannot exceed total amount!');
                        return false;
                    }
                    
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    
    // File upload validation
    $('#agreement_document').change(function() {
        var file = this.files[0];
        if (file) {
            var fileSize = file.size / 1024 / 1024; // Convert to MB
            var allowedTypes = ['application/pdf', 'application/msword', 
                              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                              'image/jpeg', 'image/jpg', 'image/png'];
            
            if (fileSize > 5) {
                alert('File size must be less than 5MB');
                $(this).val('');
                return false;
            }
            
            if (allowedTypes.indexOf(file.type) === -1) {
                alert('Invalid file type. Please select PDF, DOC, DOCX, JPG, or PNG file.');
                $(this).val('');
                return false;
            }
        }
    });
});
</script>

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