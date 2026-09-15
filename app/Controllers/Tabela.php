<?php

namespace App\Controllers;

use App\Models\TurmaModel;
use App\Models\AlunoModel;
use App\Models\JustificafaltaModel;

class Tabela extends BaseController
{
    public function index()
    {
         $perfil = session()->get('perfil');
        if ($perfil !== 'gestão' && $perfil !== 'gestao') {
            return view('errors/html/acesso_restrito');
        }

        $turmaModel = new TurmaModel();
        $alunoModel = new AlunoModel();

        $turmaId = $this->request->getGet('selecionar_turma_id') ?? 0;

        $data['turmas_lista'] = $turmaModel->whereIn('serie', ['1º ANO', '2º ANO', '3º ANO'])
                                          ->orderBy('serie', 'ASC')
                                          ->orderBy('nome', 'ASC')
                                          ->findAll();

        $data['turma_atual_id'] = $turmaId;
        $data['alunos_turma_selecionada'] = $turmaId > 0 
            ? $alunoModel->where('turma_id', $turmaId)->orderBy('nome', 'ASC')->findAll() 
            : [];
        
        return view('legal/tabela', $data);

    }
}
