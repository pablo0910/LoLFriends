<!DOCTYPE html>
<!--

	Done by Pablo Jimenez - pablo0910@outlook.es
	Base HTML5 page - http://templated.co/transit

-->
<?php

	include_once './connectToDB.php';

	$isUserConnected = false;

	session_start();
	if(!isset($_SESSION['lolfriendssession'])) {

		//We check if a cookie exists
		if(isset($_COOKIE['lolfriendscookie'])) {

			$cookieVal = $_COOKIE['lolfriendscookie'];
		    $sql = "SELECT * FROM user WHERE cookie = '$cookieVal'";

		    if ($result=mysqli_query($conn,$sql)) {

				// Fetch one and one row
				if ($row=mysqli_fetch_row($result)) {


					$isUserConnected = true;
		            $_SESSION['lolfriendssession'] = true;
		            $_SESSION['lolfriendsname'] = $row[1];
		            $_SESSION['lolfriendssummname'] = $row[4];

				}
				// Free result set
				mysqli_free_result($result);
			}


  		}

	} else {

		$isUserConnected = true;

	}

?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>LoL Friends</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<script src="js/jquery.vanillabox-0.1.6.min.js"></script>
		<link rel="stylesheet" href="css/vanillabox.css" />
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
	</head>
	<body class="landing">

		<!-- Header -->
			<header id="header">
				<h1><a href="index.html">LoL Friends</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="./">Home</a></li>
						<?php if ($isUserConnected) { ?>
						<li><a id ="checkfriends-href" href="#">Check Friends</a></li>
						<?php } ?>
						<li><a href="elements.html">Elements</a></li>
						<?php if (!$isUserConnected) { ?>
						<li><a id="signup-href" href="#" class="button special">Sign Up</a></li>
						<?php } ?>
					</ul>
				</nav>
			</header>

		<!-- Banner -->
			<section id="banner">
				<h2>LoL Friends</h2>
				<p>Check what summoners are online.</p>
				<ul class="actions">
						<?php if ($isUserConnected) { ?>
						<li><a id ="checkfriends-big-href" class="button big" href="#">Check Friends</a></li>
						<?php } else { ?>
						<li><a id="login-href" href="login.php" class="button big">Log In</a></li>
						<?php } ?>
				</ul>
			</section>

		<!-- One 
			<section id="one" class="wrapper style1 special">
				<div class="container">
					<header class="major">
						<h2>Consectetur adipisicing elit</h2>
						<p>Lorem ipsum dolor sit amet, delectus consequatur, similique quia!</p>
					</header>
					<div class="row 150%">
						<div class="4u 12u$(medium)">
							<section class="box">
								<i class="icon big rounded color1 fa-cloud"></i>
								<h3>Lorem ipsum dolor</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim quam consectetur quibusdam magni minus aut modi aliquid.</p>
							</section>
						</div>
						<div class="4u 12u$(medium)">
							<section class="box">
								<i class="icon big rounded color9 fa-desktop"></i>
								<h3>Consectetur adipisicing</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium ullam consequatur repellat debitis maxime.</p>
							</section>
						</div>
						<div class="4u$ 12u$(medium)">
							<section class="box">
								<i class="icon big rounded color6 fa-rocket"></i>
								<h3>Adipisicing elit totam</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque eaque eveniet, nesciunt molestias. Ipsam, voluptate vero.</p>
							</section>
						</div>
					</div>
				</div>
			</section>

		<!-- Two 
			<section id="two" class="wrapper style2 special">
				<div class="container">
					<header class="major">
						<h2>Lorem ipsum dolor sit</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio, autem.</p>
					</header>
					<section class="profiles">
						<div class="row">
							<section class="3u 6u(medium) 12u$(xsmall) profile">
								<img src="images/profile_placeholder.gif" alt="" />
								<h4>Lorem ipsum</h4>
								<p>Lorem ipsum dolor</p>
							</section>
							<section class="3u 6u$(medium) 12u$(xsmall) profile">
								<img src="images/profile_placeholder.gif" alt="" />
								<h4>Voluptatem dolores</h4>
								<p>Ullam nihil repudi</p>
							</section>
							<section class="3u 6u(medium) 12u$(xsmall) profile">
								<img src="images/profile_placeholder.gif" alt="" />
								<h4>Doloremque quo</h4>
								<p>Harum corrupti quia</p>
							</section>
							<section class="3u$ 6u$(medium) 12u$(xsmall) profile">
								<img src="images/profile_placeholder.gif" alt="" />
								<h4>Voluptatem dicta</h4>
								<p>Et natus sapiente</p>
							</section>
						</div>
					</section>
					<footer>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quibusdam dolore illum, temporibus veritatis eligendi, aliquam, dolor enim itaque veniam aut eaque sequi qui quia vitae pariatur repudiandae ab dignissimos ex!</p>
						<ul class="actions">
							<li>
								<a href="#" class="button big">Lorem ipsum dolor sit</a>
							</li>
						</ul>
					</footer>
				</div>
			</section>

		<!-- Three
			<section id="three" class="wrapper style3 special">
				<div class="container">
					<header class="major">
						<h2>Contact Us</h2>
						<p>Report any bug you find or send us any suggestion!</p>
					</header>
				</div>
				<div class="container 50%">
					<form action="#" method="post">
						<div class="row uniform">
							<div class="6u 12u$(small)">
								<input name="name" id="name" value="" placeholder="Name" type="text">
							</div>
							<div class="6u$ 12u$(small)">
								<input name="email" id="email" value="" placeholder="Email" type="email">
							</div>
							<div class="12u$">
								<textarea name="message" id="message" placeholder="Message" rows="6"></textarea>
							</div>
							<div class="12u$">
								<ul class="actions">
									<li><input value="Send Message" class="special big" type="submit"></li>
								</ul>
							</div>
						</div>
					</form>
				</div>
			</section> -->

		<!-- Footer
			<footer id="footer">
				<div class="container">
					<section class="links">
						<div class="row">
							<section class="3u 6u(medium) 12u$(small)">
								<h3>Lorem ipsum dolor</h3>
								<ul class="unstyled">
									<li><a href="#">Lorem ipsum dolor sit</a></li>
									<li><a href="#">Nesciunt itaque, alias possimus</a></li>
									<li><a href="#">Optio rerum beatae autem</a></li>
									<li><a href="#">Nostrum nemo dolorum facilis</a></li>
									<li><a href="#">Quo fugit dolor totam</a></li>
								</ul>
							</section>
							<section class="3u 6u$(medium) 12u$(small)">
								<h3>Culpa quia, nesciunt</h3>
								<ul class="unstyled">
									<li><a href="#">Lorem ipsum dolor sit</a></li>
									<li><a href="#">Reiciendis dicta laboriosam enim</a></li>
									<li><a href="#">Corporis, non aut rerum</a></li>
									<li><a href="#">Laboriosam nulla voluptas, harum</a></li>
									<li><a href="#">Facere eligendi, inventore dolor</a></li>
								</ul>
							</section>
							<section class="3u 6u(medium) 12u$(small)">
								<h3>Neque, dolore, facere</h3>
								<ul class="unstyled">
									<li><a href="#">Lorem ipsum dolor sit</a></li>
									<li><a href="#">Distinctio, inventore quidem nesciunt</a></li>
									<li><a href="#">Explicabo inventore itaque autem</a></li>
									<li><a href="#">Aperiam harum, sint quibusdam</a></li>
									<li><a href="#">Labore excepturi assumenda</a></li>
								</ul>
							</section>
							<section class="3u$ 6u$(medium) 12u$(small)">
								<h3>Illum, tempori, saepe</h3>
								<ul class="unstyled">
									<li><a href="#">Lorem ipsum dolor sit</a></li>
									<li><a href="#">Recusandae, culpa necessita nam</a></li>
									<li><a href="#">Cupiditate, debitis adipisci blandi</a></li>
									<li><a href="#">Tempore nam, enim quia</a></li>
									<li><a href="#">Explicabo molestiae dolor labore</a></li>
								</ul>
							</section>
						</div>
					</section>
					<div class="row">
						<div class="8u 12u$(medium)">
							<ul class="copyright">
								<li>&copy; Untitled. All rights reserved.</li>
								<li>Design: <a href="http://templated.co">TEMPLATED</a></li>
								<li>Images: <a href="http://unsplash.com">Unsplash</a></li>
							</ul>
						</div>
						<div class="4u$ 12u$(medium)">
							<ul class="icons">
								<li>
									<a class="icon rounded fa-facebook"><span class="label">Facebook</span></a>
								</li>
								<li>
									<a class="icon rounded fa-twitter"><span class="label">Twitter</span></a>
								</li>
								<li>
									<a class="icon rounded fa-google-plus"><span class="label">Google+</span></a>
								</li>
								<li>
									<a class="icon rounded fa-linkedin"><span class="label">LinkedIn</span></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer> -->

			<footer id="footer">
				<div class="container">
					<div class="row">
						<div class="8u 12u$(medium)">
							<ul class="copyright">
								<li>&copy; pablo0910. All rights reserved.</li>
							</ul>
						</div>
						
					</div>
				</div>
			</footer>


		<script>

			$(document).ready(function(){

				$("#checkfriends-href").click(function(event) {
				   
					window.location.href = "./check-friends.php";

				});

				$("#checkfriends-big-href").click(function(event) {
				   
					window.location.href = "./check-friends.php";

				});
				$('#signup-href').vanillabox({
					type: 'iframe',
					preferredWidth: 480,
					preferredHeight: 565
				});

				$('#login-href').vanillabox({
					type: 'iframe',
					preferredWidth: 480,
					preferredHeight: 420
				});
			});

		</script>

	</body>
</html>