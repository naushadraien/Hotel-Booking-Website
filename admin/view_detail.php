<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins</title>

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

    <!-- ------------admins section start----------- -->
    <section class="grid">
        <h1 class="heading">Your Profile Details:</h1>
        <div class="box-container">

            <?php
            $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
            $select_admin->execute([$admin_id]);
            $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
            
            if ($fetch_admin) {
            ?>
                <div class="box">
                    <p>Name: <span><?= $fetch_admin['name']; ?></span></p>
                    <p>Email: <span><?= $fetch_admin['email']; ?></span></p>
                    <p>Phone Number: <span><?= $fetch_admin['phone']; ?></span></p>
                </div>
            <?php
            } else {
                echo "No admins found";
            }
            ?>
        </div>
    </section>
    <!-- ------------admins section end----------- -->



















    <!-- -----------------Sweet alert js cdn link--------------------  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- ---------------------Js file link----------------------- -->
    <script src="../Js/admin_script.js"></script>

    <?php
    include '../components/message.php';
    ?>
</body>

</html>