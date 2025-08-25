<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($report_type); ?> Report - Print View</title>
    <style>
        /* Print-friendly styles */
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        
        .report-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .summary-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        
        .summary-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        
        .summary-label {
            font-weight: bold;
        }
        
        .summary-value {
            text-align: right;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 12px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .print-controls {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .filters-section {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        .filters-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button class="btn" onclick="window.print()">Print Report</button>
        <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
    </div>
    
    <div class="header">
        <div class="company-name">Real Estate Management System</div>
        <div class="report-title"><?php echo strtoupper($report_type); ?> REPORT</div>
        <div class="report-info">
            Generated on: <?php echo date('d/m/Y H:i:s'); ?>
        </div>
    </div>
    
    <?php if (!empty($filters) && (isset($filters['start_date']) || isset($filters['end_date']))): ?>
    <div class="filters-section">
        <div class="filters-title">Report Filters:</div>
        <?php if (!empty($filters['start_date'])): ?>
            <strong>From:</strong> <?php echo date('d/m/Y', strtotime($filters['start_date'])); ?>
        <?php endif; ?>
        <?php if (!empty($filters['end_date'])): ?>
            <strong>To:</strong> <?php echo date('d/m/Y', strtotime($filters['end_date'])); ?>
        <?php endif; ?>
        <?php if (!empty($filters['garden_id'])): ?>
            <strong>Garden ID:</strong> <?php echo $filters['garden_id']; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php
    // Display report content based on type
    switch ($report_type) {
        case 'sales':
            $this->load->view('reports/print/sales_report', array('report_data' => $report_data));
            break;
        case 'financial':
            $this->load->view('reports/print/financial_report', array('report_data' => $report_data));
            break;
        case 'customer':
            $this->load->view('reports/print/customer_report', array('report_data' => $report_data));
            break;
        case 'property':
            $this->load->view('reports/print/property_report', array('report_data' => $report_data));
            break;
        case 'staff':
            $this->load->view('reports/print/staff_report', array('report_data' => $report_data));
            break;
        case 'transactions':
            $this->load->view('reports/print/transaction_report', array('report_data' => $report_data));
            break;
        default:
            echo '<div class="summary-section"><p>Report type not supported for print view.</p></div>';
    }
    ?>
    
    <div class="footer">
        <p>This report was generated automatically by the Real Estate Management System</p>
        <p>© <?php echo date('Y'); ?> Real Estate Management System. All rights reserved.</p>
    </div>
    
    <script>
        // Auto-print functionality
        function autoPrint() {
            if (window.location.search.includes('auto_print=1')) {
                window.print();
            }
        }
        
        // Call auto-print when page loads
        window.onload = autoPrint;
        
        // Print function
        function printReport() {
            window.print();
        }
        
        // Add keyboard shortcut for printing
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                printReport();
            }
        });
    </script>
</body>
</html>