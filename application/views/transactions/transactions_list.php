<div class="row">
  <div class="col-12 col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">All Transactions</h5>
        <div class="card-tools">
          <a href="<?php echo base_url('transactions/record_payment'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Record Payment
          </a>
          <a href="<?php echo base_url('transactions/export_transactions'); ?>" class="btn btn-success btn-sm">
            <i class="fa fa-download"></i> Export
          </a>
        </div>
      </div>
      <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="row mb-3">
          <div class="col-md-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search transactions...">
          </div>
          <div class="col-md-2">
            <select class="form-control" id="statusFilter">
              <option value="">All Status</option>
              <option value="completed">Completed</option>
              <option value="pending">Pending</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-control" id="typeFilter">
              <option value="">All Types</option>
              <option value="advance">Advance</option>
              <option value="installment">Installment</option>
              <option value="full_payment">Full Payment</option>
              <option value="refund">Refund</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
          </div>
          <div class="col-md-3">
            <button class="btn btn-info btn-sm" id="clearFilters">Clear Filters</button>
            <button class="btn btn-secondary btn-sm" id="refreshData">Refresh</button>
          </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-responsive">
          <table class="table table-striped table-bordered" id="transactionsTable">
            <thead>
              <tr>
                <th>Date</th>
                <th>Plot/Property</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="transactionsTableBody">
              <!-- Data will be loaded here -->
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="row">
          <div class="col-md-6">
            <div class="dataTables_info" id="tableInfo">
              Showing 0 to 0 of 0 entries
            </div>
          </div>
          <div class="col-md-6">
            <div class="dataTables_paginate paging_simple_numbers" id="tablePagination">
              <!-- Pagination will be generated here -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Transactions Summary -->
