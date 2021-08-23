<?php
require 'admin/database.php';
$db = Database::connect();
$statement = $db->query('SELECT * FROM categories');
$categories = $statement->fetchAll();
function stmt_items($cn_db, $id)
{
  $stmt = $cn_db->prepare('SELECT * FROM items WHERE items.category = ?');
  $stmt->execute(array($id));
  return $stmt->fetchAll();
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
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <div class="container site">
        <h1 class="text-logo">
            <span class="glyphicon glyphicon-cutlery"></span> Burger Code
            <span class="glyphicon glyphicon-cutlery"></span>
        </h1>
        <nav>
            <ul class="nav nav-pills">
                <?php foreach ($categories as $categorie) : ?>
                <?php if ($categorie['id'] == 1) : ?>
                <li role="presentation" class="active"><a href="#<?= $categorie['id'] ?>"
                        data-toggle="tab"><?= $categorie['name'] ?></a></li>
                <?php else : ?>
                <li role="presentation"><a href="#<?= $categorie['id'] ?>"
                        data-toggle="tab"><?= $categorie['name'] ?></a></li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="tab-content">
            <?php foreach ($categories as $categorie) : ?>
            <?php if ($categorie['id'] == 1) : ?>
            <div class="tab-pane active" id="1">
                <?php else : ?>
                <div class="tab-pane " id="<?= $categorie['id'] ?>">
                    <?php endif; ?>
                    <div class="row">
                        <?php foreach (stmt_items($db, $categorie['id']) as $item) : ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">
                                <img src="images/<?= $item['image'] ?>" alt="" />
                                <div class="price"><?= number_format($item['price'], 2, '.', '') . '€' ?></div>
                                <div class="caption">
                                    <h4><?= $item['name'] ?></h4>
                                    <p><?= $item['description'] ?></p>
                                    <a href="" class="btn btn-order" role="button"><span
                                            class="glyphicon glyphicon-shopping-cart"></span>
                                        order online</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <footer class="text-center">
            <h5>© Designed by Nizar ILAHI</h5>
        </footer>
</body>
<!-- JQuery Cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Bootstrap javascript: Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
</script>

</html>