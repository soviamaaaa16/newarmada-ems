<?php

// ============================================
// FILE: app/Database/Seeds/ShieldSetupSeeder.php
// ============================================

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShieldSetupSeeder extends Seeder
{
    public function run()
    {
        echo "🚀 Setting up Shield (Custom Structure)...\n\n";

        // ===================================
        // 1. CLEAR EXISTING DATA (OPTIONAL)
        // ===================================
        // Uncomment jika mau reset
        // $this->db->table('auth_groups_users')->truncate();
        // $this->db->table('auth_permissions_users')->truncate();

        // ===================================
        // 2. CREATE DEFAULT USERS
        // ===================================
        echo "👤 Creating Default Users...\n";

        $users = model('UserModel');

        // User 1: Super Admin
        $adminExists = $this->db->table('auth_identities')
            ->where('secret', 'admin@example.com')
            ->get()
            ->getRow();

        if (!$adminExists) {
            $admin = new \CodeIgniter\Shield\Entities\User([
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'admin123',
            ]);

            $users->save($admin);
            $adminId = $users->getInsertID();

            // Assign group
            $this->db->table('auth_groups_users')->insert([
                'user_id' => $adminId,
                'group' => 'superadmin',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Assign ALL permissions
            $allPermissions = $this->getAllPermissions();
            foreach ($allPermissions as $perm) {
                $this->db->table('auth_permissions_users')->insert([
                    'user_id' => $adminId,
                    'permission' => $perm,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            echo "  ✓ Super Admin created:\n";
            echo "    Email: admin@example.com\n";
            echo "    Password: admin123\n";
            echo "    Group: superadmin\n";
            echo "    Permissions: ALL\n";
        } else {
            echo "  ⚠ Admin already exists\n";
        }

        // User 2: Regular Admin
        $admin2Exists = $this->db->table('auth_identities')
            ->where('secret', 'manager@example.com')
            ->get()
            ->getRow();

        if (!$admin2Exists) {
            $admin2 = new \CodeIgniter\Shield\Entities\User([
                'username' => 'manager',
                'email' => 'manager@example.com',
                'password' => 'manager123',
            ]);

            $users->save($admin2);
            $admin2Id = $users->getInsertID();

            // Assign group
            $this->db->table('auth_groups_users')->insert([
                'user_id' => $admin2Id,
                'group' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Assign admin permissions (most but not all)
            $adminPermissions = $this->getAdminPermissions();
            foreach ($adminPermissions as $perm) {
                $this->db->table('auth_permissions_users')->insert([
                    'user_id' => $admin2Id,
                    'permission' => $perm,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            echo "  ✓ Admin created:\n";
            echo "    Email: manager@example.com\n";
            echo "    Password: manager123\n";
            echo "    Group: admin\n";
        } else {
            echo "  ⚠ Manager already exists\n";
        }

        // User 3: Regular User
        $userExists = $this->db->table('auth_identities')
            ->where('secret', 'user@example.com')
            ->get()
            ->getRow();

        if (!$userExists) {
            $user = new \CodeIgniter\Shield\Entities\User([
                'username' => 'user',
                'email' => 'user@example.com',
                'password' => 'user123',
            ]);

            $users->save($user);
            $userId = $users->getInsertID();

            // Assign group
            $this->db->table('auth_groups_users')->insert([
                'user_id' => $userId,
                'group' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Assign basic user permissions
            $userPermissions = $this->getUserPermissions();
            foreach ($userPermissions as $perm) {
                $this->db->table('auth_permissions_users')->insert([
                    'user_id' => $userId,
                    'permission' => $perm,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            echo "  ✓ Regular User created:\n";
            echo "    Email: user@example.com\n";
            echo "    Password: user123\n";
            echo "    Group: user\n";
        } else {
            echo "  ⚠ User already exists\n";
        }

        echo "\n";

        // ===================================
        // 3. SUMMARY
        // ===================================
        echo "✅ Shield setup completed!\n\n";
        echo "📊 Summary:\n";
        echo "  • Total Users: " . $this->db->table('users')->countAllResults() . "\n";
        echo "  • Group Assignments: " . $this->db->table('auth_groups_users')->countAllResults() . "\n";
        echo "  • Permission Assignments: " . $this->db->table('auth_permissions_users')->countAllResults() . "\n";
        echo "\n";
        echo "🎉 You can now login with:\n\n";
        echo "  SUPER ADMIN:\n";
        echo "    Email: admin@example.com\n";
        echo "    Password: admin123\n\n";
        echo "  ADMIN:\n";
        echo "    Email: manager@example.com\n";
        echo "    Password: manager123\n\n";
        echo "  USER:\n";
        echo "    Email: user@example.com\n";
        echo "    Password: user123\n";
        echo "\n";
    }

    /**
     * Get all available permissions
     */
    private function getAllPermissions(): array
    {
        return [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.ban',
            'users.manage-permissions',

            // File Management
            'files.upload',
            'files.download',
            'files.delete',
            'files.manage-all',

            // Folder Management
            'folders.create',
            'folders.delete',
            'folders.manage-all',

            // Trash Management
            'trash.view',
            'trash.restore',
            'trash.empty',

            // Admin Panel
            'admin.access',
            'admin.settings',

            // Search
            'search.advanced',
        ];
    }

    /**
     * Get admin permissions (most but not manage-permissions)
     */
    private function getAdminPermissions(): array
    {
        return [
            'users.view',
            'users.create',
            'users.edit',
            'users.ban',
            'files.upload',
            'files.download',
            'files.delete',
            'files.manage-all',
            'folders.create',
            'folders.delete',
            'folders.manage-all',
            'trash.view',
            'trash.restore',
            'trash.empty',
            'admin.access',
            'search.advanced',
        ];
    }

    /**
     * Get basic user permissions
     */
    private function getUserPermissions(): array
    {
        return [
            'files.upload',
            'files.download',
            'files.delete',
            'folders.create',
            'folders.delete',
            'trash.view',
            'trash.restore',
        ];
    }
}
