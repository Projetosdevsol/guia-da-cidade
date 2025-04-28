<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cadastro</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	
	<style>
		body {
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
			min-height: 100vh;
		}

		.login-container {
			max-width: 400px;
			margin: 0 auto;
			padding: 40px 20px;
		}

		.login-card {
			background: white;
			border-radius: 15px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
			padding: 40px;
			position: relative;
			overflow: hidden;
		}

		.login-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 5px;
			background: linear-gradient(90deg, #28a745, #20c997);
		}

		.login-header {
			text-align: center;
			margin-bottom: 30px;
		}

		.login-title {
			font-size: 24px;
			font-weight: 600;
			color: #2c3e50;
			margin-bottom: 10px;
		}

		.form-control {
			border: 2px solid #e9ecef;
			padding: 12px;
			height: auto;
			font-size: 15px;
			transition: all 0.3s;
		}

		.form-control:focus {
			border-color: #28a745;
			box-shadow: none;
		}

		.form-label {
			font-weight: 500;
			color: #495057;
			margin-bottom: 8px;
		}

		.btn-login {
			width: 100%;
			padding: 12px;
			font-weight: 500;
			font-size: 16px;
			margin-top: 20px;
			background: linear-gradient(90deg, #28a745, #20c997);
			border: none;
			transition: transform 0.2s;
		}

		.btn-login:hover {
			transform: translateY(-2px);
		}

		.login-footer {
			display: flex;
			justify-content: center;
			gap: 20px;
			margin-top: 25px;
			padding-top: 20px;
			border-top: 1px solid #e9ecef;
		}

		.login-footer a {
			color: #6c757d;
			text-decoration: none;
			font-size: 14px;
			transition: color 0.2s;
		}

		.login-footer a:hover {
			color: #28a745;
		}

		.alert {
			border-radius: 10px;
			font-size: 14px;
			margin-bottom: 20px;
		}

		@media (max-width: 576px) {
			.login-container {
				padding: 20px;
			}

			.login-card {
				padding: 30px 20px;
			}
		}
	</style>
</head>
<body>
    <div class="login-container d-flex align-items-center min-vh-100">
        <div class="login-card w-100">
            <div class="login-header">
                <h1 class="login-title">Criar Conta</h1>
                <p class="text-muted">Preencha os dados para se cadastrar</p>
            </div>

            <form action="php/signup.php" method="post">
                <?php if(isset($_GET['error'])){ ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php } ?>

                <?php if(isset($_GET['success'])){ ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php } ?>

                <div class="mb-4">
                    <label class="form-label">Nome Completo</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control"
                               name="fname"
                               value="<?php echo (isset($_GET['fname']))? htmlspecialchars($_GET['fname']):"" ?>"
                               placeholder="Digite seu nome completo">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Nome de usuário</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user-circle text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control"
                               name="uname"
                               value="<?php echo (isset($_GET['uname']))? htmlspecialchars($_GET['uname']):"" ?>"
                               placeholder="Digite seu usuário">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" 
                               class="form-control"
                               name="pass"
                               placeholder="Digite sua senha">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    Cadastrar
                </button>

                <div class="login-footer">
                    <a href="login.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                    <a href="blog.php">
                        <i class="fas fa-newspaper me-1"></i>Blog
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>