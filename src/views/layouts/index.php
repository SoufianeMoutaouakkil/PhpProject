<?php
use Altendev\App;

$app = App::app();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <title><?= $title ?? "Cellule IG Application" ?></title>
</head>

<body>
    <?php if ($app->isGuest()): ?>
        <!-- top navigation bar for guests -->
        <?php include_once __DIR__ . "/includes/html/navbar.php" ?>
        
    <?php else: ?>
        <!-- top navigation bar -->
        <?php include_once __DIR__ . "/includes/html/user-navbar.php" ?>
        <!-- offcanvas -->
        <?php include_once __DIR__ . "/includes/html/sidebar.php" ?>
    <?php endif; ?>

    <main class="mt-5 pt-3">
        <div class="container-fluid">
            <?php echo (string) $content ?>
        </div>
    </main>

    <?php include_once __DIR__ . "/includes/html/js.php" ?>
</body>

</html>
