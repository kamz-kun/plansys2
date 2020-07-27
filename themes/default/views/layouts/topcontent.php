<ul class="ds-me ds-nav" >
    <?php
    if(is_array(Yii::app()->user->info)){
        if((sizeof(Yii::app()->user->info) > 1)){ //if menu items available open bracket

    ?>
    <li class="flyout" style="margin-top: 0px; padding: 10px; vertical-align: bottom;">
        <?php 
            echo '<a href="#">'.strtoupper(Yii::app()->user->info['username']).' <i class="fa fa-chevron-down"></i></a>';                                 
        ?>  
        <ul class="flyout-content ds-nav stacked username">
                <div class="col-md-12">
                    <h4><small>Email : </small></h4>
                    <h4 title="<?php echo Yii::app()->user->info['email']; ?>">
                    <?php echo Yii::app()->user->info['email']; ?>
                    </h4>
                    <h4><small>Active Role : </small></h4>
                    <h4>
                    <?php                         
                    echo Role::model()->findByAttributes(['role_name' => Yii::app()->user->fullRole])->role_description; ?>
                    </h4>
                    <?php 
                        if(sizeof(Yii::app()->user->info['roles']) > 1){
                            echo '<hr style="margin: 10px 0px;"><h5>Switch Role : </h5>';
                            $r = Yii::app()->user->info['roles'];
                            foreach($r as $k => $v){
                                echo '<h5><a ng-url="sys/profile/changeRole&id='.$v['id'].'">'.$v['role_description'] . '</a></h5>';
                            }
                        }
                    ?>
                </div>
        </ul>
    </li>
    


    <li style="margin-top: 0px; padding: 10px; vertical-align: bottom;">
        <a href="index.php?r=sys/changePass" title="Change Password"><i class="fa fa-key"></i></a>
    </li>        
    <li style="margin-top: 0px; padding: 10px; vertical-align: bottom;">
        <a href="index.php?r=site/logout" title="Logout"><i class="fa fa-sign-out"></i></a>
    </li>            
     <li style="margin-top: 4px; padding-left: 10px; border-left: 1px solid #fff;">
          <span id="jam"></span>
         <br/>
         <span id="tanggal"></span>
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
    }
    ?>
</ul>
