<?php if (!empty($report_data)): ?>
    
    <?php if (isset($report_data['monthly_revenue']) && !empty($report_data['monthly_revenue'])): ?>
    <div class="summary-section">
        <div class="summary-title">Financial Summary</div>
        <div class="summary-grid">
            <?php 
            $total_revenue = 0;
            $total_transactions = 0;
            foreach ($report_data['monthly_revenue'] as $month) {
                $total_revenue += $month->monthly_revenue;
                $total_transactions += $month->transaction_count;
            }
            ?>
            <div class="summary-item">
                <span class="summary-label">Total Revenue:</span>
                <span class="summary-value">₹ <?php echo number_format($total_revenue, 2); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Transactions:</span>
                <span class="summary-value"><?php echo $total_transactions; ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Average Transaction:</span>
                <span class="summary-value">₹ <?php echo $total_transactions > 0 ? number_format($total_revenue / $total_transactions, 2) : '0.00'; ?></span>
            </div>
        </div>
    </div>
    
    <h3>Monthly Revenue Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-right">Revenue</th>
                <th class="text-center">Transaction Count</th>
                <th class="text-right">Average Transaction</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report_data['monthly_revenue'] as $month): ?>
            <tr>
                <td><?php echo htmlspecialchars($month->month); ?></td>
                <td class="text-right">₹ <?php echo number_format($month->monthly_revenue, 2); ?></td>
                <td class="text-center"><?php echo $month->transaction_count; ?></td>
                <td class="text-right">₹ <?php echo $month->transaction_count > 0 ? number_format($month->monthly_revenue / $month->transaction_count, 2) : '0.00'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td>Total:</td>
                <td class="text-right">₹ <?php echo number_format($total_revenue, 2); ?></td>
                <td class="text-center"><?php echo $total_transactions; ?></td>
                <td class="text-right">₹ <?php echo $total_transactions > 0 ? number_format($total_revenue / $total_transactions, 2) : '0.00'; ?></td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
    
    <?php if (isset($report_data['revenue_by_type']) && !empty($report_data['revenue_by_type'])): ?>
    <div class="page-break"></div>
    <h3>Revenue by Payment Type</h3>
    <table>
        <thead>
            <tr>
                <th>Payment Type</th>
                <th class="text-center">Transaction Count</th>
                <th class="text-right">Total Amount</th>
                <th class="text-right">Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_type_revenue = 0;
            foreach ($report_data['revenue_by_type'] as $type) {
                $total_type_revenue += $type->total_amount;
            }
            ?>
            <?php foreach ($report_data['revenue_by_type'] as $type): ?>
            <tr>
                <td><?php echo ucfirst(str_replace('_', ' ', $type->transaction_type)); ?></td>
                <td class="text-center"><?php echo $type->count; ?></td>
                <td class="text-right">₹ <?php echo number_format($type->total_amount, 2); ?></td>
                <td class="text-right"><?php echo $total_type_revenue > 0 ? number_format(($type->total_amount / $total_type_revenue) * 100, 1) : '0.0'; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    
    <?php if (isset($report_data['payment_methods']) && !empty($report_data['payment_methods'])): ?>
    <h3>Revenue by Payment Method</h3>
    <table>
        <thead>
            <tr>
                <th>Payment Method</th>
                <th class="text-center">Transaction Count</th>
                <th class="text-right">Total Amount</th>
                <th class="text-right">Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_method_revenue = 0;
            foreach ($report_data['payment_methods'] as $method) {
                $total_method_revenue += $method->total_amount;
            }
            ?>
            <?php foreach ($report_data['payment_methods'] as $method): ?>
            <tr>
                <td><?php echo ucfirst($method->payment_method); ?></td>
                <td class="text-center"><?php echo $method->count; ?></td>
                <td class="text-right">₹ <?php echo number_format($method->total_amount, 2); ?></td>
                <td class="text-right"><?php echo $total_method_revenue > 0 ? number_format(($method->total_amount / $total_method_revenue) * 100, 1) : '0.0'; ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    
<?php else: ?>
    <div class="summary-section">
        <p>No financial data found for the selected criteria.</p>
    </div>
<?php endif; ?>