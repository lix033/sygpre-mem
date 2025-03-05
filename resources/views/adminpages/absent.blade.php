@extends('layouts.master')
@section('contenu')

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Absent du jour</h5>
       
        <!-- Table with stripped rows -->
        <table class="table datatable table-striped" id="table">
          <thead>
            <tr>
              <th>
                <b>N</b>om employé
              </th>
              <th>Prénom</th>
              <th>Département</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($employesAbsents as $absent)
            <tr>
              <td>{{$absent->nom}}</td>
              <td>{{$absent->prenom}}</td>
              <td>{{$absent->departement->nom_departement}}</td>
              <td><span class="badge text-bg-danger">Absent</span></td>
              {{-- <td>
                <i class="fas fa-edit text-primary"></i>
                <i class="fas fa-trash text-danger"></i>
              </td> --}}
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