<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créditos</title>
    <link rel="icon" href="./logo_WR.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --bg-light: #f4f7f6; 
            --primary-green: #3b8540;
            --text-dark: #1e293b;
            --text-gray: #64748b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Roboto, sans-serif; }
        
        body { 
            background-color: var(--bg-light); 
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            padding-top: 50px;   
        }

        .creditos-card {
            background-color: white;
            width: 100%;
            max-width: 450px;
            border-radius: 24px;
            padding: 40px 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
            margin: auto;
        }

        .gato-aura {
            width: 100%;
            max-width: 250px;
            height: auto;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .texto-creditos {
            color: var(--text-gray);
            font-size: 15px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .autor {
            color: var(--text-dark);
            font-size: 16px;
            
        }

        .autor strong {
            color: var(--primary-green);
        }


        .ug {
            color: var(--primary-green);
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="creditos-card">
        <img src="img/auraj.png" alt="gato aura" class="gato-aura">
        
        <p class="texto-creditos">
            eu mudei o texto
        </p>
        
        <p class="ug"><strong>naoA</strong></p>
    </div>

</body>
</html>