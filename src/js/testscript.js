window.onload = function () {
  document.getElementById("testButton").addEventListener("click", function () {

    jQuery.ajax({
      type: 'POST',
      data: { functionname: 'login', arguments: ['test', 'test'] },
      url: '../src/php/account/AccountController.php',
      success: function (data) {
        alert(data);
      },
      error: function (data) {
        alert(data);
      }
    });

  });
};