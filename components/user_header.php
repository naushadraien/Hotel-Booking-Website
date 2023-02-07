<!-- ----------------Header Section Start-------------- -->
<section class="header">
    <div class="flex">
        <a href="#home" class="logo">Hotels and Resorts</a>
        <a href="#availability" class="btn">check availability</a>
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>
    <nav class="navbar">
        <a href="index.php#home">home</a>
        <a href="index.php#about">about</a>
        <a href="index.php#reservation">reservation</a>
        <a href="index.php#gallery">gallery</a>
        <a href="index.php#contact">contact</a>
        <a href="index.php#reviews">reviews</a>
        <!-- Before logging in don't show my bookings options in header and after logging show my bookings option in header -->
        <?php if ($user_id != '') { ?>
            <a href="bookings.php">my bookings</a>
        <?php } ?>

        <div class="dropdown">
            <button class="dropbtn">account <i class="fas fa-angle-down"></i></button>
            <div class="dropdown-content">
                <!-- Before logging in show login now and register now options in header of account section 
                    and after logged in hide login now and register now options in header of account section -->
                <?php if ($user_id == '') { ?>
                    <a href="user_login.php">login now</a>
                    <a href="register.php">register new</a>
                <?php } ?>
                <!-- Before logging in don't show update profile and logout options in header 
            and after logging show update profile and logout options in header -->
                <?php if ($user_id != '') { ?>
                    <a href="user_update.php">update profile</a>
                    <a href="components/user_logout.php" onclick="return confirm('logout from this website?');">logout</a>
                <?php } ?>
            </div>
        </div>
    </nav>
</section>

<!-- ----------------Header Section End-------------- -->