<?php

namespace App\Controllers;

use App\Models\TurmaModel;

class Login extends BaseController
{
     public function index()
    {
        $turmaModel = new TurmaModel();

        $data['turmas_db'] = $turmaModel->getTurmasComUsuarios();

        return view('legal/login', $data);

    }

     public function logout()
    {
        // Inicia a sessão para poder acessá-la
    session_start();

    // Limpa todas as variáveis da sessão
    session_unset();

    // Destrói a sessão completamente
    session_destroy();
    // Define os cabeçalhos para evitar cache do navegador  
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
    // Redireciona o usuário de volta para a tela de login
    header("Location: inicio.php");
    exit();

    }

    public function processar()
    {
        // 1. Carrega o helper de cookies e URLs
        helper(['cookie', 'url']);

        // 2. Captura os dados do formulário via Request do CI4
        $turmaId = $this->request->getPost('turma');
        $senha   = $this->request->getPost('senha');
        $lembrar = $this->request->getPost('lembrar');

        // 3. Consulta no banco juntando as tabelas 'usuarios' e 'turmas'
        $db = \Config\Database::connect();
        $usuario = $db->table('usuarios u')
                      ->select('u.*, t.serie, t.nome as nome_turma')
                      ->join('turmas t', 'u.turma_id = t.id')
                      ->where('u.turma_id', $turmaId)
                      ->where('u.senha', $senha)
                      ->get()
                      ->getRowArray();

        // 4. Se o usuário for encontrado
        if ($usuario) {
            // Salva as informações na Sessão do CI4
            session()->set([
                'logado'              => true,
                'usuario_id'          => $usuario['id'],
                'perfil'              => $usuario['perfil'],
                'turma_id'            => $usuario['turma_id'],
                'nome_turma_completo' => $usuario['serie'] . ' - ' . $usuario['nome_turma']
            ]);

            // Cria o cookie de "Me lembrar" por 30 dias se a opção foi marcada
            if ($lembrar == "1") {
                set_cookie([
                    'name'     => 'lembrar_usuario',
                    'value'    => $usuario['id'],
                    'expire'   => 86400 * 30,
                    'path'     => '/',
                    'httponly' => true,
                ]);
            }

            return redirect()->to('/dashboard');
        }

        // 5. Se falhar, redireciona de volta com mensagem de erro temporária (Flashdata)
        return redirect()->to('/login')->with('erro', 'Chave ou Senha incorreta. Tente novamente.');
    }
}