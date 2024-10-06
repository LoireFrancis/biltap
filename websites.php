<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biltap Creations</title>
    <link rel="stylesheet" href="global.css">
</head>
<style>
    html {
        scroll-behavior: smooth;
    }

    section {
        padding: 100px 0;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #ff6600;
        padding-top: 50px;
    }

    header {
        background-color: #a14c25;
        color: white;
        padding: 10px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    header .logo {
        display: flex;
        align-items: center;
    }

    header .logo img {
        height: 50px;
        margin-right: 10px;
    }

    header .logo span {
        font-size: 24px;
        font-weight: bold;
    }

    header nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    header nav ul li {
        margin: 0 10px;
        position: relative;
    }

    header nav ul li a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        display: block;
        padding: 5px 10px;
    }

    header nav ul li a:after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: transparent;
        transition: background-color 0.3s;
    }

    header nav ul li a:hover {
        color: orangered;
    }

    header nav ul li a:hover::after {
        background-color: orangered;
    }

    .background-picture {
        margin: 30px;
        padding: 20px;
        background-color: #ff6600;
        color: white;
        background-image: url('Picture/bg.jpg');
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .projects,
    .goals,
    .footer-info {
        margin: 20px;
        padding: 20px;
        background-color: #ff6600;
        color: white;
    }

    .about-us h2,
    .goals h2,
    .projects h2 {
        margin: 0 0 10px 0;
        font-size: 24px;
    }

    .about-us {
        display: flex;
        align-items: center;
        margin: 20px;
        padding: 20px;
        background-color: #ff6600;
        color: white;
    }

    .about-us .video-container {
        flex: 1;
        margin-right: 20px;
    }

    .about-us .video-container video {
        width: 700px;
        height: 400px;
        border-radius: 8px;
        object-fit: cover;
    }

    .about-us .text-container {
        flex: 1;
    }

    .project-item {
        width: 30%;
        text-align: center;
    }

    .project-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 1/1;
        object-fit: cover;
    }

    footer {
        background-color: #a14c25;
        color: white;
        padding: 20px;
    }

    .contact-forms button {
        margin: 10px;
        padding: 10px 20px;
        background-color: #f9a548;
        color: white;
        border: none;
        cursor: pointer;
    }

    .contact-forms button:hover {
        background-color: #c98244;
    }

    .contact-us ul {
        list-style: none;
        padding: 0;
    }

    .contact-us ul li a {
        color: white;
        text-decoration: none;
    }
</style>

<body>
    <header>
        <div class="logo">
            <img src="Picture/biltaplogo.png" alt="Biltap Creations Logo" href="websites.php">Biltap Creations
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <nav>
            <ul>
                <li><a class="nav-link" href="#about-us-section">About Us</a></li>
                <li><a class="nav-link" href="#goal-section">Goal</a></li>
                <li><a class="nav-link" href="#projects-section">Projects</a></li>
                <li><a class="nav-link" href="#services-section">Services</a></li>
                <li><a class="nav-link" href="#appointment-section">Appointment</a></li>
                <li><a class="nav-link" href="#contact-us-section">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <section class="background-picture" id="home-section">
        <div class="parallax"></div>
    </section>

    <section class="about-us" id="about-us-section">
        <div class="video-container">
            <video controls>
                <source src="Picture/video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="text-container" id="about-us-section">
            <h2>About Us</h2>
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
        </div>
    </section>

    <section class="goal" id="goal-section">
    <div class="goals" id="about-us-section">
        <h2 class="content">What is our goal?</h2>
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
            </div>
    </section>

    <section class="projects" id="projects-section">
        <h2>Projects</h2>
        <div class="project-item">
            <img src="Picture/jpp.jpg" alt="Project 1">
            <h3>Project1</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
        </div>
        <div class="project-item">
            <img src="Picture/franciss.jpg" alt="Project 2">
            <h3>Project2</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
        </div>
        <div class="project-item">
            <img src="Picture/zakii.jpg" alt="Project 3">
            <h3>Project2</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
        </div>
    </section>

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

        // for tiple bar menu
        $(document).ready(function () {
            $('.navbar-toggler').click(function () {
                $('.collapse').toggleClass('show');
            });
        });

        //dropdown
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