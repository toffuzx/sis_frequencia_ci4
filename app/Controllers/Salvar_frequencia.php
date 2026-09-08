<?php

namespace App\Controllers;

use App\Models\TurmaModel;

class Salvar_frequencia extends BaseController

{

 public function index(){

// ADICIONE ESTA LINHA PARA SALVAR NO DIA CORRETO DO BRASIL:
date_default_timezone_set('America/Sao_Paulo');


// Segurança: Garante que só a liderança logada consiga salvar dados
    if (!session()->get('logado')) {
            return redirect()->to('/login');
        }

         return view('legal/dashboard',);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['freq'])) {
    
    $data_hoje = isset($_POST['data_registro']) ? $_POST['data_registro'] : date('Y-m-d');
    $frequencias = $_POST['freq']; // Pega o pacote de dados (Array) do formulário
    $turma_id = isset($_POST['turma_id']) ? $_POST['turma_id'] : '';

    // O loop passa aluno por aluno (Linha por linha da tabela)
    foreach ($frequencias as $aluno_id => $aulas) {
        
        // Passo A: Verifica se a liderança já tinha salvo a frequência deste aluno hoje
        $stmt_check = $pdo->prepare("SELECT id FROM frequencias WHERE aluno_id = ? AND data_registro = ?");
        $stmt_check->execute([$aluno_id, $data_hoje]);

        if ($stmt_check->rowCount() > 0) {
            // Se JÁ EXISTE no banco, fazemos o UPDATE
            $sql = "UPDATE frequencias SET aula_1=?, aula_2=?, aula_3=?, aula_4=?, aula_5=?, aula_6=?, aula_7=?, aula_8=?, aula_9=? WHERE aluno_id=? AND data_registro=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $aulas[1] ?? 'P', 
                $aulas[2] ?? 'P', 
                $aulas[3] ?? 'P', 
                $aulas[4] ?? 'P', 
                $aulas[5] ?? 'P', 
                $aulas[6] ?? 'P', 
                $aulas[7] ?? 'P', 
                $aulas[8] ?? 'P', 
                $aulas[9] ?? 'P', 
                $aluno_id, 
                $data_hoje
            ]);
        } else {
            // Se NÃO EXISTE, fazemos o INSERT
            $sql = "INSERT INTO frequencias (aluno_id, data_registro, aula_1, aula_2, aula_3, aula_4, aula_5, aula_6, aula_7, aula_8, aula_9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $aluno_id, 
                $data_hoje, 
                $aulas[1] ?? 'P', 
                $aulas[2] ?? 'P', 
                $aulas[3] ?? 'P', 
                $aulas[4] ?? 'P', 
                $aulas[5] ?? 'P', 
                $aulas[6] ?? 'P', 
                $aulas[7] ?? 'P', 
                $aulas[8] ?? 'P', 
                $aulas[9] ?? 'P'
            ]);
        }
    }

    // Devolve o usuário para o dashboard mantendo o dia e a turma que ele acabou de salvar
    header("Location: dashboard.php?sucesso=1&data_busca=" . $data_hoje . "&turma_busca=" . $turma_id);
    exit();
} else {
    // Se tentarem acessar a página direto pela URL
    header("Location: dashboard.php");
    exit();
}
 }
}