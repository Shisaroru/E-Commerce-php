<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">Phone shop</span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/index.php">
                <i class="fa fa-home"></i>
                Homepage
            </a>
        </li>
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) : ?>
        <li class="nav-item">
          <a class="nav-link" href="/add.php">Add new product</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Orders
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="/orders.php">Delivering</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/delivered_orders.php">Delivered</a></li>
          </ul>
        </li>
        <?php else : ?>
        <li class="nav-item">
          <a class="nav-link" href="/history.php">History</a>
        </li>
        <?php endif ?>
      </ul>
        <ul class="navbar-nav">
        <?php if (! \Project\User::isLoggedIn()): ?>
          <li class="nav-item"><a class="nav-link" href="/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/register.php">Register</a></li>
        <?php else: ?>
          <li class="nav-item dropdown pl-0">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php $temp_object = unserialize($_SESSION["userObject"]);
                  echo $temp_object->getUserName();
                  unset($temp_object);?>
          </a>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="/logout.php">Logout</a></li>
          </ul>
        </li>
        <?php endif; ?>
        </ul>
    </div>
  </div>
</nav>