<?php
session_start();
include('includes/config.php');
$recoveryError = '';
if(isset($_POST['login']))
{
$email=$_POST['email'];
$contact=$_POST['contact'];
$stmt=$mysqli->prepare("SELECT email,contactNo,password FROM userregistration WHERE (email=? && contactNo=?) ");
				$stmt->bind_param('ss',$email,$contact);
				$stmt->execute();
				$stmt -> bind_result($username,$email,$password);
				$rs=$stmt->fetch();
				if($rs)
				{
				$pwd=$password;				
				}

				else
				{
					$recoveryError = 'Invalid email address or contact number.';
				}
			}
				?>

<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>User Forgot Password</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/modern-ui.css">
</head>
<body class="auth-body">
	<main class="auth-page">
		<section class="auth-shell">
			<div class="auth-visual">
				<div class="auth-visual-content">
					<div class="auth-brand text-white">
						<span class="brand-mark"><i class="bi bi-buildings" aria-hidden="true"></i></span>
						<span>Hostel Management</span>
					</div>
					<div>
						<h1>Account Recovery</h1>
						<p>Confirm your registered email and contact number to recover access to your hostel account.</p>
					</div>
					<p>Return to login after recovery.</p>
				</div>
			</div>

			<div class="auth-panel">
				<div class="auth-card">
					<a href="index.php" class="auth-brand">
						<span class="brand-mark"><i class="bi bi-buildings" aria-hidden="true"></i></span>
						<span>Hostel Management</span>
					</a>
					<h2>Forgot password</h2>
					<p>Enter the details used during student registration.</p>

					<?php if(isset($_POST['login']) && isset($pwd)) { ?>
						<div class="alert alert-success" role="alert">
							<i class="bi bi-check-circle" aria-hidden="true"></i>
							Your password is <?php echo htmlentities($pwd); ?>. Change the password after login.
						</div>
					<?php } ?>

					<?php if($recoveryError != '') { ?>
						<div class="alert alert-danger" role="alert">
							<i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
							<?php echo htmlentities($recoveryError); ?>
						</div>
					<?php } ?>

					<form action="" method="post">
						<div class="form-floating position-relative">
							<i class="bi bi-envelope auth-input-icon" aria-hidden="true"></i>
							<input type="email" placeholder="Email" name="email" id="email" class="form-control" required>
							<label for="email">Email</label>
						</div>

						<div class="form-floating position-relative">
							<i class="bi bi-telephone auth-input-icon" aria-hidden="true"></i>
							<input type="text" placeholder="Contact no" name="contact" id="contact" class="form-control" required>
							<label for="contact">Contact no</label>
						</div>

						<button type="submit" name="login" class="btn btn-primary btn-block">
							<i class="bi bi-search" aria-hidden="true"></i> Recover Password
						</button>
					</form>

					<div class="auth-links">
						<a href="index.php">Back to sign in</a>
					</div>
				</div>
			</div>
		</section>
	</main>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script src="js/modern-ui.js"></script>
</body>
</html>
