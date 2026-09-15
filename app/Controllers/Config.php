<?php

namespace App\Controllers;

class Config extends BaseController
{
    public function index()
    {
         $perfil = session()->get('perfil');
        if ($perfil !== 'gestão' && $perfil !== 'gestao') {
            return view('errors/html/acesso_restrito');
        }

        $data['perfil'] = session()->get('perfil');
        return view('legal/config', $data);

    }
}
