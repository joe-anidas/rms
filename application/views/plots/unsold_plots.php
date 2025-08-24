<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa fa-times-circle mr-2"></i>Unsold Plots List</h5>
        <div class="card-action">
            <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus mr-1"></i>Add New Garden
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($unsold_plots)): ?>
            <div class="text-center py-5">
                <i class="fa fa-times-circle fa-3x text-warning mb-3"></i>
                <h5 class="text-muted">No unsold plots found</h5>
                <p class="text-muted">All plots have been sold or there are no plots added yet</p>
                <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>Add Garden
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Plot ID</th>
                            <th>Garden Name</th>
                            <th>Plot No</th>
                            <th>Plot Extension</th>
                            <th>Plot Value</th>
                            <th>Location</th>
                            <th>Added Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($unsold_plots as $plot): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-warning">#<?php echo $plot->id; ?></span>
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
                                    <?php 
                                    $location = array_filter([$plot->taluk_name, $plot->village_town_name]);
                                    echo htmlspecialchars(implode(', ', $location) ?: 'N/A');
                                    ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d M Y', strtotime($plot->created_at)); ?>
                                        <br><?php echo date('h:i A', strtotime($plot->created_at)); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-warning">Unsold</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewUnsoldPlot(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" onclick="markAsSold(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editUnsoldPlot(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteUnsoldPlot(<?php echo $plot->id; ?>)">
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
                            Showing <strong><?php echo count($unsold_plots); ?></strong> unsold plot(s)
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success btn-sm" onclick="exportUnsoldPlots()">
                            <i class="fa fa-download mr-1"></i>Export Data
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Unsold Plot Details Modal -->
<div class="modal fade" id="unsoldPlotModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unsold Plot Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="unsoldPlotModalBody">
                <!-- Unsold plot details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="markAsSoldFromModal()">Mark as Sold</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Sold Modal -->
<div class="modal fade" id="markAsSoldModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Plot as Sold</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="markAsSoldForm">
                    <input type="hidden" id="plotIdToSell" name="plot_id">
                    <div class="form-group">
                        <label for="customerName">Customer Name</label>
                        <input type="text" class="form-control" id="customerName" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">Customer Phone</label>
                        <input type="text" class="form-control" id="customerPhone" name="customer_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="saleDate">Sale Date</label>
                        <input type="date" class="form-control" id="saleDate" name="sale_date" required>
                    </div>
                    <div class="form-group">
                        <label for="saleAmount">Sale Amount</label>
                        <input type="number" class="form-control" id="saleAmount" name="sale_amount" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmMarkAsSold()">Mark as Sold</button>
            </div>
        </div>
    </div>
</div>

<script>
// View unsold plot details
function viewUnsoldPlot(plotId) {
    fetch(`<?php echo base_url('get_unsold_plot/'); ?>${plotId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const plot = data.plot;
                document.getElementById('unsoldPlotModalBody').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Plot Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Plot No:</strong></td><td>${plot.plot_no}</td></tr>
                                <tr><td><strong>Garden Name:</strong></td><td>${plot.garden_name}</td></tr>
                                <tr><td><strong>Plot Extension:</strong></td><td>${plot.plot_extension} Sqft/Sqmt</td></tr>
                                <tr><td><strong>Plot Value:</strong></td><td>₹${plot.plot_value}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Location Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>District:</strong></td><td>${plot.district || 'N/A'}</td></tr>
                                <tr><td><strong>Taluk:</strong></td><td>${plot.taluk_name || 'N/A'}</td></tr>
                                <tr><td><strong>Village/Town:</strong></td><td>${plot.village_town_name || 'N/A'}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-warning">Unsold</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-primary">Boundary Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>North:</strong></td><td>${plot.north || 'N/A'}</td></tr>
                                <tr><td><strong>East:</strong></td><td>${plot.east || 'N/A'}</td></tr>
                                <tr><td><strong>West:</strong></td><td>${plot.west || 'N/A'}</td></tr>
                                <tr><td><strong>South:</strong></td><td>${plot.south || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Additional Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Added Date:</strong></td><td>${new Date(plot.created_at).toLocaleDateString()}</td></tr>
                                <tr><td><strong>Last Updated:</strong></td><td>${new Date(plot.updated_at).toLocaleDateString()}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                $('#unsoldPlotModal').modal('show');
            } else {
                alert('Error loading unsold plot details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading unsold plot details');
        });
}

// Mark plot as sold
function markAsSold(plotId) {
    document.getElementById('plotIdToSell').value = plotId;
    document.getElementById('saleDate').value = new Date().toISOString().split('T')[0];
    $('#markAsSoldModal').modal('show');
}

// Mark as sold from modal
function markAsSoldFromModal() {
    const plotId = document.getElementById('plotIdToSell').value;
    markAsSold(plotId);
}

// Confirm mark as sold
function confirmMarkAsSold() {
    const formData = new FormData(document.getElementById('markAsSoldForm'));
    
    fetch('<?php echo base_url('mark_plot_as_sold'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Plot marked as sold successfully!');
            $('#markAsSoldModal').modal('hide');
            location.reload();
        } else {
            alert('Error marking plot as sold: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error marking plot as sold');
    });
}

// Edit unsold plot (placeholder function)
function editUnsoldPlot(plotId) {
    alert('Edit functionality will be implemented in the next version');
}

// Delete unsold plot (placeholder function)
function deleteUnsoldPlot(plotId) {
    if (confirm('Are you sure you want to delete this unsold plot?')) {
        alert('Delete functionality will be implemented in the next version');
    }
}

// Export unsold plots data
function exportUnsoldPlots() {
    // Create CSV content
    const headers = ['Plot ID', 'Garden Name', 'Plot No', 'Plot Extension', 'Plot Value', 'District', 'Taluk', 'Village/Town', 'North', 'East', 'West', 'South', 'Added Date'];
    const csvContent = [
        headers.join(','),
        ...<?php echo json_encode(array_map(function($plot) {
            return [
                $plot->id,
                $plot->garden_name,
                $plot->plot_no,
                $plot->plot_extension,
                $plot->plot_value,
                $plot->district ?: '',
                $plot->taluk_name ?: '',
                $plot->village_town_name ?: '',
                $plot->north ?: '',
                $plot->east ?: '',
                $plot->west ?: '',
                $plot->south ?: '',
                $plot->created_at
            ];
        }, $unsold_plots ?? [])); ?>.map(row => row.map(field => `"${field}"`).join(','))
    ].join('\n');
    
    // Download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'unsold_plots_data.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
