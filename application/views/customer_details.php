<div class="card mt-3">
    <div class="card-header">Add Customer Details
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
        <form method="POST" action="/submit-customer">
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Plot Buyer Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter Plot Buyer Name">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Father Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Father Name">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-caret-down"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>District</label>
                        <select class="form-control">
                            <option>Select District</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" class="form-control" placeholder="Pincode">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Taluk Name</label>
                        <input type="text" class="form-control" placeholder="Taluk Name">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Village/Town Name</label>
                        <input type="text" class="form-control" placeholder="Village/Town Name">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Street Address</label>
                        <textarea class="form-control" placeholder="Street Address" rows="1"></textarea>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Total Plot Bought</label>
                        <input type="text" class="form-control" placeholder="Total Plot Bought">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Phone Number 1</label>
                        <input type="text" class="form-control" placeholder="Phone Number 1">
                    </div>
                </div>
            </div>
            
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Phone Number 2</label>
                        <input type="text" class="form-control" placeholder="Phone Number 2">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>ID Proof</label>
                        <select class="form-control">
                            <option>Aadhar</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Aadhar</label>
                        <input type="text" class="form-control" placeholder="Aadhar">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
    // Toggle password visibility
    document.querySelectorAll('.fa-eye').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // Update custom file input labels
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || "Choose file";
            this.nextElementSibling.textContent = fileName;
        });
    });
</script>