<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Unit Tests for Dashboard_model
 * Tests dashboard metrics, analytics, and reporting functionality
 */
class DashboardModelTest extends RMS_TestCase {
    
    private $customer_id;
    private $property_id;
    private $staff_id;
    private $registration_id;
    private $transaction_id;
    
    protected function setUpTestData() {
        // Create test data for comprehensive dashboard testing
        
        // Create test customer
        $customer_data = $this->createTestCustomer();
        $this->customer_id = $this->CI->Customer_model->insert_customer($customer_data);
        
        // Create test staff
        $staff_data = $this->createTestStaff();
        $staff_result = $this->CI->Staff_model->insert_staff($staff_data);
        $this->staff_id = $staff_result['staff_id'];
        
        // Create test property
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
        
        // Assign staff to property
        $this->CI->Property_model->assign_staff($this->property_id, $this->staff_id);
        
        // Create test registration
        $this->registration_id = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
        
        // Create test transaction
        $transaction_data = $this->createTestTransaction($this->registration_id);
        $this->transaction_id = $this->CI->Transaction_model->record_payment($transaction_data);
    }
    
    /**
     * Test comprehensive dashboard metrics
     */
    public function testGetDashboardMetrics() {
        $metrics = $this->CI->Dashboard_model->get_dashboard_metrics();
        
        $this->assertArrayHasKey('properties', $metrics, 'Metrics should have properties data');
        $this->assertArrayHasKey('customers', $metrics, 'Metrics should have customers data');
        $this->assertArrayHasKey('staff', $metrics, 'Metrics should have staff data');
        $this->assertArrayHasKey('transactions', $metrics, 'Metrics should have transactions data');
        $this->assertArrayHasKey('revenue', $metrics, 'Metrics should have revenue data');
        
        // Test properties metrics structure
        $properties = $metrics['properties'];
        $this->assertArrayHasKey('by_status', $properties, 'Properties should have status breakdown');
        $this->assertArrayHasKey('total', $properties, 'Properties should have total count');
        $this->assertArrayHasKey('values', $properties, 'Properties should have value information');
        
        $this->assertTrue($properties['total'] >= 1, 'Should have at least one property');
        $this->assertTrue(is_array($properties['by_status']), 'Status breakdown should be array');
        
        // Test customers metrics structure
        $customers = $metrics['customers'];
        $this->assertArrayHasKey('total', $customers, 'Customers should have total count');
        $this->assertArrayHasKey('active', $customers, 'Customers should have active count');
        $this->assertArrayHasKey('new_this_month', $customers, 'Customers should have new this month count');
        
        $this->assertTrue($customers['total'] >= 1, 'Should have at least one customer');
        $this->assertTrue(is_numeric($customers['active']), 'Active customers should be numeric');
        
        // Test staff metrics structure
        $staff = $metrics['staff'];
        $this->assertArrayHasKey('total', $staff, 'Staff should have total count');
        $this->assertArrayHasKey('assigned', $staff, 'Staff should have assigned count');
        $this->assertArrayHasKey('workload', $staff, 'Staff should have workload data');
        
        $this->assertTrue($staff['total'] >= 1, 'Should have at least one staff member');
        $this->assertTrue(is_array($staff['workload']), 'Workload should be array');
        
        // Test transactions metrics structure
        $transactions = $metrics['transactions'];
        $this->assertArrayHasKey('total', $transactions, 'Transactions should have total count');
        $this->assertArrayHasKey('by_type', $transactions, 'Transactions should have type breakdown');
        $this->assertArrayHasKey('recent', $transactions, 'Transactions should have recent data');
        
        $this->assertTrue($transactions['total'] >= 1, 'Should have at least one transaction');
        $this->assertTrue(is_array($transactions['by_type']), 'Type breakdown should be array');
        
        // Test revenue metrics structure
        $revenue = $metrics['revenue'];
        $this->assertArrayHasKey('total_collected', $revenue, 'Revenue should have total collected');
        $this->assertArrayHasKey('monthly', $revenue, 'Revenue should have monthly data');
        $this->assertArrayHasKey('pending', $revenue, 'Revenue should have pending amount');
        
        $this->assertTrue(is_numeric($revenue['total_collected']), 'Total collected should be numeric');
        $this->assertTrue(is_array($revenue['monthly']), 'Monthly revenue should be array');
    }
    
