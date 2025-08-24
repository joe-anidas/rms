<div class="row">
  <!-- Key Metrics Cards -->
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body text-center">
        <i class="fa fa-home fa-3x mb-3"></i>
        <h4><?php echo isset($summary['total_properties']) ? $summary['total_properties'] : '0'; ?></h4>
        <p>Total Properties</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card bg-success text-white">
      <div class="card-body text-center">
        <i class="fa fa-check-circle fa-3x mb-3"></i>
        <h4><?php echo isset($summary['sold_properties']) ? $summary['sold_properties'] : '0'; ?></h4>
        <p>Sold Properties</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card bg-info text-white">
      <div class="card-body text-center">
        <i class="fa fa-bookmark fa-3x mb-3"></i>
        <h4><?php echo isset($summary['booked_properties']) ? $summary['booked_properties'] : '0'; ?></h4>
        <p>Booked Properties</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card bg-warning text-white">
      <div class="card-body text-center">
        <i class="fa fa-clock-o fa-3x mb-3"></i>
        <h4><?php echo isset($summary['unsold_properties']) ? $summary['unsold_properties'] : '0'; ?></h4>
        <p>Unsold Properties</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <!-- Financial Overview -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Financial Overview</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="text-center">
              <h3 class="text-success">₹<?php echo isset($summary['total_sales']) ? number_format($summary['total_sales'], 2) : '0.00'; ?></h3>
              <p class="text-muted">Total Sales Revenue</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="text-center">
              <h3 class="text-warning">₹<?php echo isset($summary['pending_revenue']) ? number_format($summary['pending_revenue'], 2) : '0.00'; ?></h3>
              <p class="text-muted">Pending Revenue</p>
            </div>
          </div>
        </div>
        
        <div class="progress mt-3">
          <?php 
          $total_value = isset($summary['total_property_value']) ? $summary['total_property_value'] : 0;
          $sold_value = isset($summary['total_sales']) ? $summary['total_sales'] : 0;
          $percentage = $total_value > 0 ? ($sold_value / $total_value) * 100 : 0;
          ?>
          <div class="progress-bar bg-success" style="width: <?php echo $percentage; ?>%">
            <?php echo round($percentage, 1); ?>%
          </div>
        </div>
        <small class="text-muted">Sales Progress</small>
      </div>
    </div>
  </div>
  
  <!-- Customer & Staff Summary -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Customer & Staff Summary</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="text-center">
              <h3 class="text-info"><?php echo isset($summary['total_customers']) ? $summary['total_customers'] : '0'; ?></h3>
              <p class="text-muted">Total Customers</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="text-center">
              <h3 class="text-primary"><?php echo isset($summary['total_staff']) ? $summary['total_staff'] : '0'; ?></h3>
              <p class="text-muted">Total Staff</p>
            </div>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-md-6">
            <div class="text-center">
              <h5 class="text-success"><?php echo isset($summary['active_customers']) ? $summary['active_customers'] : '0'; ?></h5>
              <p class="text-muted">Active Customers</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="text-center">
              <h5 class="text-success"><?php echo isset($summary['active_staff']) ? $summary['active_staff'] : '0'; ?></h5>
              <p class="text-muted">Active Staff</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <!-- Property Status Chart -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Property Status Distribution</h5>
      </div>
      <div class="card-body">
        <canvas id="propertyStatusChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
  
  <!-- Quick Actions -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Quick Actions</h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="<?php echo base_url('transactions/record_payment'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Record Payment
          </a>
          <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-success btn-sm">
            <i class="fa fa-home"></i> Add Property
          </a>
          <a href="<?php echo base_url('customer_details'); ?>" class="btn btn-info btn-sm">
            <i class="fa fa-user"></i> Add Customer
          </a>
          <a href="<?php echo base_url('staff_details'); ?>" class="btn btn-warning btn-sm">
            <i class="fa fa-users"></i> Add Staff
          </a>
          <a href="<?php echo base_url('reports/sales_report'); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-chart-bar"></i> View Reports
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <!-- Recent Activities -->
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Recent Activities</h5>
        <div class="card-tools">
          <a href="<?php echo base_url('transactions'); ?>" class="btn btn-primary btn-sm">View All</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Activity</th>
                <th>Property</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="recentActivitiesTable">
              <tr>
                <td colspan="6" class="text-center">Loading recent activities...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/plugins/Chart.js/Chart.min.js'); ?>"></script>
<script>
$(document).ready(function() {
    // Load recent activities
    loadRecentActivities();
    
    // Initialize property status chart
    initializePropertyChart();
});

function loadRecentActivities() {
    $.ajax({
        url: '<?php echo base_url("transactions/get_recent_transactions"); ?>',
        type: 'GET',
        data: { limit: 10 },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.data) {
                displayRecentActivities(response.data);
            } else {
                $('#recentActivitiesTable').html('<tr><td colspan="6" class="text-center">No recent activities found</td></tr>');
            }
        },
        error: function() {
            $('#recentActivitiesTable').html('<tr><td colspan="6" class="text-center">Failed to load recent activities</td></tr>');
        }
    });
}

function displayRecentActivities(activities) {
    const tbody = $('#recentActivitiesTable');
    tbody.empty();
    
    if (activities.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center">No recent activities found</td></tr>');
        return;
    }
    
    activities.forEach(activity => {
        const row = `
            <tr>
                <td>${activity.payment_date || 'N/A'}</td>
                <td>
                    <span class="badge badge-${getActivityBadgeClass(activity.transaction_type)}">
                        ${activity.transaction_type || 'N/A'}
                    </span>
                </td>
                <td>${activity.garden_name || 'N/A'} - Plot ${activity.plot_no || 'N/A'}</td>
                <td>${activity.customer_name || 'N/A'}</td>
                <td>₹${activity.amount || '0'}</td>
                <td>
                    <span class="badge badge-${getStatusBadgeClass(activity.status)}">
                        ${activity.status || 'N/A'}
                    </span>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function getActivityBadgeClass(type) {
    switch (type) {
        case 'advance': return 'primary';
        case 'installment': return 'info';
        case 'full_payment': return 'success';
        case 'refund': return 'warning';
        default: return 'secondary';
    }
}

function getStatusBadgeClass(status) {
    switch (status) {
        case 'completed': return 'success';
        case 'pending': return 'warning';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}

function initializePropertyChart() {
    const ctx = document.getElementById('propertyStatusChart').getContext('2d');
    
    const data = {
        labels: ['Sold', 'Booked', 'Unsold'],
        datasets: [{
            data: [
                <?php echo isset($summary['sold_properties']) ? $summary['sold_properties'] : '0'; ?>,
                <?php echo isset($summary['booked_properties']) ? $summary['booked_properties'] : '0'; ?>,
                <?php echo isset($summary['unsold_properties']) ? $summary['unsold_properties'] : '0'; ?>
            ],
            backgroundColor: [
                '#28a745', // Green for sold
                '#17a2b8', // Blue for booked
                '#ffc107'  // Yellow for unsold
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    };
    
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };
    
    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });
}
</script>
