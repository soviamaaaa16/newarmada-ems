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
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .card h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            color: #333;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            padding: 12px;
            text-align: left;
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover {
            background: #f9f9f9;
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
            margin-right: 10px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background: #e0a800;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1>👤 User Details</h1>
        <a href="<?= base_url('admin/users') ?>">← Back</a>
    </div>

    <div class="container">
        <!-- Basic Info -->
        <div class="card">
            <h3>📋 Basic Information</h3>
            <div class="info-grid">
                <div class="info-label">User ID:</div>
                <div class="info-value"><?= esc($user->id) ?></div>

                <div class="info-label">Username:</div>
                <div class="info-value"><strong><?= esc($user->username) ?></strong></div>

                <div class="info-label">Email:</div>
                <div class="info-value"><?= esc($user->email) ?></div>

                <div class="info-label">Status:</div>
                <div class="info-value">
                    <?php if ($user->active): ?>
                        <span class="badge badge-active">Active</span>
                    <?php else: ?>
                        <span class="badge badge-banned">Banned</span>
                    <?php endif; ?>
                </div>

                <div class="info-label">Created At:</div>
                <div class="info-value"><?= date('F d, Y H:i:s', strtotime($user->created_at)) ?></div>

                <div class="info-label">Updated At:</div>
                <div class="info-value"><?= date('F d, Y H:i:s', strtotime($user->updated_at)) ?></div>
            </div>

            <div style="margin-top: 30px;">
                <a href="<?= base_url('admin/users/edit/' . $user->id) ?>" class="btn btn-primary">
                    Edit User
                </a>
                <a href="<?= base_url('admin/users/permissions/' . $user->id) ?>" class="btn btn-warning">
                    Manage Permissions
                </a>
            </div>
        </div>

        <!-- Groups & Permissions -->
        <div class="card">
            <h3>🔐 Groups & Permissions</h3>

            <div class="info-grid">
                <div class="info-label">Groups:</div>
                <div class="info-value">
                    <?php if (!empty($groups)): ?>
                        <?php foreach ($groups as $group): ?>
                            <span class="badge badge-<?= esc($group) ?>">
                                <?= esc(ucfirst($group)) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color: #999;">No group</span>
                    <?php endif; ?>
                </div>

                <div class="info-label">Permissions:</div>
                <div class="info-value">
                    <?php if (!empty($permissions)): ?>
                        <?php foreach ($permissions as $permission): ?>
                            <span class="badge badge-user">
                                <?= esc($permission) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <em style="color: #999;">No direct permissions</em>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Login History -->
        <div class="card">
            <h3>📊 Login History (Last 10)</h3>

            <?php if (!empty($loginHistory)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Success</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loginHistory as $login): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i:s', strtotime($login->date)) ?></td>
                                <td><?= esc($login->ip_address ?? '-') ?></td>
                                <td><?= esc(substr($login->user_agent ?? '-', 0, 50)) ?>...</td>
                                <td>
                                    <?php if ($login->success): ?>
                                        <span class="badge badge-active">✓</span>
                                    <?php else: ?>
                                        <span class="badge badge-banned">✗</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">
                    No login history available
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>