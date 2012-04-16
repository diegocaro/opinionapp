<div class="opinions form">
<?php echo $form->create('Opinion');?>
	<fieldset>
 		<legend><?php __('Edit Opinion');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('class');
		echo $form->input('twitt_id');
		echo $form->input('user_id');
		echo $form->input('date');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('Opinion.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Opinion.id'))); ?></li>
		<li><?php echo $html->link(__('List Opinions', true), array('action' => 'index'));?></li>
		<li><?php echo $html->link(__('List Twitts', true), array('controller' => 'twitts', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Twitt', true), array('controller' => 'twitts', 'action' => 'add')); ?> </li>
	</ul>
</div>
