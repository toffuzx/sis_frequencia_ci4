<?php

namespace App\Controllers;

class Config extends BaseController
{
    public function index()
    {
        $data['perfil'] = session()->get('perfil');
        return view('legal/config', $data);

    }
}
