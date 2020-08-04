<?php
require('dbconnect.php');
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// Delete puns
if (isset($_POST['deleteBtn'])) {
  try {
    $query = "
      DELETE FROM puns
      WHERE id = :id;
    ";
    $stmt = $dbconnect->prepare($query);
    $stmt->bindValue(':id', $_POST['punId']);
    $stmt->execute();
  } catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
  }
}
// Add new pun
$message = '';
if (isset($_POST['addPunsBtn'])) {
  $pun = trim($_POST['pun']);
  if (empty($pun)) {
    $message = 
      '<div class="alert alert-danger" role="alert">
        Pun field must not be empty
      </div>';
  } else {
    try {
      $query = "
        INSERT INTO puns (content)
        VALUES (:pun);
      ";
      $stmt = $dbconnect->prepare($query);
      $stmt->bindValue(':pun', $pun);
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
  }
}
// Update pun
if (isset($_POST['updateBtn'])) { 
  $pun = trim($_POST['pun']);
  if (empty($pun)) {
    $message = 
      '<div class="alert alert-danger" role="alert">
        Pun field must not be empty
      </div>';
  } else {
    try {
      $query = "
        UPDATE puns
        SET content = :pun
        WHERE id = :id;
      ";
      $stmt = $dbconnect->prepare($query);
      $stmt->bindValue(':pun', $pun);
      $stmt->bindValue(':id', $_POST['id']);
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
  }
}
// Fetch puns to display on page
try {
  $query = "SELECT * FROM puns;";
  $stmt = $dbconnect->query($query);
  $puns = $stmt->fetchAll();
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
// echo "<pre>";
// print_r($puns);
// echo "</pre>";
// die; // Stops executing the PHP script
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Puns app</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="offset-3 col-6">
          <h1>My puns app</h1>
            <!--add-->
          <form action="" method="POST">
            <div class="input-group mb-3">
              <input type="text" name="pun" class="form-control" placeholder="Add new puns">
              <div class="input-group-append">
                <input type="submit" name="addPunsBtn" value="Add" class="btn btn-outline-secondary" id="button-addon2">
              </div>
            </div>
          </form>
          <?=$message?>
          <h3>Puns list</h3>
          <ul class="list-group">
            <?php foreach ($puns as $key => $pun) { ?>
              <li class="list-group-item">
                <p class="float-left">
                  <?=htmlentities($pun['content'])?> - 
                  <?=htmlentities($pun['create_date'])?>
                <p>
                <!--delete-->
                <form action="" method="POST" class="float-right">
                  <input type="hidden" name="punId" value="<?=$pun['id']?>">
                  <input type="submit" name="deleteBtn" value="Delete" class="btn btn-danger">
                </form>
                <!--update-->
                <button type="button" class="btn btn-warning float-right" data-toggle="modal" data-target="#exampleModal" data-pun="<?=htmlentities($pun['content'])?>" data-id="<?=htmlentities($pun['id'])?>">Update</button>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update pun</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="POST">
            <div class="modal-body">
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">pun: </label>
                  <input type="text" class="form-control" name="pun" for="recipient-name">
                  <input type="hidden" class="form-control" name="id">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <input type="submit" name="updateBtn" value="Update" class="btn btn-success">
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
  $('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var pun = button.data('pun'); // Extract info from data-* attributes
    var id = button.data('id'); // Extract info from data-* attributes
    var modal = $(this);
    modal.find(".modal-body input[name='pun']").val(pun);
    modal.find(".modal-body input[name='id']").val(id);
  });
</script>
  </body>
</html>