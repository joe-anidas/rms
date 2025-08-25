<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        Advanced Reports with Date Range Filtering
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Report Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form id="reportFiltersForm" method="GET" action="<?php echo base_url('reports/advanced_report'); ?>">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="report_type" class="form-label">Report Type</label>
                                        <select name="type" id="report_type" class="form-select">
                                            <option value="sales" <?php echo ($report_type == 'sales') ? 'selected' : ''; ?>>Sales Report</option>
                                            <option value="financial" <?php echo ($report_type == 'financial') ? 'selected' : ''; ?>>Financial Report</option>
                                            <option value="transactions" <?php echo ($report_type == 'transactions') ? 'selected' : ''; ?>>Transaction History</option>
                                            <option value="customer" <?php echo ($report_type == 'customer') ? 'selected' : ''; ?>>Customer Analytics</option>
                                            <option value="property" <?php echo ($report_type == 'property') ? 'selected' : ''; ?>>Property Performance</option>
                                            <option value="staff" <?php echo ($report_type == 'staff') ? 'selected' : ''; ?>>Staff Performance</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" 
                                               value="<?php echo isset($filters['start_date']) ? $filters['start_date'] : ''; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" 
                                               value="<?php echo isset($filters['end_date']) ? $filters['end_date'] : ''; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="garden_id" class="form-label">Garden/Property</label>
                                        <select name="garden_id" id="garden_id" class="form-select">
                                            <option value="">All Properties</option>
                                            <?php if (isset($gardens)): ?>
                                                <?php foreach ($gardens as $garden): ?>
                                                    <option value="<?php echo $garden->id; ?>" 
                                                            <?php echo (isset($filters['garden_id']) && $filters['garden_id'] == $garden->id) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($garden->garden_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="customer_id" class="form-label">Customer</label>
                                        <select name="customer_id" id="customer_id" class="form-select">
                                            <option value="">All Customers</option>
                                            <?php if (isset($customers)): ?>
                                                <?php foreach ($customers as $customer): ?>
                                                    <option value="<?php echo $customer->id; ?>" 
                                                            <?php echo (isset($filters['customer_id']) && $filters['customer_id'] == $customer->id) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($customer->plot_buyer_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Generate Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quick Date Range Buttons -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDateRange('today')">Today</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDateRange('week')">This Week</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDateRange('month')">This Month</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDateRange('quarter')">This Quarter</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDateRange('year')">This Year</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDateRange('last30')">Last 30 Days</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Report Summary -->
                    <?php if (isset($summary) && !empty($summary)): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle"></i> Report Summary</h5>
                                <div class="row">
                                    <?php foreach ($summary as $key => $value): ?>
                                        <?php if ($key !== 'error'): ?>
                                        <div class="col-md-3">
                                            <strong><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</strong>
                                            <?php if (strpos($key, 'amount') !== false || strpos($key, 'revenue') !== false || strpos($key, 'sales') !== false): ?>
                                                ₹ <?php echo number_format($value, 2); ?>
                                            <?php else: ?>
                                                <?php echo number_format($value); ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Export and Email Options -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group me-2" role="group">
                                    <button type="button" class="btn btn-success" onclick="exportReport('excel')">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="exportReport('pdf')">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="exportReport('csv')">
                                        <i class="fas fa-file-csv"></i> Export CSV
                                    </button>
                                </div>
                                <div class="btn-group me-2" role="group">
                                    <button type="button" class="btn btn-info" onclick="printReport()">
                                        <i class="fas fa-print"></i> Print Report
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="emailReport()">
                                        <i class="fas fa-envelope"></i> Email Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Report Data Display -->
                    <div class="row">
                        <div class="col-12">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Error generating report: <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php elseif (isset($report_data) && !empty($report_data)): ?>
                                <div class="table-responsive">
                                    <?php
                                    // Display different table structures based on report type
                                    switch ($report_type) {
                                        case 'sales':
                                            $this->load->view('reports/tables/sales_table', array('data' => $report_data));
                                            break;
                                        case 'financial':
                                            $this->load->view('reports/tables/financial_table', array('data' => $report_data));
                                            break;
                                        case 'transactions':
                                            $this->load->view('reports/tables/transaction_table', array('data' => $report_data));
                                            break;
                                        case 'customer':
                                            $this->load->view('reports/tables/customer_table', array('data' => $report_data));
                                            break;
                                        case 'property':
                                            $this->load->view('reports/tables/property_table', array('data' => $report_data));
                                            break;
                                        case 'staff':
                                            $this->load->view('reports/tables/staff_table', array('data' => $report_data));
                                            break;
                                        default:
                                            echo '<div class="alert alert-warning">Report type not supported.</div>';
                                    }
                                    ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle"></i>
                                    No data found for the selected criteria. Please adjust your filters and try again.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Report Modal -->
<div class="modal fade" id="emailReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="emailReportForm">
                    <div class="mb-3">
                        <label for="email_recipients" class="form-label">Recipients (comma-separated emails)</label>
                        <textarea class="form-control" id="email_recipients" name="recipients" rows="3" 
                                  placeholder="email1@example.com, email2@example.com"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="email_message" class="form-label">Additional Message (optional)</label>
                        <textarea class="form-control" id="email_message" name="message" rows="3" 
                                  placeholder="Enter any additional message to include with the report..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendEmailReport()">
                    <i class="fas fa-paper-plane"></i> Send Report
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Date range helper functions
function setDateRange(range) {
    const today = new Date();
    let startDate, endDate;
    
    switch (range) {
        case 'today':
            startDate = endDate = today;
            break;
        case 'week':
            startDate = new Date(today.setDate(today.getDate() - today.getDay()));
            endDate = new Date();
            break;
        case 'month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = new Date();
            break;
        case 'quarter':
            const quarter = Math.floor(today.getMonth() / 3);
            startDate = new Date(today.getFullYear(), quarter * 3, 1);
            endDate = new Date();
            break;
        case 'year':
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date();
            break;
        case 'last30':
            startDate = new Date(today.setDate(today.getDate() - 30));
            endDate = new Date();
            break;
    }
    
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
}

// Export functions
function exportReport(format) {
    const form = document.getElementById('reportFiltersForm');
    const formData = new FormData(form);
    
    // Add format parameter
    formData.append('format', format);
    
    // Create URL with parameters
    const params = new URLSearchParams(formData);
    const exportUrl = '<?php echo base_url("reports/export_report"); ?>?' + params.toString();
    
    // Open in new window for download
    window.open(exportUrl, '_blank');
}

function printReport() {
    const form = document.getElementById('reportFiltersForm');
    const formData = new FormData(form);
    
    // Add auto print parameter
    formData.append('auto_print', '1');
    
    // Create URL with parameters
    const params = new URLSearchParams(formData);
    const printUrl = '<?php echo base_url("reports/print_report"); ?>?' + params.toString();
    
    // Open in new window for printing
    window.open(printUrl, '_blank');
}

function emailReport() {
    $('#emailReportModal').modal('show');
}

function sendEmailReport() {
    const form = document.getElementById('reportFiltersForm');
    const emailForm = document.getElementById('emailReportForm');
    const formData = new FormData(form);
    const emailData = new FormData(emailForm);
    
    // Combine form data
    const postData = {
        report_type: formData.get('type'),
        start_date: formData.get('start_date'),
        end_date: formData.get('end_date'),
        garden_id: formData.get('garden_id'),
        customer_id: formData.get('customer_id'),
        recipients: emailData.get('recipients').split(',').map(email => email.trim()),
        message: emailData.get('message')
    };
    
    // Show loading
    const sendBtn = document.querySelector('#emailReportModal .btn-primary');
    const originalText = sendBtn.innerHTML;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    sendBtn.disabled = true;
    
    // Send AJAX request
    fetch('<?php echo base_url("reports/email_report"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(postData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Report sent successfully!');
            $('#emailReportModal').modal('hide');
        } else {
            alert('Error sending report: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending report. Please try again.');
    })
    .finally(() => {
        sendBtn.innerHTML = originalText;
        sendBtn.disabled = false;
    });
}

// Auto-submit form when report type changes
document.getElementById('report_type').addEventListener('change', function() {
    document.getElementById('reportFiltersForm').submit();
});
</script>