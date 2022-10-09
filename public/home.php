<html>
<body>

<!DOCTYPE HTML>
<!-- (A) CSS & JS -->
<link href="../src/css/styles.css" rel="stylesheet">
<script src="../src/js/testscript.js"></script>
<script type="text/javascript" src="../src/libs/jquery-min.js" ></script>

<?php
  function runMyFunction() {
    echo 'I just ran a php function';
  }

  if (isset($_GET['tryLogin'])) {
    runMyFunction();
  }
?>


    <form method="post">
    <p>1-st number: <input type="text" name="name" /></p>
    <p>2-nd number: <input type="text" name="email" /></p>
    <p><input type="submit"/></p>


<button type="button" id="testButton">Click Me!</button>

<script type="text/javascript">
	
	    jQuery.ajax({
        type: 'POST',
		data: {functionname: 'login', arguments: ['test', 'test']}, 
        url: '../src/php/functions_address.php',
         success:function(data) {
        alert(data); 
         },
		 error:function(data) {
        alert(data); 
         }
    });
</script>
</body>
</html>