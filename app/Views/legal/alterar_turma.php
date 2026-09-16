<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('img/logo_WR.png') ?>" type="image/png">
    <title>Gerenciar Alunos e Turmas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{ --primary-green:#3b8540; --primary-hover:#2c6b30; --bg-light:#f4f7f6; --text-dark:#2d3748; --text-gray:#718096; --error-red:#e53e3e; --success-green:#38a169; }
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body{ background:var(--bg-light); min-height:100vh; padding:40px 20px; color:var(--text-dark); }
        .container { max-width: 900px; margin: 0 auto; }
        .card{ background:white; padding:35px; border-radius:25px; box-shadow:0 10px 30px rgba(0,0,0,.04); margin-bottom: 25px; }
        .header-painel { text-align: center; margin-bottom: 30px; }
        .header-painel i { font-size: 50px; color: var(--primary-green); }
        .header-painel h1 { font-size: 24px; color: var(--primary-green); margin-top: 10px; }
        .alerta { padding: 12px; border-radius: 12px; text-align: center; margin-bottom: 25px; font-size: 14px; font-weight: 600; }
        .alerta-erro { background-color: #fff5f5; color: var(--error-red); border: 1px solid #fed7d7; }
        .alerta-sucesso { background-color: #f0fff4; color: var(--success-green); border: 1px solid #c6f6d5; }
        label { font-weight: 600; display: block; margin-bottom: 8px; color: var(--text-dark); }
        select, input[type="text"], textarea { width: 100%; padding: 14px; font-size: 15px; border: 2px solid #e2e8f0; border-radius: 12px; outline: none; transition: .3s; background: #fff; }
        select:focus, input:focus, textarea:focus { border-color: var(--primary-green); }
        .grid-modos { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        @media (max-width: 768px) { .grid-modos { grid-template-columns: 1fr; } }
        .btn { background: var(--primary-green); color: white; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: .3s; }
        .btn:hover { background: var(--primary-hover); }
        .btn-danger { background: var(--error-red); }
        .btn-danger:hover { background: #c53030; }
        .lista-alunos { max-height: 250px; overflow-y: auto; border: 2px solid #e2e8f0; border-radius: 12px; margin-bottom: 15px; padding: 10px; }
        .item-aluno { display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-bottom: 1px solid #edf2f7; font-size: 14px; }
        .item-aluno:last-child { border-bottom: none; }
        .links-footer { text-align: center; margin-top: 20px; }
        .links-footer a { text-decoration: none; color: var(--text-gray); font-weight: 600; }
        .links-footer a:hover { color: var(--primary-green); }
    </style>
</head>
<body>

<div class="container">

    <div class="card header-painel">
        <i class="fa-solid fa-users-gear"></i>
        <h1>Gerenciamento de Alunos por Turma</h1>
    </div>

    <div class="card">
        <form method="GET" action="<?= base_url('alterar_turma') ?>" id="formTurma">
            <label for="selecionar_turma_id">Escolha a Turma que Deseja Alterar:</label>
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
        <div class="grid-modos">
            <div class="card">
                <h2 style="font-size: 18px; color: var(--primary-green); margin-bottom: 15px;"><i class="fa-solid fa-user-minus"></i> Alunos Atuais (<?= count($alunos_turma_selecionada); ?>)</h2>
                
                <div class="lista-alunos">
                    <?php if (empty($alunos_turma_selecionada)): ?>
                        <p style="text-align: center; color: var(--text-gray); padding: 20px; font-size: 14px;">Nenhum aluno cadastrado nesta turma.</p>
                    <?php else: ?>
                        <?php foreach ($alunos_turma_selecionada as $aluno): ?>
                            <div class="item-aluno">
                                <span><?= htmlspecialchars($aluno['nome']); ?></span>
                                <form method="POST" action="<?= base_url('alterar_turma') ?>" onsubmit="return confirm('Tem certeza que deseja remover este aluno?');">
                                    <input type="hidden" name="turma_id" value="<?= $turma_atual_id; ?>">
                                    <input type="hidden" name="aluno_id" value="<?= $aluno['id']; ?>">
                                    <input type="hidden" name="acao" value="remover_individual">
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 10px; font-size: 12px;"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <hr style="border: 0; border-top: 2px solid #edf2f7; margin: 20px 0;">

                <h2 style="font-size: 18px; color: var(--primary-green); margin-bottom: 15px;"><i class="fa-solid fa-user-plus"></i> Inserir Novo Aluno</h2>
                <form method="POST" action="<?= base_url('alterar_turma') ?> "id="formAdicionarAluno">
                    <input type="hidden" name="turma_id" value="<?= $turma_atual_id; ?>">
                    <input type="hidden" name="acao" value="adicionar_individual">
                    <div id="form-nome-aluno" style="margin-bottom: 15px;">
                        <input type="text" name="nome_aluno" placeholder="Nome Completo do Aluno" required>
                    </div>
                    <button type="submit" class="btn"><i class="fa-solid fa-plus"></i> Adicionar Aluno</button>
                </form>
            </div>

            <div class="card">
                <h2 style="font-size: 18px; color: #dd6b20; margin-bottom: 15px;"><i class="fa-solid fa-file-import"></i> Sobrescrever Turma Inteira</h2>
                <p style="font-size: 13px; color: var(--text-gray); margin-bottom: 15px; line-height: 1.5;">
                    <strong>Atenção:</strong> Esta ação apagará todos os alunos atuais desta turma e colocará a lista nova abaixo.
                </p>

                <form method="POST" action="<?= base_url('alterar_turma') ?>" onsubmit="return confirm('ATENÇÃO: Você irá apagar TODOS os alunos atuais desta turma para inserir a nova lista. Continuar?');">
                    <input type="hidden" name="turma_id" value="<?= $turma_atual_id; ?>">
                    <input type="hidden" name="acao" value="sobrescrever_turma">
                    
                    <div style="margin-bottom: 15px;">
                        <label for="lista_nomes">Cole os Nomes (Um Aluno por Linha):</label>
                        <textarea name="lista_nomes" id="lista_nomes" rows="10" placeholder="Exemplo:&#10;ANA JULIA SILVA&#10;CARLOS EDUARDO SANTOS&#10;MARCOS PEREIRA COSTA" required></textarea>
                    </div>

                    <button type="submit" class="btn" style="background-color: #dd6b20;"><i class="fa-solid fa-rotate"></i> Substituir Lista de Alunos</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="card" style="text-align: center; color: var(--text-gray);">
            <i class="fa-solid fa-arrow-up" style="font-size: 30px; margin-bottom: 10px; color: var(--primary-green);"></i>
            <p>Selecione uma turma acima para exibir as opções de gerenciamento.</p>
        </div>
    <?php endif; ?>

    <div class="links-footer">
        <a href="<?= base_url('dashboard') ?>"><i class="fa-solid fa-arrow-left"></i> Voltar para frequência</a>
    </div>

</div><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

       <?php if (session()->getFlashdata('sucesso')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: '<?= session()->getFlashdata('sucesso'); ?>',
            confirmButtonColor: '#3b8540'
        });
    </script>
    <?php elseif (session()->getFlashdata('erro')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '<?= session()->getFlashdata('erro') ?>',
                confirmButtonColor: '#e53e3e'
            });
        </script>
    <?php endif; ?>


</body>
</html>


