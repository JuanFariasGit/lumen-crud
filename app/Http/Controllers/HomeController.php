<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Exception;
use DB;

class HomeController extends Controller 
{
    public function index() 
    {
        return view('home');
    }

    public function create(Request $r) 
    {
        if ($r->has(['cpf', 'nome'])) {

            $cpf = $r->input('cpf');
            $nome = $r->input('nome');

            try {
                if (count(DB::select('select cpf from pessoas where cpf = ?', [$cpf])) == 0 ) {
                    DB::insert('insert into pessoas (cpf, nome) values (?, ?)', [$cpf, $nome]);
                    echo "dados cadastrados com sucesso !!!";
                } else {
                    throw new Exception('cpf já se encontra cadastrado !!!');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function read() 
    {
        $array = ['data' => []];

        $pessoas = DB::select('select * from pessoas');

        foreach($pessoas as $pessoa) {
            $array['data'][] = [
                'DT_RowId' => $pessoa->id,
                'ID' => $pessoa->id, 
                'CPF' => $pessoa->cpf,
                'NOME' => $pessoa->nome,
                'AÇÕES' => '<button class="btn btn-sm btn-primary" onclick="editar('.$pessoa->id.')"><i class="far fa-edit fa-lg"></i></button> <button class="btn btn-sm btn-danger" onclick="deletar('.$pessoa->id.')"><i class="far fa-trash-alt fa-lg"></i></button>'
            ];
        }

        echo json_encode($array);
    }

    public function update(Request $r) 
    {
        if ($r->has(['cpf', 'nome'])) {

            $id = $r->input('id');
            $cpf = $r->input('cpf');
            $nome = $r->input('nome');

            try {
                if ((count(DB::select('select cpf from pessoas where cpf = ? and id = ?', [$cpf, $id])) == 1) || (count(DB::select('select cpf from pessoas where cpf = ?', [$cpf])) == 0 )) {
                    DB::update('update pessoas set cpf = ?, nome = ? where id = ?', [$cpf, $nome, $id]);
                    echo "dados atualizados com sucesso !!!";
                } else {
                    throw new Exception('cpf já se encontra cadastrado !!!');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function delete(Request $r) 
    {
        if ($r->has(['id'])) { 

            $id = $r->input('id');

            DB::delete('delete from pessoas where id = ?', [$id]);

            echo 'Excluida com sucesso !!!';
        }
    }
}