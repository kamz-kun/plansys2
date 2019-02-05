<style type="text/css">
body {font-family:"Verdana";font-weight:normal;color:black;background-color:white;}
h1 { font-family:"Verdana";font-weight:normal;font-size:18pt;color:red }
h2 { font-family:"Verdana";font-weight:normal;font-size:14pt;color:maroon }
h3 {font-family:"Verdana";font-weight:bold;font-size:11pt}
p {font-family:"Verdana";font-weight:normal;color:black;font-size:9pt;margin-top: -5px}
.version {color: gray;font-size:8pt;border-top:1px solid #aaaaaa;}
.error{
	margin-top:60px;
}
</style>

<div class="error">
	<h1>Error <?php echo $code; ?></h1>
	<h2><?php echo $message; ?></h2>
	<p>
			
	Kesalahan di atas terjadi saat server Web memproses permintaan Anda.
	</p>
	<p>
	Jika Anda merasa ini adalah kesalahan server, silahkan hubungi <?php echo $data['admin']; ?>.
	</p>
	<p>
	Terima kasih.
	</p>
	<div class="version">
		<?php echo date('Y-m-d H:i:s'); ?>
	</div>
</div>
