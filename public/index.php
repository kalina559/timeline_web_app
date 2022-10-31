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
        <h2 data-bind="text: title"></h2>
        <h4 data-bind="text: formattedEventPeriod()"></h4>
        <p data-bind="text: description"></p>
        <div data-bind="visible: appModel.userLoggedIn()">
          <button data-bind="click: showEditEventModal">Edit</button>
          <button data-bind="click: showDeleteEventModal">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add event input modal -->
  <form class="form-horizontal" data-bind="with: appModel, submit: editEvent">
    <div class="modal fade" id="add-event-modal" data-backdrop="static" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add event</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form class="form-horizontal">
            <div class="modal-body">
              <div class="form-group modal-field">
                <label for="title" class="col-sm-offset-1 col-sm-4 control-label">Title:</label>
                <div class="col-sm-8">
                  <textarea required id="title" data-bind="value: eventTitle" class="form-control"></textarea>
                </div>
              </div>

              <div class="form-group modal-field">
                <label for="description" class="col-sm-offset-1 col-sm-4 control-label">Description:</label>
                <div class="col-sm-8">
                  <textarea required rows="4" id="description" data-bind="value: eventDescription" class="form-control"></textarea>
                </div>
              </div>

              <div class="form-group modal-field">
                <label for="start-date" class="col-sm-offset-1 col-sm-4 control-label">Start date:</label>
                <div class="col-sm-8">
                  <input required id="start-date" data-bind="value: eventStartDate" type="date" class="form-control"></input>
                </div>
              </div>

              <div class="form-group modal-field">
                <label for="end-date" class="col-sm-offset-1 col-sm-4 control-label">End date (optional):</label>
                <div class="col-sm-8">
                  <input id="end-date" data-bind="value: eventEndDate" type="date" class="form-control"></input>
                </div>
              </div>

              <div class="form-group modal-field">
                <label for="category" class="col-sm-offset-1 col-sm-4 control-label">Category:</label>
                <div class="col-sm-8">
                  <select required id="category" class="form-control" data-bind="options: appModel.categories, optionsText: 'name',
                       value: 'id',
                       optionsCaption: 'Choose...', value: eventCategory"></select>
                </div>
              </div>

              <div class="form-group modal-field">
                <label for="image" class="col-sm-offset-1 col-sm-4 control-label">Image:</label>
                <div class="col-sm-8">
                  <input id="image" type="file" accept="image/*" onchange="appModel.updateEventImageFile(event)">
                  <img class="col-sm-12" id="addEventImage" />
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
              <button data-bind="disable: appModel.busy, text: appModel.eventModalMode" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </form>

  <!-- Delete event confirmation -->
  <form class="form-horizontal" data-bind="with: appModel, submit: addEvent">
    <div class="modal fade" id="delete-event-modal" data-backdrop="static" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Delete event</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form class="form-horizontal">
            <div class="modal-body">
              <p>Are you sure you want to remove this event?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.busy"><span class="fa fa-times"></span> Cancel</button>
              <button data-bind="disable: appModel.busy" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span> Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </form>

  <script type="text/javascript" src="../src/libs/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>