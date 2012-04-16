<div class="opinions view">
<h2><?php  __('Opinion');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $opinion['Opinion']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Class'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $opinion['Opinion']['class']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Twitt'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($opinion['Twitt']['id'], array('controller' => 'twitts', 'action' => 'view', $opinion['Twitt']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $opinion['Opinion']['user_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $opinion['Opinion']['date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $opinion['Opinion']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $opinion['Opinion']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Opinion', true), array('action' => 'edit', $opinion['Opinion']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Opinion', true), array('action' => 'delete', $opinion['Opinion']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $opinion['Opinion']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Opinions', true), array('action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Opinion', true), array('action' => 'add')); ?> </li>
		<li><?php echo $html->link(__('List Twitts', true), array('controller' => 'twitts', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Twitt', true), array('controller' => 'twitts', 'action' => 'add')); ?> </li>
	</ul>
</div>
