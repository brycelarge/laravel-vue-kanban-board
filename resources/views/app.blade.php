@extends('layout.main')

@section('content')
    <div class="container-fluid h-100" id="kanban-board">
        <kanban-board></kanban-board>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('/js/components/app.js') }}"></script>
@stop
