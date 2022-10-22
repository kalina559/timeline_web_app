'use strict'

window.addEventListener('error', function (event) {

});

$(document).ready(function () {
    ko.applyBindings(appModel, $('html')[0])

    
})

var appModel = new function () {
    var self = this;
    this.userLoggedIn = ko.observable(false)
    this.showLogin = ko.observable(false)
    this.loginButtonText = ko.observable('Login')
    this.login = ko.observable(null)
    this.password = ko.observable(null)
    this.currentUser = ko.observable(null)


    makeAjaxCall('checkIfLoggedIn', { User: 'something so that arguments are not null'},
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
                    alert('Logout succeeded');
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

