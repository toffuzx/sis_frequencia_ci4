<?php

namespace App\Controllers;

use App\Models\TurmaModel;
use App\Models\AlunoModel;
use App\Models\FrequenciaModel;

class Alterar_turma extends BaseController
{
    public function index()
    {
        // 1. VERIFICAÇÃO DE SEGURANÇA: Permite acesso estritamente ao perfil 'gestão'
        $perfil = session()->get('perfil');
        if ($perfil !== 'gestão' && $perfil !== 'gestao') {
            return view('errors/html/acesso_restrito');
        }

        $turmaModel = new TurmaModel();
        $alunoModel = new AlunoModel();

        $turmaId = $this->request->getGet('selecionar_turma_id') ?? 0;

        // Processamento de ações POST
        if ($this->request->getMethod() === 'POST') {
            $acao = $this->request->getPost('acao');
            $turmaIdPost = $this->request->getPost('turma_id');

            if ($acao === 'adicionar_individual') {
                $nome = trim($this->request->getPost('nome_aluno') ?? '');
                if (!empty($nome)) {
                    $alunoModel->insert([
                        'nome'     => mb_strtoupper($nome, 'UTF-8'),
                        'turma_id' => $turmaIdPost
                    ]);
                    session()->setFlashdata('sucesso', 'Aluno adicionado com sucesso!');
                } else {
                    session()->setFlashdata('erro', 'Digite o nome do aluno.');
                }
            }

            if ($acao === 'remover_individual') {
                $alunoId = $this->request->getPost('aluno_id');
                $frequenciaModel = new FrequenciaModel();
                
                // Remove histórico de frequência e o aluno
                $frequenciaModel->where('aluno_id', $alunoId)->delete();
                $alunoModel->delete($alunoId);
                
                session()->setFlashdata('sucesso', 'Aluno removido com sucesso!');
            }

            if ($acao === 'sobrescrever_turma') {
                $listaNomes = $this->request->getPost('lista_nomes') ?? '';
                $nomesArray = array_filter(array_map('trim', explode("\n", $listaNomes)));

                if (!empty($nomesArray)) {
                    $db = \Config\Database::connect();
                    $db->transStart();

                    // Limpa frequências e alunos antigos da turma
                    $frequenciaModel = new FrequenciaModel();
                    $alunosAntigos = $alunoModel->where('turma_id', $turmaIdPost)->findColumn('id');
                    
                    if (!empty($alunosAntigos)) {
                        $frequenciaModel->whereIn('aluno_id', $alunosAntigos)->delete();
                        $alunoModel->where('turma_id', $turmaIdPost)->delete();
                    }

                    // Insere os novos alunos
                    foreach ($nomesArray as $nome) {
                        if (!empty($nome)) {
                            $alunoModel->insert([
                                'nome'     => mb_strtoupper($nome, 'UTF-8'),
                                'turma_id' => $turmaIdPost
                            ]);
                        }
                    }

                    $db->transComplete();

                    if ($db->transStatus() === false) {
                        session()->setFlashdata('erro', 'Erro ao sobrescrever a turma.');
                    } else {
                        session()->setFlashdata('sucesso', 'Turma redefinida! ' . count($nomesArray) . ' novos alunos foram salvos.');
                    }
                } else {
                    session()->setFlashdata('erro', 'Cole ou digite a lista de alunos para sobrescrever.');
                }
            }

            return redirect()->to(base_url('alterar_turma?selecionar_turma_id=' . $turmaIdPost));
        }

        // Dados para renderizar a View
        $data['turmas_lista'] = $turmaModel->whereIn('serie', ['1º ANO', '2º ANO', '3º ANO'])
                                          ->orderBy('serie', 'ASC')
                                          ->orderBy('nome', 'ASC')
                                          ->findAll();

        $data['turma_atual_id'] = $turmaId;
        $data['alunos_turma_selecionada'] = $turmaId > 0 
            ? $alunoModel->where('turma_id', $turmaId)->orderBy('nome', 'ASC')->findAll() 
            : [];

        return view('legal/alterar_turma', $data);
    }
}