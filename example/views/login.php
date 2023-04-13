<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body class="bg-dark text-light">
    <div class="container pt-5">
      <h1 class="text-center mb-5">Login</h1>
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
          <form id="login-form">
            <div class="mb-3">
              <label for="inputEmail" class="form-label">Email address</label>
              <input type="text" class="form-control" id="inputEmail" required>
            </div>
            <div class="mb-3">
              <label for="inputPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="inputPassword" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign in</button>
          </form>
        </div>
      </div>
    </div>
    <script>
      const loginForm = document.getElementById('login-form');
      loginForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(loginForm);
        const response = await fetch('/api/login', {
          method: 'POST',
          body: formData
        });
        const jsonResponse = await response.json();
        if (jsonResponse.success) {
          window.location.href = '/about';
        } else {
          alert('Login failed. Please try again.');
        }
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
</html>
