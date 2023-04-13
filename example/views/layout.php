<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Badluck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body class="bg-dark text-light">
    <script>
      document.documentElement.setAttribute("data-bs-theme", "dark");
    </script>
    <div class="container pt-5">
      <nav>
        <ul>
          <li><a href="<?=BASE_PATH?>/">Home</a></li>
          <li><a href="<?=BASE_PATH?>/about">About</a></li>
          <li><a href="<?=BASE_PATH?>/contact">Contact</a></li>
          <li><a href="<?=BASE_PATH?>/user/42">User</a></li>
          <li><a href="<?=BASE_PATH?>/admin">Admin</a></li>
          <li><a href="<?=BASE_PATH?>/login">Login</a></li>
          <li><a href="<?=BASE_PATH?>/404">404</a></li>
        </ul>
      </nav>
      <?= $content ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
</html>
