<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 */
?>

<li class="heading"><?= __('Actions') ?></li>
<li><?= $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $tag->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id)]
    )
?></li>
<li><?= $this->Html->link(__('List Tags'), ['action' => 'index']) ?></li>
<li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
<li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?></li>
<?= $this->Form->create($tag) ?>
<fieldset>
    <legend><?= __('Add Tag') ?></legend>
    <?php
        echo $this->Form->control('title');
        echo $this->Form->control('articles._ids', ['options' => $articles]);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
