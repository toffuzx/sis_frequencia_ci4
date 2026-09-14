<?php

namespace App\Controllers;

use App\Models\JustificativafaltaModel;

class Download extends BaseController
{
    public function atestado($id = null)
    {
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setBody('ID da justificativa não informado.');
        }

        // 1. Busca o registro da justificativa no banco
        $model = new JustificativafaltaModel();
        $justificativa = $model->find($id);

        if (!$justificativa) {
            return $this->response->setStatusCode(404)->setBody('Registro de atestado/falta não encontrado.');
        }

        // 2. Busca o caminho do arquivo no banco
        $caminhoBanco = $justificativa['arquivo_caminho'] ?? null;

        if (empty($caminhoBanco)) {
            return $this->response->setStatusCode(404)->setBody('Nenhum arquivo de atestado associado a este registro.');
        }

        // 3. Obtém apenas o nome do arquivo para evitar duplicar pastas no caminho
        $nomeArquivo = basename($caminhoBanco);

        // 4. Monta o caminho exato dentro de writable/uploads/atestados/
        $caminhoCompleto = \WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'atestados' . DIRECTORY_SEPARATOR . $nomeArquivo;

        if (!file_exists($caminhoCompleto)) {
            return $this->response->setStatusCode(404)->setBody('Arquivo físico não encontrado no servidor: ' . $nomeArquivo);
        }

        // 5. Realiza o download
        return $this->response->download($caminhoCompleto, null);
    }
}