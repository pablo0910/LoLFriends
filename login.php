<!DOCTYPE html>
<!--

	Done by Pablo Jimenez - pablo0910@outlook.es
	Base HTML5 page - http://templated.co/transit

-->
<html>
	<head>
	<title>LoL Friends - Sign Up</title>

		<meta name="robots" content="noindex, nofollow">
		<!-- Include CSS File Here -->
		<link rel="stylesheet" href="css/styleSIGNUP.css"/>
		<!-- Include JS File Here -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>
			$(document).ready(function() {

				$("#register").click(function() {

					var name = $("#name").val();
					var password = $("#password").val();

					if (name == '' || password == '') {

						alert("Please fill all fields...!!!!!!");

					} else {

					$.post("sendDataLogIn.php", {

						nick: name,
						password: password

					}, function(data) {

						if (data == "true") {

							window.parent.location.reload();

						}

					});
					}
				});
			});
		</script>
	</head>
	<body>

		<div class="container">

			<div class="main">

				<form id="form" class="form" method="post" action="#">

					<div id="title-container">
						<h2>LoL Friends - Log In</h2>
					</div>

					<div id="input-container">

						<label>Nick: </label>
						<input type="text" name="dname" id="name">

						<label>Password: </label>
						<input type="password" name="password" id="password">

						<input type="button" name="register" id="register" value="Log In">

					</div>

				</form>

			</div>

		</div>

	</body>
</html>