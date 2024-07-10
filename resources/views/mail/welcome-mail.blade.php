<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Application</title>
</head>
<body>
    <h1>Welcome, {{ $user->firstname }}!</h1>
    <p>We are glad to have you with us.</p>
    <p>Your casillero number is: {{ $user->department }}</p>
    <p>Your address: {{ $user->address }}</p>
    <p>Your user ID is: {{ $userId }}</p>
    <p>Thank you for joining us!</p>
</body>
</html>