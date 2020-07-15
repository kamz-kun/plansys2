<?php
     try {
          $menu = Yii::app()->controller->mainMenu;
     } catch (CdbException $e) {
          $menu = [];
     }
     if(!!$menu){ //if menu items available open bracket
?>

<!-- Sidebar  -->
<nav id="sidebar">
	<div class="sidebar-header">
				<h4><b><?php echo Yii::app()->user->info['username'];?></b></h4>
				<h6><?php echo @Yii::app()->user->info['email'];?></h6>
				<h6><b><?php echo "As ".Yii::app()->user->info['role'];?></b></h6>
	
	</div>
	<ul class="list-unstyled components">
		<?php
            $html = '';
            if((sizeof(Yii::app()->user->info) > 1)){ //if menu items available open bracket
                foreach($menu as $k => $v){
                   if($k > 1){
                        if($v['label'] == '---'){
                        } else {
							 $html .= '<li>';
                             if(isset($v['url'])){
                                 $url = is_array($v['url']) ? $v['url'][0] : $v['url'];
                                 $url = Yii::app()->createUrl($url);
								 
								 if(isset($v['items'])){
									$html .= '<a href="#' . $k.'_'.str_replace(" ", "_",$v['label']) .'"  data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" ><i class="fa '.$v['icon'].'"  style="margin-left:10px; margin-right:10px"></i> '.$v['label'];	 
								 } else {
                                    $html .= '<a href="' . $url .'"><i class="fa '.$v['icon'].'" style="margin-left:10px; margin-right:10px"></i> '.$v['label'];	
								 }          
                             } else {
								 if(isset($v['items'])){
									$html .= '<a href="#' . $k.'_'.str_replace(" ", "_",$v['label']) .'"  data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" ><i class="fa '.$v['icon'].'"  style="margin-left:10px; margin-right:10px"></i> '.$v['label'];	 
								 } else {
                                    $html .= '<a href="#"><i class="fa '.$v['icon'].'" style="margin-left:10px; margin-right:10px"></i> '.$v['label'];	
								 }
                             }
                             if(isset($v['items'])){
                                  $html .=  ' <i class="fa fa-chevron-right" style="float:right;"></i>';
                             }
                             $html .= '</a>';
                             if(isset($v['items'])){
                                  $html .=  extractChild1($v['items'], $k.'_'.str_replace(" ", "_",$v['label']));
                             }
                             $html .= '</li>';
                        }
                    }
                } 
            }            
            echo $html;
        // }
         ?>
		
	</ul>
</nav>

<?php }?>


<?php
     function extractChild1($item, $label){
          $html = '<ul class="collapse list-unstyled" id="'.$label.'">';
          foreach($item as $k => $v){
               if($v['label'] == '---'){
                $html .= '<li class="dl-divider"></li>';
               } 
               else {
                    $html .= '<li >';
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