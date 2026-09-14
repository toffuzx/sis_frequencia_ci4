<?php

namespace App\Controllers;

class Tabela extends BaseController
{
    public function index()
    {
        $data['perfil'] = session()->get('perfil');
        return view('legal/tabela', $data);

    }
}
