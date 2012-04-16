<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		Prototipo para análisis automático de opiniones en sistemas de micro-blogging // By Diego Caro A. @diegocaro
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		//echo $html->meta('icon');

		//echo $html->css('cake.generic');
        echo $html->css('style');

        //echo $javascript->link('prototype');
        //echo $javascript->link('scriptaculous');

        echo $javascript->link('jquery');
        
        //Calendar jquery
        echo $javascript->link('jquery.date_input');
        echo $html->css('date_input');
		
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header">
		    <h1><?php echo $html->link('Prototipo opinionTwitter', '/'); ?>
		    <span><i>Un prototipo para análisis automático de opiniones en sistemas de micro-blogging</i></span>
		    </h1>
            
		</div>

		<div id="content">

			<?php $session->flash(); ?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
			<p>Desarrollado por Diego Caro A.</p>
		</div>
	</div>
	<?php echo $cakeDebug; ?>
</body>
</html>
