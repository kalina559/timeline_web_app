'use strict'

window.addEventListener('error', function (event) {

});

$(document).ready(function () {
    ko.applyBindings(appModel, $('html')[0])
    eventModel.refreshEvents()
    appModel.checkIfUserLoggedIn()
    categoryModel.refreshCategories()
})

var appModel = new function () {
    var self = this;
    this.userLoggedIn = ko.observable(false)
    this.showLogin = ko.observable(false)
    this.loginButtonText = ko.observable('Login')
    this.login = ko.observable(null)
    this.password = ko.observable(null)
    this.currentUserName = ko.observable(null)
    this.currentPassword = ko.observable(null)
    this.newPassword = ko.observable(null)
    this.newPasswordRepeat = ko.observable(null)
    this.apiCallsInProcess = ko.observable(0)

    this.dateFormat = 'DD/MM/YYYY'


    self.checkIfUserLoggedIn = function () {
        this.makeAjaxCall({ User: 'something so that arguments are not null' },
        '../src/php/controllers/account/CheckLoginStateController.php',
        function (data) {
            if (data != null) {
                self.userLoggedIn(true)
                self.currentUserName(data)
            }
        })
    }

    self.toggleLogin = function () {
        self.showLogin(!self.showLogin())

        if (self.showLogin()) {
            self.loginButtonText('Close')
        } else {
            self.loginButtonText('Login')
        }
    }

    self.printTimeline = function () {
        window.print();
    }

    self.tryLogin = function () {

        var requestArguments = {
            Login: self.login,
            Password: self.password
        }

        this.makeAjaxCall(requestArguments,
            '../src/php/controllers/account/LoginController.php',
            function (data) {
                if (data == 'success') {
                    self.userLoggedIn(true)
                    self.currentUserName(self.login())
                    self.login(null)
                    self.password(null)
                } else {
                    alert('Login failed');
                }
            })
    }

    self.tryLogout = function () {
        this.makeAjaxCall({ User: 'something so that arguments are not null' },
            '../src/php/controllers/account/LogoutController.php',
            function (data) {
                if (data == 'success') {
                    self.userLoggedIn(false)
                    self.currentUserName(null)
                } else {
                    // shouldn't really happen, but just in case
                    alert('Logout failed');
                }
            })
    }

    self.showChangePasswordModal = function () {
        self.currentPassword(null);
        self.newPassword(null);
        self.newPasswordRepeat(null);
        $('#update-password-modal').modal('show');
    }

    self.validatePasswordRepeat = function () {
        var newPassword = document.getElementById("new-password");
        var newPasswordRepeat = document.getElementById("new-password-repeat");
        if(newPassword.value != newPasswordRepeat.value){
            newPasswordRepeat.setCustomValidity("Passwords don't match");
        } else {
            newPasswordRepeat.setCustomValidity('');
        }
    }

    self.updatePassword = function () {
        var requestArguments = {
            OldPassword: self.currentPassword,
            NewPassword: self.newPassword
        }

        this.makeAjaxCall(requestArguments,
            '../src/php/controllers/account/UpdatePasswordController.php',
            function (data) {
                if (data == 'success') {
                    $('#update-password-modal').modal('hide');
                } else {
                    // shouldn't really happen, but just in case
                    alert(data);
                }
            })
    }

    self.updatePassword = function () {
        var requestArguments = {
            OldPassword: self.currentPassword,
            NewPassword: self.newPassword
        }

        this.makeAjaxCall(requestArguments,
            '../src/php/controllers/account/UpdatePasswordController.php',
            function (data) {
                if (data == 'success') {
                    $('#update-password-modal').modal('hide');
                } else {
                    // shouldn't really happen, but just in case
                    alert(data);
                }
            })
    }

    self.isBusy = ko.computed(function () {
        return self.apiCallsInProcess() != 0;
    });

    self.makeAjaxCall = function (args, url, success) {

        // we're locking the UI everytime an AJAX call is made
        var concatCallback = function (data) {
            success(data)
            self.apiCallsInProcess(self.apiCallsInProcess() - 1)
        };

        self.apiCallsInProcess(self.apiCallsInProcess() + 1)

        jQuery.ajax({
            type: 'POST',
            data: { arguments: args },
            url: url,
            success: concatCallback,
            error: function (data) {
                alert(`Ajax call failed with message: ${data.responseText}`);
                self.apiCallsInProcess(self.apiCallsInProcess() - 1)
            }
        });
    }
}()