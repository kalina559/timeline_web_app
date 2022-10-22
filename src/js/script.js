'use strict'

window.addEventListener('error', function (event) {

});

$(document).ready(function () {
    ko.applyBindings(appModel, $('html')[0])
    $('#crud-timeline').Timeline()

    $('#crud-timeline').Timeline('addEvent', [
        { id: 21, start: '2022-11-16 00:00', end: '2022-11-20 02:00', row: 2, label: 'Add Event', content: 'test test test...' },
        { id: 22, start: '2022-11-18 12:00', end: '2022-11-22 12:00', row: 3, label: 'Add Event 2', content: 'test2 test2 test2...' }
    ]
    )

    $('#crud-timeline').Timeline( 'reload', { reloadCacheKeep: true }, function(){
        console.log("essa")
    })

    
    // $('#crud-timeline').Timeline({       
    //     startDatetime: "2019-02-25 00:00"   
    // })

})

var appModel = new function () {
    var self = this;
    this.userLoggedIn = ko.observable(false)
    this.showLogin = ko.observable(false)
    this.loginButtonText = ko.observable('Login')
    this.login = ko.observable(null)
    this.password = ko.observable(null)
    this.currentUser = ko.observable(null)

    // $("#timeline').Timeline({       
    //     startDatetime: "2019-02-25 00:00"   
    // })

    // $('#timeline').Timeline('addEvent', [
    //     { id: 21, start: '2022-11-16 00:00', end: '2022-11-20 02:00', row: 2, label: 'Add Event', content: 'test test test...' },
    //     { id: 22, start: '2022-11-18 12:00', end: '2022-11-22 12:00', row: 3, label: 'Add Event 2', content: 'test2 test2 test2...' }
    // ],
    //     function (elm, opts, usrdata, addedEvents) {
    //         console.log(usrdata.message) // show "Added Events!" in console
    //     },
    //     { message: 'Added Events!' }
    // )

    makeAjaxCall('checkIfLoggedIn', { User: 'something so that arguments are not null' },
        '../src/php/account/AccountController.php',
        function (data) {
            if (data != null) {
                self.userLoggedIn(true)
                self.currentUser(data.success)
            }
        })


    self.setUpTimeline = function () {
        $('#timeline').Timeline('addEvent', [
            { id: 21, start: '2022-11-16 00:00', end: '2022-11-20 02:00', row: 2, label: 'Add Event', content: 'test test test...' },
            { id: 22, start: '2022-11-18 12:00', end: '2022-11-22 12:00', row: 3, label: 'Add Event 2', content: 'test2 test2 test2...' }
        ],
            function (elm, opts, usrdata, addedEvents) {
                console.log(usrdata.message) // show "Added Events!" in console
            },
            { message: 'Added Events!' }
        )
    }

    self.toggleLogin = function () {
        self.showLogin(!self.showLogin())

        if (self.showLogin()) {
            self.loginButtonText('Close')
        } else {
            self.loginButtonText('Login')
        }
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

    self.tryAddEvent = function () {
        var requestArguments = {
            User: 'something so that arguments are not null'
        }
        makeAjaxCall('add', requestArguments,
            '../src/php/events/EventController.php',
            function (data) {
                if (data == 'success') {
                    alert('Add event succeeded');
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
                alert(`Ajax call failed with message: ${data}`);
            }
        });
    }
}

