<?php  
require_once 'autoload.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>404 | Page Not Found</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<style>
		body
		{
			padding: 0px;
			margin:0px;
		}
		#message404
		{
			top: 50px;
			padding: 16px;
			position: absolute;
		} 
		#container404 
		{
			margin-top: 15px;
			width: 90%;
			margin: 0 auto;
			position: relative;
			min-height: 450px;
			
		}

		#copyright404 
		{	
			margin-top: 40%;
			bottom: 0px;
			width: 100%
		}
	</style>
</head>
<body>
	<div id="container404">
		<pre>
		<div id="message404">
		<h1 style="font-family: Roboto">404 File or Directory Not Found on this server..</h1>
		<p style="font-family: Roboto">Lost? You are at wrong location. URL is not exist on this server..</p>
		<p style="font-family: Roboto"><a href="<?=BASE_URL?>" style='text-decoration: none'><< Go Back to Website</a></p>
		</pre>

		<div id="copyright404">
			<a href="<?=BASE_URL;?>" target="_blank"><?=$footer_copyright?></a>
		</div>

		</div>

	</div>
</body>
</html>