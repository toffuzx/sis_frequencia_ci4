<?php

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EEEP Walter Ramos de Araújo</title>
    <link rel="icon" href="<?= base_url('img/logo_WR.png') ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-green: #3b8540; --primary-green-hover: #2c6b30; --bg-light: #f4f7f6; --text-gray: #718096; --input-border: #e2e8f0; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Roboto, sans-serif; }
        body { background-color: var(--bg-light); display: flex; justify-content: center; align-items: center; min-height: 100vh; background-image: radial-gradient(circle at 10% 20%, rgb(240, 246, 240) 0%, transparent 20%), radial-gradient(circle at 90% 80%, rgb(240, 246, 240) 0%, transparent 20%); }
        .login-card { background-color: white; width: 100%; max-width: 400px; border-radius: 24px; padding: 40px 30px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); text-align: center; }
        .logo-container { width: 120px; height: 120px; margin: 0 auto 20px; border-radius: 50%; background-color: #e8f5e9; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 4px solid #c8e6c9; }
        .logo-container img { width: 100%; height: auto; object-fit: contain; }
        h1 { color: var(--primary-green); font-size: 28px; margin-bottom: 8px; }
        p.subtitle { color: var(--text-gray); font-size: 15px; margin-bottom: 30px; }
        
        .input-group { position: relative; margin-bottom: 20px; text-align: left; }
        .input-group i.fa-solid { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--primary-green); font-size: 18px; pointer-events: none; }
        
        select, input[type="password"] {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border-radius: 50px;
            border: 1px solid var(--primary-green);
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
            color: var(--primary-green);
            font-weight: 600;
            background-color: white;
            cursor: pointer;
            appearance: none; 
            background: white url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" fill="%233b8540"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>') no-repeat right 20px center;
            background-size: 10px;
        }
        #senha {
            width: 100%;
            padding: 16px 50px 16px 50px;
            border-radius: 50px;
            border: 1px solid var(--input-border);
            font-size: 15px;
            outline: none;
        }
        #senha:hover {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(59, 133, 64, 0.1);
        }

        select:disabled { border-color: var(--input-border); color: var(--text-gray); cursor: not-allowed; opacity: 0.7; background-image: none; }
        input[type="password"] { border: 1px solid var(--input-border); color: #2d3748; font-weight: normal; background-image: none; }
        input[type="password"]:focus, select:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(59, 133, 64, 0.1); }
        input[type="password"]::placeholder { color: #a0aec0; }

        .options-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; font-size: 13px; }
        .checkbox-container { display: flex; align-items: center; gap: 8px; color: var(--primary-green); cursor: pointer; font-size: 15px; font-weight: 500; }
        .forgot-password { color: var(--primary-green); text-decoration: none; font-weight: 500; font-size: 15px; }
        .btn-submit { width: 100%; background-color: var(--primary-green); color: white; border: none; border-radius: 50px; padding: 16px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background-color 0.3s; }
        .btn-submit:hover { background-color: var(--primary-green-hover); }
        
        .olho {
            position: absolute;
            right: 20px !important;
            left: auto !important;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--primary-green);
            pointer-events: auto !important;
            z-index: 10;
            transition: all 0.2s;
        }
        .fa-eye { color: var(--primary-green); }
        .fa-eye-slash { color: #1f4d24; }

        .checkbox-container input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #3b8540;
            border-radius: 3px;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .checkbox-container input[type="checkbox"]:hover { box-shadow: 0 0 0 3px rgba(59, 133, 64, 0.15); }
        .checkbox-container input[type="checkbox"]:checked { background: #3b8540; position: relative; }
        .checkbox-container input[type="checkbox"]:checked::after {
            content: "✓";
            color: white;
            font-size: 12px;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -55%);
        }
        
        /* Box de Alerta de Erro */
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        @media (max-width: 480px) { .login-card { padding: 30px 20px; border-radius: 0; box-shadow: none; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; } }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-container">
            <img src="<?= base_url('img/logo.png.jpeg') ?>" alt="Logo">
        </div>

        <h1>Bem-vindo(a)!</h1>
        <p class="subtitle">Faça login para continuar</p>

        <!-- Exibe mensagem de erro se a senha/turma estiver errada -->
        <?php if (session()->getFlashdata('erro')): ?>
            <div class="alert-error">
                <?= session()->getFlashdata('erro') ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" action="<?= base_url('login/processar') ?>" method="POST">

            <div class="input-group">
                <i class="fa-solid fa-users"></i>
                <select id="turma" name="turma" disabled required>
                    <option value="" disabled selected>Carregando opções...</option>
                </select>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock lock-icon"></i>
                <input type="password" id="senha" name="senha" placeholder="Chave / Senha" required>
                <i class="fa-solid fa-eye olho" onclick="mostrarSenha(this)"></i>
            </div>

            <div class="options-row"> 
                <label class="checkbox-container">
                    <input type="checkbox" name="lembrar" value="1">
                    Me lembrar
                </label>

                <a href="<?= base_url('esqueci-senha') ?>" class="forgot-password">Esqueci minha senha</a>
            </div>
            
            <button type="submit" class="btn-submit">Entrar</button>
        </form>
    </div>

    <script>
        function mostrarSenha(icone) {
            const campo = document.getElementById("senha");

            if (campo.type === "password") {
                campo.type = "text";
                icone.classList.remove("fa-eye");
                icone.classList.add("fa-eye-slash");
            } else {
                campo.type = "password";
                icone.classList.remove("fa-eye-slash");
                icone.classList.add("fa-eye");
            }
        }

        // Recebe o array $turmas_db enviado do Controller Login::index
        const dadosBanco = <?= json_encode($turmas_db ?? []) ?>;
        const selectTurma = document.getElementById('turma');

        selectTurma.disabled = false;
        selectTurma.innerHTML = '<option value="" disabled selected>Selecionar Cargo</option>';

        // Preenche o campo select com os dados vindos do banco
        dadosBanco.forEach(turma => {
            let opcao = document.createElement('option');
            opcao.value = turma.id; 
            opcao.textContent = `${turma.nome}`;
            selectTurma.appendChild(opcao);
        });
    </script> 
</body>
</html>