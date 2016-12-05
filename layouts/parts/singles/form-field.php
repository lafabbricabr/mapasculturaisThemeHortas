<?php if($field_private): ?>
    <p class="privado"><span class="icon icon-private-info"></span>
<?php else: ?>
    <p>
<?php endif; ?>
<span class="label"><?php echo $field_label ?>: </span>
<?php if($field_type === "multiselect"): ?>
        <editable-multiselect entity-property="<?php echo $field_name ?>" empty-label="<?php echo $field_empty ?>" box-title="<?php echo $field_label ?>:"></editable-multiselect>
<?php else: ?>
<span
        class="js-editable <?php echo $field_class?>"
        data-edit="<?php echo $field_name ?>"
        data-original-title="<?php echo $field_label ?>"
        data-emptytext="<?php echo $field_empty ?>"><?php echo $entity->$field_name; ?></span>
<?php endif; ?>
</p>
