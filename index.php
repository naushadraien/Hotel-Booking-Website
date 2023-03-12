<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {

    //setcookie function is removed to hide the options update profile and logout when reloading or opening the index.php page in account section of header
    // setcookie('user_id', create_unique_id(), time() + 60 * 60 * 24 * 30, '/');
    $user_id = '';
    // Below header('location:index.php') should be removed for showing and hiding the update and logout button in user login page
    // header('location:index.php');
}

// ------------checking availability of rooms ---------------
if (isset($_POST['check'])) {

    $check_in = $_POST['check_in'];
    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
    $check_out = $_POST['check_out'];
    $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
    $current_date = date('Y-m-d');
    $room_no = $_POST['room_no'];
    $room_type = $_POST['room_type'];

    $total_rooms = 0;

    $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
    $check_bookings->execute([$check_in]);

    if ($check_in < $current_date) {
        $warning_msg[] = 'Invalid check-in date';
    } else if ($check_out <= $check_in) {
        $warning_msg[] = 'Invalid check-out date';
    } else if ($room_no == '-') {
        $warning_msg[] = 'Please select a room number';
    } else {
        // Check if the room is available for the selected dates
        $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
        $check_bookings->execute([$check_in]);
        while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
            $total_rooms += $fetch_bookings['rooms'];
            // Check if the selected room is already booked by other user for the selected dates
            if ($fetch_bookings['room_no'] == $room_no) {
                $warning_msg[] = 'This room is already booked for the selected dates.';
            }
        }
    }

    // if the hotel has total 30 rooms 
    if ($total_rooms >= 30) {
        $warning_msg[] = 'rooms are not available';
    } else {
        $success_msg[] = 'rooms are available';
    }
}

//   ---------------For booking of rooms ------------------

//here Phpmailer is used for sending the mail when user booked a room
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('./PhpMail/Exception.php');
require('./PhpMail/PHPMailer.php');
require('./PhpMail/SMTP.php');

if (isset($_POST['book'])) {
    // Added if($user_id != '') function and at last before the main closing bracket as } added else{ $warning_msg[] = 'please login first!'; }
    if ($user_id != '') {
        $booking_id = create_unique_id();
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);
        // $rooms = $_POST['rooms'];
        // $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
        $room_no = $_POST['room_no'];
        $room_no = filter_var($room_no, FILTER_SANITIZE_STRING);
        $room_type = $_POST['room_type'];
        $room_type = filter_var($room_type, FILTER_SANITIZE_STRING);
        $check_in = $_POST['check_in'];
        $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
        $check_out = $_POST['check_out'];
        $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
        $current_date = date('Y-m-d');
        $adults = $_POST['adults'];
        $adults = filter_var($adults, FILTER_SANITIZE_STRING);
        $childs = $_POST['childs'];
        $childs = filter_var($childs, FILTER_SANITIZE_STRING);

        //here mail sending main code starts from here
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'rohankumar770123@gmail.com';                     //SMTP username
            $mail->Password   = 'jxtqclpngmsgpizd';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('rohankumar770123@gmail.com', 'Rohan');
            $mail->addAddress($email);     //Add a recipient
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Room Booked Successfully';
            $mail->Body    = "Name: $name <br> Email: $email <br> Mobile No: $number<br>Room No: $room_no <br> Room Type: $room_type <br> Check In: $check_in <br> Check Out: $check_out <br> Adults: $adults <br> Childs: $childs";
        
            $mail->send();
            $success_msg[] = 'room booked successfully!';
        } catch (Exception $e) {
            $warning_msg[] = 'Server Error!';
        }
        //Mail code ends here

        $total_rooms = 0;

        // Check if the checked-in and checked-out dates are not in the past
        if ($check_in < $current_date) {
            $warning_msg[] = 'Invalid check-in date';
        } else if ($check_out <= $check_in) {
            $warning_msg[] = 'Invalid check-out date';
        } else if ($room_no == '-') {
            $warning_msg[] = 'Please select a room number';
        } else {
            // Check if the room is available for the selected dates
            $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
            $check_bookings->execute([$check_in]);
            while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
                $total_rooms += $fetch_bookings['rooms'];
                // Check if the selected room is already booked by other user for the selected dates
                if ($fetch_bookings['room_no'] == $room_no) {
                    $warning_msg[] = 'This room is already booked for the selected dates.';
                }
            }

            if ($total_rooms >= 30) {
                $warning_msg[] = 'Rooms are not available';
            } else {
                $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE room_no = ? AND ((check_in <= ? AND check_out >= ?) OR (check_in >= ? AND check_out <= ?) OR (check_in <= ? AND check_out >= ?))");
                $verify_bookings->execute([$room_no, $check_in, $check_out, $check_in, $check_out, $check_in, $check_out]);

                if ($verify_bookings->rowCount() > 0) {
                    $warning_msg[] = 'room is already booked for the selected date range!';
                } else {
                    $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, check_in, check_out, adults, childs, room_no, room_type) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                    $book_room->execute([$booking_id, $user_id, $name, $email, $number, $check_in, $check_out, $adults, $childs, $room_no, $room_type]);
                    $success_msg[] = 'room booked successfully!';
                }
            }
        }
    } else {
        $warning_msg[] = 'Please login first!';
    }
}


