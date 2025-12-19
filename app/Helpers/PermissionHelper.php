<?php

namespace App\Helpers;

class PermissionHelper
{
    /**
     * Get database instance
     */
    private static function db()
    {
        return \Config\Database::connect();
    }

    /**
     * Get available groups
     */
    public static function getAvailableGroups()
    {
        return [
            (object) [
                'name' => 'superadmin',
                'title' => 'Super Administrator',
                'description' => 'Complete control of the site',
            ],
            (object) [
                'name' => 'admin',
                'title' => 'Administrator',
                'description' => 'Site administration access',
            ],
            (object) [
                'name' => 'user',
                'title' => 'Regular User',
                'description' => 'Standard user access (view only)',
            ],
        ];
    }

    /**
     * Get available permissions 
     */
    public static function getAvailablePermissions()
    {
        return [
            // User Management
            (object) ['name' => 'users.view', 'description' => 'View users list'],
            (object) ['name' => 'users.create', 'description' => 'Create new users'],
            (object) ['name' => 'users.edit', 'description' => 'Edit user information'],
            (object) ['name' => 'users.delete', 'description' => 'Delete users'],
            (object) ['name' => 'users.ban', 'description' => 'Ban/Unban users'],
            (object) ['name' => 'users.manage-permissions', 'description' => 'Manage user permissions'],

            // File Management
            (object) ['name' => 'files.view', 'description' => 'View files'],
            (object) ['name' => 'files.upload', 'description' => 'Upload files'],
            (object) ['name' => 'files.download', 'description' => 'Download files'],
            (object) ['name' => 'files.delete', 'description' => 'Delete files'],
            (object) ['name' => 'files.manage-all', 'description' => 'Manage all users files'],

            // Folder Management
            (object) ['name' => 'folders.view', 'description' => 'View folders'],
            (object) ['name' => 'folders.create', 'description' => 'Create folders'],
            (object) ['name' => 'folders.delete', 'description' => 'Delete folders'],
            (object) ['name' => 'folders.manage-all', 'description' => 'Manage all users folders'],

            // Trash Management
            (object) ['name' => 'trash.view', 'description' => 'View trash'],
            (object) ['name' => 'trash.restore', 'description' => 'Restore from trash'],
            (object) ['name' => 'trash.empty', 'description' => 'Permanently delete from trash'],

            // Admin Panel
            (object) ['name' => 'admin.access', 'description' => 'Access admin panel'],
            (object) ['name' => 'admin.settings', 'description' => 'Manage system settings'],

            // Search
            (object) ['name' => 'search.basic', 'description' => 'Use basic search features'],
            (object) ['name' => 'search.advanced', 'description' => 'Use advanced search features'],
        ];
    }

    /**
     * Get permission IDs from database by permission names
     */
    public static function getPermissionIdsByNames(array $permissionNames)
    {
        // Karena permission di tabel emsauth_permissions_users sudah berisi nama permission
        // kita tidak perlu query ke tabel emsauth_permissions
        // Return array permission names langsung
        return $permissionNames;
    }

    /**
     * Get default permission IDs for each role from database
     */
    public static function getDefaultPermissionIds($role)
    {
        // Return permission names langsung, karena kolom 'permission' di 
        // emsauth_permissions_users sudah berisi nama permission
        return self::getDefaultPermissions($role);
    }

    /**
     * Get default permissions for each role (permission names)
     */
    public static function getDefaultPermissions($role)
    {
        $permissions = [
            'superadmin' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.delete',
                'users.ban',
                'users.manage-permissions',
                'files.view',
                'files.upload',
                'files.download',
                'files.delete',
                'files.manage-all',
                'folders.view',
                'folders.create',
                'folders.delete',
                'folders.manage-all',
                'trash.view',
                'trash.restore',
                'trash.empty',
                'admin.access',
                'admin.settings',
                'search.basic',
                'search.advanced',
            ],

            'admin' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.ban',
                'files.view',
                'files.upload',
                'files.download',
                'files.delete',
                'files.manage-all',
                'folders.view',
                'folders.create',
                'folders.delete',
                'folders.manage-all',
                'trash.view',
                'trash.restore',
                'trash.empty',
                'admin.access',
                'search.basic',
                'search.advanced',
            ],

