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
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
        }

        input:read-only {
            background: #f8f9fa;
            cursor: not-allowed;
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

        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1>✏️ Edit User</h1>
        <a href="<?= base_url('admin/users') ?>">← Back</a>
    </div>

    <div class="container">
        <div class="card">
            <?php if (session('errors')): ?>
                <div class="alert alert-danger">
                    <ul style="margin-left: 20px;">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/users/update/' . $user->id) ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Username: *</label>
                    <input type="text" name="username" value="<?= esc($user->username) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" value="<?= esc($user->email) ?>" readonly>
                    <small style="color: #999;">Email cannot be changed</small>
                </div>

                <div class="form-group">
                    <label>New Password:</label>
                    <input type="password" name="password">
                    <small style="color: #999;">Leave blank to keep current password</small>
                </div>

                <div class="form-group">
                    <label>User Group: *</label>
                    <select name="group" required>
                        <option value="">-- Select Group --</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= esc($group->name) ?>" <?= in_array($group->name, $userGroups) ? 'selected' : '' ?>>
                                <?= esc($group->title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-top: 30px;">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>