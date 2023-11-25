<?php
namespace Database\Seeders;

use DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate(['permissions']);

        foreach ($this->getData() as $keyName => $name) {
            DB::table('permissions')->insert([
                'key_name'   => $keyName,
                'name'       => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }


    /**
     * Return the data to populate table.
     *
     * @return array
     */
    private function getData()
    {
        return [
             /*
             * Profile
             */
            'update.password' => 'Cambiar contraseña',
            'update.profile'  => 'Cambiar Perfil',

            /*
             * services
             */
            'view.services'   => 'Ver servicios',
            'create.services' => 'Agregar servicios',
            'edit.services'   => 'Editar servicios',
            'delete.services' => 'Eliminar servicios',

            /*
             * expenses
             */
            'view.expenses'   => 'Ver gastos',
            'create.expenses' => 'Agregar gastos',
            'edit.expenses'   => 'Editar gastos',
            'delete.expenses' => 'Eliminar gastos',

            /*
             * pagos
             */
            'view.payments'   => 'Ver tipos de pago',
            'create.payments' => 'asignar tipo de pago',
            'edit.payments'   => 'Editar tipos de pago',
            'delete.payments' => 'Eliminar tipos de pago',

            /*
             * permission
             */
            'view.permissions'   => 'Ver permisos',
            'create.permissions' => 'Agregar permisos',
            'edit.permissions'   => 'Editar permisos',
            'delete.permissions' => 'Eliminar permisos',

            /*
             * roles
             */
            'view.roles'   => 'Ver roles',
            'create.roles' => 'Agregar roles',
            'edit.roles'   => 'Editar roles',
            'delete.roles' => 'Eliminar roles',

            /*
             * Type expenses
             */
            'view.type_expenses'   => 'Ver tipos de gastos',
            'create.type_expenses' => 'Agregar tipos de gastos',
            'edit.type_expenses'   => 'Editar tipos de gastos',
            'delete.type_expenses' => 'Eliminar tipos de gastos',

            /*
             * Branches
             */
            'view.branches'   => 'Ver sucursales',
            'create.branches' => 'Agregar sucursales',
            'edit.branches'   => 'Editar sucursales',
            'delete.branches' => 'Eliminar sucursales',

            /*
             * Studies
             */
            'view.studies'   => 'Ver estudios',
            'create.studies' => 'Agregar estudios',
            'edit.studies'   => 'Editar estudios',
            'delete.studies' => 'Eliminar estudios',

            /*
             * Users
             */
            'view.users'   => 'Ver Usuarios',
            'create.users' => 'Agregar usuarios',

            /*
             * Statistics
             */
            'view.statistics'   => 'Ver estadísticas',
            
        ];
    }
}
