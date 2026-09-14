<?php

namespace App\Models;

use CodeIgniter\Model;

class FrequenciaModel extends Model
{
    protected $table = 'frequencias';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'aluno_id',
        'data_registro',
        'aula_1',
    ];

    protected $returnType = 'array';
}
