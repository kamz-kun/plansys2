<head>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.bootstrap_min'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.fonts'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.ui'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.login'); ?>
     <?php Asset::registerCSS('application.themes.sidebar.views.css.component'); ?>

     <?php Asset::registerCSS('application.themes.sidebar.views.css.style', time()); ?>
     
     <link rel="stylesheet" href="<?= Yii::app()->controller->staticUrl('/css/font-awesome.min.css'); ?>" type="text/css" />
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
     <?php //Asset::registerJS('application.themes.sidebar.views.js.headerctrl'); ?>
</head>