//   ----------------For Sending of Message in Send us message section------------
if (isset($_POST['send'])) {

    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $message = $_POST['message'];
    $message = filter_var($message, FILTER_SANITIZE_STRING);

    $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $verify_message->execute([$name, $email, $number, $message]);

    if ($verify_message->rowCount() > 0) {
        $warning_msg[] = 'message sent already!';
    } else {
        $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
        $insert_message->execute([$id, $name, $email, $number, $message]);
        $success_msg[] = 'message send successfully!';
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- -----------------Swiper style cdn link--------------------  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

    <!-- ------------Font Awesome cdn Link---------------------- -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- ------------------Css File------------ -->
    <link rel="stylesheet" href="Css/style.css">
</head>

<body>
    <!-- -------------php header file link from components folder-----------  -->
    <?php
    include 'components/user_header.php';
    ?>

    <!-- --------------------Home section start--------------------------- -->
    <section class="home" id="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">
                <div class="box swiper-slide">
                    <img src="Images/home-img-1.jpg" alt="">
                    <div class="flex">
                        <h3>luxurious rooms</h3>
                        <a href="#availability" class="btn">check availability</a>
                    </div>
                </div>

                <div class="box swiper-slide">
                    <img src="Images/home-img-2.jpg" alt="">
                    <div class="flex">
                        <h3>foods and drinks </h3>
                        <a href="#reservation" class="btn">make a reservation</a>
                    </div>
                </div>

                <div class="box swiper-slide">
                    <img src="Images/home-img-3.jpg" alt="">
                    <div class="flex">
                        <h3>luxurious halls</h3>
                        <a href="#contact" class="btn">contact us</a>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!-- --------------------Home section end--------------------------- -->


    <!-- ---------------Availability Section Start---------------------------  -->
    <section class="availability" id="availability">
        <form action="" method="post">
            <div class="flex">
                <div class="box">
                    <p>check in <span>*</span></p>
                    <input type="date" name="check_in" class="input" required>
                </div>
                <div class="box">
                    <p>check out <span>*</span></p>
                    <input type="date" name="check_out" class="input" required>
                </div>
                <div class="box">
                    <p>adults <span>*</span></p>
                    <select name="adults" class="input" required>
                        <option value="1">1 adult</option>
                        <option value="2">2 adults</option>
                        <option value="3">3 adults</option>
                        <option value="4">4 adults</option>
                        <option value="5">5 adults</option>
                        <option value="6">6 adults</option>
                    </select>
                </div>
                <div class="box">
                    <p>childs <span>*</span></p>
                    <select name="childs" class="input" required>
                        <option value="-">0 child</option>
                        <option value="1">1 child</option>
                        <option value="2">2 childs</option>
                        <option value="3">3 childs</option>
                        <option value="4">4 childs</option>
                        <option value="5">5 childs</option>
                    </select>
                </div>
                <!-- <div class="box">
                    <p>rooms <span>*</span></p>
                    <select name="rooms" class="input" required>
                        <option value="1">1 room</option>
                        <option value="2">2 rooms</option>
                        <option value="3">3 rooms</option>
                        <option value="4">4 rooms</option>
                        <option value="5">5 rooms</option>
                    </select>
                </div> -->
                <div class="box">
                    <p>room no. <span>*</span></p>
                    <select name="room_no" class="input" required>
                        <option value="-">select your room no.</option>
                        <option value="1">room no. 1</option>
                        <option value="2">room no. 2</option>
                        <option value="3">room no. 3</option>
                        <option value="4">room no. 4</option>
                        <option value="5">room no. 5</option>
                        <option value="6">room no. 6</option>
                        <option value="7">room no. 7</option>
                        <option value="8">room no. 8</option>
                        <option value="9">room no. 9</option>
                        <option value="10">room no. 10</option>
                        <option value="12">room no. 12</option>
                        <option value="13">room no. 13</option>
                        <option value="14">room no. 14</option>
                        <option value="15">room no. 15</option>
                        <option value="16">room no. 16</option>
                        <option value="17">room no. 17</option>
                        <option value="18">room no. 18</option>
                        <option value="19">room no. 19</option>
                        <option value="20">room no. 20</option>
                        <option value="21">room no. 21</option>
                        <option value="22">room no. 22</option>
                        <option value="23">room no. 23</option>
                        <option value="24">room no. 24</option>
                        <option value="25">room no. 25</option>
                        <option value="26">room no. 26</option>
                        <option value="27">room no. 27</option>
                        <option value="28">room no. 28</option>
                        <option value="29">room no. 29</option>
                        <option value="30">room no. 30</option>
                    </select>
                </div>
                <div class="box">
                    <p>room types <span>*</span></p>
                    <select name="room_type" class="input" required>
                        <option value="single bed" selected>single bed</option>
                        <option value="double bed">double bed</option>
                        <option value="family">family</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="check availability" name="check" class="btn">
        </form>
    </section>


    <!-- ---------------Availability Section End---------------------------  -->

    <!-- --------------------------About Section Start---------------------  -->
    <section class="about" id="about">
        <div class="row">
            <div class="image">
                <img src="Images/about-img-1.jpg" alt="">
            </div>
            <div class="content">
                <h3>best staffs</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est dolorum iste at repellat adipisci
                    explicabo.</p>
                <a href="#reservation" class="btn">make a reservation</a>
            </div>
        </div>

        <div class="row reverse">
            <div class="image">
                <img src="Images/about-img-2.jpg" alt="">
            </div>
            <div class="content">
                <h3>best foods</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est dolorum iste at repellat adipisci
                    explicabo.</p>
                <a href="#contact" class="btn">contact us</a>
            </div>
        </div>

        <div class="row">
            <div class="image">
                <img src="Images/about-img-3.jpg" alt="">
            </div>
            <div class="content">
                <h3>swimming pool</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est dolorum iste at repellat adipisci
                    explicabo.</p>
                <a href="#availability" class="btn">check availability</a>
            </div>
        </div>

    </section>

    <!-- --------------------------About Section End---------------------  -->

    <!-- ---------------------------Services section start-------------------------- -->
    <section class="services">
        <div class="box-container">
            <div class="box">
                <img src="Images/icon-1.png" alt="">
                <h3>food and drinks</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, quia!</p>
            </div>

            <div class="box">
                <img src="Images/icon-2.png" alt="">
                <h3>outdoor dinning</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, quia!</p>
            </div>

            <div class="box">
                <img src="Images/icon-3.png" alt="">
                <h3>beach view</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, quia!</p>
            </div>

            <div class="box">
                <img src="Images/icon-4.png" alt="">
                <h3>decorations</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, quia!</p>
            </div>

            <div class="box">
                <img src="Images/icon-5.png" alt="">
                <h3>swimming pool</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, quia!</p>
            </div>

            <div class="box">
                <img src="Images/icon-6.png" alt="">
                <h3>resort beach</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, quia!</p>
            </div>
        </div>
    </section>

    <!-- ---------------------------Services section end-------------------------- -->


    <!-- --------------------------Reservation section start------------------------------  -->
    <section class="reservation" id="reservation">

        <form action="" method="post">
            <h3>make a reservation</h3>
            <div class="flex">
                <div class="box">
                    <p>your name <span>*</span></p>
                    <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="input">
                </div>
                <div class="box">
                    <p>your email <span>*</span></p>
                    <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="input">
                </div>
                <div class="box">
                    <p>your number <span>*</span></p>
                    <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="enter your number" class="input">
                </div>
                <!-- <div class="box">
                    <p>rooms <span>*</span></p>
                    <select name="rooms" class="input" required>
                        <option value="1" selected>1 room</option>
                        <option value="2">2 rooms</option>
                        <option value="3">3 rooms</option>
                        <option value="4">4 rooms</option>
                        <option value="5">5 rooms</option>
                        <option value="6">6 rooms</option>
                    </select>
                </div> -->
                <div class="box">
                    <p>room no. <span>*</span></p>
                    <select name="room_no" class="input" required>
                        <option value="-">select your room no.</option>
                        <option value="1">room no. 1</option>
                        <option value="2">room no. 2</option>
                        <option value="3">room no. 3</option>
                        <option value="4">room no. 4</option>
                        <option value="5">room no. 5</option>
                        <option value="6">room no. 6</option>
                        <option value="7">room no. 7</option>
                        <option value="8">room no. 8</option>
                        <option value="9">room no. 9</option>
                        <option value="10">room no. 10</option>
                        <option value="12">room no. 12</option>
                        <option value="13">room no. 13</option>
                        <option value="14">room no. 14</option>
                        <option value="15">room no. 15</option>
                        <option value="16">room no. 16</option>
                        <option value="17">room no. 17</option>
                        <option value="18">room no. 18</option>
                        <option value="19">room no. 19</option>
                        <option value="20">room no. 20</option>
                        <option value="21">room no. 21</option>
                        <option value="22">room no. 22</option>
                        <option value="23">room no. 23</option>
                        <option value="24">room no. 24</option>
                        <option value="25">room no. 25</option>
                        <option value="26">room no. 26</option>
                        <option value="27">room no. 27</option>
                        <option value="28">room no. 28</option>
                        <option value="29">room no. 29</option>
                        <option value="30">room no. 30</option>
                    </select>
                </div>
                <div class="box">
                    <p>room types <span>*</span></p>
                    <select name="room_type" class="input" required>
                        <option value="single bed" selected>single bed</option>
                        <option value="double bed">double bed</option>
                        <option value="family">family</option>
                    </select>
                </div>
                <div class="box">
                    <p>check in <span>*</span></p>
                    <input type="date" name="check_in" class="input" required>
                </div>
                <div class="box">
                    <p>check out <span>*</span></p>
                    <input type="date" name="check_out" class="input" required>
                </div>
                <div class="box">
                    <p>adults <span>*</span></p>
                    <select name="adults" class="input" required>
                        <option value="1" selected>1 adult</option>
                        <option value="2">2 adults</option>
                        <option value="3">3 adults</option>
                        <option value="4">4 adults</option>
                        <option value="5">5 adults</option>
                        <option value="6">6 adults</option>
                    </select>
                </div>
                <div class="box">
                    <p>childs <span>*</span></p>
                    <select name="childs" class="input" required>
                        <option value="0" selected>0 child</option>
                        <option value="1">1 child</option>
                        <option value="2">2 childs</option>
                        <option value="3">3 childs</option>
                        <option value="4">4 childs</option>
                        <option value="5">5 childs</option>
                        <option value="6">6 childs</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="book now" name="book" class="btn">
        </form>

    </section>

    <!-- --------------------------Reservation section end------------------------------  -->

    <!-- -------------------------Gallery Section Start------------------------------------  -->
    <section class="gallery" id="gallery">
        <div class="swiper gallery-slider">
            <div class="swiper-wrapper">
                <img src="Images/gallery-img-1.jpg" class="swiper-slide" alt="">
                <img src="Images/gallery-img-2.webp" class="swiper-slide" alt="">
                <img src="Images/gallery-img-3.webp" class="swiper-slide" alt="">
                <img src="Images/gallery-img-4.webp" class="swiper-slide" alt="">
                <img src="Images/gallery-img-5.webp" class="swiper-slide" alt="">
                <img src="Images/gallery-img-6.webp" class="swiper-slide" alt="">
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- -------------------------Gallery Section end------------------------------------  -->

    <!-- ------------------Contact Section Start-------------------------------  -->
    <section class="contact" id="contact">
        <div class="row">
            <form action="" method="post">
                <h3>send us message</h3>
                <input type="text" name="name" required maxlength="50" placeholder="Enter Your Name" class="box">
                <input type="email" name="email" required maxlength="50" placeholder="Enter Your email" class="box">
                <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="Enter Your number" class="box">
                <textarea name="message" class="box" required maxlength="1000" placeholder="Enter Your Message" cols="30" rows="10"></textarea>
                <input type="submit" value="send message" name="send" class="btn">
            </form>

            <div class="faq">
                <h3 class="title">frequently asked questions</h3>
                <div class="box active">
                    <h3>how to cancel?</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda autem deserunt nihil,
                        doloribus maxime pariatur!</p>
                </div>
                <div class="box">
                    <h3>is there any vacancy?</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda autem deserunt nihil,
                        doloribus maxime pariatur!</p>
                </div>
                <div class="box">
                    <h3>what are payment methods?</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda autem deserunt nihil,
                        doloribus maxime pariatur!</p>
                </div>
                <div class="box">
                    <h3>how to claim coupons codes?</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda autem deserunt nihil,
                        doloribus maxime pariatur!</p>
                </div>
                <div class="box">
                    <h3>what are the age requirements?</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda autem deserunt nihil,
                        doloribus maxime pariatur!</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ------------------Contact Section End-------------------------------  -->

    <!-- -------------------Reviews Section Start--------------------------  -->
    <section class="reviews" id="reviews">
        <div class="swiper reviews-slider">

            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <img src="Images/pic-1.png" alt="">
                    <h3>John Deo</h3>
                    <p>"Our room was enormous, with a nice view of the river.
                        It was 105 degrees (Fahrenheit) when we stayed but every thing was kept cool and pleasant Very centrally located.
                        Staff were very welcoming and helpful.
                        "</p>
                </div>
                <div class="swiper-slide box">
                    <img src="Images/pic-2.png" alt="">
                    <h3>Lara</h3>
                    <p>"Keep on doing what you do, special and unique.
                        Some people will love this hotel and some will complain about the steps up to it.
                        But as everyone knows every journey begins with one step.
                        This hotel has great people, great location and great presence.
                        Take rooms here for a month or two and Florence becomes your greatest friend."</p>
                </div>
                <div class="swiper-slide box">
                    <img src="Images/pic-3.png" alt="">
                    <h3>Peter</h3>
                    <p>"I spent a few days at this wonderful hotel! It had a combination of old world ambience and everyday comfort.
                        The staff were so patient and kind when I practiced my Italian with them and I so much appreciated that.
                        I will definitely return!"</p>
                </div>
                <div class="swiper-slide box">
                    <img src="Images/pic-4.png" alt="">
                    <h3>Gwen</h3>
                    <p>"Attended my friend wedding in this Hotel.
                        Marvellous decorations Amazing lawn Nice food really it was a total package with room banquet Hall lobby lawn.
                        We enjoyed a lot
                        One should come and check this newly renovated Hotel with its amazing HOSPITALITY"</p>
                </div>
                <div class="swiper-slide box">
                    <img src="Images/pic-5.png" alt="">
                    <h3>Edward</h3>
                    <p>"Great location, really pleasant and clean rooms, but the thing that makes this such a good place to stay are the staff.
                        Saurav and yashpal are incredibly helpful and generous with their time and advice."</p>
                </div>
                <div class="swiper-slide box">
                    <img src="Images/pic-6.png" alt="">
                    <h3>John Deo</h3>
                    <p>"Near to the city and public transport, friendly staff, clean and calm rooms, good breakfast...
                        Everything was well.Hotel is clean as well as rooms and restaurant.
                        Although in the center, it is very silent Service is perfect.
                        Breakfast is good with large variety.
                        Hotel is very well accessible by tram from port. Railway- and busstation are close, not long way to walk."</p>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- -------------------Reviews Section End--------------------------  -->
    <!-- ------------------------------For Scroll up button---------------------------- -->
    <section>
        <div class="scroll-up-btn">
            <i class="fas fa-angle-up"></i>
        </div>
    </section>

    <!-- -------------php footer file link from components folder-----------  -->
    <?php
    include 'components/footer.php';
    ?>

    <!-- -----------------Swiper js cdn link--------------------  -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

    <!-- -----------------Sweet alert js cdn link--------------------  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- ------------------Js File---------------------- -->
    <script src="Js/script.js"></script>

    <?php
    include 'components/message.php';
    ?>

</body>

</html>