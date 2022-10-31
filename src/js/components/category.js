'use strict'
var CategoryItemModel = function (category) {
    this.id = category.id;
    this.name = category.name;
    this.color_hex = category.color_hex;
}

var categoryModel = new function () {
    var self = this;
    this.categories = ko.observableArray()

    self.refreshCategories = function () {
        var requestArguments = {
            User: 'something so that arguments are not null'
        }
        appModel.makeAjaxCall('get', requestArguments,
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

}