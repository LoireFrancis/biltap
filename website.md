<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="CSS/login.css">
    <title>ConstructQR</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');

    /* Responsive Styles */
    @media (max-width: 768px) {
        .navbar-brand img {
            max-width: 60%;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand img {
            max-width: 50%;
        }
    }

    body {
        margin: 0;
        background-color: black;
        font-family: 'Poppins', sans-serif;
        color: white;
        text-align: justify;
        text-justify: inter-word;
    }

    .login-container {
        width: 300px;
        margin: auto;
    }

    .login-form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    }

    .error-message {
        margin-bottom: 10px;
    }

    .error-text {
        color: #ff0000;
        font-size: 14px;
    }

    .background-container {
        background-image: url('Picture/bg.jpg');
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 80vh;
        overflow: hidden;
        position: relative;
    }

    .content {
        padding: 100px 0;
        position: relative;
        z-index: 1;
    }

    /* parallax effect */
    .parallax {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 200%;
        transform: translateY(-50%);
        z-index: -1;
        background-size: cover;
    }

    /* login modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 999;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: lightyellow;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        position: relative;
        color: #000;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
    }

    .modal-content:hover {
        transform: scale(1.05);
        box-shadow: 0 0 40px rgba(255, 255, 255, 0.5);
    }

    .modal-image-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 20px;
    }

    .modal-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-body {
        padding: 20px;
    }

    .login-form {
        background-color: rgba(255, 255, 255, 1);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
    }

    .error-text {
        color: #ff0000;
        font-size: 14px;
    }

    .form-control {
        border-radius: 5px;
        border: none;
        background-color: rgba(255, 255, 255, 0.2);
        color: black;
        padding: 10px;
        margin-bottom: 15px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
    }

    .form-control:hover {
        background-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .form-control::placeholder {
        color: black;
    }

    .form-control:hover {
        background-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
    }

    .btn-secondary {
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-secondary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    .btn-secondary:active {
        transform: scale(0.95);
    }

    .close {
        color: #000;
        opacity: 1;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }

    /* grind */
    .content-grid {
        --padding-inline: 2rem;
        --content-max-width: 70ch;
        --breakout-max-width: 85ch;

        --breakout-size: calc((var(--breakout-max-width) - var(--content-max-width)) /2);

        display: grid;
        grid-template-columns:
            [full-width-start] minmax(var(--padding-inline), 1fr) [breakout-start] minmax(0, var(--breakout-size)) [content-start] min(100% - (var(--padding-inline) * 2), var(--content-max-width)) [content-end] minmax(0, var(--breakout-size)) [breakout-end] minmax(var(--padding-inline), 1fr) [full-width-end];
    }

    .content-grid>* {
        grid-column: content;
    }

    .content-grid>.breakout {
        grid-column: breakout;
    }

    .content-grid>.full-width {
        grid-column: full-width;
    }

    /* image asset */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
    }

    .circle {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* image project */
    .card-title {
        font-family: 'Poppins', sans-serif;
        text-align: center;
        font-weight: bold;
        color: black;
    }

    /* header */
    .navbar {
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand img {
        max-width: 100%;
    }

    .navbar-nav .nav-link {
        position: relative;
        color: #000000;
        font-weight: bold;
        transition: color 0.3s;
    }

    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: transparent;
        transition: background-color 0.3s;
    }

    .navbar-nav .nav-link:hover {
        color: #007bff;
    }

    .navbar-nav .nav-link:hover::after {
        background-color: #007bff;
    }
</style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="Picture/background.png" alt="Logo"
                    style="width: 80px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about-us-section">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#goal-section">Goal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#projects-section">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#assets-section">Assets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer-section">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer-section">Contact</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn btn-link" onclick="openLoginModal()">Login</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="loginModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ConstructQR</h5>
                    <button type="button" class="close" onclick="closeLoginModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Login Form -->
                    <form id="loginForm" action="login.php" method="POST">
                        <div class="error-message">
                            <?php
                            if (isset($_GET['error'])) {
                                echo "<p class='error-text'>" . $_GET['error'] . "</p>";
                            }
                            ?>
                        </div>
                        <div class="mb-3 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="mb-3 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                        </div>
                        <input type="checkbox" class="chk" id="chk"> Show Password</input><br><br>

                        <button type="submit" class="btn btn-secondary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="background-container">
        <div class="parallax"></div>
    </div>

    <main class="flow content-grid">
        <h1 class="title" id="about-us-section">Builtap Creations</h1>
        <p><i>Builtap Creations </i> is a business entity that specializes in the planning, execution,
            and
            management of building projects. These projects can vary widely in scale and complexity,
            ranging
            from residential homes and commercial buildings to infrastructure such as roads, bridges,
            and dams.
            Construction companies typically employ a diverse range of professionals, including
            architects,
            engineers, project managers, skilled tradespeople, and laborers, who work together to bring
            construction projects to fruition. Their services may include site preparation, building
            design and
            engineering, procurement of materials, construction, and post-construction maintenance and
            support.


        <section>
            <h2 class="content" id="goal-section">What is our goal?</h2>
            <p>Construction companies set goals to ensure the successful completion of projects while
                maintaining high
                standards of safety, quality, and efficiency.
                Key objectives typically include prioritizing safety excellence by reducing accidents
                and adhering to
                strict safety protocols. Additionally, achieving timely project completion is crucial
                for client
                satisfaction and reputation management.
                Cost control and profitability goals involve accurately budgeting projects, optimizing
                resource
                allocation, and maximizing margins. Building strong client relationships is also a
                priority, with a
                focus on understanding client needs, providing exceptional service,
                and fostering open communication.
                Embracing innovation and technology helps enhance productivity and competitiveness,
                while promoting
                sustainability and environmental responsibility aligns with modern societal
                expectations. Investing in
                employee development and engagement fosters a skilled and motivated workforce,
                while community engagement and social responsibility demonstrate a commitment to making
                positive
                contributions beyond project completion.
                Overall, construction companies strive to achieve these goals to deliver successful
                projects, uphold
                industry standards, and contribute positively to their stakeholders and communities.</p>
        </section>

        <section>
            <h2 class="content" id="projects-section">Projects</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="Picture/proj1.jpg" class="card-img-top" alt="Project 1">
                        <div class="card-body">
                            <h5 class="card-title">Buildings</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="Picture/proj2.jpg" class="card-img-top" alt="Project 2">
                        <div class="card-body">
                            <h5 class="card-title">Houses</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="Picture/proj3.jpeg" class="card-img-top" alt="Project 3">
                        <div class="card-body">
                            <h5 class="card-title">Warehouses</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="Picture/proj4.png" class="card-img-top" alt="Project 4">
                        <div class="card-body">
                            <h5 class="card-title">Condos</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <h2 class="content" id="assets-section">Assets</h2>
            <div class="container">
                <div class="circle" onmouseover="openModal('Pictures/asset1.jpg', 'John Doe', 'Engineer', '10')"
                    onmouseout="closeModal()">
                    <img src="Picture/asset1.jpg" alt="Engineer">
                </div>
                <div class="circle" onmouseover="openModal('Pictures/asset2.jpg', 'John Doe', 'Engineer', '10')"
                    onmouseout="closeModal()">
                    <img src="Picture/asset2.jpg" alt="Engineer">
                </div>
                <div class="circle" onmouseover="openModal('Pictures/asset3.jpg', 'John Doe', 'Engineer', '10')"
                    onmouseout="closeModal()">
                    <img src="Picture/asset3.jpg" alt="Engineer">
                </div>
                <div class="circle" onmouseover="openModal('Pictures/asset4.jpg', 'John Doe', 'Engineer', '10')"
                    onmouseout="closeModal()">
                    <img src="Picture/asset4.jpg" alt="Engineer">
                </div>
            </div>
        </section>

        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body">
                        <div class="modal-image-container">
                            <img id="modal-img" src="" alt="Modal Image">
                        </div>
                        <div id="modal-details"></div>
                    </div>
                </div>
            </div>
        </div>
    </main><br><br>

    <center>
        <h5>Biltap Creations</h5>
    </center>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>

        const password = document.getElementById("password");
        const chk = document.getElementById("chk");

        chk.onchange = function (e) {
            password.type = chk.checked ? "text" : "password";
        };

        // hide error message after 1.5 seconds
        function hideErrorMessage() {
            var errorMessage = document.querySelector('.error-text');
            if (errorMessage) {
                setTimeout(function () {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
        }

        window.onload = hideErrorMessage;

        //login modal
        function openLoginModal() {
            var modal = document.getElementById("loginModal");
            modal.style.display = "flex";
        }

        function closeLoginModal() {
            var modal = document.getElementById("loginModal");
            modal.style.display = "none";
        }

        //scroll effect
        $('a[href="#logo-section"]').click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#logo-us-section').offset().top
            }, 1000);
        });
        $('a[href="#about-us-section"]').click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#about-us-section').offset().top
            }, 1000);
        });
        $('a[href="#goal-section"]').click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#goal-section').offset().top
            }, 1000);
        });
        $('a[href="#projects-section"]').click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#projects-section').offset().top
            }, 1000);
        });
        $('a[href="#assets-section"]').click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#assets-section').offset().top
            }, 1000);
        });
        $('a[href="#footer-section"]').click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#footer-section').offset().top
            }, 1000);
        });

        $(window).scroll(function () {
            var scroll = $(this).scrollTop();
            $('.parallax').css('transform', 'translateY(' + scroll / 2 + 'px)');
        });

        //Asset modal
        function openModal(imgSrc, name, profession, experience) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("modal-img");
            var modalDetails = document.getElementById("modal-details");

            modal.style.display = "flex";
            modalImg.src = imgSrc;
            modalDetails.innerHTML = "<h3>Name: " + name + "</h3>" +
                "<p>Profession: " + profession + "</p>" +
                "<p>Years of Experience: " + experience + "</p>";
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        $(document).ready(function () {
            // for tiple bar
            $('.navbar-toggler').click(function () {
                $('.collapse').toggleClass('show');
            });
        });
    </script>

</body>

</html>
<div class="advertisement" id="footer-section">
    <?php include_once ('Temp/footer.html'); ?>
</div>