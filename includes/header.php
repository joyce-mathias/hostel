<?php
$isLoggedIn = !empty($_SESSION['id']);
$homeLink = $isLoggedIn ? 'dashboard.php' : 'index.php';
?>
<header class="brand hms-header">
	<a href="<?php echo $homeLink; ?>" class="logo" aria-label="Hostel Management System home">
		<span class="brand-mark"><i class="bi bi-buildings" aria-hidden="true"></i></span>
		<span>Hostel Management</span>
	</a>

	<div class="hms-header-actions">
		<button class="menu-btn" type="button" aria-label="Toggle navigation" aria-controls="hms-sidebar" aria-expanded="false">
			<i class="bi bi-list" aria-hidden="true"></i>
		</button>

		<button class="hms-icon-button" type="button" data-theme-toggle aria-label="Toggle theme">
			<i class="bi bi-moon-stars" aria-hidden="true"></i>
		</button>

		<?php if($isLoggedIn) { ?>
		<div class="hms-dropdown">
			<button class="hms-icon-button" type="button" aria-label="Notifications">
				<i class="bi bi-bell" aria-hidden="true"></i>
				<span class="hms-notification-dot" aria-hidden="true"></span>
			</button>
			<div class="hms-dropdown-menu">
				<a href="my-complaints.php"><i class="bi bi-chat-square-text" aria-hidden="true"></i> Complaint updates</a>
				<a href="room-details.php"><i class="bi bi-door-open" aria-hidden="true"></i> Room information</a>
			</div>
		</div>

		<ul class="ts-profile-nav" aria-label="Account menu">
			<li class="ts-account">
				<a href="my-profile.php">
					<img src="img/ts-avatar.jpg" class="ts-avatar hidden-side" alt="">
					<span class="hidden-side">Student</span>
					<i class="bi bi-chevron-down hidden-side" aria-hidden="true"></i>
				</a>
				<ul>
					<li><a href="my-profile.php"><i class="bi bi-person-circle" aria-hidden="true"></i> My Account</a></li>
					<li><a href="change-password.php"><i class="bi bi-key" aria-hidden="true"></i> Change Password</a></li>
					<li><a href="logout.php"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Logout</a></li>
				</ul>
			</li>
		</ul>
		<?php } ?>
	</div>
</header>
