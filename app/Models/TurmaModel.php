<?php

namespace App\Models;

use CodeIgniter\Model;

class TurmaModel extends Model
{
    protected $table         = 'turmas';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['serie', 'nome'];

    /**
     * Busca apenas as turmas/cargos que têm usuário e senha cadastrados
     */
    public function getTurmasComUsuarios()
    {
        return $this->select('turmas.id, turmas.serie, turmas.nome')
                    ->join('usuarios', 'usuarios.turma_id = turmas.id')
                    ->groupBy('turmas.id')
                    ->orderBy('turmas.serie', 'ASC')
                    ->orderBy('turmas.nome', 'ASC')
                    ->findAll();
    }
}