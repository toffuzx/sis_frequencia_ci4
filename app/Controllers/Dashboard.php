<?php

namespace App\Controllers;

use App\Models\TurmaModel;

class Dashboard extends BaseController
{
    public function index()
    {
         // 1. Verifica se está logado via Session do CI4
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }

        $data_filtro  = $this->request->getGet('data_busca') ?? date('Y-m-d');
        $turma_filtro = $this->request->getGet('turma_busca') ?? session()->get('ultima_turma_professor');


         date_default_timezone_set('America/Sao_Paulo');

        if ($data_filtro > date('Y-m-d')) {
            $data_filtro = date('Y-m-d');
        }

         // Array de meses para formatar em português
    $meses = [
        '01' => 'Janeiro',   '02' => 'Fevereiro', '03' => 'Março',
        '04' => 'Abril',     '05' => 'Maio',      '06' => 'Junho',
        '07' => 'Julho',     '08' => 'Agosto',    '09' => 'Setembro',
        '10' => 'Outubro',   '11' => 'Novembro',  '12' => 'Dezembro'
    ];

        $db     = \Config\Database::connect();
        $perfil = session()->get('perfil');
        $turma_id_sessao = session()->get('turma_id');

        $todas_turmas   = [];
        $alunos         = [];
        $mostrar_tabela = false;

        // Filtro para Professor / Gestão
        if ($perfil === 'professor' || $perfil === 'gestão') {
            $todas_turmas = $db->table('turmas')
                               ->select('id, serie, nome')
                               ->where('serie !=', 'ADMINISTRADOR')
                               ->orderBy('serie', 'ASC')
                               ->orderBy('nome', 'ASC')
                               ->get()
                               ->getResultArray();

            if (!empty($turma_filtro)) {
                session()->set('ultima_turma_professor', $turma_filtro);
                $mostrar_tabela = true;
            }
        }

        if ($mostrar_tabela && !empty($turma_filtro)) {
            $alunos_db = $db->table('alunos')
                            ->select('id, nome')
                            ->where('turma_id', $turma_filtro)
                            ->orderBy('nome', 'ASC')
                            ->get()
                            ->getResultArray();

            foreach ($alunos_db as $a) {
                $aluno_id = $a['id'];

                // Justificativas
                $justificativa = $db->table('justificativas_alunos')
                                    ->where('aluno_id', $aluno_id)
                                    ->where('data_registro', $data_filtro)
                                    ->get()
                                    ->getRowArray();

                // Justificativa de falta
                $justificativa_falta = $db->table('justificativas_faltas')
                                          ->where('aluno_id', $aluno_id)
                                          ->where('data_registro', $data_filtro)
                                          ->get()
                                          ->getRowArray();

                // Frequência
                $freq_salva = $db->table('frequencias')
                                 ->where('aluno_id', $aluno_id)
                                 ->where('data_registro', $data_filtro)
                                 ->get()
                                 ->getRowArray();

                $status = ($freq_salva && !empty($freq_salva['aula_1'])) ? $freq_salva['aula_1'] : 'P';

                $alunos[] = [
                    'id'    => $aluno_id,
                    'nome'  => $a['nome'],
                    'aulas' => [1 => $status],
                    'justificativa' => $justificativa ?: [
                        'chegou_atrasado' => 0,
                        'fardamento_incompleto' => 0,
                        'observacoes' => ''
                    ],
                    'justificativa_falta' => $justificativa_falta ?: [
                        'id' => '', 'motivo' => '', 'observacoes' => '', 'arquivo_nome' => '', 'arquivo_caminho' => ''
                    ]
                ];
            }
        }

        $dataView = [
            'perfil'          => $perfil,
            'todas_turmas'    => $todas_turmas,
            'turma_filtro'    => $turma_filtro,
            'data_filtro'     => $data_filtro,
            'alunos'          => $alunos,
            'mostrar_tabela'  => $mostrar_tabela,
            //'data_hoje_br'    => $data_hoje_br
        ];

        return view('legal/dashboard', $dataView);
    }
}

