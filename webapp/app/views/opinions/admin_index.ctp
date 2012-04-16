<div class="opinions index">
<h2><?php __('Opinions');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('class');?></th>
	<th><?php echo $paginator->sort('twitt_id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('date');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($opinions as $opinion):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $opinion['Opinion']['id']; ?>
		</td>
		<td>
			<?php echo $opinion['Opinion']['class']; ?>
		</td>
		<td>
			<?php echo $html->link($opinion['Twitt']['id'], array('controller' => 'twitts', 'action' => 'view', $opinion['Twitt']['id'])); ?>
		</td>
		<td>
			<?php echo $opinion['Opinion']['user_id']; ?>
		</td>
		<td>
			<?php echo $opinion['Opinion']['date']; ?>
		</td>
		<td>
			<?php echo $opinion['Opinion']['created']; ?>
		</td>
		<td>
			<?php echo $opinion['Opinion']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action' => 'view', $opinion['Opinion']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $opinion['Opinion']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action' => 'delete', $opinion['Opinion']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $opinion['Opinion']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Opinion', true), array('action' => 'add')); ?></li>
		<li><?php echo $html->link(__('List Twitts', true), array('controller' => 'twitts', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Twitt', true), array('controller' => 'twitts', 'action' => 'add')); ?> </li>
	</ul>
</div>
