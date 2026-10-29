<nav class="ts-sidebar" id="hms-sidebar" aria-label="Student navigation">
	<ul class="ts-sidebar-menu">
		<?php if(isset($_SESSION['id'])) { ?>
			<li class="ts-label">Student Portal</li>
			<li><a href="dashboard.php"><i class="bi bi-speedometer2" aria-hidden="true"></i> Dashboard</a></li>
			<li><a href="book-hostel.php"><i class="bi bi-door-open" aria-hidden="true"></i> Book Hostel</a></li>
			<li><a href="room-details.php"><i class="bi bi-house-check" aria-hidden="true"></i> Room Details</a></li>
			<li><a href="register-complaint.php"><i class="bi bi-chat-left-text" aria-hidden="true"></i> Register Complaint</a></li>
			<li><a href="my-complaints.php"><i class="bi bi-kanban" aria-hidden="true"></i> My Complaints</a></li>
			<li><a href="feedback.php"><i class="bi bi-star" aria-hidden="true"></i> Feedback</a></li>
			<li class="ts-label">Account</li>
			<li><a href="my-profile.php"><i class="bi bi-person-circle" aria-hidden="true"></i> My Profile</a></li>
			<li><a href="change-password.php"><i class="bi bi-key" aria-hidden="true"></i> Change Password</a></li>
			<li><a href="access-log.php"><i class="bi bi-clock-history" aria-hidden="true"></i> Access Log</a></li>
		<?php } else { ?>
			<li class="ts-label">Get Started</li>
			<li><a href="registration.php"><i class="bi bi-person-plus" aria-hidden="true"></i> User Registration</a></li>
			<li><a href="index.php"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> User Login</a></li>
			<li><a href="admin/"><i class="bi bi-shield-lock" aria-hidden="true"></i> Admin Login</a></li>
		<?php } ?>
	</ul>
</nav>
