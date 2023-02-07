<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">AdminPanel.</a>
        <nav class="navbar">
            <a href="dashboard.php">home</a>
            <a href="bookings.php">bookings</a>
            <a href="users.php">users</a>
            <a href="admins.php">admins</a>
            <a href="messages.php">messages</a>
            <a href="register.php">register</a>
            <!-- Before logging in show login options in header of account section 
                    and after logged in hide login options in header of account section -->
            <?php if ($admin_id == '') { ?>
                <a href="login.php">login</a>
            <?php } ?>
            <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');">logout</a>
        </nav>
        <div id="menu-btn" class="fas fa-bars"></div>
    </section>
</header>