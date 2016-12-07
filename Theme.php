<?php
namespace mapasculturaisThemeHortas;
use MapasCulturais\App;
use MapasCulturais\Themes\BaseV1;
use MapasCulturais\Definitions;

class Theme extends BaseV1\Theme{

    protected static function _getTexts(){
        $app = App::i();
        $self = $app->view;
        $url_search_agents = $self->searchAgentsUrl;
        $url_search_spaces = $self->searchSpacesUrl;
        $url_search_events = $self->searchEventsUrl;
        $url_search_projects = $self->searchProjectsUrl;

        return [
           'site: name' => 'Gestão de Hortas',
//            'site: description' => App::i()->config['app.siteDescription'],
//            'site: in the region' => 'na região',
//            'site: of the region' => 'da região',
//            'site: owner' => 'Secretaria',
//            'site: by the site owner' => 'pela Secretaria',
//
           'home: title' => "Olá!",
//            'home: abbreviation' => "MC",
//            'home: colabore' => "Colabore com o Mapas Culturais",
           'home: welcome' => "Este é o <b>Sistema de Gestão de Hortas Escolares</b>, uma plataforma da Secretaria Municipal de Educação de São Paulo. Foi desenvolvido para que gestores da SME, diretores, coordenadores e professores compartilhem informações sobre cultivo, compostagem e coleta seletiva nas escolas da rede municipal, facilitando a organização e atualização desses dados.",
//            'home: events' => "Você pode pesquisar eventos culturais nos campos de busca combinada. Como usuário cadastrado, você pode incluir seus eventos na plataforma e divulgá-los gratuitamente.",
           'home: agents' => "Você pode colaborar no mapeamento das hortas com suas próprias informações, preenchendo seu perfil de agente. Neste espaço, podemos encontrar uma rede de professores e gestores municipais envolvidos com hortas escolares das escolas da rede municipal de São Paulo.

",
           'home: spaces' => "Procure por escolas cadastradas na plataforma, acessando os campos de busca combinada que ajudam na precisão de sua pesquisa.",
//            'home: projects' => "Reúne projetos culturais ou agrupa eventos de todos os tipos. Neste espaço, você encontra leis de fomento, mostras, convocatórias e editais criados, além de diversas iniciativas cadastradas pelos usuários da plataforma. Cadastre-se e divulgue seus projetos.",
           'home: home_devs' => 'Existem algumas maneiras de desenvolvedores interagirem com o Sistema de Gestão de Hortas Escolares. A primeira é por meio da nossa <a href="https://github.com/hacklabr/mapasculturais/blob/master/documentation/docs/mc_config_api.md">API</a>. Com ela você pode acessar os dados públicos no nosso banco de dados e utilizá-los para desenvolver aplicações externas. Além disso, o Sistema de Gestão de Hortas Escolares foi desenvolvido a partir do software livre <a href="https://institutotim.org.br/project/mapas-culturais/">Mapas Culturais</a>, uma iniciativa do <a href="https://institutotim.org.br/">Instituto TIM</a>.',
//
//            'search: verified results' => 'Resultados Verificados',
//            'search: verified' => "Verificados"
            'entities: Spaces of the agent'=> 'Escolas do agente',
            'entities: Space Description'=> 'Descrição do Escola',
            'entities: My Spaces'=> 'Minhas Escolas',
            'entities: My spaces'=> 'Minhas escolas',

            'entities: no registered spaces'=> 'nenhuma escola cadastradoa',
            'entities: no spaces'=> 'nenhuma escola',

            'entities: Space' => 'Escola',
            'entities: Spaces' => 'Escolas',
            'entities: space' => 'escola',
            'entities: spaces' => 'escolas',
            'entities: parent space' => 'escola mãe',
            'entities: a space' => 'uma escola',
            'entities: the space' => 'a escola',
            'entities: of the space' => 'da escola',
            'entities: In this space' => 'Nesta escola',
            'entities: in this space' => 'nesta escola',
            'entities: registered spaces' => 'Escolas Identificadas',
            'entities: new space' => 'nova escola',
        ];
    }

    static function getThemeFolder() {
        return __DIR__;
    }

