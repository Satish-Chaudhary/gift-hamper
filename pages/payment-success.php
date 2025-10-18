<?php
$pageTitle = 'Payment Success';
include '../includes/config.php';
include '../includes/functions.php';

// Get payment details from URL
$paymentId = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';
$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : '';

// Check if payment data exists in session
if (!isset($_SESSION['payment_data'])) {
    header('Location: checkout.php');
    exit;
}

// Get customer data from session
$customerData = $_SESSION['payment_data'];

// Get cart items
$cart = getCartItems($conn);

// Create order in database
$orderId = createOrder($conn, $customerData);

// Clear payment data from session
unset($_SESSION['payment_data']);

// If order creation failed, show error
if (!$orderId) {
    $error = "There was a problem processing your order. Please contact support.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Payment successful for your Cute Gift Hamper purchase. Order confirmed.">
    <meta name="keywords" content="payment success, order confirmation, gift hampers">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/payment-success.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="payment-success-page">
            <div class="container">
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <h2>Order Processing Error</h2>
                        <p><?php echo $error; ?></p>
                        <a href="checkout.php" class="btn">Try Again</a>
                    </div>
                <?php elseif ($orderId): ?>
                    <div class="success-content">
                        <div class="success-header">
                            <div class="checkmark">
                                <i class="fas fa-check"></i>
                            </div>
                            <h1>Payment Successful!</h1>
                            <p>Thank you for your purchase. Your order has been confirmed.</p>
                        </div>
                        
                        <div class="order-details">
                            <h2>Order Details</h2>
                            <div class="order-info">
                                <div class="info-item">
                                    <span>Order Number:</span>
                                    <span>#<?php echo $orderId; ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Payment ID:</span>
                                    <span><?php echo htmlspecialchars($paymentId); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Date:</span>
                                    <span><?php echo date('F j, Y'); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Total:</span>
                                    <span>$<?php echo number_format($cart['total'], 2); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Status:</span>
                                    <span class="status confirmed">Confirmed</span>
                                </div>
                                <div class="info-item">
                                    <span>Customer:</span>
                                    <span><?php echo htmlspecialchars($customerData['name']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Email:</span>
                                    <span><?php echo htmlspecialchars($customerData['email']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Shipping Address:</span>
                                    <span><?php echo htmlspecialchars($customerData['address']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="success-actions">
                            <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Continue Shopping</a>
                            <?php if (isUserLoggedIn()): ?>
                                <a href="<?php echo BASE_URL; ?>pages/my_orders.php" class="btn btn-outline">View My Orders</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="error-message">
                        <h2>Order Not Found</h2>
                        <p>We couldn't find the order you're looking for.</p>
                        <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Continue Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>