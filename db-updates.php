<?php
use MapasCulturais\Entities\Space;
use MapasCulturais\Entities\Agent;

$app = MapasCulturais\App::i();
$em = $app->em;
$conn = $em->getConnection();

return [
    'import agents from file' => function() use($app, $em) {
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
            $agent->owner = $app->repo('Agent')->find(1);
            $agent->nomeCompleto = $agent->name;

            $agent->save();
        }
        $em->flush();
    },
    'import spaces from file' => function() use($app, $em){
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

            // echo "\n".$school->lon_temp."=".floatval($school->lon_temp)."\n";
            // echo $school->lat_temp."=".floatval($school->lat_temp)."\n";

            $school->location = new MapasCulturais\Types\GeoPoint(floatval($school->lon_temp), floatval($school->lat_temp));

            $agent = $app->repo('Agent')->findOneBy(['name' => $agent_from_to[$school->owner_temp]]);

            $school->owner = $agent;
            $school->type = 2;

            $school->save(true);
            $em->clear();
        }
    }
];