<ul class="list-group list-group-flush">
  @foreach ($diagnosis as $d)
  <li class="list-group-item text-sm">{{ $d->DiagnosisCode }} : <span class="text-danger">{{ $d->DiagnosisName }}</span></li>
  @endforeach
</ul>