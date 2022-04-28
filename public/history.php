<?php
	require_once '../bootstrap.php';
    use Project\Product;
    $product = new Product($conn);
    $orders = $product->getOrdersHistory();
    $temp = $orders[0]['bill_id'];
    error_reporting(E_ERROR | E_PARSE);
    if (!isset($_SESSION['id'])) {
        header('Location: /login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History orders</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../partials/header.php' ?>
    
    <main class="container my-4">
        <div class="row">
        <div class="col-12">
            <div class="accordion">
                <?php for ($i=0; $i < $_SESSION['count']; $i++): ?>
                <?php
                    if (($orders[$i]['bill_id'] != $temp) || $i==0):         
                ?>
                    <?php $temp = $orders[$i]['bill_id'] ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?=$orders[$i]['bill_id']?>">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$orders[$i]['bill_id']?>" aria-controls="collapse<?=$orders[$i]['bill_id']?>">
                            <strong>Bill #<?=$orders[$i]['bill_id']?> <br>
                            Total money: <?=$orders[$i]['total_money']?>USD<br>
                            Address: <?=htmlspecialchars($orders[$i]['address'])?><br>
                            Status: <?=$orders[$i]['status']?></strong>
                        </button>
                        </h2>
                        <div id="collapse<?=$orders[$i]['bill_id']?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$orders[$i]['bill_id']?>">
                        <div class="accordion-body">
                            <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Product name</th>
                                        <th scope="col" class="text-center">Quantity</th>
                                        <th scope="col" class="text-center">Price</th>
                                        <th scope="col" class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=htmlspecialchars($orders[$i]['product_name'])?> </td>
                                        <td> <?=$orders[$i]['quantity']?> </td>
                                        <td> <?=$orders[$i]['price']?> USD </td>
                                        <td><?=$orders[$i]['quantity']*$orders[$i]['price']?> USD</td>
                                    </tr>
                        <?php if ($orders[$i+1]['bill_id'] != $temp): ?>
                            </tbody>
                            </table>
                            </div>
                            </div>
                            </div>
                        <?php endif; ?>
                <?php elseif ($orders[$i]['bill_id'] == $temp && $orders[$i+1]['bill_id'] == $temp): ?>
                                    <tr>
                                        <td><?=htmlspecialchars($orders[$i]['product_name'])?> </td>
                                        <td> <?=$orders[$i]['quantity']?> </td>
                                        <td> <?=$orders[$i]['price']?> USD </td>
                                        <td><?=$orders[$i]['quantity']*$orders[$i]['price']?> USD</td>
                                    </tr>
                <?php else: ?>
                                    <tr>
                                        <td><?=htmlspecialchars($orders[$i]['product_name'])?> </td>
                                        <td> <?=$orders[$i]['quantity']?> </td>
                                        <td> <?=$orders[$i]['price']?> USD </td>
                                        <td><?=$orders[$i]['quantity']*$orders[$i]['price']?> USD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
        </div>

    </main>
    <?php include '../partials/footer.php' ?>
</body>
</html>