<head>
     <?php Asset::registerCSS('application.themes.default.views.css.bootstrap_min'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.fonts'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.ui'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.default'); ?>
     <?php Asset::registerCSS('application.themes.default.views.css.component'); ?>
     <?php Asset::registerCSS('application.static.calendar.calendar'); ?>
     <?php
        if(file_exists(Setting::getRootPath() . '/app/static/custom.css')){
          Asset::registerCSS('app.static.custom');
        }
     ?>

     <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
     <?php Asset::registerJS('application.static.calendar.calendar-tpls'); ?>     
     <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
     <script>
          $(function() {
          AOS.init();
          });
     </script>
</head>