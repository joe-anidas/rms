<?php
// Helper function to retrieve old input values
function old($key, $default = '') {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : $default;
}
?>
            background-color: #fff;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 1px 6px rgba(32, 33, 36, 0.28);
        }
        
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 35px;
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: #1a73e8;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1765cc;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }
        
        .btn-secondary {
            background: #fff;
            color: #1a73e8;
            border: 1px solid #dadce0;
        }
        
        .btn-secondary:hover {
            background: #f8f9fa;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }
        
        .error {
            color: #ea4335;
            font-size: 12px;
            margin-top: 4px;
        }
        
        .success {
            background-color: #e8f5e8;
            color: #137333;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #ceead6;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row-2 {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="breadcrumb">
            <a href="/billing-receipts">Billing Receipt</a> / Add Billing Receipt
        </div>
        
        <div class="form-container">
            <h1 class="page-title">Add Billing Receipt</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success">
                    <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <form action="/billing-receipt/store" method="POST" id="billingReceiptForm">
                <!-- First Row -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="bill_id">Bill ID</label>
                        <input type="text" 
                               id="bill_id" 
                               name="bill_id" 
                               value="<?= old('bill_id', 'Bill_0000000014_Plot') ?>" 
                               required>
                        <?php if (isset($errors['bill_id'])): ?>
                            <span class="error"><?= $errors['bill_id'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_time">Date & Time</label>
                        <input type="date" 
                               id="date_time" 
                               name="date_time" 
                               value="<?= old('date_time', '2025-03-02') ?>" 
                               required>
                        <?php if (isset($errors['date_time'])): ?>
                            <span class="error"><?= $errors['date_time'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="sno_customer">Sno / Customer</label>
                        <select id="sno_customer" name="sno_customer" required>
                            <option value="">Select Sno / Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['sno'] ?>"
                                        data-customer-name="<?= htmlspecialchars($customer['name']) ?>"
                                        <?= (old('sno_customer') == $customer['sno']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($customer['sno']) ?> - <?= htmlspecialchars($customer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['sno_customer'])): ?>
                            <span class="error"><?= $errors['sno_customer'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <select id="customer_name" name="customer_name" required>
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= htmlspecialchars($customer['name']) ?>"
                                        <?= (old('customer_name') == $customer['name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($customer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['customer_name'])): ?>
                            <span class="error"><?= $errors['customer_name'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="father_name">Father Name</label>
                        <input type="text" 
                               id="father_name" 
                               name="father_name" 
                               placeholder="Father Name"
                               value="<?= old('father_name') ?>" 
                               required>
                        <?php if (isset($errors['father_name'])): ?>
                            <span class="error"><?= $errors['father_name'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="nagar_garden_name">Select Nagar/Garden Name</label>
                        <select id="nagar_garden_name" name="nagar_garden_name" required>
                            <option value="">Select Nagar / Garden</option>
                            <?php foreach ($nagars as $nagar): ?>
                                <option value="<?= htmlspecialchars($nagar['name']) ?>"
                                        <?= (old('nagar_garden_name') == $nagar['name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nagar['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['nagar_garden_name'])): ?>
                            <span class="error"><?= $errors['nagar_garden_name'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Third Row -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="plot_no">Select Plot No</label>
                        <select id="plot_no" name="plot_no" required>
                            <option value="">Select Plot No</option>
                            <?php foreach ($plots as $plot): ?>
                                <option value="<?= htmlspecialchars($plot['plot_number']) ?>"
                                        <?= (old('plot_no') == $plot['plot_number']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($plot['plot_number']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['plot_no'])): ?>
                            <span class="error"><?= $errors['plot_no'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="plot_extension">Plot Extension in SqFt/Sqmt</label>
                        <input type="text" 
                               id="plot_extension" 
                               name="plot_extension" 
                               placeholder="Plot Extension in SqFt/Sqmt"
                               value="<?= old('plot_extension') ?>" 
                               required>
                        <?php if (isset($errors['plot_extension'])): ?>
                            <span class="error"><?= $errors['plot_extension'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="advance_amount">Advance Amount</label>
                        <input type="number" 
                               id="advance_amount" 
                               name="advance_amount" 
                               step="0.01" 
                               min="0"
                               value="<?= old('advance_amount') ?>">
                    </div>
                </div>
                
                <!-- Fourth Row -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="mode_of_payment">Mode of Payment</label>
                        <select id="mode_of_payment" name="mode_of_payment" required>
                            <option value="">Cash</option>
                            <option value="Cash" <?= (old('mode_of_payment') == 'Cash') ? 'selected' : '' ?>>Cash</option>
                            <option value="Bank Transfer" <?= (old('mode_of_payment') == 'Bank Transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                            <option value="Cheque" <?= (old('mode_of_payment') == 'Cheque') ? 'selected' : '' ?>>Cheque</option>
                            <option value="UPI" <?= (old('mode_of_payment') == 'UPI') ? 'selected' : '' ?>>UPI</option>
                        </select>
                        <?php if (isset($errors['mode_of_payment'])): ?>
                            <span class="error"><?= $errors['mode_of_payment'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="cash_amount">Cash</label>
                        <input type="number" 
                               id="cash_amount" 
                               name="cash_amount" 
                               placeholder="Cash"
                               step="0.01" 
                               min="0"
                               value="<?= old('cash_amount') ?>" 
                               required>
                        <?php if (isset($errors['cash_amount'])): ?>
                            <span class="error"><?= $errors['cash_amount'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="balance_amount">Balance Amount</label>
                        <input type="number" 
                               id="balance_amount" 
                               name="balance_amount" 
                               step="0.01" 
                               min="0"
                               value="<?= old('balance_amount') ?>">
                    </div>
                </div>
                
                <!-- Fifth Row -->
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" 
                               id="phone_number" 
                               name="phone_number" 
                               value="<?= old('phone_number') ?>" 
                               required>
                        <?php if (isset($errors['phone_number'])): ?>
                            <span class="error"><?= $errors['phone_number'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  placeholder="Address"
                                  required><?= old('address') ?></textarea>
                        <?php if (isset($errors['address'])): ?>
                            <span class="error"><?= $errors['address'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="/billing-receipts" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-sync customer selection
        document.getElementById('sno_customer').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const customerName = selectedOption.getAttribute('data-customer-name');
            const customerNameField = document.getElementById('customer_name');
            
            if (customerName) {
                // Select the matching customer name
                for (let i = 0; i < customerNameField.options.length; i++) {
                    if (customerNameField.options[i].value === customerName) {
                        customerNameField.selectedIndex = i;
                        break;
                    }
                }
            }
        });
        
        document.getElementById('customer_name').addEventListener('change', function() {
            const customerName = this.value;
            const snoCustomerField = document.getElementById('sno_customer');
            
            if (customerName) {
                // Select the matching sno/customer
                for (let i = 0; i < snoCustomerField.options.length; i++) {
                    if (snoCustomerField.options[i].getAttribute('data-customer-name') === customerName) {
                        snoCustomerField.selectedIndex = i;
                        break;
                    }
                }
            }
        });
        
        // Form validation
        document.getElementById('billingReceiptForm').addEventListener('submit', function(e) {
            const requiredFields = ['bill_id', 'date_time', 'sno_customer', 'customer_name', 'father_name', 'nagar_garden_name', 'plot_no', 'plot_extension', 'mode_of_payment', 'cash_amount', 'phone_number', 'address'];
            
            for (let field of requiredFields) {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    e.preventDefault();
                    alert(`Please fill in the ${field.replace('_', ' ').toUpperCase()} field.`);
                    element.focus();
                    return false;
                }
            }
        });
    </script>
</body>
</html>
