<?php

    // $inst = [
    //     "num_inep",
    //     "cod_eol",
    //     "dre_vinc",
    //     "num_reg_stu",
    //     "director_name",
    //     "director_email",
    //     "ped_coord_name",
    //     "ped_coord_name",
    //     "ped_project",
    //     "project_in_progress",
    //     "edu_int"
    // ];
    $cult = [
        "hor_ped_obj",
        "hor_crop_type",
        "hor_frut_tree",
        "hor_frut_tree_ped",
        "hor_nursery",
        "hor_scholl_assist",
        "hor_rain_wat",
        "hor_involved_name",
        "hor_involved_email",
        "hor_access_veg_sup",
        "hor_tool_types",
        "hor_area_cultivated",
        "hor_area_cultivable",
        "hor_type_cult",
        "hor_num_stud_involved"
    ];
    $comp =[
        "hor_comp",
        "hor_comp_inv_name",
        "hor_comp_inv_email",
        "hor_comp_num_stud",
        "hor_comp_assist"
    ];
    $cole = [
        "hor_selec_collect",
        "hor_solid_sep",
        "hor_solid_sep_resp",
        "hor_solid_sep_resp_email"
    ];

?>

<div class="aba-content" id="tab-cultivo">
    <?php $insert_fields($entity, $cult); ?>
</div>

<div class="aba-content" id="tab-compostagem">
    <?php $insert_fields($entity, $comp); ?>
</div>

<div class="aba-content" id="tab-coleta">
    <?php $insert_fields($entity, $cole); ?>
</div>
