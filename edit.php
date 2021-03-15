<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['name']) || strlen($_SESSION['name']) < 1  ) {
    die('ACCESS DENIED');
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

// Check to see if we have some POST data, if we do process it
if (isset($_POST['mileage']) && isset($_POST['year']) && isset($_POST['make']) && isset($_POST['model']))
{
  // Data validation

    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1)
    {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?autos_id=" . htmlentities($_REQUEST['autos_id']));
        return;
    }

    if (!is_numeric($_POST['year']) )
    {
        $_SESSION['error'] = "Year must be an integer";
        header("Location: edit.php?autos_id=" . htmlentities($_REQUEST['autos_id']));
		return;
    }

    if ( !is_numeric($_POST['mileage']))
    {
        $_SESSION['error'] = "Mileage must be an integer";
        header("Location: edit.php?autos_id=" . htmlentities($_REQUEST['autos_id']));
        return;
    }

    $sql = "UPDATE autos SET make = :make,
              model = :model, year = :year,mileage = :mileage
              WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
              ':make' => $_POST['make'],
              ':model' => $_POST['model'],
              ':year' => $_POST['year'],
              ':mileage' => $_POST['mileage'],
              ':autos_id' => $_GET['autos_id'])
      );
      $_SESSION['success'] = 'Record updated';
      header('Location: index.php');
      return;
  }
// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$m = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);

$autos_id = $row['autos_id'];
?>
<p>Edit autos</p>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $m ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $mo ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $y ?>"></p>
<p>mileage:
<input type="text" name="mileage" value="<?= $mi ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p><input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel"></p>
</form>
