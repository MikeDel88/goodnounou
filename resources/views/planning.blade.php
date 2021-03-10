@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Mon Agenda</h4>
        </header>

        {{-- Planning --}}
        <div id='calendar' data-planning="{{ Auth::user()->id }}" class="box__contenu position-relative box__calendar" style="border-right:none"></div>

        {{-- Modal en cas de click sur un evenement pour avoir des informations complétementaires --}}
        <div id="modalEvent" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </article>
@endsection
