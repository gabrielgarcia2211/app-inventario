<?php

namespace Database\Seeders\Configuration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreEnumClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'parent_id' => 1,
                'name' => 'Clientes',
                'code' => 'clients',
                'description' => '',
                'is_father' => true,
                'status' => true,
                'childrens' => [
                    [
                        'name' => 'ALBENI',
                    ],
                    [
                        'name' => 'ALFONSO',
                    ],
                    [
                        'name' => 'ALID',
                    ],
                    [
                        'name' => 'ALIRIO MIRANDA',
                    ],
                    [
                        'name' => 'ANA',
                    ],
                    [
                        'name' => 'ANGELA',
                    ],
                    [
                        'name' => 'AMPARO',
                    ],
                    [
                        'name' => 'ANDRES SANTIAGO',
                    ],
                    [
                        'name' => 'BELEN',
                    ],
                    [
                        'name' => 'BLANCA JAIMES',
                    ],
                    [
                        'name' => 'BLANCA LIZCANO',
                    ],
                    [
                        'name' => 'CAMILA',
                    ],
                    [
                        'name' => 'CARLOS FONSECA',
                    ],
                    [
                        'name' => 'CARLOS MATIZ',
                    ],
                    [
                        'name' => 'CAROLINA CATATUMBO',
                    ],
                    [
                        'name' => 'CAROLINA_MORA',
                    ],
                    [
                        'name' => 'CELINA VILLAMIZAR',
                    ],
                    [
                        'name' => 'CHICHO',
                    ],
                    [
                        'name' => 'DAVID',
                    ],
                    [
                        'name' => 'ELIDA ESPERANZA_DUQUE',
                    ],
                    [
                        'name' => 'ELIZABETH',
                    ],
                    [
                        'name' => 'ENEIL JEFFERSON',
                    ],
                    [
                        'name' => 'FANNY',
                    ],
                    [
                        'name' => 'FREDDY_SANCHEZ',
                    ],
                    [
                        'name' => 'GEOVANNY',
                    ],
                    [
                        'name' => 'GEOVANNY_ANTONIA',
                    ],
                    [
                        'name' => 'GINNA',
                    ],
                    [
                        'name' => 'JACKELYN',
                    ],
                    [
                        'name' => 'JAVIER ARDILA',
                    ],
                    [
                        'name' => 'JHON EDUAR',
                    ],
                    [
                        'name' => 'JOSE_GOMEZ',
                    ],
                    [
                        'name' => 'JOSE RODRIGUEZ',
                    ],
                    [
                        'name' => 'JOSEFA',
                    ],
                    [
                        'name' => 'KAREN',
                    ],
                    [
                        'name' => 'KELLY',
                    ],
                    [
                        'name' => 'LEONIDAS TORRES',
                    ],
                    [
                        'name' => 'LILIANA',
                    ],
                    [
                        'name' => 'LORENA SUAREZ',
                    ],
                    [
                        'name' => 'LUIS TORRES',
                    ],
                    [
                        'name' => 'MARCOS_TARAZONA',
                    ],
                    [
                        'name' => 'MARIA_TIZOI',
                    ],
                    [
                        'name' => 'MARIO_LOPEZ',
                    ],
                    [
                        'name' => 'MERCEDES',
                    ],
                    [
                        'name' => 'MIRIAM',
                    ],
                    [
                        'name' => 'MAURICIO',
                    ],
                    [
                        'name' => 'PAOLA_RIVAS',
                    ],
                    [
                        'name' => 'PALACIO',
                    ],
                    [
                        'name' => 'RAUL',
                    ],
                    [
                        'name' => 'REYNA',
                    ],
                    [
                        'name' => 'ROSA PARADA',
                    ],
                    [
                        'name' => 'RUBEN',
                    ],
                    [
                        'name' => 'RUTH GLORIA',
                    ],
                    [
                        'name' => 'SANDRA_MORALES_JANNER',
                    ],
                    [
                        'name' => 'SAIRE',
                    ],
                    [
                        'name' => 'SINDY_KATERINE_PIO',
                    ],
                    [
                        'name' => 'TAVO',
                    ],
                    [
                        'name' => 'WILLIAM',
                    ],
                    [
                        'name' => 'YAMILE',
                    ],
                    [
                        'name' => 'YURI CAROLINA_SARMIENTO',
                    ],
                    [
                        'name' => 'YURI OJEDA',
                    ],
                    [
                        'name' => 'YEPE',
                    ],
                ],
            ],
        ];

        foreach ($items as $key => $item) {
            $temp_children = null;
            if (isset($item['childrens'])) {
                $temp_children = $item['childrens'];
                unset($item['childrens']);
            }

            # Creamos el padre y vinculamos los hijos
            $father_id = DB::table('enum_options')->insertGetId($item);
            if (!empty($temp_children)) {
                foreach ($temp_children as $child) {
                    DB::table('enum_options')->insert([
                        'parent_id' => $father_id,
                        'name' => $child['name'],
                        'status' => true
                    ]);
                }
            }
        }
    }
}
