/**
 * Dashboard JavaScript Functions
 * Handles Chart.js interactions, AJAX calls, and dashboard functionality
 */

// Global chart instances
let dashboardCharts = {};

// Initialize dashboard when document is ready
$(document).ready(function() {
    initializeDashboard();
});

/**
 * Initialize dashboard functionality
 */
function initializeDashboard() {
    // Initialize date pickers if they exist
    if ($('#start_date').length && $('#end_date').length) {
        initializeDatePickers();
    }
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        if (typeof refreshDashboard === 'function') {
            console.log('Auto-refreshing dashboard data...');
            refreshDashboardData();
        }
    }, 300000); // 5 minutes
}

/**
 * Initialize date pickers with default values
 */
function initializeDatePickers() {
    // Set default date range to last 30 days if no values are set
    if (!$('#start_date').val()) {
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        $('#start_date').val(thirtyDaysAgo.toISOString().split('T')[0]);
    }
    
    if (!$('#end_date').val()) {
        const today = new Date();
        $('#end_date').val(today.toISOString().split('T')[0]);
    }
}

/**
 * Refresh dashboard data via AJAX
 */
function refreshDashboardData() {
    $.ajax({
        url: base_url + 'dashboard/get-data',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                updateDashboardMetrics(response.data);
                updateCharts(response.data);
                showNotification('Dashboard data refreshed successfully', 'success');
            } else {
                showNotification('Error refreshing dashboard: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Dashboard refresh error:', error);
            showNotification('Failed to refresh dashboard data', 'error');
        }
    });
}

/**
 * Update dashboard metric cards
 */
function updateDashboardMetrics(data) {
    // Update property metrics
    if (data.properties) {
        $('.property-total').text(data.properties.total);
        $('.property-sold').text(data.properties.by_status.sold || 0);
        $('.property-booked').text(data.properties.by_status.booked || 0);
        $('.property-unsold').text(data.properties.by_status.unsold || 0);
    }
    
    // Update customer metrics
    if (data.customers) {
        $('.customer-total').text(data.customers.total);
        $('.customer-active').text(data.customers.active);
        $('.customer-new').text(data.customers.new_this_month);
    }
    
    // Update revenue metrics
    if (data.revenue) {
        $('.revenue-collected').text('₹' + formatNumber(data.revenue.total_collected));
        $('.revenue-pending').text('₹' + formatNumber(data.revenue.pending));
    }
    
    // Update staff metrics
    if (data.staff) {
        $('.staff-total').text(data.staff.total);
        $('.staff-assigned').text(data.staff.assigned);
    }
}

/**
 * Update charts with new data
 */
function updateCharts(data) {
    // Update property status chart
    if (dashboardCharts.propertyStatus && data.properties) {
        dashboardCharts.propertyStatus.data.datasets[0].data = [
            data.properties.by_status.unsold || 0,
            data.properties.by_status.booked || 0,
            data.properties.by_status.sold || 0
        ];
        dashboardCharts.propertyStatus.update();
    }
    
    // Update revenue chart
    if (dashboardCharts.revenue && data.revenue && data.revenue.monthly) {
        dashboardCharts.revenue.data.labels = data.revenue.monthly.map(item => item.month);
        dashboardCharts.revenue.data.datasets[0].data = data.revenue.monthly.map(item => parseFloat(item.revenue));
        dashboardCharts.revenue.update();
    }
}

/**
 * Load analytics data via AJAX
 */
