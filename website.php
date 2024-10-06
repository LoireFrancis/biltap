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

    .background-container {
        background-image: url('Picture/bg.jpg');
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 70vh;
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

    /* Ensure the dropdown stays open on hover */
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
        margin-left: 0;
        opacity: 1;
    }

    /* Optional: Smooth dropdown transition */
    .nav-item.dropdown .dropdown-menu {
        transition: opacity 0.2s ease-in-out;
        opacity: 0;
    }

    /* Fix for positioning */
    .navbar-nav .dropdown-menu {
        position: absolute;
    }
</style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="website.php"><img src="Picture/background.png" alt="Logo" style="width: 80px;">
                Biltap Creations</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- Home Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="homeDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Home
                        </a>
                        <div class="dropdown-menu" aria-labelledby="homeDropdown">
                            <a class="dropdown-item" href="#about-us-section">About Us</a>
                            <a class="dropdown-item" href="#projects-section">Projects</a>
                        </div>
                    </li>

                    <!-- Company Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="companyDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Company
                        </a>
                        <div class="dropdown-menu" aria-labelledby="companyDropdown">
                            <a class="dropdown-item" href="#goal-section">Goal</a>
                            <a class="dropdown-item" href="#footer-section">Services</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#appointment-section">Appointment</a>
                    </li>
                    <!-- Contact Us -->
                    <li class="nav-item">
                        <a class="nav-link" href="#footer-section">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="background-container">
        <div class="parallax"></div>
    </div><br><br>

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
            support.</p>

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
            <form id="appointment-section">

            </form>
        </section>
    </main><br><br>

    <center>
        <h5>Biltap Creations</h5>
    </center>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
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


        $(document).ready(function () {
            // for tiple bar
            $('.navbar-toggler').click(function () {
                $('.collapse').toggleClass('show');
            });
        });

        $(document).ready(function () {
            $('.nav-item.dropdown').hover(function () {
                $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(200);
            }, function () {
                $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(200);
            });
        });

    </script>

</body>

</html>
<div class="advertisement" id="footer-section">
    <?php include_once('Temp/footer.html'); ?>
</div>