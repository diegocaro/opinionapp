<div class="twitts index">
<h2><?php __('Twitts');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('content');?></th>
	<th><?php echo $paginator->sort('date');?></th>
	<th><?php echo $paginator->sort('json');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th><?php echo $paginator->sort('hash');?></th>
	<th><?php echo $paginator->sort('code');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($twitts as $twitt):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $twitt['Twitt']['id']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['user_id']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['content']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['date']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['json']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['created']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['modified']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['hash']; ?>
		</td>
		<td>
			<?php echo $twitt['Twitt']['code']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action' => 'view', $twitt['Twitt']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $twitt['Twitt']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action' => 'delete', $twitt['Twitt']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $twitt['Twitt']['id'])); ?>
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
		<li><?php echo $html->link(__('New Twitt', true), array('action' => 'add')); ?></li>
		<li><?php echo $html->link(__('List Opinions', true), array('controller' => 'opinions', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Opinion', true), array('controller' => 'opinions', 'action' => 'add')); ?> </li>
	</ul>
</div>
