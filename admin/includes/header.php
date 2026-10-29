<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="../css/modern-ui.css">
<script defer src="../js/modern-ui.js"></script>

<header class="brand hms-header">
	<a href="dashboard.php" class="logo" aria-label="Hostel Management System admin home">
		<span class="brand-mark"><i class="bi bi-buildings" aria-hidden="true"></i></span>
		<span>Hostel Admin</span>
	</a>

	<div class="hms-header-actions">
		<button class="menu-btn" type="button" aria-label="Toggle navigation" aria-controls="hms-sidebar" aria-expanded="false">
			<i class="bi bi-list" aria-hidden="true"></i>
		</button>

		<button class="hms-icon-button" type="button" data-theme-toggle aria-label="Toggle theme">
			<i class="bi bi-moon-stars" aria-hidden="true"></i>
		</button>

		<div class="hms-dropdown">
			<button class="hms-icon-button" type="button" aria-label="Notifications">
				<i class="bi bi-bell" aria-hidden="true"></i>
				<span class="hms-notification-dot" aria-hidden="true"></span>
			</button>
			<div class="hms-dropdown-menu">
				<a href="new-complaints.php"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> New complaints</a>
				<a href="feedbacks.php"><i class="bi bi-star" aria-hidden="true"></i> Student feedback</a>
			</div>
		</div>

		<ul class="ts-profile-nav" aria-label="Account menu">
			<li class="ts-account">
				<a href="admin-profile.php">
					<img src="img/software-engineer.png" class="ts-avatar hidden-side" alt="">
					<span class="hidden-side">Admin</span>
					<i class="bi bi-chevron-down hidden-side" aria-hidden="true"></i>
				</a>
				<ul>
					<li><a href="admin-profile.php"><i class="bi bi-person-gear" aria-hidden="true"></i> My Account</a></li>
					<li><a href="logout.php"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Logout</a></li>
				</ul>
			</li>
		</ul>
	</div>
</header>
