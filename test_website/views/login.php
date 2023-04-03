<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="<?=PUBLIC_DIR?>/css/login.css">
  </head>
  <body>
    <div class="login-container">
      <h1>Login</h1>
      <form>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
      </form>
    </div>

    <script>
    const loginForm = document.querySelector('#login-form');

    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = new FormData(loginForm);

      fetch('/api/login', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.result) {
          window.location.href = '/admin';
        } else {
          alert('Invalid username or password');
        }
      })
      .catch(error => {
        console.error(error);
        alert('Error occurred while logging in');
      });
    });
  </script>
  </body>
</html>
