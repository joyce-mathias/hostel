<?php
session_start();
include('includes/config.php');
$loginError = '';
if(isset($_POST['login']))
{
$emailreg=$_POST['emailreg'];
$password=$_POST['password'];
$stmt=$mysqli->prepare("SELECT email,password,id FROM userregistration WHERE (email=? || regNo=?) and password=? ");
				$stmt->bind_param('sss',$emailreg,$emailreg,$password);
				$stmt->execute();
				$stmt -> bind_result($email,$password,$id);
				$rs=$stmt->fetch();
				$stmt->close();
				$_SESSION['id']=$id;
				$_SESSION['login']=$emailreg;
				$uip=$_SERVER['REMOTE_ADDR'];
				$ldate=date('d/m/Y h:i:s', time());
				if($rs)
				{
             $uid=$_SESSION['id'];
             $uemail=$_SESSION['login'];
$ip=$_SERVER['REMOTE_ADDR'];
$geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
$addrDetailsArr = unserialize(file_get_contents($geopluginURL));
$city = $addrDetailsArr['geoplugin_city'];
$country = $addrDetailsArr['geoplugin_countryName'];
$log="insert into userLog(userId,userEmail,userIp,city,country) values('$uid','$uemail','$ip','$city','$country')";
$mysqli->query($log);
if($log)
{
header("location:dashboard.php");
				}
}
				else
				{
					$loginError = 'Invalid username, email, registration number, or password.';
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
	<meta name="theme-color" content="#3e454c">
	<title>Student Hostel Registration</title>
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
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript">
function valid()
{
if(document.registration.password.value!= document.registration.cpassword.value)
{
alert("Password and Re-Type Password Field do not match  !!");
document.registration.cpassword.focus();
return false;
}
return true;
}
</script>
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
						<h1>Student Hostel Portal</h1>
						<p>Manage room bookings, complaints, feedback, profile updates, and hostel records from a clean student workspace.</p>
					</div>
					<p>Secure access for registered students.</p>
				</div>
			</div>

			<div class="auth-panel">
				<div class="auth-card">
					<a href="index.php" class="auth-brand">
						<span class="brand-mark"><i class="bi bi-buildings" aria-hidden="true"></i></span>
						<span>Hostel Management</span>
					</a>
					<h2>Welcome back</h2>
					<p>Sign in with your email address or registration number.</p>

					<?php if($loginError != '') { ?>
						<div class="alert alert-danger" role="alert">
							<i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
							<?php echo htmlentities($loginError); ?>
						</div>
					<?php } ?>

					<form action="" method="post" id="studentLoginForm">
						<div class="form-floating position-relative">
							<i class="bi bi-person-badge auth-input-icon" aria-hidden="true"></i>
							<input type="text" placeholder="Email / Registration Number" name="emailreg" id="emailreg" class="form-control" required>
							<label for="emailreg">Email / Registration Number</label>
						</div>

						<div class="form-floating position-relative">
							<i class="bi bi-shield-lock auth-input-icon" aria-hidden="true"></i>
							<input type="password" placeholder="Password" name="password" id="password" class="form-control" required>
							<label for="password">Password</label>
							<button type="button" class="password-toggle" data-password-toggle="#password" aria-label="Show password">
								<i class="bi bi-eye" aria-hidden="true"></i>
							</button>
						</div>

						<div class="auth-meta">
							<label class="d-inline-flex align-items-center gap-2 mb-0">
								<input type="checkbox" name="remember" id="rememberLogin" value="1"> Remember me
							</label>
							<a href="forgot-password.php">Forgot password?</a>
						</div>

						<button type="submit" name="login" class="btn btn-primary btn-block">
							<i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Login
						</button>
					</form>

					<div class="auth-links">
						<span>New student?</span>
						<a href="registration.php">Create an account</a>
						<span>|</span>
						<a href="admin/">Admin login</a>
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
	<script>
	(function() {
		var loginInput = document.getElementById('emailreg');
		var rememberInput = document.getElementById('rememberLogin');
		var form = document.getElementById('studentLoginForm');
		var savedLogin = localStorage.getItem('hmsStudentLogin');

		if (savedLogin && loginInput && rememberInput) {
			loginInput.value = savedLogin;
			rememberInput.checked = true;
		}

		if (form && loginInput && rememberInput) {
			form.addEventListener('submit', function() {
				if (rememberInput.checked) {
					localStorage.setItem('hmsStudentLogin', loginInput.value);
				} else {
					localStorage.removeItem('hmsStudentLogin');
				}
			});
		}
	})();
	</script>
</body>

</html>
