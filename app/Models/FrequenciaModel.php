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
        'aula_2',
        'aula_3',
        'aula_4',
        'aula_5',
        'aula_6',
        'aula_7',
        'aula_8',
        'aula_9',
    ];

    protected $returnType = 'array';
}
