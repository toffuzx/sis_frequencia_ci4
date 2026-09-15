 <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso Restrito</title>
        <link rel="icon" href="<?= base_url('img/logo_WR.png') ?>" type="image/png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body { background: #f4f7f6; min-height: 100vh; display: flex; justify-content: center; align-items: center; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px; }
            .card-erro { background: white; width: 100%; max-width: 450px; padding: 40px; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,.08); text-align: center; }
            .card-erro h1 { color: #2d3748; font-size: 22px; margin-bottom: 10px; }
            .card-erro p { color: #718096; font-size: 16px; line-height: 1.6; margin-bottom: 25px; }
            .btn-voltar { display: inline-flex; align-items: center; gap: 10px; text-decoration: none; background: #3b8540; color: white; padding: 12px 25px; border-radius: 12px; font-weight: 600; transition: .3s; }
            .btn-voltar:hover { background: #2c6b30; }
        </style>
    </head>
    <body>
        <div class="card-erro">
            <i class="fa-solid fa-lock" style="font-size: 40px; color: #718096; margin-bottom: 15px;"></i>
            <h1>Acesso Restrito</h1>
            <p>Somente a gestão pode visualizar esta página.</p>
            <a href="javascript:history.back()" class="btn-voltar">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </body>
    </html>