<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #5568d3;
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .error-message {
            color: #c33;
            font-size: 12px;
            margin-top: 5px;
        }

        .text-center {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .text-center a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Akun</h2>

        <?php if (session('errors')): ?>
            <div class="alert alert-danger">
                <ul style="margin-left: 20px;">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?= old('username') ?>" required>
                <?php if (session('errors.username')): ?>
                    <div class="error-message"><?= session('errors.username') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= old('email') ?>" required>
                <?php if (session('errors.email')): ?>
                    <div class="error-message"><?= session('errors.email') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
                <small style="color: #999;">Min. 8 karakter</small>
                <?php if (session('errors.password')): ?>
                    <div class="error-message"><?= session('errors.password') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password:</label>
                <input type="password" name="password_confirm" required>
                <?php if (session('errors.password_confirm')): ?>
                    <div class="error-message"><?= session('errors.password_confirm') ?></div>
                <?php endif; ?>
            </div>

            <button type="submit">Daftar</button>
        </form>

        <div class="text-center">
            <p>Sudah punya akun? <a href="<?= base_url('login') ?>">Login disini</a></p>
        </div>
    </div>
</body>

</html>