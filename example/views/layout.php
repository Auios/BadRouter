<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BadRouter Example</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Code&display=swap">
    <link href="<?= PUBLIC_PATH ?>/css/style.css" rel="stylesheet">
  </head>
  <body>
    <h1>BadRouter</h1>
    <?php include(VIEWS_PATH . '/nav.php'); ?>
    <?= $content ?>
  </body>
</html>
