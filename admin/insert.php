<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'database.php';
$cn = Database::connect();
$res = ($cn->query("SELECT `id`, `name` FROM categories"))->fetchAll();
$name = $description = $price = $category = $image = $errors = $is_success = $is_upload =  null;
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
        $errors['image'] = "Ce champ ne peut pas être vide";
    } else {
        $is_upload = true;
        if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
            $errors['image'] = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
            $is_upload = false;
        }
        if (file_exists($imagePath)) {
            $errors['image'] = "Le fichier existe deja";
            $is_upload = false;
        }
        if ($_FILES["image"]["size"] > 500000) {
            $errors['image'] = "Le fichier ne doit pas depasser les 500KB";
            $is_upload = false;
        }
        if ($is_upload && !$errors) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                $errors['image'] = "Il y a eu une erreur lors de l'upload";
                $is_upload = false;
            }
        }
    }
    if (!$errors && $is_upload) {
        $is_success = true;
    }
}
if ($is_success) {
    $statement = $cn->prepare("INSERT INTO items (`name`,`description`,`price`,`image`,`category`) values(?, ?, ?, ?, ?)");
    $statement->execute(array($name, $description, $price, $image, $category));
    Database::disconnect();
    header("Location: index.php");
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
            <h1><strong>Ajouter un item</strong></h1>
            <br>
            <form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nom">Nom: </label>
                    <input type="text" class="form-control" name="name" placeholder="Nom" value="<?= $name ?>">
                    <?php if (!empty($errors['name'])) : ?>
                        <div class="invalid-feedback" style="color: red;"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="description">Description: </label>
                    <input type="text" class="form-control" name="description" placeholder="Description" value="<?= $description ?>">
                    <?php if (!empty($errors['description'])) : ?>
                        <div class="invalid-feedback" style="color: red;"><?= $errors['description'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="prix">Prix: (en €)</label>
                    <input type="text" class="form-control" name="price" placeholder="Prix" value="<?= $price ?>">
                    <?php if (!empty($errors['price'])) : ?>
                        <div class="invalid-feedback" style="color: red;"><?= $errors['price'] ?></div>
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
                    <label for="image">Sélectionner une image: </label>
                    <input type="file" id="image" name="image">
                    <?php if (!empty($errors['image'])) : ?>
                        <div class="invalid-feedback" style="color: red;"><?= $errors['image'] ?></div>
                    <?php endif; ?>
                </div>
                <br>
                <div class="form-action">
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Ajouter</button> <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                </div>
            </form>
        </div>
    </div>
</body>
<!-- JQuery Cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Bootstrap javascript: Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</html>