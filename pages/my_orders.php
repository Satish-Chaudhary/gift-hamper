<?php
$pageTitle = 'My Orders';
include '../includes/config.php';
include '../includes/functions.php';

// Check if user is logged in
if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get user's orders
$userId = $_SESSION['user_id'];
$orders = getUserOrders($conn, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View your order history at Cute Gift Hamper. Track your gift hamper orders and view past purchases.">
    <meta name="keywords" content="my orders, order history, gift hampers, order tracking, purchase history">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/my_orders.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="my-orders-page">
            <div class="container">
                <h1>My Orders</h1>
                
                <?php if (empty($orders)): ?>
                    <div class="empty-cart">
                        <p>You haven't placed any orders yet.</p>
                        <a href="<?php echo BASE_URL; ?>pages/products.php" class="btn">Start Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="order-info">
                                        <h3>Order #<?php echo $order['id']; ?></h3>
                                        <p class="order-date"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                                    </div>
                                    <div class="order-status">
                                        <span class="status <?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="order-details">
                                    <div class="order-summary">
                                        <p><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                                        <p><strong>Items:</strong> <?php 
                                            $items = getOrderItems($conn, $order['id']);
                                            echo count($items);
                                        ?> item(s)</p>
                                    </div>
                                    
                                    <div class="order-actions">
                                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-outline">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any my orders page specific JavaScript here if needed
    </script>
</body>
</html>