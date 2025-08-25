<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Sale Date</th>
            <th>Property/Garden</th>
            <th>Plot No</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>District</th>
            <th class="text-end">Sale Amount</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)): ?>
            <?php 
            $total_sales = 0;
            foreach ($data as $sale): 
                $total_sales += isset($sale->sale_amount) ? $sale->sale_amount : 0;
            ?>
            <tr>
                <td><?php echo isset($sale->sale_date) ? date('d/m/Y', strtotime($sale->sale_date)) : 'N/A'; ?></td>
                <td><?php echo htmlspecialchars($sale->garden_name ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->plot_no ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->plot_buyer_name ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->phone_number_1 ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($sale->district ?? 'N/A'); ?></td>
                <td class="text-end">₹ <?php echo number_format($sale->sale_amount ?? 0, 2); ?></td>
                <td class="text-center">
                    <span class="badge bg-<?php echo ($sale->status ?? '') == 'sold' ? 'success' : 'warning'; ?>">
                        <?php echo ucfirst($sale->status ?? 'N/A'); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center text-muted">No sales data found</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <?php if (!empty($data)): ?>
    <tfoot class="table-secondary">
        <tr>
            <th colspan="6" class="text-end">Total Sales:</th>
            <th class="text-end">₹ <?php echo number_format($total_sales, 2); ?></th>
            <th></th>
        </tr>
    </tfoot>
    <?php endif; ?>
</table>