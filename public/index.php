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
  <script src="../src/js/components/category.js"></script>
  <script src="../src/js/components/events.js"></script>

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
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </form>
      <button class="btn btn-primary" type="button" data-bind="click: appModel.toggleLogin, text: appModel.loginButtonText">Login</button>
    </div>

    <!-- add new event -->
    <div class="col-sm-6" style="text-align: center; width: 50%; margin: 0 auto">
      <button class="btn btn-danger" type="button" data-bind="click: appModel.tryLogout, visible: appModel.userLoggedIn()">Logout</button>
      <div>
        <button class="btn btn-primary" type="button" data-bind="click: eventModel.showAddEventModal, visible: appModel.userLoggedIn()">Add new event</button>
      </div>
    </div>

  </div>

  <!-- list of all categories -->
  <aside class="category-legend col-sm-3" data-bind="with: categoryModel">
    <h1 class="mb-4">Categories:</h1>
    <div data-bind="foreach: categoryModel.categories">
      <div class="row">
        <div class="category-color-box" data-bind="click: showEditCategoryModal, style:{ 'background-color': color_hex}"></div>
        <p data-bind="text: name"></p>
        <p class="delete-button" data-bind="click: showDeleteCategoryModal">x</p>
      </div>
    </div>
    <button class="btn btn-primary" data-bind="click: showAddCategoryModal">Add a category</button>
  </aside>

  <!-- timeline with all events -->
  <div class="timeline" data-bind="foreach: eventModel.events">
    <div class="container">
      <div class="content" data-bind="style:{ 'background-color': categoryModel.categories().length > 0 ? categoryColor() : '#FFFFFF' }">
        <h2 data-bind="text: title"></h2>
        <div class="image-container">
          <img class="event-image" data-bind="attr:{src: image}, visible: image != null" />
        </div>
        <h4 data-bind="text: formattedEventPeriod()"></h4>
        <p data-bind="text: description"></p>
        <div data-bind="visible: appModel.userLoggedIn()">
          <button class="btn btn-primary" data-bind="click: showEditEventModal">Edit</button>
          <button class="btn btn-danger" data-bind="click: showDeleteEventModal">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Event input modal -->
  <form class="form-horizontal add-update-modal" data-bind="with: eventModel, submit: eventModel.submitEvent">
    <div class="modal fade" id="event-modal" data-backdrop="static" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" data-bind="text: eventModalMode() + ' event'"></h4>
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
                  <select required id="category" class="form-control" data-bind="options: categoryModel.categories, optionsText: 'name',
                       value: 'id',
                       optionsCaption: 'Choose...', value: eventCategory"></select>
                </div>
              </div>
              <div class="form-group modal-field">
                <label for="image" class="col-sm-offset-1 col-sm-4 control-label">Image:</label>
                <div class="col-sm-8">
                  <input id="image" type="file" accept="image/*" onchange="eventModel.updateEventImageFile(event)">
                  <img class="col-sm-12" id="addEventImage" />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
              <button data-bind="disable: appModel.busy, text: eventModalMode" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </form>

  <!-- Delete event confirmation -->
  <form class="form-horizontal add-update-modal" data-bind="with: eventModel, submit: eventModel.deleteEvent">
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
              <button data-bind="disable: appModel.busy" type="submit" class="btn btn-danger"><span class="fa fa-pencil"></span> Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </form>
  <script type="text/javascript" src="../src/libs/bootstrap/js/bootstrap.min.js"></script>
</body>

<!-- Category input modal -->
<form class="form-horizontal add-update-modal" data-bind="with: categoryModel, submit: categoryModel.submitCategory">
  <div class="modal fade" id="category-modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" data-bind="text: categoryModalMode() + ' category'"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <div class="form-group modal-field">
              <label for="category-name" class="col-sm-offset-1 col-sm-4 control-label">Name:</label>
              <div class="col-sm-8">
                <textarea required id="category-name" data-bind="value: categoryName" class="form-control"></textarea>
              </div>
            </div>
            <div class="form-group modal-field">
              <label for="category-color" class="col-sm-offset-1 col-sm-4 control-label">Color:</label>
              <div class="col-sm-8">
                <textarea required rows="4" id="category-color" data-bind="value: categoryColorHex" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
            <button data-bind="disable: appModel.busy, text: categoryModalMode" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>

<!-- Delete category confirmation -->
<form class="form-horizontal add-update-modal" data-bind="with: categoryModel, submit: categoryModel.deleteCategory">
  <div class="modal fade" id="delete-category-modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <p>Are you sure you want to remove this category?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.busy"><span class="fa fa-times"></span> Cancel</button>
            <button data-bind="disable: appModel.busy" type="submit" class="btn btn-danger"><span class="fa fa-pencil"></span> Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>


<script type="text/javascript" src="../src/libs/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>