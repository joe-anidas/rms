<div class="container-fluid mt-3">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Plots Overview</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fa fa-map-marker mr-2"></i>Plots Overview
                </h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="mb-1"><?php echo $statistics['total_plots'] ?? 0; ?></h4>
                            <p class="text-muted mb-0">Total Plots</p>
                        </div>
                        <div class="col-4 text-right">
                            <i class="fa fa-map-marker fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="mb-1"><?php echo $statistics['unsold_plots'] ?? 0; ?></h4>
                            <p class="text-muted mb-0">Unsold Plots</p>
                        </div>
                        <div class="col-4 text-right">
                            <i class="fa fa-times-circle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="mb-1"><?php echo $statistics['booked_plots'] ?? 0; ?></h4>
                            <p class="text-muted mb-0">Booked Plots</p>
                        </div>
                        <div class="col-4 text-right">
                            <i class="fa fa-calendar-check fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="mb-1"><?php echo $statistics['sold_plots'] ?? 0; ?></h4>
                            <p class="text-muted mb-0">Sold Plots</p>
                        </div>
                        <div class="col-4 text-right">
                            <i class="fa fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Value Statistics -->
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="mb-1">₹<?php echo number_format($statistics['total_value'] ?? 0); ?></h4>
                            <p class="text-muted mb-0">Total Plot Value</p>
                        </div>
                        <div class="col-4 text-right">
                            <i class="fa fa-money fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="mb-1">₹<?php echo number_format($statistics['sold_value'] ?? 0); ?></h4>
                            <p class="text-muted mb-0">Total Sales Value</p>
                        </div>
                        <div class="col-4 text-right">
                            <i class="fa fa-chart-line fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="unsold">Unsold</option>
                                <option value="booked">Booked</option>
                                <option value="sold">Sold</option>
                                <option value="registered">Registered</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="gardenFilter">
                                <option value="">All Gardens</option>
                                <?php if(isset($plots) && !empty($plots)): ?>
                                    <?php 
                                    $gardens = array_unique(array_column($plots, 'garden_name'));
                                    foreach($gardens as $garden): ?>
                                        <option value="<?php echo htmlspecialchars($garden); ?>"><?php echo htmlspecialchars($garden); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search plots...">
                        </div>
                        <div class="col-md-3 text-right">
                            <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary">
                                <i class="fa fa-plus mr-1"></i>Add New Plot
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plots Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Plots</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($plots)): ?>
                        <div class="text-center py-5">
                            <i class="fa fa-map-marker fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No plots found</h5>
                            <p class="text-muted">Add your first plot to get started</p>
                            <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add Plot
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="plotsTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Plot ID</th>
                                        <th>Garden Name</th>
                                        <th>Plot No</th>
                                        <th>Extension</th>
                                        <th>Value</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($plots as $plot): ?>
                                        <tr data-status="<?php echo $plot->status; ?>" data-garden="<?php echo htmlspecialchars($plot->garden_name); ?>">
                                            <td>
                                                <span class="badge badge-secondary">#<?php echo $plot->id; ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($plot->garden_name); ?></strong>
                                                <?php if($plot->district): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($plot->district); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-info"><?php echo htmlspecialchars($plot->plot_no); ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($plot->plot_extension); ?></strong>
                                                <br><small class="text-muted">Sqft/Sqmt</small>
                                            </td>
                                            <td>
                                                <strong class="text-primary">₹<?php echo number_format($plot->plot_value); ?></strong>
                                            </td>
                                            <td>
                                                <?php if($plot->customer_name): ?>
                                                    <strong><?php echo htmlspecialchars($plot->customer_name); ?></strong>
                                                    <?php if($plot->customer_phone): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($plot->customer_phone); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status_badges = [
                                                    'unsold' => 'badge-warning',
                                                    'booked' => 'badge-info',
                                                    'sold' => 'badge-success',
                                                    'registered' => 'badge-primary'
                                                ];
                                                $status_text = ucfirst($plot->status);
                                                $badge_class = $status_badges[$plot->status] ?? 'badge-secondary';
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $date_field = '';
                                                $date_value = '';
                                                switch($plot->status) {
                                                    case 'sold':
                                                        $date_field = 'Sale Date';
                                                        $date_value = $plot->sale_date;
                                                        break;
                                                    case 'booked':
                                                        $date_field = 'Booking Date';
                                                        $date_value = $plot->booking_date;
                                                        break;
                                                    default:
                                                        $date_field = 'Added Date';
                                                        $date_value = $plot->created_at;
                                                }
                                                ?>
                                                <small class="text-muted">
                                                    <?php echo $date_field; ?><br>
                                                    <?php echo $date_value ? date('d M Y', strtotime($date_value)) : 'N/A'; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-info" onclick="viewPlot(<?php echo $plot->id; ?>)">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <?php if($plot->status === 'unsold'): ?>
                                                        <button type="button" class="btn btn-sm btn-success" onclick="markAsSold(<?php echo $plot->id; ?>)">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-info" onclick="bookPlot(<?php echo $plot->id; ?>)">
                                                            <i class="fa fa-calendar-plus"></i>
                                                        </button>
                                                    <?php elseif($plot->status === 'booked'): ?>
                                                        <button type="button" class="btn btn-sm btn-success" onclick="convertToSale(<?php echo $plot->id; ?>)">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-warning" onclick="editPlot(<?php echo $plot->id; ?>)">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </div>
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
</div>

