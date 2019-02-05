<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="<?= $this->staticUrl('/css/bootstrap.min.css'); ?>"  />
        <link rel="stylesheet" type="text/css" href="<?= $this->staticUrl('/css/non-responsive.css'); ?>"  />
        <link rel="stylesheet" type="text/css" href="<?= $this->staticUrl('/css/font-awesome.min.css'); ?>"  />
        <link rel="stylesheet" type="text/css" href="<?= $this->staticUrl('/css/main.css'); ?>"  />
        <link rel="stylesheet" type="text/css" href="<?= $this->moduleUrl . "install.css"; ?>"  />
        <title>Plansys Installer</title>
    </head>
    <body>

    <body ng-controller="MainController">
        
        <div id="content" class="no-widget">
            <?php echo $content; ?>
        </div>
    </body>
</html>


