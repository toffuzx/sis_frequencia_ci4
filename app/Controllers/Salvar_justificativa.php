<?php

namespace App\Controllers;

use App\Models\JustificativaalunoModel;

class Salvar_justificativa extends BaseController
{
    public function salvar()
    {
        // 1. Verifica se os dados vieram via JSON (Modal 1 - Botão "Marcar")
        $json = $this->request->getJSON(true);

        if (!empty($json)) {
            $alunoId      = $json['aluno_id'] ?? null;
            $dataRegistro = $json['data_registro'] ?? null;
            $atrasado     = $json['chegou_atrasado'] ?? 0;
            $fardamento   = $json['fardamento_incompleto'] ?? 0;
            $observacoes  = $json['observacoes'] ?? '';

            if (!$alunoId || !$dataRegistro) {
                return $this->response->setJSON([
                    'sucesso'  => false,
                    'mensagem' => 'Dados do aluno ou data inválidos.'
                ]);
            }

            $model = new JustificativaalunoModel();

            $registro = $model->where('aluno_id', $alunoId)
                              ->where('data_registro', $dataRegistro)
                              ->first();

            $dados = [
                'aluno_id'              => $alunoId,
                'data_registro'         => $dataRegistro,
                'chegou_atrasado'       => $atrasado,
                'fardamento_incompleto' => $fardamento,
                'observacoes'           => $observacoes,
            ];

            if ($registro) {
                $salvou = $model->update($registro['id'], $dados);
            } else {
                $salvou = $model->insert($dados);
            }

            return $this->response->setJSON([
                'sucesso'  => (bool)$salvou,
                'mensagem' => $salvou ? 'Marcações salvas!' : 'Erro ao salvar marcações.'
            ]);
        }

        // 2. Se não veio JSON, trata como POST comum / FormData (Modal 2 - Atestado/Gestão)
        $alunoId      = $this->request->getPost('aluno_id');
        $dataRegistro = $this->request->getPost('data_registro');
        $motivo       = $this->request->getPost('motivo');
        $observacoes  = $this->request->getPost('observacoes');
        $fileAtestado = $this->request->getFile('atestado');

        if (!$alunoId || !$dataRegistro) {
            return $this->response->setJSON([
                'sucesso'  => false,
                'mensagem' => 'Dados do aluno ou data não foram enviados.'
            ]);
        }

        $dados = [
            'aluno_id'      => $alunoId,
            'data_registro' => $dataRegistro,
            'motivo'        => $motivo,
            'observacoes'   => $observacoes,
        ];

        // Trata upload se houver arquivo
        if ($fileAtestado && $fileAtestado->isValid() && !$fileAtestado->hasMoved()) {
            $novoNome = $fileAtestado->getRandomName();
            $fileAtestado->move(WRITABLEPATH . 'uploads/atestados', $novoNome);

            $dados['arquivo_nome']    = $fileAtestado->getClientName();
            $dados['arquivo_caminho'] = 'uploads/atestados/' . $novoNome;
        }

        $model = new JustificativaalunoModel();

        $registro = $model->where('aluno_id', $alunoId)
                          ->where('data_registro', $dataRegistro)
                          ->first();

        if ($registro) {
            $salvou = $model->update($registro['id'], $dados);
        } else {
            $salvou = $model->insert($dados);
        }

        return $this->response->setJSON([
            'sucesso'  => (bool)$salvou,
            'mensagem' => $salvou ? 'Justificativa salva com sucesso!' : 'Erro ao salvar no banco.'
        ]);
    }
}