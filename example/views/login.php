<style>
  #error {
    display: none;
    background-color: #cc0b0b;
    max-width: fit-content;
    margin: 8px 0px;
    padding: 4px;
    border-radius: 8px;
  }
</style>

<h2>Login</h2>
<form>
  <label for="password">Password:</label>
  <input type="text" id="password" name="password">
  <br><br>
  <input type="submit" value="Login">
</form>
<div id="error">Wrong password!</div>

<script>
  const form = $('form');
  const error = $('#error');
  const password = $('#password');
  let timeoutId = null;

  password.focus();

  form.on('submit', function(event) {
    event.preventDefault();

    const fields = {
      password: password.val(),
    }

    console.log(JSON.stringify(fields));

    $.post('<?= BASE_PATH ?>/api/login', fields, function(data) {
      if (data.success) {
        // Pass
        window.location.href = '<?= BASE_PATH ?>/admin';
      } else {
        // Fail
        password.val('');
        password.focus();
        error.stop();
        error.fadeIn(100);

        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
          error.fadeOut(1000);
        }, 1000);
      }
    });
  });
</script>
