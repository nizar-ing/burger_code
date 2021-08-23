<?php
$erreur = null;
$pwd = '$2y$10$zKcaSJFzypiGPpM31ytZAOlBFAUNQnNrs0pdXpvaEuTqTchf5B2Lq';
if (!empty($_POST)) {
    $email = check_input($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $erreur['email'] = "Invalid Email";
    }
    $password = check_input($_POST['password']);
    if ($email == "nizar.ilahi@gmail.com" && password_verify($password, $pwd)) {
        // on connecte l'utilisateur
        session_start();
        $_SESSION['connecte'] = 1;
        header('Location: ./');
        exit();
    } else {
        $erreur['pwd'] = "incorrect parameters";
    }
}

function check_input(string $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    return $data;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Burger Code</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger Code <span
            class="glyphicon glyphicon-cutlery"></span></h1>
    <div class="container admin">
        <div class="row">
            <h1><strong>Supprimer un item</strong></h1>
            <br>
            <form class="form" action="login.php" role="form" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" name="email" value="<?= (!empty($_POST) ? $email : '') ?>">
                    <?php if ($erreur) : ?>
                    <div class="alert alert-danger"><?= $erreur['email'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password">
                    <?php if ($erreur) : ?>
                    <div class="alert alert-danger"><?= $erreur['pwd'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</body>
<!-- JQuery Cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Bootstrap javascript: Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
</script>

</html>