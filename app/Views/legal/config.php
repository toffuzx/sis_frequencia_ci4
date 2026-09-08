<?php
include 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <link rel="icon" href="./logo_WR.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { 
            --primary-dark: #1a4331;
            --primary-green: #3b8540;
            --bg-light: #f4f7f6;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-light);
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background-color: var(--primary-dark);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .header-text h1 {
            color: var(--primary-dark);
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header-text p {
            color: var(--text-gray);
            font-size: 15px;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .setting-card {
            background-color: white;
            border-radius: 16px;
            padding: 25px;
            text-decoration: none;
            transition: 0.25s ease;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }

        .setting-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            border-color: var(--primary-green);
        }

        .setting-icon {
            width: 55px;
            height: 55px;
            background-color: #e8f5e9;
            color: var(--primary-green);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }

        .setting-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .setting-description {
            font-size: 14px;
            color: var(--text-gray);
            line-height: 1.5;
        }

        @media (max-width: 600px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <div class="header">
        <div class="header-icon">
            <i class="fa-solid fa-gear"></i>
        </div>

        <div class="header-text">
            <h1>Configurações</h1>
            <p>Gerencie opções do sistema e personalize sua conta.</p>
        </div>
    </div>

    <div class="settings-grid">

        <a href="trocar_senha.php" class="setting-card">
            <div class="setting-icon">
                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="setting-title">
                Trocar Senha
            </div>

            <div class="setting-description">
                Atualize sua senha de acesso para manter sua conta segura.
            </div>
        </a>

        <a href="alterar_turma.php" class="setting-card">
            <div class="setting-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="setting-title">
                Alterações de Turma
            </div>

            <div class="setting-description">
                Gerencie mudanças relacionadas às turmas e alunos.
            </div>
        </a>

    </div>

</div>

<?php include 'rodapelegal.php'; ?>

</body>
</html>
