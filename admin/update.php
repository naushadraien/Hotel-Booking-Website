<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    //    header('location:login.php');
}

$select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    if (!empty($email)) {
        $verify_email = $conn->prepare("SELECT email FROM `admins` WHERE email = ?");
        $verify_email->execute([$email]);
        if ($verify_email->rowCount() > 0) {
            $warning_msg[] = 'email already taken!';
        } else {
            $update_email = $conn->prepare("UPDATE `admins` SET email = ? WHERE id = ?");
            $update_email->execute([$email, $admin_id]);
            $success_msg[] = 'email updated!';

            // Proceed to update phone number if email was updated successfully
            if ($update_email->rowCount() > 0) {
                if (!empty($phone)) {
                    $verify_number = $conn->prepare("SELECT phone FROM `admins` WHERE phone = ?");
                    $verify_number->execute([$phone]);
                    if ($verify_number->rowCount() > 0) {
                        $warning_msg[] = 'number already taken!';
                    } else {
                        $update_number = $conn->prepare("UPDATE `admins` SET phone = ? WHERE id = ?");
                        $update_number->execute([$phone, $admin_id]);
                        $success_msg[] = 'number updated!';
                    }
                }
                if (!empty($name)) {
                    $verify_name = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
                    $verify_name->execute([$name]);
                    if ($verify_name->rowCount() > 0) {
                        $warning_msg[] = 'Username already taken!';
                    } else {
                        $update_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
                        $update_name->execute([$name, $admin_id]);
                        $success_msg[] = 'Username updated!';
                    }
                }
            }
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
    <section class="form-container">
        <form action="" method="POST">
            <h3>update profile</h3>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" required oninput="this.value = 
            this.value.replace(/\s/g, '')">

            <input type="number" name="phone" maxlength="10" placeholder="<?= $fetch_profile['phone']; ?>" class="box" required oninput="this.value = 
            this.value.replace(/\s/g, '')">

            <input type="submit" value="update now" name="submit" class="btn">
        </form>
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