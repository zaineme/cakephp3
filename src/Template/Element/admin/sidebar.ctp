<nav class="large-3 medium-4 columns" id="actions-sidebar">
	<ul class="side-nav">
		<?php $newAction = 'New ' . $this->request->params['controller']; ?>
	    <li class="heading"><?= __('Actions') ?></li>
	    <li><?= $this->Html->link(__($newAction), ['action' => 'add']) ?></li>
	    <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
	    <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
	    <li><?= $this->Html->link(__('List Tags'), ['controller' => 'Tags', 'action' => 'index']) ?></li>
	</ul>
</nav>
