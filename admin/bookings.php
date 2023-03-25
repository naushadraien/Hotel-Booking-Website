<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
}

if(isset($_POST['delete'])){
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
    
    $verify_delete = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id= ?");
    $verify_delete->execute([$delete_id]);

    if($verify_delete->rowCount() > 0){
        $delete_bookings = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
        $delete_bookings->execute([$delete_id]);
        $success_msg[] ='Booking deleted!';
    }else{
        $warning_msg[] = 'Booking deleted already!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>

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

    <!-- ------------bookings section start----------- -->
    <section class="grid">
        <h1 class="heading">bookings</h1>
        <div class="box-container">
            <?php
            $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
            $select_bookings->execute();
            if ($select_bookings->rowCount() > 0) {
                while ($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <p>booking id: <span><?= $fetch_bookings['booking_id']; ?></span></p>
                        <p>name: <span><?= $fetch_bookings['name']; ?></span></p>
                        <p>email: <span><?= $fetch_bookings['email']; ?></span></p>
                        <p>number: <span><?= $fetch_bookings['number']; ?></span></p>
                        <p>check in: <span><?= $fetch_bookings['check_in']; ?></span></p>
                        <p>check out: <span><?= $fetch_bookings['check_out']; ?></span></p>
                        <p>room no: <span><?= $fetch_bookings['room_no']; ?></span></p>
                        <p>room type: <span><?= $fetch_bookings['room_type']; ?></span></p>
                        <p>adults: <span><?= $fetch_bookings['adults']; ?></span></p>
                        <p>childs: <span><?= $fetch_bookings['childs']; ?></span></p>
                        <form action="" method="POST">
                            <input type="hidden" name="delete_id" value="<?= $fetch_bookings['booking_id']; ?>">
                            <input type="submit" value="delete bookings" onclick="return confirm('delete this booking');" name="delete" class="btn">
                        </form>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="box" style="text-align: center;">
                    <p>no bookings found!</p>
                    <a href="dashboard.php" class="btn">go to home</a>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="box" style="text-align: center;">
        <a href="update_bookings.php" class="btn">Update Bookings</a> 
        </div>

    </section>
    <!-- ------------bookings section end----------- -->



















    <!-- -----------------Sweet alert js cdn link--------------------  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- ---------------------Js file link----------------------- -->
    <script src="../Js/admin_script.js"></script>

    <?php
    include '../components/message.php';
    ?>
</body>

</html>