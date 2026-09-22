<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Turma - Frequência</title>

    <link rel="icon" href="<?= base_url('img/logo_WR.png') ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <style>
       
        :root {
            --primary-red: #f01616;
            --primary-dark: #1a4331;
            --primary-green: #3b8540;
            --bg-light: #f4f7f6;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --border-color: #f1f5f9;
            --status-p-bg: #e8f5e9;
            --status-p-text: #2e7d32;
            --status-f-bg: #ffebee;
            --status-f-text: #c62828;
            --status-j-bg: #fff8e1;
            --status-j-text: #f57f17;
        }

        /* =====================================================
           RESET
        ===================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, sans-serif;
        }

        html, body {
            overflow-x: hidden;
        }

        body {
            background-color: var(--bg-light);
            min-height: 100vh;
        }
        

        /* =====================================================
           CONTAINER
        ===================================================== */
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 15px;
        }

        /* =====================================================
           SAUDAÇÃO
        ===================================================== */
        .greeting-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            background-color: var(--primary-dark);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .greeting-text h1 {
            color: var(--primary-dark);
            font-size: 24px;
            margin-bottom: 4px;
        }

        .greeting-text p {
            color: var(--text-gray);
            font-size: 14px;
        }

        /* =====================================================
           CARDS
        ===================================================== */
        .info-cards-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .info-card {
            background-color: white;
            padding: 15px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            flex: 1;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .icon-green {
            background-color: var(--status-p-bg);
            color: var(--primary-green);
        }

        /* =====================================================
           FILTRO
        ===================================================== */
        .filter-form {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }

        .filter-input {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            outline: none;
            font-size: 14px;
            color: var(--text-dark);
            background-color: #fff;
            cursor: pointer;
        }

        .filter-input[type="date"] {
            min-width: 150px;
            flex-shrink: 0;
        }

        /* =====================================================
           TABELA
        ===================================================== */
        .table-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            overflow-x: auto;
            margin-bottom: 40px;
            width: 100%;
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-gray);
            text-transform: uppercase;
        }

        th:first-child, td:first-child {
            text-align: left;
            padding-left: 20px;
            width: 65%;
        }

        .student-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .student-avatar {
            width: 32px;
            height: 32px;
            background-color: var(--primary-dark);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* =====================================================
           NOME DO ALUNO
        ===================================================== */
        .nome-aluno {
            cursor: pointer;
            transition: 0.2s;
        }

        .nome-aluno:hover {
            color: var(--primary-green);
            text-decoration: underline;
        }

        /* =====================================================
           P / F / J
        ===================================================== */
        .status-badge {
            width: 70px;
            height: 30px;
            border-radius: 6px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            font-weight: 700;
            user-select: none;
        }

        .status-P { background-color: var(--status-p-bg); color: var(--status-p-text); }
        .status-F { background-color: var(--status-f-bg); color: var(--status-f-text); }
        .status-J { background-color: var(--status-j-bg); color: var(--status-j-text); }

        .is-interactive {
            cursor: pointer;
            transition: transform 0.1s, filter 0.2s;
        }

        .is-interactive:hover { filter: brightness(0.95); }
        .is-interactive:active { transform: scale(0.85); }

        /* =====================================================
           BOTÃO SALVAR
        ===================================================== */
        .btn-salvar-container {
            text-align: right;
            padding: 20px;
            background-color: #f8fafc;
            border-top: 1px solid var(--border-color);
        }

        .btn-salvar {
            background-color: var(--primary-green);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(59,133,64,0.2);
            transition: background 0.3s;
        }

        .btn-salvar:hover { background-color: #2c6b30; }

        /* =====================================================
           ALERTA
        ===================================================== */
        .alerta-sucesso {
            background-color: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            border: 1px solid #34d399;
        }

        /* =====================================================
           MODAL DO ALUNO
        ===================================================== */
        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 9999;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            padding: 20px;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.aberto { display: flex; }
        
        .modal-aluno {
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            background: white;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            animation: aparecerModal 0.2s ease;
            
        }

        @keyframes aparecerModal {
            from { opacity: 0; transform: translateY(15px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            background-color: var(--primary-dark);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 18px 18px 0 0;
        }

        .modal-header-info { display: flex; align-items: center; gap: 12px; }

        .modal-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: white;
            color: var(--primary-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .modal-header h2 { font-size: 18px; margin-bottom: 3px; }
        .modal-header p { font-size: 13px; opacity: 0.8; }

        .btn-fechar-modal {
            border: none;
            background: transparent;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 38px;
            height: 38px;
            border-radius: 50%;
        }

        .btn-fechar-modal:hover { background: rgba(255,255,255,0.12); }

        .modal-body { padding: 25px; }

        .campo-modal { margin-bottom: 20px; }

        .campo-modal label.titulo-campo {
            display: block;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .data-perfil {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
            color: var(--text-gray);
            font-size: 14px;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: 0.2s;
        }

        .check-item:hover { background: #f8fafc; border-color: #b7d8bf; }

        .check-item input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-green);
            cursor: pointer;
        }

        .check-item span { color: var(--text-dark); font-size: 14px; }

        .observacao-input {
            width: 100%;
            min-height: 100px;
            resize: vertical;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px;
            outline: none;
            font-size: 14px;
        }

        .observacao-input:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(59,133,64,0.12);
        }

        .modal-footer {
            padding: 15px 25px 25px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-cancelar {
            border: 1px solid #cbd5e1;
            background: white;
            color: #475569;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-cancelar:hover { background: #f8fafc; }

        .btn-confirmar {
            border: none;
            background: var(--primary-green);
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-confirmar:hover { background: #2c6b30; }
          .btn-excluir {
            border: none;
            background: var(--primary-red);
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-excluir:hover { background: #c93131; }

        .arquivo-input { width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:10px; background:#fff; font-size:14px; }
        .arquivo-info { margin-top:8px; font-size:13px; color:var(--text-gray); }
        .justificativa-aviso { background:#fff8e1; border:1px solid #fde68a; color:#92400e; padding:10px 12px; border-radius:8px; font-size:13px; margin-bottom:15px; }

        /* =====================================================
           CHOICES
        ===================================================== */
        .choices__inner {
            background-color: #ffffff !important;
            border: 1.5px solid #e0e0e0 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-size: 16px;
            min-height: 48px;
        }

        .choices.is-focused .choices__inner {
            border-color: #467657 !important;
            box-shadow: 0 0 0 3px rgba(70,118,87,0.2) !important;
        }

        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #467657 !important;
            color: white !important;
        }

        .choices__list--single .choices__item,
        .choices__list--dropdown .choices__item {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (min-width: 768px) { .choices { min-width: 200px; } }

        /* =====================================================
           CELULAR
        ===================================================== */
        @media (max-width: 768px) {
            .info-card.white-bg { flex-direction: column; align-items: stretch !important; }
            .filter-form { margin-left: 0; width: 100%; flex-direction: column; }
            .filter-input { width: 100%; }
            .modal-overlay { padding: 10px; }
            .modal-aluno { max-height: 95vh; }
            th:first-child, td:first-child { width: 60%; }
        }
    </style>
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">

   
         <!-- ALERTA DE SUCESSO -->
 
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alerta-sucesso">
            <i class="fa-solid fa-check-circle"></i> Frequência salva com sucesso!
        </div>
    <?php endif; ?>

    <div class="greeting-section">
        <div class="user-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="greeting-text">
            <?php if ($perfil === 'professor'): ?>
                <h1>Olá, Professor(a)!</h1>
                <p>Selecione uma data e uma turma para verificar a frequência.</p>
            <?php elseif ($perfil === 'gestão'): ?>
                <h1>Olá, Gestão!</h1>
                <p>Bem-vindo(a) ao painel de frequência. Aqui você pode acompanhar as presenças e faltas.</p>
                
                <?php endif; ?>
        </div>
    </div>

    <!-- CARD DO FILTRO -->
    <div class="info-cards-row">
        <div class="info-card white-bg" style="justify-content: space-between; width: 100%;">
            <div style="display:flex; align-items:center; gap:15px;">
                <div class="info-icon icon-green">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div class="info-content">
                    <h2>Data da Frequência</h2>
                </div>
            </div>

            <?php if ($perfil === 'professor' || $perfil === 'gestão'): ?>
                <form id="filtro-form" method="GET" action="<?= base_url('dashboard') ?>" class="filter-form">
                    <input type="date" name="data_busca" class="filter-input" value="<?= htmlspecialchars($data_filtro) ?>" max="<?= date('Y-m-d') ?>" onchange="document.getElementById('filtro-form').submit()" required>

                    <select name="turma_busca" class="filter-input" onchange="document.getElementById('filtro-form').submit()" required>
                        <option value="" disabled hidden <?= empty($turma_filtro) ? 'selected' : '' ?>>Selecionar Turma</option>
                        <?php foreach ($todas_turmas as $t): ?>
                            <option value="<?= htmlspecialchars($t['id']) ?>" <?= ($turma_filtro == $t['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['serie'] . ' - ' . $t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- TABELA -->
    <?php if ($mostrar_tabela): ?>
        <form id="form-frequencia" action="<?= base_url('salvar_frequencia') ?>" method="POST">
            <input type="hidden" name="data_registro" value="<?= htmlspecialchars($data_filtro) ?>">
            <input type="hidden" name="turma_id" value="<?= htmlspecialchars($turma_filtro ?? '') ?>">

            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nome do aluno</th>
                                <th>Presença</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($alunos as $aluno): ?>
                            <?php 
                                $justAtraso = is_array($aluno['justificativa'] ?? null) ? $aluno['justificativa'] : [];
                                $justFalta  = is_array($aluno['justificativa_falta'] ?? null) ? $aluno['justificativa_falta'] : [];
                            ?>
                            <tr>
                                <td>
                                    <div class="student-name-cell">
                                        <div class="student-avatar">
                                            <i class="fa-solid fa-user"></i>
                                        </div>

                                        <span class="nome-aluno"
                                            data-aluno="<?= htmlspecialchars($aluno['id'] ?? '') ?>"
                                            data-nome="<?= htmlspecialchars($aluno['nome'] ?? '') ?>"
                                            data-atrasado="<?= htmlspecialchars($justAtraso['chegou_atrasado'] ?? '0') ?>"
                                            data-fardamento="<?= htmlspecialchars($justAtraso['fardamento_incompleto'] ?? '0') ?>"
                                            data-observacoes="<?= htmlspecialchars($justAtraso['observacoes'] ?? '') ?>"
                                        >
                                            <?= htmlspecialchars($aluno['nome']) ?>
                                        </span>
                                    </div>
                                </td>

                                <?php foreach ($aluno['aulas'] as $numero_aula => $status): ?>
                                    <td>
                                        <span class="status-badge aula-badge status-<?= $status ?> <?php
                                                if ($perfil === 'professor' && in_array($status, ['P','F'], true)) {
                                                    echo 'is-interactive';
                                                } elseif ($perfil === 'gestão' && in_array($status, ['F','J'], true)) {
                                                    echo 'is-interactive justificativa-interactive';
                                                }
                                               
                                            ?>"
                                            data-aluno="<?= htmlspecialchars($aluno['id'] ?? '') ?>"
                                            data-aula="<?= htmlspecialchars($numero_aula) ?>"
                                            data-justificativa-id="<?= htmlspecialchars($justFalta['id'] ?? '') ?>"
                                            data-motivo="<?= htmlspecialchars($justFalta['motivo'] ?? '') ?>"
                                            data-observacoes="<?= htmlspecialchars($justFalta['observacoes'] ?? '') ?>"
                                            data-arquivo-nome="<?= htmlspecialchars($justFalta['arquivo_nome'] ?? '') ?>"
                                            data-arquivo-caminho="<?= htmlspecialchars($justFalta['arquivo_caminho'] ?? '') ?>"
                                        >
                                            <?= htmlspecialchars($status) ?>
                                        </span>

                                        <input type="hidden"
                                            name="freq[<?= htmlspecialchars($aluno['id']) ?>][<?= htmlspecialchars($numero_aula) ?>]"
                                            id="hidden_<?= htmlspecialchars($aluno['id']) ?>_<?= htmlspecialchars($numero_aula) ?>"
                                            value="<?= htmlspecialchars($status) ?>"
                                        >
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($perfil === 'professor'): ?>
                    <div class="btn-salvar-container">
                        <button type="submit" class="btn-salvar">
                            <i class="fa-solid fa-floppy-disk"></i> Enviar Frequência do Dia
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </form>

    <?php elseif ($perfil === 'professor' || $perfil === 'gestão'): ?>
        <div style="text-align:center; padding:50px; color:#64748b;">
            <i class="fa-solid fa-list-check" style="font-size:40px; margin-bottom:15px; opacity:0.5;"></i>
            <h3>Nenhuma turma selecionada</h3>
            <p>Use o filtro acima para carregar a frequência de uma turma.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'rodapelegal.php'; ?>

<!-- MODAL DO PERFIL DO ALUNO-->
<div id="modal-aluno" class="modal-overlay">
    <div class="modal-aluno" role="dialog" aria-modal="true" aria-labelledby="modal-nome-aluno">
        <div class="modal-header">
            <div class="modal-header-info">
                <div class="modal-avatar"><i class="fa-solid fa-user"></i></div>
                <div>
                    <h2 id="modal-nome-aluno">Aluno</h2>
                    <p>Perfil de frequência</p>
                </div>
            </div>
                
        </div>

        <div class="modal-body">
            <div class="campo-modal">
                <label class="titulo-campo">Aluno</label>
                <div id="modal-nome-display" class="data-perfil">-</div>
            </div>
          
            <div class="campo-modal">
                <label class="titulo-campo">Data da frequência</label>
                <div id="modal-data" class="data-perfil"><?= htmlspecialchars($data_filtro) ?></div>
            </div>
            <div class="campo-modal">
                <label class="titulo-campo">Justificativas</label>
                <label class="check-item">
                    <input type="checkbox" class="justificativa-checkbox" name="justificativa_atrasado" value="1">
                    <span>Chegou atrasado</span>
                </label>
                <label class="check-item">
                    <input type="checkbox" class="justificativa-checkbox" name="justificativa_fardamento" value="1">
                    <span>Fardamento incompleto</span>
                </label>
            </div>

            <div class="campo-modal">
                <label class="titulo-campo" for="observacoes-aluno">Observações extras</label>
                <textarea id="observacoes-aluno" class="observacao-input" name="observacoes" placeholder="Digite uma observação sobre o aluno..."></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" id="cancelar-modal">Fechar</button>
            <button type="button" class="btn-confirmar" id="salvar-perfil-aluno">
                
                <i class="fa-solid fa-check"></i> Marcar
            </button>
            <button type="button" class="btn-excluir" id="excluir-modal-frequencia">excluir</button>
        </div>
    </div>
</div>
<div id="modal-justificativa-falta" class="modal-overlay">
    <div class="modal-aluno" role="dialog" aria-modal="true">
        <div class="modal-header">
            <div class="modal-header-info">
                <div class="modal-avatar"><i class="fa-solid fa-user"></i></div>
                <div><h2 id="justificativa-nome">Justificar falta</h2><p id="justificativa-data">-</p></div>
            </div>
           
        </div>
        <form id="form-justificativa-falta" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="aluno_id" id="justificativa-aluno-id">
                <input type="hidden" name="data_registro" value="<?= htmlspecialchars($data_filtro) ?>">
                <div class="justificativa-aviso">Ao salvar, a falta será marcada como <strong>J (Justificada)</strong>.</div>
                
                <div class="campo-modal">
                    <label class="titulo-campo" for="motivo">Motivo</label>
                    <select id="motivo" name="motivo" class="filter-input" style="width:100%;" required>
                        <option value="" disabled selected hidden>Selecionar motivo</option>
                        <option value="Atestado médico">Atestado médico</option>
                        <option value="Compromisso escolar">Compromisso escolar</option>
                        <option value="Problema familiar">Problema familiar</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <div class="campo-modal">
                    <label class="titulo-campo" for="observacoes-falta">Observações</label>
                    <textarea id="observacoes-falta" name="observacoes" class="observacao-input" placeholder="Observações sobre a justificativa..."></textarea>
                </div>
                <div class="campo-modal"id="atestado-campo" style="display:none;">
                    <label class="titulo-campo" for="atestado-arquivo">Atestado / documento</label>
                    <input id="atestado-arquivo" name="atestado" class="arquivo-input" type="file" accept=".pdf,.jpg,.jpeg,.png,.pdf,image/*">
                    <div id="arquivo-existente" class="arquivo-info"></div>
                    <div id="arquivo-link" class="arquivo-info"></div>
                </div>
                
<script>
const selectFalta = document.getElementById('motivo');
const blocoExtra = document.getElementById('atestado-campo');
const inputArquivo = document.getElementById('atestado-arquivo');
const divArquivoExistente = document.getElementById('arquivo-existente');

// Função centralizada para validar o estado do campo de arquivo
function verificarMotivo() {
    if (selectFalta.value === "Atestado médico") {
        blocoExtra.style.display = "block"; 
        
        // Verifica se o texto na div diz que há um documento anexado
        const temArquivoSalvo = divArquivoExistente.textContent.includes("Documento atual:");
        
        // Só obriga o upload se o aluno NÃO tiver um arquivo já gravado
        inputArquivo.required = !temArquivoSalvo;          
    } else {
        blocoExtra.style.display = "none"; 
        inputArquivo.required = false;       
        inputArquivo.value = "";               
    }
}

// Monitora se o usuário mudar a opção manualmente clicando na tela
selectFalta.addEventListener('change', verificarMotivo);

document.addEventListener('DOMContentLoaded', function () {
    const alunos = document.querySelectorAll('.nome-aluno');

    alunos.forEach(function (alunoEl) {
        const idAluno = alunoEl.getAttribute('data-aluno');
        const atrasado = alunoEl.getAttribute('data-atrasado') === '1';
        const fardamento = alunoEl.getAttribute('data-fardamento') === '1';
        const observacoes = (alunoEl.getAttribute('data-observacoes') || '').trim();

        // Condição: verifica se existe qualquer dado salvo
        const TemF = fardamento;
        const TemO = observacoes !== '';
        const TemA = atrasado;


        if (TemA) {
            alunoEl.innerHTML += ' <i class= "fa-solid fa-user-clock " title="Atrasado"></i>';
        }
        if (TemF) {
            alunoEl.innerHTML += ' <i class="fa-solid fa-user-xmark " title="justificativa"></i>';
        }
        if (TemO) {
            alunoEl.innerHTML += ' <i class= "fa-solid fa-user-pen  " title="Possui observações"></i>';
        }
    });
});
</script>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn-excluir" id="excluir-modal-justificativa">excluir</button>
                <button type="button" class="btn-cancelar" id="cancelar-modal-justificativa">fechar</button>
                <button type="submit" class="btn-confirmar" id="btn-salvar-justificativa"><i class="fa-solid fa-check"></i> Salvar justificativa</button>
            </div>
        </form>
    </div>
</div>
<!-- JAVASCRIPT-->
<!-- JAVASCRIPT CORRIGIDO -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Alternância de P/F (Professor) e Abertura de Justificativa (Gestão)
    const botoesStatus = document.querySelectorAll('.aula-badge.is-interactive');

    botoesStatus.forEach(function (botao) {
        botao.addEventListener('click', function (event) {
            event.preventDefault(); 
            event.stopPropagation();

            const perfilAtual = <?= json_encode($perfil) ?>;
            const statusAtual = this.textContent.trim();
            
            if (perfilAtual === 'gestão') { 
                abrirModalJustificativa(this); 
                return; 
            }

            const alunoId = this.dataset.aluno;
            const aulaNum = this.dataset.aula;
            const hiddenInput = document.getElementById('hidden_' + alunoId + '_' + aulaNum);

            if (statusAtual === 'P') {
                this.textContent = 'F'; 
                this.classList.remove('status-P'); 
                this.classList.add('status-F'); 
                if (hiddenInput) hiddenInput.value = 'F';
            } else if (statusAtual === 'F') {
                this.textContent = 'P'; 
                this.classList.remove('status-F'); 
                this.classList.add('status-P'); 
                if (hiddenInput) hiddenInput.value = 'P';
            }
        });
    });

    // 2. Modal de perfil / frequência do aluno
    const modal = document.getElementById('modal-aluno');
    const cancelarModal = document.getElementById('cancelar-modal');
    const nomeModal = document.getElementById('modal-nome-aluno');
    const nomeDisplay = document.getElementById('modal-nome-display');
    const dataModal = document.getElementById('modal-data');
    const observacoes = document.getElementById('observacoes-aluno');

    document.querySelectorAll('.nome-aluno').forEach(function (nome) {
        nome.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

           const perfilAtual = <?= json_encode($perfil) ?>;
        const alunoId = this.getAttribute('data-aluno');

        // Busca os badges de status das aulas deste aluno
        const badgesAluno = document.querySelectorAll('.aula-badge[data-aluno="' + alunoId + '"]');
        
        // Verifica se alguma das aulas do aluno está marcada como 'F'
        let possuiFalta = false;
        badgesAluno.forEach(function (badge) {
            if (badge.textContent.trim() === 'F') {
                possuiFalta = true;
            }
        });

        // Se for professor e o aluno tiver 'F', impede a abertura do modal
        if (perfilAtual === 'professor' && possuiFalta) {
            Swal.fire({
        icon: 'info',
        title: 'Atenção',
        text: 'Não é possível alterar as justificativas de um aluno que possui falta marcada.',
        confirmButtonColor: '#3b8540',
        confirmButtonText: 'Entendido'
    });
    return;
        
        }

        // --- Código normal de abertura do modal abaixo ---
        const nomeAluno = this.getAttribute('data-nome');

        modal.setAttribute('data-aluno', alunoId);
        nomeModal.textContent = nomeAluno;
        nomeDisplay.textContent = nomeAluno;
        dataModal.textContent = "<?= htmlspecialchars($data_filtro) ?>";

        const atrasadoSalvo = this.getAttribute('data-atrasado') === '1';
        const fardamentoSalvo = this.getAttribute('data-fardamento') === '1';
        const observacoesSalvas = this.getAttribute('data-observacoes') || '';

        const inputAtrasado = document.querySelector('input[name="justificativa_atrasado"]');
        const inputFardamento = document.querySelector('input[name="justificativa_fardamento"]');
        const btnSalvarPerfil = document.getElementById('salvar-perfil-aluno');

        if (inputAtrasado) inputAtrasado.checked = atrasadoSalvo;
        if (inputFardamento) inputFardamento.checked = fardamentoSalvo;
        if (observacoes) observacoes.value = observacoesSalvas;

        if (perfilAtual === 'gestão') {
            if (inputAtrasado) inputAtrasado.disabled = true;
            if (inputFardamento) inputFardamento.disabled = true;
            if (observacoes) observacoes.disabled = true;
            if (btnSalvarPerfil) btnSalvarPerfil.style.display = 'none';
        } else if (perfilAtual === 'professor') {
            if (inputAtrasado) inputAtrasado.disabled = false;
            if (inputFardamento) inputFardamento.disabled = false;
            if (observacoes) observacoes.disabled = false;
            if (btnSalvarPerfil) btnSalvarPerfil.style.display = 'inline-flex';
            if (btnExcluirFreq) btnExcluirFreq.style.display = 'none';
        }

        modal.classList.add('aberto');
        document.body.style.overflow = 'hidden';
    });
    });

    function fecharJanelaAluno() {
        modal.classList.remove('aberto');
        document.body.style.overflow = '';
    }

    if (cancelarModal) cancelarModal.addEventListener('click', fecharJanelaAluno);

    modal.addEventListener('click', function (event) {
        if (event.target === modal) fecharJanelaAluno();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('aberto')) fecharJanelaAluno();
    });

    // Gravar justificativa do Perfil
    const btnMarcar = document.getElementById('salvar-perfil-aluno');
    if (btnMarcar) {

        btnMarcar.addEventListener('click', function () {
            const alunoId = modal.getAttribute('data-aluno');
            const atrasado = document.querySelector('input[name="justificativa_atrasado"]').checked;
            const fardamento = document.querySelector('input[name="justificativa_fardamento"]').checked;
            const texto = observacoes.value.trim();

            fetch('<?= site_url("salvar_justificativa") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    aluno_id: alunoId,
                    data_registro: "<?= htmlspecialchars($data_filtro) ?>",
                    chegou_atrasado: atrasado ? 1 : 0,
                    fardamento_incompleto: fardamento ? 1 : 0,
                    observacoes: texto
                })
            })
            .then(response => response.json())
            .then(resultado => {
                if (resultado.sucesso) {
                    fecharJanelaAluno();
                     Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: resultado.mensagem || 'Justificativa adicionada com sucesso.',
                            confirmButtonColor: '#3b8540'
                        }).then(() => window.location.reload());
                } else {
                    alert(resultado.mensagem || 'Erro ao salvar justificativa.');
                }
            })
            .catch(() => alert('Erro ao salvar a justificativa.'));
        });
    }

    // 3. Modal - Gestão (Faltas / Atestados)
    const modalJust = document.getElementById('modal-justificativa-falta');
    const formJust = document.getElementById('form-justificativa-falta');
    const btnSalvarJust = document.getElementById('btn-salvar-justificativa');
    let botaoJustificativaAtual = null;

    function abrirModalJustificativa(botao) {
        botaoJustificativaAtual = botao;
        const nomeAluno = document.querySelector('.nome-aluno[data-aluno="' + botao.dataset.aluno + '"]');

        if (!modalJust) return;

        document.getElementById('justificativa-nome').textContent = nomeAluno ? nomeAluno.dataset.nome : 'Justificar falta';
        document.getElementById('justificativa-data').textContent = 'Data: <?= htmlspecialchars($data_filtro) ?>';
        document.getElementById('justificativa-aluno-id').value = botao.dataset.aluno || '';
        document.getElementById('motivo').value = botao.dataset.motivo || '';
        document.getElementById('observacoes-falta').value = botao.dataset.observacoes || '';
        document.getElementById('atestado-arquivo').value = '';

        const arquivoExistente = document.getElementById('arquivo-existente');
        arquivoExistente.textContent = botao.dataset.arquivoNome
            ? 'Documento atual: ' + botao.dataset.arquivoNome
            : 'Nenhum documento anexado.';

        const linkArquivo = document.getElementById('arquivo-link');
        const baseUrl = '<?= site_url('download/atestado') ?>';

        if (botao.dataset.arquivoNome && botao.dataset.justificativaId) {
            linkArquivo.innerHTML = '<a href="' + baseUrl + '/' + encodeURIComponent(botao.dataset.justificativaId) + '" target="_blank"><i class="fa-solid fa-download"></i> Abrir / baixar documento</a>';
        } else {
            linkArquivo.innerHTML = '';
        }

        const statusAtual = botao.textContent.trim();
        const somenteVisualizacao = (statusAtual === 'J');

        btnSalvarJust.style.display = somenteVisualizacao ? 'none' : '';
        document.getElementById('motivo').disabled = somenteVisualizacao;
        document.getElementById('observacoes-falta').disabled = somenteVisualizacao;
        document.getElementById('atestado-arquivo').disabled = somenteVisualizacao;

        const btnExcluir = document.getElementById('excluir-modal-justificativa');
        if (btnExcluir) {
            btnExcluir.style.display = (statusAtual === 'J') ? 'inline-block' : 'none';
        }

        modalJust.classList.add('aberto');
        document.body.style.overflow = 'hidden';

        if (typeof verificarMotivo === 'function') verificarMotivo();
    }

    function fecharModalJustificativa() { 
        modalJust.classList.remove('aberto'); 
        document.body.style.overflow = ''; 
    }

    function excluirModalJustificativa() {
        modalJust.classList.remove('aberto'); 
        document.body.style.overflow = ''; 
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja realmente remover esta justificativa?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                const alunoId = document.getElementById('justificativa-aluno-id').value;
                const dataRegistro = "<?= htmlspecialchars($data_filtro) ?>";

                fetch('<?= site_url("excluir_justificativa") ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ aluno_id: alunoId, data_registro: dataRegistro })
                })
                .then(response => response.json())
                .then(resultado => {
                    if (resultado.sucesso) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Excluído!',
                            text: resultado.mensagem || 'Justificativa removida com sucesso.',
                            confirmButtonColor: '#3b8540'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Erro', resultado.mensagem || 'Não foi possível excluir.', 'error');
                    }
                })
                .catch(() => Swal.fire('Erro', 'Erro ao executar a ação.', 'error'));
            }
        });
    }

    function excluirModalFrequencia() {
        fecharJanelaAluno();
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja realmente remover esta marcação?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                const alunoId = modal.getAttribute('data-aluno');
                const dataRegistro = "<?= htmlspecialchars($data_filtro) ?>";

                fetch('<?= site_url("excluir_frequencia") ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ aluno_id: alunoId, data_registro: dataRegistro })
                })
                .then(response => response.json())
                .then(resultado => {
                    if (resultado.sucesso) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Excluído!',
                            text: resultado.mensagem || 'Frequência removida com sucesso.',
                            confirmButtonColor: '#3b8540'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Erro', resultado.mensagem || 'Não foi possível excluir.', 'error');
                    }
                })
                .catch(() => Swal.fire('Erro', 'Erro ao executar a ação.', 'error'));
            }
        });
    }

    // Vinculação de eventos dos botões dos modais
    const btnCancelarJust = document.getElementById('cancelar-modal-justificativa');
    const btnExcluirJust = document.getElementById('excluir-modal-justificativa');
    const btnExcluirFreq = document.getElementById('excluir-modal-frequencia');

    if (btnCancelarJust) btnCancelarJust.addEventListener('click', fecharModalJustificativa);
    if (btnExcluirJust) btnExcluirJust.addEventListener('click', excluirModalJustificativa);
    if (btnExcluirFreq) btnExcluirFreq.addEventListener('click', excluirModalFrequencia);

    // Submissão da justificativa
    if (formJust) {
        formJust.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formJust);

            btnSalvarJust.disabled = true;
            btnSalvarJust.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Salvando...';

            fetch('<?= site_url("salvar_justificativa_falta") ?>', {
                method: 'POST',
                body: fd
            })
            .then(async response => {
                const texto = await response.text();
                try { return JSON.parse(texto); }
                catch (e) { throw new Error('Servidor não retornou JSON válido.'); }
            })
            .then(resultado => {
                if (!resultado.sucesso) throw new Error(resultado.mensagem || 'Erro ao salvar.');

                fecharModalJustificativa();
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: resultado.mensagem || 'Justificativa salva com sucesso.',
                    confirmButtonColor: '#3b8540'
                }).then(() => window.location.reload());
            })
            .catch(err => {
                alert(err.message);
                btnSalvarJust.disabled = false;
                btnSalvarJust.innerHTML = '<i class="fa-solid fa-check"></i> Salvar justificativa';
            });
        });
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>