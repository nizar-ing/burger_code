<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'database.php';
if (!empty($_GET['id'])) {
    $id = check_input($_GET['id']);
    $cn = Database::connect();
    $stmt = $cn->prepare("SELECT items.id, items.name, items.description, items.price, items.image, categories.name AS category
                          FROM items INNER JOIN categories ON items.category = categories.id
                          WHERE items.id = ?
                       ");
    $stmt->execute(array($id));
    $item = $stmt->fetch();
    Database::disconnect();
}
function check_input(string $data): int
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    //$data = htmlentities($data);
    return (int) $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Burger Code</title>
    <!-- Bootstrap Cdn Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <!-- Bootstrap Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <!-- Lato font google -->
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Holtwood+One+SC&display=swap" rel="stylesheet" />
    <!-- Our file css -->
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>

    <h1 class="text-logo">
        <span class="glyphicon glyphicon-cutlery"></span> Burger Code
        <span class="glyphicon glyphicon-cutlery"></span>
    </h1>
    <div class="container admin">
        <div class="row">
            <div class="col-sm-6">
                <h1><strong>Voir un item</strong></h1>
                <br>
                <form>
                    <div class="form-group">
                        <label>Name: </label><?= ' ' . $item['name'] ?>
                    </div>
                    <div class="form-group">
                        <label>Description: </label><?= ' ' . $item['description'] ?>
                    </div>
                    <div class="form-group">
                        <label>Prix: </label><?= ' ' . number_format((float) $item['price'], 2, '.', '') . ' €' ?>
                    </div>
                    <div class="form-group">
                        <label>Catégorie: </label><?= ' ' . $item['category'] ?>
                    </div>
                    <div class="form-group">
                        <label>Image: </label><?= ' ' . $item['image'] ?>
                    </div>
                </form>
                <br>
                <div class="form-actions">
                    <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                </div>
            </div>
            <div class="col-sm-6 site">
                <div class="thumbnail">
                    <img src="<?= '../images/' . $item['image'] ?>" alt="" />
                    <div class="price"><?= number_format((float) $item['price'], 2, '.', '') . ' €' ?></div>
                    <div class="caption">
                        <h4><?= $item['name'] ?></h4>
                        <p><?= $item['description'] ?></p>
                        <a href="" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span>
                            order online</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- JQuery Cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Bootstrap javascript: Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</html>