<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>phpInfo</title>
  <base href="/">
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<?php 
		phpinfo();
			 $version = phpversion();

		if( extension_loaded('sqlanywhere') ){
		var_dump('yes');
	
		}
		else {
		echo 'no';
		}
	?>
</body>
</html>
