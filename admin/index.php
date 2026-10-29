<?php
session_start();
include('includes/config.php');
$loginError = '';
if(isset($_POST['login']))
{
$username=$_POST['username'];
$password=$_POST['password'];
$stmt=$mysqli->prepare("SELECT username,email,password,id FROM admin WHERE (userName=?|| email=?) and password=? ");
				$stmt->bind_param('sss',$username,$username,$password);
				$stmt->execute();
				$stmt -> bind_result($username,$username,$password,$id);
				$rs=$stmt->fetch();
				$_SESSION['id']=$id;
				$uip=$_SERVER['REMOTE_ADDR'];
				$ldate=date('d/m/Y h:i:s', time());
				if($rs)
				{
                //  $insert="INSERT into admin(adminid,ip)VALUES(?,?)";
   // $stmtins = $mysqli->prepare($insert);
   // $stmtins->bind_param('sH',$id,$uip);
    //$res=$stmtins->execute();
					header("location:dashboard.php");
				}

				else
				{
					$loginError = 'Invalid username, email, or password.';
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

	<title>Admin login</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="../css/modern-ui.css">
</head>
<body class="auth-body">
	<main class="auth-page">
		<section class="auth-shell">
			<div class="auth-visual admin">
				<div class="auth-visual-content">
					<div class="auth-brand text-white">
						<span class="brand-mark"><i class="bi bi-buildings" aria-hidden="true"></i></span>
						<span>Hostel Admin</span>
					</div>
					<div>
						<h1>Admin Control Center</h1>
						<p>Monitor rooms, students, complaints, courses, feedback, and access logs from a modern management dashboard.</p>
					</div>
					<p>Administrative access only.</p>
				</div>
			</div>

			<div class="auth-panel">
				<div class="auth-card">
					<a href="index.php" class="auth-brand">
						<span class="brand-mark"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
						<span>Hostel Admin</span>
					</a>
					<h2>Admin login</h2>
					<p>Sign in with your admin username or email.</p>

					<?php if($loginError != '') { ?>
						<div class="alert alert-danger" role="alert">
							<i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
							<?php echo htmlentities($loginError); ?>
						</div>
					<?php } ?>

					<form action="" method="post" id="adminLoginForm">
						<div class="form-floating position-relative">
							<i class="bi bi-person-gear auth-input-icon" aria-hidden="true"></i>
							<input type="text" placeholder="Username or Email" name="username" id="username" class="form-control" required>
							<label for="username">Username or Email</label>
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
								<input type="checkbox" name="remember" id="rememberAdminLogin" value="1"> Remember me
							</label>
							<a href="../index.php">Student login</a>
						</div>

						<button type="submit" name="login" class="btn btn-primary btn-block">
							<i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Login
						</button>
					</form>
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
	<script src="../js/modern-ui.js"></script>
	<script>
	(function() {
		var loginInput = document.getElementById('username');
		var rememberInput = document.getElementById('rememberAdminLogin');
		var form = document.getElementById('adminLoginForm');
		var savedLogin = localStorage.getItem('hmsAdminLogin');

		if (savedLogin && loginInput && rememberInput) {
			loginInput.value = savedLogin;
			rememberInput.checked = true;
		}

		if (form && loginInput && rememberInput) {
			form.addEventListener('submit', function() {
				if (rememberInput.checked) {
					localStorage.setItem('hmsAdminLogin', loginInput.value);
				} else {
					localStorage.removeItem('hmsAdminLogin');
				}
			});
		}
	})();
	</script>
</body>
</html>
