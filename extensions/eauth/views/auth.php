<div class="services">
	<ul class="auth-services clear">
		
		<?php
		
		foreach ($services as $name => $service) {
			if($service->id == 'google_oauth'){
				$url =  Yii::app()->createAbsoluteUrl($action,array('service' => $name));
				$icon = Yii::app()->baseUrl.'/assets/images/google-logo-roundel.png';
				
				$html = '<li class="btn-default auth-service '.$name.'"><a href="'.$url.'" class="auth-link '.$name.'" style="color:#777;text-decoration:none;"><img src="'.$icon.'" width="24px" style= "margin-right:25px" /><b>' . $service->title . '</b></a></li>';
				
				echo $html;
			}else{
				echo '<li class="auth-service ' . $service->id . '">';
				$html = '<span class="auth-icon ' . $service->id . '"><i></i></span>';
				$html .= '<span class="auth-title">' . $service->title . '</span>';
				$html = CHtml::link($html, array($action, 'service' => $name), array(
					'class' => 'auth-link ' . $service->id,
				));
				echo $html;
				echo '</li>';	
			}
			
		}
		?>
	</ul>
</div>
