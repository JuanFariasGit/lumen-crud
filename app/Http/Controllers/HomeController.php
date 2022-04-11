<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Pessoa;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function create(Request $req)
    {
        $rules = [
            'cpf' => 'required|min:14|max:14|unique:pessoas',
            'nome' => 'required|min:3|max:100'
        ];

        $messages = [
            'cpf.unique' => 'Já existe uma pessoa cadastrada com esse cpf',
            'min' => 'O campo deve ter no mínimo 14 caracteres',
            'max' => 'O campo deve ter no máximo 14 caracteres',
            'required' => 'Campo obrigatório',
        ];

        $this->validate($req, $rules, $messages);

        Pessoa::create($req->all());
    }

    public function read()
    {
        return ['data' => Pessoa::all()];
    }

    public function update(Request $req)
    {
        $id = $req->input('id');

        $rules = [
            'cpf' => 'required|min:14|max:14|unique:pessoas,cpf,'.$id,
            'nome' => 'required|min:3|max:100'
        ];

        $messages = [
            'cpf.unique' => 'Já existe uma pessoa cadastrada com esse cpf',
            'min' => 'O campo deve ter no mínimo 14 caracteres',
            'max' => 'O campo deve ter no máximo 14 caracteres',
            'required' => 'Campo obrigatório',
        ];

        $this->validate($req, $rules, $messages);

        Pessoa::find($id)->update($req->all());
    }

    public function delete(Request $req)
    {
        $id = $req->input('id');

        Pessoa::find($id)->delete();
    }
}
