<div class="card mt-3">
    <div class="card-header">Nager/Garden Profile
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
        <h5 class="card-title">Add Nager/Garden Profile</h5>
        <form id="gardenProfileForm">
            <div class="row row-group m-0">
                <!-- First Row of Inputs -->
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Nagar/Garden Name</label>
                        <input type="text" class="form-control" name="garden_name" placeholder="Enter Name" value="Sample Garden" required>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>District</label>
                        <input type="text" class="form-control" name="district" placeholder="Enter District" value="Chennai">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Taluk Name</label>
                        <input type="text" class="form-control" name="taluk_name" placeholder="Enter Taluk Name" value="Tambaram">
                    </div>
                </div>
            </div>

            <!-- Continue with other rows in the same pattern -->
            <!-- Second Row -->
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Village/Town Name</label>
                        <input type="text" class="form-control" name="village_town_name" placeholder="Enter Village/Town Name" value="Tambaram">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Patta / Chitta No</label>
                        <input type="text" class="form-control" name="patta_chitta_no" placeholder="Enter Patta/Chitta No" value="P123456">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>T.S.No</label>
                        <input type="text" class="form-control" name="ts_no" placeholder="Enter T.S.No" value="TS001">
                    </div>
                </div>
            </div>

            <!-- Third Row -->
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Ward/Block</label>
                        <input type="text" class="form-control" name="ward_block" placeholder="Enter Ward/Block" value="Ward 1">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Land Mark</label>
                        <input type="text" class="form-control" name="land_mark" placeholder="Enter Land Mark" value="Near Bus Stand">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>DTCP No</label>
                        <input type="text" class="form-control" name="dtcp_no" placeholder="Enter DTCP No" value="DTCP001">
                    </div>
                </div>
            </div>

            <!-- Fourth Row -->
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>RERA No</label>
                        <input type="text" class="form-control" name="rera_no" placeholder="Enter RERA No" value="RERA001">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Nagar/Garden Total Extension in Sqft/Sqmt</label>
                        <input type="text" class="form-control" name="total_extension" placeholder="Enter Total Extension" value="50000">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Nagar/Garden Total No of Plots</label>
                        <input type="text" class="form-control" name="total_plots" placeholder="Enter Total No of Plots" value="100">
                    </div>
                </div>
            </div>

            <!-- Fifth Row -->
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Nagar/Garden Sale Extension in Sqft/Sqmt</label>
                        <input type="text" class="form-control" name="sale_extension" placeholder="Nagar/Garden Sale Extension in Sqft/Sqmt" value="45000">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Park Extension in Sqft/Sqmt</label>
                        <input type="text" class="form-control" name="park_extension" placeholder="Park Extension in Sqft/Sqmt" value="3000">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Read Extension in Sqft/Sqmt</label>
                        <input type="text" class="form-control" name="road_extension" placeholder="Read Extension in Sqft/Sqmt" value="2000">
                    </div>
                </div>
            </div>

            <!-- Radio Button Row -->
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Choose EB Line:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="eb_line" id="eb_yes" value="yes" checked>
                            <label class="form-check-label" for="eb_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="eb_line" id="eb_no" value="no">
                            <label class="form-check-label" for="eb_no">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Choose Tree Saplings:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tree_saplings" id="tree_yes" value="yes" checked>
                            <label class="form-check-label" for="tree_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tree_saplings" id="tree_no" value="no">
                            <label class="form-check-label" for="tree_no">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Choose Water Tank:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="water_tank" id="water_yes" value="yes" checked>
                            <label class="form-check-label" for="water_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="water_tank" id="water_no" value="no">
                            <label class="form-check-label" for="water_no">No</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sixth Row -->
            <div class="row row-group m-0">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Land Purchased RS.no</label>
                        <input type="text" class="form-control" name="land_purchased_rs" placeholder="Land Purchased RS.no" value="RS001">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Land UnPurchased RS.no</label>
                        <input type="text" class="form-control" name="land_unpurchased_rs" placeholder="Land UnPurchased RS.no" value="RS002">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>Incentive %</label>
                        <input type="text" class="form-control" name="incentive_percent" placeholder="Incentive %" value="5">
                    </div>
                </div>
            </div>

            <!-- Registration Office Details Table -->
            <div class="card mt-3">
                <div class="card-header">REGISTRATION OFFICE DETAILS</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-borderless">
                            <tbody>
                                <tr>
                                    <td>Registration District</td>
                                    <td><input type="text" class="form-control" name="registration_district" value="Chennai"></td>
                                </tr>
                                <tr>
                                    <td>Registration Sub - District</td>
                                    <td><input type="text" class="form-control" name="registration_sub_district" value="Tambaram"></td>
                                </tr>
                                <tr>
                                    <td>Town/Village</td>
                                    <td><input type="text" class="form-control" name="town_village" value="Tambaram"></td>
                                </tr>
                                <tr>
                                    <td>Revenue Taluk</td>
                                    <td><input type="text" class="form-control" name="revenue_taluk" value="Tambaram"></td>
                                </tr>
                                <tr>
                                    <td>Sub Registrar</td>
                                    <td><input type="text" class="form-control" name="sub_registrar" value="Sub Registrar Office"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- File Upload Section -->
            <div class="row row-group m-0 mt-3">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" class="form-control" id="imageUpload" accept="image/*" onchange="displayFileName()">
                        <small id="fileName" class="form-text text-muted">No file chosen</small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row m-0 row-group text-center border-top border-light-3 mt-3">
                <div class="col-12 col-lg-6">
                    <button type="button" class="btn btn-light px-5">Upload CSV</button>
                </div>
                <div class="col-12 col-lg-6">
                    <button type="button" class="btn btn-light px-5">Download Sample CSV</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Plot Details Table Card -->
<div class="card mt-3">
    <div class="card-header">Plot Details
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
        <div class="table-responsive">
            <table class="table align-items-center table-flush table-borderless" id="dynamicTable">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>PLOT NO</th>
                        <th>PLOT EXTENSION</th>
                        <th>NORTH</th>
                        <th>EAST</th>
                        <th>WEST</th>
                        <th>SOUTH</th>
                        <th>PLOT VALUE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <button class="btn btn-sm btn-danger delete-btn">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success add-row">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                        <td><input type="text" class="form-control form-control-sm" name="plot_no[]" value="A001"></td>
                        <td><input type="text" class="form-control form-control-sm" name="plot_extension[]" value="1200"></td>
                        <td><input type="text" class="form-control form-control-sm" name="north[]" value="30"></td>
                        <td><input type="text" class="form-control form-control-sm" name="east[]" value="40"></td>
                        <td><input type="text" class="form-control form-control-sm" name="west[]" value="30"></td>
                        <td><input type="text" class="form-control form-control-sm" name="south[]" value="40"></td>
                        <td><input type="text" class="form-control form-control-sm plot-value" name="plot_value[]" value="6000000"></td>
                        <td><input type="text" class="form-control form-control-sm" name="status[]" placeholder="UnSold" value="unsold"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <button id="submitTable" class="btn btn-primary px-5">Submit</button>
        </div>
    </div>
</div>

<!-- Document Upload Card -->
<div class="card mt-3">
    <div class="card-header">Upload Documents
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
        <div class="row row-group m-0">
            <div class="col-12 col-lg-6 border-light">
                <div class="form-group">
                    <label>Upload FMB/TSLR Sketch</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="fmbUpload">
                        <label class="custom-file-label" for="fmbUpload">Choose file</label>
                    </div>
                    <small class="form-text text-muted">Drop files here or click to upload</small>
                </div>
            </div>
            <div class="col-12 col-lg-6 border-light">
                <div class="form-group">
                    <label>Upload Plot Layout Diagram</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="plotUpload">
                        <label class="custom-file-label" for="plotUpload">Choose file</label>
                    </div>
                    <small class="form-text text-muted">Drop files here or click to upload</small>
                </div>
            </div>
        </div>
        <div class="row row-group m-0 mt-3">
            <div class="col-12 col-lg-6 border-light">
                <div class="form-group">
                    <label>Upload Parent Document</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="parentUpload">
                        <label class="custom-file-label" for="parentUpload">Choose file</label>
                    </div>
                    <small class="form-text text-muted">Drop files here or click to upload</small>
                </div>
            </div>
            <div class="col-12 col-lg-6 border-light">
                <div class="form-group">
                    <label>Upload Another Document</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="otherUpload">
                        <label class="custom-file-label" for="otherUpload">Choose file</label>
                    </div>
                    <small class="form-text text-muted">Drop files here or click to upload</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Final Action Buttons -->
<div class="row mt-3">
    <div class="col-6">
        <button class="btn btn-light btn-block"><i class="fa fa-arrow-left mr-2"></i> Previous</button>
    </div>
    <div class="col-6">
        <button type="button" class="btn btn-primary btn-block" onclick="submitGardenProfile()">Submit <i class="fa fa-check ml-2"></i></button>
    </div>
</div>

<script>
    function displayFileName() {
        const fileInput = document.getElementById('imageUpload');
        const fileNameDisplay = document.getElementById('fileName');
        const fileName = fileInput.files[0]?.name || "No file chosen";
        fileNameDisplay.textContent = fileName;
    }

    // Update custom file input labels
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || "Choose file";
            this.nextElementSibling.textContent = fileName;
        });
    });

    // Table row management (same as before)
    $(document).ready(function () {
        const table = $("#dynamicTable tbody");

        const updateAddButtonVisibility = () => {
            table.find("tr .add-row").hide();
            table.find("tr:last-child .add-row").show();
        };

        const addRow = () => {
            const newRow = `
                <tr>
                    <td>
                        <button class="btn btn-sm btn-danger delete-btn">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success add-row">
                            <i class="fa fa-plus"></i>
                        </button>
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="plot_no[]" value="A002"></td>
                    <td><input type="text" class="form-control form-control-sm" name="plot_extension[]" value="1200"></td>
                    <td><input type="text" class="form-control form-control-sm" name="north[]" value="30"></td>
                    <td><input type="text" class="form-control form-control-sm" name="east[]" value="40"></td>
                    <td><input type="text" class="form-control form-control-sm" name="west[]" value="30"></td>
                    <td><input type="text" class="form-control form-control-sm" name="south[]" value="40"></td>
                    <td><input type="text" class="form-control form-control-sm plot-value" name="plot_value[]" value="6000000"></td>
                    <td><input type="text" class="form-control form-control-sm" name="status[]" placeholder="UnSold" value="unsold"></td>
                </tr>`;
            table.append(newRow);
            updateAddButtonVisibility();
        };

        const deleteRow = (button) => {
            $(button).closest("tr").remove();
            updateAddButtonVisibility();
        };

        $(document).on("click", ".add-row", function () {
            addRow();
        });

        $(document).on("click", ".delete-btn", function () {
            deleteRow(this);
        });

        $(document).on("keydown", ".plot-value", function (e) {
            if (e.key === "Tab") {
                e.preventDefault();
                const currentRow = $(this).closest("tr");
                const nextRow = currentRow.next();
                if (nextRow.length === 0) {
                    addRow();
                }
                currentRow.next().find("td:nth-child(3) .form-control").focus();
            }
        });

        $("#submitTable").on("click", function () {
            const rows = table.find("tr").map(function () {
                return $(this)
                    .find("input")
                    .map(function () {
                        return $(this).val();
                    })
                    .get();
            }).get();

            alert(JSON.stringify(rows, null, 2));
        });

        updateAddButtonVisibility();
    });

    // Submit garden profile form
    function submitGardenProfile() {
        // Get form data
        const form = document.getElementById('gardenProfileForm');
        const formData = new FormData(form);
        
        // Add plots data from dynamic table
        const table = document.getElementById('dynamicTable');
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach((row, index) => {
            const inputs = row.querySelectorAll('input');
            if (inputs.length >= 8) {
                formData.append('plot_no[]', inputs[0].value || '');
                formData.append('plot_extension[]', inputs[1].value || '');
                formData.append('north[]', inputs[2].value || '');
                formData.append('east[]', inputs[3].value || '');
                formData.append('west[]', inputs[4].value || '');
                formData.append('south[]', inputs[5].value || '');
                formData.append('plot_value[]', inputs[6].value || '');
                formData.append('status[]', inputs[7].value || 'unsold');
            }
        });
        
        // Show loading state
        const submitBtn = document.querySelector('button[onclick="submitGardenProfile()"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Submitting...';
        submitBtn.disabled = true;
        
        // Submit form
        fetch('<?php echo base_url('submit_garden_profile'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Garden profile saved successfully!');
                // Optionally redirect to view page
                window.location.href = '<?php echo base_url('unsold_plots'); ?>';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting form. Please try again.');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
</script>