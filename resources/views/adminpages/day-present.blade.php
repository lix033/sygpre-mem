@extends('layouts.master')
@section('contenu')

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Présence du jour</h5>
       
        <!-- Table with stripped rows -->
        <table class="table datatable table-striped" id="table">
          <thead>
            <tr>
              <th>
                <b>N</b>om employé
              </th>
              <th>Prénom</th>
              <th>Heure arrivé</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($presenceJour as $present)
            <tr>
              <td>{{$present->employe->nom}}</td>
              <td>{{$present->employe->prenom}}</td>
              <td>{{$present->heure_point}}</td>
              @if ($present->motif == "Arrivée")
              <td><span class="badge text-bg-success">{{$present->motif}}</span></td>
              @elseif ($present->motif == "Sortie")
              <td><span class="badge text-bg-warning">{{$present->motif}}</span></td>
              @elseif ($present->motif == "Retour")
              <td><span class="badge text-bg-primary">{{$present->motif}}</span></td>
              @else
              <td><span class="badge text-bg-secondary">{{$present->motif}}</span></td>
              @endif
              <td>
                <i class="fas fa-edit text-primary"></i>
                <i class="fas fa-trash text-danger"></i>
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