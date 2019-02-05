<?php

class PageController extends Controller {
     
     public $enableCsrf = false;
     public $enableDebug = false;

     public function createAction($action) {
          return new CInlineAction($this, 'index');
     }
     
     public function actionIndex($r) {
          $pageRaw = Helper::explodeLast("/", $r);
          $pageMode = "render";
          $pageAlias = $pageRaw;
          $isRoot = true;
          if (strpos($pageRaw, ":") !== false) {
               $pageModeRaw = Helper::explodeFirst(":", $pageRaw);
               $pageAlias = Helper::explodeLast(":", $pageRaw);
               
               $isRoot = Helper::explodeFirst("|", $pageModeRaw) == "r";
               $pageMode = Helper::explodeLast("|", $pageModeRaw);
          }
          
          $pageName = Helper::explodeLast(".", $pageAlias);
          $pageClass = $pageName . "Page";
          $pageFile = Page::resolve($pageAlias);
          $page = false;
          if (is_file($pageFile)) {
               if (!class_exists($pageClass, false)) {
                    require($pageFile);
               }
               $page = Page::load($pageAlias, $isRoot, true);
          }
          
          if ($page !== false) {
               switch ($pageMode) {
                    case "render":
                         $content = $page->renderPage();
                    break;
                    case "css":
                         header("Content-type: text/css");
                         echo $page->renderCSS();
                    break;
                    case "post": 
                         $post = file_get_contents("php://input");
                         $path = $this->getConfPath($page, $pageName, $pageAlias, $pageFile); 
                         file_put_contents($path['path'], $post);
                         return;
                         break;
                    case "clear":
                         $path = $this->getConfPath($page, $pageName, $pageAlias, $pageFile);
                         unlink($path['path']);
                         return;
                         break;
                    case "conf":
                         $this->getConf($page, $pageName, $pageAlias, $pageFile);
                         return;
                         break;
               }
          } else 
               throw new CHttpException(404);
     }
     
     public function getConfPath($page, $pageName, $pageAlias, $pageFile) {
          $conf = $page->renderConf();
          $hash = md5($conf);
          $basePath = Yii::getPathOfAlias('root.assets.rpage');
          if (!is_dir($basePath)) {
               mkdir($basePath, 0777, true);
          }
          $prefix = ".conf-" . ($page->isRoot ? "root-" : "") ;
          $jspath = $basePath . DIRECTORY_SEPARATOR . $pageAlias . $prefix . $hash . ".js";
          $baseUrl = Yii::app()->baseUrl . '/assets/rpage/';
          $jsurl = $baseUrl . $pageAlias . $prefix . $hash . ".js";
          return [
               'basePath' => $basePath,
               'path' => $jspath,
               'url' => $jsurl,
               'conf' => $conf
          ];
     }
     
     public function getConf($page, $pageName, $pageAlias, $pageFile) {
          $path = $this->getConfPath($page, $pageName, $pageAlias, $pageFile);
          $jspath = $path['path'];
          $content = $path['conf'];
          if (is_file($jspath)) {
               header("Location:" . $path['url']);
          }  else {
               ## remove old js file
               $prefix = $page->isRoot ? "conf-root-" : "conf-";
               $glob = glob($path['basePath'] . DIRECTORY_SEPARATOR . $pageAlias . "." . $prefix ."*.js");
               if (count($glob) > 0) {
                    foreach ($glob as $f) {
                         unlink($f);
                    }
               }
               
               ## create js file
               file_put_contents($jspath, $content);
               echo $content;
          }
     }
     
}