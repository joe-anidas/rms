<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">
          <i class="zmdi zmdi-chart"></i> Performance Dashboard
        </h4>
        <div class="card-actions">
          <button class="btn btn-sm btn-primary" onclick="refreshMetrics()">
            <i class="zmdi zmdi-refresh"></i> Refresh
          </button>
        </div>
      </div>
      <div class="card-body">
        
        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <!-- Performance Metrics Cards -->
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6 class="card-title">Memory Usage</h6>
                    <h4 id="memory-usage"><?php echo number_format($metrics['memory_usage'] / 1024 / 1024, 2); ?> MB</h4>
                  </div>
                  <div class="align-self-center">
                    <i class="zmdi zmdi-memory zmdi-3x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6 class="card-title">Execution Time</h6>
                    <h4 id="execution-time"><?php echo number_format($metrics['execution_time'] * 1000, 2); ?> ms</h4>
                  </div>
                  <div class="align-self-center">
                    <i class="zmdi zmdi-time zmdi-3x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-3">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6 class="card-title">DB Queries</h6>
                    <h4 id="db-queries"><?php echo $metrics['database_queries']; ?></h4>
                  </div>
                  <div class="align-self-center">
                    <i class="zmdi zmdi-storage zmdi-3x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-3">
            <div class="card bg-info text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6 class="card-title">Included Files</h6>
                    <h4 id="included-files"><?php echo $metrics['included_files']; ?></h4>
                  </div>
                  <div class="align-self-center">
                    <i class="zmdi zmdi-file zmdi-3x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cache Management -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Cache Management</h5>
              </div>
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Total Cache Keys:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-primary"><?php echo $cache_info['total_keys']; ?></span>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Cache Size:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-info"><?php echo number_format($cache_info['size'] / 1024, 2); ?> KB</span>
                  </div>
                </div>
                
                <div class="btn-group w-100" role="group">
                  <a href="<?php echo base_url('performance/clear_cache'); ?>" class="btn btn-danger btn-sm">
                    <i class="zmdi zmdi-delete"></i> Clear Cache
                  </a>
                  <a href="<?php echo base_url('performance/warm_cache'); ?>" class="btn btn-success btn-sm">
                    <i class="zmdi zmdi-fire"></i> Warm Cache
                  </a>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Asset Optimization</h5>
              </div>
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Environment:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-<?php echo ENVIRONMENT === 'production' ? 'success' : 'warning'; ?>">
                      <?php echo strtoupper(ENVIRONMENT); ?>
                    </span>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Minification:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-<?php echo ENVIRONMENT === 'production' ? 'success' : 'secondary'; ?>">
                      <?php echo ENVIRONMENT === 'production' ? 'Enabled' : 'Disabled'; ?>
                    </span>
                  </div>
                </div>
                
                <div class="btn-group w-100" role="group">
                  <a href="<?php echo base_url('performance/clean_assets'); ?>" class="btn btn-warning btn-sm">
                    <i class="zmdi zmdi-cleaning-services"></i> Clean Assets
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Image Optimization -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Image Optimization</h5>
              </div>
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Optimized Images:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-primary"><?php echo $optimization_stats['total_optimized']; ?></span>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Total Size:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-info"><?php echo number_format($optimization_stats['total_optimized_size'] / 1024 / 1024, 2); ?> MB</span>
                  </div>
                </div>
                
                <div class="btn-group w-100" role="group">
                  <a href="<?php echo base_url('performance/batch_optimize_images'); ?>" class="btn btn-primary btn-sm">
                    <i class="zmdi zmdi-image"></i> Batch Optimize
                  </a>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cleanImagesModal">
                    <i class="zmdi zmdi-delete"></i> Clean Old
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Database Optimization</h5>
              </div>
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Query Cache:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-success">Enabled</span>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <strong>Indexes:</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="badge bg-success">Optimized</span>
                  </div>
                </div>
                
                <div class="btn-group w-100" role="group">
                  <a href="<?php echo base_url('performance/optimize_database'); ?>" class="btn btn-success btn-sm">
                    <i class="zmdi zmdi-storage"></i> Optimize DB
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Speed Test -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Speed Test</h5>
              </div>
              <div class="card-body">
                <form id="speed-test-form" action="<?php echo base_url('performance/test_speed'); ?>" method="post">
                  <div class="row">
                    <div class="col-md-8">
                      <input type="url" class="form-control" name="url" placeholder="Enter URL to test" value="<?php echo base_url(); ?>">
                    </div>
                    <div class="col-md-4">
                      <button type="submit" class="btn btn-primary w-100">
                        <i class="zmdi zmdi-play"></i> Run Speed Test
                      </button>
                    </div>
                  </div>
                </form>
                
                <div id="speed-test-results" class="mt-3" style="display: none;">
                  <!-- Speed test results will be displayed here -->
                </div>
                
                <?php if ($this->session->flashdata('speed_test_result')): ?>
                  <?php $result = $this->session->flashdata('speed_test_result'); ?>
                  <div class="mt-3">
                    <h6>Speed Test Results:</h6>
                    <div class="row">
                      <div class="col-md-3">
                        <strong>Total Time:</strong> <?php echo number_format($result['total_time'] * 1000, 2); ?> ms
                      </div>
                      <div class="col-md-3">
                        <strong>HTTP Code:</strong> <?php echo $result['http_code']; ?>
                      </div>
                      <div class="col-md-3">
                        <strong>Size:</strong> <?php echo number_format($result['size_download'] / 1024, 2); ?> KB
                      </div>
                      <div class="col-md-3">
                        <strong>Status:</strong> 
                        <span class="badge bg-<?php echo $result['success'] ? 'success' : 'danger'; ?>">
                          <?php echo $result['success'] ? 'Success' : 'Failed'; ?>
                        </span>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Performance Actions</h5>
              </div>
              <div class="card-body">
                <div class="btn-group" role="group">
                  <a href="<?php echo base_url('performance/generate_report'); ?>" class="btn btn-info">
                    <i class="zmdi zmdi-file-text"></i> Generate Report
                  </a>
                  <button class="btn btn-secondary" onclick="exportMetrics()">
                    <i class="zmdi zmdi-download"></i> Export Metrics
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Clean Images Modal -->
<div class="modal fade" id="cleanImagesModal" tabindex="-1" aria-labelledby="cleanImagesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cleanImagesModalLabel">Clean Old Images</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo base_url('performance/clean_images'); ?>" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label for="days_old" class="form-label">Delete images older than (days):</label>
            <input type="number" class="form-control" id="days_old" name="days_old" value="30" min="1" max="365">
          </div>
          <div class="alert alert-warning">
            <i class="zmdi zmdi-alert-triangle"></i>
            This action will permanently delete old optimized images and thumbnails. Original images will not be affected.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Clean Images</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function refreshMetrics() {
  $.ajax({
    url: '<?php echo base_url("performance/get_metrics"); ?>',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        $('#memory-usage').text((response.metrics.memory_usage / 1024 / 1024).toFixed(2) + ' MB');
        $('#execution-time').text((response.metrics.execution_time * 1000).toFixed(2) + ' ms');
        $('#db-queries').text(response.metrics.database_queries);
        $('#included-files').text(response.metrics.included_files);
        
        // Show success message
        showAlert('Metrics refreshed successfully', 'success');
      } else {
        showAlert('Failed to refresh metrics', 'danger');
      }
    },
    error: function() {
      showAlert('Error refreshing metrics', 'danger');
    }
  });
}

