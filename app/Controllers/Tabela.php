<?php

namespace App\Controllers;

use App\Models\TurmaModel;
use App\Models\FrequenciaModel;
use App\Models\AlunoModel;
use App\Models\JustificativaFaltaModel;

class Tabela extends BaseController
{
    public function index()
    {
        $perfil = session()->get('perfil');
        if ($perfil !== 'gestão' && $perfil !== 'gestao') {
            return view('errors/html/acesso_restrito');
        }

        $turmaModel = new TurmaModel();
        $db = \Config\Database::connect();

        $turmaId = (int) ($this->request->getGet('selecionar_turma_id') ?? 0);
        $mesAtual = date('m');
        $anoAtual = date('Y');

        $alunosFaltas = [];

        if ($turmaId > 0) {
            // Busca alunos da turma ordenados do maior número de faltas do mês para o menor
            $builder = $db->table('alunos a');
            $builder->select("
                a.id as aluno_id, 
                a.nome as aluno_nome,
                COUNT(f.id) as total_faltas,
                jf.id as justificativa_id,
                jf.motivo,
                jf.observacoes,
                jf.arquivo_nome,
                jf.arquivo_caminho
            ");
            // Frequência 'F' (Falta) no mês/ano atual
            $builder->join('frequencias f', "f.aluno_id = a.id AND f.aula_1 = 'F' AND MONTH(f.data_registro) = {$mesAtual} AND YEAR(f.data_registro) = {$anoAtual}", 'left');
            // Anexa as justificativas de faltas se existirem
            $builder->join('justificativas_faltas jf', 'jf.aluno_id = a.id', 'left');
            $builder->where('a.turma_id', $turmaId);
            $builder->groupBy('a.id, jf.id');
            $builder->orderBy('total_faltas', 'DESC');
            $builder->orderBy('a.nome', 'ASC');

            $alunosFaltas = $builder->get()->getResultArray();
        }

        $data['turmas_lista'] = $turmaModel->whereIn('serie', ['1º ANO', '2º ANO', '3º ANO'])
                                           ->orderBy('serie', 'ASC')
                                           ->orderBy('nome', 'ASC')
                                           ->findAll();

        $data['turma_atual_id'] = $turmaId;
        $data['alunos_faltas'] = $alunosFaltas;

        return view('legal/tabela', $data);


    }
}
