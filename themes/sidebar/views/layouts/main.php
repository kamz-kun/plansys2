<!DOCTYPE html>
<html lang="en" ng-app="main">
	<?php $this->includeFile("_head.php"); ?>

	<body ng-controller="MainController">
		<div class="wrapper">
			<?php
				$this->includeFile("sidebar.php");
			?>
			<!-- Page Content  -->
			<main class="main" ng-cloak>
				<div id="content">
					<?php $this->includeFile("topbar.php"); ?>
					<?php echo $content; ?>
				</div>
			</main>		
		</div>	
	</body>
</html>