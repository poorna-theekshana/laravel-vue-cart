<?php 
//THis webhook is for tutorial 
        include('database/mydb.php');
        require_once 'stripe-php-master/init.php';

// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey('enter your secret key');

// If you are testing your webhook locally with the Stripe CLI you
// can find the endpoint's secret by running `stripe listen`
// Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
$endpoint_secret = 'whsec_Enter your endpoint secret';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}
$id = $event->data->object->id;
$amount = $event->data->object->amount_captured;
$currency = $event->data->object->currency;
$cus_email = $event->data->object->receipt_email;
$name = $event->data->object->billing_details->name;

// Handle the event
switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object; // contains a StripePaymentIntent
        handlePaymentIntentSucceeded($paymentIntent);
        break;

    case 'charge.succeeded':
    $stmt = $con->prepare("INSERT INTO payment_success (txn_id, amount, currency, cus_email, name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $id, $amount, $currency, $cus_email, $name);
    $stmt->execute();
    if (!$stmt) {
      # code...
      echo 'There was an error'.mysqli_error($con);
    }    
    $stmt->close();
    $con->close();
        break;

        case 'charge.failed':
    $stmt = $con->prepare("INSERT INTO payment_failed (txn_id, amount, currency, cus_email, name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $id, $amount, $currency, $cus_email, $name);
    $stmt->execute();
    if (!$stmt) {
      # code...
      echo 'There was an error'.mysqli_error($con);
    }    
    $stmt->close();
    $con->close();
        break;


    // ... handle other event types
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);
?>