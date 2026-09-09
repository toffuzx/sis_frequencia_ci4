<?php

namespace App\Controllers;

use App\Models\FrequenciaModel;

class Salvar_frequencia extends BaseController
{
    public function salvar(){
        if($this->request->getmethod()!== 'post'){
            return redirect()->to('/dashboard');
        }
        $dataRegistro = $this->request->getPost('data_registro');
        $frequencias = $this->request->getPost('freq');
        $turmaId = $this->request->getPost('turma_id');
    
   if (empty($dataRegistro) || empty($frequencias)) {
        return redirect()
        ->to('/dashboard')
        ->with('erro', 'Deu ruim');
    }
     $model = new FrequenciaModel();

      // Percorre cada aluno
        foreach ($frequencias as $alunoId => $aulas) {

            // Procura se esse aluno já possui frequência nessa data
            $registro = $model
                ->where('aluno_id', $alunoId)
                ->where('data_registro', $dataRegistro)
                ->first();

            // Dados que serão gravados
            $dados = [
                'aluno_id'      => $alunoId,
                'data_registro' => $dataRegistro,

                'aula_1' => $aulas[1] ?? 'P'
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
