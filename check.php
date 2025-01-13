<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['job_id'])) {
    $cost = $_POST['cost'];

    // Round the cost up to the next integer using ceil
    $cost = ceil($cost);

    // Store the cost in the session to retrieve it later
    $_SESSION['payment_cost'] = $cost;

} else {
    echo "No data found";
    exit();
}

require_once 'C:\wamp64\www\mini2\stripe-php-master\stripe-php-master\init.php'; // Make sure this path is correct
$stripe_secret_key = "your api key"; 

\Stripe\Stripe::setApiKey($stripe_secret_key);

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        "mode" => "payment",
        "success_url" => "http://localhost/mini2/success.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => "http://localhost/mini2/home.php",
        "locale" => "auto",
        "billing_address_collection" => "required", 
        "shipping_address_collection" => [
            "allowed_countries" => ['IN'],
        ],
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "inr",
                    "unit_amount" => $cost * 100, // Stripe expects the amount in cents/paise
                    "product_data" => [
                        "name" => "Service Payment",
                    ],
                ],
            ],
        ],
    ]);

    http_response_code(303);
    header("Location: " . $checkout_session->url);

} catch (Exception $e) {
    echo 'Error creating checkout session: ' . $e->getMessage();
}
?>
