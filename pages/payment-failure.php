<?php
$pageTitle = 'Payment Failed';
include '../includes/config.php';
include '../includes/functions.php';

// Get error details from URL
$errorCode = isset($_GET['error_code']) ? $_GET['error_code'] : '';
$errorMessage = isset($_GET['error_message']) ? $_GET['error_message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Payment failed for your Cute Gift Hamper purchase. Please try again.">
    <meta name="keywords" content="payment failed, order error, gift hampers">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/payment-failure.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="payment-failure-page">
            <div class="container">
                <div class="failure-content">
                    <div class="failure-header">
                        <div class="crossmark">
                            <i class="fas fa-times"></i>
                        </div>
                        <h1>Payment Failed!</h1>
                        <p>Sorry, your payment could not be processed.</p>
                    </div>
                    
                    <?php if (!empty($errorMessage)): ?>
                        <div class="error-details">
                            <h3>Error Details:</h3>
                            <p><?php echo htmlspecialchars($errorMessage); ?></p>
                            <?php if (!empty($errorCode)): ?>
                                <p><strong>Code:</strong> <?php echo htmlspecialchars($errorCode); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="failure-actions">
                        <a href="checkout.php" class="btn">Try Again</a>
                        <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn btn-outline">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>