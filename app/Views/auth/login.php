<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/icoii.png') ?>" />
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            background: 
                /* linear-gradient(rgba(40, 40, 40, 0.4), rgba(40, 40, 40, 0.4)), */
                url("/assets/img/Cool Background.jpg");
/* 
            background: linear-gradient(135deg, #f37f3bff, #8d528dff, #ffffff); */

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
        }

        .welcome-text1 {
            position: absolute;
            top: 160px;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            width: 100%;
            text-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .welcome-text {
            position: absolute;
            top: 200px;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            width: 100%;
            text-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        /* .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        } */

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

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me input {
            margin-right: 8px;
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
            background: #2a4affe3;
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

        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .text-center {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .text-center a {
            color: #385ba7ff;
            text-decoration: none;
            font-weight: 600;
        }

        .container { 
            width: 85%; 
            max-width: 1100px; 
            height: 600px; 
            background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(20px); 
            border-radius: 20px; display: 
            flex; overflow: hidden; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.15); 
        } 
        
        .left-box { 
            flex: 1.2; 
            padding: 50px; 
            color: white; 
            /* background: linear-gradient(135deg, #a45deb, #ff8b49);  */

            background: 
                linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url("/assets/img/container_background.jpg");

            position: relative; 
        } 
        
        .left-box h1 { 
            font-size: 38px; 
            font-weight: 700; 
        } 
        
        .left-box p { 
            width: 80%; 
            margin-top: 15px; 
            line-height: 1.6; 
            opacity: 0.9; 
        } 
        
        .right-box { 
            flex: 1; 
            background: white; 
            padding: 50px 60px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
        } 
            
        .right-box h2 { 
            text-align: center; 
            font-size: 20px; 
            color: #385ba7ff; margin-bottom: 30px; 
            letter-spacing: 1px; 
        } 
        
        .form-group { 
            margin-bottom: 18px; 
        } 
        
        .form-group input { 
            width: 100%; 
            padding: 12px 15px; 
            border-radius: 25px; 
            border: none; background: #f1f1ff; 
            outline: none; font-size: 14px; 
        } 
        
        .remember-box { 
            display: flex; 
            justify-content: space-between; 
            font-size: 13px; color: #666; 
            margin-bottom: 25px; 
        } 
        
        .remember-box a { 
            text-decoration: none; 
            color: #385ba7ff; 
        } 
        
        .btn-login { 
            width: 100%; 
            padding: 12px; 
            border-radius: 25px; 
            border: none; 
            cursor: pointer; 
            background: linear-gradient(90deg, #6a85b6, #bac8e0);
            color: white; 
            font-size: 15px; 
            font-weight: 600; 
            transition: 0.2s; 
        } 
        
        .btn-login:hover { opacity: 0.9; }

        .hero-title {
          font-family: "Poppins", "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
          font-weight: 800;
          font-size: clamp(28px, 4vw, 48px);
          line-height: 1.02;
          margin: 0 0 18px 0;
          letter-spacing: -0.02em;
          text-transform: uppercase;
          text-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        .hero-brand{
          display: inline-block;
          border-radius: 6px;
        }

        .hero-lead{
          font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
          font-weight: 400;
          font-size: clamp(14px, 1.6vw, 16px);
          color: rgba(255,255,255,0.92);
          line-height: 1.7;
          margin: 0 0 28px 0;
          opacity: 0.95;
        }

        .login-logo img {
            width: 80px;
            display: block;
            margin: 0 auto 20px auto;
        }

        .hero-logos {
            /* margin-top: 25px; */
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25px;
            margin: 0 auto 20px auto;
        }

        .hero-logos img {
            height: 55px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.15));
        }

        @media (max-width: 600px) {
            .hero-logos img {
                height: 45px;
            }
        }
    </style>
</head>

<body>
    <!-- <div class="welcome-text1">WElCOME TO</div>
    <div class="welcome-text">ARMADA ISO 14001:2015 DATABASE</div> -->

    <div class="container">
        <div class="left-box"> 
            <h1 class="hero-title">SELAMAT DATANG DI<br><span class="hero-brand">DATABASE ISO 14001</span><br>PT MEKAR ARMADA JAYA</h1>
            <p class="hero-lead">
              Sistem manajemen dokumen ISO 14001:2015 untuk PT Mekar Armada Jaya — Plant Tambun.
              Silakan login ke akun Anda untuk melanjutkan.
            </p>
        </div>

        <div class="right-box"> 
            <div class="hero-logos">
                <img src="/assets/img/SAFETY_FIRST-removebg-preview.png" alt="Logo Safety">
                <img src="/assets/img/ISO 1.png" alt="Logo ISO">
                <img src="/assets/img/ISO.png" alt="Logo ISO 2">
                <img src="/assets/img/icoii-nobg.png" alt="Logo Perusahaan">
            </div>
            <h2>USER LOGIN</h2> 
            <form action="<?= base_url('login') ?>" method="post"> <?= csrf_field() ?> 
                <div class="form-group"> <input type="text" name="email" placeholder="Email atau Username" required> </div> 
                    <div class="form-group"> 
                        <input type="password" name="password" placeholder="Password" required> 
                    </div> 
                <div class="remember-box"> 
                    <label><input type="checkbox" name="remember"> Remember me </label> <a href="#">Forgot password?</a> 
                </div> 
                <button type="submit" class="btn-login">LOGIN</button> 
            </form> 
            <div class="text-center">
                <p>Belum punya akun? <a href="<?= base_url('register') ?>">Daftar disini</a></p>
            </div>
        </div>
        
        
        
        
        
        
        <!-- <h2>Login</h2>
        <?php if (session('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session('message')): ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Email atau Username</label>
                <input type="text" name="email" value="<?= old('email') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" value="1" id="remember">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="text-center">
            <p>Belum punya akun? <a href="<?= base_url('register') ?>">Daftar disini</a></p>
        </div> -->
    </div>
</body>

</html>