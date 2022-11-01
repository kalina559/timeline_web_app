'use strict'
var CategoryItemModel = function (category) {
    this.id = category.id;
    this.name = category.name;
    this.color_hex = category.color_hex;

    this.showEditCategoryModal = function () {
        categoryModel.categoryModalMode('Edit');
        categoryModel.editedCategoryId(this.id);
        categoryModel.categoryName(this.name);
        categoryModel.categoryColorHex(this.color_hex);
        $('#category-modal').modal('show');
    }

    this.showDeleteCategoryModal = function () {
        categoryModel.editedCategoryId(this.id);
        $('#delete-category-modal').modal('show');
    }
}

var categoryModel = new function () {
    var self = this;
    this.categoryModalMode = ko.observable(null)
    this.categoryName = ko.observable(null)
    this.categoryColorHex = ko.observable(null)
    this.editedCategoryId = ko.observable(null)
    this.categories = ko.observableArray()

    self.resetCategoryFields = function () {
        self.categoryName(null)
        self.categoryColorHex(null)
    }

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

    self.showAddCategoryModal = function () {
        self.resetCategoryFields();
        self.categoryModalMode('Add');
        $('#category-modal').modal('show');
    }

    self.submitCategory = function () {
        if (self.categoryModalMode() == 'Add') {
            addCategory()
        } else if (self.categoryModalMode() == 'Edit') {
            editCategory()
        }
    }

    function addCategory() {
        var requestArguments = {
            Name: self.categoryName,
            ColorHex: self.categoryColorHex() != null ? self.categoryColorHex : '#000000'
        }
        appModel.makeAjaxCall('add', requestArguments,
            '../src/php/categories/CategoriesController.php',
            function (data) {
                $('#category-modal').modal('hide');
                if (data == 'success') {
                    self.refreshCategories()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Add category failed');
                }
            })
    }

    function editCategory() {
        var requestArguments = {
            Id: self.editedCategoryId,
            Name: self.categoryName,
            ColorHex: self.categoryColorHex
        }
        appModel.makeAjaxCall('update', requestArguments,
            '../src/php/categories/CategoriesController.php',
            function (data) {
                $('#category-modal').modal('hide');
                if (data == 'success') {
                    self.refreshCategories()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Update category failed');
                }
            })
    }

    self.deleteCategory = function () {
        var requestArguments = {
            Id: self.editedCategoryId
        }
        appModel.makeAjaxCall('delete', requestArguments,
            '../src/php/categories/CategoriesController.php',
            function (data) {
                $('#delete-category-modal').modal('hide');
                if (data == 'success') {
                    self.refreshCategories()
                } else {
                    // shouldn't really happen, but just in case
                    alert('Delete category failed');
                }
            })
    }

}