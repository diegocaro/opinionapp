<div class="twitts view">
<h2><?php  __('Twitt');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['user_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Content'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['content']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Json'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['json']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Hash'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['hash']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $twitt['Twitt']['code']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Twitt', true), array('action' => 'edit', $twitt['Twitt']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Twitt', true), array('action' => 'delete', $twitt['Twitt']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $twitt['Twitt']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Twitts', true), array('action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Twitt', true), array('action' => 'add')); ?> </li>
		<li><?php echo $html->link(__('List Opinions', true), array('controller' => 'opinions', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Opinion', true), array('controller' => 'opinions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Opinions');?></h3>
	<?php if (!empty($twitt['Opinion'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Class'); ?></th>
		<th><?php __('Twitt Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Date'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($twitt['Opinion'] as $opinion):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $opinion['id'];?></td>
			<td><?php echo $opinion['class'];?></td>
			<td><?php echo $opinion['twitt_id'];?></td>
			<td><?php echo $opinion['user_id'];?></td>
			<td><?php echo $opinion['date'];?></td>
			<td><?php echo $opinion['created'];?></td>
			<td><?php echo $opinion['modified'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller' => 'opinions', 'action' => 'view', $opinion['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller' => 'opinions', 'action' => 'edit', $opinion['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller' => 'opinions', 'action' => 'delete', $opinion['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $opinion['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Opinion', true), array('controller' => 'opinions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
