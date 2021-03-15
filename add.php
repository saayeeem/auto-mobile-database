<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['name']) || strlen($_SESSION['name']) < 1  ) {
    die('ACCESS DENIED');
}
if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year'])
    && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
      $_SESSION['error'] = "All fields are required";
      header("Location: add.php?autos_id=".$_REQUEST['id']);
      return;

    }
    elseif(is_numeric($_POST['year'])===false)
    {
      $_SESSION['error'] = 'year must be numeric';
      header("Location: add.php?autos_id=".$_REQUEST['id']);

    }
    elseif(is_numeric($_POST['mileage'])===false)
    {
      $_SESSION['error'] = 'mileage must be numeric';
      header("Location: add.php?autos_id=".$_REQUEST['id']);
      return;
    }
    $sql = "INSERT INTO autos (make, model, year,mileage)
              VALUES (:make, :model, :year,:mileage)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']));
    $_SESSION['success'] = 'Record Added';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
// if ( isset($_SESSION['error']) ) {
//     echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
//     unset($_SESSION['error']);
// }
?>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>Mohammad Sayem Chowdhry Add Page</title>
</head>
<body>
<div class="container">
    <h1>Tracking Autos for <?php echo $_SESSION['name']; ?></h1>
    <?php
    // Note triple not equals and think how badly double
    // not equals would work here...
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="post">
        <p>Make:

            <input type="text" name="make" size="40"/></p>
        <p>Model:

            <input type="text" name="model" size="40"/></p>
        <p>Year:

            <input type="text" name="year" size="10"/></p>
        <p>Mileage:

            <input type="text" name="mileage" size="10"/></p>
        <input type="submit" name='add' value="Add">
        <a href="index.php">Cancel</a>
    </form>


</div>
</body>
</html>
