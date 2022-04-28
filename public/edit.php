<?php
    require_once '../bootstrap.php';
    use Project\Product;
    $product = new Product($conn);
    $editProduct = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
        $editProduct = $product->getEdit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
        $product->delete();
        $_SESSION['message'] = 'delete';
        header('Location: /index.php');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
        $product->update();
        $_SESSION['message'] = 'update';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../partials/header.php' ?>
    
    <main class="container mt-4">
        <div class="row">
            <form action="/edit.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product name</label>
                    <input type="text" class="form-control" id="name" name="product_name" value="<?=$editProduct['product_name']?>">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?=$editProduct['price']?>" step="0.01">
                </div>
                <div class="mb-3">
                    Current Image: <br>
                    <img class="m-4" src="<?=$editProduct['product_image']?>" style="max-width: 30%; height: auto;"><br>
                    <p class="m-1">Change to:</p>
                    <img class="m-4" src="#" id="new" style="max-width: 30%; height: auto;">
                    <label for="image" class="form-label"></label>
                    <input type="file" class="form-control" id="image" name="product_image" accept="image/*" onchange="readURL(this);">
                </div>
                <button type="submit" class="btn btn-primary" name="update" value="<?=$editProduct['product_id']?>">Update</button>
            </form>
        </div>
    </main>

    <?php include '../partials/footer.php' ?>
</body>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
            $('#new').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
  }
}
</script>
</html>