function exportMetrics() {
  $.ajax({
    url: '<?php echo base_url("performance/get_metrics"); ?>',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        const dataStr = JSON.stringify(response, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        const url = URL.createObjectURL(dataBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'performance-metrics-' + new Date().toISOString().slice(0, 10) + '.json';
        link.click();
        URL.revokeObjectURL(url);
      }
    }
  });
}

function showAlert(message, type) {
  const alertHtml = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  `;
  $('.card-body').prepend(alertHtml);
  
  // Auto-dismiss after 3 seconds
  setTimeout(function() {
    $('.alert').fadeOut();
  }, 3000);
}

// Speed test form submission
$('#speed-test-form').on('submit', function(e) {
  e.preventDefault();
  
  const form = $(this);
  const button = form.find('button[type="submit"]');
  const originalText = button.html();
  
  button.html('<i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Testing...').prop('disabled', true);
  
  $.ajax({
    url: form.attr('action'),
    type: 'POST',
    data: form.serialize(),
    dataType: 'json',
    success: function(response) {
      const resultsDiv = $('#speed-test-results');
      const statusClass = response.success ? 'success' : 'danger';
      
      const resultsHtml = `
        <h6>Speed Test Results:</h6>
        <div class="row">
          <div class="col-md-3">
            <strong>Total Time:</strong> ${(response.total_time * 1000).toFixed(2)} ms
          </div>
          <div class="col-md-3">
            <strong>HTTP Code:</strong> ${response.http_code}
          </div>
          <div class="col-md-3">
            <strong>Size:</strong> ${(response.size_download / 1024).toFixed(2)} KB
          </div>
          <div class="col-md-3">
            <strong>Status:</strong> 
            <span class="badge bg-${statusClass}">
              ${response.success ? 'Success' : 'Failed'}
            </span>
          </div>
        </div>
      `;
      
      resultsDiv.html(resultsHtml).show();
    },
    error: function() {
      showAlert('Error running speed test', 'danger');
    },
    complete: function() {
      button.html(originalText).prop('disabled', false);
    }
  });
});

// Auto-refresh metrics every 30 seconds
setInterval(refreshMetrics, 30000);
</script>