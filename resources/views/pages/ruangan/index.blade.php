@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'ruangan'
])

@section('content')
  <div class="content">
    <div id="app">
      <div class="row">
        @if (auth()->user()->akses_id == 1)
        <div class="col">
          <a href="{{ route('ruangan.covid') }}">
            <div class="card bg-primary shadow-none">
              <div class="card-body">
                <h4 class="text-white"><i class="fas fa-info"></i> Total: {{ $totalCovid }}</h4>
                <h4 class="text-white"><i class="fas fa-bed"></i> Data Kamar Covid</h4>
              </div>
            </div>
          </a>
        </div>
        @endif
        @if (auth()->user()->akses_id == 0)
        <div class="col">
          <a href="{{ route('ruangan.noncovid') }}">
            <div class="card bg-primary shadow-none">
              <div class="card-body">
                <h4 class="text-white"><i class="fas fa-info"></i> Total: {{ $totalNonCovid }}</h4>
                <h4 class="text-white"><i class="fas fa-bed"></i> Data Kamar Non Covid</h4>
              </div>
            </div>
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('css')
    
@endpush

@push('scripts')
    
@endpush