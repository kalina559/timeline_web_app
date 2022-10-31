'use strict'
var EventItemModel = function (event) {
    this.id = event.id;
    this.start_date = event.start_date;
    this.end_date = event.end_date;
    this.category_id = event.category_id;
    this.title = event.title;
    this.description = event.description;

    this.formattedEventPeriod = function () {
        if (this.end_date == null) {
            return moment.utc(event.start_date).format(appModel.dateFormat);
        } else if (this.start_date != null) {
            return `${moment.utc(this.start_date).format(appModel.dateFormat)} - ${moment.utc(this.end_date).format(appModel.dateFormat)}`;
        } else {
            return '';
        }
    }
    
    this.categoryColor = function () {
        var currentCategory = appModel.categories().find(c => c.id == this.category_id)
        return currentCategory.color_hex
    }

    this.showDeleteEventModal = function () {
        appModel.editedEventId(this.id);
        $('#delete-event-modal').modal('show');
    }

    this.showEditEventModal = function () {

        appModel.eventModalMode('Edit');
        appModel.editedEventId(this.id);
        appModel.eventTitle(this.title)
        appModel.eventDescription(this.description)
        appModel.eventStartDate(this.start_date)
        appModel.eventEndDate(this.end_date)

        var selectedCategory = appModel.categories().find(c => c.id == this.category_id)
        appModel.eventCategory(selectedCategory)

        //appModel.eventImageFile(this.title)
        $('#event-modal').modal('show');
    }
}

var CategoryItemModel = function (category) {
    this.id = category.id;
    this.name = category.name;
    this.color_hex = category.color_hex;
}

window.addEventListener('error', function (event) {

});

$(document).ready(function () {
    ko.applyBindings(appModel, $('html')[0])

    appModel.refreshEvents()
    appModel.refreshCategories()
})

