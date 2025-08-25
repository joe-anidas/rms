<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Registration Management</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registrations</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('registrations/create'); ?>" class="btn btn-primary">
                        <i class="fa fa-plus"></i> New Registration
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="dashboard-grid dashboard-grid-lg-4 dashboard-grid-md-2 dashboard-grid-sm-2">
            <div class="metric-card metric-card-info">
                <div class="metric-value"><?php echo isset($statistics['total_registrations']) ? $statistics['total_registrations'] : 0; ?></div>
                <div class="metric-label">Total Registrations</div>
                <i class="fa fa-file-text-o" style="position: absolute; top: 1rem; right: 1rem; font-size: 2rem; opacity: 0.3;"></i>
            </div>
            <div class="metric-card metric-card-warning">
                <div class="metric-value"><?php echo isset($statistics['status_active']) ? $statistics['status_active'] : 0; ?></div>
                <div class="metric-label">Active Registrations</div>
                <i class="fa fa-clock-o" style="position: absolute; top: 1rem; right: 1rem; font-size: 2rem; opacity: 0.3;"></i>
            </div>
            <div class="metric-card metric-card-success">
                <div class="metric-value"><?php echo isset($statistics['status_completed']) ? $statistics['status_completed'] : 0; ?></div>
                <div class="metric-label">Completed</div>
                <i class="fa fa-check-circle" style="position: absolute; top: 1rem; right: 1rem; font-size: 2rem; opacity: 0.3;"></i>
            </div>
            <div class="metric-card metric-card-danger">
                <div class="metric-value">₹<?php echo isset($statistics['total_pending']) ? number_format($statistics['total_pending'], 0) : 0; ?></div>
                <div class="metric-label">Pending Amount</div>
                <i class="fa fa-money" style="position: absolute; top: 1rem; right: 1rem; font-size: 2rem; opacity: 0.3;"></i>
            </div>
        </div>

        <!-- Quick Stats Summary -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="modern-card bg-light">
                    <div class="modern-card-body p-3">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fa fa-file-text-o text-info mr-2"></i>
                                    <div>
                                        <div class="font-bold text-lg"><?php echo $total_count; ?></div>
                                        <div class="text-sm text-muted">Total Found</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fa fa-clock-o text-warning mr-2"></i>
                                    <div>
                                        <div class="font-bold text-lg"><?php echo isset($statistics['status_active']) ? $statistics['status_active'] : 0; ?></div>
                                        <div class="text-sm text-muted">Active</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fa fa-check-circle text-success mr-2"></i>
                                    <div>
                                        <div class="font-bold text-lg"><?php echo isset($statistics['status_completed']) ? $statistics['status_completed'] : 0; ?></div>
                                        <div class="text-sm text-muted">Completed</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fa fa-money text-danger mr-2"></i>
                                    <div>
                                        <div class="font-bold text-lg">₹<?php echo isset($statistics['total_pending']) ? number_format($statistics['total_pending'], 0) : 0; ?></div>
                                        <div class="text-sm text-muted">Pending</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-lg-12">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h6 class="modern-card-title"><i class="fa fa-filter"></i> Filters & Search</h6>
                    </div>
                    <div class="modern-card-body">
                        <form method="GET" action="<?php echo base_url('registrations'); ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="completed" <?php echo (isset($filters['status']) && $filters['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo (isset($filters['status']) && $filters['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="date_from" class="form-control" 
                                               value="<?php echo isset($filters['date_from']) ? $filters['date_from'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="date_to" class="form-control" 
                                               value="<?php echo isset($filters['date_to']) ? $filters['date_to'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Search</label>
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Registration number, property, customer..."
                                               value="<?php echo isset($filters['search']) ? $filters['search'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Filter
                                    </button>
                                    <a href="<?php echo base_url('registrations'); ?>" class="btn btn-secondary">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                    <a href="<?php echo base_url('registrations/export?' . http_build_query($filters)); ?>" class="btn btn-success">
                                        <i class="fa fa-download"></i> Export CSV
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h6 class="modern-card-title"><i class="fa fa-table"></i> Registrations List</h6>
                        <span class="modern-badge modern-badge-info"><?php echo $total_count; ?> total</span>
                    </div>
                    <div class="modern-card-body">
                        <?php if (!empty($registrations)): ?>
                            <div class="modern-table">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Registration #</th>
                                            <th>Property</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Paid</th>
                                            <th>Pending</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($registrations as $registration): ?>
                                            <?php 
                                            $pending_amount = $registration->total_amount - $registration->paid_amount;
                                            $status_class = '';
                                            switch($registration->status) {
                                                case 'active': $status_class = 'badge-warning'; break;
                                                case 'completed': $status_class = 'badge-success'; break;
                                                case 'cancelled': $status_class = 'badge-danger'; break;
                                                default: $status_class = 'badge-secondary';
                                            }
                                            ?>
                                            <tr class="registration-row">
                                                <td>
                                                    <strong><?php echo $registration->registration_number; ?></strong>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?php echo $registration->garden_name; ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php echo ucfirst($registration->property_type); ?> - 
                                                            <?php echo $registration->district; ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?php echo $registration->plot_buyer_name; ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?php echo $registration->phone_number_1; ?></small>
                                                    </div>
                                                </td>
                                                <td><?php echo date('d-m-Y', strtotime($registration->registration_date)); ?></td>
                                                <td>
                                                    <span class="badge status-badge <?php echo $status_class; ?>" title="Click to filter by this status">
                                                        <?php echo ucfirst($registration->status); ?>
                                                    </span>
                                                </td>
                                                <td>₹<?php echo number_format($registration->total_amount, 0); ?></td>
                                                <td>₹<?php echo number_format($registration->paid_amount, 0); ?></td>
                                                <td>
                                                    <span class="<?php echo $pending_amount > 0 ? 'text-danger' : 'text-success'; ?>">
                                                        ₹<?php echo number_format($pending_amount, 0); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="<?php echo base_url('registrations/view/' . $registration->id); ?>" 
                                                           class="modern-btn modern-btn-info btn-sm" title="View Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('registrations/edit/' . $registration->id); ?>" 
                                                           class="modern-btn modern-btn-warning btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <?php if (!empty($registration->agreement_path)): ?>
                                                            <a href="<?php echo base_url('registrations/download_agreement/' . $registration->id); ?>" 
                                                               class="modern-btn modern-btn-success btn-sm" title="Download Agreement">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($registration->status == 'active' && $pending_amount > 0): ?>
                                                            <a href="<?php echo base_url('transactions/create?registration_id=' . $registration->id); ?>" 
                                                               class="modern-btn modern-btn-primary btn-sm" title="Record Payment">
                                                                <i class="fa fa-plus"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($total_pages > 1): ?>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <?php if ($current_page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo base_url('registrations?' . http_build_query(array_merge($filters, ['page' => $current_page - 1]))); ?>">
                                                    Previous
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="<?php echo base_url('registrations?' . http_build_query(array_merge($filters, ['page' => $i]))); ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($current_page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo base_url('registrations?' . http_build_query(array_merge($filters, ['page' => $current_page + 1]))); ?>">
                                                    Next
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fa fa-file-text-o fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No registrations found</h5>
                                <p class="text-muted">Try adjusting your filters or create a new registration.</p>
                                <a href="<?php echo base_url('registrations/create'); ?>" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Create New Registration
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Enhanced CSS for Registration Management -->
<style>
.btn-sm.modern-btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.2;
    border-radius: 0.375rem;
}