function loadAnalyticsData(type, dateRange = {}) {
    const endpoints = {
        'property': 'dashboard/ajax-property',
        'financial': 'dashboard/ajax-financial',
        'customer': 'dashboard/ajax-customer',
        'staff': 'dashboard/ajax-staff'
    };
    
    if (!endpoints[type]) {
        console.error('Invalid analytics type:', type);
        return;
    }
    
    showLoadingSpinner();
    
    $.ajax({
        url: base_url + endpoints[type],
        type: 'POST',
        data: dateRange,
        dataType: 'json',
        success: function(response) {
            hideLoadingSpinner();
            if (response.success) {
                updateAnalyticsCharts(type, response.data);
                showNotification('Analytics data loaded successfully', 'success');
            } else {
                showNotification('Error loading analytics: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            hideLoadingSpinner();
            console.error('Analytics load error:', error);
            showNotification('Failed to load analytics data', 'error');
        }
    });
}

/**
 * Update analytics charts with new data
 */
function updateAnalyticsCharts(type, data) {
    switch (type) {
        case 'property':
            updatePropertyAnalyticsCharts(data);
            break;
        case 'financial':
            updateFinancialAnalyticsCharts(data);
            break;
        case 'customer':
            updateCustomerAnalyticsCharts(data);
            break;
        case 'staff':
            updateStaffAnalyticsCharts(data);
            break;
    }
}

/**
 * Update property analytics charts
 */
function updatePropertyAnalyticsCharts(data) {
    // Implementation would depend on specific chart instances
    console.log('Updating property analytics charts', data);
}

/**
 * Update financial analytics charts
 */
function updateFinancialAnalyticsCharts(data) {
    // Implementation would depend on specific chart instances
    console.log('Updating financial analytics charts', data);
}

/**
 * Update customer analytics charts
 */
function updateCustomerAnalyticsCharts(data) {
    // Implementation would depend on specific chart instances
    console.log('Updating customer analytics charts', data);
}

/**
 * Update staff analytics charts
 */
function updateStaffAnalyticsCharts(data) {
    // Implementation would depend on specific chart instances
    console.log('Updating staff analytics charts', data);
}

/**
 * Export dashboard data
 */
function exportDashboard() {
    window.open(base_url + 'dashboard/export', '_blank');
}

/**
 * Show loading spinner
 */
function showLoadingSpinner() {
    if ($('#loadingSpinner').length === 0) {
        $('body').append('<div id="loadingSpinner" class="loading-overlay"><div class="spinner"></div></div>');
    }
    $('#loadingSpinner').show();
}

/**
 * Hide loading spinner
 */
function hideLoadingSpinner() {
    $('#loadingSpinner').hide();
}

/**
 * Show notification message
 */
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const notification = `
        <div class="alert ${alertClass} alert-dismissible fade show notification-alert" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Remove existing notifications
    $('.notification-alert').remove();
    
    // Add new notification
    $('body').prepend(notification);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.notification-alert').fadeOut();
    }, 5000);
}

/**
 * Format number with commas
 */
function formatNumber(num) {
    if (num === null || num === undefined) return '0';
    return parseFloat(num).toLocaleString('en-IN');
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return '��' + formatNumber(amount);
}

/**
 * Create responsive chart options
 */
function getResponsiveChartOptions(options = {}) {
    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };
    
    return Object.assign(defaultOptions, options);
}

/**
 * Create color palette for charts
 */
function getChartColors(count = 5) {
    const colors = [
        '#ff6384',
        '#36a2eb',
        '#ffcd56',
        '#4bc0c0',
        '#9966ff',
        '#ff9f40',
        '#ff6384',
        '#c9cbcf'
    ];
    
    return colors.slice(0, count);
}

/**
 * Initialize Chart.js defaults
 */
function initializeChartDefaults() {
    if (typeof Chart !== 'undefined') {
        Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#666';
    }
}

/**
 * Destroy chart if it exists
 */
function destroyChart(chartId) {
    if (dashboardCharts[chartId]) {
        dashboardCharts[chartId].destroy();
        delete dashboardCharts[chartId];
    }
}

/**
 * Store chart instance
 */
function storeChart(chartId, chartInstance) {
    dashboardCharts[chartId] = chartInstance;
}

// Initialize Chart.js defaults when script loads
initializeChartDefaults();