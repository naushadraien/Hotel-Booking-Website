<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM `users` WHERE id= ?");
    $verify_delete->execute([$delete_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_admin = $conn->prepare("DELETE FROM `users` WHERE id = ?");
        $delete_admin->execute([$delete_id]);
        $success_msg[] = 'User deleted!';
    } else {
        $warning_msg[] = 'User deleted already!';
    }
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
        <h1 class="heading">users</h1>
        <div class="box-container">

            <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            if ($select_users->rowCount() > 0) {
                while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <p>name: <span><?= $fetch_users['name']; ?></span></p>
                        <form action="" method="POST">
                            <input type="hidden" name="delete_id" value="<?= $fetch_users['id']; ?>">
                            <input type="submit" value="delete users" onclick="return confirm('delete this admin');" name="delete" class="btn">
                        </form>
                    </div>
            <?php
                }
            } else {
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