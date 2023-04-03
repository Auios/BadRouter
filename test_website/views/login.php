<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <link rel="stylesheet" href="<?=PUBLIC_DIR?>/css/style.css">
</head>
<body>
  <div class="login-box">
    <h1>Login</h1>
    <form id="login-form">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <input type="submit" value="Login">
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
