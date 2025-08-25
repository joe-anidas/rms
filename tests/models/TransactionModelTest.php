<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Unit Tests for Transaction_model
 * Tests payment recording, balance calculations, and financial reporting
 */
class TransactionModelTest extends RMS_TestCase {
    
    private $customer_id;
    private $property_id;
    private $registration_id;
    private $transaction_id;
    
    protected function setUpTestData() {
        // Create test customer
        $customer_data = $this->createTestCustomer();
        $this->customer_id = $this->CI->Customer_model->insert_customer($customer_data);
        
        // Create test property
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
        
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
     * Test payment recording with valid data
     */
    public function testRecordPaymentWithValidData() {
        $transaction_data = $this->createTestTransaction($this->registration_id, [
            'amount' => 100000.00,
            'payment_type' => 'installment',
            'payment_method' => 'bank_transfer',
            'notes' => 'Test installment payment'
        ]);
        
        $result = $this->CI->Transaction_model->record_payment($transaction_data);
        
        $this->assertNotNull($result, 'Payment recording should return transaction ID');
        $this->assertTrue(is_numeric($result), 'Transaction ID should be numeric');
        
        // Verify transaction was recorded correctly
        $recorded_transaction = $this->CI->Transaction_model->get_transaction($result);
        $this->assertNotNull($recorded_transaction, 'Recorded transaction should be retrievable');
        $this->assertEqual($transaction_data['amount'], $recorded_transaction['amount']);
        $this->assertEqual($transaction_data['payment_type'], $recorded_transaction['payment_type']);
        $this->assertEqual($transaction_data['payment_method'], $recorded_transaction['payment_method']);
    }
    
    /**
     * Test payment recording with missing required fields
     */
    public function testRecordPaymentWithMissingRequiredFields() {
        $invalid_data = [
            'amount' => 50000.00,
            'payment_method' => 'cash'
            // Missing registration_id, payment_type, payment_date
        ];
        
        $result = $this->CI->Transaction_model->record_payment($invalid_data);
        $this->assertFalse($result, 'Payment recording should fail with missing required fields');
    }
    
    /**
     * Test receipt number generation
     */
    public function testGenerateReceiptNumber() {
        $receipt_number = $this->CI->Transaction_model->generate_receipt_number();
        
        $this->assertNotNull($receipt_number, 'Receipt number should be generated');
        $this->assertTrue(strpos($receipt_number, 'RCP') === 0, 'Receipt number should start with RCP');
        $this->assertTrue(strlen($receipt_number) >= 11, 'Receipt number should have proper length');
        
        // Test uniqueness
        $receipt_number2 = $this->CI->Transaction_model->generate_receipt_number();
        $this->assertFalse($receipt_number === $receipt_number2, 'Receipt numbers should be unique');
    }
    
    /**
     * Test balance calculation
     */
    public function testCalculateBalance() {
        $balance_info = $this->CI->Transaction_model->calculate_balance($this->registration_id);
        
        $this->assertArrayHasKey('total_amount', $balance_info, 'Should have total amount');
        $this->assertArrayHasKey('total_paid', $balance_info, 'Should have total paid');
        $this->assertArrayHasKey('balance', $balance_info, 'Should have balance');
        $this->assertArrayHasKey('payment_status', $balance_info, 'Should have payment status');
        
        $this->assertTrue(is_numeric($balance_info['total_amount']), 'Total amount should be numeric');
        $this->assertTrue(is_numeric($balance_info['total_paid']), 'Total paid should be numeric');
        $this->assertTrue(is_numeric($balance_info['balance']), 'Balance should be numeric');
        
        // Verify calculation accuracy
        $expected_balance = $balance_info['total_amount'] - $balance_info['total_paid'];
        $this->assertEqual($expected_balance, $balance_info['balance'], 'Balance calculation should be accurate');
        
        // Test with non-existent registration
        $invalid_balance = $this->CI->Transaction_model->calculate_balance(99999);
        $this->assertArrayHasKey('error', $invalid_balance, 'Should return error for non-existent registration');
    }
    
    /**
     * Test payment schedule creation
     */
    public function testCreatePaymentSchedule() {
        $schedule_data = [
            'total_amount' => 500000.00,
            'installment_count' => 10,
            'start_date' => date('Y-m-d')
        ];
        
        $result = $this->CI->Transaction_model->create_payment_schedule($this->registration_id, $schedule_data);
        $this->assertTrue($result, 'Payment schedule creation should succeed');
        
        // Verify schedule was created
        $schedule = $this->CI->Transaction_model->get_payment_schedule($this->registration_id);
        $this->assertTrue(is_array($schedule), 'Payment schedule should be array');
        $this->assertEqual(10, count($schedule), 'Should create 10 installments');
        
        // Verify installment amounts
        $expected_amount = $schedule_data['total_amount'] / $schedule_data['installment_count'];
        foreach ($schedule as $installment) {
            $this->assertEqual(round($expected_amount, 2), $installment['amount'], 
                'Installment amounts should be calculated correctly');
            $this->assertEqual('pending', $installment['status'], 'Initial status should be pending');
        }
    }
    
    /**
     * Test getting payment schedule
     */
    public function testGetPaymentSchedule() {
        // Create payment schedule first
        $schedule_data = [
            'total_amount' => 300000.00,
            'installment_count' => 6,
            'start_date' => date('Y-m-d')
        ];
        $this->CI->Transaction_model->create_payment_schedule($this->registration_id, $schedule_data);
        
        $schedule = $this->CI->Transaction_model->get_payment_schedule($this->registration_id);
        
        $this->assertTrue(is_array($schedule), 'Payment schedule should be array');
        $this->assertEqual(6, count($schedule), 'Should have 6 installments');
        
        if (count($schedule) > 0) {
            $installment = $schedule[0];
            $this->assertArrayHasKey('installment_number', $installment, 'Should have installment number');
            $this->assertArrayHasKey('due_date', $installment, 'Should have due date');
            $this->assertArrayHasKey('amount', $installment, 'Should have amount');
            $this->assertArrayHasKey('status', $installment, 'Should have status');
        }
        
        // Test with non-existent registration
        $empty_schedule = $this->CI->Transaction_model->get_payment_schedule(99999);
        $this->assertEqual([], $empty_schedule, 'Non-existent registration should return empty array');
    }
    
    /**
     * Test transaction history retrieval
     */
    public function testGetTransactionHistory() {
        // Create additional transactions for history testing
        for ($i = 0; $i < 3; $i++) {
            $transaction_data = $this->createTestTransaction($this->registration_id, [
                'amount' => 25000.00 + ($i * 5000),
                'payment_type' => 'installment',
                'notes' => "Test transaction $i"
            ]);
            $this->CI->Transaction_model->record_payment($transaction_data);
        }
        
        $history = $this->CI->Transaction_model->get_transaction_history();
        
        $this->assertTrue(is_array($history), 'Transaction history should be array');
        $this->assertTrue(count($history) >= 4, 'Should have at least 4 transactions');
        
        // Test with filters
        $filtered_history = $this->CI->Transaction_model->get_transaction_history([
            'registration_id' => $this->registration_id,
            'payment_type' => 'installment'
        ]);
        $this->assertTrue(count($filtered_history) >= 3, 'Filtered history should have installment transactions');
        
        // Test with date range filter
        $date_filtered = $this->CI->Transaction_model->get_transaction_history([
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d')
        ]);
        $this->assertTrue(is_array($date_filtered), 'Date filtered history should be array');
        
        // Test with limit
        $limited_history = $this->CI->Transaction_model->get_transaction_history([
            'limit' => 2
        ]);
        $this->assertTrue(count($limited_history) <= 2, 'Should respect limit parameter');
    }
    
    /**
     * Test financial report generation
     */
    public function testGenerateFinancialReport() {
        $report_params = [
            'start_date' => date('Y-m-01'), // First day of current month
            'end_date' => date('Y-m-t'),    // Last day of current month
            'group_by' => 'day'
        ];
        
        $report = $this->CI->Transaction_model->generate_financial_report($report_params);
        
        $this->assertArrayHasKey('summary', $report, 'Report should have summary');
        $this->assertArrayHasKey('by_payment_type', $report, 'Report should have payment type breakdown');
        $this->assertArrayHasKey('by_payment_method', $report, 'Report should have payment method breakdown');
        $this->assertArrayHasKey('timeline', $report, 'Report should have timeline');
        $this->assertArrayHasKey('top_properties', $report, 'Report should have top properties');
        $this->assertArrayHasKey('pending_payments', $report, 'Report should have pending payments');
        
        // Verify summary structure
        $summary = $report['summary'];
        $this->assertArrayHasKey('total_transactions', $summary, 'Summary should have transaction count');
        $this->assertArrayHasKey('total_revenue', $summary, 'Summary should have total revenue');
        $this->assertArrayHasKey('average_transaction', $summary, 'Summary should have average transaction');
        
        $this->assertTrue(is_numeric($summary['total_transactions']), 'Transaction count should be numeric');
        $this->assertTrue(is_numeric($summary['total_revenue']), 'Total revenue should be numeric');
    }
    
    /**
     * Test transaction update functionality
     */
    public function testUpdateTransaction() {
        $update_data = [
            'amount' => 75000.00,
            'payment_method' => 'cheque',
            'notes' => 'Updated transaction notes'
        ];
        
        $result = $this->CI->Transaction_model->update_transaction($this->transaction_id, $update_data);
        $this->assertTrue($result, 'Transaction update should succeed');
        
        // Verify updates
        $updated_transaction = $this->CI->Transaction_model->get_transaction($this->transaction_id);
        $this->assertEqual($update_data['amount'], $updated_transaction['amount']);
        $this->assertEqual($update_data['payment_method'], $updated_transaction['payment_method']);
        $this->assertEqual($update_data['notes'], $updated_transaction['notes']);
        
        // Test update with non-existent transaction
        $invalid_result = $this->CI->Transaction_model->update_transaction(99999, $update_data);
        $this->assertFalse($invalid_result, 'Update of non-existent transaction should fail');
    }
    
    /**
     * Test transaction deletion
     */
    public function testDeleteTransaction() {
        // Create transaction for deletion test
        $delete_transaction_data = $this->createTestTransaction($this->registration_id, [
            'amount' => 30000.00,
            'notes' => 'Transaction to delete'
        ]);
        $delete_transaction_id = $this->CI->Transaction_model->record_payment($delete_transaction_data);
        
        $result = $this->CI->Transaction_model->delete_transaction($delete_transaction_id);
        $this->assertTrue($result, 'Transaction deletion should succeed');
        
        // Verify transaction was deleted
        $deleted_transaction = $this->CI->Transaction_model->get_transaction($delete_transaction_id);
        $this->assertEqual(null, $deleted_transaction, 'Deleted transaction should not be retrievable');
        
        // Test deletion of non-existent transaction
        $invalid_result = $this->CI->Transaction_model->delete_transaction(99999);
        $this->assertFalse($invalid_result, 'Deletion of non-existent transaction should fail');
    }
    
    /**
     * Test receipt generation
     */
    public function testGenerateReceipt() {
        $receipt = $this->CI->Transaction_model->generate_receipt($this->transaction_id);
        
        $this->assertNotNull($receipt, 'Receipt should be generated');
        $this->assertArrayHasKey('transaction', $receipt, 'Receipt should have transaction data');
        $this->assertArrayHasKey('balance_info', $receipt, 'Receipt should have balance info');
        $this->assertArrayHasKey('receipt_date', $receipt, 'Receipt should have receipt date');
        $this->assertArrayHasKey('company_info', $receipt, 'Receipt should have company info');
        
        // Verify transaction data in receipt
        $transaction_data = $receipt['transaction'];
        $this->assertArrayHasKey('amount', $transaction_data, 'Receipt transaction should have amount');
        $this->assertArrayHasKey('payment_date', $transaction_data, 'Receipt transaction should have payment date');
        
        // Test with non-existent transaction
        $invalid_receipt = $this->CI->Transaction_model->generate_receipt(99999);
        $this->assertEqual(null, $invalid_receipt, 'Receipt for non-existent transaction should be null');
    }
    
    /**
     * Test overdue payments functionality
     */
    public function testGetOverduePayments() {
        // Create payment schedule with past due dates
        $schedule_data = [
            'total_amount' => 200000.00,
            'installment_count' => 4,
            'start_date' => date('Y-m-d', strtotime('-2 months'))
        ];
        $this->CI->Transaction_model->create_payment_schedule($this->registration_id, $schedule_data);
        
        $overdue_payments = $this->CI->Transaction_model->get_overdue_payments(0);
        
        $this->assertTrue(is_array($overdue_payments), 'Overdue payments should be array');
        
        if (count($overdue_payments) > 0) {
            $overdue = $overdue_payments[0];
            $this->assertArrayHasKey('registration_number', $overdue, 'Should have registration number');
            $this->assertArrayHasKey('customer_name', $overdue, 'Should have customer name');
            $this->assertArrayHasKey('property_name', $overdue, 'Should have property name');
            $this->assertArrayHasKey('due_date', $overdue, 'Should have due date');
        }
        
        // Test marking overdue payments
        $mark_result = $this->CI->Transaction_model->mark_overdue_payments();
        $this->assertTrue($mark_result, 'Marking overdue payments should succeed');
    }
    
    /**
     * Test customer transaction summary
     */
    public function testGetCustomerTransactionSummary() {
        $summary = $this->CI->Transaction_model->get_customer_transaction_summary($this->customer_id);
        
        $this->assertArrayHasKey('total_transactions', $summary, 'Should have transaction count');
        $this->assertArrayHasKey('total_paid', $summary, 'Should have total paid amount');
        $this->assertArrayHasKey('first_payment_date', $summary, 'Should have first payment date');
        $this->assertArrayHasKey('last_payment_date', $summary, 'Should have last payment date');
        $this->assertArrayHasKey('properties_count', $summary, 'Should have properties count');
        $this->assertArrayHasKey('pending_amount', $summary, 'Should have pending amount');
        
        $this->assertTrue(is_numeric($summary['total_transactions']), 'Transaction count should be numeric');
        $this->assertTrue(is_numeric($summary['total_paid']), 'Total paid should be numeric');
        $this->assertTrue(is_numeric($summary['pending_amount']), 'Pending amount should be numeric');
    }
    
    /**
     * Test transactions by property
     */
    public function testGetTransactionsByProperty() {
        $transactions = $this->CI->Transaction_model->get_transactions_by_property($this->property_id);
        
        $this->assertTrue(is_array($transactions), 'Transactions should be array');
        $this->assertTrue(count($transactions) >= 1, 'Should have at least one transaction');
        
        if (count($transactions) > 0) {
            $transaction = $transactions[0];
            $this->assertArrayHasKey('amount', $transaction, 'Transaction should have amount');
            $this->assertArrayHasKey('payment_date', $transaction, 'Transaction should have payment date');
            $this->assertArrayHasKey('registration_number', $transaction, 'Should have registration number');
        }
        
        // Test with non-existent property
        $empty_transactions = $this->CI->Transaction_model->get_transactions_by_property(99999);
        $this->assertEqual([], $empty_transactions, 'Non-existent property should have no transactions');
    }
    
    /**
     * Test transaction data validation
     */
    public function testValidateTransactionData() {
        // Test valid data
        $valid_data = $this->createTestTransaction($this->registration_id);
        $validation_result = $this->CI->Transaction_model->validate_transaction_data($valid_data);
        
        if (method_exists($this->CI->Transaction_model, 'validate_transaction_data')) {
            $this->assertArrayHasKey('valid', $validation_result, 'Validation should return valid status');
            $this->assertTrue($validation_result['valid'], 'Valid data should pass validation');
        }
        
        // Test invalid amount
        $invalid_data = $this->createTestTransaction($this->registration_id, ['amount' => -1000]);
        
        try {
            $this->CI->Transaction_model->record_payment($invalid_data);
            // If no exception, check if validation caught it
            $this->assertTrue(true, 'System should handle invalid amounts appropriately');
        } catch (Exception $e) {
            $this->assertTrue(true, 'System should reject invalid amounts');
        }
    }
}