<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'conexao.php'; // Conecta ao banco de dados

// Garante que o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('America/Sao_Paulo');

// Identifica o perfil do usuário (lideranca ou professor/gestao)
$perfil_usuario = $_SESSION['perfil'] ?? 'lideranca';

// Define qual mês exibir (pega por parâmetro GET ou usa o mês atual no formato Y-m)
$mes_selecionado = isset($_GET['mes']) ? $_GET['mes'] : date('Y-m');

// --- GERAÇÃO AUTOMÁTICA DE OPÇÕES DE MESES ---
$meses_nomes = [
    '01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', 
    '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', 
    '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro'
];

$ano_atual = date('Y');
$mes_atual_num = (int)date('m'); 
$opcoes_meses = [];

for ($m = 1; $m <= $mes_atual_num; $m++) {
    $mes_zero = str_pad($m, 2, "0", STR_PAD_LEFT);
    $chave_mes = $ano_atual . '-' . $mes_zero; 
    $opcoes_meses[$chave_mes] = $meses_nomes[$mes_zero] . ' de ' . $ano_atual;
}

// --- LÓGICA DE DEFINIÇÃO DA TURMA ---
$mostrar_conteudo = false;
$nome_turma = 'Nenhuma Turma Selecionada';
$turma_id = 0;  

$lista_turmas_escola = [];
if ($perfil_usuario === 'professor' || $perfil_usuario === 'gestão') {
    $stmt_todas_turmas = $pdo->query("SELECT id, serie, nome FROM turmas WHERE serie != 'ADMINISTRADOR' ORDER BY serie ASC, nome ASC");
    $lista_turmas_escola = $stmt_todas_turmas->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['turma_id'])) {
        $turma_id = (int)$_GET['turma_id'];
        $_SESSION['ultima_turma_professor'] = $turma_id;
    } else if (isset($_SESSION['ultima_turma_professor']) && !empty($_SESSION['ultima_turma_professor'])) {
        $turma_id = (int)$_SESSION['ultima_turma_professor'];
    }

    if ($turma_id > 0) {
        $stmt_t = $pdo->prepare("SELECT serie, nome FROM turmas WHERE id = ?");
        $stmt_t->execute([$turma_id]);
        $dados_da_turma = $stmt_t->fetch(PDO::FETCH_ASSOC);
        if ($dados_da_turma) {
            $nome_turma = $dados_da_turma['serie'] . ' - ' . $dados_da_turma['nome'];
            $mostrar_conteudo = true;
        }
    }
} else {
    $turma_id = $_SESSION['turma_id'] ?? 0;
    $nome_turma = $_SESSION['nome_turma_completo'] ?? 'Sua Turma';
    $mostrar_conteudo = ($turma_id > 0);
}

