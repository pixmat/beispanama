<div id="content" class="clearfix">
	<div id="formApp">
		<h4>Hola, est&aacute;s logueado usando la cuenta <?php echo "@" . $this->twitter->userdata->screen_name; ?>.</h4>
		<p><?php echo anchor("app/logout", "Quiero desloguearme &raquo;"); ?></p>
		
		<p>Si quieres apoyar a tu equipo favorito de b&eacute;isbol nacional de Panam&aacute; tu cuenta de Twitter es muy f&aacute;cil: Escoge tu equipo favorito del b&eacute;isbol nacional, mira como quedar&iacute;a con tu profile picture actual y &iexcl;listo!.</p>
		
		<div id="formChangeAvatar">
			<?php echo form_open("app/process", array("class" => "jqtransform")); ?>
				<h5>Paso 1: Selecciona tu equipo favorito</h5>
				<p>
					Yo soy fan de: <?php echo form_dropdown('equipo', $equipos, "bocas"); ?>
				</p>
				
				<hr />
				
				<h5>Paso 2: Mira como quedar&iacute;a tu Avatar</h5>
				<div id="avatarSelector">
					<img src="<?php echo $avatar; ?>" alt="" />
					<img class="logoEquipo" src="<?php echo base_url(); ?>images/bocas.png" alt="" />
				</div><!--#avatarSelector -->
				
				<hr />
				
				<h5>Paso 3: &iquest;Quieres seguir a los creadores? Somos medio g33ks, pero no mordemos</h5>
				<p>
					<?php echo form_checkbox('follow', 'yes', TRUE); ?>
					<label for="follow">&iquest;Qui&eacute;res seguir @pixmat y @demogar en Twitter?</label>
				</p>
				<p>
					<?php echo form_checkbox('send_tweet', 'yes', TRUE); ?>
					<label for="send_tweet">&iquest;Qui&eacute;res enviar un tweet para que otros muestren el fervor por su equipo?</label>
				</p>
				
				<hr />
				
				<p><small><strong>NOTA:</strong> Guardamos tu <em>screen_name</em> por motivos de contabilizar cuantos usan nuestro servicio, pero ning&uacute;n dato privado ser&aacute; compartido con otras personas.</small></p>
				
				<h5>Paso 4: Cambia tu Avatar</h5>
				<?php echo form_submit("submit", "Cambia mi avatar"); ?>
			<?php echo form_close(); ?>
		</div><!--#formChangeAvatar-->
	</div>
</div><!--#content-->