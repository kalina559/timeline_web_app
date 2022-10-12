<html data-bind="with: appModel, visible: true">

<body>

  <!DOCTYPE HTML>
  <!-- (A) CSS & JS -->
  <link rel="stylesheet" href="../src/libs/bootstrap/css/bootstrap.min.css">
  </link>
  <link href="../src/css/styles.css" rel="stylesheet">
  <script type="text/javascript" src="../src/libs/jquery.min.js"></script>
  <script type="text/javascript" src="../src/libs/knockout-3.5.1.min.js"></script>
  <script src="../src/js/script.js"></script>








  <!-- login window -->
  <div class="col-sm-4" style="text-align: center; background: aqua" data-bind="visible: !appModel.userLoggedIn()">

    <form class="login-form col-sm-12" data-bind="visible: appModel.showLogin(), submit: appModel.tryLogin">
      <div class="col-sm-12">
        <label class="col-sm-4" for="login-field">Login:</label>
        <input id="login-field" class="input-field col-sm-6" required type="text" data-bind="value: appModel.login" placeholder="Type your login" />
      </div>

      <div class="col-sm-12">
        <label class="col-sm-4" for="password-field">Password:</label>
        <input id="password-field" class="input-field col-sm-6" type="password" required type="text" data-bind="value: appModel.password" placeholder="Type your password" />
      </div>

      <div class="col-sm-12">
        <button type="submit">Submit</button>
      </div>
    </form>

    <button type="button" data-bind="click: appModel.toggleLogin, text: appModel.loginButtonText">Login</button>
  </div>

  <button type="button" data-bind="click: appModel.tryLogout, visible: appModel.userLoggedIn()">Logout</button>

  <span data-bind="visible: appModel.userLoggedIn()">Amazing feature only visible to logged in users</span>





















  <script type="text/javascript" src="../src/libs/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>