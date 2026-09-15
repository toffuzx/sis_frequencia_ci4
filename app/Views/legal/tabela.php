<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('img/logo_WR.png') ?>" type="image/png">
    <title>Tabela de Justificativas de Falta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <style>
        :root{ --primary-green:#3b8540; --primary-hover:#2c6b30; --bg-light:#f4f7f6; --text-dark:#2d3748; --text-gray:#718096; --error-red:#e53e3e; --success-green:#38a169; }
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body{ background:var(--bg-light); min-height:100vh; padding:40px 20px; color:var(--text-dark); }
        .container { max-width: 900px; margin: 0 auto; }
        .card{ background:white; padding:35px; border-radius:25px; box-shadow:0 10px 30px rgba(0,0,0,.04); margin-bottom: 25px; }
    </style>
</head>
<body>
    <div class="container">
     <div class="card">
        <form method="GET" action="<?= base_url('tabela') ?>" id="formTurma">
            <label for="selecionar_turma_id">Selecione a Turma:</label>
            <div style="display: flex; gap: 15px;">
                <select name="selecionar_turma_id" id="selecionar_turma_id" required onchange="document.getElementById('formTurma').submit();">
                    <option value="" disabled <?= ($turma_atual_id == 0) ? 'selected' : ''; ?>>Selecione...</option>
                    <?php foreach ($turmas_lista as $t): ?>
                        <option value="<?= $t['id']; ?>" <?= ($turma_atual_id == $t['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($t['serie'] . " — " . $t['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</body>
</html>