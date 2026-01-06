<!DOCTYPE html>
<html>
<head>
    <title>Admission Test Notification</title>
</head>
<body>
    <p>Dear Parent,</p>
    <p>Your child’s admission has been approved. Here are the test details:</p>
    <ul>
        <li>Date: {{ $testDetails['test_date'] }}</li>
        <li>Time: {{ $testDetails['test_time'] }}</li>
        <li>Venue: {{ $testDetails['venue'] }}</li>
    </ul>
    <p>Thank you for your cooperation.</p>
</body>
</html>