var appModel = new function () {
    var self = this;
    this.userLoggedIn = ko.observable(false)
    this.showLogin = ko.observable(false)
    this.loginButtonText = ko.observable('Login')
    this.login = ko.observable(null)
    this.password = ko.observable(null)
    this.currentUser = ko.observable(null)
    this.events = ko.observableArray()
    this.categories = ko.observableArray()

    this.eventModalMode = ko.observable(null)
    this.eventTitle = ko.observable(null)
    this.eventDescription = ko.observable(null)
    this.eventStartDate = ko.observable(null)
    this.eventEndDate = ko.observable(null)
    this.eventCategory = ko.observable(null)
    this.eventImageFile = ko.observable(null)
    this.editedEventId = ko.observable(null)

    this.dateFormat = 'DD/MM/YYYY'

    makeAjaxCall('checkIfLoggedIn', { User: 'something so that arguments are not null' },
        '../src/php/account/AccountController.php',
        function (data) {
            if (data != null) {
                self.userLoggedIn(true)
                self.currentUser(data.success)
            }
        })

    self.toggleLogin = function () {
        self.showLogin(!self.showLogin())

        if (self.showLogin()) {
            self.loginButtonText('Close')
        } else {
            self.loginButtonText('Login')
        }
    }

    self.resetEventFields = function () {
        self.eventTitle(null)
        self.eventDescription(null)
        self.eventStartDate(null)
        self.eventEndDate(null)
        self.eventCategory(null)
        self.eventImageFile(null)
    }

    self.tryLogin = function () {

        var requestArguments = {
            Login: self.login,
            Password: self.password
        }

        makeAjaxCall('login', requestArguments,
            '../src/php/account/AccountController.php',
            function (data) {
                if (data == 'success') {
                    self.userLoggedIn(true)
                    self.currentUser(self.login)
                    self.login(null)
                    self.password(null)
                } else {
                    alert('Login failed');
                }
            })
    }

    self.tryLogout = function () {
        var requestArguments = {
            User: self.currentUser
        }

        makeAjaxCall('logout', requestArguments,
            '../src/php/account/AccountController.php',
            function (data) {
                if (data == 'success') {
                    self.userLoggedIn(false)
                    self.currentUser(null)
                } else {
                    // shouldn't really happen, but just in case
                    alert('Logout failed');
                }
            })
    }

    self.refreshEvents = function () {
        var requestArguments = {
            User: 'something so that arguments are not null'
        }
        makeAjaxCall('get', requestArguments,
            '../src/php/events/EventController.php',
            function (data) {
                if (data != null) {
                    self.events.removeAll();
                    var events = data

                    var eventArray = [];

                    for (var i = 0; i < events.length; i++) {
                        eventArray[i] = new EventItemModel(events[i])
                    }

                    self.events(eventArray);
                } else {
                    // shouldn't really happen, but just in case
                    alert('Get events failed');
                }
            })
    }

    self.refreshCategories = function () {
        var requestArguments = {
            User: 'something so that arguments are not null'
        }
        makeAjaxCall('get', requestArguments,
            '../src/php/categories/CategoriesController.php',
            function (data) {
                if (data != null) {
                    self.categories.removeAll();
                    var categories = data

                    var categoriesArray = [];

                    for (var i = 0; i < categories.length; i++) {
                        categoriesArray[i] = new CategoryItemModel(categories[i])
                    }

                    self.categories(categoriesArray);
                } else {
                    // shouldn't really happen, but just in case
                    alert('Get categories failed');
                }
            })
    }

    self.showAddEventModal = function () {
        self.resetEventFields();
        self.eventModalMode('Add');
        $('#event-modal').modal('show');
    }

    self.updateEventImageFile = function (value) {
        var addEventImage = document.getElementById('addEventImage');
        addEventImage.src = URL.createObjectURL(event.target.files[0]);

        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onloadend = () => {
            const base64String = reader.result
                .replace('data:', '')
                .replace(/^.+,/, '');

            self.eventImageFile(reader.result);
        };
        reader.readAsDataURL(file);

        addEventImage.onload = function () {
            URL.revokeObjectURL(addEventImage.src) // free memory
        }
    }

    self.addEvent = function () {
        var requestArguments = {
            Title: self.eventTitle,
            Description: self.eventDescription,
            StartDate: self.eventStartDate,
            EndDate: self.eventEndDate,
            CategoryId: self.eventCategory().id,
            ImageFile: self.eventImageFile
        }
        makeAjaxCall('add', requestArguments,
            '../src/php/events/EventController.php',
            function (data) {
                $('#event-modal').modal('hide');
                if (data == 'success') {
                    self.refreshEvents()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Add event failed');
                }
            })
    }

    self.editEvent = function () {
        var requestArguments = {
            Id: self.editedEventId,
            Title: self.eventTitle,
            Description: self.eventDescription,
            StartDate: self.eventStartDate,
            EndDate: self.eventEndDate,
            CategoryId: self.eventCategory().id,
            ImageFile: self.eventImageFile
        }
        makeAjaxCall('update', requestArguments,
            '../src/php/events/EventController.php',
            function (data) {
                $('#event-modal').modal('hide');
                if (data == 'success') {
                    self.refreshEvents()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Add event failed');
                }
            })
    }

    self.deleteEvent = function () {
        var requestArguments = {
            Id: self.editedEventId
        }
        makeAjaxCall('delete', requestArguments,
            '../src/php/events/EventController.php',
            function (data) {
                $('#delete-event-modal').modal('hide');
                if (data == 'success') {
                    self.refreshEvents()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Add event failed');
                }
            })
    }

    function makeAjaxCall(functionName, args, url, success) {
        jQuery.ajax({
            type: 'POST',
            data: { functionname: functionName, arguments: args },
            url: url,
            success: success,
            error: function (data) {
                alert(`Ajax call failed with message: ${data.responseText}`);
            }
        });
    }
}