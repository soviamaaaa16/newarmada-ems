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
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .permission-list {
            margin: 20px 0;
        }

        .permission-item {
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .permission-item:hover {
            background: #f9f9f9;
            border-color: #667eea;
        }

        .permission-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            cursor: pointer;
        }

        .permission-details {
            flex: 1;
        }

        .permission-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .permission-desc {
            font-size: 13px;
            color: #666;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1>🔐 Manage User Permissions</h1>
        <a href="<?= base_url('admin/users') ?>">← Back</a>
    </div>

    <div class="container">
        <div class="card">
            <div class="user-info">
                <h3>User: <strong><?= esc($user->username) ?></strong></h3>
                <p style="color: #666; margin-top: 5px;">
                    Email: <?= esc($user->email) ?>
                </p>
            </div>

            <form action="<?= base_url('admin/users/updatePermissions/' . $user->id) ?>" method="post">
                <?= csrf_field() ?>

                <h4 style="margin-bottom: 20px;">Select Permissions:</h4>

                <div class="permission-list">
                    <?php if (!empty($allPermissions)): ?>
                        <?php foreach ($allPermissions as $permission): ?>
                            <div class="permission-item">
                                <input type="checkbox" name="permissions[]" value="<?= esc($permission->name) ?>"
                                    id="perm_<?= md5($permission->name) ?>" <?= in_array($permission->name, $userPermissions) ? 'checked' : '' ?>>
                                <label for="perm_<?= md5($permission->name) ?>" class="permission-details">
                                    <div class="permission-name">
                                        <?= esc($permission->name) ?>
                                    </div>
                                    <div class="permission-desc">
                                        <?= esc($permission->description) ?>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #999; padding: 40px;">
                            No permissions available
                        </p>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                    <button type="button" onclick="selectAll()" class="btn btn-secondary">Select All</button>
                    <button type="button" onclick="deselectAll()" class="btn btn-secondary">Deselect All</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectAll() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
        }

        function deselectAll() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    </script>
</body>

</html>