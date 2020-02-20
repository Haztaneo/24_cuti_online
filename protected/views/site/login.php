<div class="container">
		<header>
			<h1>CUTI ONLINE <span> PT. K24 Indonesia</span></h1>
		</header>
		<section>				
			<div id="container_demo">                    
				<div id="wrapper">
					<div id="login" class="animate form">
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'login-form',
						'enableClientValidation'=>true,
						'clientOptions'=>array(
							'validateOnSubmit'=>true,
						),
					)); ?>                            
						<h1>Log in</h1>
						<?php 							
							if(strlen(strip_tags($form->error($model,'username')))>1){ 
								// echo '<div style="border:1px solid #CC0000; background-color:#FFEEEE; padding:5px; text-align:center; margin-bottom:20px; color:red"><b>'.$form->error($model,'username').'</b></div>';
								echo '<div style="text-align:center; margin-bottom:20px; color:red"><b>'.$form->error($model,'username').'</b></div>';
						 } ?>
						<p> 
							<label for="username" class="uname" data-icon="u" > Email Corporate </label>
							<?php echo $form->textField($model,'username',array('required'=>'required','placeholder'=>'Email Corporate')); ?>
						</p>
						<p> 
							<label for="password" class="youpasswd" data-icon="p"> Password </label>
							<?php echo $form->passwordField($model,'password',array('required'=>'required','placeholder'=>'Password Email Corporate')); ?>
						</p>
						<p class="login button"> 
							<input type="submit" value="Login" /> 
						</p>
						<p class="change_link"></p>                            
					<?php $this->endWidget(); ?>
					</div>	
				</div>				
			</div>  
		</section>		
	</div>		