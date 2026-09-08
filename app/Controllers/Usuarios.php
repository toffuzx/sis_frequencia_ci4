<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Usuarios extends BaseController
{
    public function index()
    {
        $usuarioModel = new UsuarioModel();

        //  Pegar TODOS os usuários da tabela
        $todosUsuarios = $usuarioModel->findAll();

        // 2. Pegar apenas UM usuário pelo ID (exemplo: ID 3)
        $usuario = $usuarioModel->find(3);

        // 3. Pegar usuário junto com os dados da Turma (usando o método customizado)
        $usuarioComTurma = $usuarioModel->getUsuarioComTurma(3);

        // Enviar os dados para a View
        $data['usuarios'] = $todosUsuarios;
        return view('usuarios/lista', $data);
    }
}
