<div class="servico">
    <?php $this->applyTemplateHook('tab-about-service','begin'); ?>

    <?php if($this->isEditable() || $entity->emailPublico): ?>
    <p><span class="label">Email Público:</span> <span class="js-editable" data-edit="emailPublico" data-original-title="Email Público" data-emptytext="Insira um email que será exibido publicamente"><?php echo $entity->emailPublico; ?></span></p>
    <?php endif; ?>

    <?php if($this->isEditable()):?>
        <p class="privado"><span class="icon icon-private-info"></span><span class="label">Email Privado:</span> <span class="js-editable" data-edit="emailPrivado" data-original-title="Email Privado" data-emptytext="Insira um email que não será exibido publicamente"><?php echo $entity->emailPrivado; ?></span></p>
    <?php endif; ?>

    <?php if($this->isEditable() || $entity->telefonePublico): ?>
    <p><span class="label">Telefone Público:</span> <span class="js-editable js-mask-phone" data-edit="telefonePublico" data-original-title="Telefone Público" data-emptytext="Insira um telefone que será exibido publicamente"><?php echo $entity->telefonePublico; ?></span></p>
    <?php endif; ?>

    <?php if($this->isEditable()):?>
        <p class="privado"><span class="icon icon-private-info"></span><span class="label">Telefone Privado 1:</span> <span class="js-editable js-mask-phone" data-edit="telefone1" data-original-title="Telefone Privado" data-emptytext="Insira um telefone que não será exibido publicamente"><?php echo $entity->telefone1; ?></span></p>
        <p class="privado"><span class="icon icon-private-info"></span><span class="label">Telefone Privado 2:</span> <span class="js-editable js-mask-phone" data-edit="telefone2" data-original-title="Telefone Privado" data-emptytext="Insira um telefone que não será exibido publicamente"><?php echo $entity->telefone2; ?></span></p>
    <?php endif; ?>

    <?php $this->applyTemplateHook('tab-about-service','end'); ?>
</div>