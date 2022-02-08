@extends('layouts.app')

@section('css-header')
<style>
  .twitter-typeahead, .tt-hint, .tt-input, .tt-menu { width: 100%; }
  .tt-query, .tt-hint { outline: none;}
  .tt-query { box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);}
  .tt-hint {color: #999;}
  .tt-menu { 
      width: 100%;
      margin-top: 12px;
      padding: 8px 0;
      background-color: #fff;
      border: 1px solid #ccc;
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      box-shadow: 0 5px 10px rgba(0,0,0,.2);
  }
  .tt-suggestion { padding: 3px 20px; }
  .tt-suggestion.tt-is-under-cursor { color: #fff; }
  .tt-suggestion p { margin: 0;}
</style>
@endsection

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('tramitacoes.index') }}">Lista de Tramitações</a></li>
      <li class="breadcrumb-item active" aria-current="page">Alterar Registro</li>
    </ol>
  </nav>
</div>





<div class="container">
  @if(Session::has('edited_protocolotipo'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Info!</strong>  {{ session('edited_protocolotipo') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
</div>


<div class="container">
  @if(Session::has('create_tramitacao'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Info!</strong>  {{ session('create_tramitacao') }}
    <p>{{ session('novaTramitacaoID') }}</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
</div>

<div class="container py-2" id="tramitacao">

  <div class="card">
      <div class="card-header">
        <div class="row">
          <strong class="align-middle"> Tramitado em {{$tramitacao->created_at->format('d/m/Y')}} {{$tramitacao->created_at->diffForHumans()}}</strong>
        </div>   
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <div class="container p-3 mb-2 bg-info text-white">
              <div class="row">           
                <div class="col">
                  <label for="tramitado_para"><i class="bi bi-arrow-right"></i> Tramitado para o funcionário:</label>
                  <h3 id="tramitado_para">{{$tramitacao->userDestino->name}}</h3> 
                </div>
                <div class="col">
                  <label for="no_setor"><i class="bi bi-arrow-right"></i> Do setor:</label>
                  <h3 id="no_setor">{{$tramitacao->setorDestino->descricao}}</h3> 
                </div>
              </div>
            </div>
          </li>


          <li class="list-group-item">
            <div class="container">
              <div class="row">           
                <div class="col">
                  <label for="funcionario_origem">Funcionário de Origem:</label>
                  <h4 id="funcionario_origem">{{$tramitacao->userOrigem->name}} </h4> 
                </div>
                <div class="col">
                  <label for="setor_origem">Setor de Origem:</label>  
                  <h4 id="setor_origem">{{$tramitacao->setorOrigem->descricao}}  </h4> 
                </div>
              </div>
            </div>
          </li>


          <li class="list-group-item">          
            <div class="container">
              <div class="row">
                <div class="col">
                  @if ($tramitacao->recebido == 's')
                    <strong>Recebido em {{$tramitacao->recebido_em->format('d/m/Y')}}</strong>
                  @else
                    <h4><span class="badge badge-danger">Não Recebido</span></h4>
                  @endif
                </div>
                <div class="col">
                  @if ($tramitacao->recebido == 's')
                    <strong>Tramitado em {{$tramitacao->tramitado_em->format('d/m/Y')}}</strong>
                  @else
                    <h4><span class="badge badge-warning">Não Tramitado</span></h4>
                  @endif
                </div>
              </div>
            </div>
          </li>

          @if ( strlen($tramitacao->mensagem) > 0 )
          <li class="list-group-item">
            <div class="container p-2 mb-2 bg-light text-dark">       
              <p><strong>Mensagem:</strong> {{ $tramitacao->mensagem }}</p>
            </div>
          </li>
          @endif

          @if ( strlen($tramitacao->mensagemRecebido) > 0 )
          <li class="list-group-item">
            <div class="container">       
              <p><strong>Mensagem de recebimento:</strong> {{ $tramitacao->mensagemRecebido }}</p>
            </div>
          </li>
          @endif

          <li class="list-group-item">  
            <div class="container py-2 text-center" id="opcoestramitacoes">
              

              <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalReceberProtocolo">
                <i class="bi bi-hand-thumbs-up"></i> Receber
              </button>



              <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalTramitarProtocolo">
                <i class="bi bi-arrow-clockwise"></i> Tramitar 
              </button>    


            </div>
          </li>
        </ul>
      </div>  
    </div>
</div>







<div class="container" id="protocolo">
  <div class="container">
    <form>
      <div class="form-row">
        <div class="form-group col-md-3">
          <div class="p-3 bg-primary text-white text-right h2">Nº {{ $protocolo->id }}</div>    
        </div>
        <div class="form-group col-md-2">
          <label for="dia">Data</label>
          <input type="text" class="form-control" name="dia" value="{{ $protocolo->created_at->format('d/m/Y') }}" readonly>
        </div>
        <div class="form-group col-md-2">
          <label for="hora">Hora</label>
          <input type="text" class="form-control" name="hora" value="{{ $protocolo->created_at->format('H:i') }}" readonly>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="dia">Funcionário Responsável pelo Protocolo</label>
          <input type="text" class="form-control" name="dia" value="{{ $protocolo->user->name }}" readonly tabindex="-1">
        </div>
        <div class="form-group col-md-6">
          <label for="dia">Setor de Origem</label>
          <input type="text" class="form-control" name="dia" value="{{ $protocolo->user->setor->descricao  }}" readonly tabindex="-1">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="referencia">N° do Processo/Referência</label>
          <input type="text" class="form-control{{ $errors->has('referencia') ? ' is-invalid' : '' }}" name="referencia" value="{{ old('referencia') ?? $protocolo->referencia }}" readonly>
          @if ($errors->has('referencia'))
          <div class="invalid-feedback">
          {{ $errors->first('referencia') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-6">
          <label for="protocolo_tipo">Tipo do Protocolo</label>
          <input type="text" class="form-control" name="protocolo_tipo" value="{{ $protocolo->protocoloTipo->descricao }}" readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="conteudo">Conteúdo/Descrição</label>
        <textarea class="form-control" name="conteudo" rows="5" readonly>{{ $protocolo->conteudo }}</textarea>    
      </div>

      @if ($protocolo->concluido === 'n')
        <div class="form-group">
          <label for="situacao">Situação</label>
          <input type="text" class="form-control" name="situacao" value="{{ $protocolo->protocoloSituacao->descricao }}" readonly style="font-weight: bold;">
        </div>
      @else
      <div class="form-row">
          <div class="form-group col-sm-6">
            <label for="situacao">Situação</label>
            <input type="text" class="form-control" name="situacao" value="{{ $protocolo->protocoloSituacao->descricao }}" readonly style="font-weight: bold;">
          </div>
          <div class="form-group col-sm-3">
            <label for="protocolo_data_conclusao">Data(conclusão)</label>
            <input type="text" class="form-control" name="protocolo_data_conclusao" value="{{ $protocolo->concluido_em->format('d/m/Y') }} " readonly>
          </div>
          <div class="form-group col-sm-3">
            <label for="protocolo_hora_conclusao">Hora(conclusão)</label>
            <input type="text" class="form-control" name="protocolo_hora_conclusao" value="{{ $protocolo->concluido_em->format('H:i')  }}" readonly>
          </div>
        </div>
        <div class="form-group">
          <label for="conteudo">Notas de conclusão</label>
          <textarea class="form-control" name="conteudo" rows="3" readonly>{{ $protocolo->concluido_mensagem }}</textarea>
        </div>
      @endif
    </form>
  </div>


<div class="container">
    <div class="container bg-primary text-light">
      <p class="text-center"><strong>Anexos</strong></p>
    </div>
    @if ($protocolo->concluido === 'n')
    <div class="container">
      <form method="POST" action="{{ route('anexos.store') }}" class="form-inline" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="protocolo_id" name="protocolo_id" value="{{ $tramitacao->protocolo->id }}">
        <input type="hidden" id="tramitacao_id" name="tramitacao_id" value="{{ $tramitacao->id }}">
        <div class="form-group p-2">
          <label for="arquivos">Arquivos</label>
          <input type="file" class="form-control-file" id="arquivos" name="arquivos[]" multiple data-show-upload="true" data-show-caption="true">
        </div>
        <div class="form-group py-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> Anexar Arquivo</button>
          <button type="button" class="btn btn-secondary" data-toggle="popover" title="Informações sobre o arquivo" data-placement="right" data-content="Somente são aceitos os seguintes formatos para o arquivo: pdf, doc, docx, ppt, pptx, xls, xlsx, png, jpg, jpeg ou rtf. Cada arquivo não pode ter mais de 10Mb. Você pode adicionar múltiplos arquivos de uma só vez."><i class="bi bi-info-square"></i> Info</button>
        </div>
      </form>  
    </div>
    @endif

    <div class="container">
      @if(Session::has('create_anexo'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Info!</strong>  {{ session('create_anexo') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if(Session::has('delete_anexo'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Info!</strong>  {{ session('delete_anexo') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if($errors->has('arquivos.*'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><b><i class="bi bi-exclamation-diamond"></i> Erro! </b></strong> {{ $errors->first('arquivos.*') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if ( !$anexos->isEmpty() ) 
      <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Arquivo</th>
                    <th scope="col">Enviado por</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($anexos as $anexo)
                <tr>
                    <td>{{ $anexo->created_at->format('d/m/Y')  }}</td>
                    <td>{{ $anexo->created_at->format('H:i') }}</td>
                    <td><a href="{{ route('anexos.download', $anexo->codigoAnexoPublico) }}">{{ $anexo->arquivoNome }}</a></td>
                    <td>{{ $anexo->user->name }}</td>
                    <td>
                      @if ($anexo->user->id === Auth::user()->id)
                        @if ($protocolo->concluido === 'n')
                        <form method="post" action="{{route('anexos.destroy', $anexo->id)}}"  onsubmit="return confirm('Você tem certeza que quer excluir esse arquivo?');">
                          @csrf
                          @method('DELETE')  
                          <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                      @endif  
                    </td>                        
                </tr>    
                @endforeach                                                 
            </tbody>
        </table>
      </div>
      @else
        <p>Nenhum anexo encontrado</p>
      @endif
    </div>  
  </div>
</div>


<div class="container">
  <div class="float-right">
    <a href="{{ route('tramitacoes.index') }}" class="btn btn-primary" role="button"><i class="bi bi-arrow-left-square"></i> Voltar</a>
  </div>
</div>
<br>


  @if ($protocolo->concluido === 'n')
  <!-- Janela para tramitar o protocolo -->
  <div class="modal fade" id="modalTramitarProtocolo" tabindex="-1" role="dialog" aria-labelledby="JanelaFiltro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="bi bi-arrow-clockwise"></i> Tramitar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('tramitacoes.store') }}">
            @csrf
            <input type="hidden" id="protocolo_id" name="protocolo_id" value="{{ $tramitacao->protocolo->id }}">
            <input type="hidden" id="tramitacao_id" name="tramitacao_id" value="{{ $tramitacao->id }}">

            <div class="form-group">
              <label for="funcionario_tramitacao">Funcionário <strong  class="text-danger">(*)</strong></label>
              <input type="text" class="form-control typeahead" name="funcionario_tramitacao" id="funcionario_tramitacao" autocomplete="off">
              <input type="hidden" id="funcionario_tramitacao_id" name="funcionario_tramitacao_id">
              <input type="hidden" id="setor_tramitacao_id" name="setor_tramitacao_id">
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="funcionario_tramitacao_setor">Setor</label>
                <input type="text" class="form-control" name="funcionario_tramitacao_setor" id="funcionario_tramitacao_setor" readonly tabIndex="-1" placeholder="">
              </div>
              <div class="form-group col-md-6">
                <label for="funcionario_tramitacao_email">E-mail</label>
                <input type="text" class="form-control" name="funcionario_tramitacao_email" id="funcionario_tramitacao_email" readonly tabIndex="-1" placeholder="">
              </div>
            </div>

            <div class="form-group">
              <label for="mensagem">Mensagem <strong class="text-warning">(Opcional)</strong></label>
              <textarea class="form-control" name="mensagem" rows="3"></textarea> 
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i> Tramitar</button>

          </form>  
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Fechar</button>
        </div>
      </div>
    </div>
  </div>
  @endif

@endsection

@section('script-footer')
<script src="{{ asset('js/typeahead.bundle.min.js') }}"></script>
<script>
$(document).ready(function(){

var funcionarios = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "{{route('users.autocomplete')}}?query=%QUERY",
            wildcard: '%QUERY'
        },
        limit: 10
    });
    funcionarios.initialize();

    $("#funcionario_tramitacao").typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: "funcionarios",
                displayKey: "text",
                source: funcionarios.ttAdapter(),
                templates: {
                  empty: [
                    '<div class="empty-message">',
                      '<p class="text-center font-weight-bold text-warning">Não foi encontrado nenhum funcionário com o texto digitado.</p>',
                    '</div>'
                  ].join('\n'),
                  suggestion: function(data) {
                      return '<div class="text-dark"><div>' + data.text + ' - <strong>Setor:</strong> ' + data.setor + '</div></div>';
                    }
                }
        }).on("typeahead:selected", function(obj, datum, name) {
            console.log(datum);
            $(this).data("seletectedId", datum.value);
            $('#funcionario_tramitacao_id').val(datum.value);
            $('#funcionario_tramitacao_setor').val(datum.setor);
            $('#funcionario_tramitacao_email').val(datum.email);
            $('#setor_tramitacao_id').val(datum.setor_id);
        }).on('typeahead:autocompleted', function (e, datum) {
            console.log(datum);
            $(this).data("seletectedId", datum.value);
            $('#funcionario_tramitacao_id').val(datum.value);
            $('#funcionario_tramitacao_setor').val(datum.setor);
            $('#funcionario_tramitacao_email').val(datum.email);
            $('#setor_tramitacao_id').val(datum.setor_id);
    });

    $(function () {
      $('[data-toggle="popover"]').popover()
    })   

});
</script>
@endsection
