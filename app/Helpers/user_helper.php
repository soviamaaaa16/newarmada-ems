<?php

// app/Helpers/user_helper.php

if (!function_exists('get_user_by_id')) {
    /**
     * Get user by ID
     * 
     * @param int $userId
     * @return object|null User object or null
     */
    function get_user_by_id(int $userId)
    {
        $users = auth()->getProvider();
        return $users->findById($userId);
    }
}

if (!function_exists('get_username')) {
    /**
     * Get username from user ID
     * 
     * @param int $userId
     * @return string Username or 'Unknown User'
     */
    function get_username(int $userId): string
    {
        $user = get_user_by_id($userId);
        return $user ? $user->username : 'Unknown User';
    }
}

if (!function_exists('get_user_email')) {
    /**
     * Get user email from user ID
     * 
     * @param int $userId
     * @return string Email or ''
     */
    function get_user_email(int $userId): string
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return '';
        }

        // Get primary email
        $email = $user->getEmailIdentity();
        return $email ? $email->secret : '';
    }
}

if (!function_exists('get_user_full_name')) {
    /**
     * Get user full name from user ID
     * Assumes you have first_name and last_name columns
     * 
     * @param int $userId
     * @return string Full name or username
     */
    function get_user_full_name(int $userId): string
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return 'Unknown User';
        }

        // If you have first_name and last_name columns
        if (isset($user->first_name) && isset($user->last_name)) {
            return trim($user->first_name . ' ' . $user->last_name);
        }

        // Fallback to username
        return $user->username ?? 'Unknown User';
    }
}

if (!function_exists('get_user_avatar')) {
    /**
     * Get user avatar URL from user ID
     * 
     * @param int $userId
     * @param string $default Default avatar URL
     * @return string Avatar URL
     */
    function get_user_avatar(int $userId, string $default = '/assets/img/default-avatar.png'): string
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return $default;
        }

        // If you have avatar column
        if (isset($user->avatar) && !empty($user->avatar)) {
            return base_url('uploads/avatars/' . $user->avatar);
        }

        // Fallback to Gravatar
        $email = get_user_email($userId);
        if ($email) {
            $hash = md5(strtolower(trim($email)));
            return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
        }

        return $default;
    }
}

if (!function_exists('get_user_role')) {
    /**
     * Get user primary role/group
     * 
     * @param int $userId
     * @return string Role name
     */
    function get_user_role(int $userId): string
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return 'guest';
        }

        $groups = $user->getGroups();

        // Return first group or 'user' as default
        return !empty($groups) ? $groups[0] : 'user';
    }
}

if (!function_exists('get_user_roles')) {
    /**
     * Get all user roles/groups
     * 
     * @param int $userId
     * @return array Array of role names
     */
    function get_user_roles(int $userId): array
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return [];
        }

        return $user->getGroups();
    }
}

if (!function_exists('get_user_info')) {
    /**
     * Get complete user information
     * 
     * @param int $userId
     * @return array User info array
     */
    function get_user_info(int $userId): array
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return [
                'id' => $userId,
                'username' => 'Unknown User',
                'email' => '',
                'role' => 'guest',
                'avatar' => get_user_avatar($userId),
                'is_active' => false,
            ];
        }

        return [
            'id' => $user->id,
            'username' => $user->username ?? 'Unknown',
            'email' => get_user_email($userId),
            'role' => get_user_role($userId),
            'roles' => get_user_roles($userId),
            'avatar' => get_user_avatar($userId),
            'is_active' => $user->active ?? false,
            'is_banned' => $user->isBanned() ?? false,
            'created_at' => $user->created_at ?? null,
        ];
    }
}

if (!function_exists('user_exists')) {
    /**
     * Check if user exists
     * 
     * @param int $userId
     * @return bool
     */
    function user_exists(int $userId): bool
    {
        $user = get_user_by_id($userId);
        return $user !== null;
    }
}

if (!function_exists('get_user_display_name')) {
    /**
     * Get user display name (full name or username)
     * 
     * @param int $userId
     * @return string Display name
     */
    function get_user_display_name(int $userId): string
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return 'Unknown User';
        }

        // Priority: full_name > first_name + last_name > username
        if (isset($user->full_name) && !empty($user->full_name)) {
            return $user->full_name;
        }

        if (isset($user->first_name) && !empty($user->first_name)) {
            $name = $user->first_name;
            if (isset($user->last_name) && !empty($user->last_name)) {
                $name .= ' ' . $user->last_name;
            }
            return $name;
        }

        return $user->username ?? 'Unknown User';
    }
}

if (!function_exists('get_multiple_users')) {
    /**
     * Get multiple users by IDs
     * 
     * @param array $userIds Array of user IDs
     * @return array Array of user objects
     */
    function get_multiple_users(array $userIds): array
    {
        if (empty($userIds)) {
            return [];
        }

        $users = model('UserModel')->findAll();

        return array_filter($users, function ($user) use ($userIds) {
            return in_array($user->id, $userIds);
        });
    }
}

if (!function_exists('format_user_badge')) {
    /**
     * Format user badge HTML based on role
     * 
     * @param int $userId
     * @return string HTML badge
     */
    function format_user_badge(int $userId): string
    {
        $role = get_user_role($userId);

        $badges = [
            'superadmin' => '<span class="badge bg-danger">Super Admin</span>',
            'admin' => '<span class="badge bg-primary">Admin</span>',
            'user' => '<span class="badge bg-secondary">User</span>',
        ];

        return $badges[$role] ?? '<span class="badge bg-light">Guest</span>';
    }
}

if (!function_exists('get_user_status')) {
    /**
     * Get user status (active, banned, etc)
     * 
     * @param int $userId
     * @return string Status
     */
    function get_user_status(int $userId): string
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return 'not_found';
        }

        if ($user->isBanned()) {
            return 'banned';
        }

        if (!$user->active) {
            return 'inactive';
        }

        return 'active';
    }
}

if (!function_exists('get_user_last_active')) {
    /**
     * Get user last active time
     * 
     * @param int $userId
     * @return string|null Last active time or null
     */
    function get_user_last_active(int $userId): ?string
    {
        $user = get_user_by_id($userId);

        if (!$user) {
            return null;
        }

        // Assuming you have last_active column
        return $user->last_active ?? null;
    }
}