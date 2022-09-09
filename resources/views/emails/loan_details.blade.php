<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
</head>
<body>
    <p>Hello {{$name}},</p>
    @if(count($emis))
    <p>Congrats!!!</p> 
    <p>Your loan request has been approved, following are the EMI details. Please find the details in the attachment.</p>
    @else
    <p>Sorry to inform you that your loan request has been rejected due to reason : {{$reject_reason}}</p>
    @endif
</body>
</html>