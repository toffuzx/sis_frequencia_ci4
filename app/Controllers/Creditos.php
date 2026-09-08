<?php

namespace App\Controllers;

class Creditos extends BaseController
{
    public function index()
    {
        return view('legal/creditos')
             . view('legal/rodapelegal');
    }
}
