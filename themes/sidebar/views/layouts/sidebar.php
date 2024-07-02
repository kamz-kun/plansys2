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
	<div class="sidebar-header row mx-0">
          <div class="col-md-3 pr-0 text-right">
               <img src="app/static/blank-user.png" alt="" class="img-fluid user-icon">
          </div>
          <div class="col-md-9">
               <h5 style="font-size:15px" class="mt-1 mb-1 text-capitalize"><b><?php echo Yii::app()->user->isGuest ? 'Username' : Yii::app()->user->info['username'];?></b></h5>
               <h6 style="font-size:14px" class="mt-1"><?php echo  Yii::app()->user->isGuest ? 'email@test.com' : Yii::app()->user->info['email'];?></h6>
               <span class="badge badge-secondary badge-jabatan">
                    <b><?php echo Yii::app()->user->isGuest ? 'Role' : Yii::app()->user->info['role'];?></b>
               </span>
          </div>
          <div class="col-md-12">
               <a class="btn btn-logout w-100" href="index.php?r=sys/changePass" title="Change Password"><i class="fa fa-key"></i> Change Password</a>
          </div>
	</div>
	<ul class="list-unstyled components">
		<?php
            $html = '';
          
            if(is_array(Yii::app()->user->info) && (sizeof(Yii::app()->user->info) > 1)){ //if menu items available open bracket
                foreach($menu as $k => $v){
                   if($k > 1){
                        if($v['label'] == '---'){
                        } else {
							 $html .= '<li>';
                             if(isset($v['url'])){
                                 $active = '';
                                 $url = is_array($v['url']) ? $v['url'][0] : $v['url'];
                                 if( $_GET['r'] == $url){
                                   $active = 'active';
                                 }
                                 $url = Yii::app()->createUrl($url);
								 if(isset($v['items'])){
									$html .= '<a href="#' . $k.'_'.str_replace(" ", "_",$v['label']) .'"  data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" ><span class="icon-side"><i class="fa '.$v['icon'].'"></i> '.'</span>'.$v['label'];	 
								 } else {
                                    $html .= '<a class="'.$active.'" href="' . $url .'"><span class="icon-side"><i class="fa '.$v['icon'].'" ></i> </span>'.$v['label'];	
								 }          
                             } else {
								 if(isset($v['items'])){
									$html .= '<a href="#' . $k.'_'.str_replace(" ", "_",$v['label']) .'"  data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" ><span class="icon-side"><i class="fa '.$v['icon'].'"></i> '.'</span>'.$v['label'];
								 } else {
                                    $html .= '<a href="#"><span class="icon-side"><i class="fa '.$v['icon'].'"></i> </span>'.$v['label'];	
								 }
                             }
                             if(isset($v['items'])){
                                  $html .=  ' <i class="fa fa-chevron-down sub-icon"></i>';
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
     <div class="text-center v-logout">
          <a class="btn btn-outline-danger w-100" href="index.php?r=site/logout" title="Logout"><i class="fa fa-sign-out"></i> Logout</a>
     </div>
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