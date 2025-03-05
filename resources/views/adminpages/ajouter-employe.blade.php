@extends('layouts.master')
@section('contenu')

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Enregistrez un employé</h5>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
               <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif

    @if (session()->has('success'))
        <div class="alert alert-success">{{session()->get('success')}}</div>
    @endif

        <!-- General Form Elements -->
        <form action="{{route('action.ajout.employe')}}"  method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Nom</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nom">
            </div>
          </div>
          <div class="row mb-3">
            <label for="inputPassword" class="col-sm-2 col-form-label">Prénom</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="prenom">
            </div>
          </div>
          <div class="row mb-3">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" name="email">
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Département</label>
            <div class="col-sm-10">
              <select class="form-select" aria-label="Default select example" name="departement">
                <option selected>Choisissez le département</option>
                @foreach ($departements as $departement)
                
                <option value="{{$departement->id}}">{{$departement->nom_departement}}</option>
                @endforeach
                
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="inputNumber" class="col-sm-2 col-form-label">Photo</label>
            <div class="col-sm-10">
              <input class="form-control" type="file" id="formFile" name="image">
            </div>
          </div>

         
          <div class="row mb-3">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Enregistrez employé</button>
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