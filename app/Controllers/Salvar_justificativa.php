<?php

namespace App\Controllers;

use App\Models\JustificativaalunoModel;

class Salvar_justificativa extends BaseController
{
    public function salvar(){
        if($this->request->getmethod()!== 'post'){
            return redirect()->to('/dashboard');
        }
        $dataRegistro = $this->request->getPost('data_registro');
        $justificativas_alunos = $this->request->getPost('justificativas_alunos');
        $turmaId = $this->request->getPost('turma_id');
    
   if (empty($dataRegistro) || empty($justificativas_alunos)) {
        return redirect()
        ->to('/dashboard')
        ->with('erro', 'Deu ruim');
    }
     $model = new JustificativaalunoModel();

      // Percorre cada aluno
        foreach ($justificativas_alunos as $alunoId => $justificativa) {

            // Procura se esse aluno já possui frequência nessa data
            $registro = $model
                ->where('aluno_id', $alunoId)
                ->where('data_registro', $dataRegistro)
                ->first();

            // Dados que serão gravados
            $dados = [
                'aluno_id'      => $alunoId,
                'data_registro' => $dataRegistro,

                'aula_1' => $aulas[1] ?? 'P',
                'aula_2' => $aulas[2] ?? 'P',
                'aula_3' => $aulas[3] ?? 'P',
                'aula_4' => $aulas[4] ?? 'P',
                'aula_5' => $aulas[5] ?? 'P',
                'aula_6' => $aulas[6] ?? 'P',
                'aula_7' => $aulas[7] ?? 'P',
                'aula_8' => $aulas[8] ?? 'P',
                'aula_9' => $aulas[9] ?? 'P',
            ];

            if ($registro) {

                // Já existe → atualiza
                $model->update(
                    $registro['id'],
                    $dados
                );

            } else {

                // Não existe → cria
                $model->insert($dados);
            }
        }

        // Volta para o dashboard
        return redirect()->to(
            '/dashboard?sucesso=1'
            . '&data_busca=' . urlencode($dataRegistro)
            . '&turma_busca=' . urlencode($turmaId)
        );
    }
}
