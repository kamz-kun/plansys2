<ul class="ds-me ds-nav">
    <?php
        if(is_array(Yii::app()->user->info) && (sizeof(Yii::app()->user->info) > 1)){ //if menu items available open bracket

    ?>
    <!-- <li class="flyout" style="padding: 10px; vertical-align: super;">
        <?php 
            echo '<a href="#">'.strtoupper(Yii::app()->user->info['username']).' <i class="fa fa-chevron-down"></i></a>';                                 
        ?>  
        <ul class="flyout-content ds-nav stacked username">			
			<div class = "col-md-12 text-center" style="margin-top:10px;">
				<h5 style="cursor: default;"><?php echo Yii::app()->user->info['email'];?></h5>
				<h5 style="cursor: default;"><?php echo Yii::app()->user->info['role'];?></h5>				
			</div>
			<?php 
				$rolenow = Yii::app()->user->info['role'];
				if(count(Yii::app()->user->info['roles'])>1){
			?>	
				<div class = "col-md-12"  style="background-color:#546e7a; padding-bottom:10px;">
					<div class = "col-md-12" style="margin-top:10px; margin-left: -5px;">
                        <h5 style="cursor: default;">Ubah ke Role : </h5>
                        <hr>
					</div>
					<?php
						foreach(Yii::app()->user->info['roles'] as $k => $role){
							if($role['role_name'] != $rolenow) {
					?>
							<div class="col-md-12 link box" style="padding: 10px; display:flex; <?php echo $k==count(Yii::app()->user->info['roles'])-1 ? "" : "margin-bottom:5px;";?>">
								<a href="index.php?r=site/switchrole&t=<?php echo $role['role_name'];?>" title="Switch Role" style="width:100%; font-size:15px;"><?php echo strtoupper($role['role_name']);?></a>
							</div>
					<?php			
							}
						}
					?>
				</div>
				<?php
				}
				?>                
        </ul>
	</li> -->
     <li>
        <h5 class="color-text mt-0 mb-1">Dashboard</h5>
        <div class="color-text time-bar">
            <span id="jam"></span> - 
            <span id="tanggal"></span>
        </div>
     </li>     
	 
	
     <?php
        }
        else {
        
    ?>
        <li style="margin-top: 0px; padding: 10px; vertical-align: bottom;">
            <a href="index.php?r=site/login"><i class="fa fa-sign-in"></i> Login</a>
        </li>        
    <?php
            }
    ?>
</ul>