    function _init() {
        parent::_init();
        $app = App::i();

        $cult = [
            'hor_has_cult',
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

        $has_fields = function($fields, $entity){
            foreach ($fields as $field)
                if ($entity->$field) return true;
            return false;
        };

        $app->hook('view.render(<<*>>):before', function() use($app) {
            $this->_publishAssets();
            $this->assetManager->publishAsset('img/home-agents.png');
            $this->assetManager->publishAsset('img/home-developers.png');
            $this->assetManager->publishAsset('img/home-intro.png');
            $this->assetManager->publishAsset('img/home-spaces.png');
            $this->assetManager->publishAsset('img/sme-about.png');
            $this->assetManager->publishFolder('fonts');
        });

        $app->hook('template(space.<<*>>.tabs):begin', function() use($app, $cult, $comp, $cole, $has_fields){
            $entity = $app->view->controller->requestedEntity;
            $show_cult = $this->isEditable() || $has_fields($cult, $entity);
            $show_comp = $this->isEditable() || $has_fields($comp, $entity);
            $show_cole = $this->isEditable() || $has_fields($cole, $entity);
            $this->part(
                'header-tabs',
                [
                    'show_cult' => $show_cult,
                    'show_comp' => $show_comp,
                    'show_cole' => $show_cole
                ]
             );
        });

        $insert_fields = function($entity, $fields) use($app){

            foreach ($fields as $field) {

                $meta = $app->getRegisteredMetadataByMetakey($field, $entity);

                if($this->isEditable() || ($entity->$field && !$meta->private)){

                    if (isset($meta->config['empty_text']))
                        $field_empty = $meta->config['empty_text'];
                    else if (in_array($meta->type, ['select', 'multiselect']))
                        $field_empty = 'Selecione';
                    else
                        $field_empty = 'Insira';

                    $field_class = '';

                    if ($this->isEditable && $meta->is_required)
                        $field_class .= ' required';


                    $this->part(
                        'singles/form-field',
                        [
                            'entity'        => $entity,
                            'field_name'    => $field,
                            'field_label'   => $meta->label,
                            'field_empty'   => $field_empty,
                            'field_class'   => $field_class,
                            'field_private' => $meta->private,
                            'field_type'    => $meta->type
                        ]
                    );
                }
            }

        };

        $app->hook('template(space.<<*>>.tabs-content):end', function() use($app, $insert_fields, $cult, $comp, $cole, $has_fields){
            $entity = $app->view->controller->requestedEntity;

            $show_cult = $this->isEditable() || $has_fields($cult, $entity);
            $show_comp = $this->isEditable() || $has_fields($comp, $entity);
            $show_cole = $this->isEditable() || $has_fields($cole, $entity);

            $this->part(
                'content-tabs',
                [
                    'entity'        => $entity,
                    'insert_fields' => $insert_fields,
                    'cult'          => $cult,
                    'comp'          => $comp,
                    'cole'          => $cole,
                    'show_cult'     => $show_cult,
                    'show_comp'     => $show_comp,
                    'show_cole'     => $show_cole
                ]
            );
        });

        $app->hook('template(space.<<*>>.tab-about-service):begin', function() use($app, $insert_fields){
            $inst = [
                "hor_num_inep",
                "hor_cod_eol",
                "hor_num_reg_stu",
                "hor_director_name",
                "hor_director_email",
                "hor_ped_coord_name",
                "hor_ped_coord_email",
                "hor_ped_project",
                "hor_project_in_progress",
                "hor_edu_int"
            ];
            $entity = $app->view->controller->requestedEntity;
            $this->part(
                'content-tab-inst',
                [
                    'entity' => $entity,
                    'inst' => $inst,
                    'insert_fields' => $insert_fields
                ]
            );
        });
    }

    protected function _getSpaceMetadata() {
        return [
            // Inicio Institucional
            "num_inep" => [
                "label" => "Número INEP",
                "type" => "int"
            ],
            "cod_eol" => [
                "label" => "Código EOL",
                "required" => "Campo obrigatório"
            ],
            "dre_vinc" => [
                "label" => "DRE a qual a escola está vinculada",
                "type" => "select",
                "validations" => [
                    "required" => "Selecione a qual DRE a escola está vinculada"
                ],
                "options" => [
                    "DRE Butantã",
                    "DRE Campo Limpo",
                    "DRE Capela do Socorro",
                    "DRE Freguesia/ Brasilândia",
                    "DRE Guaianases",
                    "DRE Ipiranga",
                    "DRE Itaquera",
                    "DRE Jaçanã / Tremembé",
                    "DRE Penha",
                    "DRE Pirituba",
                    "DRE Santo Amaro",
                    "DRE São Mateus",
                    "DRE São Miguel"
                ],
                "required" => "Campo obrigatório"
            ],
            "num_reg_stu" => [
                "label" => "Número de alunos matriculados",
                "type" => "int"
            ],
            "director_name" => [
                "label" => "Nome do Diretor",
            ],
            "director_email" => [
                "label" => "Email do Diretor",
            ],
            "ped_coord_name" => [
                "label" => "Nome do Coordenador Pedagógico",
            ],
            "ped_coord_email" => [
                "label" => "Email do Coordenador Pedagógico",
            ],
            "ped_project" => [
                "label" => "Projeto Político Pedagógico",
                "type" => "link"
            ],
            "project_in_progress" => [
                "label" => "Projetos em andamento",
                "type" => "link"
            ],
            "edu_int" => [
                "label" => "É uma escola de educação integral?",
                "type" => "select",
                "options" => [
                    "Não",
                    "Integral (até 35h semanais ou mais)",
                    "Parcial (até 34,5h semanais)"
                ]
            ],
            // Fim Institucional
            // Começo Cultivo
            "has_cult" => [
                "label" => "A escola tem horta?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ],
            ],
            "ped_obj" => [
                "label" => "É uma horta com objetivo pedagógico?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ],
            ],
            "crop_type" => [
                "label" => "Quais são os tipos de cultivo utilizados?",
                "type" => "select",
                "options" => [
                    "Cultivo acima do solo",
                    "Cultivo no solo",
                    "Cultivos verticais",
                    "Cultivo em telhados"
                ]
            ],
            "frut_tree" => [
                "label" => "A escola tem uma ou mais árvores frutíferas?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ]
            ],
            "frut_tree_ped" => [
                "label" => "Estas árvores frutíferas são utilizadas com objetivo pedagógico?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ]
            ],
            "nursery" => [
                "label" => "A escola tem viveiro?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ]
            ],
            "scholl_assist" => [
                "label" => "A escola conta com assistência técnica permanente para horta, pomar ou viveiro?",
                "type" => "select",
                "options" => [
                    "Não",
                    "Técnico da SME",
                    "Técnico da SVMA",
                    "Técnico da SDTE",
                    "Técnico da SMS",
                    "Técnico de ONG",
                    "Técnico da comunidade Escolar",
                    "Outros"
                ]
            ],
            "rain_wat" => [
                "label" => "A escola realiza captação de água da chuva?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ]
            ],
            "involved_name" => [
                "label" => "Nome de um ou mais envolvidos com atividades na horta, pomar ou viveiro.",
            ],
            "involved_email" => [
                "label" => "Email de um ou mais envolvidos com atividades na horta, pomar ou viveiro.",
            ],
            "access_veg_sup" => [
                "label" => "A escola tem acesso permanente a insumos consumíveis para horta?",
                "type" => "select",
                "options" => [
                    "Adubo",
                    "Orgânico",
                    "Argila Expandida",
                    "Biofertilizante",
                    "Calcário",
                    "Composto",
                    "Manta Bidimensional",
                    "Mudas",
                    "Palha",
                    "Pedrisco",
                    "Sacho",
                    "Sementes",
                    "Terra",
                    "Triturado De Poda"
                ]
            ],
            "tool_types" => [
                "label" => "Quais são os tipos e quantidades de ferramentas disponíveis para horta na escola?",
                'type' => 'multiselect',
                "options" => [
                    "Ancinho",
                    "Balde",
                    "Cavadeira",
                    "Enxada Padolfo Larga",
                    "Enxadão Estreito",
                    "Luvas",
                    "Mangueira",
                    "Pazinha De Mão",
                    "Peneira De Areia",
                    "Pá De Bico",
                    "Pá Quadrada",
                    "Pá Quadrada",
                    "Rastelo",
                    "Regador",
                    "Tesoura De Poda",
                ]
            ],
            "area_cultivated" => [
                "label" => "Qual é a área cultivada em m2?",
                "type" => "int"
            ],
            "area_cultivable" => [
                "label" => "Qual a área cultivável em m2?",
                "type" => "int"
            ],
            "type_cult" => [
                "label" => "Quais são os tipos de alimentos cultivados?",
                'type' => 'multiselect',
                "options" => [
                    "Frutas",
                    "Legumes",
                    "Leguminosas",
                    "Medicinais",
                    "PANCs",
                    "Temperos",
                    "Verduras"
                ]
            ],
            "num_stud_involved" => [
                "label" => "Qual a quantidade de alunos envolvidos com horta?",
                "type" => "int"
            ],
            // Fim Cultivo
            // Início Compostagem
            "comp" => [
                "label" => "A escola faz compostagem?",
                "type" => "select",
                "options" => [
                    "Não",
                    "Sim, com minhocário",
                    "Sim, com composteira termofílica"
                ]
            ],
            "comp_inv_name" => [
                "label" => "Nome de um ou mais envolvidos com atividades de compostagem"
            ],
            "comp_inv_email" => [
                "label" => "Email de um ou mais envolvidos com atividades de compostagem."
            ],
            "comp_num_stud" => [
                "label" => "Qual a quantidade de alunos envolvidos com compostagem?",
                "type" => "int"
            ],
            "comp_assist" => [
                "label" => "A escola conta com assistência técnica para compostagem?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ]
            ],
            // Fim compostagem
            // Início Coleta
            "selec_collect" => [
                "label" => "A escola conta com coleta seletiva na porta?",
                "type" => "select",
                "options" => [
                    'Não',
                    'Caminhão de coleta seletiva da prefeitura',
                    'Cooperativa de coleta credenciada pela prefeitura',
                    'Outros'
                ]
            ],
            "solid_sep" => [
                "label" => "A escola faz separação de resíduos sólidos?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
                ]
            ],
            "solid_sep_resp" => [
                "label" => "Nome do responsável pela separação de resíduos para coleta seletiva "
            ],
            "solid_sep_resp_email" => [
                "label" => "Email do responsável"
            ]
            // Fim Coleta
        ];

    }

