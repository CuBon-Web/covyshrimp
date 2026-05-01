<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Support\RbacPermissions;

class SeedRbacRoleTemplates extends Migration
{
    public function up()
    {
        $now = now();

        // Ensure new permission slugs exist before seeding templates.
        foreach (RbacPermissions::all() as $slug => $name) {
            $exists = DB::table('permissions')->where('slug', $slug)->first();
            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $name,
                    'slug' => $slug,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $permissionMap = DB::table('permissions')->pluck('id', 'slug')->toArray();

        $viewerSlugs = [];
        foreach (array_keys(RbacPermissions::all()) as $slug) {
            if (substr($slug, -5) === '.view') {
                $viewerSlugs[] = $slug;
            }
        }
        $viewerSlugs[] = 'dashboard.view';

        $editorSlugs = array_unique(array_merge($viewerSlugs, [
            'product.create', 'product.update',
            'blog.create', 'blog.update',
            'service.create', 'service.update',
            'project.create', 'project.update',
            'solution.create', 'solution.update',
            'pagecontent.create', 'pagecontent.update',
            'bannerads.create', 'bannerads.update',
            'review.create', 'review.update',
            'message.update',
            'customer.update',
            'language.update',
        ]));

        $operatorSlugs = [
            'dashboard.view',
            'bill.view', 'bill.update',
            'message.view', 'message.update',
            'customer.view', 'customer.update',
            'review.view', 'review.update',
            'service.view',
            'product.view',
        ];

        $templates = [
            ['name' => 'Viewer', 'slug' => 'viewer', 'permission_slugs' => $viewerSlugs],
            ['name' => 'Editor', 'slug' => 'editor', 'permission_slugs' => $editorSlugs],
            ['name' => 'Operator', 'slug' => 'operator', 'permission_slugs' => $operatorSlugs],
        ];

        foreach ($templates as $template) {
            $exists = DB::table('roles')->where('slug', $template['slug'])->first();
            if ($exists) {
                // Respect existing custom role config.
                continue;
            }

            $roleId = DB::table('roles')->insertGetId([
                'name' => $template['name'],
                'slug' => $template['slug'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $permissionIds = [];
            foreach ($template['permission_slugs'] as $slug) {
                if (isset($permissionMap[$slug])) {
                    $permissionIds[] = (int) $permissionMap[$slug];
                }
            }
            $permissionIds = array_values(array_unique($permissionIds));

            foreach ($permissionIds as $permissionId) {
                DB::table('role_permission')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down()
    {
        // Keep seeded template roles as baseline RBAC data.
    }
}
