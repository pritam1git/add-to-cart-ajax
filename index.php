<?php

	// Get no.of items available in the cart table

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link src="./style.css">
    <title>shopping cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>

    <!-- Navbar start -->
    <nav class="navbar navbar-expand-md bg-light navbar-dark shadow-lg">
        <a class="navbar-brand" href="index.php"><img src="./images/pngegg.png" alt="" style="    width: 130px;
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
                    <a class="nav-link" href="cart.php"><i class="bi bi-cart text-dark fs-5"></i> <span id="cart-item" class="badge bg-danger"></span></a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navbar end -->

    <!-- Displaying Products Start -->
    <div class="container">
        <div id="message"></div>
        <div class="row">
            <?php
            include_once 'db.php';
            $sql = "SELECT * FROM products";
            $result  = $conn->query($sql);
            while ($row = $result->fetch_assoc()) :
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3 mb-2 mt-4">
                    <div class="card-deck">
                        <div class="card p-2 border-secondary mb-2">
                            <img src="<?= $row['product_image'] ?>" class="card-img-top" height="250">
                            <div class="card-body p-1">
                                <h4 class="card-title text-center text-info"><?= $row['product_name'] ?></h4>
                                <h5 class="card-text text-center text-danger"><i class="fas fa-rupee-sign"></i>&nbsp;&nbsp;<?= $row['product_price'] ?>/-</h5>
                            </div>
                            <div class="card-footer">
                                <form class="form-submit">
                                    <div class="row p-2">
                                        <div class="col-md-6 py-1 pl-4">
                                            <b>Quantity : </b>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" class="form-control pqty" value="<?= $row['product_qty'] ?>">
                                        </div>
                                    </div>
                                    <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                                    <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                                    <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                                    <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
                                    <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                                    <button class="btn btn-success btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Add to cart</button>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    </div>
    <!-- Displaying Products End -->

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            // Send product details in the server
            $(".addItemBtn").click(function(e) {
                e.preventDefault();
                var $form = $(this).closest(".form-submit");
                var pid = $form.find(".pid").val();
                var pname = $form.find(".pname").val();
                var pprice = $form.find(".pprice").val();
                var pimage = $form.find(".pimage").val();
                var pcode = $form.find(".pcode").val();
                var pqty = $form.find(".pqty").val();
                $.ajax({
                    url: 'data.php',
                    method: 'post',
                    data: {
                        pid: pid,
                        pname: pname,
                        pprice: pprice,
                        pqty: pqty,
                        pimage: pimage,
                        pcode: pcode
                    },
                    success: function(response) {
                        $("#message").html(response);
                        window.scrollTo(0, 0);
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>