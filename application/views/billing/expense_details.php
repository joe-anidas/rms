<div class="card mt-3">
    <div class="card-header">Add Expense Details
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
        <form action="/expense-details/store" method="POST">
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Select Nagar/Garden Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-control" name="nagar_garden_name" required>
                                <option value="">Select Nagar / Garden</option>
                                <!-- Dynamic options go here -->
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Select Expense <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-control" name="expense_type" required>
                                <option value="">Select Expense</option>
                                <!-- Dynamic options go here -->
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Expense <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="expense_amount" placeholder="Expense" step="0.01" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-group m-0">
                <div class="col-12">
                    <div class="form-group">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" placeholder="Description" rows="3" required></textarea>
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
