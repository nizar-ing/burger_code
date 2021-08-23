<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'auth.php';
user_should_connect();
require __DIR__ . DIRECTORY_SEPARATOR . 'database.php';
$con_db = Database::connect();
$res = $con_db->query("SELECT * FROM items", PDO::FETCH_ASSOC);
$res = $res->fetchAll();
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
            <h1><strong>Liste des items</strong> <a href="insert.php" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-plus"></span> Ajouter</a></h1>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($res as $tuple) : ?>
                        <tr>
                            <td><?= $tuple['name'] ?></td>
                            <td><?= $tuple['description'] ?></td>
                            <td><?= number_format((float) ($tuple['price']), 2, '.', ''); ?></td>
                            <?php $cat = ($con_db->query("SELECT `name` FROM categories WHERE `id` = {$tuple['category']}", PDO::FETCH_NUM))->fetch(); ?>
                            <td><?= $cat[0] ?></td>
                            <td width=300><a href="view.php?id=<?= $tuple['id'] ?>" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span> Voir</a> <a href="update.php?id=<?= $tuple['id'] ?>" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>
                                <a href="delete.php?id=<?= $tuple['id'] ?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></>
                        </tr>
                    <?php endforeach; ?>
                    <?php Database::disconnect(); ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>
<!-- JQuery Cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Bootstrap javascript: Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
</script>

</html>