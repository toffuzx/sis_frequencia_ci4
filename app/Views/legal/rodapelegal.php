<?php
    $perfil = session()->get('perfil');
?>
<style>
    body {
        padding-bottom: 40px; 
    }

    /* Rodapé fixo e fino */
    .footer-sistema {
        background-color: var(--primary-dark, #2e5343);
        color: white;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        font-size: 13px;
        z-index: 998;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
        height: 40px;
        overflow: visible;
    }

    
</style>

<footer class="footer-sistema">
    <span>&copy; <?php echo date('Y'); ?> EEEP Walter Ramos de Araújo.</span>

    
</footer>