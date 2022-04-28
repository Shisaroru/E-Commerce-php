<?php
    require_once '../bootstrap.php';
    use Project\User;
    $error = true;
    if (isset($_POST['loginEmail'])) {
        $error = Project\User::login($conn);
    }
    if (!$error) {
       header("Location: /index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../partials/header.php' ?>
    
    <main class="container mt-4">
        <div class="row">
            <form action="/login.php" method="POST">
                <div class="mb-3">
                    <label for="email1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="loginEmail">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="loginPassword">
                </div>
                <?php if ($error && isset($_POST['loginEmail'])): ?>
                    <div class="mb-3">
                        <strong class="text-danger">Wrong Email or Password</strong>
                    </div>
                <?php endif ?>
                <button type="submit" class="btn btn-primary">Login</button>
                <a class="btn btn-secondary mx-3" href="/register.php">Register</a>
            </form>
        </div>
    </main>

    <?php include '../partials/footer.php' ?>
</body>
</html>