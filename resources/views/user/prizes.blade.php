@extends('user.user')

@section('content')
    <div class="max-w-5xl mx-auto p-4 md:p-8 animate-fade-in">

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-6">Мои призы</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-4">
            @each('user.partials._myprizes', $prizes, 'prize')
        </div>
    </div>
@stop
