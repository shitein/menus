<!--
/*
*  Author : Shyam Kadam
*  Date   : March 2020
*
*  List view to show tables.
*  Called from listing-container and can be called independantly.
*
*/

/*  Updated by :
*   Updated on :
*
*
*/
-->
<section class="articles">
    {{-- If 'add' form is set to true, Form with key $addEditFormKey will be opened in modal popup  --}}
    <input type="hidden" class="addEditFormKey" id="addEditFormKey" value="{{ $addEditFormKey}}">
    {{-- If there is custom form for 'Add' or 'Edit', then this controller will be called. --}}
    {{-- TO DO : Change nage of 'multiSaveKey'  --}}
    <input type="hidden" class="multiSaveKey" id="multiSaveKey" value="<?php if(isset($settings['multiSaveKey'])){ echo $settings['multiSaveKey']; } else { echo $settings['customFormController']; } ?>">

    {{-- TO DO : Check if required --}}
    <div class="col-sm-6">
        <form method="get" action="" class="filter" id="">
            <input type="hidden" value=" @if(isset($settings['orderBy'])){{$settings['orderBy']}} @endif" name="filter_column_name" class="filter_column_name">
            <input type="hidden" value="@if(isset($settings['sortOrder'])){{$settings['sortOrder']}} @endif"  name="sorting_method" class="sorting_method">
            <input type="hidden" name="id" value="<?php if (isset($_GET['id']) && !empty($_GET['id'])) {
                echo $_GET['id'];
            } ?>">
        </form>
    </div>
    {{-- ------------------------------------------------------------------------ --}}


    <div class="right-container list-container" id="right-container">
        <div class="container main-body">
            <div class="row">
                <div class="col-md-12">
                    {{-- TO DO : Explain how to use --}}
                    @if (Session::has('message'))
                        <div class="alert alert-success alert-dismissible fade" role="alert">{{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    {{-- ----------------------------------------------------------- --}}
                </div>
            </div>

            {{-- Show search box.  --}}
            @if($settings['search'] == 1)
                <div class="row" style="padding-bottom: 10px;">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <h3 class="text-orange font-weight400 font-size25">{{ $settings['list_title'] }}</h3>
                            </div>
                            @include('Menus::layouts.search')
                        </div>
                    </div>
                </div>
            @endif

            {{-- TO DO : Check until line 126. What is the purpose?--}}
            @if(isset($settings['report']) && $settings['report'] == 1)
                <div class="row" style="padding-bottom: 10px;">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-10 text-center">
                                <h3 class="text-orange font-weight400 font-size25 ml-5 pl-4">{{ $settings['list_title'] }}</h3>
                            </div>
                            <!--<div class="col-md-"></div>-->
                            <div class="col-md-2">
                                @if($settings['filter_button'] == 1)
                                    <button type="button" class="btn-sm btn-sky-blue" data-toggle="collapse" data-target="#collapse">Customize</button>
                                @endif
                                @if($settings['add_button'] == 1)
                                    <button type="button" class="addButton btn-sm btn-sky-blue" data-toggle="modal" data-target="#modalAddEdit" id="" onclick="addModuleOpen('listing',null)">Add</button>
                                @endif
                                @if($settings['add_button'] == 2)
                                    @if (isset($_GET['service']))
                                        <button type="button" class="btn-sm btn-sky-blue" data-toggle="modal" data-target="#modalAddEdit" onclick="addCustomModuleOpen('{{$settings['multiSaveKey']}}', '{{ $addEditFormKey }}', null, '{{ $_GET['service'] }}')">Add</button>
                                    @else
                                        <button type="button" class="btn-sm btn-sky-blue" data-toggle="modal" data-target="#modalAddEdit" onclick="addCustomModuleOpen('{{$settings['multiSaveKey']}}', '{{ $addEditFormKey }}', null, '')">Add</button>
                                    @endif
                                @endif
                                @if($settings['export_button'] == 1)
                                    {{--<button class="btn-sm btn-outline-sky-blue export_buttons" type="submit">Export</button>--}}
                                    <a href="{{ url('export_to_excel') }}" class="btn-sm btn-outline-sky-blue export_buttons" type="submit">Export</a>
                                @endif
                                @if($settings['import_button'] == 1)
                                    <a data-toggle="modal" href="#modalImport" class="btn btn-sm btn-outline-sky-blue import_button">Import</a>
                                    <!-- Modal -->
                                    <div id="modalImport" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">General Master Import</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ url('import_to_excel') }}" method="POST" enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <div class="form-group">
                                                            <label for="import_file">Select File</label>
                                                            <input type="file" name="import_file" id="import_file" class="form-control border-sky-blue">
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-outline-sky-blue float-right" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sky-blue float-right">Import</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-------------------------------------------------------------------}}

            {{-- If filter is required on the list.--}}
            @if($settings['filter_button'] == 1)
            <!--<div class="container collapse border border-primary" id="collapse"> -->
                <div class="row collapse pl-3 pr-3 pb-1" id="collapse">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button class="btn-sm filter_buttons btn-filter-filter nav-link active" href="#filter" role="tab" data-toggle="tab">Filter</button>
                        </li>

                        {{-- If group by is required in the filter. --}}
                        @if($settings['group_button'] == 1)
                            <li class="nav-item">
                                <button class="btn-sm filter_buttons btn-filter-group nav-link" href="#group" role="tab" data-toggle="tab">Group</button>
                            </li>
                        @endif
                    </ul>
                    @include('Menus::layouts.filter')
                </div>
            @endif

            <div class="row1 row padding-top">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {{--
                            @ Mahesh Wani
                            @ Use in user dashboard listing side Top Button
                            @ 24/Sep/2020
                        --}}
                        @if(isset($top_buttons) && !empty($top_buttons))
                            <div class="mb-2">
                                @foreach($top_buttons as $btnKey => $btnVal)
                                <?php
                                    if(isset($_GET['view-type']))
                                    {
                                        $view_type = $_GET['view-type'];
                                    }else{
                                        $view_type = 'open';
                                    };
                                    $current_url = '/'.Request::path().'?view-type='.$view_type;
                                    //echo $btnKey.'==='.$current_url;
                                ?>
                                    <a class="mr-2 btn btn-sm @if($btnKey == $current_url){{'btn-sky-blue'}}@endif" style="font-size: 11px !important;background: #cacaca;" href="{{url($btnKey)}}">{{$btnVal}}</a>
                                @endforeach
                            </div>
                        @endif

                        <table class="table sort-table table-hover">
                            <thead>
                                <tr>
                                    {{-- TO DO: Check if 'data-filtermethod' is being used. --}}
                                    {{-- TO DO: Check sorting icon. It is currently not displaying. --}}
                                    {{--
                                        @ Sorting icon show error solve
                                        @ Mahesh Wani
                                        @ 11-Jun-2020
                                     --}}
                                    @foreach($headers as $key => $val)
                                        <th data-filterBy="{{ $key }}"
                                            data-sorting_url="@if(!is_array($paginationData) && !empty($paginationData->url(1))){{ $paginationData->url(1)}} @endif"
                                            data-filtermethod="@if(app('request')->input('filter_column_name') == $key && app('request')->input('sorting_method') == 'ASC'){{app('request')->input('sorting_method')}}@elseif(app('request')->input('sorting_method') == 'DESC'){{app('request')->input('sorting_method')}}@endif" class="header @if(app('request')->input('filter_column_name') == $key && app('request')->input('sorting_method') == 'ASC') headerSortDown @elseif (app('request')->input('filter_column_name') == $key && app('request')->input('sorting_method') == 'DESC') headerSortUp @endif">
                                            <span data-toggle="tooltip" data-placement="bottom" title="{{ $val }}">{{-- substr($val, 0,15) --}}{{$val}}</span>
                                            <span class="fa fa-sort-@if(app('request')->input('filter_column_name') == $key && app('request')->input('sorting_method') == 'asc'){{'asc'}}@elseif(app('request')->input('filter_column_name') == $key && app('request')->input('sorting_method') == 'desc'){{'desc'}}@endif"></span>
                                        </th>
                                    @endforeach

                                    {{-- Additional action column--}}
                                    @if($settings['action_header'] == 1)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                            @if(!empty($listingData['data']))
                                @foreach($listingData['data'] as $datakey)
                                    <tr class="table-tr">
                                         @foreach($headers as $headerkey => $headerval)
                                            <td class="" id="td_{{ $headerkey }}" style="@if(!empty($datakey[$headerkey . '_style'])) {{ $datakey[$headerkey . '_style'] }} @endif">
                                                <span data-toggle="tooltip" data-placement="bottom"
                                                      title = "@if(!empty($datakey[$headerkey . '_tool_tip'])) {{ $datakey[$headerkey . '_tool_tip'] }} @endif">
                                                      {{--<i class="fa fa-circle" aria-hidden="true" style="color: red;"></i>--}}
                                                      {{--<span class="fa @if(!empty($datakey[$headerkey . '_icon'])) {{ $datakey[$headerkey . '_icon'] }} @endif"
                                                        style="@if(!empty($datakey[$headerkey . '_icon_style'])) {{ $datakey[$headerkey . '_icon_style'] }} @endif"></span> --}}
                                                      {!! $datakey[$headerkey] !!}
                                                </span>
                                            </td>
                                        @endforeach

                                        {{-- Additional dynamic action buttons or urls --}}
                                        @if(!empty($settings['actions_buttons']) && count($settings['actions_buttons']) > 0)
                                            <td class="">
                                                @foreach($settings['actions_buttons'] as $actionsBtn)
                                                    @if($actionsBtn['type'] == 'button')
                                                        {{--<input type="button" class="btn btn-sm btn-primary" value="{{$actionsBtn['button-title']}}" @if(!empty($actionsBtn['trigger-action'])) {{$actionsBtn['button-trigger-action']}}="@if(!empty($actionsBtn['trigger-function'])){{$actionsBtn['trigger-function']}}@endif @endif">--}}
                                                        @if(!empty($actionsBtn['replace-with']))
                                                            @if(is_array($actionsBtn['replace-with']))
                                                                <?php $triggerArray = []; ?>
                                                                @foreach($actionsBtn['replace-with'] as $triggerKey => $triggerVal)
                                                                    <?php
                                                                        $triggerArray[$triggerVal] = $datakey[$triggerVal];
                                                                    ?>
                                                                @endforeach
                                                                <button type="button" class="btn btn-sm table-row-btn btnEdit" id="editButton" @if(!empty($actionsBtn['trigger-action'])) {{$actionsBtn['trigger-action']}}="@if(!empty($actionsBtn['trigger-function'])){{strtr($actionsBtn['trigger-function'], $triggerArray)}}@endif @endif" data-toggle="modal" data-target="#modalAddEdit"  @if(isset($actionsBtn['button-tool-tip']) && !empty(isset($actionsBtn['button-tool-tip'])))data-toggle="tooltip" data-html="true" title="{{$actionsBtn['button-tool-tip']}}"@endif>
                                                                    @if(isset($actionsBtn['btn-name']) && !empty($actionsBtn['btn-name']))
                                                                        {{$actionsBtn['btn-name']}}
                                                                    @else
                                                                        <span class="fa {{$actionsBtn['icon-class']}}"></span>
                                                                    @endif
                                                                </button>
                                                            @else
                                                                <button type="button" class="btnbtn-sm btn-sky-blue table-row-btn btnEdit" id="editButton" @if(!empty($actionsBtn['trigger-action'])) {{$actionsBtn['trigger-action']}}="@if(!empty($actionsBtn['trigger-function'])){{str_replace('{' . $actionsBtn['replace-with'] . '}', $datakey[$actionsBtn['replace-with']], $actionsBtn['trigger-function'])}}@endif @endif" data-toggle="modal" data-target="#modalAddEdit" @if(isset($actionsBtn['button-tool-tip']) && !empty(isset($actionsBtn['button-tool-tip'])))data-toggle="tooltip" data-html="true" title="{{$actionsBtn['button-tool-tip']}}"@endif>
                                                                    @if(isset($actionsBtn['btn-name']) && !empty($actionsBtn['btn-name']))
                                                                        {{$actionsBtn['btn-name']}}
                                                                    @else
                                                                        <span class="fa {{$actionsBtn['icon-class']}}"></span>
                                                                    @endif
                                                                </button>
                                                            @endif
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-sky-blue table-row-btn btnEdit" id="editButton" @if(!empty($actionsBtn['trigger-action'])) {{$actionsBtn['trigger-action']}}="@if(!empty($actionsBtn['trigger-function'])){{$actionsBtn['trigger-function']}}@endif @endif" data-toggle="modal" data-target="#modalAddEdit" @if(isset($actionsBtn['button-tool-tip']) && !empty(isset($actionsBtn['button-tool-tip'])))data-toggle="tooltip" data-html="true" title="{{$actionsBtn['button-tool-tip']}}"@endif>
                                                                @if(isset($actionsBtn['btn-name']) && !empty($actionsBtn['btn-name']))
                                                                        {{$actionsBtn['btn-name']}}
                                                                    @else
                                                                        <span class="fa {{$actionsBtn['icon-class']}}"></span>
                                                                    @endif
                                                            </button>
                                                        @endif
                                                    @elseif($actionsBtn['type'] == 'link')
                                                        @if(!empty($actionsBtn['replace-with']))
                                                            @if(is_array($actionsBtn['replace-with']))
                                                                <?php $triggerArray = []; ?>
                                                                @foreach($actionsBtn['replace-with'] as $triggerKey => $triggerVal)
                                                                    <?php
                                                                    $triggerArray[$triggerVal] = $datakey[$triggerVal];
                                                                    ?>
                                                                @endforeach
                                                                <a
                                                                    href="{{strtr($actionsBtn['url'], $triggerArray)}}" @if(isset($actionsBtn['link-tool-tip']) && !empty(isset($actionsBtn['link-tool-tip'])))data-toggle="tooltip" data-html="true" title="{{$actionsBtn['link-tool-tip']}}"  @else title="Accordion" @endif>
                                                                    @if(isset($actionsBtn['btn-name']) && !empty($actionsBtn['btn-name']))
                                                                        {{$actionsBtn['btn-name']}}
                                                                    @else
                                                                        <span class="fa {{$actionsBtn['icon-class']}}"></span>
                                                                    @endif
                                                                </a>
                                                             @else
                                                                <a @if(isset($actionsBtn['btn-name']) && !empty($actionsBtn['btn-name']))class="btn btn-sm btn-sky-blue table-row-btn"@endif style="margin: 3px;"
                                                                href="{{str_replace('{' . $actionsBtn['replace-with'] . '}', $datakey[$actionsBtn['replace-with']], $actionsBtn['url'])}}"   @if(isset($actionsBtn['link-tool-tip']) && !empty(isset($actionsBtn['link-tool-tip'])))data-toggle="tooltip" data-html="true" title="{{$actionsBtn['link-tool-tip']}}"  @else title="Accordion" @endif>
                                                                    @if(isset($actionsBtn['btn-name']) && !empty($actionsBtn['btn-name']))
                                                                        {{$actionsBtn['btn-name']}}
                                                                    @else
                                                                        <span class="fa {{$actionsBtn['icon-class']}}"></span>
                                                                    @endif
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="{{$actionsBtn['url']}}" @if(isset($actionsBtn['link-tool-tip']) && !empty(isset($actionsBtn['link-tool-tip'])))data-toggle="tooltip" data-html="true" title="{{$actionsBtn['link-tool-tip']}}"  @else title="Accordion" @endif>
                                                                @if(isset($actionsBtn['btn-name']) && !empty($actionsBtn['btn-name']))
                                                                        {{$actionsBtn['btn-name']}}
                                                                    @else
                                                                        <span class="fa {{$actionsBtn['icon-class']}}"></span>
                                                                    @endif
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </td>
                                        @else
                                            @if($settings['action_header'] == 1)
                                                <td class="">
                                                    {{-- edit button in the action column. Will call addEditFormKey--}}
                                                    @if($settings['action_edit_button'] == 1)
                                                        <button type="button" class="btn btn-sm table-row-btn btnEdit" id="editButton" onClick="editEntry( {{$datakey['id']}}, $(this))" data-toggle="modal" data-target="#modalAddEdit">
                                                            <span class="fa fa-edit"></span>
                                                        </button>
                                                    @endif

                                                    {{-- If custom form for edit. Will required addEditFormKey and customFormController set in settings. --}}
                                                    @if($settings['action_edit_button'] == 2)
                                                        <button type="button" class="btn btn-sm table-row-btn customBtnEdit" id="customEditButton"
                                                                onclick="editCustomModuleOpen('{{ $settings['multiSaveKey']}}', '{{ $addEditFormKey }}', '{{ $datakey['id'] }}', '@if(isset($_GET['service_type'])){{$_GET['service_type']}}@endif', '{{ $_GET['list_id'] }}')"
                                                                data-toggle="modal" data-target="#modalAddEdit">
                                                            <span class="fa fa-edit"></span>
                                                        </button>
                                                    @endif

                                                    {{-- Only used in form creation and field creation page. --}}
                                                    @if($settings['action_view_button'] == 1)
                                                        <button type="button" class="btn btn-sm table-row-btn btnEdit" id="viewButton">
                                                            <a href="{{ url($addEditFormKey.'/edit?id='.$datakey['id'])}}" title="Edit signature">
                                                                <span class="fa fa-info-circle"></span></a>
                                                        </button>
                                                    @endif


                                                    {{-- If accordion is used. accordio_url must be set in settings.
                                                        TO DO : encrypt url parameters
                                                    --}}
                                                    @if($settings['action_accordion_button'] == 1)
                                                        <button type="button" class="btn btn-sm table-row-btn btnEdit" id="accordionButton">
                                                            <?php
                                                            if (isset($_GET['service'])) { ?>
                                                            <a href="{{ url($settings['accordion_url'].'?list_id='.$datakey['id'].'&service_type='.$_GET['service'] )}}" title="Accordion">
                                                                <?php } else { ?>
                                                                <a href="{{ url($settings['accordion_url'].'?list_id='.$datakey['id'])}}" title="Accordion">
                                                                    <?php } ?>
                                                                    <span class="fa fa-info-circle"></span></a>
                                                            </a>
                                                        </button>
                                                    @endif
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            @else {{-- If list data is empty --}}
                                <?php
                                if ($settings['action_header'] == 1)
                                    $total_colspan = count($headers) + 1;
                                else
                                    $total_colspan = count($headers);
                                ?>
                                <tr class="table-tr text-center">
                                    <td colspan="<?php echo $total_colspan; ?>">
                                        @if(isset($settings['footer']) && !empty($settings['footer']))
                                            <b>{{$settings['footer']}}</b>
                                        @else
                                            <b>No Record Found</b>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    @include('Menus::layouts.pagination')
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // console.log('reloading file');
    $.getScript("/js/common.js", function(data, textStatus, jqxhr) {});
</script>

{{-- TO DO: check commit history of file. What is the purpsoe of this code?--}}
<style>
    tr.table-tr td span {
        white-space: nowrap !important;
        vertical-align: bottom !important;
    }
    .header{
        cursor: pointer;
    }
</style>
