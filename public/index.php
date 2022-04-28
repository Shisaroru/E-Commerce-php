<?php
	require_once '../bootstrap.php';
    use Project\Product;
    use Project\User;
    $product = new Product($conn);
    $products = $product->getProducts();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
        $products[$_POST['id']]->addToCart($_POST['quantity']);
        $products[$_POST['id']]->message("Added", "Added product to cart");
    }
    $admin = (isset($_SESSION["admin"]) && $_SESSION['admin'] == 1) ? '/edit.php' : '/index.php';
    if (isset($_SESSION["message"])) {
        if ($_SESSION['message'] == 'delete') {
            $product->message("Delete", "Delete product from database");
        }
        if ($_SESSION['message'] == 'update') {
            $product->message("Update", "Update product from database");
        }
        unset($_SESSION['message']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Homepage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">

</head>
<body>
    <?php include '../partials/header.php' ?>

    <main class="container my-3">
        <div class="row row-cols-3">
            <?php foreach ($products as $product): ?>
                <div class="col my-3">
                    <div class="card h-100">
                        <img class="card-img-top" src="<?=htmlspecialchars($product->product_image)?>">
                        <div class="card-body card-img-bottom">
                            <h5 class="card-title"><?=htmlspecialchars($product->product_name)?></h5>
                            <p class="card-text"><?=$product->price . " USD"?></p>
                            <form action="<?=$admin?>" method="post">
                                <div class="row">
                                    <div class="col">
                                        <?php if (isset($_SESSION["admin"]) && $_SESSION['admin'] == 1):?>
                                        <button class="btn btn-danger" type="submit" name="delete" value="<?=$product->product_id?>">
                                        Delete</button>
                                        <?php else: ?>
                                        <input class="form-control" type="number" placeholder="Quantity" name="quantity" value=1>
                                        </div>
                                    <div class="col">
                                        <?php endif ?>
                                    <?php if (\Project\User::isLoggedIn()): ?>
                                        <button class="btn btn-primary" type="submit" name="id" value="<?=$product->product_id?>">
                                        <?= (isset($_SESSION["admin"]) && $_SESSION['admin'] == 1) ? 'Edit' : 'Add to cart'?></button>
                                    <?php else: ?>
                                        <button class="btn btn-primary"><a class="text-light text-decoration-none" href="/login.php">Add to cart</a></button>
                                    <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 0): ?>
    <a class="position-fixed bottom-0 start-0 m-4 bg-dark border-0 rounded-circle p-3" href="/cart.php">
        <i class="fa fa-shopping-cart fs-3 text-primary"></i>
    </a>
    <?php endif; ?>
    </main>

    <?php include '../partials/footer.php' ?>
</body>
<script>
    $(function() {
        $('.toast').toast('show');
    });
</script>
</html>
