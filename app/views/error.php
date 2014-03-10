<html lang="en">
	<head>
		<title>Error</title>
		<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<style type="text/css">
		  .center {text-align: center; margin-left: auto; margin-right: auto; margin-bottom: auto; margin-top: auto;}
		</style>
	</head>
	<body>
	<div class="container">
  <div class="row">
    <div class="span12">
      <div class="hero-unit center">
          <h1>Oops!<br /><small><font face="Tahoma" color="red">Something went wrong!</font></small></h1>
          <br />
          <pre>
          	{{$exception}}
          </pre>
        </div>
	</body>
</html>