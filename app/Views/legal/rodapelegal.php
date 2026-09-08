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

    /* Área do link / Botão */
    .footer-sistema .footer-creditos-link {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-decoration: none;
    position: relative; 
    width: 55px; 
    background: transparent !important; /* Remove qualquer fundo cinza padrão */
    background-color: transparent !important; 
    outline: none !important;
    box-shadow: none !important; /* Remove sombras que possam parecer quadrados */
}

    /* Estilo padrão para as duas imagens */
    .footer-creditos-link .footer-img-dev {
        height: 50px;  
        width: auto;   
        object-fit: contain;
        position: absolute;
        transition: opacity 0.2s ease, transform 0.2s ease;
        border-radius: 100px; /* Deixa as bordas arredondadas */
    }

    /* Comportamento da imagem piscando: começa invisível */
    .footer-img-dev.pisca {
        opacity: 0;
    }

    /* --- EFEITO AO PASSAR O MOUSE / CLICAR --- */
    
    /* 1. Dá um leve zoom no botão inteiro */
    .footer-creditos-link:hover .footer-img-dev {
        transform: scale(1.15);
    }

    /* 2. Esconde a imagem normal */
    .footer-creditos-link:hover .footer-img-dev.normal {
        opacity: 0;
    }

    /* 3. Mostra a imagem piscando */
    .footer-creditos-link:hover .footer-img-dev.pisca {
        opacity: 1;
    }
</style>

<footer class="footer-sistema">
    <span>&copy; <?php echo date('Y'); ?> EEEP Walter Ramos de Araújo.</span>
    
    <a href="<?= base_url('creditos') ?> " target="_blank" class="footer-creditos-link" title="Desenvolvedores do Sistema">
        <img src="<?= base_url('img/gato1.png') ?>" alt="Créditos" class="footer-img-dev normal">
        <img src="<?= base_url('img/gato-pisca.png') ?>" alt="Créditos Piscando" class="footer-img-dev pisca">
    </a>
</footer>