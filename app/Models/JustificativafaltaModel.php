<?php

namespace App\Models;

use CodeIgniter\Model;

class JustificativafaltaModel extends Model
{
    protected $table = 'justificativas_faltas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'aluno_id',
        'data_registro',
        'motivo',
        'observacoes',
        'arquivo_nome',
        'arquivo_caminho',
        'arquivo_tipo',
        'arquivo_tamanho',
        'usuario_id',
        'criado_em',
        'atualizado_em',
    ];

    protected $returnType = 'array';
}
