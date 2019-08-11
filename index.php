<?php
ob_start(); 
require_once 'engine/config.php';
require_once 'engine/init.php';

if (!($user -> LoggedIn()))
{
	header('Location: login.php');
	die();
}
if ($user -> IsBanned($odb))
{
	header('Location: logout.php');
	die();
}

$SQLGetHomePage = $odb -> prepare("SELECT homepage FROM `settings` WHERE `id` = 1 LIMIT 1");
$SQLGetHomePage -> execute();
$getInfo = $SQLGetHomePage -> fetch(PDO::FETCH_ASSOC);
$hptext = $getInfo['homepage'];

$SQLGetUserI = $odb -> prepare("SELECT `package`,`maxboot` FROM `users` WHERE `id` = :id LIMIT 1");
$SQLGetUserI -> execute(array(':id' => $_SESSION['ID']));
$packageInfo = $SQLGetUserI -> fetchColumn(0);
$userpackage = $packageInfo['package'];
if($userpackage > 0){
	$SQLGetTime = $odb -> prepare("SELECT `packages`.`mbt` FROM `packages` LEFT JOIN `users` ON `users`.`package` = `packages`.`ID` WHERE `users`.`ID` = :id");
	$SQLGetTime -> execute(array(':id' => $_SESSION['ID']));
	$maxboott = $SQLGetTime -> fetchColumn(0);
	$SQL = $odb -> prepare("UPDATE `users` SET `maxboot` = :maxboot WHERE `ID` = :id");
	$SQL -> execute(array(':maxboot' => $maxboott, ':id' => $_SESSION['ID']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="" />
	<meta name="author" content="Woopza.com" />

	<title><?php echo $web_title;?>Dashboard</title>

	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Arimo:400,700,400italic">
	<link rel="stylesheet" href="assets/css/fonts/linecons/css/linecons.css">
	<link rel="stylesheet" href="assets/css/fonts/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/xenon-core.css">
	<link rel="stylesheet" href="assets/css/xenon-forms.css">
	<link rel="stylesheet" href="assets/css/xenon-components.css">
	<link rel="stylesheet" href="assets/css/xenon-skins.css">
	<link rel="stylesheet" href="assets/css/custom.css">

	<script src="assets/js/jquery-1.11.1.min.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<div class="page-loading-overlay">
	<div class="loader-2"></div>
</div>
<body class="page-body">
	
	<div class="page-container">
			
		<?php include 'templates/sidebar.php'; ?>
	
		<div class="main-content">
					
			<?php include 'templates/navbar.php'; ?>
			
			<div class="row">
			
				<div class="col-sm-3">
					
					<div class="xe-widget xe-counter" data-count=".num" data-from="0" data-to="<?php echo $stats -> totalBoots($odb); ?>" data-duration="3" data-easing="false">
						<div class="xe-icon">
							<i class="linecons-fire"></i>
						</div>
						<div class="xe-label">
							<strong class="num"><?php echo $stats -> totalBoots($odb); ?></strong>
							<span>Total Attacks</span>
						</div>
					</div>
					
				</div>
				
				<div class="col-sm-3">
					
					<div class="xe-widget xe-counter xe-counter-blue" data-count=".num" data-from="0" data-to="<?php echo $stats -> totalUsers($odb); ?>" data-duration="3" data-easing="true">
						<div class="xe-icon">
							<i class="fa-users"></i>
						</div>
						<div class="xe-label">
							<strong class="num"><?php echo $stats -> totalUsers($odb); ?></strong>
							<span>Registered Users</span>
						</div>
					</div>
				
				</div>
				
				<div class="col-sm-3">
					
					<div class="xe-widget xe-counter xe-counter-info" data-count=".num" data-from="0" data-to="<?php echo $stats -> totalServers($odb) + $stats -> totalApis($odb); ?>" data-duration="3" data-easing="true">
						<div class="xe-icon">
							<i class="fa-laptop"></i>
						</div>
						<div class="xe-label">
							<strong class="num"><?php echo $stats -> totalServers($odb) + $stats -> totalApis($odb); ?></strong>
							<span>Attack Servers</span>
						</div>
					</div>
				
				</div>
				
				<div class="col-sm-3">
					
					<div class="xe-widget xe-counter xe-counter-red" data-count=".num" data-from="0" data-to="<?php echo $stats -> runningBoots($odb); ?>" data-duration="3" data-easing="true">
						<div class="xe-icon">
							<i class="fa-flash"></i>
						</div>
						<div class="xe-label">
							<strong class="num"><?php echo $stats -> runningBoots($odb); ?></strong>
							<span>Running Attacks</span>
						</div>
					</div>
				
				</div>
				
				<div class="clearfix"></div>
			
				<div class="col-sm-8">
					<div class="panel panel-color panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">Latest News & Updates</h3>
						</div>
						
						<div class="panel-body">
							<p><?php echo $hptext; ?></p>	
						</div>
					</div>
				</div>
				
				<div class="col-sm-4">
					<div class="panel panel-color panel-success">
						<div class="panel-heading">
							<h3 class="panel-title">Account Information</h3>
						</div>
						
						<div class="panel-body">
							<p><strong> E-Mail:</strong> <?php echo $user -> getEmail($odb, $_SESSION['username']); ?></p>
							<p><strong> Package:</strong> <?php echo $user -> getPackage($odb, $_SESSION['username']); ?></p>
							<p><strong> Max Boot Time:</strong> <?php echo $user -> getMBT($odb, $_SESSION['username']); ?></p>
							<p><strong> Expiration:</strong> <?php echo $user -> getExpiration($odb, $_SESSION['username']); ?></p>
						</div>
					</div>
				</div>
				
				<div class="clearfix"></div>
				
			</div>
			
			<?php include 'templates/footer.php'; ?>
		</div>
		
	</div>
	

	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/css/fonts/meteocons/css/meteocons.css">

	<!-- Bottom Scripts -->
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/TweenMax.min.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/xenon-api.js"></script>
	<script src="assets/js/xenon-toggles.js"></script>


	<!-- Imported scripts on this page -->
	<script src="assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="assets/js/jvectormap/regions/jquery-jvectormap-world-mill-en.js"></script>
	<script src="assets/js/xenon-widgets.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/xenon-custom.js"></script>

</body>
</html>
