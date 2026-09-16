<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('img/logo_WR.png') ?>" type="image/png">
    <title>Justificativas e Faltas por Turma</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{ --primary-green:#3b8540; --primary-hover:#2c6b30; --bg-light:#f4f7f6; --text-dark:#2d3748; --text-gray:#718096; --error-red:#e53e3e; }
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body{ background:var(--bg-light); min-height:100vh; padding:40px 20px; color:var(--text-dark); }
        .container { max-width: 900px; margin: 0 auto; }
        .card{ background:white; padding:30px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,.04); margin-bottom: 25px; }
        select { width: 100%; padding: 14px; font-size: 15px; border: 2px solid #e2e8f0; border-radius: 12px; outline: none; background: #fff; }
        .item-aluno { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; border-bottom: 1px solid #edf2f7; }
        .item-aluno:last-child { border-bottom: none; }
        .badge-faltas { background-color: #fff5f5; color: var(--error-red); padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 13px; }
        .btn-ver { background: var(--primary-green); color: white; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; }
        .btn-ver:hover { background: var(--primary-hover); }
        badge-faltas { background-color: #fff5f5; color: var(--error-red); padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 13px; }

        /* Modal de Visualização do Documento */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
        .modal-content { background: white; margin: 3% auto; width: 80%; max-width: 800px; height: 85vh; border-radius: 16px; display: flex; flex-direction: column; overflow: hidden; }
        .modal-header { padding: 15px 20px; background: var(--primary-green); color: white; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { flex: 1; padding: 0; }
        .modal-body iframe { width: 100%; height: 100%; border: none; }
        .close-btn { color: white; font-size: 24px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <form method="GET" action="<?= base_url('tabela') ?>" id="formTurma">
            <label style="font-weight:600; margin-bottom:8px; display:block;">Selecione a Turma:</label>
            <select name="selecionar_turma_id" id="selecionar_turma_id" required onchange="document.getElementById('formTurma').submit();">
                <option value="" disabled <?= ($turma_atual_id == 0) ? 'selected' : ''; ?>>Selecione...</option>
                <?php foreach ($turmas_lista as $t): ?>
                    <option value="<?= $t['id']; ?>" <?= ($turma_atual_id == $t['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($t['serie'] . " — " . $t['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($turma_atual_id > 0): ?>
        <div class="card">
            <h2 style="font-size:18px; color:var(--primary-green); margin-bottom:15px;">
                <i class="fa-solid fa-list-ol"></i> Alunos com Mais Faltas no Mês
            </h2>
            <div style="border: 2px solid #e2e8f0; border-radius: 12px; overflow:hidden;">
                <?php if (empty($alunos_faltas)): ?>
                    <p style="padding:20px; text-align:center; color:var(--text-gray);">Nenhum registro encontrado nesta turma.</p>
                <?php else: ?>
                    <?php foreach ($alunos_faltas as $aluno): ?>
                        <div class="item-aluno">
                            <div>
                                <strong><?= htmlspecialchars($aluno['aluno_nome']); ?></strong>
                                <span class="badge-faltas"><?= $aluno['total_faltas']; ?> faltas</span>
                                <?php if (!empty($aluno['motivo' ])): ?>
                                    <div style="font-size: 12px; color: var(--text-gray); margin-top: 4px;">
                                        Motivo: <?= htmlspecialchars($aluno['motivo']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div>

                                
                                <?php if (!empty($aluno['justificativa_id']) && !empty($aluno['motivo'] == 'Atestado médico')): ?>
                                    <button class="btn-ver" onclick="abrirDocumento('<?= base_url('arquivo/atestado/' . $aluno['justificativa_id']); ?>' , '<?= htmlspecialchars($aluno['aluno_nome'], ENT_QUOTES); ?>')">
                                        <i class="fa-solid fa-eye"></i> Ver Justificativa
                                    </button>
                               
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function abrirDocumento(url, nomeAluno) {
        if (url) {
            window.open(url, '_blank');
        }
    }
</script>

</body>
</html>