<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $resources = [
            'page', 'article', 'category', 'tag', 'service',
            'banner', 'contact_message', 'menu', 'media', 'user',
            'company_profile', 'site_setting',
        ];

        $actions = ['view_any', 'view', 'create', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}_{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

        $superAdmin->syncPermissions(Permission::all());
        $admin->syncPermissions(Permission::where('name', 'not like', '%_user')->get());

        $editorResources = ['page', 'article', 'category', 'tag', 'media'];
        $editorPermissions = collect($editorResources)
            ->flatMap(fn (string $resource) => collect($actions)
                ->map(fn (string $action) => "{$action}_{$resource}"))
            ->all();
        $editor->syncPermissions(Permission::whereIn('name', $editorPermissions)->get());
    }
}
