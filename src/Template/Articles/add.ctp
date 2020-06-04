<h1>Add Article</h1>
<?php
    echo $this->Form->create($article);
    echo $this->Form->control('title');
    echo $this->Form->control('body', [ 'class' => 'editor']);
    echo $this->Form->control('tag_string', ['type' => 'text']);
    echo $this->Form->button(__('Save Article'));
    echo $this->Form->end();
?>
<script type="text/javascript">
	console.log($('.ckeditor').val());
</script>