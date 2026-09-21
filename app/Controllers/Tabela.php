<?php

namespace App\Controllers;

use App\Models\TurmaModel;
use App\Models\FrequenciaModel;
use App\Models\AlunoModel;
use App\Models\JustificativafaltaModel;

class Tabela extends BaseController
{
    public function index()
    {
        $session = session();
        $perfilUsuario = $session->get('perfil');

        // Apenas Gestão tem permissão de acesso
        if ($perfilUsuario !== 'gestão' && $perfilUsuario !== 'gestao') {
            return view('errors/html/acesso_restrito');
        }

        $db = \Config\Database::connect();
        $turmaModel = new TurmaModel();

        // --- FILTROS DE MÊS E TURMA ---
        $mesSelecionado = $this->request->getGet('mes') ?? date('Y-m');
        $turmaId = (int) ($this->request->getGet('turma_id') ?? $this->request->getGet('selecionar_turma_id') ?? 0);

        if ($turmaId > 0) {
            $session->set('ultima_turma_gestao', $turmaId);
        } else if ($session->has('ultima_turma_gestao') && !empty($session->get('ultima_turma_gestao'))) {
            $turmaId = (int) $session->get('ultima_turma_gestao');
        }

        // --- GERAÇÃO AUTOMÁTICA DE OPÇÕES DE MESES ---
        $mesesNomes = [
            '01' => 'Janeiro',   '02' => 'Fevereiro', '03' => 'Março',    '04' => 'Abril', 
            '05' => 'Maio',      '06' => 'Junho',     '07' => 'Julho',    '08' => 'Agosto', 
            '09' => 'Setembro', '10' => 'Outubro',   '11' => 'Novembro', '12' => 'Dezembro'
        ];

        $anoAtual = date('Y');
        $mesAtualNum = (int) date('m'); 
        $opcoesMeses = [];

        for ($m = 1; $m <= $mesAtualNum; $m++) {
            $mesZero = str_pad($m, 2, "0", STR_PAD_LEFT);
            $chaveMes = $anoAtual . '-' . $mesZero; 
            $opcoesMeses[$chaveMes] = $mesesNomes[$mesZero] . ' de ' . $anoAtual;
        }

        // Lista de turmas para o dropdown
        $listaTurmasEscola = $turmaModel->where('serie !=', 'ADMINISTRADOR')
                                         ->orderBy('serie', 'ASC')
                                         ->orderBy('nome', 'ASC')
                                         ->findAll();

        // --- DEFINIÇÃO DA TURMA ---
        $mostrarConteudo = false;
        $nomeTurma = 'Todas as Turmas';

        if ($turmaId > 0) {
            $dadosTurma = $turmaModel->find($turmaId);
            if ($dadosTurma) {
                $nomeTurma = $dadosTurma['serie'] . ' - ' . $dadosTurma['nome'];
                $mostrarConteudo = true;
            }
        }

        // --- RANKING DE TURMAS MAIS FALTOSAS ---
        $rankingTurmasFaltas = [];
        $builderRanking = $db->table('frequencias f')
            ->select('t.id AS turma_id, t.serie, t.nome AS nome_turma, f.aula_1')
            ->join('alunos a', 'f.aluno_id = a.id')
            ->join('turmas t', 'a.turma_id = t.id')
            ->where('t.serie !=', 'ADMINISTRADOR')
            ->like('f.data_registro', $mesSelecionado, 'after');

        $freqsTodas = $builderRanking->get()->getResultArray();
        $acumuladoTurmas = [];

        foreach ($freqsTodas as $fr) {
            $tid = $fr['turma_id'];
            if (!isset($acumuladoTurmas[$tid])) {
                $acumuladoTurmas[$tid] = [
                    'id' => $tid,
                    'nome' => $fr['serie'] . ' - ' . $fr['nome_turma'],
                    'faltas_reais' => 0,
                    'total_aulas' => 0
                ];
            }

            if (!empty($fr['aula_1'])) {
                $acumuladoTurmas[$tid]['total_aulas']++;
                if (in_array($fr['aula_1'], ['F', 'J'])) {
                    $acumuladoTurmas[$tid]['faltas_reais']++;
                }
            }
        }

        foreach ($acumuladoTurmas as $tInfo) {
            if ($tInfo['faltas_reais'] > 0) {
                $perc = $tInfo['total_aulas'] > 0 ? round(($tInfo['faltas_reais'] / $tInfo['total_aulas']) * 100, 0) : 0;
                $rankingTurmasFaltas[] = [
                    'id' => $tInfo['id'],
                    'nome' => $tInfo['nome'],
                    'faltas_reais' => $tInfo['faltas_reais'],
                    'percentual' => $perc
                ];
            }
        }

        usort($rankingTurmasFaltas, function($a, $b) {
            return $b['faltas_reais'] <=> $a['faltas_reais'];
        });

        // --- DASHBOARD E MÉTRICAS DA TURMA SELECIONADA ---
        $totalFaltasTurma = 0;
        $percentualFaltasTurma = 0;
        $labelsDias = [];
        $valoresTotais = [];
        $valoresFaltas = [];
        $valoresJustificadas = [];
        
        $tabelaAlunosSemJustificativa = [];
        $tabelaAlunosJustificados = [];

        if ($mostrarConteudo && $turmaId > 0) {
            $builderFreq = $db->table('frequencias f')
                ->select('f.*')
                ->join('alunos a', 'f.aluno_id = a.id')
                ->where('a.turma_id', $turmaId)
                ->like('f.data_registro', $mesSelecionado, 'after');

            $frequenciasMes = $builderFreq->get()->getResultArray();

            $totalOportunidadesAulas = 0;
            foreach ($frequenciasMes as $freq) {
                if (!empty($freq['aula_1'])) {
                    $totalOportunidadesAulas++;
                    if (in_array($freq['aula_1'], ['F', 'J'])) {
                        $totalFaltasTurma++;
                    }
                }
            }

            $percentualFaltasTurma = $totalOportunidadesAulas > 0 
                ? round(($totalFaltasTurma / $totalOportunidadesAulas) * 100, 0) 
                : 0;

            // Dados do Gráfico por Dia
            $anoPart = (int) substr($mesSelecionado, 0, 4);
            $mesPart = (int) substr($mesSelecionado, 5, 2);
            $qtdDiasMes = cal_days_in_month(CAL_GREGORIAN, $mesPart, $anoPart);

            $mapeamentoFaltas = [];
            $mapeamentoJustificadas = [];
            $mapeamentoTotais = [];

            for ($d = 1; $d <= $qtdDiasMes; $d++) {
                $diaZero = str_pad($d, 2, "0", STR_PAD_LEFT);
                $mapeamentoFaltas[$diaZero] = 0;
                $mapeamentoJustificadas[$diaZero] = 0;
                $mapeamentoTotais[$diaZero] = 0;
            }

            foreach ($frequenciasMes as $freq) {
                $dia = date('d', strtotime($freq['data_registro']));
                $statusAula = $freq['aula_1'] ?? '';

                if (isset($mapeamentoTotais[$dia])) {
                    if ($statusAula === 'F') { 
                        $mapeamentoFaltas[$dia]++; 
                        $mapeamentoTotais[$dia]++;
                    } elseif ($statusAula === 'J') { 
                        $mapeamentoJustificadas[$dia]++; 
                        $mapeamentoTotais[$dia]++;
                    }
                }
            }

            $labelsDias = array_keys($mapeamentoTotais);
            $valoresTotais = array_values($mapeamentoTotais);
            $valoresFaltas = array_values($mapeamentoFaltas);
            $valoresJustificadas = array_values($mapeamentoJustificadas);

            // Lista de Alunos
            $builderAlunos = $db->table('alunos a')
                ->select('a.id, a.nome, jf.id AS justificativa_id, jf.motivo, jf.observacoes, jf.arquivo_nome, jf.arquivo_caminho')
                ->join('justificativas_faltas jf', 'jf.aluno_id = a.id', 'left')
                ->where('a.turma_id', $turmaId)
                ->orderBy('a.nome', 'ASC');

            $listaAlunos = $builderAlunos->get()->getResultArray();

            foreach ($listaAlunos as $aluno) {
                $faltasAluno = 0;
                $totalAulasAluno = 0;

                foreach ($frequenciasMes as $f) {
                    if ($f['aluno_id'] == $aluno['id']) {
                        if (!empty($f['aula_1'])) {
                            $totalAulasAluno++;
                            if (in_array($f['aula_1'], ['F', 'J'])) {
                                $faltasAluno++;
                            }
                        }
                    }
                }

                if ($faltasAluno > 0) {
                    $percA = $totalAulasAluno > 0 ? round(($faltasAluno / $totalAulasAluno) * 100, 0) : 0;
                    $itemAluno = [
                        'id'               => $aluno['id'],
                        'nome'             => $aluno['nome'],
                        'faltas_reais'     => $faltasAluno,
                        'percentual'       => $percA,
                        'justificativa_id' => $aluno['justificativa_id'],
                        'motivo'           => $aluno['motivo'],
                        'observacoes'      => $aluno['observacoes'],
                        'arquivo_nome'     => $aluno['arquivo_nome'],
                        'arquivo_caminho'  => $aluno['arquivo_caminho']
                    ];

                    // Qualquer aluno com registro de justificativa vai para a tabela de justificados
                    if (!empty($aluno['justificativa_id'])) {
                        $tabelaAlunosJustificados[] = $itemAluno;
                    } else {
                        $tabelaAlunosSemJustificativa[] = $itemAluno;
                    }
                }
            }
        }

        $mesIndex = substr($mesSelecionado, 5, 2);
        $nomeMesExibicao = $mesesNomes[$mesIndex] ?? 'Mês Inválido';
        $anoExibicao = substr($mesSelecionado, 0, 4);

        $data = [
            'mes_selecionado'               => $mesSelecionado,
            'opcoes_meses'                  => $opcoesMeses,
            'lista_turmas_escola'           => $listaTurmasEscola,
            'turma_id'                      => $turmaId,
            'nome_turma'                    => $nomeTurma,
            'perfil_usuario'                => $perfilUsuario,
            'mostrar_conteudo'              => $mostrarConteudo,
            'ranking_turmas_faltas'         => $rankingTurmasFaltas,
            'total_faltas_turma'            => $totalFaltasTurma,
            'percentual_faltas_turma'       => $percentualFaltasTurma,
            'labels_dias'                   => $labelsDias,
            'valores_totais'                => $valoresTotais,
            'valores_faltas'                => $valoresFaltas,
            'valores_justificadas'          => $valoresJustificadas,
            'tabela_alunos_sem_justificativa' => $tabelaAlunosSemJustificativa,
            'tabela_alunos_justificados'    => $tabelaAlunosJustificados,
            'nome_mes_exibicao'             => $nomeMesExibicao,
            'ano_exibicao'                  => $anoExibicao,
        ];

        return view('legal/tabela', $data);
    }
}