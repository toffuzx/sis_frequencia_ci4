
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>EEEP Walter Ramos de Araújo</title>
<link rel="icon" href="img/logo_WR.png" type="image/png">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header>

    <div class="logo-area">
        <img src="img/logo.png.jpeg" alt="Logo">
        <h1>EEEP Walter Ramos</h1>
    </div>

    <nav class="navbar">

        <a href="<?= base_url('login') ?>" class="btn-login">
            Entrar
        </a>

    </nav>

    </header>

<style>
/* NAVBAR */
    
    .navbar{
        display:flex;
        align-items:center;
        gap:15px;
        flex-wrap:wrap;
    }
    
    .navbar a{
        text-decoration:none;
        color:var(--text-dark);
        font-weight:600;
        padding:10px 16px;
        border-radius:12px;
        transition:0.3s;
        display:flex;
        align-items:center;
        gap:8px;
    }
    
    .navbar a i{
        color:var(--primary-green);
    }
    
    .navbar a:hover{
        background:#e8f5e9;
        color:var(--primary-green);
    }
    
    /* BOTÃO LOGIN */
    
    .btn-login{
        background:var(--primary-green);
        color:white !important;
        padding:12px 22px !important;
        border-radius:50px !important;
    }
    
    .btn-login:hover{
        background:var(--primary-hover) !important;
        color:white !important;
    }
    
    /* RESPONSIVO */
    
    @media(max-width:900px){
    
        header{
            flex-direction:column;
            gap:20px;
        }
    
        .navbar{
            justify-content:center;
        }
    
    }

:root{
    --primary-green:#3b8540;
    --primary-hover:#2c6b30;
    --bg-light:#f4f7f6;
    --text-dark:#2d3748;
    --text-gray:#718096;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background-color:var(--bg-light);
    color:var(--text-dark);
}

/* HEADER */

header{
    width:100%;
    padding:20px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.logo-area{
    display:flex;
    align-items:center;
    gap:15px;
}

.logo-area img{
    width:60px;
    height:60px;
    border-radius:50%;
    object-fit:cover;
}

.logo-area h1{
    color:var(--primary-green);
    font-size:22px;
}

.btn-login{
    background:var(--primary-green);
    color:white;
    padding:12px 24px;
    border-radius:50px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.btn-login:hover{
    background:var(--primary-hover);
}

/* HERO */

.hero{
    min-height:85vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:60px 20px;
}

.hero-content{
    max-width:1200px;
    width:100%;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:50px;
    align-items:center;
}

.hero-text h2{
    font-size:48px;
    color:var(--primary-green);
    margin-bottom:20px;
}

.hero-text p{
    font-size:18px;
    line-height:1.8;
    color:var(--text-gray);
    margin-bottom:30px;
}

.hero-buttons{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.btn-primary{
    background:var(--primary-green);
    color:white;
    padding:15px 28px;
    border-radius:50px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.btn-primary:hover{
    background:var(--primary-hover);
}

.btn-secondary{
    border:2px solid var(--primary-green);
    color:var(--primary-green);
    padding:15px 28px;
    border-radius:50px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.btn-secondary:hover{
    background:var(--primary-green);
    color:white;
}

/* CARD */

.info-card{
    background:white;
    border-radius:24px;
    padding:40px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

.info-card img{
    width:100%;
    border-radius:18px;
    margin-bottom:25px;
}

.info-card h3{
    color:var(--primary-green);
    margin-bottom:15px;
}

.info-card p{
    color:var(--text-gray);
    line-height:1.7;
}

/* FEATURES */

.features{
    padding:80px 20px;
}

.features-title{
    text-align:center;
    margin-bottom:50px;
}

.features-title h2{
    color:var(--primary-green);
    font-size:36px;
}

.features-grid{
    max-width:1200px;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}

.feature-box{
    background:white;
    padding:30px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
    transition:0.3s;
}

.feature-box:hover{
    transform:translateY(-5px);
}

.feature-box i{
    font-size:40px;
    color:var(--primary-green);
    margin-bottom:20px;
}

.feature-box h3{
    margin-bottom:10px;
}

.feature-box p{
    color:var(--text-gray);
    line-height:1.6;
}

/* FOOTER */

footer{
    background:white;
    padding:30px;
    text-align:center;
    color:var(--text-gray);
    margin-top:40px;
}

@media(max-width:900px){

    .hero-content{
        grid-template-columns:1fr;
    }

    .hero-text{
        text-align:center;
    }

    .hero-buttons{
        justify-content:center;
    }

    .hero-text h2{
        font-size:36px;
    }

    header{
        padding:20px;
    }
}

</style>
</head>
<body>


<section class="hero">

    <div class="hero-content">

        <div class="hero-text">

            <h2>
                Site de Frequência Escolar Digital 
            </h2>

            <p>
                Plataforma desenvolvida para auxiliar alunos,
                professores e coordenação escolar na organização
                acadêmica, comunicação e gerenciamento de atividades.
            </p>

            <div class="hero-buttons">

            </div>

        </div>

        <div class="info-card">

            <img src="img/escola.jpeg" alt="Escola">

            <h3>
                Educação + Tecnologia
            </h3>

            <p>
                Nosso sistema foi criado para modernizar
                o ambiente escolar e facilitar o acesso
                às informações acadêmicas de maneira
                rápida, segura e organizada.
            </p>

        </div>

    </div>


<?php include 'rodapelegal.php'; ?>

</body>
</html>

