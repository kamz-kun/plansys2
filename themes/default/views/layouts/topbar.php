<?php
     try {
          $menu = Yii::app()->controller->mainMenu;
     } catch (CdbException $e) {
          $menu = [];
     }
     if(true){ //if menu items available open bracket
     
?>
<div class="top-bar" onload="getTime()">
     <div id="desktop-menu">
          <ul class="ds-nav site-nav" style="float:left;">
         <?php
            $html = '';
            if(is_array(Yii::app()->user->info)){
                if((sizeof(Yii::app()->user->info) > 1)){ //if menu items available open bracket
                    foreach($menu as $k => $v){
                        
                       if($k > 1){
                            if($v['label'] == '---'){
                            } else {
                                if(isset($v['items'])){
                                    $html .= '<li class="flyout">';
                                } else {
                                    $html .= '<li>';
                                }
                                 
                                 if(isset($v['url'])){
                                     $url = is_array($v['url']) ? $v['url'][0] : $v['url'];
                                    $url = Yii::app()->createUrl($url);
                                    $html .= '<a href="' . $url .'"><i class="fa '.$v['icon'].'"></i> '.$v['label'];	
                                 } else {
                                      $html .= '<a href="#"><i class="fa '.$v['icon'].'"></i> '.$v['label'];	
                                 }
                                 if(isset($v['items'])){
                                      $html .=  ' <i class="fa fa-chevron-down"></i>';
                                 }
                                 $html .= '</a>';
                                 if(isset($v['items'])){
                                      $html .=  extractChild($v['items']);
                                 }
                                 $html .= '</li>';
                            }
                        }
                    } 
                }            
            }
            echo $html;
        // }
         ?>
         </ul>
	</div>
	<div id="mobile-menu">
     	<div id="dl-menu" class="dl-menuwrapper">
     		<button class="dl-trigger c-hamburger c-hamburger--htx">
     			<span>toggle menu</span>
     		</button>
     		<ul class="dl-menu">
     		     <?php     		          
     		     if(is_array(Yii::app()->user->info)){
                       if((sizeof(Yii::app()->user->info) > 1)){ //if menu items available open bracket
                            $this->includeFile('menuheader.php', [
                                'menu' => $menu
                            ]);                                
                            echo loopMenuMobile($menu);
                            $this->includeFile('menufooter.php', [
                                'menu' => $menu
                           ]);    
                       } else {
                            echo  '<li><a href="index.php?r=site/login">Login</a></li>';
                       }
     		     }
                    ?>            
     		</ul>
     	</div><!-- /dl-menuwrapper -->
	</div>
	<div class="dl-menuleft" style="margin-left: 10px;">
	     <ul class="ds-nav">
	          <li  style="margin-top: 0px">
	               <!--<img src="#" width="35px" style="margin-top: 5px; margin-right:10px;">     -->
	          </li>
	     </ul>
	</div>
	<div class="dl-menuright" style="display: block;">
	    <?php
               $this->includeFile('topcontent.php');   
	     ?>
	</div>
	
</div><!-- /top-bar -->
<?php
	} //if menu items available close bracket
?>


<?php
     function getHeaderMenu(){
          
          return "
               <li id='menu-header'>
                    <h3>Test</h3>
               </li>
          
          ";
     }
     function loopMenuMobile($menu){
          $html = '';
          foreach($menu as $k => $v){
               if($k > 1){
                    if($v['label'] == '---'){
                         $html .= '<li class="dl-divider">
			                    </li>';
                    } else {
                         $html .= '<li>';
                         if(isset($v['url'])){
                             $url = is_array($v['url']) ? $v['url'][0] : $v['url'];
                            $url = Yii::app()->createUrl($url);
                            $html .= '<a href="' . $url .'">'.$v['label'].'</a>';	
                         } else {
                              $html .= '<a href="#">'.$v['label'].'</a>';	
                         }
                         if(isset($v['items'])){
                              $html .=  extractChildMobile($v['items']);
                         }
                         $html .= '</li>';
                    }
               }
          }
          return $html;
          
          
     }
     
     function extractChildMobile($item){
          $html = '<ul class="dl-submenu">';
          foreach($item as $k => $v){
               if($v['label'] == '---'){
                    $html .= '<li class="dl-divider">
	                         </li>';
               } 
               else {
                    
                    $html .= '<li>';
                    if(isset($v['url'])){
                        if(is_array($v['url'])){
                            $url = $v['url'][0];
                            $params = $v['url'];
                            unset($params[0]);
                            $url = Yii::app()->createUrl($url, $params);
                            $html .= '<a href="' . $url .'">'.$v['label'].'</a>';	    
                        } else {
                            $url = Yii::app()->createUrl($v['url']);
                            $html .= '<a href="' . $url .'">'.$v['label'].'</a>';	    
                        }
                    } else {
                         $html .= '<a href="#">'.$v['label'].'</a>';          
                    }
                    if(isset($v['items'])){
                         $html .=  extractChildMobile($v['items']);
                    }
                    $html .= '</li>';     
               }
               
          }
          $html .= '</ul>';
          return $html;
     }
     
     function extractChild($item){
          $html = '<ul class="flyout-content ds-nav stacked">';
          foreach($item as $k => $v){
               if($v['label'] == '---'){
                $html .= '<li class="dl-divider">
                </li>';
               } 
               else {
                    if(isset($v['items'])){
                        $html .= '<li class="flyout-alt">';
                    } else {
                        $html .= '<li>';
                    }
                    
                    if(isset($v['url'])){
                        if(is_array($v['url'])){
                            $url = $v['url'][0];
                            $params = $v['url'];
                            unset($params[0]);
                            $url = Yii::app()->createUrl($url, $params);
                            if(isset($v['icon'])) {
                                $html .= '<a href="' . $url .'"><i class="fa '.$v['icon'].'" style=" vertical-align: middle;"></i>&nbsp '.$v['label'].'</a>';
                            } else {
                                $html .= '<a href="' . $url .'">'.$v['label'].'</a>';
                            }
                        } else {
                            $url = Yii::app()->createUrl($v['url']);
                            if(isset($v['icon'])) {
                                $html .= '<a href="' . $url .'"><i class="fa '.$v['icon'].'" style=" vertical-align: middle;"></i>&nbsp '.$v['label'].'</a>';
                            } else {
                                $html .= '<a href="' . $url .'">'.$v['label'].'</a>';
                            }
                        }
                    } else {
                         $html .= '<a href="#">'.$v['label'].'</a>';          
                    }
                    if(isset($v['items'])){
                         $html .=  extractChild($v['items']);
                    }
                    $html .= '</li>';     
               }
               
          }
          $html .= '</ul>';
          return $html;
     }

?>