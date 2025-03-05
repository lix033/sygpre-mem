@extends('layouts.master')
@section('contenu')

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Enregistrez un département</h5>
       
        @if ($errors->any())
            @foreach ($errors->all() as $error)
               <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif

    @if (session()->has('success'))
        <div class="alert alert-success">{{session()->get('success')}}</div>
    @endif
        <!-- General Form Elements -->
        <form action="{{route('ajout.departement.action')}}" method="Post">
          @csrf
          <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Nom département</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nom_departement">
            </div>
          </div>
          <div class="row mb-3">
            <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="description">
            </div>
          </div>
         
          <div class="row mb-3">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Enregistrez departement</button>
            </div>
          </div>

        </form><!-- End General Form Elements -->

      </div>
    </div>

  </div>

  
    </div>

  </div>
</div>

@endsection