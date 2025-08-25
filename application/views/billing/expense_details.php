<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
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
        .form-group.full-width {
            grid-column: 1 / 4;
            width: 100%;
            margin-bottom: 20px;
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
        }
        textarea {
            padding: 10px 12px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
            font-family: inherit;
            min-height: 100px;
            resize: vertical;
            width: 100%;
            box-sizing: border-box;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .btn {
            padding: 11px 32px;
            border-radius: 5px;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            border: none;
            transition: all 0.18s;
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
        @media (max-width: 800px) {
            .form-row { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: 1 / 2; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="breadcrumb">
            <a href="/expense-details">Expense Details</a> / Add Expense Details
        </div>
        <div class="page-title">Add Expense Details</div>
        <form action="/expense-details/store" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nagar_garden_name">Select Nagar/Garden Name</label>
                    <select id="nagar_garden_name" name="nagar_garden_name" required>
                        <option value="">Select Nagar / Garden</option>
                        <!-- Dynamic options go here -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="expense_type">Select Expense</label>
                    <select id="expense_type" name="expense_type" required>
                        <option value="">Select Expense</option>
                        <!-- Dynamic options go here -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="expense_amount">Expense</label>
                    <input 
                        type="number" 
                        id="expense_amount" 
                        name="expense_amount" 
                        placeholder="Expense" 
                        step="0.01" 
                        min="0" 
                        required>
                </div>
            </div>
            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Description" 
                    required></textarea>
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="/expense-details" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
