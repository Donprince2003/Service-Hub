<?php
require_once 'C:\wamp64\www\mini2\stripe-php-master\stripe-php-master\init.php'; // Make sure this path is correct
$stripe_secret_key = "sk_test_tR3PYbcVNZZ796tH88S4VQ2u"; 

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
                    "unit_amount" => 2000, 
                    "product_data" => [
                        "name" => "T-shirt"
                    ]
                ]
            ],
            [
                "quantity" => 2,
                "price_data" => [
                    "currency" => "inr",
                    "unit_amount" => 700, 
                    "product_data" => [
                        "name" => "Hat"
                    ]
                ]
            ]
        ]
    ]);
    http_response_code(303);
    header("Location: " . $checkout_session->url);

} catch (Exception $e) {
    echo 'Error creating checkout session: ' . $e->getMessage();
}
?>