<div class="row">
  <div class="col-12 col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Recent Transactions Summary</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body text-center">
                <h4 id="totalTransactions">0</h4>
                <p>Total Transactions</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-success text-white">
              <div class="card-body text-center">
                <h4 id="totalAmount">₹0</h4>
                <p>Total Amount</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-info text-white">
              <div class="card-body text-center">
                <h4 id="completedTransactions">0</h4>
                <p>Completed</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-warning text-white">
              <div class="card-body text-center">
                <h4 id="pendingTransactions">0</h4>
                <p>Pending</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    let currentPage = 1;
    let totalPages = 1;
    let transactionsPerPage = 10;
    let allTransactions = [];
    let filteredTransactions = [];

    // Load initial data
    loadTransactions();

    // Search functionality
    $('#searchInput').on('keyup', function() {
        filterTransactions();
    });

    // Filter functionality
    $('#statusFilter, #typeFilter, #dateFilter').on('change', function() {
        filterTransactions();
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#typeFilter').val('');
        $('#dateFilter').val('');
        filterTransactions();
    });

    // Refresh data
    $('#refreshData').on('click', function() {
        loadTransactions();
    });

    function loadTransactions() {
        $.ajax({
            url: '<?php echo base_url("transactions/get_all_transactions"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    allTransactions = response.data || [];
                    filterTransactions();
                    updateSummary();
                } else {
                    console.error('Error loading transactions:', response.message);
                    allTransactions = [];
                    filterTransactions();
                }
            },
            error: function() {
                console.error('Failed to load transactions');
                allTransactions = [];
                filterTransactions();
            }
        });
    }

    function filterTransactions() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const statusFilter = $('#statusFilter').val();
        const typeFilter = $('#typeFilter').val();
        const dateFilter = $('#dateFilter').val();

        filteredTransactions = allTransactions.filter(transaction => {
            let matches = true;

            // Search filter
            if (searchTerm) {
                const searchFields = [
                    transaction.plot_no || '',
                    transaction.garden_name || '',
                    transaction.customer_name || '',
                    transaction.transaction_type || '',
                    transaction.payment_method || ''
                ].join(' ').toLowerCase();
                
                if (!searchFields.includes(searchTerm)) {
                    matches = false;
                }
            }

            // Status filter
            if (statusFilter && transaction.status !== statusFilter) {
                matches = false;
            }

            // Type filter
            if (typeFilter && transaction.transaction_type !== typeFilter) {
                matches = false;
            }

            // Date filter
            if (dateFilter && transaction.payment_date !== dateFilter) {
                matches = false;
            }

            return matches;
        });

        currentPage = 1;
        displayTransactions();
        updatePagination();
    }

    function displayTransactions() {
        const startIndex = (currentPage - 1) * transactionsPerPage;
        const endIndex = startIndex + transactionsPerPage;
        const pageTransactions = filteredTransactions.slice(startIndex, endIndex);

        const tbody = $('#transactionsTableBody');
        tbody.empty();

        if (pageTransactions.length === 0) {
            tbody.append('<tr><td colspan="8" class="text-center">No transactions found</td></tr>');
            return;
        }

        pageTransactions.forEach(transaction => {
            const row = `
                <tr>
                    <td>${transaction.payment_date || 'N/A'}</td>
                    <td>${transaction.garden_name || 'N/A'} - Plot ${transaction.plot_no || 'N/A'}</td>
                    <td>${transaction.customer_name || 'N/A'}</td>
                    <td><span class="badge badge-${getTypeBadgeClass(transaction.transaction_type)}">${transaction.transaction_type || 'N/A'}</span></td>
                    <td>₹${transaction.amount || '0'}</td>
                    <td>${transaction.payment_method || 'N/A'}</td>
                    <td><span class="badge badge-${getStatusBadgeClass(transaction.status)}">${transaction.status || 'N/A'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewTransaction(${transaction.id})">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteTransaction(${transaction.id})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        updateTableInfo();
    }

    function updatePagination() {
        totalPages = Math.ceil(filteredTransactions.length / transactionsPerPage);
        const pagination = $('#tablePagination');
        pagination.empty();

        if (totalPages <= 1) {
            return;
        }

        // Previous button
        const prevDisabled = currentPage === 1 ? 'disabled' : '';
        pagination.append(`
            <a class="paginate_button previous ${prevDisabled}" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
        `);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const active = i === currentPage ? 'current' : '';
            pagination.append(`
                <a class="paginate_button ${active}" href="#" onclick="changePage(${i})">${i}</a>
            `);
        }

        // Next button
        const nextDisabled = currentPage === totalPages ? 'disabled' : '';
        pagination.append(`
            <a class="paginate_button next ${nextDisabled}" href="#" onclick="changePage(${currentPage + 1})">Next</a>
        `);
    }

    function changePage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            displayTransactions();
            updatePagination();
        }
    }

    function updateTableInfo() {
        const start = (currentPage - 1) * transactionsPerPage + 1;
        const end = Math.min(currentPage * transactionsPerPage, filteredTransactions.length);
        const total = filteredTransactions.length;

        $('#tableInfo').text(`Showing ${start} to ${end} of ${total} entries`);
    }

    function updateSummary() {
        const total = allTransactions.length;
        const completed = allTransactions.filter(t => t.status === 'completed').length;
        const pending = allTransactions.filter(t => t.status === 'pending').length;
        const totalAmount = allTransactions.reduce((sum, t) => sum + (parseFloat(t.amount) || 0), 0);

        $('#totalTransactions').text(total);
        $('#completedTransactions').text(completed);
        $('#pendingTransactions').text(pending);
        $('#totalAmount').text('₹' + totalAmount.toFixed(2));
    }

    function getTypeBadgeClass(type) {
        switch (type) {
            case 'advance': return 'primary';
            case 'installment': return 'info';
            case 'full_payment': return 'success';
            case 'refund': return 'warning';
            default: return 'secondary';
        }
    }

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'completed': return 'success';
            case 'pending': return 'warning';
            case 'cancelled': return 'danger';
            default: return 'secondary';
        }
    }

    // Global functions for onclick events
    window.changePage = changePage;
    window.viewTransaction = function(id) {
        window.location.href = '<?php echo base_url("transactions/plot_transactions/"); ?>' + id;
    };
    window.deleteTransaction = function(id) {
        if (confirm('Are you sure you want to delete this transaction?')) {
            $.ajax({
                url: '<?php echo base_url("transactions/delete_transaction/"); ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Transaction deleted successfully!');
                        loadTransactions();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the transaction.');
                }
            });
        }
    };
});
</script>
