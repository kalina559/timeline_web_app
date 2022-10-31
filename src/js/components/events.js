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
        var currentCategory = categoryModel.categories().find(c => c.id == this.category_id)
        return currentCategory.color_hex
    }

    this.showDeleteEventModal = function () {
        eventModel.editedEventId(this.id);
        $('#delete-event-modal').modal('show');
    }

    this.showEditEventModal = function () {

        eventModel.eventModalMode('Edit');
        eventModel.editedEventId(this.id);
        eventModel.eventTitle(this.title)
        eventModel.eventDescription(this.description)
        eventModel.eventStartDate(this.start_date)
        eventModel.eventEndDate(this.end_date)

        var selectedCategory = categoryModel.categories().find(c => c.id == this.category_id)
        eventModel.eventCategory(selectedCategory)

        //appModel.eventImageFile(this.title)
        $('#event-modal').modal('show');
    }
}

var eventModel = new function () {
    var self = this;
    this.eventModalMode = ko.observable(null)
    this.eventTitle = ko.observable(null)
    this.eventDescription = ko.observable(null)
    this.eventStartDate = ko.observable(null)
    this.eventEndDate = ko.observable(null)
    this.eventCategory = ko.observable(null)
    this.eventImageFile = ko.observable(null)
    this.editedEventId = ko.observable(null)
    this.events = ko.observableArray()

    self.resetEventFields = function () {
        self.eventTitle(null)
        self.eventDescription(null)
        self.eventStartDate(null)
        self.eventEndDate(null)
        self.eventCategory(null)
        self.eventImageFile(null)
    }

    self.refreshEvents = function () {
        var requestArguments = {
            User: 'something so that arguments are not null'
        }
        appModel.makeAjaxCall('get', requestArguments,
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

    self.submitEvent = function () {
        if (self.eventModalMode() == 'Add') {
            addEvent()
        } else if (self.eventModalMode() == 'Edit') {
            editEvent()
        }
    }

    function addEvent() {
        var requestArguments = {
            Title: self.eventTitle,
            Description: self.eventDescription,
            StartDate: self.eventStartDate,
            EndDate: self.eventEndDate,
            CategoryId: self.eventCategory().id,
            ImageFile: self.eventImageFile
        }
        appModel.makeAjaxCall('add', requestArguments,
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

    function editEvent() {
        var requestArguments = {
            Id: self.editedEventId,
            Title: self.eventTitle,
            Description: self.eventDescription,
            StartDate: self.eventStartDate,
            EndDate: self.eventEndDate,
            CategoryId: self.eventCategory().id,
            ImageFile: self.eventImageFile
        }
        appModel.makeAjaxCall('update', requestArguments,
            '../src/php/events/EventController.php',
            function (data) {
                $('#event-modal').modal('hide');
                if (data == 'success') {
                    self.refreshEvents()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Update event failed');
                }
            })
    }

    self.deleteEvent = function () {
        var requestArguments = {
            Id: self.editedEventId
        }
        appModel.makeAjaxCall('delete', requestArguments,
            '../src/php/events/EventController.php',
            function (data) {
                $('#delete-event-modal').modal('hide');
                if (data == 'success') {
                    self.refreshEvents()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Delete event failed');
                }
            })
    }

}