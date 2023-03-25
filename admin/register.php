<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
}

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $c_pass = $_POST['c_pass'];
    $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);

    $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
    $select_admins->execute([$name]);

    if ($select_admins->rowCount() > 0) {
        $warning_msg[] = 'Username already taken!';
    } else {
        if ($pass != $c_pass) {
            $warning_msg[] = 'Password not matched!';
        } else {
            $insert_admin = $conn->prepare("INSERT INTO `admins` (id,name, password, email, phone) VALUES(?,?,?,?,?)");
            $insert_admin->execute([$id, $name, $c_pass, $email, $phone]);
            $success_msg[] = 'Registered successfully!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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

    <!-- -------------register section start------------ -->
    <section class="form-container">
        <form action="" method="POST">
            <h3>register new</h3>
            <input type="text" name="name" placeholder="enter username" maxlength="20" class="box" required oninput="this.value = 
            this.value.replace(/\s/g, '')">

            <input type="email" name="email" placeholder="email" class="box" required oninput="this.value = 
            this.value.replace(/\s/g, '')">

            <input type="number" name="phone" maxlength="10" placeholder="phone no" class="box" required oninput="this.value = 
            this.value.replace(/\s/g, '')">

            <input type="password" name="pass" placeholder="enter password" maxlength="20" class="box" required oninput="this.value = 
            this.value.replace(/\s/g, '')">

            <input type="password" name="c_pass" placeholder="confirm password" maxlength="20" class="box" required oninput="this.value = 
            this.value.replace(/\s/g, '')">

            <input type="submit" value="register now" name="submit" class="btn">
        </form>
    </section>

    <!-- -------------register section end------------ -->

















    <!-- -----------------Sweet alert js cdn link--------------------  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- ---------------------Js file link----------------------- -->
    <script src="../Js/admin_script.js"></script>

    <?php
    include '../components/message.php';
    ?>

</body>

</html>