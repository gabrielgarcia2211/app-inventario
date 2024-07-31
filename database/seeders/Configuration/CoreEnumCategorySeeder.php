<?php

namespace Database\Seeders\Configuration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreEnumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'parent_id' => 1,
                'name' => 'Categorias',
                'code' => 'category',
                'description' => '',
                'is_father' => true,
                'status' => true,
                'childrens' => [
                    [
                        'name' => 'Pueblos',
                        'value1' =>  0.10,
                    ],
                    [
                        'name' => 'Ciudad A',
                        'value1' => 0.0,
                    ],
                    [
                        'name' => 'Ciudad B',
                        'value1' => 0.08,
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
                        'value1' => $child['value1'],
                        'status' => true
                    ]);
                }
            }
        }
    }
}
