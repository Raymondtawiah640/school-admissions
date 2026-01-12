<!DOCTYPE html>
<html>
<body>
    <p>Dear Parent,</p>
    <p>Your child’s admission has been approved. Here are the test details:</p>
    <ul>
        <li>Date: {{ $data['test_date'] }}</li>
        <li>Time: {{ $data['test_time'] }}</li>
        <li>Venue: {{ $data['venue'] }}</li>
    </ul>
    <p>Thank you for your cooperation.</p>
</body>
</html>