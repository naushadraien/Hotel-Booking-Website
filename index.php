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

    $total_rooms = 0;

    $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
    $check_bookings->execute([$check_in]);

    while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
        $total_rooms += $fetch_bookings['rooms'];
    }

    // if the hotel has total 30 rooms 
    if ($total_rooms >= 30) {
        $warning_msg[] = 'rooms are not available';
    } else {
        $success_msg[] = 'rooms are available';
    }
}

//   ---------------For booking of rooms ------------------
if (isset($_POST['book'])) {
    // Added if($user_id != '') function and at last before the main closing bracket as } added else{ $warning_msg[] = 'please login first!'; }
    if($user_id != ''){
        
    $booking_id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $rooms = $_POST['rooms'];
    $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
    $check_in = $_POST['check_in'];
    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
    $check_out = $_POST['check_out'];
    $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
    $adults = $_POST['adults'];
    $adults = filter_var($adults, FILTER_SANITIZE_STRING);
    $childs = $_POST['childs'];
    $childs = filter_var($childs, FILTER_SANITIZE_STRING);

    $total_rooms = 0;

    $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
    $check_bookings->execute([$check_in]);

    while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
        $total_rooms += $fetch_bookings['rooms'];
    }

    if ($total_rooms >= 30) {
        $warning_msg[] = 'rooms are not available';
    } else {

        $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
        $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

        if ($verify_bookings->rowCount() > 0) {
            $warning_msg[] = 'room booked already!';
        } else {
            $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
            $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
            $success_msg[] = 'room booked successfully!';
        }
    }
}else{
    $warning_msg[] = 'please login first!';
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
                <div class="box">
                    <p>rooms <span>*</span></p>
                    <select name="rooms" class="input" required>
                        <option value="1">1 room</option>
                        <option value="2">2 rooms</option>
                        <option value="3">3 rooms</option>
                        <option value="4">4 rooms</option>
                        <option value="5">5 rooms</option>
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
                <div class="box">
                    <p>rooms <span>*</span></p>
                    <select name="rooms" class="input" required>
                        <option value="1" selected>1 room</option>
                        <option value="2">2 rooms</option>
                        <option value="3">3 rooms</option>
                        <option value="4">4 rooms</option>
                        <option value="5">5 rooms</option>
                        <option value="6">6 rooms</option>
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