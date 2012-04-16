<div class="twitts form">
<?php echo $form->create('Twitt');?>
	<fieldset>
 		<legend><?php __('Add Twitt');?></legend>
	<?php
		echo $form->input('user_id');
		echo $form->input('content');
		echo $form->input('date');
		echo $form->input('json');
		echo $form->input('hash');
		echo $form->input('code');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Twitts', true), array('action' => 'index'));?></li>
		<li><?php echo $html->link(__('List Opinions', true), array('controller' => 'opinions', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Opinion', true), array('controller' => 'opinions', 'action' => 'add')); ?> </li>
	</ul>
</div>
