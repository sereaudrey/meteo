
<?php 
$pdo = new PDO('mysql:host=localhost; dbname=stationmeteo; charset=utf8','root','root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$error = null;
$success = null;
// $id = $pdo->quote($_GET['id']);
// var_dump($id); die();

try {
    $query = $pdo->prepare('SELECT * FROM sensor WHERE id_sensor = :id_sensor');
    $query->execute([
        'id_sensor' => $_GET['id_sensor']
    ]);
    $post = $query->fetch();
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>

<div class="container">
    <p>
        <a href="index.php">Revenir au listing</a>
    </p>
    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else : ?>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="name" value="<?= htmlentities($post->name)?>">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="content"><?= htmlentities($post->content)?></textarea>
            </div>
            <button class="btn btn-primary">Sauvegarder</button>
        </form>
    <?php endif ?>
</div>




