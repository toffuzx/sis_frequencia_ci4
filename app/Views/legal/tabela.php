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

        .aluno-nome-col { display: flex; align-items: flex-start; gap: 10px; min-width: 0; }
        .aluno-nome-col i { color: var(--primary-dark); background: #e8f0e9; padding: 6px; border-radius: 50%; font-size: 12px; margin-top: 2px; }
        .aluno-nome { font-size: 18px;}
        .aluno-nome { cursor: pointer; transition: 0.2s;}
        .aluno-nome:hover { color: var(--primary-green); text-decoration: underline; }


        .campo-modal-justificativa { margin-bottom: 20px; }

        .campo-modal-justificativa label.titulo-campo {
            display: block;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 14px;
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
            .dashboard-filter-card { flex-direction: column; align-items: stretch; gap: 15px; }
            .filter-inputs-group { flex-direction: column; width: 100%; gap: 10px; }
            .filter-inputs-group select { width: 100%; min-width: 0; }
            .cards-grid { flex-direction: column; }
            .tables-grid { flex-direction: column; }
        }
    </style>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="container">
        <div class="page-title-section">
            <h2 style="color: #1b3322;">Faltas da Escola (Gestão)</h2>
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
                    <?php foreach ($opcoes_meses as $valor_mes =>$texto_mes): ?>
                        <option value="<?= $valor_mes ?>" <?= ($mes_selecionado ==$valor_mes) ? 'selected' : ''; ?>>
                            <?= esc($texto_mes) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select id="select-turma" onchange="atualizarFiltros()">
                    <option value="0" <?= empty($turma_id) ? 'selected' : '' ?>>-- Selecione uma Turma --</option>
                    <?php foreach ($lista_turmas_escola as$t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($turma_id ==$t['id']) ? 'selected' : ''; ?>>
                            <?= esc($t['serie'] . ' ' .$t['nome']) ?>
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
                <table class="tabela-faltas">
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
                        <?php foreach ($ranking_turmas_faltas as $index =>$turma_item): ?>
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
                                <th>Aluno / Motivo / Obs.</th>
                                <th>Faltas</th>
                                <th>Anexo</th>
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
                                <?php foreach ($tabela_alunos_justificados as $aluno): ?>
                                    <tr>
                                        <td>
                                            <div class="aluno-nome-col">
                                                <i class="fa-solid fa-user"></i>
                                                <div>

                                                    <span class="aluno-nome"
                                                        data-nome="<?= esc($aluno['nome']); ?>"
                                                        data-aluno="<?= esc($aluno['id']); ?>"
                                                        data-justificativa="<?= esc($aluno['motivo']); ?>"
                                                        data-observacoes="<?= esc($aluno['observacoes']); ?>"
                                                        data-arquivo-nome="<?= esc($aluno['arquivo_nome']); ?>"
                                                        >
                                                      <?= esc($aluno['nome']); ?>
                                                    </span>
                                                    
                                                    <?php if (!empty($aluno['motivo'])): ?>
                                                        <div class="motivo-texto">
                                                            <i class="" style="font-size: 10px; color: var(--primary-green); background: none; padding: 0;"></i>
                                                            Motivo: <?= esc($aluno['motivo']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($aluno['observacoes'])): ?>
                                                        <div class="obs-texto">
                                                            Obs: <?= esc($aluno['observacoes']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-justificada-tabela">
                                                <?= str_pad($aluno['faltas_reais'], 2, "0", STR_PAD_LEFT); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <!-- modal de ver justificativas -->


                                            <?php if (!empty($aluno['arquivo_caminho']) || !empty($aluno['arquivo_nome'])): ?>
                                                <a href="<?= base_url('arquivo/atestado/' . $aluno['justificativa_id']); ?>" target="_blank" class="btn-ver">
                                                    <i class="fa-solid fa-eye"></i> Ver
                                                </a>
                                            <?php else: ?>
                                                <span style="font-size: 11px; color: #999;">Sem anexo</span>
                                            <?php endif; ?>
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

        const dadosFaltasReais = <?= json_encode($valores_faltas); ?>;
        const dadosJustificados = <?= json_encode($valores_justificadas); ?>;

        const canvasGrafico = document.getElementById('graficoFaltas');
        if (canvasGrafico) {
            const ctx = canvasGrafico.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels_dias); ?>,
                    datasets: [{
                        label: 'Total de Ausentes',
                        data: <?= json_encode($valores_totais); ?>,
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
                        x: {
                            ticks: { maxRotation: 0, minRotation: 0 }
                        },
                        y: { 
                            beginAtZero: true, 
                            ticks: { stepSize: 1 } 
                        }
                    }
                }
            });
        }

        const modal = document.getElementById('modal-aluno');
        const excluirModal = document.getElementById('excluir-modal');
        const cancelarModal = document.getElementById('cancelar-modal');
        const nomeModal = document.getElementById('modal-aluno-nome');
        const nomeDisplay = document.getElementById('modal-nome-display');
        const dataModal = document.getElementById('modal-data');
        


        document.querySelectorAll('.aluno-nome').forEach(function (nome) {
            nome.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const alunoId = this.getAttribute('data-aluno');
                const nomeAluno = this.getAttribute('data-nome');

                modal.setAttribute('data-aluno', alunoId);
                nomeModal.textContent = nomeAluno;
                nomeDisplay.textContent = nomeAluno;

                
                observacoes.value = observacoesSalvas;

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