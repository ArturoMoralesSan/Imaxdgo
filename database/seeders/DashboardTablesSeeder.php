<?php

namespace Database\Seeders;

use App\Models\Permission;
use DB;

class DashboardTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate(['dashboard_sections', 'dashboard_submenus', 'dashboard_links']);

        $permissions = Permission::all('id', 'key_name')->pluck('id', 'key_name');

        foreach ($this->getData() as $i => $section) {
            $sectionId = DB::table('dashboard_sections')->insertGetId([
                'name'       => $section['name'],
                'tile'       => $section['tile'],
                'order'      => $i + 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            foreach ($section['submenus'] as $j => $submenu) {
                $submenuId = DB::table('dashboard_submenus')->insertGetId([
                    'name'       => $submenu['name'],
                    'icon'       => $submenu['icon'],
                    'order'      => $j + 1,
                    'section_id' => $sectionId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                foreach ($submenu['links'] as $k => $link) {
                    DB::table('dashboard_links')->insert([
                        'name'          => $link['name'],
                        'route'         => $link['route'],
                        'order'         => $k + 1,
                        'submenu_id'    => $submenuId,
                        'permission_id' => $permissions[$link['permission']],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
    }



    /**
     * Return the data to populate tables.
     *
     * @return array
     */
    private function getData()
    {
        return [
            [
                'name' => 'ACL',
                'tile' => 'lock.svg',
                'submenus' => [
                    [
                        'name' => 'Permisos',
                        'icon' => 'permissions.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar permisos',
                                'route'      => 'agregar-permisos',
                                'permission' => 'create.permissions'
                            ],
                            [
                                'name'       => 'Lista de permisos',
                                'route'      => 'permisos',
                                'permission' => 'view.permissions'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Roles',
                        'icon' => 'role.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar roles',
                                'route'      => 'agregar-roles',
                                'permission' => 'create.roles'
                            ],
                            [
                                'name'       => 'Lista de roles',
                                'route'      => 'roles',
                                'permission' => 'view.roles'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Usuarios',
                        'icon' => 'users.svg',
                        'links' => [
                            [
                                'name'       => 'Lista de usuarios',
                                'route'      => 'usuarios',
                                'permission' => 'view.users'
                            ]
                        ],
                    ],
                    
                ]
            ],
            [
                'name' => 'Servicios',
                'tile' => 'servicios.svg',
                'submenus' => [
                    [
                        'name' => 'Servicios',
                        'icon' => 'servicios.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar servicios',
                                'route'      => 'agregar-servicio',
                                'permission' => 'create.services'
                            ],
                            [
                                'name'       => 'Lista de servicios',
                                'route'      => 'servicios',
                                'permission' => 'view.services'
                            ],
                        ]
                    ],
                    [
                        'name' => 'Sucursales',
                        'icon' => 'sucursales.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar sucursales',
                                'route'      => 'agregar-sucursales',
                                'permission' => 'create.branches'
                            ],
                            [
                                'name'       => 'Lista de sucursales',
                                'route'      => 'sucursales',
                                'permission' => 'view.branches'
                            ],
                        ]
                    ],
                    [
                        'name' => 'estudios',
                        'icon' => 'estudios.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar estudios',
                                'route'      => 'agregar-estudios',
                                'permission' => 'create.studies'
                            ],
                            [
                                'name'       => 'Lista de estudios',
                                'route'      => 'estudios',
                                'permission' => 'view.studies'
                            ],
                        ]
                    ],
                    [
                        'name' => 'Tipos de pago',
                        'icon' => 'pagos.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar tipos de pago',
                                'route'      => 'agregar-pagos',
                                'permission' => 'create.payments'
                            ],
                            [
                                'name'       => 'Lista de tipos de pagos',
                                'route'      => 'pagos',
                                'permission' => 'view.payments'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Gastos',
                'tile' => 'gastos.svg',
                'submenus' => [
                    [
                        'name' => 'Gastos',
                        'icon' => 'gastos.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar gasto',
                                'route'      => 'agregar-gastos',
                                'permission' => 'create.expenses'
                            ],
                            [
                                'name'       => 'Lista de gastos',
                                'route'      => 'gastos',
                                'permission' => 'view.expenses'
                            ],
                        ]
                    ],
                    [
                        'name' => 'Tipo de gastos',
                        'icon' => 'tipo-gastos.svg',
                        'links' => [
                            [
                                'name'       => 'Agregar tipo de gasto',
                                'route'      => 'agregar-tipos-gastos',
                                'permission' => 'create.type_expenses'
                            ],
                            [
                                'name'       => 'Lista de tipo de gastos',
                                'route'      => 'tipos-gastos',
                                'permission' => 'view.type_expenses'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Indicadores',
                'tile' => 'indicadores.svg',
                'submenus' => [
                    [
                        'name' => 'Estadísticas',
                        'icon' => 'estadisticas.svg',
                        'links' => [
                            [
                                'name'       => 'Estadísticas de servicios',
                                'route'      => 'estadisticas-servicios',
                                'permission' => 'view.statistics'
                            ],
                            [
                                'name'       => 'Estadísticas de gastos',
                                'route'      => 'estadisticas-servicios',
                                'permission' => 'view.statistics'
                            ],
                        ]
                    ],
                ]
            ],
            
        ];
    }
}

