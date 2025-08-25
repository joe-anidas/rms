<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Date</th>
            <th>Receipt No</th>
            <th>Customer</th>
            <th>Property</th>
            <th>Registration No</th>
            <th>Payment Type</th>
            <th>Method</th>
            <th class="text-end">Amount</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)): ?>
            <?php 
            $total_amount = 0;
            foreach ($data as $transaction): 
                $total_amount += isset($transaction['amount']) ? $transaction['amount'] : 0;
            ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($transaction['payment_date'])); ?></td>
                <td><?php echo htmlspecialchars($transaction['receipt_number'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($transaction['plot_buyer_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($transaction['garden_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($transaction['registration_number'] ?? 'N/A'); ?></td>
                <td>
                    <span class="badge bg-info">
                        <?php echo ucfirst(str_replace('_', ' ', $transaction['payment_type'])); ?>
                    </span>
                </td>
                <td>
                    <span class="badge bg-secondary">
                        <?php echo ucfirst($transaction['payment_method']); ?>
                    </span>
                </td>
                <td class="text-end">₹ <?php echo number_format($transaction['amount'], 2); ?></td>
                <td>
                    <?php 
                    $notes = $transaction['notes'] ?? '';
                    echo htmlspecialchars(strlen($notes) > 30 ? substr($notes, 0, 30) . '...' : $notes); 
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center text-muted">No transaction data found</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <?php if (!empty($data)): ?>
    <tfoot class="table-secondary">
        <tr>
            <th colspan="7" class="text-end">Total Amount:</th>
            <th class="text-end">₹ <?php echo number_format($total_amount, 2); ?></th>
            <th></th>
        </tr>
    </tfoot>
    <?php endif; ?>
</table>