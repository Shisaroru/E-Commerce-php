<?php

namespace Project;

use PDO;

class Product {

    private $db;

    public $product_id;
    public $product_name;
    public $price;
    public $product_image;

    public function __construct(PDO $conn){
        $this->db = $conn;
    }

    public function getProducts() {
        $products = [];
        $stmt = $this->db->prepare("Select * from products");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $product = new Product($this->db);
            $product->fillObjectAttributes($row);
            $products[$product->product_id] = $product;
        }
        return $products;
    }

    protected function fillObjectAttributes(array $row) {
        $this->product_id = $row['product_id'];
        $this->product_name = $row['product_name'];
        $this->price = $row['price'];
        $this->product_image = $row['product_image'];
    }

    public function addToCart($quantity) {
        $quantityInCart = $this->alreadyInCart();
        if ($quantityInCart > 0) {
            $stmt = $this->db->prepare("UPDATE cart SET quantity = :quantity WHERE product_id=:product_id and user_id=:userid and bill_id is null");
            $quantityInCart+=$quantity;
            $stmt->bindParam(':quantity',$quantityInCart);
            $stmt->bindParam(':product_id', $this->product_id);
            $stmt->bindParam(':userid', $_SESSION['id']);
            $stmt->execute();
        }
        else {
            $stmt = $this->db->prepare("INSERT INTO cart (product_id, user_id, quantity) value ( :product_id, :userid, :quantity)");
            $stmt->bindParam(':product_id', $this->product_id);
            $stmt->bindParam(':userid', $_SESSION['id']);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();
        }
    }

    protected function alreadyInCart() {
        $stmt = $this->db->prepare("SELECT * FROM cart where product_id=:product_id and user_id=:userid and bill_id is null");
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':userid', $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (count($result) > 0) {
            return $result[0]['quantity'];
        }
        else {
            return 0;
        }
    }

    public function getOrders() {
        $stmt = $this->db->prepare("SELECT b.bill_id, username, total_money, status, address, quantity, product_name, price from bill b, cart c, products p, users u where b.bill_id=c.bill_id and c.product_id=p.product_id and b.user_id=u.id and status='Delivering';");
        $stmt->execute();
        $array = [];
        $_SESSION['count'] = 0;
        while ($row = $stmt->fetch()) {
            $array[$_SESSION['count']] = $row;
            $_SESSION['count']++;
        }
        return $array;
    }

    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id=:product_id");
        $stmt->bindParam(':product_id', $_POST['delete']);
        $stmt->execute();
        $img_directory = ROOTDIR . 'public/img/';
        unlink($img_directory . $_POST['delete'] . ".jpg");
    }

    public function getEdit() {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE product_id=:product_id");
        $stmt->bindParam(':product_id', $_POST['id']);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update() {
        if (isset($_FILES['product_image'])) {
            $img_directory = ROOTDIR . 'public/img/';
            unlink($img_directory . $_POST['update'] . ".jpg");
            move_uploaded_file($_FILES["product_image"]["tmp_name"], $img_directory . $_POST['update'] . ".jpg");
        }
        $stmt = $this->db->prepare("UPDATE products SET product_name=:product_name, price=:price WHERE product_id=:product_id ");
        $stmt->bindParam(':product_name', $_POST['product_name']);
        $stmt->bindParam(':price', $_POST['price']);
        $stmt->bindParam(':product_id', $_POST['update']);
        $stmt->execute();
        header('Location: /index.php');
    }

    public function add() {
        $stmt = $this->db->prepare("INSERT INTO products (product_name, price) value (:product_name, :price)");
        $stmt->bindParam(':product_name', $_POST['product_name']);
        $stmt->bindParam(':price', $_POST['price']);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $image = "img/" . $id . ".jpg";
        $stmt = $this->db->prepare("UPDATE products SET product_image=:product_image WHERE product_id=:product_id");
        $stmt->bindParam(':product_image', $image);
        $stmt->bindParam(':product_id', $id);
        $stmt->execute();
        if (isset($_FILES['add_product_image'])) {
            $img_directory = ROOTDIR . 'public/img/';
            move_uploaded_file($_FILES["add_product_image"]["tmp_name"], $img_directory . $id . ".jpg");
        }
    }

    public function getOrdersHistory() {
        $stmt = $this->db->prepare("SELECT b.bill_id, total_money, status, address, quantity, product_name, price from bill b, cart c, products p, users u where b.bill_id=c.bill_id and c.product_id=p.product_id and b.user_id=u.id and b.user_id=:user_id;");
        $stmt->bindParam(':user_id', $_SESSION['id']);
        $stmt->execute();
        $array = [];
        $_SESSION['count'] = 0;
        while ($row = $stmt->fetch()) {
            $array[$_SESSION['count']] = $row;
            $_SESSION['count']++;
        }
        return $array;
    }
    
    public function orderDelivered() {
        $stmt = $this->db->prepare("UPDATE bill SET status = :status where bill_id = :bill_id");
        $status = "Delivered";
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':bill_id', $_POST['checked']);
        $stmt->execute();
    }

    public function getDeliveredOrders() {
        $stmt = $this->db->prepare("SELECT b.bill_id, username, total_money, status, address, quantity, product_name, price from bill b, cart c, products p, users u where b.bill_id=c.bill_id and c.product_id=p.product_id and b.user_id=u.id and status='Delivered';");
        $stmt->execute();
        $array = [];
        $_SESSION['count'] = 0;
        while ($row = $stmt->fetch()) {
            $array[$_SESSION['count']] = $row;
            $_SESSION['count']++;
        }
        return $array;
    }

    public function message($mes, $mes2) {
        echo '<div class="position-fixed bottom-0 end-0 p-3" style="z-index:100;">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">'.$mes .' success <i class="fa fa-circle-check text-success"></i></strong> 
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                '.$mes2. '
            </div>
        </div>
    </div>';
    }
}

?>