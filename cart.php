<?php
session_start();

include_once 'db.php';

if (isset($_POST['qty']) && isset($_POST['pid']) && isset($_POST['pprice'])) {
    $qty = $_POST['qty'];
    $pid = $_POST['pid'];
    $pprice = $_POST['pprice'];

    $tprice = $qty * $pprice;

    $sql = "UPDATE product SET product_qty=$qty product_price=$pprice total_price = $tprice WHERE pid = $pid";
    if ($conn->query($sql) === TRUE) {
        header("location: cart.php");
        exit;
    } else {
        // Error occurred while updating the record
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Sahil Kumar">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cart</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>
    <nav class="navbar navbar-expand-md bg-light navbar-dark shadow-lg">
        <a class="navbar-brand" href="index.php"><img src="./images/pngegg.png" alt="" style="width: 130px;
    height: 41px;
    margin-left: 28px;
"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active text-dark" href="index.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark"  href="i&e.php">Import & Export</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="checkout.php">Checkout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php"><i class="bi bi-cart text-dark fs-5"></i><span id="cart-item" class="badge bg-danger"></span></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-striped text-center ">
                        <thead class="">
                            <tr>
                                <td colspan="7">
                                    <h4 class="text-center text-dark m-0">Products in your cart!</h4>
                                </td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>
                                    <a href="data.php?clear=all" class="badge-primery badge p-1" onclick="return confirm('Are you sure want to clear your cart?');">&nbsp;&nbsp;All Delete</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="">
                            <?php
                            require 'db.php';
                            $stmt = $conn->prepare('SELECT * FROM product');
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $grand_total = 0;
                            while ($row = $result->fetch_assoc()) :
                            ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                                    <td><img src="<?= $row['product_image'] ?>" width="50"></td>
                                    <td><?= $row['product_name'] ?></td>
                                    <td>
                                        <i class="fas fa-rupee-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'], 2); ?>
                                    </td>
                                    <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                                    <td>
                                        <input type="number" class="form-control itemQty" class="qty" value="<?= $row['product_qty'] ?>" style="width:75px;">
                                    </td>
                                    <td><i class="fas fa-rupee-sign"></i>&nbsp;&nbsp;<?= number_format($row['total_price'], 2); ?></td>

                                    <td>
                                        <a href="data.php?remove=<?= $row['id'] ?>" class="text-danger lead" onclick="return confirm('Are you sure want to remove this item?');">Delete</a>
                                    </td>
                                </tr>
                                <?php $grand_total  += $row['total_price']; ?>
                            <?php endwhile; ?>
                            <tr>
                                <td colspan="3">
                                    <a href="index.php" class="btn btn-light">&nbsp;&nbsp;Continue
                                        Shopping</a>
                                </td>
                                <td colspan="2"><b>Grand Total</b></td>
                                <td><b>&nbsp;&nbsp;<?= number_format($grand_total, 2); ?></b></td>
                                <td>
                                    <a href="checkout.php" class="btn btn-success ">Checkout</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>
    <script type="text/javascript">
        $(document).ready(function() {



            // Change the item quantity
            $(".itemQty").on('change', function() {
                var $el = $(this).closest('tr');
                var pid = $el.find(".pid").val();
                var pprice = $el.find(".pprice").val();
                var qty = $el.find(".itemQty").val();
                location.reload(true);
                $.ajax({
                    url: 'data.php',
                    method: 'post',
                    cache: false,
                    data: {
                        qty: qty,
                        pid: pid,
                        pprice: pprice
                    },
                    success: function(response) {
                        console.log(response);
                        load_cart();
                    }
                });
            });
            // Load total no.of items added in the cart and display in the navbar
            load_cart();

            function load_cart() {
                $.ajax({
                    url: "data.php",
                    method: "get",
                    data: {
                        cartItem: "cart_item"
                    },
                    success: function(response) {
                        $("#cart-item").html(response)
                    }
                });
            }
        });
    </script>

</body>

</html>