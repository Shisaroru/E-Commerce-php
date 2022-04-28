<?php
    require_once '../bootstrap.php';
    use Project\Product;
    $product = new Product($conn);
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
        $product->add();
        $product->message("Added", "Added new product to database");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../partials/header.php' ?>
    
    <main class="container mt-4">
        <div class="row">
            <form action="/add.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product name</label>
                    <input type="text" class="form-control" id="name" name="product_name">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01">
                </div>
                <div class="mb-3">
                    <img class="m-4" src="#" id="new" style="max-width: 30%; height: auto;">
                    <label for="image" class="form-label"></label>
                    <input type="file" class="form-control" id="image" name="add_product_image" accept="image/*" onchange="readURL(this);">
                </div>
                <button type="submit" class="btn btn-primary" name="add">Add</button>
            </form>
        </div>
    </main>

    <?php include '../partials/footer.php' ?>
</body>
<script>
    $(function() {
        $('.toast').toast('show');
    });
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