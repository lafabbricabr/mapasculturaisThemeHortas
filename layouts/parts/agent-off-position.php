<?php if($this->isEditable() || $entity->hor_off_position ): ?>
    <p>
        <span class="label">Cargo:</span>
        <span class="js-editable"
        data-edit="hor_off_position"
        data-original-title="Cargo do Funcionário"
        data-emptytext="Insira o cargo do funcionário"><?php echo $entity->hor_off_position; ?></span>
    </p>
<?php endif; ?>