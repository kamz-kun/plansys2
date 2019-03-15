<head>
     <?php Asset::registerCSS('application.themes.default.views.css.bootstrap_min'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.fonts'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.ui'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.default'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.component'); ?>
     <?php
        if(file_exists(Setting::getRootPath() . '/app/static/custom.css')){
          Asset::registerCSS('app.static.custom');
        }
     ?>

     <?php
          // include(Yii::getPathOfAlias('application.themes.default.views') . '/vendor/autoload.php');
          // $dir = Yii::getPathOfAlias('application.themes.default.views.css');
          // $stylus = new NodejsPhpFallback\Stylus($dir . "/style.styl");
          // $stylus->write($dir . "/style.css");
     ?>
     <?php Asset::registerCSS('application.themes.default.views.css.style', time()); ?>
     
     <link rel="stylesheet" href="<?= Yii::app()->controller->staticUrl('/css/font-awesome.min.css'); ?>" type="text/css" />
     <title><?php echo CHtml::encode(Yii::app()->controller->pageTitle); ?></title>
     <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
     <?php ThemeManager::registerCoreScript(['/js/index.ctrl.js']); ?> 
     
     <?php Asset::registerJS('application.themes.default.views.js.modernizr'); ?>
     <?php Asset::registerJS('application.themes.default.views.js.dlmenu'); ?>
     <script>
          paceOptions = {
            ajax: false, // disabled
            document: true, // disabled
            eventLag: true // disabled
          };
     </script>
     <?php Asset::registerJS('application.themes.default.views.js.pace'); ?>
     
     <?php Asset::registerJS('application.themes.default.views.js.mainctrl'); ?>
     <?php //Asset::registerJS('application.themes.default.views.js.headerctrl'); ?>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>