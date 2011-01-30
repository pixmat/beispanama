		<div id="footer">
			<div class="like-box">
				<p><a href="http://twitter.com/share" class="twitter-share-button" data-url="http://www.beispanama.com" data-text="Yo apoyo a mi equipo favorito del béisbol panameño" data-count="horizontal" data-via="pixmat" data-lang="es">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></p>
				
				<p><fb:like href="http://www.beispanama.com" show_faces="false" width="450" font="arial"></fb:like></p>
			</div><!--.like-box-->
			
			<p>
				&copy; Copyright 2011. C&oacute;digo y dise&ntilde;o hecho por @demogar en @pixmat. Made in Panama, &iexcl;carajo!.<br />
				C&oacute;digo liberado en <a href="http://github.com/demogar/beispanama" target="_blank" title="Repositorio en Git">GitHub</a> bajo licencia <a href="http://sam.zoy.org/wtfpl/" target="_blank" title="WTFPL - Do What The Fuck You Want To Public License">WTFPL</a>.
			</p>
		</div><!--#footer-->
		
		<!-- JavaScript -->
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<script type="text/javascript" charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>assets/js/jquery.uniform.min.js"></script>
		
		<script src="http://platform.twitter.com/anywhere.js?id=anxYCxdAmgIf4yfi8rylsw&amp;v=1" type="text/javascript"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$("select[name='equipo']").change(function() {
					$("img.logoEquipo").attr("src", "<?php echo base_url(); ?>images/" + $(this).val() + ".png");
				});
				
				$("#formChangeAvatar form").submit(function() {
					return confirm("Tienes un backup de tu imagen actual de Twitter? La sobreescribiremos");
				});
				
				$("select, input").uniform();
			});
			
			twttr.anywhere(function (T) {
				T.hovercards();
			});
		</script>
		
		<!-- Analytics -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-3999022-9']);
			_gaq.push(['_trackPageview']);
			
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</body>
</html>