.modern-btn-info {
    background-color: var(--info-600, #0284c7);
    border-color: var(--info-600, #0284c7);
    color: white;
}

.modern-btn-info:hover {
    background-color: var(--info-700, #0369a1);
    border-color: var(--info-700, #0369a1);
    color: white;
}

.modern-btn-warning {
    background-color: var(--warning-600, #d97706);
    border-color: var(--warning-600, #d97706);
    color: white;
}

.modern-btn-warning:hover {
    background-color: var(--warning-700, #b45309);
    border-color: var(--warning-700, #b45309);
    color: white;
}

.registration-row:hover {
    background-color: var(--bg-secondary, #f8fafc);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

.status-badge {
    cursor: pointer;
    transition: all 0.2s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}

.quick-actions {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.registration-row:hover .quick-actions {
    opacity: 1;
}
</style>

<!-- Enhanced JavaScript for Registration List -->
<script>
$(document).ready(function() {
    // Auto-submit search form with debounce
    let searchTimeout;
    $('input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val();
        
        searchTimeout = setTimeout(function() {
            if (searchValue.length >= 3 || searchValue.length === 0) {
                // Auto-submit form for search
                $('form').submit();
            }
        }, 500);
    });
    
    // Quick filter buttons
    $('.quick-filter').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        $('select[name="status"]').val(status);
        $('form').submit();
    });
    
    // Bulk actions (if needed in future)
    $('.select-all').on('change', function() {
        $('.registration-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkActions();
    });
    
    $('.registration-checkbox').on('change', function() {
        updateBulkActions();
    });
    
    function updateBulkActions() {
        const checkedCount = $('.registration-checkbox:checked').length;
        if (checkedCount > 0) {
            $('.bulk-actions').show();
            $('.bulk-count').text(checkedCount);
        } else {
            $('.bulk-actions').hide();
        }
    }
    
    // Enhanced tooltips for action buttons
    $('[title]').tooltip();
    
    // Confirm delete actions
    $('.delete-registration').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this registration? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
    
    // Status badge click to filter
    $('.badge').on('click', function() {
        const status = $(this).text().toLowerCase();
        $('select[name="status"]').val(status);
        $('form').submit();
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