<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
</head>
<body>
    <p>Hello {{$name}}</p>
    <p>Congrats!!!</p> 
    <p>Your loan request has been approved, following are the EMI details:</p>
    <table border="1" cellspacing="2" cellpadding="10">
        <thead>
            <tr>
                <th>Creating Date</th>
                <th>Finish Date</th>
                <th>No. of EMIs</th>
                <th>Loan Amount</th>
                <th>Pending EMIs</th>
                <th>Outstanding Amount</th>
                <th>Monthly EMI Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$emis['creation_date']}}</td>
                <td>{{$emis['finish_date']}}</td>
                <td>{{$emis['total_emis']}}</td>
                <td>{{$emis['loan_amount']}}</td>
                <td>{{$emis['pending_emis']}}</td>
                <td>{{$emis['outstanding_amount']}}</td>
                <td>{{$emis['monthly_emi_amount']}}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>