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
        select, input[type="text"], textarea { width: 100%; padding: 14px; font-size: 15px; border: 2px solid #e2e8f0; border-radius: 12px; outline: none; transition: .3s; background: #fff; }
        select:focus, input:focus, textarea:focus { border-color: var(--primary-green); }
        .lista-alunos { max-width: 900px; overflow-y: auto; border: 2px solid #e2e8f0; border-radius: 12px; margin-bottom: 15px; padding: 10px; }
        .item-aluno { display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-bottom: 1px solid #edf2f7; font-size: 14px; }
        .item-aluno:last-child { border-bottom: none; }
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
    
    
            <?php if ($turma_atual_id > 0): ?>
                <div class="card">
                    <div class="lista-alunos">
                        <?php foreach ($alunos_turma_selecionada as $aluno): ?>
                                    <div class="item-aluno">
                                        <span><?= htmlspecialchars($aluno['nome']); ?></span>
                                        <input type="hidden" name="turma_id" value="<?= $turma_atual_id; ?>">
                                        <input type="hidden" name="aluno_id" value="<?= $aluno['id']; ?>">
                                    </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        
</body>
</html>