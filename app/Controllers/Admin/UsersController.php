<?php

// ============================================
// FILE: app/Controllers/Admin/UsersController.php
// UPDATED untuk struktur database custom
// ============================================

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;
use App\Helpers\PermissionHelper;

class UsersController extends BaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();

    }

    /**
     * List all users
     */
    public function index()
    {
        $perPage = 10;
        $users = $this->userModel
            ->select('users.*, auth_identities.secret as email')
            ->join('auth_identities', 'auth_identities.user_id = users.id', 'left')
            ->where('auth_identities.type', 'email_password')
            ->paginate($perPage);

        // Get groups untuk setiap user
        foreach ($users as $user) {
            $userGroups = $this->db->table('auth_groups_users')
                ->where('user_id', $user->id)
                ->get()
                ->getResult();
            $user->groups = !empty($userGroups) ? array_column($userGroups, 'group') : [];
        }

        $data = [
            'title' => 'Manage Users',
            'users' => $users,
            'pager' => $this->userModel->pager,
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Create new user form
     */
    public function create()
    {
        $data = [
            'title' => 'Create New User',
            'groups' => $this->getAvailableGroups(),
        ];

        return view('admin/users/create', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'group' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Create user
        $user = new User([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        $this->userModel->save($user);
        $userId = $this->userModel->getInsertID();

        // Assign group
        $this->db->table('auth_groups_users')->insert([
            'user_id' => $userId,
            'group' => $this->request->getPost('group'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        // Assign DEFAULT permissions sesuai role
        PermissionHelper::assignDefaultPermissions($userId, $this->request->getPost('group'));
        return redirect()->to('/admin/users')
            ->with('message', 'User created successfully!');
    }

    /**
     * Edit user form
     */
    public function edit($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found');
        }

        // Get user groups
        $userGroups = $this->db->table('auth_groups_users')
            ->where('user_id', $id)
            ->get()
            ->getResult();
        $userGroupNames = !empty($userGroups) ? array_column($userGroups, 'group') : [];

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'groups' => $this->getAvailableGroups(),
            'userGroups' => $userGroupNames,
        ];

        return view('admin/users/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found');
        }

        $rules = [
            'username' => "required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            // 'email' => "required",
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Update user
        $user->username = $this->request->getPost('username');

        if ($this->request->getPost('password')) {
            $user->password = $this->request->getPost('password');
        }

        $this->userModel->save($user);

        // Update group
        if ($this->request->getPost('group')) {
            // Delete old group
            $this->db->table('auth_groups_users')->where('user_id', $id)->delete();

            $this->db->table('auth_groups_users')->insert([
                'user_id' => $id,
                'group' => $this->request->getPost('group'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/admin/users')
            ->with('message', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        if ($id == $this->uid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You cannot delete your own account',
            ]);
        }

        $user = $this->userModel->findById($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        // Delete user (cascade akan hapus groups & permissions)
        $this->userModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    /**
     * Ban user
     */
    public function ban($id)
    {
        if ($id == $this->uid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You cannot ban yourself',
            ]);
        }

        $user = $this->userModel->findById($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $user->active = 0;
        $this->userModel->save($user);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User banned successfully',
        ]);
    }

    /**
     * Unban user
     */
    public function unban($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $user->active = 1;
        $this->userModel->save($user);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User unbanned successfully',
        ]);
    }

    /**
     * View user details
     */
    public function show($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found');
        }

        // Get user groups
        $userGroups = $this->db->table('auth_groups_users')
            ->where('user_id', $id)
            ->get()
            ->getResult();
        $groups = !empty($userGroups) ? array_column($userGroups, 'group') : [];

        // Get user permissions
        $userPerms = $this->db->table('auth_permissions_users')
            ->where('user_id', $id)
            ->get()
            ->getResult();
        $permissions = !empty($userPerms) ? array_column($userPerms, 'permission') : [];

        // Get login history
        $loginHistory = $this->db->table('auth_logins')
            ->where('user_id', $id)
            ->orderBy('date', 'DESC')
            ->limit(10)
            ->get()
            ->getResult();

        $data = [
            'title' => 'User Details',
            'user' => $user,
            'groups' => $groups,
            'permissions' => $permissions,
            'loginHistory' => $loginHistory,
        ];

        return view('admin/users/show', $data);
    }

    /**
     * Manage user permissions
     */
    public function permissions($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found');
        }

        // Get user permissions
        $userPerms = $this->db->table('auth_permissions_users')
            ->where('user_id', $id)
            ->get()
            ->getResult();
        $userPermissions = !empty($userPerms) ? array_column($userPerms, 'permission') : [];

        $data = [
            'title' => 'Manage Permissions',
            'user' => $user,
            'allPermissions' => $this->getAvailablePermissions(),
            'userPermissions' => $userPermissions,
        ];

        return view('admin/users/permissions', $data);
    }

    /**
     * Update user permissions
     */
    public function updatePermissions($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found');
        }

        $permissions = $this->request->getPost('permissions') ?? [];

        // Delete old permissions
        $this->db->table('auth_permissions_users')->where('user_id', $id)->delete();

        // Insert new permissions
        foreach ($permissions as $permission) {
            $this->db->table('auth_permissions_users')->insert([
                'user_id' => $id,
                'permission' => $permission,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/admin/users')
            ->with('message', 'Permissions updated successfully!');
    }

    /**
     * Search users
     */
    public function search()
    {
        $query = $this->request->getGet('q');

        $users = $this->userModel
            ->select('users.*, auth_identities.secret as email')
            ->join('auth_identities', 'auth_identities.user_id = users.id', 'left')
            ->where('auth_identities.type', 'email_password')
            ->groupStart()
            ->like('users.username', $query)
            ->orLike('auth_identities.secret', $query)
            ->groupEnd()
            ->findAll();

        foreach ($users as $user) {
            $userGroups = $this->db->table('auth_groups_users')
                ->where('user_id', $user->id)
                ->get()
                ->getResult();
            $user->groups = !empty($userGroups) ? array_column($userGroups, 'group') : [];
        }

        $data = [
            'title' => 'Search Results',
            'users' => $users,
            'query' => $query,
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Get available groups
     */
    private function getAvailableGroups()
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
                'description' => 'Standard user access',
            ],
        ];
    }

    /**
     * Get available permissions 
     */
    private function getAvailablePermissions()
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
            (object) ['name' => 'files.upload', 'description' => 'Upload files'],
            (object) ['name' => 'files.download', 'description' => 'Download files'],
            (object) ['name' => 'files.delete', 'description' => 'Delete files'],
            (object) ['name' => 'files.manage-all', 'description' => 'Manage all users files'],

            // Folder Management
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
            (object) ['name' => 'search.advanced', 'description' => 'Use advanced search features'],
        ];
    }
}