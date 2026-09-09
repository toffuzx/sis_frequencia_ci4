<?php

namespace App\Models;

use CodeIgniter\Model;

class JustificativaalunoModel extends Model
{
    protected $table = 'justificativas_alunos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'aluno_id',
        'data_registro',
        'chegou_atrasado',
        'fardamento_incompleto',
        'observacoes',
        'criado_em',
        'atualizado_em',
    ];

    protected $returnType = 'array';
}
