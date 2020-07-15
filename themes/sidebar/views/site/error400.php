<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Bad Request</title>
<style type="text/css">
/*<![CDATA[*/
body {font-family:"Verdana";font-weight:normal;color:black;background-color:white;}
h1 { font-family:"Verdana";font-weight:normal;font-size:18pt;color:red }
h2 { font-family:"Verdana";font-weight:normal;font-size:14pt;color:maroon }
h3 {font-family:"Verdana";font-weight:bold;font-size:11pt}
p {font-family:"Verdana";font-weight:normal;color:black;font-size:9pt;margin-top: -5px}
.version {color: gray;font-size:8pt;border-top:1px solid #aaaaaa;}
/*]]>*/
	.top-bar{
		display:none;
	}
	#sidebar {
		display:none;
	}
	#content{
		margin-left:0px !important;
	}
</style>
</head>
<body>
	<div class = "col-md-12">
		<div class = "col-md-4"></div>
		<div class = "col-md-4">
			<img ng-src="plansys/themes/sidebar/views/images/oops.png" style="max-width: 100%;max-height: 100%;">
		</div>
		<div class = "col-md-4"></div>
	</div>
	<h1 style="font-size:2em"><span style="color:#999; border-right:3px solid #999; padding-right:20px;">400</span><span style="padding-left: 20px; color:#f87575">Bad Request</span></h1>
	<h2><?php echo nl2br(CHtml::encode($data['message'])); ?></h2>
	<p style="text-align: center;">
		The request could not be understood by the server due to malformed syntax. Please do not repeat the request without modifications.
	</p>
	<p style="text-align: center;">
		If you think this is a server error, please contact Administrator.
	</p>
	<div style="text-align:center; margin-top:80px;">
		<a ng-url="site/home" style="background-color: #333; padding: 20px; color: white; border-radius: 10px;">Go to home page</a>
	</div>
	<div class = "col-md-12" style="position:fixed; bottom:10px; text-align: center;">
		<small><i>Plansys 2.0</i></small>
	</div>
	<br>
	<br>
</body>
</html>