    protected function _publishAssets() {
        $this->jsObject['assets']['logo-instituicao'] = $this->asset('img/logo-instituicao.png', false);
    }

    public function register() {
        parent::register();
        $app = App::i();
        $metadata = [];

        foreach($this->_getSpaceMetadata() as $key => $cfg){
            $key = 'hor_' . $key;
            $metadata['MapasCulturais\Entities\Space'][$key] = $cfg;
        }

        foreach($metadata as $entity_class => $metas){
            foreach($metas as $key => $cfg){
                $def = new \MapasCulturais\Definitions\Metadata($key, $cfg);
                $app->registerMetadata($def, $entity_class);
            }
        }

        $app->hook('app.register', function(&$registry) {
            $group = null;
            $registry['entity_type_groups']['MapasCulturais\Entities\Space'] = array_filter($registry['entity_type_groups']['MapasCulturais\Entities\Space'], function($item) use (&$group) {
                if ($item->name === 'Hortas Escolares') {
                    $group = $item;
                    return $item;
                } else {
                    return null;
                }
            });

            $registry['entity_types']['MapasCulturais\Entities\Space'] = array_filter($registry['entity_types']['MapasCulturais\Entities\Space'], function($item) use ($group) {
                    if ($item->id >= $group->min_id && $item->id <= $group->max_id) {
                        return $item;
                    } else {
                        return null;
                    }
                }
            );
        });

        $school_types =[
            6000 => 'CCI/CIPS',
            6001 => 'CECI',
            6002 => 'CEI Direta',
            6003 => 'CEI Indireta',
            6004 => 'CEU CEI',
            6005 => 'CEU EMEI',
            6006 => 'Creche Conveniada',
            6007 => 'EMEI',
            6008 => 'CEMEI',
            6019 => 'EMEBS Infantil',
            6010 => 'EMEF',
            6011 => 'CEU EMEF',
            6012 => 'EMEBS EMEF',
            6013 => 'EJA Regular',
            6014 => 'EJS Modular',
            6015 => 'CIEJA',
            6016 => 'MOVA',
            6017 => 'Ensino Médio Regular',
            6018 => 'Ensino Profissionalizante'
        ];

        $app->registerEntityTypeGroup(new Definitions\EntityTypeGroup('MapasCulturais\Entities\Space', 'Hortas Escolares', 6000, 6019));

        foreach($school_types as $k => $v)
            $app->registerEntityType(new Definitions\EntityType('MapasCulturais\Entities\Space', $k, $v));
    }
}
