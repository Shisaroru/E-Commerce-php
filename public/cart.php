<?php
	require_once '../bootstrap.php';
    use Project\Product;
    use Project\User;
    if (!isset($_SESSION['id'])) {
        header('Location: /login.php');
    }
    $total = 0;
    $temp_object = unserialize($_SESSION["userObject"]);
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
        $temp_object->deleteCart($conn, $_POST['delete']);
    }
    $cart = $temp_object->getCart($conn);
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address']) && count($cart)>0) {
        $temp_object->confirmOrder($conn, $_POST['total']);
    }
    $cart = $temp_object->getCart($conn);
    unset($temp_object);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../partials/header.php' ?>

    <main class="container my-4">
        <div class="row justify-content-center">
        <div class="col-auto">
            <table class="table table-dark table-striped table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col" class="text-center">Product name</th>
                        <th scope="col" class="text-center">Price</th>
                        <th scope="col" class="text-center">Quantity</th>
                        <th scope="col" class="text-center">Total</th>
                        <th scope="col" class="text-center">Delete from cart</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $product): ?>
                        <tr>
                            <td><img src="<?=htmlspecialchars($product['product_image'])?>" style="max-width: 60%; height: auto;"></td>
                            <td class="text-center"><?=htmlspecialchars($product['product_name'])?></td>
                            <td class="text-center"><?=$product['price']?></td>
                            <td class="text-center"><?=$product['quantity']?></td>
                            <td class="text-center"><?=$product['price']*$product['quantity']?></td>
                            <td class="text-center">
                                <form action="/cart.php" method="post">
                                    <button class="bg-danger" type="submit" name="delete" value="<?=htmlspecialchars($product['product_id'])?>">
                                        <i class="fa fa-trash-can text-light"></i>
                                    </button>
                                </form>
                            </td>
                            <?php $total+= $product['price']*$product['quantity']; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="6" class="text-end table-info fw-bold">Total: <?=$total?> USD</td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
        <div class="row">
        <div class="col-8">

            <form action="/cart.php" method="post" class="m-3 border p-3 rounded">
                <label for="address" class="form-label">Delivery Address</label>
                <input type="text" name="address" class="form-control m-2" id="address">
                <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST['address'])): ?>
                        <strong class="text-danger">This field is required</strong>
                        <br>
                <?php endif ?>
                <button type="submit" class="btn btn-success mt-2" name="total" value="<?=$total?>">Confirm order</button>
            </form>

        </div>
        </div>
    </main>

    <?php include '../partials/footer.php' ?>
</body>
<script>
    $(function() {
        $('.toast').toast('show');
    });
</script>
</html>