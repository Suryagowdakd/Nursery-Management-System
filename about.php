<?php

@include 'config.php';

session_start();

// $user_id = $_SESSION['user_id'];

// if(!isset($user_id)){
//    header('location:login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>about</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <!-- <h3>about us</h3> -->
        <p> <a href="home.php">home</a> / about </p>
    </section>

    <section class="about">

        <div class="flex">

            <div class="image">
                <img src="images/slide2.jpg" alt="">
            </div>

            <div class="content">
                <h3>why choose us?</h3>
                <p align="justify" style="font-size:1.8rem">Embark on your gardening journey with confidence by choosing our Plant Nursery. We stand out as your ideal partner for several reasons. Our wide selection of healthy and vibrant plants is nurtured by experienced horticulturists who are passionate about their craft. We prioritize quality, ensuring that every plant you bring home has the potential to flourish. With expert advice and personalized assistance, we're here to guide you, whether you're a seasoned gardener or just starting.</p>
                <a href="shop.php" class="btn">shop now</a>
            </div>

        </div>

        <div class="flex">

            <div class="content">
                <h3>what we provide?</h3>
                <p align="justify" style="font-size:1.8rem">At our Plant Nursery, we offer a diverse range of plants that cater to every gardening enthusiast. From ornamental blooms to lush greenery, our collection has something for everyone. Our knowledgeable staff is ready to share insights and recommendations, helping you choose the perfect plants for your space and preferences. Additionally, we provide gardening supplies, soil, and accessories to equip you with all you need for a successful gardening experience. Your journey to create a thriving garden begins with us.</p>
                <a href="contact.php" class="btn">contact us</a>
            </div>

            <div class="image">
                <img src="images/slide1.jpg" alt="">
            </div>

        </div>


    </section>













    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>