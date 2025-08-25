<?php if (!empty($report_data)): ?>
    <div class="summary-section">
        <div class="summary-title">Transaction Summary</div>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Total Transactions:</span>
                <span class="summary-value"><?php echo count($report_data); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Amount:</span>
                <span class="summary-value">₹ <?php 
                    $total_amount = 0;
                    foreach ($report_data as $transaction) {
                        $total_amount += isset($transaction['amount']) ? $transaction['amount'] : 0;
                    }
                    echo number_format($total_amount, 2);
                ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Average Transaction:</span>
                <span class="summary-value">₹ <?php 
                    echo count($report_data) > 0 ? number_format($total_amount / count($report_data), 2) : '0.00';
                ?></span>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Receipt No</th>
                <th>Customer</th>
                <th>Property</th>
                <th>Registration No</th>
                <th>Payment Type</th>
                <th>Method</th>
                <th class="text-right">Amount</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report_data as $transaction): ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($transaction['payment_date'])); ?></td>
                <td><?php echo htmlspecialchars($transaction['receipt_number'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($transaction['plot_buyer_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($transaction['garden_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($transaction['registration_number'] ?? 'N/A'); ?></td>
                <td><?php echo ucfirst(str_replace('_', ' ', $transaction['payment_type'])); ?></td>
                <td><?php echo ucfirst($transaction['payment_method']); ?></td>
                <td class="text-right">₹ <?php echo number_format($transaction['amount'], 2); ?></td>
                <td><?php echo htmlspecialchars(substr($transaction['notes'] ?? '', 0, 50)); ?><?php echo strlen($transaction['notes'] ?? '') > 50 ? '...' : ''; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="7" class="text-right">Total:</td>
                <td class="text-right">₹ <?php echo number_format($total_amount, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <div class="summary-section">
        <p>No transaction data found for the selected criteria.</p>
    </div>
<?php endif; ?>