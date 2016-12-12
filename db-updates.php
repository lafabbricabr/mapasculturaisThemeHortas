<?php
use MapasCulturais\Entities\Space;
use MapasCulturais\Entities\Agent;
use MapasCulturais\Entities\Seal;
use MapasCulturais\Entities\SpaceSealRelation;
use MapasCulturais\Entities\AgentSealRelation;

$app = MapasCulturais\App::i();
$em = $app->em;
$conn = $em->getConnection();

return [
    'hor: import agents from file' => function() use($app, $em) {
        $rows = explode("\n", file_get_contents(__DIR__ . '/imports/agents_dre.csv'));
        $header = explode(',', $rows[0]);

        unset($rows[0]);

        foreach ($rows as $row) {
            $agent = new Agent($app->repo('User')->find(1));
            $row = explode(',', $row);
            foreach($row as $i => $col){
                $agent->$header[$i] = $col;
            }

            echo "Adding Agent \"$agent->name\"\n";

            $agent->En_Municipio = 'São Paulo';
            $agent->En_Estado = 'SP';

            $agent->endereco = $agent->En_Nome_Logradouro . ", " . $agent->En_Num . ", " . $agent->En_Bairro . ", " . $agent->En_CEP  . ", " . $agent->En_Municipio . ", " . $agent->En_Estado;
            $agent->terms['area'] = ['Educação'];
            $agent->owner = $app->repo('Agent')->find(1);
            $agent->nomeCompleto = $agent->name;

            $agent->save(true);
        }
    },
    'hor: import spaces from file' => function() use($app, $em){
        $app->em->flush();
        $from_to = [
            'CODESC'        => '',
            'TIPOESC'       => '',
            'NOMESC'        => 'name',
            'DRE'           => 'dre_vinc',
            'DIRETORIA'     => 'owner_temp',
            'SUBPREF'       => '',
            'CEU'           => '',
            'ENDERECO'      => 'En_Nome_Logradouro',
            'NUMERO'        => 'En_Num',
            'BAIRRO'        => 'En_Bairro',
            'CEP'           => 'En_CEP',
            'TEL1'          => 'telefone1',
            'TEL2'          => 'telefone2',
            'FAX'           => '',
            'SITUACAO'      => '',
            'CDIST'         => '',
            'DISTRITO'      => '',
            'SETOR'         => '',
            'CODSEE'        => '',
            'CODINEP'       => 'num_inep',
            'EH'            => '',
            'NOME_ANT'      => '',
            'T2D3D'         => '',
            'DTURNOS'       => '',
            'LATITUDE'      => 'lat_temp',
            'LONGITUDE'     => 'lon_temp',
            'REDE'          => '',
            'DATABASE'      => '',
            'TOTCLA'        => '',
            'TOTALU'        => 'num_reg_stu'
        ];

        $agent_from_to = [
            'PENHA'                     => 'DRE Penha',
            'BUTANTA'                   => 'DRE Butantã',
            'PIRITUBA'                  => 'DRE Pirituba',
            'ITAQUERA'                  => 'DRE Itaquera',
            'IPIRANGA'                  => 'DRE Ipiranga',
            'FREGUESIA/BRASILANDIA'     => 'DRE Freguesia/Brasilândia',
            'SANTO AMARO'               => 'DRE Santo Amaro',
            'CAMPO LIMPO'               => 'DRE Campo Limpo',
            'CAPELA DO SOCORRO'         => 'DRE Capela do Socorro',
            'GUAIANASES'                => 'DRE Guaianases',
            'SAO MIGUEL'                => 'DRE São Miguel',
            'SAO MATEUS'                => 'DRE São Mateus',
            'JACANA/TREMEMBE'           => 'DRE Jaçanã/Tremembé'
        ];

        $school_types = [
            'CECI'      => 6001,
            'CEI DIRET' => 6002,
            'CEI INDIR' => 6003,
            'CEU CEI'   => 6004,
            'CEU EMEI'  => 6005,
            'CR.P.CONV' => 6006,
            'EMEI'      => 6007,
            'CEMEI'     => 6008,
            'EMEBS'     => 6019,
            'EMEF'      => 6010,
            'CEU EMEF'  => 6011,
            'EMEFM'     => 6012,
                // 6013 => 'EJA Regular',
                // 6014 => 'EJS Modular',
            'CIEJA'     => 6015,
            'MOVA'      => 6016,
            'DIR EDUC'  => 6017,
            'E TECNICA' => 6018,
        ];

        $rows = explode("\n", file_get_contents(__DIR__ . '/imports/schools.csv'));

        $header = explode(',', $rows[0]);
        unset($rows[0]);

        foreach($rows as $i => $row) {
            $school = new Space;
            $row = str_getcsv($row);

            foreach ($row as $i => $col){
                if($col && !empty($from_to[$header[$i]]))
                    $school->$from_to[$header[$i]] = $col;
            }

            echo "\nAdding Space \"$school->name\"";

            $school->En_Municipio = 'São Paulo';
            $school->En_Estado = 'SP';

            $school->endereco = $school->En_Nome_Logradouro . ", " . $school->En_Num . ", " . $school->En_Bairro . ", " . $school->En_CEP  . ", " . $school->En_Municipio . ", " . $school->En_Estado;

            $text_to_coord = function($text){
                return substr($text, 0, 3) . '.' . substr($text, 3);
            };
            $school->lat_temp = $text_to_coord($school->lat_temp);
            $school->lon_temp = $text_to_coord($school->lon_temp);

            $school->terms['area'] = ['Educação'];
            $school->shortDescription = $school->name;

            $school->location = new MapasCulturais\Types\GeoPoint(floatval($school->lon_temp), floatval($school->lat_temp));

            $agent = $app->repo('Agent')->findOneBy(['name' => $agent_from_to[$school->owner_temp]]);

            $school->owner = $agent;
            $school->type = $app->getRegisteredEntityTypeById(
                'MapasCulturais\Entities\Space',
                $school_types[isset($school_types[$school->type_temp]) ? $school->type_temp : 'CECI']
            );

            $school->save(true);
            $em->clear();
        }
    },
    'hor: add default seal to all spaces' => function() use($app){

        $app->em->flush();

        $app->user = $app->repo('User')->find(1);
        $app->auth->authenticatedUser = $app->repo('User')->find(1);
        $owner_agent = $app->user->profile;
        $seal = $app->repo('Seal')->findOneBy(['name' => 'SME']);
        if (!$seal){
            $seal = new Seal;
            $seal->id = $app->em->getConnection()->fetchColumn("SELECT nextval('seal_id_seq')");
            $seal->name = "SME";
            $seal->shortDescription = 'Secretaria Municipal de Educação';
            $seal->longDescription = 'Secretaria Municipal de Educação';
            $seal->validPeriod = 0;
            $seal->agent = $owner_agent;
            $seal->owner = $owner_agent;
            $seal->_ownerId = $owner_agent->id;
            $seal->save(true);
        }

        $schools = $app->repo('Space')->findAll();

        foreach ($schools as $school) {
            $seal_relation = new SpaceSealRelation;
            $seal_relation->seal = $seal;
            $seal_relation->objectId = $school->id;
            $seal_relation->object_type = $school->entityClassName;
            $seal_relation->agent = $owner_agent;
            $seal_relation->owner = $school;
            $seal_relation->owner_relation = $owner_agent;
            echo "\nAdding seal \"$seal->name\" to space \"$school->name\"";
            $seal_relation->save();
        }
    },
    'hor: add default seal to all agents' => function() use($app){
        $app->em->flush();

        $app->user = $app->repo('User')->find(1);
        $app->auth->authenticatedUser = $app->repo('User')->find(1);
        $owner_agent = $app->user->profile;
        $seal = $app->repo('Seal')->findOneBy(['name' => 'SME']);

        if(!$seal){
            echo "Seal \"SME\" needs to be created before this db-update run";
            return false;
        }
        $agents = $app->repo('Agent')->findAll();
        foreach ($agents as $agent) {
            $seal_relation = new AgentSealRelation;
            $seal_relation->seal = $seal;
            $seal_relation->objectId = $agent->id;
            $seal_relation->object_type = $agent->entityClassName;
            $seal_relation->agent = $owner_agent;
            $seal_relation->owner = $agent;
            $seal_relation->owner_relation = $owner_agent;
            echo "\nAdding seal \"$seal->name\" to agent \"$agent->name\"";
            $seal_relation->save();
        }
    },
    'hor: remove default application seal' => function() use($app){
        $seal = $app->repo('Seal')->findOneBy(['name' => 'Selo Mapas']);
        if ($seal)
            $seal->delete();
    }
];