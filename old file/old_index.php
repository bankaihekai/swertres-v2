<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo.png" />

    <title>Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade {
            animation: fadeIn 1s ease-in-out;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var container = document.querySelector(".container");
            container.classList.add("animate-fade");
        });
    </script>
</head>

<body>
    <div class="card">
        <div class="card-header d-inline-block mt-1">
            <div class="d-flex justify-content-center">
                <img src="img/logo.png" alt="" class="img-fluid mb-3" style="width:120px">
            </div>
            <h3 class="text-center w-100">Login As</h3>
        </div>
        <div class="card-body mt-1">
            <a href="login.php" class="btn btn-primary w-100">User</a>
            <a href="adminlogin.php" class="btn btn-primary w-100 mt-1">Admin</a>
        </div>
        <div class="card-footer text-center">
            <small>&copy; Technical Myles</small>
        </div>
    </div>
</body>

</html>