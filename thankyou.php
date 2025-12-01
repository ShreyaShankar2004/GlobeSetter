<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .thankyou-message {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="thankyou-message">
        Thank you for booking!
    </div>

    <!-- Redirect to Home.html after 5 seconds -->
    <script>
        setTimeout(function() {
            window.location.href = 'Home.html';
        }, 1000); // 5 seconds delay
    </script>

</body>
</html>
