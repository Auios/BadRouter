<nav>
  <ul>
    <li><a href="<?= BASE_PATH ?>/">Home</a></li>
    <li><a href="<?= BASE_PATH ?>/dynamic/42">dynamic/42</a></li>
    <li><a href="<?= BASE_PATH ?>/dynamic/bob_smith">dynamic/bob_smith</a></li>
    <li><a href="<?= BASE_PATH ?>/this/path/doesn't/exist">404</a></li>
    <br>
    <li id="admin-link" style="display: none;"><a href="<?= BASE_PATH ?>/admin">Admin page</a></li>
    <li id="login-link"><a href="<?= BASE_PATH ?>/login">Login</a></li>
    <li id="logout-link" style="display: none;"><a href="#">Logout</a></li>
  </ul>
</nav>

<script>
  $.get('<?= BASE_PATH ?>/api/is_admin', function(data) {
    if (data.result) {
      $('#admin-link').show();
      $('#logout-link').show();
      $('#login-link').hide();
      sessionStorage.setItem('is_admin', 'true');
    } else {
      $('#admin-link').hide();
      $('#logout-link').hide();
      $('#login-link').show();
      sessionStorage.setItem('is_admin', 'false');
    }
  });

  $('#logout-link').click(function(e) {
    e.preventDefault();
    $.post('<?= BASE_PATH ?>/api/logout', function(data) {
      window.location.reload();
    });
  });
</script>