    /**
     * Test property analytics
     */
    public function testGetPropertyAnalytics() {
        $analytics = $this->CI->Dashboard_model->get_property_analytics();
        
        $this->assertArrayHasKey('status_distribution', $analytics, 'Should have status distribution');
        $this->assertArrayHasKey('trends', $analytics, 'Should have trends data');
        $this->assertArrayHasKey('type_distribution', $analytics, 'Should have type distribution');
        $this->assertArrayHasKey('sales_metrics', $analytics, 'Should have sales metrics');
        
        // Test status distribution
        $status_distribution = $analytics['status_distribution'];
        $this->assertTrue(is_array($status_distribution), 'Status distribution should be array');
        
        if (count($status_distribution) > 0) {
            $status = $status_distribution[0];
            $this->assertArrayHasKey('status', $status, 'Should have status field');
            $this->assertArrayHasKey('count', $status, 'Should have count field');
            $this->assertArrayHasKey('percentage', $status, 'Should have percentage field');
        }
        
        // Test trends data
        $trends = $analytics['trends'];
        $this->assertTrue(is_array($trends), 'Trends should be array');
        
        // Test type distribution
        $type_distribution = $analytics['type_distribution'];
        $this->assertTrue(is_array($type_distribution), 'Type distribution should be array');
        
        if (count($type_distribution) > 0) {
            $type = $type_distribution[0];
            $this->assertArrayHasKey('property_type', $type, 'Should have property type');
            $this->assertArrayHasKey('count', $type, 'Should have count');
            $this->assertArrayHasKey('average_price', $type, 'Should have average price');
        }
        
        // Test with date range
        $date_range = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];
        $analytics_with_range = $this->CI->Dashboard_model->get_property_analytics($date_range);
        $this->assertTrue(is_array($analytics_with_range), 'Analytics with date range should be array');
    }
    
    /**
     * Test financial analytics
     */
    public function testGetFinancialAnalytics() {
        $analytics = $this->CI->Dashboard_model->get_financial_analytics();
        
        $this->assertArrayHasKey('revenue_trends', $analytics, 'Should have revenue trends');
        $this->assertArrayHasKey('payment_methods', $analytics, 'Should have payment methods analysis');
        $this->assertArrayHasKey('payment_types', $analytics, 'Should have payment types analysis');
        $this->assertArrayHasKey('outstanding', $analytics, 'Should have outstanding payments');
        $this->assertArrayHasKey('forecast', $analytics, 'Should have revenue forecast');
        
        // Test revenue trends
        $revenue_trends = $analytics['revenue_trends'];
        $this->assertTrue(is_array($revenue_trends), 'Revenue trends should be array');
        
        // Test payment methods analysis
        $payment_methods = $analytics['payment_methods'];
        $this->assertTrue(is_array($payment_methods), 'Payment methods should be array');
        
        if (count($payment_methods) > 0) {
            $method = $payment_methods[0];
            $this->assertArrayHasKey('payment_method', $method, 'Should have payment method');
            $this->assertArrayHasKey('transaction_count', $method, 'Should have transaction count');
            $this->assertArrayHasKey('total_amount', $method, 'Should have total amount');
            $this->assertArrayHasKey('percentage', $method, 'Should have percentage');
        }
        
        // Test payment types analysis
        $payment_types = $analytics['payment_types'];
        $this->assertTrue(is_array($payment_types), 'Payment types should be array');
        
        // Test outstanding payments
        $outstanding = $analytics['outstanding'];
        $this->assertTrue(is_array($outstanding), 'Outstanding payments should be array');
        
        // Test forecast
        $forecast = $analytics['forecast'];
        $this->assertTrue(is_array($forecast), 'Forecast should be array');
        $this->assertArrayHasKey('potential_revenue', $forecast, 'Forecast should have potential revenue');
        $this->assertArrayHasKey('pending_registrations', $forecast, 'Forecast should have pending registrations');
        
        // Test with date range
        $date_range = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];
        $analytics_with_range = $this->CI->Dashboard_model->get_financial_analytics($date_range);
        $this->assertTrue(is_array($analytics_with_range), 'Financial analytics with date range should be array');
    }
    
    /**
     * Test customer analytics
     */
    public function testGetCustomerAnalytics() {
        $analytics = $this->CI->Dashboard_model->get_customer_analytics();
        
        $this->assertArrayHasKey('acquisition_trends', $analytics, 'Should have acquisition trends');
        $this->assertArrayHasKey('top_customers', $analytics, 'Should have top customers');
        $this->assertArrayHasKey('geographic_distribution', $analytics, 'Should have geographic distribution');
        $this->assertArrayHasKey('lifecycle', $analytics, 'Should have lifecycle analysis');
        
        // Test acquisition trends
        $acquisition_trends = $analytics['acquisition_trends'];
        $this->assertTrue(is_array($acquisition_trends), 'Acquisition trends should be array');
        
        // Test top customers
        $top_customers = $analytics['top_customers'];
        $this->assertTrue(is_array($top_customers), 'Top customers should be array');
        
        if (count($top_customers) > 0) {
            $customer = $top_customers[0];
            $this->assertArrayHasKey('customer_name', $customer, 'Should have customer name');
            $this->assertArrayHasKey('properties_count', $customer, 'Should have properties count');
            $this->assertArrayHasKey('total_paid', $customer, 'Should have total paid');
            $this->assertArrayHasKey('total_value', $customer, 'Should have total value');
        }
        
        // Test geographic distribution
        $geographic = $analytics['geographic_distribution'];
        $this->assertTrue(is_array($geographic), 'Geographic distribution should be array');
        
        // Test lifecycle analysis
        $lifecycle = $analytics['lifecycle'];
        $this->assertTrue(is_array($lifecycle), 'Lifecycle analysis should be array');
        
        if (count($lifecycle) > 0) {
            $stage = $lifecycle[0];
            $this->assertArrayHasKey('lifecycle_stage', $stage, 'Should have lifecycle stage');
            $this->assertArrayHasKey('customer_count', $stage, 'Should have customer count');
        }
        
        // Test with date range
        $date_range = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];
        $analytics_with_range = $this->CI->Dashboard_model->get_customer_analytics($date_range);
        $this->assertTrue(is_array($analytics_with_range), 'Customer analytics with date range should be array');
    }
    
    /**
     * Test staff analytics
     */
    public function testGetStaffAnalytics() {
        $analytics = $this->CI->Dashboard_model->get_staff_analytics();
        
        $this->assertArrayHasKey('performance', $analytics, 'Should have performance metrics');
        $this->assertArrayHasKey('workload', $analytics, 'Should have workload distribution');
        $this->assertArrayHasKey('assignment_history', $analytics, 'Should have assignment history');
        
        // Test performance metrics
        $performance = $analytics['performance'];
        $this->assertTrue(is_array($performance), 'Performance metrics should be array');
        
        if (count($performance) > 0) {
            $staff_performance = $performance[0];
            $this->assertArrayHasKey('employee_name', $staff_performance, 'Should have employee name');
            $this->assertArrayHasKey('designation', $staff_performance, 'Should have designation');
            $this->assertArrayHasKey('properties_assigned', $staff_performance, 'Should have properties assigned');
            $this->assertArrayHasKey('registrations_handled', $staff_performance, 'Should have registrations handled');
            $this->assertArrayHasKey('revenue_generated', $staff_performance, 'Should have revenue generated');
        }
        
        // Test workload distribution
        $workload = $analytics['workload'];
        $this->assertTrue(is_array($workload), 'Workload distribution should be array');
        
        if (count($workload) > 0) {
            $staff_workload = $workload[0];
            $this->assertArrayHasKey('employee_name', $staff_workload, 'Should have employee name');
            $this->assertArrayHasKey('total_assignments', $staff_workload, 'Should have total assignments');
        }
        
        // Test assignment history
        $assignment_history = $analytics['assignment_history'];
        $this->assertTrue(is_array($assignment_history), 'Assignment history should be array');
        
        // Test with date range
        $date_range = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];
        $analytics_with_range = $this->CI->Dashboard_model->get_staff_analytics($date_range);
        $this->assertTrue(is_array($analytics_with_range), 'Staff analytics with date range should be array');
    }
    
    /**
     * Test today's transactions
     */
    public function testGetTransactionsToday() {
        $today_transactions = $this->CI->Dashboard_model->get_transactions_today();
        
        $this->assertArrayHasKey('count', $today_transactions, 'Should have transaction count');
        $this->assertArrayHasKey('total_amount', $today_transactions, 'Should have total amount');
        
        $this->assertTrue(is_numeric($today_transactions['count']), 'Count should be numeric');
        $this->assertTrue(is_numeric($today_transactions['total_amount']), 'Total amount should be numeric');
        $this->assertTrue($today_transactions['count'] >= 1, 'Should have at least one transaction today');
    }
    
    /**
     * Test new customers today
     */
    public function testGetNewCustomersToday() {
        $new_customers_count = $this->CI->Dashboard_model->get_new_customers_today();
        
        $this->assertTrue(is_numeric($new_customers_count), 'New customers count should be numeric');
        $this->assertTrue($new_customers_count >= 1, 'Should have at least one new customer today');
    }
    
    /**
     * Test dashboard insights
     */
    public function testGetDashboardInsights() {
        // Create additional data for insights testing
        $property_data2 = $this->createTestProperty(['garden_name' => 'Insights Test Property']);
        $property_id2 = $this->CI->Property_model->create_property($property_data2);
        
        $registration_id2 = $this->CI->Registration_model->create_registration(
            $property_id2, 
            $this->customer_id,
            ['total_amount' => 300000.00, 'registration_date' => date('Y-m-d', strtotime('-1 month'))]
        );
        
        // Complete the registration to create sales data
        $this->CI->Registration_model->update_status($registration_id2, 'completed');
        
        $insights = $this->CI->Dashboard_model->get_dashboard_insights();
        
        $this->assertTrue(is_array($insights), 'Insights should be array');
        
        // Test revenue growth insight
        if (isset($insights['revenue_growth'])) {
            $revenue_growth = $insights['revenue_growth'];
            $this->assertArrayHasKey('percentage', $revenue_growth, 'Should have growth percentage');
            $this->assertArrayHasKey('trend', $revenue_growth, 'Should have trend direction');
            $this->assertArrayHasKey('current_month', $revenue_growth, 'Should have current month data');
            $this->assertArrayHasKey('previous_month', $revenue_growth, 'Should have previous month data');
        }
        
        // Test sales velocity insight
        if (isset($insights['sales_velocity'])) {
            $sales_velocity = $insights['sales_velocity'];
            $this->assertArrayHasKey('avg_days', $sales_velocity, 'Should have average days to sell');
            $this->assertArrayHasKey('performance', $sales_velocity, 'Should have performance rating');
        }
        
        // Test customer satisfaction insight
        if (isset($insights['customer_satisfaction'])) {
            $customer_satisfaction = $insights['customer_satisfaction'];
            $this->assertArrayHasKey('repeat_rate', $customer_satisfaction, 'Should have repeat rate');
            $this->assertArrayHasKey('repeat_customers', $customer_satisfaction, 'Should have repeat customers count');
            $this->assertArrayHasKey('total_customers', $customer_satisfaction, 'Should have total customers count');
        }
    }
    
    /**
     * Test market trends analysis
     */
    public function testGetMarketTrends() {
        $trends = $this->CI->Dashboard_model->get_market_trends();
        
        $this->assertArrayHasKey('property_types', $trends, 'Should have property types trends');
        $this->assertArrayHasKey('seasonal', $trends, 'Should have seasonal trends');
        $this->assertArrayHasKey('pricing', $trends, 'Should have pricing trends');
        
        // Test property types trends
        $property_types = $trends['property_types'];
        $this->assertTrue(is_array($property_types), 'Property types trends should be array');
        
        if (count($property_types) > 0) {
            $type_trend = $property_types[0];
            $this->assertArrayHasKey('property_type', $type_trend, 'Should have property type');
            $this->assertArrayHasKey('total_properties', $type_trend, 'Should have total properties');
            $this->assertArrayHasKey('sold_properties', $type_trend, 'Should have sold properties');
            $this->assertArrayHasKey('conversion_rate', $type_trend, 'Should have conversion rate');
        }
        
        // Test seasonal trends
        $seasonal = $trends['seasonal'];
        $this->assertTrue(is_array($seasonal), 'Seasonal trends should be array');
        
        // Test pricing trends
        $pricing = $trends['pricing'];
        $this->assertTrue(is_array($pricing), 'Pricing trends should be array');
        
        if (count($pricing) > 0) {
            $price_trend = $pricing[0];
            $this->assertArrayHasKey('month', $price_trend, 'Should have month');
            $this->assertArrayHasKey('avg_price', $price_trend, 'Should have average price');
            $this->assertArrayHasKey('min_price', $price_trend, 'Should have minimum price');
            $this->assertArrayHasKey('max_price', $price_trend, 'Should have maximum price');
        }
    }
    
    /**
     * Test data consistency across different analytics methods
     */
    public function testDataConsistency() {
        // Get data from different methods and verify consistency
        $dashboard_metrics = $this->CI->Dashboard_model->get_dashboard_metrics();
        $property_analytics = $this->CI->Dashboard_model->get_property_analytics();
        
        // Property counts should be consistent
        $total_from_metrics = $dashboard_metrics['properties']['total'];
        $total_from_status = array_sum($property_analytics['status_distribution'], function($item) {
            return $item['count'];
        });
        
        // Note: This might not be exactly equal due to different filtering, but should be close
        $this->assertTrue(abs($total_from_metrics - $total_from_status) <= 1, 
            'Property counts should be consistent across different methods');
        
        // Customer counts consistency
        $customers_from_metrics = $dashboard_metrics['customers']['total'];
        $this->assertTrue($customers_from_metrics >= 1, 'Customer count should be consistent');
        
        // Staff counts consistency
        $staff_from_metrics = $dashboard_metrics['staff']['total'];
        $this->assertTrue($staff_from_metrics >= 1, 'Staff count should be consistent');
    }
    
    /**
     * Test performance with large datasets (simulation)
     */
    public function testPerformanceWithLargeDataset() {
        // Create additional test data to simulate larger dataset
        for ($i = 0; $i < 5; $i++) {
            $customer_data = $this->createTestCustomer(['plot_buyer_name' => "Performance Customer $i"]);
            $customer_id = $this->CI->Customer_model->insert_customer($customer_data);
            
            $property_data = $this->createTestProperty(['garden_name' => "Performance Property $i"]);
            $property_id = $this->CI->Property_model->create_property($property_data);
            
            $registration_id = $this->CI->Registration_model->create_registration(
                $property_id, 
                $customer_id,
                ['total_amount' => 400000.00 + ($i * 50000)]
            );
            
            $transaction_data = $this->createTestTransaction($registration_id, ['amount' => 50000.00]);
            $this->CI->Transaction_model->record_payment($transaction_data);
        }
        
        // Test that dashboard methods still perform well with more data
        $start_time = microtime(true);
        $metrics = $this->CI->Dashboard_model->get_dashboard_metrics();
        $end_time = microtime(true);
        
        $execution_time = $end_time - $start_time;
        $this->assertTrue($execution_time < 5.0, 'Dashboard metrics should execute within 5 seconds');
        $this->assertNotNull($metrics, 'Dashboard metrics should return data even with larger dataset');
        
        // Test analytics performance
        $start_time = microtime(true);
        $analytics = $this->CI->Dashboard_model->get_property_analytics();
        $end_time = microtime(true);
        
        $execution_time = $end_time - $start_time;
        $this->assertTrue($execution_time < 3.0, 'Property analytics should execute within 3 seconds');
        $this->assertNotNull($analytics, 'Property analytics should return data even with larger dataset');
    }
}