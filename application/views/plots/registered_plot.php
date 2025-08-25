<div class="container mt-3">
    <!-- Page 1: Plot Details -->
    <form id="page1">
      <div class="card">
        <div class="card-header">Register Plot Details
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
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="reg-0026" placeholder="S.No" value="1">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control" id="garden-name">
                  <option>Select Nagar/Garden</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="plot-no" placeholder="Select Plot No" value="A1">
              </div>
            </div>
          </div>

          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="plot-extension" placeholder="Total Plot Extension in Sqft/Sqmt" value="1200">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="doc-number" placeholder="Plot Registration Document Number" value="DOC001">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="reg-date" placeholder="Plot Registration Date" value="<?= date('Y-m-d') ?>">
              </div>
            </div>
          </div>

          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="patta-chitta" placeholder="Patta/Chitta No" value="PC001">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="ts-no" placeholder="T.S.No" value="TS001">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="ward-block" placeholder="Ward/Block" value="Ward 1">
              </div>
            </div>
          </div>

          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="plot-rate" placeholder="Plot Rate/Sqft" value="500">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control" id="employee">
                  <option>Name Refered By</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" id="alt-phone" placeholder="Alternative Phone Number" value="9876543210">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">Enter Plot Four Side Extension Value in Sqft/Sqmt</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dynamicTable" class="table align-items-center table-flush table-borderless">
              <thead>
                <tr>
               
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
         
                  <td><input type="text" class="form-control form-control-sm"></td>
                  <td><input type="text" class="form-control form-control-sm"></td>
                  <td><input type="text" class="form-control form-control-sm"></td>
                  <td><input type="text" class="form-control form-control-sm"></td>
                  <td><input type="text" class="form-control form-control-sm"></td>
                  <td><input type="text" class="form-control form-control-sm"></td>
                  <td><input type="text" class="form-control form-control-sm plot-value"></td>
                  <td><input type="text" class="form-control form-control-sm" placeholder="UnSold"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">REGISTRATION OFFICE DETAILS</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-items-center table-flush table-borderless">
              <tbody>
                <tr>
                  <td>Registration District</td>
                  <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                  <td>Registration Sub-District</td>
                  <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                  <td>Town/Village</td>
                  <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                  <td>Revenue Taluk</td>
                  <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                  <td>Sub Registrar</td>
                  <td><input type="text" class="form-control"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </form>
    
    <!-- Page 2: Customer Details -->
    <form id="page2">
      <div class="card mt-3">
        <div class="card-header">Customer Details</div>
        <div class="card-body">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Enter Plot Buyer Name">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Enter Father Name">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control">
                  <option>Select District</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Pincode">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Taluk Name">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Village/Town Name">
              </div>
            </div>
          </div>
          
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Street Address">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Phone Number 1">
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Phone Number 2">
              </div>
            </div>
          </div>
          
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control">
                  <option>Id Proof</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Aadhar">
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    
    <!-- Page 3: Payment Details -->
    <form id="page3">
      <div class="card mt-3">
        <div class="card-header">Payment Details</div>
        <div class="card-body">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <select class="form-control">
                  <option>Mode of Payment</option>
                  <option>Cash</option>
                  <option>Cheque</option>
                  <option>UPI</option>
                  <option>NEFT/RTGS</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Cash">
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    
    <!-- Page 4: Upload Documents -->
    <form id="page4">
      <div class="card mt-3">
        <div class="card-header">Upload Documents</div>
        <div class="card-body">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-6 border-light">
              <div class="card-body">
                <label>Upload Registered Title Deed Document</label>
                <input type="file" class="form-control">
              </div>
            </div>
            <div class="col-12 col-lg-6 border-light">
              <div class="card-body">
                <label>Upload Plot Sketch</label>
                <input type="file" class="form-control">
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-light px-5">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </form>
</div>