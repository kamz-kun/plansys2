<ul class="ds-me ds-nav" >
    <?php
        if((sizeof(Yii::app()->user->info) > 1)){ //if menu items available open bracket

    ?>
    <li class="flyout" style="margin-top: 0px; padding: 10px; vertical-align: bottom;">
        <?php 
            echo '<a href="#">'.strtoupper(Yii::app()->user->info['full_role']).'</a>';                     
            
        ?>  
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
    ?>
</ul>
