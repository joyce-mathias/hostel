<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

$studentId = $_SESSION['id'];
$studentLogin = $_SESSION['login'];
$complaintsTotal = 0;
$roomBookings = 0;
$newComplaints = 0;
$inProcessComplaints = 0;
$closedComplaints = 0;
$latestComplaint = null;

$stmt = $mysqli->prepare("SELECT count(*) FROM complaints WHERE userId=?");
$stmt->bind_param('i', $studentId);
$stmt->execute();
$stmt->bind_result($complaintsTotal);
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare("SELECT count(*) FROM registration WHERE emailid=? || regno=?");
$stmt->bind_param('ss', $studentLogin, $studentLogin);
$stmt->execute();
$stmt->bind_result($roomBookings);
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare("SELECT complaintStatus, count(*) as total FROM complaints WHERE userId=? GROUP BY complaintStatus");
$stmt->bind_param('i', $studentId);
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()) {
	$status = $row['complaintStatus'];
	if($status == '') {
		$newComplaints = $row['total'];
	} elseif($status == 'In Process') {
		$inProcessComplaints = $row['total'];
	} elseif($status == 'Closed') {
		$closedComplaints = $row['total'];
	}
}
$stmt->close();

$stmt = $mysqli->prepare("SELECT ComplainNumber, complaintType, complaintStatus, registrationDate FROM complaints WHERE userId=? ORDER BY registrationDate DESC LIMIT 1");
$stmt->bind_param('i', $studentId);
$stmt->execute();
$latestComplaint = $stmt->get_result()->fetch_object();
$stmt->close();
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
	
	<title>DashBoard</title>
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

<body>
<?php include("includes/header.php");?>

	<div class="ts-main-content">
		<?php include("includes/sidebar.php");?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="hms-page-header">
					<div>
						<div class="hms-eyebrow">Student Portal</div>
						<h1 class="hms-page-heading">Dashboard</h1>
						<p class="hms-page-subtitle">Track your hostel booking, complaint progress, and account activity from one place.</p>
					</div>
					<a href="book-hostel.php" class="btn btn-primary"><i class="bi bi-door-open" aria-hidden="true"></i> Book Hostel</a>
				</div>

				<div class="hms-stat-grid">
					<a href="my-profile.php" class="hms-stat-card">
						<div>
							<p class="hms-eyebrow">Account</p>
							<div class="hms-stat-value">Profile</div>
							<p class="hms-stat-label">Review and update your student details.</p>
							<span class="hms-stat-link">View profile <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
						</div>
						<span class="hms-icon-badge"><i class="bi bi-person-circle" aria-hidden="true"></i></span>
					</a>

					<a href="room-details.php" class="hms-stat-card">
						<div>
							<p class="hms-eyebrow">Room</p>
							<div class="hms-stat-value"><?php echo $roomBookings > 0 ? 'Booked' : 'Open'; ?></div>
							<p class="hms-stat-label"><?php echo $roomBookings > 0 ? 'Your hostel allocation is available.' : 'No room is currently booked.'; ?></p>
							<span class="hms-stat-link">Room details <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
						</div>
						<span class="hms-icon-badge"><i class="bi bi-house-check" aria-hidden="true"></i></span>
					</a>

					<a href="my-complaints.php" class="hms-stat-card">
						<div>
							<p class="hms-eyebrow">Complaints</p>
							<div class="hms-stat-value"><?php echo $complaintsTotal; ?></div>
							<p class="hms-stat-label">Registered complaints in your account.</p>
							<span class="hms-stat-link">Track status <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
						</div>
						<span class="hms-icon-badge"><i class="bi bi-chat-left-text" aria-hidden="true"></i></span>
					</a>
				</div>

				<div class="hms-dashboard-grid">
					<div class="hms-card">
						<div class="hms-card-header">
							<h2 class="hms-card-title">Complaint Status</h2>
							<a href="register-complaint.php" class="btn btn-default btn-sm"><i class="bi bi-plus-lg" aria-hidden="true"></i> New Complaint</a>
						</div>
						<div class="hms-card-body">
							<div class="hms-chart-wrap">
								<canvas id="studentComplaintChart" width="640" height="250" aria-label="Complaint status chart"></canvas>
							</div>
						</div>
					</div>

					<div class="hms-card">
						<div class="hms-card-header">
							<h2 class="hms-card-title">Recent Activity</h2>
						</div>
						<div class="hms-card-body">
							<ul class="hms-activity-list">
								<li class="hms-activity-item">
									<span class="hms-icon-badge"><i class="bi bi-house-check" aria-hidden="true"></i></span>
									<div>
										<strong><?php echo $roomBookings > 0 ? 'Room allocation active' : 'Room booking pending'; ?></strong>
										<span><?php echo $roomBookings > 0 ? 'Open room details to review your allocation and fees.' : 'Use Book Hostel to submit your room allocation request.'; ?></span>
									</div>
								</li>
								<li class="hms-activity-item">
									<span class="hms-icon-badge"><i class="bi bi-chat-square-text" aria-hidden="true"></i></span>
									<div>
										<strong><?php echo $latestComplaint ? '#'.$latestComplaint->ComplainNumber.' '.$latestComplaint->complaintType : 'No complaint yet'; ?></strong>
										<span><?php echo $latestComplaint ? (($latestComplaint->complaintStatus == '') ? 'New' : $latestComplaint->complaintStatus).' on '.$latestComplaint->registrationDate : 'You can register and track complaints here.'; ?></span>
									</div>
								</li>
								<li class="hms-activity-item">
									<span class="hms-icon-badge"><i class="bi bi-star" aria-hidden="true"></i></span>
									<div>
										<strong>Feedback</strong>
										<span>Share hostel feedback after your stay or service experience.</span>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="hms-card mt-3">
					<div class="hms-card-header">
						<h2 class="hms-card-title">Quick Actions</h2>
					</div>
					<div class="hms-card-body">
						<div class="hms-quick-actions">
							<a class="hms-quick-action" href="book-hostel.php"><i class="bi bi-door-open" aria-hidden="true"></i> Book Hostel</a>
							<a class="hms-quick-action" href="room-details.php"><i class="bi bi-receipt" aria-hidden="true"></i> View Fees</a>
							<a class="hms-quick-action" href="register-complaint.php"><i class="bi bi-chat-left-text" aria-hidden="true"></i> Register Complaint</a>
							<a class="hms-quick-action" href="feedback.php"><i class="bi bi-star" aria-hidden="true"></i> Submit Feedback</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
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
	window.addEventListener('load', function() {
		var chart = document.getElementById('studentComplaintChart');
		if (!chart || !window.Chart) return;

		new Chart(chart.getContext('2d')).Doughnut([
			{
				value: <?php echo (int)$newComplaints; ?>,
				color: '#f59e0b',
				highlight: '#fbbf24',
				label: 'New'
			},
			{
				value: <?php echo (int)$inProcessComplaints; ?>,
				color: '#0ea5e9',
				highlight: '#38bdf8',
				label: 'In Process'
			},
			{
				value: <?php echo (int)$closedComplaints; ?>,
				color: '#16a34a',
				highlight: '#22c55e',
				label: 'Closed'
			}
		], {
			responsive: true,
			percentageInnerCutout: 62
		});
	});
	</script>

</body>

</html>
