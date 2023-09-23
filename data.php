<?php
require 'db.php';
// Set content-type and charset headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Add products into the cart table
if (isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	$pname = $_POST['pname'];
	
	$pprice = $_POST['pprice'];
	$pimage = $_POST['pimage'];
	$pcode = $_POST['pcode'];
	$pqty = $_POST['pqty'];
	$total_price = $pprice * $pqty;


	$code = $r['product_code'] ?? '';

	if (!$code) {
		$query = $conn->prepare('INSERT INTO product (product_name,product_price,product_image,product_qty,total_price,product_code) VALUES (?,?,?,?,?,?)');
		$query->bind_param('ssssss', $pname, $pprice, $pimage, $pqty, $total_price, $pcode);
		$query->execute();
		
		echo '<script>alert("data added")</script>';
	} else {
		echo '<script>alert("data already added")</script>';
	}
}




// counter alert
if (isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item') {
	$stmt = $conn->prepare('SELECT * FROM product');
	$stmt->execute();
	$stmt->store_result();
	$rows = $stmt->num_rows;

	echo $rows;
}



// Remove single items from cart
if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	$stmt = $conn->prepare('DELETE FROM product WHERE id=?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	header('location:cart.php');
}

if (isset($_GET['clear'])) {

	$stmt = $conn->prepare('DELETE FROM product');
	$stmt->execute();
	header('location:cart.php');
}

// Checkout and save customer info in the orders table
if (isset($_POST['action']) && isset($_POST['action']) == 'order') {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$products = $_POST['products'];
	$grand_total = $_POST['grand_total'];
	$address = $_POST['address'];
	$pmode = $_POST['pmode'];
	
	$data = '';

	$stmt = $conn->prepare('INSERT INTO orders (name,email,phone,address,pmode,products,amount_paid)VALUES(?,?,?,?,?,?,?)');
	$stmt->bind_param('sssssss', $name, $email, $phone, $address, $pmode, $products, $grand_total);
	$stmt->execute();
	$stmt2 = $conn->prepare('DELETE FROM product');
	$stmt2->execute();
	$data .= '<div class="text-center">
                                  
                                  <h1 class="display-4 mt-2 text-dark">Thank You!</h1>
                                  <h2 class="text-success">Your Order Placed Successfully!</h2>
                                  <h4 class="bg-dark text-light rounded p-2">Items Purchased : ' . $products . '</h4>
                                  <h4>Your Name : ' . $name . '</h4>
                                  <h4>Your E-mail : ' . $email . '</h4>
                                  <h4>Your Phone : ' . $phone . '</h4>
                                  <h4>Total Amount Paid : ' . number_format($grand_total, 2) . '</h4>
                                  <h4>Payment Mode : ' . $pmode . '</h4>
                            </div>
							<div class=" col-12 d-flex justify-content-center mt-5"> 
                                  <button class="btn btn-warning"style="">  <a href="download.php?download=csv">Download csv File</a></button>
                                  </div>';
	echo $data;

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// require_once 'vendor/autoload.php';

// $mail = new PHPMailer(true);
// $mail->isSMTP();
// $mail->SMTPAuth = true;

// $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
// $mail->Username = 'your_email@gmail.com';   // Your Gmail email address
// $mail->Password = 'your_password_or_app_specific_password';   // Your Gmail password or an app-specific password
// $mail->Port = 465;  // SMTP port (465 for SSL)

// // Enable SSL encryption
// $mail->SMTPSecure = "ssl";

// // Sender information
// $mail->setFrom('pritamsood123@gmail.com', 'pritam');  // Replace with your information

// // Receiver address and name
// $mail->addAddress('pritam.up2mark@gmail.com', 'pitu');  // Replace with recipient's information

// $mail->addAttachment(__DIR__ . '/download.png');

// $mail->isHTML(true);

// $mail->Subject = 'PHPMailer SMTP test';
// $mail->Body = "<h4>PHPMailer the awesome Package</h4>
// <b>PHPMailer is working fine for sending mail</b>
// <p>This is a tutorial to guide you on PHPMailer integration</p>";

// // Send mail
// try {
//     $mail->send();
//     echo 'Message has been sent.';
// } catch (Exception $e) {
//     echo 'Email not sent an error was encountered: ' . $mail->ErrorInfo;
// }

	
$to = 'pritam.up2mark@gmail.com';

// Set content-type and charset headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


$msg = '<table id="table" border="0" cellpadding="5px" cellspacing="1px" style="border: 1px solid #eee">
           
Confirmation Number--: '.$phone .'; <br>	

Hello--:'.$name.',<br>

We’re happy to let you know that we’ve received your order.<br>

Once your package ships, we will send you an email with a tracking number and link so you can see the movement of your package.<br>

If you have any questions, contact us here or call us on '.$phone.'!<br>

We are here to help!<br>

Returns: If you would like to return your product(s), please see here [link] or contact us.<br>

P.S. psst… you may love these too:<br>

list of products<br>

'.$products.'

        </table>';

// Send the email
$mailSent = mail($to, 'Confirmation Email', $msg, $headers);

if ($mailSent) {
    echo 'Confirmation email sent successfully.';
	
} else {
    echo 'Confirmation email could not be sent.'. error_get_last()['message'];
}

}