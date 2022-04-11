@extends('layouts.app')

@section('title', 'CRUD LUMEN')

@section('header')
    <h1>CRUD Desenvolvimento Web Com PHP e Lumen</h1>
@endsection

@section('content')
    <button class="btn btn-sm btn-primary mb-2" onclick="adicionar()"><i class="fas fa-plus-circle fa-lg"></i></button>
    <div class="mensagem"></div>
    <table id="tabela" class="table table-striped table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>CPF</th>
                <th>NOME</th>
                <th>AÇÕES</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>CPF</th>
                <th>NOME</th>
                <th>AÇÕES</th>
            </tr>
        </tfoot>
    </table>

    <div id="modal-form" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="cpf">CPF</label>
                            <input class="form-control" type="text" id="cpf" name="cpf">
                            <div class="erro-cpf"></div>
			            </div>
                        <div class="form-group">
                            <label for="nome">NOME</label>
                            <input class="form-control" type="text" id="nome" name="nome">
                            <div class="erro-nome"></div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary"></button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="fecharForm()">CANCELAR</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
const tabela = $('#tabela').DataTable( {
    "responsive": true,
    "autoWidth": false,
    "columnDefs": [{
    "targets": [1, 2, 3],
    "orderable": false
    }],
    "ajax": {
        "method": "POST",
        "url": "{{ route('read') }}",
    },
    "columns": [
        {
            "data":"id"
        },
        {
            "data":"cpf"
        },
        {
            "data":"nome"
        },
        {
            "data": function (data) {
                let html = ''

                html += `<button id="${data.id}" class="btn btn-sm btn-primary" onclick="editar('${data.id}')">
                    <i class="far fa-edit fa-lg"></i></button>`
                html += `<button id="row_${data.id}" class="btn btn-sm btn-danger mx-1"
                    onclick="deletar('${data.id}')">
                        <i class="far fa-trash-alt fa-lg"></i></button>`

                return html
            }
        }
    ],
    "order": [0, "desc"],
    "lengthMenu": [5, 10, 15],
    "pagingType": "full_numbers",
    "language": {
        "infoFiltered":   "(filtrado do total de _MAX_ entradas)",
        "infoEmpty":      "Mostrando 0 a 0 de 0 entradas",
        "zeroRecords": "Nenhum registro correspondente encontrado",
        "loadingRecords": "Carregando...",
        "lengthMenu": "Mostrar _MENU_ entradas",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "search": "Procurar:",
        "paginate": {
        "first": "«",
        "last":  "»",
        "next":  "›",
        "previous": "‹"
        }
    },
});

function fechar() {
    $('#modal').modal('hide')
    $('#modal').find('.modal-header').html('')
}

function fecharForm() {
    $('#modal-form').modal('hide')
    $('#modal-form').find('.modal-header').html('')
    $('#modal-form').find('form input[name=id]').remove()
    $('#modal-form').find('form input[name="cpf"]').val('')
    $('#modal-form').find('form input[name="nome"]').val('')
    $('.erro-cpf').text('')
    $('.erro-nome').text('')
    $('#modal-form').find('form button').html('')
    $('#modal-form').find('form').off('submit')
}

function adicionar() {
    $('#modal-form').find('.modal-header').html('ADICIONAR PESSOA')
    $('#modal-form').find('form button').html('ADICIONAR')
    $('#modal-form').find('form').on('submit', adicionarPessoa)
    $('#modal-form').modal('show')
}

function adicionarPessoa(e) {
    e.preventDefault()
    const cpf = $(this).find('input[name="cpf"]').val()
    const nome = $(this).find('input[name="nome"]').val()

    $.ajax ({
        'method': 'POST',
        'url': "{{ route('create') }}",
        'data': {'cpf':cpf, 'nome':nome},
        'success': function() {
            tabela.ajax.reload()
            $('.mensagem').prepend(`<div class="alert alert-success" role="alert">
            dados cadastrados com sucesso !!!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button></div>`)
            fecharForm()
        },
        'error': function(resposta) {
            let {cpf, nome} = resposta.responseJSON
            $('.erro-cpf').text(cpf)
            $('.erro-nome').text(nome)
        }
    })
}

function editar(id) {
    const cpf = $(`#${id}`).parents('tr').find('td:eq(1)').html()
    const nome = $(`#${id}`).parents('tr').find('td:eq(2)').html()

    $('#modal-form').find('.modal-header').html('EDITAR PESSOA')
    $('#modal-form').find('form').prepend('<input type="hidden" name="id">')
    $('#modal-form').find('form input[name=id]').val(id)
    $('#modal-form').find('form input[name="cpf"]').val(cpf)
    $('#modal-form').find('form input[name="nome"]').val(nome)
    $('#modal-form').find('form button').html('SALVAR')
    $('#modal-form').find('form').on('submit', editarPessoa)
    $('#modal-form').modal('show')
}

function editarPessoa(e) {
    e.preventDefault()
    const id = $(this).find('input[name="id"]').val()
    const cpf = $(this).find('input[name="cpf"]').val()
    const nome = $(this).find('input[name="nome"]').val()

    $.ajax ({
        'method': 'POST',
        'url': "{{ route('update') }}",
        'data': {'id':id, 'cpf':cpf, 'nome':nome},
        'success': function() {
            tabela.cell($(`#${id}`).parents('tr'), 0).data(id).draw(false)
            tabela.cell($(`#${id}`).parents('tr'), 1).data(cpf).draw(false)
            tabela.cell($(`#${id}`).parents('tr'), 2).data(nome).draw(false)
            $('.mensagem').prepend(`<div class="alert alert-success" role="alert">
            dados atualizados com sucesso !!!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button></div>`)
            fecharForm()
        },
        'error': function(resposta) {
            let {cpf, nome} = resposta.responseJSON
            $('.erro-cpf').text(cpf)
            $('.erro-nome').text(nome)
        }
    })
}

function deletar(id) {
    $('#modal').find('.modal-header').html(`<h5>Realmente deseja deletar a tarefa de id "${id}" ?</h5>`)
    $('#modal').find('.modal-footer').html(`<button class="btn btn-sm btn-success" onclick="deletarPessoa(${id})">SIM</button> <button class="btn btn-sm btn-danger" onclick="fechar()">NÃO</button>`)
    $('#modal').modal('show')
}

function deletarPessoa(id) {
    $.ajax({
        'method': 'POST',
        'url': "{{ route('delete') }}",
        'data': {'id': id},
        'success': function() {
            tabela.row($(`#${id}`).parents('tr')).remove().draw(false)
            fechar()
        }
    })
}
</script>
@endsection
