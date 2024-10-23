<?php
require_once 'C:\wamp64\www\mini2\payment\stripe-php-master\stripe-php-master\init.php'; // Make sure this path is correct

// Replace this with your actual Stripe secret key
$stripe_secret_key = "sk_test_HEQTluW9iTsG9yBawuEdOlmy00jro9OYVf"; 

// Set the Stripe API key
\Stripe\Stripe::setApiKey($stripe_secret_key);

try {
    // Create the Checkout Session
    $checkout_session = \Stripe\Checkout\Session::create([
        "mode" => "payment",
        "success_url" => "http://localhost/mini2/payment/success.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => "http://localhost/mini2/index.php",
        "locale" => "auto",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "usd",
                    "unit_amount" => 2000, // $20.00 (unit_amount is in cents)
                    "product_data" => [
                        "name" => "T-shirt"
                    ]
                ]
            ],
            [
                "quantity" => 2,
                "price_data" => [
                    "currency" => "inr",
                    "unit_amount" => 700, // $7.00 per hat
                    "product_data" => [
                        "name" => "Hat"
                    ]
                ]
            ]
        ]
    ]);

    // Redirect to the Stripe Checkout session
    http_response_code(303);
    header("Location: " . $checkout_session->url);

} catch (Exception $e) {
    // Handle any errors that occur during the session creation
    echo 'Error creating checkout session: ' . $e->getMessage();
}
?>



