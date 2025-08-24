<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa fa-calendar-check mr-2"></i>Booked Plots List</h5>
        <div class="card-action">
            <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus mr-1"></i>Add New Garden
            </a>
            <a href="<?php echo base_url('plots/overview'); ?>" class="btn btn-secondary btn-sm">
                <i class="fa fa-eye mr-1"></i>View All Plots
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($booked_plots)): ?>
            <div class="text-center py-5">
                <i class="fa fa-calendar-check fa-3x text-info mb-3"></i>
                <h5 class="text-muted">No booked plots found</h5>
                <p class="text-muted">Booked plots will appear here once they are reserved by customers</p>
                <a href="<?php echo base_url('garden_profile'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>Add Garden
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Plot ID</th>
                            <th>Garden Name</th>
                            <th>Plot No</th>
                            <th>Plot Extension</th>
                            <th>Plot Value</th>
                            <th>Customer Name</th>
                            <th>Booking Date</th>
                            <th>Booking Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($booked_plots as $plot): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-info">#<?php echo $plot->id; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($plot->garden_name); ?></strong>
                                    <?php if($plot->district): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($plot->district); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?php echo htmlspecialchars($plot->plot_no); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($plot->plot_extension); ?></strong>
                                    <br><small class="text-muted">Sqft/Sqmt</small>
                                </td>
                                <td>
                                    <strong class="text-primary">₹<?php echo number_format($plot->plot_value); ?></strong>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($plot->customer_name); ?></strong>
                                    <?php if($plot->customer_phone): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($plot->customer_phone); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d M Y', strtotime($plot->booking_date)); ?>
                                        <br><?php echo date('h:i A', strtotime($plot->booking_date)); ?>
                                    </small>
                                </td>
                                <td>
                                    <strong class="text-info">₹<?php echo number_format($plot->booking_amount); ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">Booked</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewBookedPlot(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" onclick="convertToSale(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editBookedPlot(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="cancelBooking(<?php echo $plot->id; ?>)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Showing <strong><?php echo count($booked_plots); ?></strong> booked plot(s)
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success btn-sm" onclick="exportBookedPlots()">
                            <i class="fa fa-download mr-1"></i>Export Data
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Booked Plot Details Modal -->
<div class="modal fade" id="bookedPlotModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booked Plot Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bookedPlotModalBody">
                <!-- Booked plot details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="convertToSaleFromModal()">Convert to Sale</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Convert to Sale Modal -->
<div class="modal fade" id="convertToSaleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Convert Booking to Sale</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="convertToSaleForm">
                    <input type="hidden" id="plotIdToConvert" name="plot_id">
                    <div class="form-group">
                        <label for="finalSaleAmount">Final Sale Amount</label>
                        <input type="number" class="form-control" id="finalSaleAmount" name="final_sale_amount" value="6000000" required>
                    </div>
                    <div class="form-group">
                        <label for="saleDate">Sale Date</label>
                        <input type="date" class="form-control" id="saleDate" name="sale_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">Payment Method</label>
                        <select class="form-control" id="paymentMethod" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="cheque" selected>Cheque</option>
                            <option value="upi">UPI</option>
                            <option value="neft">NEFT/RTGS</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="additionalNotes">Additional Notes</label>
                        <textarea class="form-control" id="additionalNotes" name="additional_notes" rows="3" placeholder="Enter any additional notes here...">Booking successfully converted to sale. All documents submitted.</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmConvertToSale()">Convert to Sale</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cancelBookingForm">
                    <input type="hidden" id="plotIdToCancel" name="plot_id">
                    <div class="form-group">
                        <label for="cancellationReason">Cancellation Reason</label>
                        <select class="form-control" id="cancellationReason" name="cancellation_reason" required>
                            <option value="">Select Reason</option>
                            <option value="customer_request" selected>Customer Request</option>
                            <option value="payment_issue">Payment Issue</option>
                            <option value="document_issue">Document Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="refundAmount">Refund Amount (if applicable)</label>
                        <input type="number" class="form-control" id="refundAmount" name="refund_amount" value="0">
                    </div>
                    <div class="form-group">
                        <label for="cancellationNotes">Additional Notes</label>
                        <textarea class="form-control" id="cancellationNotes" name="cancellation_notes" rows="3" placeholder="Enter cancellation details...">Booking cancelled as per customer request.</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmCancelBooking()">Cancel Booking</button>
            </div>
        </div>
    </div>
</div>

