<?php

namespace App\Controllers;

use App\Models\TurmaModel;

class Turma extends BaseController
{
    public function index()
    {
        $turmaModel = new TurmaModel();

        $data['turmas_db'] = $turmaModel->getTurmasComUsuarios();
        
        return view('turma/lista', $data);
    }
}
