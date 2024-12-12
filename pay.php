<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Options</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full height of the viewport */
        }
        .button {
            padding: 15px 25px;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007BFF; /* Blue */
            color: white;
        }
        .button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
</head>
<body>

    <h1>Select Payment Method</h1>
    <form action="" method="POST">
        <button type="submit" name="payment_type" value="online" class="button">Online Pay</button>
        <button type="submit" name="payment_type" value="offline" class="button">Offline Pay</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['payment_type'])) {
            $payment_type = $_POST['payment_type'];
            if ($payment_type === 'online') {
                // Redirect to online payment process
                header("Location: check.php");
                exit();
            } elseif ($payment_type === 'offline') {
                // Redirect to offline payment instructions or process
                header("Location: offline_payment.php");
                exit();
            }
        }
    }
    ?>

</body>
</html>
