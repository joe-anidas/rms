<div class="card mt-3">
    <div class="card-header">Add Employee Salary
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
        
        <form action="/employee-salary/store" method="POST" id="employeeSalaryForm">
            <!-- First Row -->
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Salary Duration Start <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="salary_duration_start" value="<?= date('Y-m-01') ?>" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Salary Duration End <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="salary_duration_end" value="<?= date('Y-m-t') ?>" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Employee Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-control" name="employee_name" required>
                                <option value="">Select Employee Name</option>
                                <!-- Sample options - replace with dynamic PHP data -->
                                <option value="John Doe" data-employee-id="EMP001">John Doe</option>
                                <option value="Jane Smith" data-employee-id="EMP002">Jane Smith</option>
                                <option value="Mike Johnson" data-employee-id="EMP003">Mike Johnson</option>
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
                        <label>Employee ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="employee_id" placeholder="Employee ID" value="EMP001" readonly required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Employee ID Alt <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="employee_id_alt" placeholder="Employee ID" value="EMP001" readonly required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Net Salary Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="net_salary_amount" placeholder="Net Salary Amount" step="0.01" min="0" value="25000.00" required>
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
                        <label>Advance Amount</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="advance_amount" placeholder="Advance Amount" step="0.01" min="0" value="0.00">
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
                            <input type="number" class="form-control" name="balance_amount" placeholder="Balance Amount" step="0.01" min="0" value="25000.00" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <!-- Empty third column for spacing -->
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
