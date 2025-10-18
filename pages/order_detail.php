<?php
$pageTitle = 'Order Details';
include '../includes/config.php';
include '../includes/functions.php';

// Check if user is logged in
if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get order ID from URL
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get order details
$order = null;
$orderItems = array();

if ($orderId > 0) {
    // First, verify that this order belongs to the logged-in user
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ? AND user_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $orderId, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        // If order exists and belongs to user, get order items
        if ($order) {
            $orderItems = getOrderItems($conn, $orderId);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View detailed information about your order at Cute Gift Hamper. Track your gift hamper order status.">
    <meta name="keywords" content="order details, order tracking, gift hampers, order status, purchase details">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/order_detail.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="order-detail-page">
            <div class="container">
                <?php if (!$order): ?>
                    <div class="error-message">
                        <h2>Order Not Found</h2>
                        <p>We couldn't find the order you're looking for, or you don't have permission to view it.</p>
                        <a href="<?php echo BASE_URL; ?>pages/my_orders.php" class="btn">Back to My Orders</a>
                    </div>
                <?php else: ?>
                    <div class="order-detail-content">
                        <div class="order-header">
                            <h1>Order #<?php echo $order['id']; ?></h1>
                            <div class="order-status">
                                <span class="status <?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="order-info-grid">
                            <div class="order-info-card">
                                <h3>Order Information</h3>
                                <div class="info-item">
                                    <span>Order Date:</span>
                                    <span><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Total Amount:</span>
                                    <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Status:</span>
                                    <span class="status <?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-info-card">
                                <h3>Shipping Information</h3>
                                <div class="info-item">
                                    <span>Customer:</span>
                                    <span><?php echo htmlspecialchars($order['customer_name']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Email:</span>
                                    <span><?php echo htmlspecialchars($order['customer_email']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span>Address:</span>
                                    <span><?php echo htmlspecialchars($order['customer_address']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-items">
                            <h3>Order Items</h3>
                            <div class="order-items-list">
                                <?php foreach ($orderItems as $item): ?>
                                    <div class="order-item">
                                        <?php if ($item['product_image']): ?>
                                            <img src="<?php echo BASE_URL; ?>assets/images/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                        <?php else: ?>
                                            <div class="no-image">No Image</div>
                                        <?php endif; ?>
                                        <div class="item-details">
                                            <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                                            <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                                            <p>Subtotal: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="order-actions">
                            <a href="<?php echo BASE_URL; ?>pages/my_orders.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Orders</a>
                            <?php if ($order['status'] == 'pending' || $order['status'] == 'processing'): ?>
                                <button class="btn" disabled>Track Shipment</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any order detail page specific JavaScript here if needed
    </script>
</body>
</html>