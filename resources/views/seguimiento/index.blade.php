
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@include('seguimiento.mensajes')


{{-- <form action="{{ route('BUSCADOR1')}}" method="GET" role="search">
  <div class="input-group">
    <input type="text" name="q" id="q" class="form-control" placeholder="Search..."> <span class="input-group-btn">
          <button type="submit" class="btn btn-primary">
              <span class="glyphicon glyphicon-search" ><i class="fas fa-search"></i></span>
          </button>
         
      
  </div>
 
</form> --}}

<div>
  <input type="text" class="form-control" id="search" placeholder="Buscar...">
</div>
<br>

<h1>Listado De Seguimientos</h1>
<a href="{{route('Seguimiento.create')}}" title="DETALLE" class="btn  btn-primary">
  <span class="icon-zoom-in" ></span> NUEVO SEGUIMIENTO</a>
  <a href="{{route('export')}}" class="btn  btn-success " style="float: right;
  margin-right: 0;
  width: 14%;
  position: relative;
  right: 0;"><i class="fas fa-book"></i>   REPORTE</a>
    {{-- <section class="content-header">
      
        <h1 class="pull-right">
        
            
            <a class="btn btn-primary pull-right"
            style="margin-top: -10px;
            margin-bottom: 5px" href="{{url('sivigila/create')}}">Realizar seguimiento</a>
            
        </h1>
        </section> --}}
@stop

            @section('content')
            


            <table class="table">
                <thead class="table table-hover table-dark">
                  <tr>
                    <th scope="col">Identificacion</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Fecha Reporte</th>
                    <th scope="col">Ips</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody id="table">
                  <tr>
                    @foreach($incomeedit as $student2)
                    <th scope="row">{{ $student2->num_ide_ }}</th>
                    <td>{{ $student2->pri_nom_.' '.$student2->seg_nom_.' '.$student2->pri_ape_.' '.$student2->seg_ape_ }}</td>
                    <td>{{$student2->Fecha_ingreso_ingres}}</td>
                    <td>{{$student2->Ips_at_inicial}}</td>
                        
                      <td>  <a class="btn  btn-warning" href="{{url('/Seguimiento/'.$student2->id. '/edit')}}" class="ref" >
                        <i class="fas fa-edit"></i>
                    </a>
                  
                    
                  
                    <a href="{{route('Seguimiento.destroy', $student2->id)}}"
                      onclick="event.preventDefault();
                      if(confirm('¿Está seguro de que desea eliminar el producto?')) {
                      document.getElementById('delete-form-{{$student2->id}}').submit();
                      }" class="btn  btn-danger">
                     <i class="fas fa-trash"></i>
                   </a>
                   <form id="delete-form-{{$student2->id}}" action="{{route('Seguimiento.destroy', $student2->id)}}"
                    method="POST" style="display: none;">
                  @method('DELETE')
                  @csrf
              </form>
                  
                  </td>
                
                            
                </td>
                  </tr>
                
                 
             
                  
                  @endforeach 
            
                </tbody>
                
              </table>
              {{-- {{ $master->links() }}  --}}
            
          
             
            @stop
            

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript"> 
  $(document).ready(function(){
      $("#search").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#table tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
      });
  });
</script>
@stop



