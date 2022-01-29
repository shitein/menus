@extends('Menus::layouts.app')

@section('content')
    <div class="card-body pt-1 pb-1 accordion-list">
        @include('Menus::layouts.listing', compact('headers','listingData', 'paginationData', 'settings', 'addEditFormKey', 'filterFormData', 'filterFields'))
    </div>
    <!-- ADD Edit Modal -->
    <div id="modalAddEdit" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" id="modalContent">

            <!-- Modal content will be populated on ajax call for add/edit form-->

        </div>
    </div>
@endsection
