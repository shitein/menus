@extends('layouts.app')
@section('content')

<div class="right-container" id="right-container">
    <div class="container">
        @if(isset($_GET['menu_name']))
        <input type="hidden" id="active_menu" value="{{$_GET['menu_name']}}">
        @endif
        <div class="row1 padding-top">
            <div class="col-md-12">
                <div class="container">
                    <div class="">
                        <div class="col-md-12">
                            @foreach($menusMasterList as $masterDetail)
                            <a class="text-decoration-none" href="collab_menus?menu_name={{ $masterDetail->id }}">
                                <span><kbd class="
                                             @if(isset($_GET['menu_name']) && $_GET['menu_name'] == $masterDetail->id)
                                                active
                                             @else
                                                inactive
                                            @endif
                                                ">{{ $masterDetail->description }}</kbd></span>
                            </a>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <span style="font-size: x-large">Menu List</span><br>
                <span>
                    <a class="text-decoration-none" href="#"><kbd class="btn-primary" data-toggle="modal" data-target="#addMenuModal">Create New</kbd></a>
                </span>
                <span>
                    @if(isset($_GET['menu_name']))
                    <a class="text-decoration-none menu-access-control" href="#"><kbd class="btn-primary">Access Control</kbd></a>
                    @endif
                </span>
                <ul id="parent" style="list-style: none; padding: 0px;">
                    @foreach($menuList as $menu)
                    <li class="parent" style="padding: 4px 4px !important; background: #f6f6f6; border: 1px solid #dbdbdb; border-radius: 4px; margin: 3px 0px;">{{ App\Common\Common::get_translation($menu->language_key,$menu->description) }}
                        <input type="button" data-menuid="{{ $menu->id }}" class="addToMenuList btn btn-xs btn-primary" value="ADD" style="float: right;font-size: 10px;margin: -4px;">
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4 pr-0">
                <span style="font-size: x-large">Menu Mapping</span>
                <div class="dd nestable" id="nestable">
                    <ol class="dd-list">
                        {!! $stringMapping !!}
                    </ol>
                </div>
            </div>
            <div class="col-md-5 pl-0">
                <textarea id="nestable-output" style="display: none;"></textarea>
                <div class="access-control" id="access-control"></div>
            </div>
        </div>
    </div>

</div>

{{-- Add New Menu --}}
<div class="modal fade" id="addMenuModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <!--<i class="fa fa-close pull-right" style="position: absolute; right: 5px; top: 11px; font-size: 18px;"></i> -->
                <h4 class="modal-title">Add Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -45px !important;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/collab_menus') }}" class="filter">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Short Code</label>
                                <input class="form-control" placeholder="Name" type="text" Name="short_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <input class="form-control" placeholder="Description" type="text" Name="description" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">URL</label>
                                <input class="form-control" placeholder="URL" type="text" Name="url" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Class</label>
                                <input class="form-control" placeholder="Class" type="text" Name="class">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Font Icon Class</label>
                                <input class="form-control" placeholder="Class" type="text" Name="font_icon_class">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Language Key</label>
                                <input class="form-control" placeholder="Class" type="text" Name="language_key">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="submit" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Menu --}}
<div class="modal fade" id="editMenuModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <!--          <i class="fa fa-close pull-right" style="position: absolute; right: 5px; top: 11px; font-size: 18px;"></i> -->
                <h4 class="modal-title">Edit <span id="menu-name"></span> Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -45px !important;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/collab_menus/update') }}" class="filter">
                <input type="hidden" id="old_id" name="old_id" value="">
                <input type="hidden" id="menu_url" name="menu_url" value="">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Short Code</label>
                                <input class="form-control" placeholder="Name" type="text" id="short_code" Name="short_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <input class="form-control" placeholder="Description" id="description" type="text" Name="description" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">URL</label>
                                <input class="form-control" placeholder="URL" id="url" type="text" Name="url" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Class</label>
                                <input class="form-control" placeholder="Class" id="class" type="text" Name="class">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Font Icon Class</label>
                                <input class="form-control" placeholder="Class" type="text" Name="font_icon_class" id="font_icon_class">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Language Key</label>
                                <input class="form-control" placeholder="Class" type="text" Name="language_key" id="language_key">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
