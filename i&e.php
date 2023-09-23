<?php

include_once 'db.php';
$msg = '';
error_reporting(E_ALL ^ E_WARNING);
if (isset($_POST['import_csv'])) {

	//get file extension

	$getfile = basename($_FILES["file"]["name"]);
	$extention = @end(explode('.', $getfile));
	$lover_extention = strtolower($extention);
	$filename = $_FILES['file']['temp_name'];
	// check only file accepted 
	$allowTypes = array('csv');
	if (in_array($lover, $allowTypes)) {
		if ($_FILES["file"]["size"] > 0) {
		
			$file = fopen($filename, "r");
			$i = 0;
			while (($col = fgetcsv($file, 10000, ",")) !==TRUE) {
				if ($i > 0 && $col[0] != '') {
					$insert = "INSERT INTO orders (name,phone,email,address)values('". $col[0] . "','" . $col[1] . "','" . $col[2] . "','" . $col[3] . "')";
					mysqli_query($conn, $insert);
				}
				$i++;
			}
			$msg = '<p style="color: green;"> CSV Data inserted successfully</p>';
		}
	} else {
		$msg = '<p style="color: red;">File extension should be .csv format</p>';
	}
}


?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link src="./style.css">
	<title>shopping cart</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
					<a class="nav-link text-dark" href="i&e.php">Import & Export</a>
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
	<form class="form-control mt-5" method="post" action="" enctype="multipart/form-data" style="padding: 54px 173px;">

		<input type="file" name="file" class="form-control">

		<div class="dropdown">
  <button class="btn btn-success mt-3 " type="button"  aria-expanded="false">
    Import 
  </button>
</div>

		<?php
		$fetchsql ="SELECT * FROM orders";
		$fetchrow = mysqli_query($conn, $fetchsql);
		$fetchcount = mysqli_num_rows($fetchrow);
		if ($fetchcount > 0) {
			echo '<h3 class="mt-3">User List:</h3>
			<form action="" method="POST" >
            <div class=" col-12 d-flex justify-content-start mt-5"> 
			<button class="btn btn-success mt-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
			Export Dropdown
		  </button>
		  <ul class="dropdown-menu">
			<li><a class="dropdown-item" <a href="download.php?download=csv" class="text-light btn btn-primary ">Export csv </a></li>
			<li><a class="dropdown-item" <a href="download.php?download=xml" class="text-light btn btn-primary ">Export xml </a></li>
			<li><a class="dropdown-item" <a href="download.php?download=json" class="text-light btn btn-primary ">Export json </a></li>
		  </ul>
		</div>
			
            </div>
				<br>

				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th></th>
							<th>S.No.</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
						</tr>
					</thead>
					<tbody>';
			$k = 1;
			while ($get = $fetchrow->fetch_assoc()) {
				echo '<tr>
						<td><input type="checkbox" name="userid[]" class="checkbox" value="' . $get['id'] . '"></td>
						<td>' . $k . '</td>
						<td>' . $get['name'] . '</td>
						<td>' . $get['email'] . '</td>
						<td>' . $get['phone'] . '</td>
					</tr>';
				$k++;
			}
			echo '</tbody>
			</table>

		</form>';
		}

		?>
	</form>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>