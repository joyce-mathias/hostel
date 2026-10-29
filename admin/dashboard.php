<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

function hms_count($mysqli, $sql) {
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();
	return (int)$count;
}

$studentsTotal = hms_count($mysqli, "SELECT count(*) FROM registration");
$roomsTotal = hms_count($mysqli, "SELECT count(*) FROM rooms");
$coursesTotal = hms_count($mysqli, "SELECT count(*) FROM courses");
$complaintsTotal = hms_count($mysqli, "SELECT count(*) FROM complaints");
$newComplaints = hms_count($mysqli, "SELECT count(*) FROM complaints WHERE complaintStatus is null");
$inProcessComplaints = hms_count($mysqli, "SELECT count(*) FROM complaints WHERE complaintStatus='In Process'");
$closedComplaints = hms_count($mysqli, "SELECT count(*) FROM complaints WHERE complaintStatus='Closed'");
$feedbackTotal = hms_count($mysqli, "SELECT count(*) FROM feedback");
$occupiedRooms = hms_count($mysqli, "SELECT count(DISTINCT roomno) FROM registration");
$occupancyPercent = $roomsTotal > 0 ? round(($occupiedRooms / $roomsTotal) * 100) : 0;

$latestComplaint = null;
$stmt = $mysqli->prepare("SELECT ComplainNumber, complaintType, complaintStatus, registrationDate FROM complaints ORDER BY registrationDate DESC LIMIT 1");
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
	<link rel="stylesheet" href="../css/modern-ui.css">


</head>

<body>
<?php include("includes/header.php");?>

	<div class="ts-main-content">
		<?php include("includes/sidebar.php");?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="hms-page-header">
					<div>
						<div class="hms-eyebrow">Admin Overview</div>
						<h1 class="hms-page-heading">Dashboard</h1>
						<p class="hms-page-subtitle">Monitor occupancy, complaints, feedback, rooms, courses, and student records from one responsive workspace.</p>
					</div>
					<a href="create-room.php" class="btn btn-primary"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Room</a>
				</div>

				<div class="hms-stat-grid">
					<a href="manage-students.php" class="hms-stat-card">
						<div>
							<p class="hms-eyebrow">Students</p>
							<div class="hms-stat-value"><?php echo $studentsTotal; ?></div>
							<p class="hms-stat-label">Registered hostel students.</p>
							<span class="hms-stat-link">Manage students <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
						</div>
						<span class="hms-icon-badge"><i class="bi bi-people" aria-hidden="true"></i></span>
					</a>

					<a href="manage-rooms.php" class="hms-stat-card">
						<div>
							<p class="hms-eyebrow">Rooms</p>
							<div class="hms-stat-value"><?php echo $roomsTotal; ?></div>
							<p class="hms-stat-label"><?php echo $occupiedRooms; ?> rooms currently allocated.</p>
							<span class="hms-stat-link">Manage rooms <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
						</div>
						<span class="hms-icon-badge"><i class="bi bi-door-open" aria-hidden="true"></i></span>
					</a>

					<a href="manage-courses.php" class="hms-stat-card">
						<div>
							<p class="hms-eyebrow">Courses</p>
							<div class="hms-stat-value"><?php echo $coursesTotal; ?></div>
							<p class="hms-stat-label">Courses available for student records.</p>
							<span class="hms-stat-link">Manage courses <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
						</div>
						<span class="hms-icon-badge"><i class="bi bi-journal-bookmark" aria-hidden="true"></i></span>
					</a>

					<a href="all-complaints.php" class="hms-stat-card">
						<div>
							<p class="hms-eyebrow">Complaints</p>
							<div class="hms-stat-value"><?php echo $complaintsTotal; ?></div>
							<p class="hms-stat-label"><?php echo $newComplaints; ?> new and <?php echo $inProcessComplaints; ?> in process.</p>
							<span class="hms-stat-link">Open desk <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
						</div>
						<span class="hms-icon-badge"><i class="bi bi-chat-left-text" aria-hidden="true"></i></span>
					</a>
				</div>

				<div class="hms-dashboard-grid">
					<div class="hms-card">
						<div class="hms-card-header">
							<h2 class="hms-card-title">Complaint Monitoring</h2>
							<a href="new-complaints.php" class="btn btn-default btn-sm"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> New Queue</a>
						</div>
						<div class="hms-card-body">
							<div class="hms-chart-wrap">
								<canvas id="adminComplaintChart" width="640" height="250" aria-label="Complaint monitoring chart"></canvas>
							</div>
						</div>
					</div>

					<div class="hms-card">
						<div class="hms-card-header">
							<h2 class="hms-card-title">Room Occupancy</h2>
						</div>
						<div class="hms-card-body">
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="text-muted">Allocated rooms</span>
								<strong><?php echo $occupancyPercent; ?>%</strong>
							</div>
							<div class="hms-progress mb-3"><span data-progress="<?php echo $occupancyPercent; ?>"></span></div>
							<ul class="hms-activity-list">
								<li class="hms-activity-item">
									<span class="hms-icon-badge"><i class="bi bi-door-open" aria-hidden="true"></i></span>
									<div>
										<strong><?php echo $occupiedRooms; ?> of <?php echo $roomsTotal; ?> rooms allocated</strong>
										<span>Use room management for capacity and fees.</span>
									</div>
								</li>
								<li class="hms-activity-item">
									<span class="hms-icon-badge"><i class="bi bi-star" aria-hidden="true"></i></span>
									<div>
										<strong><?php echo $feedbackTotal; ?> feedback entries</strong>
										<span>Review student experience signals.</span>
									</div>
								</li>
								<li class="hms-activity-item">
									<span class="hms-icon-badge"><i class="bi bi-clock-history" aria-hidden="true"></i></span>
									<div>
										<strong><?php echo $latestComplaint ? '#'.$latestComplaint->ComplainNumber.' '.$latestComplaint->complaintType : 'No complaints yet'; ?></strong>
										<span><?php echo $latestComplaint ? (($latestComplaint->complaintStatus == '') ? 'New' : $latestComplaint->complaintStatus).' on '.$latestComplaint->registrationDate : 'Complaint activity will appear here.'; ?></span>
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
							<a class="hms-quick-action" href="registration.php"><i class="bi bi-person-plus" aria-hidden="true"></i> Register Student</a>
							<a class="hms-quick-action" href="create-room.php"><i class="bi bi-door-open" aria-hidden="true"></i> Add Room</a>
							<a class="hms-quick-action" href="new-complaints.php"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> New Complaints</a>
							<a class="hms-quick-action" href="feedbacks.php"><i class="bi bi-star" aria-hidden="true"></i> Feedback</a>
							<a class="hms-quick-action" href="access-log.php"><i class="bi bi-clock-history" aria-hidden="true"></i> Access Logs</a>
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
	<script src="../js/modern-ui.js"></script>
	<script>
	window.addEventListener('load', function() {
		var chart = document.getElementById('adminComplaintChart');
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
