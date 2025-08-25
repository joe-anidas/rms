<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .receipt-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .receipt-title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .company-info { font-size: 14px; color: #666; }
        .receipt-details { margin-bottom: 30px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .detail-label { font-weight: bold; }
        .amount-section { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .total-amount { font-size: 18px; font-weight: bold; color: #28a745; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<?php if (isset($receipt) && $receipt): ?>
    
    <div class="receipt-header">
        <div class="receipt-title">PAYMENT RECEIPT</div>
        <div class="company-info">
            <div><?= $receipt['company_info']['name'] ?></div>
            <div><?= $receipt['company_info']['address'] ?></div>
            <div>Phone: <?= $receipt['company_info']['phone'] ?> | Email: <?= $receipt['company_info']['email'] ?></div>
        </div>
    </div>

    <div class="receipt-details">
        <div class="detail-row">
            <span class="detail-label">Receipt Number:</span>
            <span><?= $receipt['transaction']['receipt_number'] ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date:</span>
            <span><?= date('d/m/Y', strtotime($receipt['transaction']['payment_date'])) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Registration Number:</span>
            <span><?= $receipt['transaction']['registration_number'] ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Customer Name:</span>
            <span><?= $receipt['transaction']['plot_buyer_name'] ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Property:</span>
            <span><?= $receipt['transaction']['garden_name'] ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Type:</span>
            <span><?= ucfirst(str_replace('_', ' ', $receipt['transaction']['payment_type'])) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Method:</span>
            <span><?= ucfirst(str_replace('_', ' ', $receipt['transaction']['payment_method'])) ?></span>
        </div>
    </div>

    <div class="amount-section">
        <div class="detail-row total-amount">
            <span>Amount Paid:</span>
            <span>₹<?= number_format($receipt['transaction']['amount'], 2) ?></span>
        </div>
    </div>

    <?php if (isset($receipt['balance_info']) && !isset($receipt['balance_info']['error'])): ?>
        <div class="receipt-details">
            <h4>Balance Information</h4>
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span>₹<?= number_format($receipt['balance_info']['total_amount'], 2) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Paid:</span>
                <span>₹<?= number_format($receipt['balance_info']['total_paid'], 2) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Remaining Balance:</span>
                <span class="<?= $receipt['balance_info']['balance'] > 0 ? 'text-danger' : 'text-success' ?>">
                    ₹<?= number_format($receipt['balance_info']['balance'], 2) ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($receipt['transaction']['notes'])): ?>
        <div class="receipt-details">
            <div class="detail-row">
                <span class="detail-label">Notes:</span>
                <span><?= $receipt['transaction']['notes'] ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer">
        <div>Thank you for your payment!</div>
        <div>Generated on: <?= date('d/m/Y H:i:s') ?></div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

<?php else: ?>
    <div class="alert alert-danger">
        Receipt not found or could not be generated.
    </div>
<?php endif; ?>

</body>
</html>