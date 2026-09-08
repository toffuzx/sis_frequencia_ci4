<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['turma_id', 'senha', 'perfil'];

    /**
     * Busca o usuário trazendo os dados da turma associada
     */
    public function getUsuarioComTurma(int $id)
    {
        return $this->select('usuarios.*, turmas.serie, turmas.nome as nome_turma')
                    ->join('turmas', 'turmas.id = usuarios.turma_id')
                    ->where('usuarios.id', $id)
                    ->first();
    }
}
