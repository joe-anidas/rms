<?php if (!empty($report_data)): ?>
    <div class="summary-section">
        <div class="summary-title">Sales Summary</div>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Total Properties Sold:</span>
                <span class="summary-value"><?php echo count($report_data); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Sales Amount:</span>
                <span class="summary-value">₹ <?php 
                    $total_sales = 0;
                    foreach ($report_data as $sale) {
                        $total_sales += isset($sale->sale_amount) ? $sale->sale_amount : 0;
                    }
                    echo number_format($total_sales, 2);
                ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Average Sale Value:</span>
                <span class="summary-value">₹ <?php 
                    echo count($report_data) > 0 ? number_format($total_sales / count($report_data), 2) : '0.00';
                ?></span>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Sale Date</th>
                <th>Property/Garden</th>
                <th>Plot No</th>
                <th>Customer Name</th>
                <th>Customer Phone</th>
                <th>District</th>
                <th class="text-right">Sale Amount</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report_data as $sale): ?>
            <tr>
                <td><?php echo isset($sale->sale_date) ? date('d/m/Y', strtotime($sale->sale_date)) : 'N/A'; ?></td>
                <td><?php echo htmlspecialchars($sale->garden_name ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->plot_no ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->plot_buyer_name ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->phone_number_1 ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->district ?? 'N/A'); ?></td>
                <td class="text-right">₹ <?php echo number_format($sale->sale_amount ?? 0, 2); ?></td>
                <td class="text-center"><?php echo ucfirst($sale->status ?? 'N/A'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="6" class="text-right">Total:</td>
                <td class="text-right">₹ <?php echo number_format($total_sales, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <div class="summary-section">
        <p>No sales data found for the selected criteria.</p>
    </div>
<?php endif; ?>