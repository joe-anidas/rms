<div class="container-fluid mt-3">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Garden Details</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fa fa-tree mr-2"></i>Garden Details
                </h4>
            </div>
        </div>
    </div>

    <?php if(isset($garden)): ?>
        <!-- Single Garden Details -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo htmlspecialchars($garden->garden_name); ?></h5>
                        <div class="card-action">
                            <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus mr-1"></i>Add New Plot
                            </a>
                            <a href="<?php echo base_url('plots/overview'); ?>" class="btn btn-secondary btn-sm">
                                <i class="fa fa-eye mr-1"></i>View All Plots
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Basic Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Garden Name:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->garden_name); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>District:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->district ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Taluk:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->taluk_name ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Village/Town:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->village_town_name ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Extension:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->total_extension ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Plots:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->total_plots ?: 'N/A'); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Registration Details</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Registration District:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->registration_district ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registration Sub-District:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->registration_sub_district ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Town/Village:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->town_village ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Revenue Taluk:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->revenue_taluk ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sub Registrar:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->sub_registrar ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created Date:</strong></td>
                                        <td><?php echo $garden->created_at ? date('d M Y', strtotime($garden->created_at)) : 'N/A'; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6 class="text-primary">Infrastructure Details</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>EB Line:</strong></td>
                                        <td>
                                            <?php if($garden->eb_line === 'yes'): ?>
                                                <span class="badge badge-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Not Available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tree Saplings:</strong></td>
                                        <td>
                                            <?php if($garden->tree_saplings === 'yes'): ?>
                                                <span class="badge badge-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Not Available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Water Tank:</strong></td>
                                        <td>
                                            <?php if($garden->water_tank === 'yes'): ?>
                                                <span class="badge badge-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Not Available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sale Extension:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->sale_extension ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Park Extension:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->park_extension ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Road Extension:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->road_extension ?: 'N/A'); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Financial Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Land Purchased (₹):</strong></td>
                                        <td><?php echo htmlspecialchars($garden->land_purchased_rs ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Land Unpurchased (₹):</strong></td>
                                        <td><?php echo htmlspecialchars($garden->land_unpurchased_rs ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Incentive Percentage:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->incentive_percentage ?: 'N/A'); ?>%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DTCP No:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->dtcp_no ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>RERA No:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->rera_no ?: 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Patta/Chitta No:</strong></td>
                                        <td><?php echo htmlspecialchars($garden->patta_chitta_no ?: 'N/A'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Garden Plots -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Plots in <?php echo htmlspecialchars($garden->garden_name); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($plots)): ?>
                            <div class="text-center py-5">
                                <i class="fa fa-map-marker fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No plots found in this garden</h5>
                                <p class="text-muted">Add plots to get started</p>
                                <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary">
                                    <i class="fa fa-plus mr-2"></i>Add Plot
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Plot No</th>
                                            <th>Extension</th>
                                            <th>Value</th>
                                            <th>Status</th>
                                            <th>Customer</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($plots as $plot): ?>
                                            <tr>
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
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-info" onclick="viewPlot(<?php echo $plot->id; ?>)">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
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

    <?php else: ?>
        <!-- All Gardens List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">All Gardens</h5>
                        <div class="card-action">
                            <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus mr-1"></i>Add New Garden
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(empty($gardens)): ?>
                            <div class="text-center py-5">
                                <i class="fa fa-tree fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No gardens found</h5>
                                <p class="text-muted">Add your first garden to get started</p>
                                <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary">
                                    <i class="fa fa-plus mr-2"></i>Add Garden
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach($gardens as $garden): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">
                                                    <i class="fa fa-tree mr-2"></i><?php echo htmlspecialchars($garden->garden_name); ?>
                                                </h6>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        <i class="fa fa-map-marker mr-1"></i>
                                                        <?php echo htmlspecialchars($garden->district ?: 'N/A'); ?>, 
                                                        <?php echo htmlspecialchars($garden->taluk_name ?: 'N/A'); ?>
                                                    </small>
                                                </p>
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <small class="text-muted">Total Plots</small>
                                                        <div class="font-weight-bold"><?php echo $garden->total_plots ?: 0; ?></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Extension</small>
                                                        <div class="font-weight-bold"><?php echo htmlspecialchars($garden->total_extension ?: 'N/A'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="<?php echo base_url('garden/details/' . $garden->id); ?>" class="btn btn-sm btn-primary btn-block">
                                                    <i class="fa fa-eye mr-1"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// View plot details
function viewPlot(plotId) {
    // Redirect to plot view page
    window.location.href = `<?php echo base_url('plots/view/'); ?>${plotId}`;
}

// Edit plot
function editPlot(plotId) {
    // Redirect to plot edit page
    window.location.href = `<?php echo base_url('garden_profile'); ?>?action=edit&plot_id=${plotId}`;
}
</script>