<script>
// View booked plot details
function viewBookedPlot(plotId) {
    fetch(`<?php echo base_url('get_booked_plot/'); ?>${plotId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const plot = data.plot;
                document.getElementById('bookedPlotModalBody').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Plot Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Plot No:</strong></td><td>${plot.plot_no}</td></tr>
                                <tr><td><strong>Garden Name:</strong></td><td>${plot.garden_name}</td></tr>
                                <tr><td><strong>Plot Extension:</strong></td><td>${plot.plot_extension} Sqft/Sqmt</td></tr>
                                <tr><td><strong>Plot Value:</strong></td><td>₹${plot.plot_value}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Customer Information</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Customer Name:</strong></td><td>${plot.customer_name}</td></tr>
                                <tr><td><strong>Phone:</strong></td><td>${plot.customer_phone || 'N/A'}</td></tr>
                                <tr><td><strong>Booking Date:</strong></td><td>${new Date(plot.booking_date).toLocaleDateString()}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-info">Booked</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-primary">Booking Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>Booking Amount:</strong></td><td>₹${plot.booking_amount}</td></tr>
                                <tr><td><strong>Balance Amount:</strong></td><td>₹${plot.plot_value - plot.booking_amount}</td></tr>
                                <tr><td><strong>Payment Method:</strong></td><td>${plot.payment_method || 'N/A'}</td></tr>
                                <tr><td><strong>Booking Reference:</strong></td><td>${plot.booking_reference || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Location Details</h6>
                            <table class="table table-borderless">
                                <tr><td><strong>District:</strong></td><td>${plot.district || 'N/A'}</td></tr>
                                <tr><td><strong>Taluk:</strong></td><td>${plot.taluk_name || 'N/A'}</td></tr>
                                <tr><td><strong>Village/Town:</strong></td><td>${plot.village_town_name || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                $('#bookedPlotModal').modal('show');
            } else {
                alert('Error loading booked plot details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading booked plot details');
        });
}

// Convert booking to sale
function convertToSale(plotId) {
    document.getElementById('plotIdToConvert').value = plotId;
    document.getElementById('saleDate').value = new Date().toISOString().split('T')[0];
    $('#convertToSaleModal').modal('show');
}

// Convert to sale from modal
function convertToSaleFromModal() {
    const plotId = document.getElementById('plotIdToConvert').value;
    convertToSale(plotId);
}

// Confirm convert to sale
function confirmConvertToSale() {
    const formData = new FormData(document.getElementById('convertToSaleForm'));
    
    fetch('<?php echo base_url('convert_booking_to_sale'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Booking converted to sale successfully!');
            $('#convertToSaleModal').modal('hide');
            location.reload();
        } else {
            alert('Error converting booking to sale: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error converting booking to sale');
    });
}

// Cancel booking
function cancelBooking(plotId) {
    document.getElementById('plotIdToCancel').value = plotId;
    $('#cancelBookingModal').modal('show');
}

// Confirm cancel booking
function confirmCancelBooking() {
    const formData = new FormData(document.getElementById('cancelBookingForm'));
    
    fetch('<?php echo base_url('cancel_booking'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Booking cancelled successfully!');
            $('#cancelBookingModal').modal('hide');
            location.reload();
        } else {
            alert('Error cancelling booking: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error cancelling booking');
    });
}

// Edit booked plot (placeholder function)
function editBookedPlot(plotId) {
    alert('Edit functionality will be implemented in the next version');
}

// Export booked plots data
function exportBookedPlots() {
    // Create CSV content
    const headers = ['Plot ID', 'Garden Name', 'Plot No', 'Plot Extension', 'Plot Value', 'Customer Name', 'Customer Phone', 'Booking Date', 'Booking Amount', 'Balance Amount', 'Payment Method', 'District', 'Taluk', 'Village/Town'];
    const csvContent = [
        headers.join(','),
        ...<?php echo json_encode(array_map(function($plot) {
            return [
                $plot->id,
                $plot->garden_name,
                $plot->plot_no,
                $plot->plot_extension,
                $plot->plot_value,
                $plot->customer_name,
                $plot->customer_phone ?: '',
                $plot->booking_date,
                $plot->booking_amount,
                $plot->plot_value - $plot->booking_amount,
                $plot->payment_method ?: '',
                $plot->district ?: '',
                $plot->taluk_name ?: '',
                $plot->village_town_name ?: ''
            ];
        }, $booked_plots ?? [])); ?>.map(row => row.map(field => `"${field}"`).join(','))
    ].join('\n');
    
    // Download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'booked_plots_data.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
