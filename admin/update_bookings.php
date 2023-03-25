<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
}

if (isset($_POST['update'])) {
    $update_id = $_POST['update_id'];
    $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);

    $verify_update = $conn->prepare("SELECT name,email,number,room_no, room_type, check_in, check_out, adults, childs FROM `bookings` WHERE booking_id = ?");
    $verify_update->execute([$update_id]);

    if ($verify_update->rowCount() > 0) {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
        $room_no = filter_var($_POST['room_no'], FILTER_SANITIZE_STRING);
        $room_type = filter_var($_POST['room_type'], FILTER_SANITIZE_STRING);
        $check_in = filter_var($_POST['check_in'], FILTER_SANITIZE_STRING);
        $check_out = filter_var($_POST['check_out'], FILTER_SANITIZE_STRING);
        $adults = filter_var($_POST['adults'], FILTER_SANITIZE_NUMBER_INT);
        $childs = filter_var($_POST['childs'], FILTER_SANITIZE_NUMBER_INT);

        $update_bookings = $conn->prepare("UPDATE `bookings` SET name=?, email=?, number=?,room_no=?, room_type=?, check_in=?, check_out=?, adults=?, childs=? WHERE booking_id = ?");
        $update_bookings->execute([$name, $email, $number,$room_no,$room_type, $check_in, $check_out, $adults, $childs, $update_id]);

        if ($update_bookings->rowCount() > 0) {
            $success_msg[] = 'Booking Updated!';
        } else {
            $warning_msg[] = 'Booking Update Failed!';
        }
    } else {
        $warning_msg[] = 'Booking Not Found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>

    <!-- ------------Font Awesome cdn Link---------------------- -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- --------------Css file link------------------------- -->
    <link rel="stylesheet" href="../Css/admin_style.css">

</head>

<body>
    <!-- --------------header section start------------- -->
    <?php
    include '../components/admin_header.php';
    ?>


    <!-- --------------header section end------------- -->

    <!-- -------------update section start------------ -->
    <section class="grid">
    <h1 class="heading">Update Profile</h1>
    <div class="box-container">
        <?php
        $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
        $select_bookings->execute();
        if ($select_bookings->rowCount() > 0) {
            while ($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <div class="box">
                    <form action="" method="POST" class="form">
                    <label for="name">Name:</label>
                    <input type="text" id="check_in" name="name" value="<?= $fetch_bookings['name']; ?>" class="txt"><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $fetch_bookings['email']; ?>" class="txt"><br>
                    <label for="number">Number:</label>
                    <input type="number" id="check_in" name="number" maxlength="10" value="<?= $fetch_bookings['number']; ?>" class="txt"><br>
                    <label for="check_in">Check In:</label>
                    <input type="date" id="check_in" name="check_in" value="<?= $fetch_bookings['check_in']; ?>" class="date"><br>
                    <label for="check_out">Check Out:</label>
                    <input type="date" id="check_out" name="check_out" value="<?= $fetch_bookings['check_out']; ?>" class="date"><br>
                    <label for="room_no">Room No:</label>
                    <select id="room_no" name="room_no" class="input" value="<?= $fetch_bookings['room_no']; ?>" required>
                        <option <?php if($fetch_bookings['room_no'] == 1) { echo 'selected'; } ?> value="1">1</option>
                        <option <?php if($fetch_bookings['room_no'] == 2) { echo 'selected'; } ?> value="2">2</option>
                        <option <?php if($fetch_bookings['room_no'] == 3) { echo 'selected'; } ?> value="3">3</option>
                        <option <?php if($fetch_bookings['room_no'] == 4) { echo 'selected'; } ?> value="4">4</option>
                        <option <?php if($fetch_bookings['room_no'] == 5) { echo 'selected'; } ?> value="5">5</option>
                        <option <?php if($fetch_bookings['room_no'] == 6) { echo 'selected'; } ?> value="6">6</option>
                        <option <?php if($fetch_bookings['room_no'] == 7) { echo 'selected'; } ?> value="7">7</option>
                        <option <?php if($fetch_bookings['room_no'] == 8) { echo 'selected'; } ?> value="8">8</option>
                        <option <?php if($fetch_bookings['room_no'] == 9) { echo 'selected'; } ?> value="9">9</option>
                        <option <?php if($fetch_bookings['room_no'] == 10) { echo 'selected'; } ?> value="10">10</option>
                        <option <?php if($fetch_bookings['room_no'] == 11) { echo 'selected'; } ?> value="12">11</option>
                        <option <?php if($fetch_bookings['room_no'] == 12) { echo 'selected'; } ?> value="13">12</option>
                        <option <?php if($fetch_bookings['room_no'] == 13) { echo 'selected'; } ?> value="14">13</option>
                        <option <?php if($fetch_bookings['room_no'] == 14) { echo 'selected'; } ?> value="15">14</option>
                        <option <?php if($fetch_bookings['room_no'] == 15) { echo 'selected'; } ?> value="16">15</option>
                        <option <?php if($fetch_bookings['room_no'] == 16) { echo 'selected'; } ?> value="17">16</option>
                        <option <?php if($fetch_bookings['room_no'] == 17) { echo 'selected'; } ?> value="18">17</option>
                        <option <?php if($fetch_bookings['room_no'] == 18) { echo 'selected'; } ?> value="19">18</option>
                        <option <?php if($fetch_bookings['room_no'] == 19) { echo 'selected'; } ?> value="20">19</option>
                        <option <?php if($fetch_bookings['room_no'] == 20) { echo 'selected'; } ?> value="21">20</option>
                        <option <?php if($fetch_bookings['room_no'] == 21) { echo 'selected'; } ?> value="22">21</option>
                        <option <?php if($fetch_bookings['room_no'] == 22) { echo 'selected'; } ?> value="23">22</option>
                        <option <?php if($fetch_bookings['room_no'] == 23) { echo 'selected'; } ?> value="24">23</option>
                        <option <?php if($fetch_bookings['room_no'] == 24) { echo 'selected'; } ?> value="25">24</option>
                        <option <?php if($fetch_bookings['room_no'] == 25) { echo 'selected'; } ?> value="26">25</option>
                        <option <?php if($fetch_bookings['room_no'] == 26) { echo 'selected'; } ?> value="27">26</option>
                        <option <?php if($fetch_bookings['room_no'] == 27) { echo 'selected'; } ?> value="28">27</option>
                        <option <?php if($fetch_bookings['room_no'] == 28) { echo 'selected'; } ?> value="29">28</option>
                        <option <?php if($fetch_bookings['room_no'] == 29) { echo 'selected'; } ?> value="30">29</option>
                        <option <?php if($fetch_bookings['room_no'] == 30) { echo 'selected'; } ?> value="30">30</option>
                    </select><br>
                    <label for="room_type">Room Type:</label>
                    <select id="room_type"  name="room_type" class="input" value="<?= $fetch_bookings['room_type']; ?>" required>
                        <option value="single bed">single bed</option>
                        <option value="double bed">double bed</option>
                        <option value="family">family</option>
                    </select><br>
                    <label for="adults">Adults:</label>
                   
               <input type="number" minlength="0" maxlength="4" name="adults" value="<?= $fetch_bookings['adults'];?>" class="num">

                    <label for="childs">Childs:</label>
                    
                    <input type="number" minlength="0" maxlength="4" name="childs" value="<?= $fetch_bookings['childs'];?>" class="num">
                    <br><br>
                    <input type="hidden" name="update_id" value="<?= $fetch_bookings['booking_id']; ?>">
                    <input type="submit" value="Update Booking" onclick="return confirm('Are you sure you want to update this booking?');" name="update" class="btn">
                </form>
                </div>

            <?php
            }
        } else {
            ?>
            </div>
            <div class="box" style="text-align: center;">
                <p>no bookings found!</p>
                <a href="dashboard.php" class="btn">go to home</a>
            </div>
        <?php
        }
        ?>
    </section>

    <!-- -------------update section end------------ -->


    <!-- -----------------Sweet alert js cdn link--------------------  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- ---------------------Js file link----------------------- -->
    <script src="../Js/admin_script.js"></script>

    <?php
    include '../components/message.php';
    ?>

</body>

</html>