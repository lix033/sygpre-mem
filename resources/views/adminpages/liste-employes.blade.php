@extends('layouts.master')
@section('contenu')

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Liste des employés</h5>
        {{-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable. Check for <a href="https://fiduswriter.github.io/simple-datatables/demos/" target="_blank">more examples</a>.</p> --}}

        <!-- Table with stripped rows -->
        <table class="table datatable table-striped" id="table">
          <thead>
            <tr>
              <th>Matricule</th>
              <th>Nom</th>
              <th>Prénoms</th>
              <th>Département</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
           @foreach ($listEmployes as $employe)
            <tr>
              <td>{{$employe->code_employe}}</td>
              <td>{{$employe->nom}}</td>
              <td>{{$employe->prenom}}</td>
              <td>{{ $employe->departement_id ? $employe->departement->nom_departement : 'N/A'}}</td>
              <td>
                <i class="fas fa-edit text-primary">Edit</i>
                <i class="fas fa-trash text-danger">Delete</i>
              </td>
            </tr>
           @endforeach
            
          </tbody>
        </table>
        <!-- End Table with stripped rows -->

      </div>
    </div>

  </div>
</div>

@endsection