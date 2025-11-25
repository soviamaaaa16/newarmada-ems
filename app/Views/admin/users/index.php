<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f5f5;
        }

        .navbar {
            background: #667eea;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            font-size: 24px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            margin-left: 10px;
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background: #138496;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            margin-right: 5px;
        }

        .badge-superadmin {
            background: #6f42c1;
            color: white;
        }

        .badge-admin {
            background: #667eea;
            color: white;
        }

        .badge-user {
            background: #28a745;
            color: white;
        }

        .badge-active {
            background: #28a745;
            color: white;
        }

        .badge-banned {
            background: #dc3545;
            color: white;
        }

        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1>👥 User Management</h1>
        <div>
            <a href="<?= base_url('drive') ?>">← Back to Dashboard</a>
            <a href="<?= base_url('logout') ?>">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if (session('message')): ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <div class="header">
            <h2>All Users (<?= count($users) ?>)</h2>
            <div style="display: flex; gap: 10px;">
                <form action="<?= base_url('admin/users/search') ?>" method="get" class="search-box">
                    <input type="text" name="q" placeholder="Search by username or email..."
                        value="<?= esc($query ?? '') ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <a href="<?= base_url('admin/users/create') ?>" class="btn btn-success">+ Add New User</a>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Groups</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                No users found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user->id) ?></td>
                                <td><strong><?= esc($user->username) ?></strong></td>
                                <td><?= esc($user->email) ?></td>
                                <td>
                                    <?php if (!empty($user->groups)): ?>
                                        <?php foreach ($user->groups as $group): ?>
                                            <span class="badge badge-<?= esc($group) ?>">
                                                <?= esc(ucfirst($group)) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span style="color: #999;">No group</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user->active): ?>
                                        <span class="badge badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-banned">Banned</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y-m-d H:i', strtotime($user->created_at)) ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= base_url('admin/users/show/' . $user->id) ?>" class="btn btn-info btn-sm"
                                            title="View Details">
                                            👁️
                                        </a>
                                        <a href="<?= base_url('admin/users/edit/' . $user->id) ?>"
                                            class="btn btn-primary btn-sm" title="Edit">
                                            ✏️
                                        </a>
                                        <a href="<?= base_url('admin/users/permissions/' . $user->id) ?>"
                                            class="btn btn-warning btn-sm" title="Permissions">
                                            🔐
                                        </a>

                                        <?php if ($user->active): ?>
                                            <button onclick="banUser(<?= $user->id ?>)" class="btn btn-warning btn-sm"
                                                title="Ban User">
                                                🚫
                                            </button>
                                        <?php else: ?>
                                            <button onclick="unbanUser(<?= $user->id ?>)" class="btn btn-success btn-sm"
                                                title="Unban User">
                                                ✅
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($user->id != auth()->user()->id): ?>
                                            <button onclick="deleteUser(<?= $user->id ?>)" class="btn btn-danger btn-sm"
                                                title="Delete">
                                                🗑️
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (isset($pager)): ?>
                <div style="padding: 20px; text-align: center;">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function banUser(id) {
            if (confirm('Are you sure you want to ban this user?')) {
                fetch(`<?= base_url('admin/users/ban/') ?>${id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) location.reload();
                    });
            }
        }

        function unbanUser(id) {
            if (confirm('Are you sure you want to unban this user?')) {
                fetch(`<?= base_url('admin/users/unban/') ?>${id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) location.reload();
                    });
            }
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone!')) {
                fetch(`<?= base_url('admin/users/delete/') ?>${id}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) location.reload();
                    });
            }
        }
    </script>
</body>

</html>