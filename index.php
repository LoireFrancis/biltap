<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <title>ConstructQR Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to left, #BB3B0C, #fff, #BB3B0C);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            display: flex;
            max-width: 700px;
            max-height: 400px;
            width: 100%;
            height: 100%;
            border-radius: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 1);
            overflow: hidden;
            background: linear-gradient(to bottom, #BB3B0C, #5B2410);
        }

        .logo-section {
            flex: 1;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
        }

        .logo-section h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
            background: linear-gradient(to right, #C69586, #FFFFFF, #C69586);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-section p {
            font-size: 1.2rem;
            margin: 0;
            background: linear-gradient(to right, #C69586, #FFFFFF, #C69586);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .error-message {
            margin-bottom: 15px;
        }

        .error-text {
            font-size: 14px;
            border-radius: 5px;
            padding: 10px;
            color: #fff;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            background-color: #fff;
            color: #495057;
            padding: 12px;
            margin-bottom: 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #BB3B0C;
            box-shadow: 0 0 0 0.2rem rgba(187, 59, 12, 0.25);
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        .btn-secondary {
            background-color: #411a0b;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-secondary:hover {
            background-color: #5B2410;
            transform: scale(1.05);
        }

        .btn-secondary:active {
            transform: scale(0.95);
        }

        .chk {
            margin-bottom: 20px;
        }

        .chk-label {
            margin-left: 8px;
            font-size: 14px;
            color: #fff;
        }

        /* Responsive design */
        @media screen and (max-width: 1200px) {
            .login-container {
                flex-direction: column;
                max-height: none;
                width: 90%;
                max-width: 600px;
            }

            .logo-section,
            .login-form-section {
                width: 100%;
                padding: 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 90%;
                max-width: 400px;
                height: auto;
            }

            .logo-section {
                order: -1;
                padding: 15px;
            }

            .login-form-section {
                order: 1;
                padding: 15px;
            }

            .login-container h1 {
                font-size: 1.8rem;
            }

            .login-container p {
                font-size: 1rem;
            }

            .form-control {
                padding: 10px;
            }

            .btn-secondary {
                padding: 10px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo-section">
            <div>
                <img src="Picture/biltaplogo.png" width="150" height="150">
                <h1>Biltap Creation</h1>
                <p>Welcome Back!</p>
            </div>
        </div>
        <div class="login-form-section">
            <form id="loginForm" action="login.php" method="POST">
                <div class="mb-3 input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3 input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                </div>
                <div class="error-message">
                    <?php
                    if (isset($_GET['error'])) {
                        echo "<p class='error-text'>" . $_GET['error'] . "</p>";
                    }
                    ?>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="chk">
                    <label class="form-check-label chk-label" for="chk">Show Password</label>
                </div><br>
                <button type="submit" class="btn btn-secondary">Login</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <script>
        const password = document.getElementById("password");
        const chk = document.getElementById("chk");

        chk.onchange = function (e) {
            password.type = chk.checked ? "text" : "password";
        };

        // Hide error message after 3 seconds
        function hideErrorMessage() {
            var errorMessage = document.querySelector('.error-text');
            if (errorMessage) {
                setTimeout(function () {
                    errorMessage.style.display = 'none';
                }, 2500);
            }
        }

        window.onload = hideErrorMessage;
    </script>
</body>

</html>
