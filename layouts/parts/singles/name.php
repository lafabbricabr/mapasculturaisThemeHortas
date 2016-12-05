<?php
$class = isset($disable_editable) ? '' : 'js-editable';

$editEntity = $this->controller->action === 'create' || $this->controller->action === 'edit';
?>

<?php $this->applyTemplateHook('name','before'); ?>
<h2><span class="<?php echo $class ?> <?php echo ($entity->isPropertyRequired($entity,"name") && $editEntity? 'required': '');?>" data-edit="name" data-original-title="Nome da escola" data-emptytext="Nome da escola"><?php echo $entity->name; ?></span></h2>
<?php $this->applyTemplateHook('name','after'); ?>
