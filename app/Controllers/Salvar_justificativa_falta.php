<?php

namespace App\Controllers;

use App\Models\JustificativafaltaModel;
use App\Models\FrequenciaModel;

class Salvar_justificativa_falta extends BaseController
{
    public function salvar()
    {
        try {
            $alunoId      = $this->request->getPost('aluno_id');
            $dataRegistro = $this->request->getPost('data_registro');
            $motivo       = $this->request->getPost('motivo');
            $observacoes  = $this->request->getPost('observacoes');
            $fileAtestado = $this->request->getFile('atestado');

            if (!$alunoId || !$dataRegistro || !$motivo) {
                return $this->response->setJSON([
                    'sucesso'  => false,
                    'mensagem' => 'Selecione o motivo e preencha os campos obrigatórios.'
                ]);
            }

            $dados = [
                'aluno_id'      => $alunoId,
                'data_registro' => $dataRegistro,
                'motivo'        => $motivo,
                'observacoes'   => $observacoes,
                'usuario_id'    => session()->get('usuario_id') ?? null,
            ];

            // Processa o upload do arquivo se enviado
            if ($fileAtestado && $fileAtestado->isValid() && !$fileAtestado->hasMoved()) {
                $novoNome = $fileAtestado->getRandomName();
                $caminhoUpload = WRITEPATH . 'uploads/atestados'; // Corrigido para >WRITEPATH<

                if (!is_dir($caminhoUpload)) {
                    mkdir($caminhoUpload, 0777, true);
                }

                $fileAtestado->move($caminhoUpload, $novoNome);

                $dados['arquivo_nome']    = $fileAtestado->getClientName();
                $dados['arquivo_caminho'] = 'uploads/atestados/' . $novoNome;
                $dados['arquivo_tipo']    = $fileAtestado->getClientMimeType();
                $dados['arquivo_tamanho'] = $fileAtestado->getSize();
            }

            $model = new JustificativafaltaModel();

            $registro = $model->where('aluno_id', $alunoId)
                              ->where('data_registro', $dataRegistro)
                              ->first();

            $salvou = false;

            if ($registro) {
                // Se nada mudar no update, o CI4 lança exceção do tipo "There is no data to update."
                try {
                    $salvou = $model->update($registro['id'], $dados);
                } catch (\CodeIgniter\Database\Exceptions\DataException $e) {
                    // Captura e aceita o envio caso os dados sejam exatamente idênticos aos gravados
                    $salvou = true; 
                }
            } else {
                $salvou = (bool) $model->insert($dados);
            }

            if ($salvou) {
                // Atualiza o status do aluno para 'J' na tabela frequencias
                $frequenciaModel = new FrequenciaModel();
                
                try {
                    $frequenciaModel->where('aluno_id', $alunoId)
                                    ->where('data_registro', $dataRegistro)
                                    ->set(['aula_1' => 'J'])
                                    ->update();
                } catch (\CodeIgniter\Database\Exceptions\DataException $e) {
                    // Ignora caso a frequência já esteja marcada com status 'J'
                }

                return $this->response->setJSON([
                    'sucesso'  => true,
                    'mensagem' => 'Justificativa salva com sucesso!'
                ]);
            }

            return $this->response->setJSON([
                'sucesso'  => false,
                'mensagem' => 'Erro ao gravar dados na tabela de justificativas.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'sucesso'  => false,
                'mensagem' => 'Erro no servidor: ' . $e->getMessage()
            ]);
        }
    }
}