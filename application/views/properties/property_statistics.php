<div class="content-wrapper">
    <div class="container-fluid">
        
        <!-- Breadcrumb -->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Property Statistics</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('properties'); ?>">Properties</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Statistics</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url('properties'); ?>" class="btn btn-outline-secondary waves-effect waves-light">
                        <i class="fa fa-arrow-left"></i> Back to Properties
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="widgets-icons bg-light-primary text-primary mr-3">
                                <i class="icon-home"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 font-weight-bold"><?php echo isset($statistics['total_properties']) ? $statistics['total_properties'] : 0; ?></h4>
                                <p class="mb-0 text-secondary">Total Properties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="widgets-icons bg-light-success text-success mr-3">
                                <i class="icon-check"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 font-weight-bold"><?php echo isset($statistics['status_sold']) ? $statistics['status_sold'] : 0; ?></h4>
                                <p class="mb-0 text-secondary">Sold Properties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="widgets-icons bg-light-warning text-warning mr-3">
                                <i class="icon-book-open"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 font-weight-bold"><?php echo isset($statistics['status_booked']) ? $statistics['status_booked'] : 0; ?></h4>
                                <p class="mb-0 text-secondary">Booked Properties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="widgets-icons bg-light-info text-info mr-3">
                                <i class="icon-clock"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 font-weight-bold"><?php echo isset($statistics['status_unsold']) ? $statistics['status_unsold'] : 0; ?></h4>
                                <p class="mb-0 text-secondary">Unsold Properties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Statistics -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Financial Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center border-right">
                                    <h3 class="text-success mb-1">
                                        ₹<?php echo isset($statistics['total_value']) ? number_format($statistics['total_value'], 0) : '0'; ?>
                                    </h3>
                                    <p class="mb-0 text-muted">Total Property Value</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-primary mb-1">
                                        ₹<?php echo isset($statistics['sold_value']) ? number_format($statistics['sold_value'], 0) : '0'; ?>
                                    </h3>
                                    <p class="mb-0 text-muted">Sold Property Value</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <h4 class="text-info mb-1">
                                        ₹<?php echo isset($statistics['average_price']) ? number_format($statistics['average_price'], 0) : '0'; ?>
                                    </h4>
                                    <p class="mb-0 text-muted">Average Property Price</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Staff Assignment</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center border-right">
                                    <h3 class="text-success mb-1">
                                        <?php echo isset($statistics['assigned_properties']) ? $statistics['assigned_properties'] : 0; ?>
                                    </h3>
                                    <p class="mb-0 text-muted">Assigned Properties</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-warning mb-1">
                                        <?php echo isset($statistics['unassigned_properties']) ? $statistics['unassigned_properties'] : 0; ?>
                                    </h3>
                                    <p class="mb-0 text-muted">Unassigned Properties</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="progress">
                            <?php 
                            $total = isset($statistics['total_properties']) ? $statistics['total_properties'] : 1;
                            $assigned = isset($statistics['assigned_properties']) ? $statistics['assigned_properties'] : 0;
                            $assignment_percentage = ($total > 0) ? ($assigned / $total) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?php echo $assignment_percentage; ?>%" 
                                 aria-valuenow="<?php echo $assignment_percentage; ?>" 
                                 aria-valuemin="0" aria-valuemax="100">
                                <?php echo round($assignment_percentage, 1); ?>% Assigned
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Type Distribution -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Property Type Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><i class="fa fa-circle text-success"></i> Gardens</td>
                                        <td class="text-right">
                                            <strong><?php echo isset($statistics['type_garden']) ? $statistics['type_garden'] : 0; ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-circle text-info"></i> Plots</td>
                                        <td class="text-right">
                                            <strong><?php echo isset($statistics['type_plot']) ? $statistics['type_plot'] : 0; ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-circle text-warning"></i> Houses</td>
                                        <td class="text-right">
                                            <strong><?php echo isset($statistics['type_house']) ? $statistics['type_house'] : 0; ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-circle text-primary"></i> Flats</td>
                                        <td class="text-right">
                                            <strong><?php echo isset($statistics['type_flat']) ? $statistics['type_flat'] : 0; ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><i class="fa fa-circle text-success"></i> Sold</td>
                                        <td class="text-right">
                                            <strong><?php echo isset($statistics['status_sold']) ? $statistics['status_sold'] : 0; ?></strong>
                                        </td>
                                        <td class="text-right">
                                            <?php 
                                            $total = isset($statistics['total_properties']) ? $statistics['total_properties'] : 1;
                                            $sold = isset($statistics['status_sold']) ? $statistics['status_sold'] : 0;
                                            $sold_percentage = ($total > 0) ? ($sold / $total) * 100 : 0;
                                            echo round($sold_percentage, 1) . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-circle text-warning"></i> Booked</td>
                                        <td class="text-right">
                                            <strong><?php echo isset($statistics['status_booked']) ? $statistics['status_booked'] : 0; ?></strong>
                                        </td>
                                        <td class="text-right">
                                            <?php 
                                            $booked = isset($statistics['status_booked']) ? $statistics['status_booked'] : 0;
                                            $booked_percentage = ($total > 0) ? ($booked / $total) * 100 : 0;
                                            echo round($booked_percentage, 1) . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-circle text-secondary"></i> Unsold</td>
                                        <td class="text-right">
                                            <strong><?php echo isset($statistics['status_unsold']) ? $statistics['status_unsold'] : 0; ?></strong>
                                        </td>
                                        <td class="text-right">
                                            <?php 
                                            $unsold = isset($statistics['status_unsold']) ? $statistics['status_unsold'] : 0;
                                            $unsold_percentage = ($total > 0) ? ($unsold / $total) * 100 : 0;
                                            echo round($unsold_percentage, 1) . '%';
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Trends -->
        <?php if(isset($statistics['monthly_trends']) && !empty($statistics['monthly_trends'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Property Creation Trends (Last 12 Months)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Properties Created</th>
                                        <th>Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($statistics['monthly_trends'] as $trend): ?>
                                        <tr>
                                            <td><?php echo date('F Y', strtotime($trend->month . '-01')); ?></td>
                                            <td><strong><?php echo $trend->count; ?></strong></td>
                                            <td>
                                                <div class="progress" style="height: 10px;">
                                                    <?php 
                                                    $max_count = max(array_column($statistics['monthly_trends'], 'count'));
                                                    $percentage = ($max_count > 0) ? ($trend->count / $max_count) * 100 : 0;
                                                    ?>
                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                         style="width: <?php echo $percentage; ?>%"></div>
                                                </div>
                                            </td>
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

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?php echo base_url('properties/create'); ?>" class="btn btn-primary btn-block">
                                    <i class="fa fa-plus"></i> Add New Property
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?php echo base_url('properties?status=unsold'); ?>" class="btn btn-warning btn-block">
                                    <i class="fa fa-clock"></i> View Unsold Properties
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?php echo base_url('properties/search'); ?>" class="btn btn-info btn-block">
                                    <i class="fa fa-search"></i> Advanced Search
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?php echo base_url('properties'); ?>" class="btn btn-secondary btn-block">
                                    <i class="fa fa-list"></i> All Properties
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-refresh statistics every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000); // 5 minutes
});
</script>