<head>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.bootstrap_min'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.fonts'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.ui'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.default'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.component'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.sidebar'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.style', time()); ?>
     <?php
        if(file_exists(Setting::getRootPath() . '/app/static/custom.css')){
          Asset::registerCSS('app.static.custom');
        }
     ?>
	

     <?php
          // include(Yii::getPathOfAlias('application.themes.sidebar.views') . '/vendor/autoload.php');
          // $dir = Yii::getPathOfAlias('application.themes.sidebar.views.css');
          // $stylus = new NodejsPhpFallback\Stylus($dir . "/style.styl");
          // $stylus->write($dir . "/style.css");
     ?>
     
     <link rel="stylesheet" href="<?= Yii::app()->controller->staticUrl('/css/font-awesome.min.css'); ?>" type="text/css" />
     <!-- <link href="app/static/interfont/stylesheet.css" rel="stylesheet">
     <link rel="preload" href="app/static/interfont/Inter-Black.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-ExtraBold.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-ExtraLight.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-Bold.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-Regular.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-Thin.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-Light.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-Medium.woff2" as="font" type="font/woff2" crossorigin>
     <link rel="preload" href="app/static/interfont/Inter-SemiBold.woff2" as="font" type="font/woff2" crossorigin> -->
     <link href="app/static/inter-woff/stylesheet.css" rel="stylesheet">
     <title><?php echo CHtml::encode(Yii::app()->controller->pageTitle); ?></title>
     <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
     <?php ThemeManager::registerCoreScript(['/js/index.ctrl.js']); ?> 
     
     <?php Asset::registerJS('application.themes.sidebar.views.js.modernizr'); ?>
     <?php Asset::registerJS('application.themes.sidebar.views.js.dlmenu'); ?>
     <script>
          paceOptions = {
            ajax: false, // disabled
            document: true, // disabled
            eventLag: true // disabled
          };
     </script>
     <?php Asset::registerJS('application.themes.sidebar.views.js.pace'); ?>
     
     <?php Asset::registerJS('application.themes.sidebar.views.js.mainctrl'); ?>
     <?php //Asset::registerJS('application.themes.default.views.js.headerctrl'); ?>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <?php Asset::registerJS('application.static.calendar.calendar-tpls'); ?>     
</head>