            'user' => [
                // User role: View only - no special permissions
                'files.view',
                'trash.view',
            ],
        ];

        return $permissions[$role] ?? [];
    }

    /**
     * Insert permissions for a user when created/updated
     * 
     * @param int $userId User ID
     * @param string $role User role (superadmin, admin, user)
     * @return bool
     */
    public static function assignDefaultPermissions($userId, $role)
    {
        try {
            $db = self::db();

            // Get permission names for the role
            $permissions = self::getDefaultPermissions($role);

            // Delete existing permissions
            $db->table('auth_permissions_users')
                ->where('user_id', $userId)
                ->delete();

            // Insert new permissions
            if (!empty($permissions)) {
                $data = [];
                foreach ($permissions as $permission) {
                    $data[] = [
                        'user_id' => $userId,
                        'permission' => $permission, // Langsung simpan nama permission
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                }
                $db->table('auth_permissions_users')->insertBatch($data);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Failed to assign permissions: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Assign specific permissions to a user
     * 
     * @param int $userId User ID
     * @param array $permissions Array of permission names (bukan IDs)
     * @return bool
     */
    public static function assignPermissions($userId, array $permissions)
    {
        try {
            $db = self::db();

            // Delete existing permissions
            $db->table('auth_permissions_users')
                ->where('user_id', $userId)
                ->delete();

            // Insert new permissions
            if (!empty($permissions)) {
                $data = [];
                foreach ($permissions as $permission) {
                    $data[] = [
                        'user_id' => $userId,
                        'permission' => $permission, // Langsung simpan nama permission
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                }
                $db->table('auth_permissions_users')->insertBatch($data);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Failed to assign permissions: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user permission names from database
     * 
     * @param int $userId User ID
     * @return array Array of permission names
     */
    public static function getUserPermissions($userId)
    {
        $db = self::db();

        // Langsung ambil dari kolom 'permission' yang sudah berisi nama permission
        $result = $db->table('auth_permissions_users')
            ->select('permission')
            ->where('user_id', $userId)
            ->get()
            ->getResult();

        return array_column($result, 'permission');
    }

    /**
     * Get user permission names from database (alias)
     * Sama dengan getUserPermissions karena permission sudah berisi nama
     * 
     * @param int $userId User ID
     * @return array Array of permission names
     */
    public static function getUserPermissionIds($userId)
    {
        // Return sama dengan getUserPermissions karena kolom permission 
        // sudah berisi nama permission, bukan ID
        return self::getUserPermissions($userId);
    }

    /**
     * Check if user has permission
     * This checks from DATABASE, not from default role permissions
     * 
     * @param object $user User object with id property
     * @param string $permission Permission name to check
     * @return bool
     */
    public static function hasPermission($user, $permission)
    {
        if (!$user || !isset($user->id)) {
            return false;
        }

        // Superadmin always has all permissions
        if (isset($user->role) && $user->role === 'superadmin') {
            return true;
        }

        // Get user's actual permissions from database
        $userPermissions = self::getUserPermissions($user->id);

        return in_array($permission, $userPermissions);
    }

    /**
     * Check if user has any of the given permissions
     * 
     * @param object $user User object
     * @param array $permissions Array of permission names
     * @return bool
     */
    public static function hasAnyPermission($user, array $permissions)
    {
        if (!$user || !isset($user->id)) {
            return false;
        }

        // Superadmin always has all permissions
        if (isset($user->role) && $user->role === 'superadmin') {
            return true;
        }

        $userPermissions = self::getUserPermissions($user->id);

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     * 
     * @param object $user User object
     * @param array $permissions Array of permission names
     * @return bool
     */
    public static function hasAllPermissions($user, array $permissions)
    {
        if (!$user || !isset($user->id)) {
            return false;
        }

        // Superadmin always has all permissions
        if (isset($user->role) && $user->role === 'superadmin') {
            return true;
        }

        $userPermissions = self::getUserPermissions($user->id);

        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user can upload files
     */
    public static function canUpload($user)
    {
        return self::hasPermission($user, 'files.upload');
    }

    /**
     * Check if user can download files
     */
    public static function canDownload($user)
    {
        return self::hasPermission($user, 'files.download');
    }

    /**
     * Check if user can delete files
     */
    public static function canDeleteFiles($user)
    {
        return self::hasPermission($user, 'files.delete');
    }

    /**
     * Check if user can create folders
     */
    public static function canCreateFolders($user)
    {
        return self::hasPermission($user, 'folders.create');
    }

    /**
     * Check if user can delete folders
     */
    public static function canDeleteFolders($user)
    {
        return self::hasPermission($user, 'folders.delete');
    }

    /**
     * Check if user can view files (read-only access)
     */
    public static function canViewFiles($user)
    {
        return self::hasPermission($user, 'files.view');
    }

    /**
     * Check if user can view folders (read-only access)
     */
    public static function canViewFolders($user)
    {
        return self::hasPermission($user, 'folders.view');
    }

    /**
     * Check if user is view-only (user role)
     */
    public static function isViewOnly($user)
    {
        if (!$user || !isset($user->role)) {
            return true;
        }

        return $user->role === 'user';
    }

    /**
     * Check if user is admin or superadmin
     */
    public static function isAdmin($user)
    {
        if (!$user || !isset($user->role)) {
            return false;
        }

        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Check if user is superadmin
     */
    public static function isSuperAdmin($user)
    {
        if (!$user || !isset($user->role)) {
            return false;
        }

        return $user->role === 'superadmin';
    }

    /**
     * Get role display name
     */
    public static function getRoleTitle($role)
    {
        $groups = self::getAvailableGroups();
        foreach ($groups as $group) {
            if ($group->name === $role) {
                return $group->title;
            }
        }
        return ucfirst($role);
    }

    /**
     * Check if user can access admin panel
     */
    public static function canAccessAdmin($user)
    {
        return self::hasPermission($user, 'admin.access');
    }

    /**
     * Check if user can manage other users
     */
    public static function canManageUsers($user)
    {
        return self::hasAnyPermission($user, [
            'users.create',
            'users.edit',
            'users.delete',
            'users.ban',
        ]);
    }

    /**
     * Get permission categories for display
     */
    public static function getPermissionCategories()
    {
        return [
            'User Management' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.delete',
                'users.ban',
                'users.manage-permissions',
            ],
            'File Management' => [
                'files.view',
                'files.upload',
                'files.download',
                'files.delete',
                'files.manage-all',
            ],
            'Folder Management' => [
                'folders.view',
                'folders.create',
                'folders.delete',
                'folders.manage-all',
            ],
            'Trash Management' => [
                'trash.view',
                'trash.restore',
                'trash.empty',
            ],
            'Admin Panel' => [
                'admin.access',
                'admin.settings',
            ],
            'Search' => [
                'search.basic',
                'search.advanced',
            ],
        ];
    }

    /**
     * Get all available permissions for UI display
     * Returns list of permission names from getAvailablePermissions()
     * 
     * @return array
     */
    public static function getAllPermissionsFromDB()
    {
        // Return dari available permissions yang sudah didefinisikan
        return self::getAvailablePermissions();
    }
}