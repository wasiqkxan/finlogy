<!DOCTYPE html>
<html>
<head>
    <title>Monthly Transaction Report</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Monthly Transaction Report - {{ $month }}</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->date }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->category->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
