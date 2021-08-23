<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'database.php';
if (!empty($_GET)) {
    $id = check_input($_GET['id']);
}
$cn = Database::connect();
$res = ($cn->query("SELECT `id`, `name` FROM categories"))->fetchAll();
$name = $description = $price = $category = $image = $errors = $is_upload = $image_update =  null;
if (!empty($_POST)) {
    $name = check_input($_POST['name']);
    if (empty($name)) {
        $errors['name'] = "Ce champ ne peut pas être vide";
    }
    $description = check_input($_POST['description']);
    if (empty($description)) {
        $errors['description'] = "Ce champ ne peut pas être vide";
    }
    $price = check_input($_POST['price']);
    if (empty($price)) {
        $errors['price'] = "Ce champ ne peut pas être vide";
    }
    $category = check_input($_POST['category']);
    if (empty($category)) {
        $errors['category'] = "Ce champ ne peut pas être vide";
    }
    $image = check_input($_FILES['image']['name']);
    $imagePath = '../images/' . basename($image);
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    if (empty($image)) {
        $image_update = false;
    } else {
        $image_update = true;
        $is_upload = true;
        if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
            $imageError = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
            $is_upload = false;
        }
        /* if (file_exists($imagePath)) {
            $errors['image'] = "Le fichier existe deja";
            $is_upload = false;
        } */
        if ($_FILES["image"]["size"] > 500000) {
            $errors['image'] = "Le fichier ne doit pas depasser les 500KB";
            $is_upload = false;
        }
        if ($is_upload) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                $errors['image'] = "Il y a eu une erreur lors de l'upload";
                $is_upload = false;
            }
        }
    }
    if ((!$errors && $image_update && $is_upload) || (!$errors && !$image_update)) {
        $db = Database::connect();
        if ($image_update) {
            $statement = $db->prepare("UPDATE items  set name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?");
            $statement->execute(array($name, $description, $price, $category, $image, $id));
        } else {
            $statement = $db->prepare("UPDATE items  set name = ?, description = ?, price = ?, category = ? WHERE id = ?");
            $statement->execute(array($name, $description, $price, $category, $id));
        }
        Database::disconnect();
        header("Location: index.php");
    } else if ($image_update && !$is_upload) {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM items where id = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();
        $image          = $item['image'];
        Database::disconnect();
    }
} else {
    $cn = Database::connect();
    $res = ($cn->query("SELECT `id`, `name` FROM categories"))->fetchAll();
    $stmt = $cn->prepare("SELECT items.id, items.name, items.description, items.price, items.image, categories.name AS category
                          FROM items INNER JOIN categories ON items.category = categories.id
                          WHERE items.id = ?
                       ");
    $stmt->execute(array($id));
    $item = $stmt->fetch();
    $name = $item['name'];
    $description = $item['description'];
    $price = $item['price'];
    $category = $item['category'];
    $image = $item['image'];
    Database::disconnect();
}
function check_input(string $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    //$data = htmlentities($data);
    return $data;
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
                <h1><strong>Modifier un item</strong></h1>
                <br>
                <form class="form" role="form" action="<?= 'update.php?id=' . $id ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name: </label>
                        <input type="text" class="form-control" name="name" value="<?= $name ?>">
                        <?php if (!empty($errors['name'])) : ?>
                            <div style="color: red;"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="description">Description: </label>
                        <input type="text" class="form-control" name="description" value="<?= $description ?>">
                        <?php if (!empty($errors['description'])) : ?>
                            <div style="color: red;"><?= $errors['description'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="price">Prix: </label>
                        <input type="text" class="form-control" name="price" value="<?= number_format((float) $price, 2, '.', '') . ' €' ?>">
                        <?php if (!empty($errors['price'])) : ?>
                            <div style="color: red;"><?= $errors['price'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="categorie">Catégorie: </label>
                        <select class="form-control" name="category">
                            <?php foreach ($res as $tuple) : ?>
                                <option value="<?= $tuple['id'] ?>" <?= ($category == $tuple['name']) ? 'selected' : '' ?>><?= $tuple['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Image: </label><?= ' ' . $image ?>
                    </div>
                    <div class="form-group">
                        <label for="image">Sélectionner une image: </label>
                        <input type="file" id="image" name="image">
                        <?php if (!empty($errors['image'])) : ?>
                            <div style="color: red;"><?= $errors['image'] ?></div>
                        <?php endif; ?>
                    </div>
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-pencil"></span> Modifier</button> <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                    </div>
                </form>
            </div>
            <div class="col-sm-6 site">
                <div class="thumbnail">
                    <img src="<?= '../images/' . $image ?>" alt="" />
                    <div class="price"><?= number_format((float) $price, 2, '.', '') . ' €' ?></div>
                    <div class="caption">
                        <h4><?= $name ?></h4>
                        <p><?= $description ?></p>
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