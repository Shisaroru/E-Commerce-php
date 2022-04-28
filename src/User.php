<?php

namespace Project;

use PDO;

class User {

    private $id;
    public $username;
    private $password;
    public $email;

    public function __construct($id, $username, $password, $email) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public static function isLoggedIn() {
        return isset($_SESSION["id"]);
    }

    public function getUserName() {
        return $this->username;
    }

    public static function login(PDO $conn) {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $conn->prepare('SELECT * FROM users where email = :email; ');
            $stmt->bindParam(':email', $_POST['loginEmail'], PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if ((count($result) == 1) && (password_verify( $_POST['loginPassword'], $result[0]['password']))) {
                $_SESSION["id"] = $result[0]['id'];
                $_SESSION["username"] = $result[0]['username'];
                $temp_object = new User($_SESSION["id"], $_SESSION["username"], $result[0]['password'], $result[0]['email']);
                $_SESSION["userObject"] = serialize($temp_object);
                $_SESSION["admin"] = $result[0]['privilege'];
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return false;
        }
    }

    public static function register(PDO $conn) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUE (:username, :email, :password)');
            $stmt->bindParam(':username', $_POST['registerUsername']);
            $stmt->bindParam(':email', $_POST['registerEmail']);
            $hash = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hash);
            $stmt->execute();
            header('Location: /login.php');
        }
    }

    public static function validate($info, PDO $conn) {
        $processed_info = User::preProcess($info);
        $errors = [];
        if (empty($processed_info['registerEmail'])) {
            $errors['registerEmail'] = "This field is required";
        } else if (!filter_var($processed_info['registerEmail'], FILTER_VALIDATE_EMAIL)) {
            $errors['registerEmail'] = "Please enter a valid email address";
        } else {
            $stmt = $conn->prepare('select * from users where email = :email');
            $stmt->bindParam(':email', $processed_info['registerEmail']);
            $stmt->execute();
            if (count($stmt->fetchAll()) > 0) {
                $errors['registerEmail'] = "This email is already in use. Please choose another email";
            }
        }

        if (empty($processed_info['registerUsername'])) {
            $errors['registerUsername'] = "This field is required";
        }

        if (empty($processed_info['registerPassword'])) {
            $errors['registerPassword'] = "This field is required";
        } else if (strlen($processed_info['registerPassword']) < 6) {
            $errors['registerPassword'] = "Password must longer than 5 characters";
        }
        
        if (empty($processed_info['registerPasswordConFirm'])) {
            $errors['registerPasswordConFirm'] = "This field is required";
        }
        else if ( !empty($processed_info['registerPassword']) &&
            ($processed_info['registerPassword'] != $processed_info['registerPasswordConFirm'])) {
            $errors['registerPasswordConFirm'] = "Password confirm must be the same as password";
        }

        return $errors;
    }

    protected static function preProcess($info) {
        $temp_arr = $info;
        foreach ($temp_arr as $key) {
            $temp = $key;
            $temp = trim($temp);
            $temp = stripslashes($temp);
            $temp = htmlspecialchars($temp);
            $key = $temp;
        }
        return $temp_arr;
    }

    public function getCart(PDO $conn) {
        $stmt = $conn->prepare("SELECT p.product_id, quantity, product_name, price, product_image
         FROM cart as c join products as p on p.product_id=c.product_id where user_id=:user_id and bill_id is null;");
        $stmt->bindParam(':user_id', $_SESSION['id']);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteCart(PDO $conn, $id) {
        $stmt = $conn->prepare("DELETE FROM cart where user_id=:user_id and product_id=:id and bill_id is null");
        $stmt->bindParam(':user_id', $_SESSION['id']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function confirmOrder(PDO $conn, $total) {
        $stmt = $conn->prepare("INSERT INTO bill (user_id, total_money, status, address) value
        (:user_id, :total_money, :status, :address)");
        $stmt->bindParam(':user_id', $_SESSION['id']);
        $stmt->bindValue(':status', "Delivering");
        $stmt->bindParam(':total_money', $total);
        $stmt->bindParam(':address', $_POST['address']);
        $stmt->execute();
        $id = $conn->lastInsertId();
        $stmt = $conn->prepare("UPDATE cart set bill_id=:bill_id where user_id=:user_id and bill_id is null");
        $stmt->bindParam(':bill_id', $id);
        $stmt->bindParam(':user_id', $_SESSION['id']);
        $stmt->execute();
        echo '<div class="position-fixed bottom-0 end-0 p-3" style="z-index:100;">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto"> Order success <i class="fa fa-circle-check text-success"></i></strong> 
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                You can find order history in History tab
            </div>
        </div>
    </div>';
    }
}