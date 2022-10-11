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
            },
            function (data) {
                alert('Login failed');
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
            },
            function (data) {
                alert('Logout failed');
            })
    }

    function makeAjaxCall(functionName, args, url, success, error) {
        jQuery.ajax({
            type: 'POST',
            data: { functionname: functionName, arguments: args },
            url: url,
            success: success,
            error: error
        });
    }

    function getJSessionId(){
        var jsId = document.cookie.match(/JSESSIONID=[^;]+/);
        if(jsId != null) {
            if (jsId instanceof Array)
                jsId = jsId[0].substring(11);
            else
                jsId = jsId.substring(11);
        }
        return jsId;
    }
}

