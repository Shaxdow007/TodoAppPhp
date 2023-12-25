<?php
// Database connection parameters.
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

// Back-End :
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Connect to the database TodoList :
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test Submit :
    if ($action === 'new') {
        $title = $_POST['title'];
        $sql = "INSERT INTO todo (title) VALUES (:title)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $taskId = $_POST['id'];
        $sql = "DELETE FROM todo WHERE id = :taskId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':taskId', $taskId);
        $stmt->execute();
    } elseif ($action === 'toggle') {
        $taskId = $_POST['id'];
        $sql = "UPDATE todo SET done = 1 - done WHERE id = :taskId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':taskId', $taskId);
        $stmt->execute();
    }
}

// Front-End :
$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
$pdo = new PDO($dsn, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT * FROM todo ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo List</title>
  <!-- Bootstrap Css -->
  <link rel="stylesheet" href="./bootstrap.min.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="index.php">TodoList</a>
  </nav>

  <div class="container mt-4">
    <form method="POST">
      <div class="form-row d-flex gap-2">
        <div class="form-group col-md-11">
          <input type="text" class="form-control" name="title" placeholder="Enter Your Todo">
        </div>
        <div class="form-group col-md-1">
          <button type="submit" class="btn btn-primary" name="action" value="new">Add</button>
        </div>
      </div>
    </form>
    <div class="mt-4">
      <ul class="list-group">
        <?php foreach ($taches as $tache): ?>
        <li
          class="list-group-item text-capitalize py-2 <?php echo $tache['done'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
          <div class="d-flex justify-content-between">
            <?php echo $tache['title']; ?>
            <form class="d-inline" method="POST">
              <input type="hidden" name="id" value="<?php echo $tache['id']; ?>">
              <button type="submit" class="btn btn-sm btn-primary" name="action"
                value="toggle"><?php echo $tache['done'] ? 'Undo' : 'Done'; ?></button>
              <button type="submit" class="btn btn-sm btn-danger" name="action" value="delete">Delete</button>
            </form>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
  </div>
  <!-- Bootstrap Js  -->
  <script src="./bootstrap.bundle.min.js"></script>
</body>

</html>