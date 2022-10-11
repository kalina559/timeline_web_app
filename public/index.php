<html data-bind="with: appModel, visible: true">

<body>

  <!DOCTYPE HTML>
  <!-- (A) CSS & JS -->
  <link href="../src/css/styles.css" rel="stylesheet">

  <script type="text/javascript" src="../src/libs/jquery.min.js"></script>
  <script type="text/javascript" src="../src/libs/knockout-3.5.1.min.js"></script>
  <script src="../src/js/script.js"></script>


  <div data-bind="visible: !appModel.userLoggedIn()">

    <form class="login-form" data-bind="visible: appModel.showLogin(), submit: appModel.tryLogin">
      <p>Login: <input class="input-field" required type="text" id="login" data-bind="value: appModel.login" placeholder = "Type your login"/></p>
      <p>Password: <input class="input-field" required type="text" id="password" data-bind="value: appModel.password" placeholder = "Type your password"/></p>

      <button type="submit">Login</button>
    </form>

    <button type="button" data-bind="click: appModel.toggleLogin, text: appModel.loginButtonText">Show login form</button>
  </div>

  <button type="button" data-bind="click: appModel.tryLogout, visible: appModel.userLoggedIn()">Logout</button>

<span data-bind="visible: appModel.userLoggedIn()">Amazing feature only visible to logged in users</span>

</body>

</html>