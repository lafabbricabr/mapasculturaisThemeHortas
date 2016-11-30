<?php
    $inst = [
        "num_inep",
        "cod_eol",
        "dre_vinc",
        "num_reg_stu",
        "director_name",
        "director_email",
        "ped_coord_name",
        "ped_coord_name",
        "ped_project",
        "project_in_progress",
        "edu_int"
    ];
?>
<?php if($show_cult):?>
    <div class="aba-content" id="tab-cultivo">
        <div class="ficha-spcultura">
            <div class="servico">
                <?php $insert_fields($entity, $cult); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if($show_comp):?>
    <div class="aba-content" id="tab-compostagem">
        <div class="ficha-spcultura">
            <div class="servico">
                <?php $insert_fields($entity, $comp); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if($show_cole):?>
    <div class="aba-content" id="tab-coleta">
        <div class="ficha-spcultura">
            <div class="servico">
                <?php $insert_fields($entity, $cole); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
