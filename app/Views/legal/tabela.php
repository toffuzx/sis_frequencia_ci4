<?php
    $perfil = session()->get('perfil');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faltas por Mês - <?= esc($nome_turma); ?></title>
    <link rel="icon" href="<?= base_url('img/logo_WR.png'); ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-dark: #1b3322;
            --primary-green: #3b8540;
            --primary-hover: #2c6b30;
            --border-color: #f1f5f9;
            --text-gray: #666;
            --error-red: #c94c4c;
            --badge-warning: #d97706;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f7f4; color: #333; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .page-title-section { margin-bottom: 25px; }

        .dashboard-filter-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            border: 1px solid #eef2ee;
            margin-bottom: 25px;
        }

        .filter-left-info { display: flex; align-items: center; gap: 15px; }
        .filter-icon-circle {
            width: 50px; height: 50px;
            background-color: #e8f0e9;
            color: var(--primary-dark);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        .filter-left-info h3 { font-size: 20px; color: #111; font-weight: bold; margin-bottom: 2px; }
        .filter-left-info p { font-size: 13px; color: var(--text-gray); }

        .filter-inputs-group { display: flex; align-items: center; gap: 12px; }
        .filter-inputs-group select {
            padding: 10px 16px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: #fff;
            color: #495057;
            outline: none;
            min-width: 180px;
            cursor: pointer;
        }

        .cards-grid { display: flex; gap: 15px; margin-bottom: 25px; }
        .card-resumo {
            background: white; padding: 20px; border-radius: 15px; flex: 1;
            display: flex; align-items: center; gap: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #e2e8e2;
        }

        .badge-numero {
            width: 50px; height: 50px; border-radius: 50%;
            background-color: #fce4e4; color: var(--error-red);
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 18px;
        }

        .chart-box, .table-box {
            background: white; padding: 20px; border-radius: 15px;
            margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #e2e8e2;
        }

        .tables-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .tables-grid .table-box {
            flex: 1;
            margin-bottom: 0;
        }

        

        .section-title { font-size: 16px; font-weight: bold; color: var(--primary-dark); margin-bottom: 15px; }
        .tabela-faltas { width: 100%; border-collapse: collapse; }
        .tabela-faltas th, .tabela-faltas td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .tabela-faltas th { color: #777; font-size: 13px; }

        .tabela-coisas { width: 100%; border-collapse: collapse; }
        .tabela-coisas th, .tabela-coisas td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .tabela-coisas th { color: #777; font-size: 13px; }



        .tabela-faltas th:first-child, .tabela-faltas td:first-child {
            text-align: left;
            padding-left: 20px;
            
        }

        .aluno-nome-col { display: flex; align-items: flex-start; gap: 10px; min-width: 0; }
        .aluno-nome-col i { color: var(--primary-dark); background: #e8f0e9; padding: 6px; border-radius: 50%; font-size: 12px; margin-top: 2px; }
        
        /* ESTILO LINK DO NOME DO ALUNO */
        .nome-aluno-link { 
            font-size: 14px; 
            font-weight: bold; 
            cursor: pointer; 
            color: var(--primary-green);
            text-decoration: underline;
            transition: color 0.2s;
        }
        .nome-aluno-link:hover { 
            color: var(--primary-hover); 
        }

        .badge-falta-tabela {
            background: #fce4e4; color: var(--error-red);
            padding: 4px 10px; border-radius: 12px;
            font-weight: bold; font-size: 13px;
        }

        .badge-justificada-tabela {
            background: #fef3c7; color: var(--badge-warning);
            padding: 4px 10px; border-radius: 12px;
            font-weight: bold; font-size: 13px;
        }

        .btn-ver {
            background: var(--primary-green); color: white; border: none;
            padding: 6px 12px; border-radius: 8px; cursor: pointer;
            font-size: 12px; display: inline-flex; align-items: center;
            gap: 6px; text-decoration: none;
        }

        .btn-ver:hover { background: var(--primary-hover); }
        
        .motivo-texto { font-size: 12px; color: #444; margin-top: 4px; font-weight: 600; }
        .obs-texto { font-size: 11px; color: var(--text-gray); margin-top: 2px; font-style: italic; }

        @media (max-width: 900px) {
            .tabela-coisas th, .tabela-coisas td, .tabela-faltas th, .tabela-faltas td { font-size: 12px; padding: 10px; }
            .table-box { flex-direction: column; align-items: stretch; gap: 15px; }
            .dashboard-filter-card { flex-direction: column; align-items: stretch; gap: 15px; }
            .filter-inputs-group { flex-direction: column; width: 100%; gap: 10px; }
            .filter-inputs-group select { width: 100%; min-width: 0; }
            .cards-grid { flex-direction: column; }
            .tables-grid { flex-direction: column; }
            .tables-grid th, .tables-grid td { font-size: 13px; padding: 14px; }
        }

        /* ESTILOS DO MODAL */
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

        .modal-overlay.aberto {
            display: flex !important;
        }

        .modal-aluno {
            width: 100%;
            max-width: 580px;
            max-height: 85vh;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            animation: aparecerModal 0.2s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fdfdfd;
        }

        .modal-header h3 { font-size: 16px; color: var(--primary-dark); }
        .modal-header .close-btn {
            font-size: 22px; cursor: pointer; color: #888; border: none; background: none; line-height: 1;
        }
        .modal-header .close-btn:hover { color: #000; }

        .modal-body {
            padding: 20px 24px;
            overflow-y: auto;
        }

        .item-falta-card {
            background: #f9fbf9;
            border: 1px solid #e3e8e3;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 12px;
        }

        .item-falta-card:last-child { margin-bottom: 0; }

        .item-falta-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .falta-data {
            font-weight: bold;
            font-size: 13px;
            color: var(--primary-dark);
        }

        .text-empty {
            color: #999;
            font-style: italic;
            font-size: 12px;
        }

        @keyframes aparecerModal {
            from { opacity: 0; transform: translateY(15px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="container">
        <div class="page-title-section">
            <h2 style="color: #1b3322;">Faltas da Escola</h2>
            <p style="color: #666; font-size: 14px;">Controle e comparativo de frequência por turma e aluno</p>
        </div>

        <div class="dashboard-filter-card">
            <div class="filter-left-info">
                <div class="filter-icon-circle">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <h3>Filtros de Pesquisa</h3>
                    <p>Selecionado: <strong><?= esc($nome_mes_exibicao . " de " . $ano_exibicao); ?></strong></p>
                </div>
            </div>

            <div class="filter-inputs-group">
                <select id="select-mes" onchange="atualizarFiltros()">
                    <?php foreach ($opcoes_meses as $valor_mes => $texto_mes): ?>
                        <option value="<?= $valor_mes ?>" <?= ($mes_selecionado == $valor_mes) ? 'selected' : ''; ?>>
                            <?= esc($texto_mes) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select id="select-turma" onchange="atualizarFiltros()">
                    <option value="0" hidden disable <?= empty($turma_id) ? 'selected' : '' ?>>Selecionar Turma</option>
                    <?php foreach ($lista_turmas_escola as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($turma_id == $t['id']) ? 'selected' : ''; ?>>
                            <?= esc($t['serie'] . ' ' . $t['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- RANKING DE TURMAS MAIS FALTOSAS -->
        <?php if (!empty($ranking_turmas_faltas)): ?>
            <div class="table-box">
                <div class="section-title">
                    <i class="fa-solid fa-ranking-star" style="margin-right: 8px;"></i>
                    Ranking de Turmas Mais Faltosas (<?= esc($nome_mes_exibicao); ?>)
                </div>
                <table class="tabela-coisas" >
                    <thead>
                        <tr>
                            <th></th>
                            <th>Turma</th>
                            <th>Faltas reais</th>
                            <th>Percentual de faltas</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ranking_turmas_faltas as $index => $turma_item): ?>
                            <tr>
                                <td style="font-weight: bold; width: 30px;"><?= $index + 1; ?>º</td>
                                <td>
                                    <div class="aluno-nome-col">
                                        <i class="fa-solid fa-users"></i>
                                        <span><?= esc($turma_item['nome']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-falta-tabela">
                                        <?= str_pad($turma_item['faltas_reais'], 2, "0", STR_PAD_LEFT); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-falta-tabela">
                                        <?= $turma_item['percentual'] . '%'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('tabela'); ?>?mes=<?= $mes_selecionado ?>&turma_id=<?= $turma_item['id'] ?>" style="color: var(--primary-dark); font-weight: bold; font-size: 13px; text-decoration: none;">
                                        Ver Alunos <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($mostrar_conteudo): ?>
            
            <div class="cards-grid">
                <div class="card-resumo">
                    <div class="badge-numero"><?= str_pad($total_faltas_turma, 2, "0", STR_PAD_LEFT); ?></div>
                    <div>
                        <strong style="font-size: 15px;">Faltas Reais</strong>
                        <p style="font-size: 12px; color: #777;">Total de ausências no mês em <?= esc($nome_turma); ?></p>
                    </div>
                </div>
                
                <div class="card-resumo">
                    <div class="badge-numero"><?= $percentual_faltas_turma; ?>%</div>
                    <div>
                        <strong style="font-size: 15px;">Percentual de faltas</strong>
                        <p style="font-size: 12px; color: #777;">Ausências da turma no mês</p>
                    </div>
                </div>
            </div>

            <div class="chart-box">
                <div class="section-title">Ausências da Turma: <?= esc($nome_turma); ?></div>
                <canvas id="graficoFaltas" style="max-height: 220px;"></canvas>
            </div>

            <!-- GRID LADO A LADO DAS TABELAS DE ALUNOS -->
            <div class="tables-grid">
                
                <!-- TABELA 1: ALUNOS FALTOSOS SEM JUSTIFICATIVA -->
                <div class="table-box">
                    <div class="section-title">
                        <i class="fa-solid fa-user-xmark" style="color: var(--error-red); margin-right: 6px;"></i>
                        Faltas Sem Justificativa
                    </div>
                    <table class="tabela-faltas">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Faltas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tabela_alunos_sem_justificativa)): ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #777; padding: 20px;">
                                        Nenhum aluno com falta sem justificativa!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tabela_alunos_sem_justificativa as$aluno): ?>
                                    <tr>
                                        <td>
                                            <div class="aluno-nome-col">
                                                <i class="fa-solid fa-user"></i>
                                                <span><?= esc($aluno['nome']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-falta-tabela">
                                                <?= str_pad($aluno['faltas_reais'], 2, "0", STR_PAD_LEFT); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- TABELA 2: ALUNOS COM FALTAS JUSTIFICADAS -->
                <div class="table-box">
                    <div class="section-title">
                        <i class="fa-solid fa-file-signature" style="color: var(--primary-green); margin-right: 6px;"></i>
                        Faltas Justificadas
                    </div>
                    <table class="tabela-faltas">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Faltas</th>  
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tabela_alunos_justificados)): ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #777; padding: 20px;">
                                        Nenhuma falta justificada neste mês!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tabela_alunos_justificados as$aluno): ?>
                                    <tr>
                                        <td>
                                            <div class="aluno-nome-col">
                                                <i class="fa-solid fa-user"></i>
                                                <div>
                                                    <!-- BOTÃO COM APARÊNCIA DE LINK -->
                                                    <a href="javascript:void(0)" 
                                                       class="nome-aluno-link"
                                                       data-nome="<?= esc($aluno['nome']); ?>"
                                                       data-detalhes='<?= base64_encode(json_encode($aluno['detalhes_faltas'] ?? [])); ?>'>
                                                        <?= esc($aluno['nome']); ?>
                                                    </a>
            
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-justificada-tabela">
                                                <?= str_pad($aluno['faltas_reais'], 2, "0", STR_PAD_LEFT); ?>
                                            </span>
                                        </td>
                                        
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        <?php else: ?>
            <div style="text-align: center; padding: 50px; background: white; border-radius: 15px; border: 1px solid #e2e8e2; color: #64748b;">
                <i class="fa-solid fa-list-check" style="font-size: 40px; margin-bottom: 15px; opacity: 0.5; color: var(--primary-dark);"></i>
                <h3>Nenhuma turma individual selecionada</h3>
                <p style="font-size: 14px; margin-top: 5px;">Selecione uma turma específica no filtro acima para analisar os dados individuais dos alunos e o gráfico da turma.</p>
            </div>
        <?php endif; ?>

    </div>

    <?php include 'rodapelegal.php'; ?>

    <!-- MODAL DE HISTÓRICO DE FALTAS -->
    <div id="modal-aluno" class="modal-overlay">
        <div class="modal-aluno">
            <div class="modal-header">
                <h3>Detalhes das Faltas - <span id="nome-aluno-modal"></span></h3>
                <button type="button" id="cancelar-modal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body" id="modal-corpo-faltas">
                <!-- Conteúdo dinâmico via JS -->
            </div>
        </div>
    </div>

    <script>
        function atualizarFiltros() {
            const mes = document.getElementById('select-mes').value;
            const selectTurma = document.getElementById('select-turma');
            
            let url = '<?= base_url('tabela'); ?>?mes=' + mes;
            if (selectTurma && selectTurma.value !== '0') {
                url += '&turma_id=' + selectTurma.value;
            }
            
            window.location.href = url;
        }

        const dadosFaltasReais = <?= json_encode($valores_faltas ?? []); ?>;
        const dadosJustificados = <?= json_encode($valores_justificadas ?? []); ?>;

        const canvasGrafico = document.getElementById('graficoFaltas');
        if (canvasGrafico) {
            const ctx = canvasGrafico.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels_dias ?? []); ?>,
                    datasets: [{
                        label: 'Total de Ausentes',
                        data: <?= json_encode($valores_totais ?? []); ?>,
                        backgroundColor: '#beddc2',    
                        maxBarThickness: 35,         
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const total = context.raw;
                                    const faltas = dadosFaltasReais[index] || 0;
                                    const justificados = dadosJustificados[index] || 0;
                                    
                                    let linhasTooltip = [`Total: ${total} aluno(s)`];
                                    if (faltas > 0) {
                                        linhasTooltip.push(`• Faltosos: ${faltas}`);
                                    }
                                    if (justificados > 0) {
                                        linhasTooltip.push(`• Justificados: ${justificados}`);
                                    }
                                    
                                    return linhasTooltip;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { ticks: { maxRotation: 0, minRotation: 0 } },
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }

        // LÓGICA ROBUSTA DO MODAL
        const modal = document.getElementById('modal-aluno');
        const cancelarModal = document.getElementById('cancelar-modal');
        const nomeModal = document.getElementById('nome-aluno-modal');
        const corpoModal = document.getElementById('modal-corpo-faltas');

        document.querySelectorAll('.nome-aluno-link').forEach(function (el) {
            el.addEventListener('click', function (event) {
                event.preventDefault();

                const nomeAluno = this.getAttribute('data-nome');
                const rawDetalhesBase64 = this.getAttribute('data-detalhes');
                let detalhes = [];

                try {
                    // Decodifica a string em Base64 para evitar conflitos de aspas
                    const jsonString = decodeURIComponent(escape(window.atob(rawDetalhesBase64)));
                    detalhes = JSON.parse(jsonString);
                } catch (e) {
                    console.error("Erro ao ler JSON de detalhes:", e);
                    detalhes = [];
                }

                nomeModal.textContent = nomeAluno;
                corpoModal.innerHTML = '';

                if (detalhes.length === 0) {
                    corpoModal.innerHTML = '<p class="text-empty" style="text-align: center; padding: 20px;">Nenhum detalhe de falta encontrado para este aluno.</p>';
                } else {
                    detalhes.forEach(item => {
                        const card = document.createElement('div');
                        card.className = 'item-falta-card';

                        const motivoHtml = item.motivo ? `<div><strong>Motivo:</strong> ${item.motivo}</div>` : '<div class="text-empty">Sem justificativa registrada</div>';
                        const obsHtml = item.observacoes ? `<div style="font-size: 12px; margin-top: 4px;"><strong>Obs:</strong> ${item.observacoes}</div>` : '<div class="text-empty">Sem observações</div>';
                        
                        let anexoHtml = '<span class="text-empty">Sem atestado</span>';
                        if (item.justificativa_id && (item.arquivo_nome || item.arquivo_caminho)) {
                            anexoHtml = `<a href="<?= base_url('arquivo/atestado/'); ?>/${item.justificativa_id}" target="_blank" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver atestado</a>`;
                        }

                        card.innerHTML = `
                            <div class="item-falta-header">
                                <span class="falta-data"><i class="fa-solid fa-calendar-day" style="margin-right: 5px;"></i> Dia ${item.data}</span>
                                ${anexoHtml}
                            </div>
                            <div style="font-size: 13px; color: #333; margin-top: 6px;">
                                ${motivoHtml}
                                ${obsHtml}
                            </div>
                        `;
                        corpoModal.appendChild(card);
                    });
                }

                modal.classList.add('aberto');
                document.body.style.overflow = 'hidden';
            });
        });

        function fecharJanelaAluno() {
            modal.classList.remove('aberto');
            document.body.style.overflow = '';
        }

        cancelarModal.addEventListener('click', fecharJanelaAluno);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) fecharJanelaAluno();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('aberto')) fecharJanelaAluno();
        });
    </script>
</body>
</html>