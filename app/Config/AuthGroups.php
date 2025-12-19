<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title' => 'Super Administrator',
            'description' => 'Complete control of the site',
        ],
        'admin' => [
            'title' => 'Administrator',
            'description' => 'Site administration access',
        ],
        'user' => [
            'title' => 'Regular User',
            'description' => 'Standard user access (view only)',
        ],
    ];

    public array $permissions = [
        // User Management
        'users.view' => 'View users list',
        'users.create' => 'Create new users',
        'users.edit' => 'Edit user information',
        'users.delete' => 'Delete users',
        'users.ban' => 'Ban/Unban users',
        'users.manage-permissions' => 'Manage user permissions',

        // File Management
        'files.view' => 'View files',
        'files.upload' => 'Upload files',
        'files.download' => 'Download files',
        'files.delete' => 'Delete files',
        'files.manage-all' => 'Manage all users files',

        // Folder Management
        'folders.view' => 'View folders',
        'folders.create' => 'Create folders',
        'folders.delete' => 'Delete folders',
        'folders.manage-all' => 'Manage all users folders',

        // Trash Management
        'trash.view' => 'View trash',
        'trash.restore' => 'Restore from trash',
        'trash.empty' => 'Permanently delete from trash',

        // Admin Panel
        'admin.access' => 'Access admin panel',
        'admin.settings' => 'Manage system settings',

        // Search
        'search.basic' => 'Use basic search features',
        'search.advanced' => 'Use advanced search features',
    ];

    public array $matrix = [
        'superadmin' => [
            'users.*',      // All user permissions
            'files.*',      // All file permissions
            'folders.*',    // All folder permissions
            'trash.*',      // All trash permissions
            'admin.*',      // All admin permissions
            'search.*',     // All search permissions
        ],
        'admin' => [
            'users.view',
            'users.create',
            'users.edit',
            'users.ban',
            'files.*',
            'folders.*',
            'trash.*',
            'admin.access',
            'search.*',
        ],
        'user' => [
            'files.view',
            'folders.view',
            'search.basic',
        ],
    ];
}
