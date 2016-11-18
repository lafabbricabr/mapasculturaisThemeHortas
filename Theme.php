<?php
namespace mapasculturaisThemeHortas;
use MapasCulturais\Themes\BaseV1;
use MapasCulturais\App;

class Theme extends BaseV1\Theme{

    protected static function _getTexts(){
        $app = App::i();
        $self = $app->view;
        $url_search_agents = $self->searchAgentsUrl;
        $url_search_spaces = $self->searchSpacesUrl;
        $url_search_events = $self->searchEventsUrl;
        $url_search_projects = $self->searchProjectsUrl;

        return [
//            'site: name' => App::i()->config['app.siteName'],
//            'site: description' => App::i()->config['app.siteDescription'],
//            'site: in the region' => 'na região',
//            'site: of the region' => 'da região',
//            'site: owner' => 'Secretaria',
//            'site: by the site owner' => 'pela Secretaria',
//
//            'home: title' => "Bem-vind@!",
//            'home: abbreviation' => "MC",
//            'home: colabore' => "Colabore com o Mapas Culturais",
//            'home: welcome' => "O Mapas Culturais é uma plataforma livre, gratuita e colaborativa de mapeamento cultural.",
//            'home: events' => "Você pode pesquisar eventos culturais nos campos de busca combinada. Como usuário cadastrado, você pode incluir seus eventos na plataforma e divulgá-los gratuitamente.",
//            'home: agents' => "Você pode colaborar na gestão da cultura com suas próprias informações, preenchendo seu perfil de agente cultural. Neste espaço, estão registrados artistas, gestores e produtores; uma rede de atores envolvidos na cena cultural da região. Você pode cadastrar um ou mais agentes (grupos, coletivos, bandas instituições, empresas, etc.), além de associar ao seu perfil eventos e espaços culturais com divulgação gratuita.",
//            'home: spaces' => "Procure por espaços culturais incluídos na plataforma, acessando os campos de busca combinada que ajudam na precisão de sua pesquisa. Cadastre também os espaços onde desenvolve suas atividades artísticas e culturais.",
//            'home: projects' => "Reúne projetos culturais ou agrupa eventos de todos os tipos. Neste espaço, você encontra leis de fomento, mostras, convocatórias e editais criados, além de diversas iniciativas cadastradas pelos usuários da plataforma. Cadastre-se e divulgue seus projetos.",
//            'home: home_devs' => 'Existem algumas maneiras de desenvolvedores interagirem com o Mapas Culturais. A primeira é através da nossa <a href="https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md" target="_blank">API</a>. Com ela você pode acessar os dados públicos no nosso banco de dados e utilizá-los para desenvolver aplicações externas. Além disso, o Mapas Culturais é construído a partir do sofware livre <a href="http://institutotim.org.br/project/mapas-culturais/" target="_blank">Mapas Culturais</a>, criado em parceria com o <a href="http://institutotim.org.br" target="_blank">Instituto TIM</a>, e você pode contribuir para o seu desenvolvimento através do <a href="https://github.com/hacklabr/mapasculturais/" target="_blank">GitHub</a>.',
//
//            'search: verified results' => 'Resultados Verificados',
//            'search: verified' => "Verificados"
        ];
    }

    static function getThemeFolder() {
        return __DIR__;
    }

    function _init() {
        parent::_init();
        $app = App::i();

        $app->hook('view.render(<<*>>):before', function() use($app) {
            $this->_publishAssets();
        });

    }

    protected function _getSpaceMetadata() {
        return [
            "num_inep" => [
                "label" => "Número INEP da escola",
                "type" => "text"
            ],
            "cod_eol" => [
                "label" => "Código EOL",
                "type" => "text",
                "required" => "Campo obrigatório"
            ],
            "dre_vinc" => [
                "label" => "DRE a qual a escola está vinculada",
                "type" => "select",
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
                "type" => "text"
            ],
            "director_email" => [
                "label" => "Email do Diretor",
                "type" => "text"
            ],
            "ped_coord_name" => [
                "label" => "Nome do Coordenador Pedagógico",
                "type" => "text"
            ],
            "ped_coord_name" => [
                "label" => "Email do Coordenador Pedagógico",
                "type" => "text"
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
                "type" => "text"
            ],
            "involved_email" => [
                "label" => "Email de um ou mais envolvidos com atividades na horta, pomar ou viveiro.",
                "type" => "text"
            ],
            "access_veg_sup" => [
                "label" => "A escola tem acesso permanente a insumos consumíveis para horta?",
                "type" => "select",
                "options" => [
                    "Adubo Orgânico",
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
                'multiselect',
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
                    "Tesoura De Poda"
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
                "multiselect",
                "options" => [
                    "Frutas".
                    "Legumes".
                    "Leguminosas".
                    "Medicinais".
                    "PANCs".
                    "Temperos".
                    "Verduras"
                ]
            ],
            "num_" => [
                "label" => "Qual a quantidade de alunos envolvidos com horta?",
                "type" => "int"
            ],
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
                "label" => "Nome de um ou mais envolvidos com atividades de compostagem",
                "type" => "text"
            ],
            "comp_inv_email" => [
                "label" => "Email de um ou mais envolvidos com atividades de compostagem.",
                "type" => "text"
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
            "selec_collect" => [
                "label" => "A escola conta com coleta seletiva na porta?",
                "type" => "select",
                "options" => [
                    "Sim",
                    "Não"
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
                "label" => "Nome do responsável pela separação de resíduos para coleta seletiva ",
                "type" => "text"
            ],
            "solid_sep_resp_email" => [
                "label" => "Email do responsável",
                "type" => "text"
            ]
        ];

    }

    protected function _publishAssets() {
        $this->jsObject['assets']['logo-instituicao'] = $this->asset('img/logo-instituicao.png', false);
    }

    public function register() {
        parent::register();

        foreach($this->_getSpaceMetadata() as $key => $cfg){
            $key = $prefix . $key;

            $metadata['MapasCulturais\Entities\Space'][$key] = $cfg;
        }
    }
}
