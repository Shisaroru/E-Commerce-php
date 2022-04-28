<?php
	require_once '../bootstrap.php';
    use Project\User;
    $errors = [];
    if (!empty($_POST)) {
        $errors = Project\User::validate($_POST, $conn);
    }
    if (empty($errors)) {
        Project\User::register($conn);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../partials/header.php' ?>

    <main class="container mt-4">
        <div class="row">
            <form action="/register.php" method="POST">
                <div class="mb-3">
                    <label for="email1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="registerEmail"
                    value="<?=(!empty($_POST)) ? $_POST['registerEmail'] : "";?>"
                    >
                    <?php if (isset($errors['registerEmail'])): ?>
                        <strong class="text-danger"><?=$errors['registerEmail']?></strong>
                    <?php endif ?>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">User Name</label>
                    <input type="text" class="form-control" id="username" aria-describedby="emailHelp" name="registerUsername"
                    value="<?=(!empty($_POST)) ? $_POST['registerUsername'] : "";?>"
                    >
                    <?php if (isset($errors['registerUsername'])): ?>
                        <strong class="text-danger"><?=$errors['registerUsername']?></strong>
                    <?php endif ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="registerPassword">
                    <?php if (isset($errors['registerPassword'])): ?>
                        <strong class="text-danger"><?=$errors['registerPassword']?></strong>
                    <?php endif ?>
                </div>
                <div class="mb-3">
                    <label for="password-cf" class="form-label">Password confirm</label>
                    <input type="password" class="form-control" id="password-cf" name="registerPasswordConFirm">
                    <?php if (isset($errors['registerPasswordConFirm'])): ?>
                        <strong class="text-danger"><?=$errors['registerPasswordConFirm']?></strong>
                    <?php endif ?>
                </div>  
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </main>

    <?php include '../partials/footer.php' ?>
    
</body>
</html>