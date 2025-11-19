<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Usuarios
        Permission::create([
            'name' => 'seguridad.users.index',
            'group_name' => 'Usuarios',
            'nombre' => 'Ver Lista Usuarios',
            'description' => 'Listar Usuarios de la Aplicación',
            'estado' => true,
        ]);
        Permission::create([
            'name' => 'seguridad.users.create',
            'group_name' => 'Usuarios',
            'nombre' => 'Crear Usuarios',
            'description' => 'Crear Usuarios de la Aplicación',
            'estado' => true,
        ]);
        Permission::create([
            'name' => 'seguridad.users.edit',
            'group_name' => 'Usuarios',
            'nombre' => 'Editar Usuarios',
            'description' => 'Editar Usuarios de la Aplicación',
            'estado' => true,
        ]);
        Permission::create([
            'name' => 'seguridad.users.show',
            'group_name' => 'Usuarios',
            'nombre' => 'Ver Detalle del Usuarios',
            'description' => 'Ver Detalle del Usuarios de la Aplicación',
            'estado' => true,
        ]);
        Permission::create([
            'name' => 'seguridad.users.destroy',
            'group_name' => 'Usuarios',
            'nombre' => 'Elimnar Usuarios',
            'description' => 'Elimnar Usuarios de la Aplicación',
            'estado' => true,
        ]);

        //Permisos

        Permission::create([
            'name' => 'seguridad.permiso.index',
            'group_name' => 'Permisos',
            'nombre' => 'Ver Lista Permisos',
            'description' => 'Listar Permisos de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.permiso.create',
            'group_name' => 'Permisos',
            'nombre' => 'Crear Permisos',
            'description' => 'Crear Permisos de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.permiso.edit',
            'group_name' => 'Permisos',
            'nombre' => 'Editar Permisos',
            'description' => 'Editar Permisos de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.permiso.show',
            'group_name' => 'Permisos',
            'nombre' => 'Ver Detalle del Permisos',
            'description' => 'Ver Detalle del Permisos de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.permiso.destroy',
            'group_name' => 'Permisos',
            'nombre' => 'Eliminar Permisos',
            'description' => 'Eliminar Permisos de la Aplicación',
            'estado' => true,
        ]);

        //Roles

        Permission::create([
            'name' => 'seguridad.roles.index',
            'group_name' => 'Roles',
            'nombre' => 'Ver Lista Roles',
            'description' => 'Listar Roles de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.roles.create',
            'group_name' => 'Roles',
            'nombre' => 'Crear Roles',
            'description' => 'Crear Roles de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.roles.edit',
            'group_name' => 'Roles',
            'nombre' => 'Editar Roles',
            'description' => 'Editar Roles de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.roles.show',
            'group_name' => 'Roles',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.roles.destroy',
            'group_name' => 'Roles',
            'nombre' => 'Eliminar Roles',
            'description' => 'Eliminar Roles de la Aplicación',
            'estado' => true,
        ]);

        //Empresas

        Permission::create([
            'name' => 'seguridad.empresa.index',
            'group_name' => 'Empresas',
            'nombre' => 'Ver Lista Empresas',
            'description' => 'Listar Empresas de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.empresa.create',
            'group_name' => 'Empresas',
            'nombre' => 'Crear Empresas',
            'description' => 'Crear Empresas de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.empresa.edit',
            'group_name' => 'Empresas',
            'nombre' => 'Editar Empresas',
            'description' => 'Editar Empresas de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.empresa.show',
            'group_name' => 'Empresas',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'seguridad.empresa.destroy',
            'group_name' => 'Empresas',
            'nombre' => 'Eliminar Empresas',
            'description' => 'Eliminar Empresas de la Aplicación',
            'estado' => true,
        ]);

        //Cochera

        Permission::create([
            'name' => 'operacion.cochera.index',
            'group_name' => 'Cocheras',
            'nombre' => 'Ver Lista Cocheras',
            'description' => 'Listar Cocheras de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.cochera.create',
            'group_name' => 'Cocheras',
            'nombre' => 'Crear Cocheras',
            'description' => 'Crear Cocheras de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.cochera.edit',
            'group_name' => 'Cocheras',
            'nombre' => 'Editar Cocheras',
            'description' => 'Editar Cocheras de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.cochera.show',
            'group_name' => 'Cocheras',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.cochera.destroy',
            'group_name' => 'Cocheras',
            'nombre' => 'Eliminar Cocheras',
            'description' => 'Eliminar Cocheras de la Aplicación',
            'estado' => true,
        ]);

        //Usuarios x Cochera

        Permission::create([
            'name' => 'operacion.usuario_cocheras.index',
            'group_name' => 'Cocheras',
            'nombre' => 'Ver Lista Cocheras',
            'description' => 'Listar Cocheras de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.usuario_cocheras.create',
            'group_name' => 'Cocheras',
            'nombre' => 'Crear Cocheras',
            'description' => 'Crear Cocheras de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.usuario_cocheras.edit',
            'group_name' => 'Cocheras',
            'nombre' => 'Editar Cocheras',
            'description' => 'Editar Cocheras de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.usuario_cocheras.show',
            'group_name' => 'Cocheras',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.usuario_cocheras.destroy',
            'group_name' => 'Cocheras',
            'nombre' => 'Eliminar Cocheras',
            'description' => 'Eliminar Cocheras de la Aplicación',
            'estado' => true,
        ]);

        //Tipo_Vehiculo

        Permission::create([
            'name' => 'operacion.tipo_vehiculo.index',
            'group_name' => 'Tipo Vehiculo',
            'nombre' => 'Ver Lista Tipo Vehiculo',
            'description' => 'Listar Tipo Vehiculo de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.tipo_vehiculo.create',
            'group_name' => 'Tipo Vehiculo',
            'nombre' => 'Crear Tipo Vehiculo',
            'description' => 'Crear Tipo Vehiculo de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.tipo_vehiculo.edit',
            'group_name' => 'Tipo Vehiculo',
            'nombre' => 'Editar Tipo Vehiculo',
            'description' => 'Editar Tipo Vehiculo de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.tipo_vehiculo.show',
            'group_name' => 'Tipo Vehiculo',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.tipo_vehiculo.destroy',
            'group_name' => 'Tipo Vehiculo',
            'nombre' => 'Eliminar Tipo Vehiculo',
            'description' => 'Eliminar Tipo Vehiculo de la Aplicación',
            'estado' => true,
        ]);

        //Espacios Parqueo

        Permission::create([
            'name' => 'operacion.espacios_parqueo.index',
            'group_name' => 'Espacio Parqueo',
            'nombre' => 'Ver Lista Espacio Parqueo',
            'description' => 'Listar Espacio Parqueo de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.espacios_parqueo.create',
            'group_name' => 'Espacio Parqueo',
            'nombre' => 'Crear Espacio Parqueo',
            'description' => 'Crear Espacio Parqueo de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.espacios_parqueo.edit',
            'group_name' => 'Espacio Parqueo',
            'nombre' => 'Editar Espacio Parqueo',
            'description' => 'Editar Espacio Parqueo de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.espacios_parqueo.show',
            'group_name' => 'Espacio Parqueo',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'operacion.espacios_parqueo.destroy',
            'group_name' => 'Espacio Parqueo',
            'nombre' => 'Eliminar Espacio Parqueo',
            'description' => 'Eliminar Espacio Parqueo de la Aplicación',
            'estado' => true,
        ]);

        //Tarifas

        Permission::create([
            'name' => 'gestion.tarifa.index',
            'group_name' => 'Tarifa',
            'nombre' => 'Ver Lista Tarifa',
            'description' => 'Listar Tarifa de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.tarifa.create',
            'group_name' => 'Tarifa',
            'nombre' => 'Crear Tarifa',
            'description' => 'Crear Tarifa de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.tarifa.edit',
            'group_name' => 'Tarifa',
            'nombre' => 'Editar Tarifa',
            'description' => 'Editar Tarifa de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.tarifa.show',
            'group_name' => 'Tarifa',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.tarifa.destroy',
            'group_name' => 'Tarifa',
            'nombre' => 'Eliminar Tarifa',
            'description' => 'Eliminar Tarifa de la Aplicación',
            'estado' => true,
        ]);

        //Marcacion

        Permission::create([
            'name' => 'gestion.marcacion.index',
            'group_name' => 'marcacion',
            'nombre' => 'Ver Lista marcacion',
            'description' => 'Listar marcacion de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.marcacion.create',
            'group_name' => 'marcacion',
            'nombre' => 'Crear marcacion',
            'description' => 'Crear marcacion de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.marcacion.edit',
            'group_name' => 'marcacion',
            'nombre' => 'Editar marcacion',
            'description' => 'Editar marcacion de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.marcacion.show',
            'group_name' => 'marcacion',
            'nombre' => 'Ver Detalle del Rol',
            'description' => 'Ver Detalle del Rol de la Aplicación',
            'estado' => true,
        ]);
        
        Permission::create([
            'name' => 'gestion.marcacion.destroy',
            'group_name' => 'marcacion',
            'nombre' => 'Eliminar marcacion',
            'description' => 'Eliminar marcacion de la Aplicación',
            'estado' => true,
        ]);
        


        $adminRole = Role::create(['name' => 'Admin']);
        $gerenteRole = Role::create(['name' => 'Gerente']);

        $adminRole->givePermissionTo([
            'seguridad.users.index',
            'seguridad.users.create',
            'seguridad.users.edit',
            'seguridad.users.show',
            'seguridad.users.destroy',  
            'seguridad.permiso.index',
            'seguridad.permiso.create',
            'seguridad.permiso.edit',
            'seguridad.permiso.show',
            'seguridad.permiso.destroy', 
            'seguridad.roles.index',
            'seguridad.roles.create',
            'seguridad.roles.edit',
            'seguridad.roles.show',
            'seguridad.roles.destroy',
            'seguridad.empresa.index',
            'seguridad.empresa.create',
            'seguridad.empresa.edit',
            'seguridad.empresa.show',
            'seguridad.empresa.destroy',
            'operacion.cochera.index',
            'operacion.cochera.create',
            'operacion.cochera.edit',
            'operacion.cochera.show',
            'operacion.cochera.destroy',
            'operacion.usuario_cocheras.index',
            'operacion.usuario_cocheras.create',
            'operacion.usuario_cocheras.edit',
            'operacion.usuario_cocheras.show',
            'operacion.usuario_cocheras.destroy',
            'operacion.tipo_vehiculo.index',
            'operacion.tipo_vehiculo.create',
            'operacion.tipo_vehiculo.edit',
            'operacion.tipo_vehiculo.show',
            'operacion.tipo_vehiculo.destroy',
            'operacion.espacios_parqueo.index',
            'operacion.espacios_parqueo.create',
            'operacion.espacios_parqueo.edit',
            'operacion.espacios_parqueo.show',
            'operacion.espacios_parqueo.destroy',
            'gestion.tarifa.index',
            'gestion.tarifa.create',
            'gestion.tarifa.edit',
            'gestion.tarifa.show',
            'gestion.tarifa.destroy',
            'gestion.marcacion.index',
            'gestion.marcacion.create',
            'gestion.marcacion.edit',
            'gestion.marcacion.show',
            'gestion.marcacion.destroy'
        ]);

        $gerenteRole->givePermissionTo([
            'seguridad.users.index',
            'seguridad.users.create',
            'seguridad.users.edit',
            'seguridad.users.show',
            'operacion.cochera.index',
            'operacion.cochera.create',
            'operacion.cochera.edit',
            'operacion.cochera.show',
            'operacion.cochera.destroy',
            'operacion.usuario_cocheras.index',
            'operacion.usuario_cocheras.create',
            'operacion.usuario_cocheras.edit',
            'operacion.usuario_cocheras.show',
            'operacion.usuario_cocheras.destroy',
            'operacion.tipo_vehiculo.index',
            'operacion.tipo_vehiculo.create',
            'operacion.tipo_vehiculo.edit',
            'operacion.tipo_vehiculo.show',
            'operacion.tipo_vehiculo.destroy',
            'operacion.espacios_parqueo.index',
            'operacion.espacios_parqueo.create',
            'operacion.espacios_parqueo.edit',
            'operacion.espacios_parqueo.show',
            'operacion.espacios_parqueo.destroy',
            'gestion.tarifa.index',
            'gestion.tarifa.create',
            'gestion.tarifa.edit',
            'gestion.tarifa.show',
            'gestion.tarifa.destroy',
            'gestion.marcacion.index',
            'gestion.marcacion.create',
            'gestion.marcacion.edit',
            'gestion.marcacion.show',
            'gestion.marcacion.destroy'
        ]);

        $user =  User::find(1);
        $user->assignRole('Admin');
        
        $user2 =  User::find(2);
        $user2->assignRole('Admin');
        
        $user3 =  User::find(3);
        $user3->assignRole('Gerente');
    }
}
