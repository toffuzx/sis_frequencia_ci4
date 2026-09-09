<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" href="./logo_WR.png" type="image/png">
<style>
    :root {
        --primary-dark: #4A7A61;
    }
    body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        padding-top: 70px; /* Cria um espaço para o conteúdo não sumir por baixo da barra fixa */
    }
    /* NAVBAR (TOPO) */
    .topbar {
        background-color: var(--primary-dark);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;

        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 66px; /* Altura exata definida para controle */
        z-index: 997; /* Garante que fique acima do conteúdo, mas abaixo da sidebar */
        box-sizing: border-box;
    }
    .home-btn {
        background-color: white;
        color: var(--primary-dark);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        font-size: 16px;
    }

    .home-btn:hover {
        background-color: #f0f0f0;
        color: var(--primary-dark);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transform: scale(1.15);
    }

    /* BARRA LATERAL (SIDEBAR DETECTADA) */
    .sidebar-right {
        height: 100%;
        width: 280px;
        position: fixed;
        z-index: 1000;
        top: 0;
        right: -280px;
        background-color: #2e5343;
        color: white;
        overflow-x: hidden;
        transition: 0.4s ease;
        padding-top: 20px;
        box-shadow: -2px 0px 10px rgba(0,0,0,0.3);
    }
    .sidebar-right.active {
        right: 0;
    }
    .sidebar-right .btn-fechar {
        position: absolute;
        top: 15px;
        left: 20px;
        font-size: 30px;
        color: #fff;
        text-decoration: none;
    }
    .sidebar-header {
        text-align: center;
        padding: 20px 10px;
    }
    .school-logo {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
    }
    .menu-items {
        padding: 10px 20px;
    }
    .menu-items a {
        padding: 14px 15px;
        text-decoration: none;
        font-size: 16px;
        color: #f1f1f1;
        display: block;
        transition: 0.3s;
    }
    .menu-items a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    .menu-items a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }
    .menu-items .txt-sair {
        color: #ff6b6b;
    }
    .overlay-menu {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 999;
    }
    .overlay-menu.active {
        display: block;
    }
</style>

<div id="overlay-direito" class="overlay-menu" onclick="toggleMenuRight()"></div>

<header class="topbar">
    <a href="<?= base_url('logout') ?>" class="home-btn" title="Sair do sistema">
        <i class="fa-solid fa-right-from-bracket"></i>
    </a>

    <a href="javascript:void(0)" id="btn-menu-direito" onclick="toggleMenuRight()" style="color: inherit; text-decoration: none;">
        <i class="fa-solid fa-ellipsis-vertical" style="font-size: 20px; cursor: pointer;"></i>
    </a>
</header>

<div id="sidebar-direito" class="sidebar-right">
    <a href="javascript:void(0)" class="btn-fechar" onclick="toggleMenuRight()">&times;</a>
    
    <div class="sidebar-header">
        <img src="<?= base_url('assets/images/logo.png.jpeg') ?>" alt="Logo Escola" class="school-logo">
    </div>

    <div class="menu-items">
        <a href="<?= base_url('dashboard') ?>"><i class="fa-solid fa-chart-line"></i> Frequência</a>
        <a href="<?= base_url('faltas') ?>"><i class="fa-solid fa-calendar-days"></i> Faltas por mês</a>
        <a href="<?= base_url('planilhas') ?>"><i class="fa-solid fa-file-excel"></i> Planilha</a>
        <a href="<?= base_url('config') ?>"><i class="fa-solid fa-gear"></i> Configurações</a>
        <a href="<?= base_url('perfil') ?>"><i class="fa-solid fa-user"></i> Perfil</a>
    </div>
</div>

<script>
    function toggleMenuRight() {
        const sidebar = document.getElementById("sidebar-direito");
        const overlay = document.getElementById("overlay-direito");
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    }
</script>