// --- LÓGICA DO RANKING DAS TURMAS MAIS FALTOSAS NO MÊS SELECIONADO ---
$ranking_turmas_faltas = [];
if ($perfil_usuario === 'professor' || $perfil_usuario === 'gestão') {
    $stmt_ranking = $pdo->prepare("
        SELECT 
            t.id AS turma_id,
            t.serie,
            t.nome AS nome_turma,
            f.aula_1, f.aula_2, f.aula_3, f.aula_4, f.aula_5, f.aula_6, f.aula_7, f.aula_8, f.aula_9
        FROM frequencias f
        JOIN alunos a ON f.aluno_id = a.id
        JOIN turmas t ON a.turma_id = t.id
        WHERE t.serie != 'ADMINISTRADOR' AND f.data_registro LIKE ?
    ");
    $stmt_ranking->execute([$mes_selecionado . '%']);
    $freqs_todas = $stmt_ranking->fetchAll(PDO::FETCH_ASSOC);

    $acumulado_turmas = [];

    foreach ($freqs_todas as $fr) {
        $tid = $fr['turma_id'];
        if (!isset($acumulado_turmas[$tid])) {
            $acumulado_turmas[$tid] = [
                'id' => $tid,
                'nome' => $fr['serie'] . ' - ' . $fr['nome_turma'],
                'faltas_reais' => 0,
                'total_aulas' => 0
            ];
        }

        for ($i = 1; $i <= 9; $i++) {
            if (!empty($fr["aula_$i"])) {
                $acumulado_turmas[$tid]['total_aulas']++;
                if ($fr["aula_$i"] === 'F' || $fr["aula_$i"] === 'J') {
                    $acumulado_turmas[$tid]['faltas_reais']++;
                }
            }
        }
    }

    foreach ($acumulado_turmas as $t_info) {
        if ($t_info['faltas_reais'] > 0) {
            $perc = $t_info['total_aulas'] > 0 ? round(($t_info['faltas_reais'] / $t_info['total_aulas']) * 100, 0) : 0;
            $ranking_turmas_faltas[] = [
                'id' => $t_info['id'],
                'nome' => $t_info['nome'],
                'faltas_reais' => $t_info['faltas_reais'],
                'percentual' => $perc
            ];
        }
    }

    // Ordena do maior número de faltas para o menor
    usort($ranking_turmas_faltas, function($a, $b) {
        return $b['faltas_reais'] <=> $a['faltas_reais'];
    });
}

// Inicializa variáveis do dashboard da turma individual
$total_faltas_turma = 0;
$percentual_faltas_turma = 0;
$labels_dias = [];
$valores_totais = [];
$valores_faltas = [];
$valores_justificadas = [];
$tabela_alunos_faltas = [];

if ($mostrar_conteudo && $turma_id > 0) {
    
    // --- LÓGICA 1: TOTAL DE AULAS AUSENTES (F+J) E PERCENTUAL POR AULA ---
    $stmt_faltas = $pdo->prepare("
        SELECT f.* FROM frequencias f
        JOIN alunos a ON f.aluno_id = a.id
        WHERE a.turma_id = ? AND f.data_registro LIKE ?
    ");
    $stmt_faltas->execute([$turma_id, $mes_selecionado . '%']);
    $frequencias_mes = $stmt_faltas->fetchAll(PDO::FETCH_ASSOC);

    $total_oportunidades_aulas = 0;
    $total_faltas_turma = 0;

    foreach ($frequencias_mes as $freq) {
        for ($i = 1; $i <= 9; $i++) {
            if (!empty($freq["aula_$i"])) {
                $total_oportunidades_aulas++;
                if ($freq["aula_$i"] === 'F' || $freq["aula_$i"] === 'J') {
                    $total_faltas_turma++;
                }
            }
        }
    }

    $percentual_faltas_turma = $total_oportunidades_aulas > 0 
        ? round(($total_faltas_turma / $total_oportunidades_aulas) * 100, 0) 
        : 0;
        
    // --- LÓGICA 2: GRÁFICO DE FALTAS POR DIA DO MÊS ---
    $qtd_dias_mes = cal_days_in_month(CAL_GREGORIAN, (int)substr($mes_selecionado, 5, 2), (int)substr($mes_selecionado, 0, 4));

    $mapeamento_faltas = [];
    $mapeamento_justificadas = [];
    $mapeamento_totais = [];

    for ($d = 1; $d <= $qtd_dias_mes; $d++) {
        $dia_com_zero = str_pad($d, 2, "0", STR_PAD_LEFT);
        $mapeamento_faltas[$dia_com_zero] = 0;
        $mapeamento_justificadas[$dia_com_zero] = 0;
        $mapeamento_totais[$dia_com_zero] = 0;
    }

    foreach ($frequencias_mes as $freq) {
        $dia = date('d', strtotime($freq['data_registro']));
        
        $tem_falta = false;
        $tem_justificada = false;

        for ($i = 1; $i <= 9; $i++) {
            if ($freq["aula_$i"] === 'F') { $tem_falta = true; }
            if ($freq["aula_$i"] === 'J') { $tem_justificada = true; }
        }

        if (isset($mapeamento_totais[$dia])) {
            if ($tem_falta) { $mapeamento_faltas[$dia]++; }
            if ($tem_justificada) { $mapeamento_justificadas[$dia]++; }
            if ($tem_falta || $tem_justificada) { $mapeamento_totais[$dia]++; }
        }
    }

    $labels_dias = array_keys($mapeamento_totais);
    $valores_totais = array_values($mapeamento_totais);
    $valores_faltas = array_values($mapeamento_faltas);
    $valores_justificadas = array_values($mapeamento_justificadas);

    // --- LÓGICA 3: TABELA DETALHADA POR ALUNO (APENAS FALTOSOS) ---
    $stmt_alunos = $pdo->prepare("SELECT id, nome FROM alunos WHERE turma_id = ? ORDER BY nome ASC");
    $stmt_alunos->execute([$turma_id]);
    $lista_alunos = $stmt_alunos->fetchAll(PDO::FETCH_ASSOC);

    foreach ($lista_alunos as $aluno) {
        $faltas_aluno = 0;
        $total_aulas_aluno = 0;
        
        foreach ($frequencias_mes as $f) {
            if ($f['aluno_id'] == $aluno['id']) {
                for ($i = 1; $i <= 9; $i++) {
                    if (!empty($f["aula_$i"])) {
                        $total_aulas_aluno++;
                        if ($f["aula_$i"] === 'F' || $f["aula_$i"] === 'J') {
                            $faltas_aluno++;
                        }
                    }
                }
            }
        }
        
        if ($faltas_aluno > 0) {
            $perc_aluno = $total_aulas_aluno > 0 ? round(($faltas_aluno / $total_aulas_aluno) * 100, 0) : 0;
            
            $tabela_alunos_faltas[] = [
                'nome' => $aluno['nome'],
                'faltas_reais' => $faltas_aluno,
                'percentual' => $perc_aluno
            ];
        }
    }
}

$mes_index = substr($mes_selecionado, 5, 2);
$nome_mes_exibicao = isset($meses_nomes[$mes_index]) ? $meses_nomes[$mes_index] : 'Mês Inválido';
$ano_exibicao = substr($mes_selecionado, 0, 4);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faltas por Mês - <?php echo htmlspecialchars($nome_turma); ?></title>
    <link rel="icon" href="./logo_WR.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-dark: #1b3322;
            --border-color: #f1f5f9;
            --text-gray: #666;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f7f4; color: #333; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .page-title-section { margin-bottom: 25px; }

        .dashboard-filter-card {
            background: white; padding: 25px; border-radius: 15px;
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #eef2ee; margin-bottom: 25px;
        }

        .filter-left-info { display: flex; align-items: center; gap: 15px; }
        .filter-icon-circle {
            width: 50px; height: 50px; background-color: #e8f0e9; color: var(--primary-dark);
            border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;
        }

        .filter-left-info h3 { font-size: 20px; color: #111; font-weight: bold; margin-bottom: 2px; }
        .filter-left-info p { font-size: 13px; color: var(--text-gray); }
        .filter-inputs-group { display: flex; align-items: center; gap: 12px; }

        .filter-inputs-group select {
            padding: 10px 16px; font-size: 14px; border: 1px solid #ced4da; border-radius: 8px;
            background-color: #fff; color: #495057; outline: none; min-width: 180px; cursor: pointer;
        }

        .cards-grid { display: flex; gap: 15px; margin-bottom: 25px; }
        .card-resumo {
            background: white; padding: 20px; border-radius: 15px; flex: 1;
            display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #e2e8e2;
        }

        .badge-numero {
            width: 50px; height: 50px; border-radius: 50%; background-color: #fce4e4; color: #c94c4c;
            display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px;
        }

        .chart-box, .table-box {
            background: white; padding: 20px; border-radius: 15px; margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #e2e8e2;
        }

        .section-title { font-size: 16px; font-weight: bold; color: var(--primary-dark); margin-bottom: 15px; }
        .tabela-faltas { width: 100%; border-collapse: collapse; }
        .tabela-faltas th, .tabela-faltas td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .tabela-faltas th { color: #777; font-size: 14px; }
        .aluno-nome-col { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .aluno-nome-col i { color: var(--primary-dark); background: #e8f0e9; padding: 6px; border-radius: 50%; font-size: 12px; }
        .badge-falta-tabela { background: #fce4e4; color: #c94c4c; padding: 4px 10px; border-radius: 12px; font-weight: bold; font-size: 13px; }
        
        .turma-link { text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px; }
        .turma-link:hover { color: var(--primary-dark); text-decoration: underline; }

        @media (max-width: 900px) {
            .dashboard-filter-card {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .filter-left-info {
                width: 100%;
            }

            .filter-inputs-group {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }

            .filter-inputs-group select {
                width: 100%;
                min-width: 0;
            }

            .cards-grid {
                flex-direction: column;
            }
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
                    <p>Selecionado: <strong><?php echo htmlspecialchars($nome_mes_exibicao . " de " . $ano_exibicao); ?></strong></p>
                </div>
            </div>

            <div class="filter-inputs-group">
                <select id="select-mes" onchange="atualizarFiltros()">
                    <?php foreach ($opcoes_meses as $valor_mes => $texto_mes): ?>
                        <option value="<?= $valor_mes ?>" <?= $mes_selecionado == $valor_mes ? 'selected' : ''; ?>>
                            <?= $texto_mes ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if ($perfil_usuario === 'professor' || $perfil_usuario === 'gestão'): ?>
                    <select id="select-turma" onchange="atualizarFiltros()">
                        <option value="0" <?= empty($turma_id) ? 'selected' : '' ?>>-- Todas as Turmas --</option>
                        <?php foreach ($lista_turmas_escola as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= $turma_id == $t['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($t['serie'] . ' ' . $t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
        </div>

        <!-- NOVO: RANKING DE TURMAS MAIS FALTOSAS -->
        <?php if (($perfil_usuario === 'professor' || $perfil_usuario === 'gestão') && !empty($ranking_turmas_faltas)): ?>
            <div class="table-box">
                <div class="section-title">
                    <i class="fa-solid fa-ranking-star" style="margin-right: 8px;"></i>
                    Ranking de Turmas Mais Faltosas (<?php echo htmlspecialchars($nome_mes_exibicao); ?>)
                </div>
                <table class="tabela-faltas">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Turma</th>
                            <th>Faltas reais</th>
                            <th>Percentual de faltas</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ranking_turmas_faltas as $index => $turma_item): ?>
                        <tr>
                            <td style="font-weight: bold; width: 30px;"><?php echo $index + 1; ?>º</td>
                            <td>
                                <div class="aluno-nome-col">
                                    <i class="fa-solid fa-users"></i>
                                    <span><?php echo htmlspecialchars($turma_item['nome']); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-falta-tabela">
                                    <?php echo str_pad($turma_item['faltas_reais'], 2, "0", STR_PAD_LEFT); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-falta-tabela">
                                    <?php echo $turma_item['percentual'] . '%'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="faltas.php?mes=<?= $mes_selecionado ?>&turma_id=<?= $turma_item['id'] ?>" style="color: var(--primary-dark); font-weight: bold; font-size: 13px; text-decoration: none;">
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
                    <div class="badge-numero"><?php echo str_pad($total_faltas_turma, 2, "0", STR_PAD_LEFT); ?></div>
                    <div>
                        <strong style="font-size: 15px;">Faltas Reais</strong>
                        <p style="font-size: 12px; color: #777;">Total de ausências no mês em <?php echo htmlspecialchars($nome_turma); ?></p>
                    </div>
                </div>
                
                <div class="card-resumo">
                    <div class="badge-numero"><?php echo $percentual_faltas_turma; ?>%</div>
                    <div>
                        <strong style="font-size: 15px;">Percentual de faltas</strong>
                        <p style="font-size: 12px; color: #777;">Ausências coletivas no mês</p>
                    </div>
                </div>
            </div>

            <div class="chart-box">
                <div class="section-title">Ausências da Turma: <?php echo htmlspecialchars($nome_turma); ?></div>
                <canvas id="graficoFaltas" style="max-height: 220px;"></canvas>
            </div>

            <div class="table-box">
                <div class="section-title">Desempenho por Aluno em <?php echo htmlspecialchars($nome_turma); ?></div>
                <table class="tabela-faltas">
                    <thead>
                        <tr>
                            <th>Nome do aluno</th>
                            <th>Faltas reais</th>
                            <th>Percentual de faltas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tabela_alunos_faltas)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: #777; padding: 20px;">
                                Nenhum aluno faltou neste mês!
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($tabela_alunos_faltas as $aluno): ?>
                            <tr>
                                <td>
                                    <div class="aluno-nome-col">
                                        <i class="fa-solid fa-user"></i>
                                        <span><?php echo htmlspecialchars($aluno['nome']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-falta-tabela">
                                        <?php echo str_pad($aluno['faltas_reais'], 2, "0", STR_PAD_LEFT); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-falta-tabela">
                                        <?php echo $aluno['percentual'] . '%'; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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
            
            let url = 'faltas.php?mes=' + mes;
            if (selectTurma && selectTurma.value !== undefined) {
                url += '&turma_id=' + selectTurma.value;
            }
            
            window.location.href = url;
        }

        // Arrays auxiliares para o tooltip dinâmico
        const dadosFaltasReais = <?php echo json_encode($valores_faltas); ?>;
        const dadosJustificados = <?php echo json_encode($valores_justificadas); ?>;

        const canvasGrafico = document.getElementById('graficoFaltas');
        if (canvasGrafico) {
            const ctx = canvasGrafico.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels_dias); ?>,
                    datasets: [{
                        label: 'Total de Ausentes',
                        data: <?php echo json_encode($valores_totais); ?>,
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
                            ticks: {
                                maxRotation: 0,
                                minRotation: 0
                            }
                        },
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    </script>
</body>
</html>