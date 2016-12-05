<?php if($this->isEditable()): ?>
    <span style="display: none" class="js-editable-taxonomy" data-original-title="Área de Atuação" data-taxonomy="area"><?php echo $entity->terms['area'] ? implode('; ', $entity->terms['area']) : "Educação"; ?></span>
<?php endif;?>