<!-- Plot Details Modal -->
<div class="modal fade" id="plotModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Plot Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="plotModalBody">
                <!-- Plot details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterPlots);
document.getElementById('gardenFilter').addEventListener('change', filterPlots);
document.getElementById('searchFilter').addEventListener('input', filterPlots);

function filterPlots() {
    const statusFilter = document.getElementById('statusFilter').value;
    const gardenFilter = document.getElementById('gardenFilter').value;
    const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
    
    const rows = document.querySelectorAll('#plotsTable tbody tr');
    
    rows.forEach(row => {
        let show = true;
        
        // Status filter
        if (statusFilter && row.dataset.status !== statusFilter) {
            show = false;
        }
        
        // Garden filter
        if (gardenFilter && row.dataset.garden !== gardenFilter) {
            show = false;
        }
        
        // Search filter
        if (searchFilter) {
            const text = row.textContent.toLowerCase();
            if (!text.includes(searchFilter)) {
                show = false;
            }
        }
        
        row.style.display = show ? '' : 'none';
    });
}

// View plot details
function viewPlot(plotId) {
    // This would typically fetch plot details from the server
    // For now, we'll show a simple message
    document.getElementById('plotModalBody').innerHTML = `
        <div class="text-center">
            <i class="fa fa-spinner fa-spin fa-2x mb-3"></i>
            <p>Loading plot details...</p>
        </div>
    `;
    $('#plotModal').modal('show');
    
    // In a real implementation, you would fetch the data here
    // fetch(`/plots/view/${plotId}`)
    //     .then(response => response.json())
    //     .then(data => {
    //         // Populate modal with plot data
    //     });
}

// Mark plot as sold
function markAsSold(plotId) {
    if (confirm('Are you sure you want to mark this plot as sold?')) {
        // Redirect to the plot sale form or show a modal
        window.location.href = `<?php echo base_url('plots/unsold'); ?>?action=mark_sold&plot_id=${plotId}`;
    }
}

// Book plot
function bookPlot(plotId) {
    if (confirm('Are you sure you want to book this plot?')) {
        // Redirect to the plot booking form or show a modal
        window.location.href = `<?php echo base_url('plots/unsold'); ?>?action=book&plot_id=${plotId}`;
    }
}

// Convert booking to sale
function convertToSale(plotId) {
    if (confirm('Are you sure you want to convert this booking to a sale?')) {
        // Redirect to the conversion form or show a modal
        window.location.href = `<?php echo base_url('plots/booked'); ?>?action=convert&plot_id=${plotId}`;
    }
}

// Edit plot
function editPlot(plotId) {
    // Redirect to edit form
    window.location.href = `<?php echo base_url('garden_profile'); ?>?action=edit&plot_id=${plotId}`;
}

// Initialize DataTable for better functionality
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#plotsTable').DataTable({
            pageLength: 25,
            order: [[0, 'desc']],
            responsive: true
        });
    }
});
</script>
