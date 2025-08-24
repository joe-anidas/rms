<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa fa-check-circle mr-2"></i>Sold Plots List</h5>
        <div class="card-action">
            <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus mr-1"></i>Add New Garden
            </a>
            <a href="<?php echo base_url('plots/overview'); ?>" class="btn btn-secondary btn-sm">
                <i class="fa fa-eye mr-1"></i>View All Plots
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($sold_plots)): ?>
            <div class="text-center py-5">
                <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-muted">No sold plots found</h5>
                <p class="text-muted">Sold plots will appear here once they are purchased by customers</p>
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
                            <th>Customer Name</th>
                            <th>Sale Date</th>
                            <th>Sale Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sold_plots as $plot): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-success">#<?php echo $plot->id; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($plot->garden_name); ?></strong>
                                    <?php if($plot->district): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($plot->district); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-success"><?php echo htmlspecialchars($plot->plot_no); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($plot->plot_extension); ?></strong>
                                    <br><small class="text-muted">Sqft/Sqmt</small>
                                </td>
                                <td>
                                    <strong class="text-success">₹<?php echo number_format($plot->plot_value); ?></strong>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($plot->customer_name); ?></strong>
                                    <?php if($plot->customer_phone): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($plot->customer_phone); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d M Y', strtotime($plot->sale_date)); ?>
                                        <br><?php echo date('h:i A', strtotime($plot->sale_date)); ?>
                                    </small>
                                </td>
                                <td>
                                    <strong class="text-success">₹<?php echo number_format($plot->sale_amount); ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-success">Sold</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewSoldPlot(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editSoldPlot(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="printSaleReceipt(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-print"></i>
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
                            Showing <strong><?php echo count($sold_plots); ?></strong> sold plot(s)
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success btn-sm" onclick="exportSoldPlots()">
                            <i class="fa fa-download mr-1"></i>Export Data
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Sold Plot Details Modal -->
<div class="modal fade" id="soldPlotModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sold Plot Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="soldPlotModalBody">
                <!-- Sold plot details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// View sold plot details
function viewSoldPlot(plotId) {
    fetch(`<?php echo base_url('get_sold_plot/'); ?>${plotId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const plot = data.plot;
                document.getElementById('soldPlotModalBody').innerHTML = `
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
                            <h6 class="text-primary">Customer Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Customer Name:</strong></td><td>${plot.customer_name}</td></tr>
                                <tr><td><strong>Phone:</strong></td><td>${plot.customer_phone || 'N/A'}</td></tr>
                                <tr><td><strong>Sale Date:</strong></td><td>${new Date(plot.sale_date).toLocaleDateString()}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-success">Sold</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-primary">Sale Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Sale Amount:</strong></td><td>₹${plot.sale_amount}</td></tr>
                                <tr><td><strong>Payment Method:</strong></td><td>${plot.payment_method || 'N/A'}</td></tr>
                                <tr><td><strong>Sale Reference:</strong></td><td>${plot.sale_reference || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Location Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>District:</strong></td><td>${plot.district || 'N/A'}</td></tr>
                                <tr><td><strong>Taluk:</strong></td><td>${plot.taluk_name || 'N/A'}</td></tr>
                                <tr><td><strong>Village/Town:</strong></td><td>${plot.village_town_name || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                $('#soldPlotModal').modal('show');
            } else {
                alert('Error loading sold plot details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading sold plot details');
        });
}

// Edit sold plot (placeholder function)
function editSoldPlot(plotId) {
    alert('Edit functionality will be implemented in the next version');
}

// Print sale receipt (placeholder function)
function printSaleReceipt(plotId) {
    alert('Print functionality will be implemented in the next version');
}

// Export sold plots data
function exportSoldPlots() {
    // Create CSV content
    const headers = ['Plot ID', 'Garden Name', 'Plot No', 'Plot Extension', 'Plot Value', 'Customer Name', 'Customer Phone', 'Sale Date', 'Sale Amount', 'Payment Method', 'District', 'Taluk', 'Village/Town'];
    const csvContent = [
        headers.join(','),
        ...<?php echo json_encode(array_map(function($plot) {
            return [
                $plot->id,
                $plot->garden_name,
                $plot->plot_no,
                $plot->plot_extension,
                $plot->plot_value,
                $plot->customer_name,
                $plot->customer_phone ?: '',
                $plot->sale_date,
                $plot->sale_amount,
                $plot->payment_method ?: '',
                $plot->district ?: '',
                $plot->taluk_name ?: '',
                $plot->village_town_name ?: ''
            ];
        }, $sold_plots ?? [])); ?>.map(row => row.map(field => `"${field}"`).join(','))
    ].join('\n');
    
    // Download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sold_plots_data.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
