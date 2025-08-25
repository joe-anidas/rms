<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Salary Advance</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            margin: 0;
        }
        
        .container {
            max-width: 900px;
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
            margin-bottom: 30px;
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
            margin-top: 20px;
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
            color: #6c757d;
            border: 1px solid #dadce0;
        }
        
        .btn-secondary:hover {
            background: #f3f4f6;
        }
        
        .btn-cancel {
            background: #fff;
            color: #dc2626;
            border: 1px solid #dc2626;
        }
        
        .btn-cancel:hover {
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
            <a href="/salary-advances">Salary Advance</a> / Add Salary Advance
        </div>
        
        <div class="page-title">Add Salary Advance</div>
        
        <form action="/salary-advance/store" method="POST" id="salaryAdvanceForm">
            <div class="form-row">
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
                    <label for="advance_amount">Advance Amount</label>
                    <input type="number" 
                           id="advance_amount" 
                           name="advance_amount" 
                           placeholder="Advance Amount"
                           step="0.01" 
                           min="0"
                           required>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                <button type="button" class="btn btn-cancel" onclick="cancelForm()">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        // Auto-fill employee ID when employee name is selected
        document.getElementById('employee_name').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const employeeId = selectedOption.getAttribute('data-employee-id');
            const employeeIdField = document.getElementById('employee_id');
            
            if (employeeId) {
                employeeIdField.value = employeeId;
            } else {
                employeeIdField.value = '';
            }
        });
        
        // Reset form function
        function resetForm() {
            document.getElementById('salaryAdvanceForm').reset();
            document.getElementById('employee_id').value = '';
        }
        
        // Cancel function
        function cancelForm() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/salary-advances';
            }
        }
        
        // Form validation
        document.getElementById('salaryAdvanceForm').addEventListener('submit', function(e) {
            const employeeName = document.getElementById('employee_name').value;
            const employeeId = document.getElementById('employee_id').value;
            const advanceAmount = document.getElementById('advance_amount').value;
            
            if (!employeeName || !employeeId || !advanceAmount) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            if (parseFloat(advanceAmount) <= 0) {
                e.preventDefault();
                alert('Advance amount must be greater than 0.');
                return false;
            }
        });
    </script>
</body>
</html>
