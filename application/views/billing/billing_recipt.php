<?php
// Helper function to retrieve old input values
function old($key, $default = '') {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : $default;
}
?>

<div class="card mt-3">
    <div class="card-header">Customer Receipt
        <div class="card-action">
            <div class="dropdown">
                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                    <i class="icon-options"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="javascript:void();">Action</a>
                    <a class="dropdown-item" href="javascript:void();">Another action</a>
                    <a class="dropdown-item" href="javascript:void();">Something else here</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void();">Separated link</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success">
                    <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <form action="/billing-receipt/store" method="POST" id="billingReceiptForm">
                <!-- First Row -->
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Bill ID <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bill_id" placeholder="Enter Bill ID" value="<?= old('bill_id', 'Bill_0000000014_Plot') ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Date & Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date_time" value="<?= old('date_time', date('Y-m-d')) ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Sno / Customer <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" name="sno_customer" required>
                                    <option value="">Select Sno / Customer</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['sno'] ?>"
                                                data-customer-name="<?= htmlspecialchars($customer['name']) ?>"
                                                <?= (old('sno_customer') == $customer['sno']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($customer['sno']) ?> - <?= htmlspecialchars($customer['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" name="customer_name" required>
                                    <option value="">Select Customer</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= htmlspecialchars($customer['name']) ?>"
                                                <?= (old('customer_name') == $customer['name']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($customer['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Father Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="father_name" placeholder="Father Name" value="<?= old('father_name', 'N/A') ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Select Nagar/Garden Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" name="nagar_garden_name" required>
                                    <option value="">Select Nagar / Garden</option>
                                    <?php foreach ($nagars as $nagar): ?>
                                        <option value="<?= htmlspecialchars($nagar['name']) ?>"
                                                <?= (old('nagar_garden_name') == $nagar['name']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($nagar['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Third Row -->
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Select Plot No <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" name="plot_no" required>
                                    <option value="">Select Plot No</option>
                                    <?php foreach ($plots as $plot): ?>
                                        <option value="<?= htmlspecialchars($plot['plot_number']) ?>"
                                                <?= (old('plot_no') == $plot['plot_number']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($plot['plot_number']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Plot Extension in SqFt/Sqmt <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="plot_extension" placeholder="Plot Extension in SqFt/Sqmt" value="<?= old('plot_extension', '1200') ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Advance Amount</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="advance_amount" step="0.01" min="0" value="<?= old('advance_amount', '0.00') ?>">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Fourth Row -->
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Mode of Payment <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" name="mode_of_payment" required>
                                    <option value="">Select Mode of Payment</option>
                                    <option value="Cash" <?= (old('mode_of_payment') == 'Cash' || old('mode_of_payment') == '') ? 'selected' : '' ?>>Cash</option>
                                    <option value="Bank Transfer" <?= (old('mode_of_payment') == 'Bank Transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                                    <option value="Cheque" <?= (old('mode_of_payment') == 'Cheque') ? 'selected' : '' ?>>Cheque</option>
                                    <option value="UPI" <?= (old('mode_of_payment') == 'UPI') ? 'selected' : '' ?>>UPI</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Cash <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="cash_amount" placeholder="Cash" step="0.01" min="0" value="<?= old('cash_amount', '0.00') ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label>Balance Amount</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="balance_amount" step="0.01" min="0" value="<?= old('balance_amount', '0.00') ?>">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Fifth Row -->
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label>Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="tel" class="form-control" name="phone_number" placeholder="Phone Number" value="<?= old('phone_number', '9876543210') ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label>Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address" placeholder="Address" rows="3" required><?= old('address', 'Enter customer address here') ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


