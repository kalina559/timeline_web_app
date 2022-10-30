<html data-bind="with: appModel, visible: true">

<body>

  <!DOCTYPE HTML>
  <!-- (A) CSS & JS -->
  <link rel="stylesheet" href="../src/libs/bootstrap/css/bootstrap.min.css">
  <link href="../src/css/styles.css" rel="stylesheet">
  <script type="text/javascript" src="../src/libs/jquery.min.js"></script>
  <script type="text/javascript" src="../src/libs/moment/moment.min.js"></script>
  <script type="text/javascript" src="../src/libs/knockout-3.5.1.min.js"></script>
  <script src="../src/js/script.js"></script>

  <!-- login window -->
  <div class="col-sm-12">
    <div class="col-sm-6" style="text-align: center; width: 50%; margin: 0 auto" data-bind="visible: !appModel.userLoggedIn()">

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


    <div class="col-sm-6" style="text-align: center; width: 50%; margin: 0 auto">
      <button type="button" data-bind="click: appModel.tryLogout, visible: appModel.userLoggedIn()">Logout</button>
      <div>
        <span data-bind="visible: appModel.userLoggedIn()">Amazing feature only visible to logged in users</span>
        <button type="button" data-bind="click: appModel.showAddEventModal, visible: appModel.userLoggedIn()">Some action available to logged in users</button>
      </div>
    </div>
  </div>

  <div class="timeline" data-bind="foreach: appModel.events">
    <div class="container ">
      <div class="content">
        <h2 data-bind="text: formattedEventPeriod()"></h2>
        <p data-bind="text: description"></p>
      </div>
    </div>
  </div>

  <!-- add event input modal -->
  <div class="modal fade" id="add-event-modal" data-backdrop="static" data-bind="with: appModel" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add event</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal" data-bind="">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-4 control-label">Nyt navn</label>
              <div class="col-sm-6">
                <input class="form-control" required oninput="appModel.checkInput(this, 'common')" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.busy"><span class="fa fa-times"></span> Luk</button>
            <button data-bind="disable: appModel.busy" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span> Rediger</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="../src/libs/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>