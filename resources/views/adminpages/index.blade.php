@extends('layouts.master')
@section('contenu')


<div class="row">

  <!-- Left side columns -->
  <div class="col-lg-8">
    <div class="row">

      <!-- Sales Card -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card">

          <div class="card-body">
            <h5 class="card-title">Employés <span>| Total</span></h5>

            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-people"></i>
              </div>
              <div class="ps-3">
                <h6>{{$totalEmployes}}</h6>
                {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

              </div>
            </div>
          </div>

        </div>
      </div><!-- End Sales Card -->

      <!-- Revenue Card -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card revenue-card">

          <div class="card-body">
            <h5 class="card-title">Présence <span>| Aujourd'huie</span></h5>

            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-card-checklist"></i>
              </div>
              <div class="ps-3">
                <h6>{{$presenceJour}}</h6>
                {{-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

              </div>
            </div>
          </div>

        </div>
      </div><!-- End Revenue Card -->

      <!-- Customers Card -->
      <div class="col-xxl-4 col-xl-12">

        <div class="card info-card customers-card">

          <div class="card-body">
            <h5 class="card-title">Superviseur</h5>

            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-people"></i>
              </div>
              <div class="ps-3">
                <h6>{{$totalSuperviseurs}}</h6>
                {{-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> --}}

              </div>
            </div>

          </div>
        </div>

      </div><!-- End Customers Card -->
    
    </div>
  </div><!-- End Left side columns -->
   
</div>

@endsection