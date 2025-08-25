<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee Salary</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            margin: 0;
        }
        
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            padding: 30px;
        }
        
        .breadcrumb {
            font-size: 14px;
            margin-bottom: 10px;
            color: #a78bfa;
        }
        
        .breadcrumb a {
            color: #a78bfa;
            text-decoration: none;
            font-weight: 500;
        }
        
        .page-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        input, select {
            padding: 10px 12px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
            transition: border-color 0.2s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #a78bfa;
        }
        
        input[type="date"] {
            position: relative;
        }
        
        input[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 35px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 11px 32px;
            border-radius: 5px;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            border: none;
            transition: all 0.18s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: #a78bfa;
            color: #fff;
        }
        
        .btn-primary:hover {
            background: #7c3aed;
        }
        
        .btn-secondary {
            background: #fff;
            color: #dc2626;
            border: 1px solid #dc2626;
        }
        
        .btn-secondary:hover {
            background: #f3f4f6;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .container {
                padding: 20px;
            }
            
            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="breadcrumb">
            <a href="/employee-salary">Employee Salary</a> / Add Employee Salary
        </div>
        
        <div class="page-title">Add Employee Salary</div>
        
        <form action="/employee-salary/store" method="POST" id="employeeSalaryForm">
            <!-- First Row -->
            <div class="form-row">
                <div class="form-group">
                    <label for="salary_duration_start">Salary Duration Start</label>
                    <input type="date" 
                           id="salary_duration_start" 
                           name="salary_duration_start" 
                           placeholder="dd-mm-yyyy"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="salary_duration_end">Salary Duration End</label>
                    <input type="date" 
                           id="salary_duration_end" 
                           name="salary_duration_end" 
                           placeholder="dd-mm-yyyy"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="employee_name">Employee Name</label>
                    <select id="employee_name" name="employee_name" required>
                        <option value="">Select Employee Name</option>
                        <!-- Sample options - replace with dynamic PHP data -->
                        <option value="John Doe" data-employee-id="EMP001">John Doe</option>
                        <option value="Jane Smith" data-employee-id="EMP002">Jane Smith</option>
                        <option value="Mike Johnson" data-employee-id="EMP003">Mike Johnson</option>
                    </select>
                </div>
            </div>
            
            <!-- Second Row -->
            <div class="form-row">
                <div class="form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" 
                           id="employee_id" 
                           name="employee_id" 
                           placeholder="Employee ID"
                           readonly 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="employee_id_alt">Employee ID</label>
                    <input type="text" 
                           id="employee_id_alt" 
                           name="employee_id_alt" 
                           placeholder="Employee ID"
                           readonly 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="net_salary_amount">Net Salary Amount</label>
                    <input type="number" 
                           id="net_salary_amount" 
                           name="net_salary_amount" 
                           placeholder="Net Salary Amount"
                           step="0.01" 
                           min="0"
                           required>
                </div>
            </div>
            
            <!-- Third Row -->
            <div class="form-row">
                <div class="form-group">
                    <label for="advance_amount">Advance Amount</label>
                    <input type="number" 
                           id="advance_amount" 
                           name="advance_amount" 
                           placeholder="Advance Amount"
                           step="0.01" 
                           min="0">
                </div>
                
                <div class="form-group">
                    <label for="balance_amount">Balance Amount</label>
                    <input type="number" 
                           id="balance_amount" 
                           name="balance_amount" 
                           placeholder="Balance Amount"
                           step="0.01" 
                           min="0"
                           readonly>
                </div>
                
                <div class="form-group">
                    <!-- Empty third column for spacing -->
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
            </div>
        </form>
    </div>

    <script>
        // Auto-fill employee details when employee name is selected
        document.getElementById('employee_name').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const employeeId = selectedOption.getAttribute('data-employee-id');
            
            const employeeIdField = document.getElementById('employee_id');
            const employeeIdAltField = document.getElementById('employee_id_alt');
            
            if (employeeId) {
                employeeIdField.value = employeeId;
                employeeIdAltField.value = employeeId;
            } else {
                employeeIdField.value = '';
                employeeIdAltField.value = '';
            }
        });
        
        // Calculate balance amount automatically
        function calculateBalance() {
            const netSalary = parseFloat(document.getElementById('net_salary_amount').value) || 0;
            const advance = parseFloat(document.getElementById('advance_amount').value) || 0;
            const balance = netSalary - advance;
            
            document.getElementById('balance_amount').value = balance >= 0 ? balance.toFixed(2) : '0.00';
        }
        
        // Add event listeners for automatic balance calculation
        document.getElementById('net_salary_amount').addEventListener('input', calculateBalance);
        document.getElementById('advance_amount').addEventListener('input', calculateBalance);
        
        // Back button function
        function goBack() {
            window.history.back();
        }
        
        // Set default dates (current month)
        window.addEventListener('load', function() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            document.getElementById('salary_duration_start').value = firstDay.toISOString().split('T')[0];
            document.getElementById('salary_duration_end').value = lastDay.toISOString().split('T')[0];
        });
        
        // Form validation
        document.getElementById('employeeSalaryForm').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('salary_duration_start').value);
            const endDate = new Date(document.getElementById('salary_duration_end').value);
            const employeeName = document.getElementById('employee_name').value;
            const netSalary = document.getElementById('net_salary_amount').value;
            
            // Validate required fields
            if (!employeeName || !netSalary) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            // Validate date range
            if (startDate >= endDate) {
                e.preventDefault();
                alert('Salary duration end date must be after start date.');
                return false;
            }
            
            // Validate salary amount
            if (parseFloat(netSalary) <= 0) {
                e.preventDefault();
                alert('Net salary amount must be greater than 0.');
                return false;
            }
        });
    </script>
</body>
</html>
