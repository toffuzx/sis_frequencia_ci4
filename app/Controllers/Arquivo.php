<?php

namespace App\Controllers;

use App\Models\JustificativafaltaModel;

class Arquivo extends BaseController
{
    public function atestado($id)
    {
        $model = new JustificativafaltaModel();
        $justificativa = $model->find($id);

        if (!$justificativa || empty($justificativa['arquivo_caminho'])) {
            return $this->response->setStatusCode(404)->setBody('Arquivo não encontrado.');
        }

        $caminhoRelativo = $justificativa['arquivo_caminho'];
        $nomeArquivoFisico = basename($caminhoRelativo);

        $caminhoCompleto = WRITEPATH . 'uploads/' . $caminhoRelativo;

        if (!file_exists($caminhoCompleto)) {
            return $this->response->setStatusCode(404)->setBody('Arquivo não encontrado.');
        }

        $mimeType = $justificativa['arquivo_tipo'] ?? mime_content_type($caminhoCompleto);
        $nomeOriginal = $justificativa['arquivo_nome'] ?? $nomeArquivoFisico;

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $nomeOriginal . '"')
            ->setBody(file_get_contents($caminhoCompleto));
    }
}