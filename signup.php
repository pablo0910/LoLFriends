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
					var email = $("#email").val();
					var password = $("#password").val();
					var cpassword = $("#cpassword").val();

					if (name == '' || email == '' || password == '' || cpassword == '') {

						alert("Please fill all fields...!!!!!!");

					} else if ((password.length) < 6) {

						alert("Password should atleast 6 character in length...!!!!!!");

					} else if (!(password).match(cpassword)) {

						alert("Your passwords don't match. Try again?");

					} else {

					$.post("sendDataSignUp.php", {

						nick: name,
						email: email,
						password: password

					}, function(data) {

						$("#input-container").hide();
						$("#title-container").html("<h2>You have succesfully registered</h2>");

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
						<h2>LoL Friends - Sign Up</h2>
					</div>

					<div id="input-container">

						<label>Nick :</label>
						<input type="text" name="dname" id="name">

						<label>Email :</label>
						<input type="text" name="demail" id="email">

						<label>Password :</label>
						<input type="password" name="password" id="password">

						<label>Confirm Password :</label>
						<input type="password" name="cpassword" id="cpassword">

						<input type="button" name="register" id="register" value="Register">

					</div>

				</form>

			</div>

		</div>

	</body>
</html>