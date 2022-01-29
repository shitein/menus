<form method="post" action="{{ url('/save-user-access-role') }}" class="" id="addForm">
    @csrf
    <input type="hidden" name='parentmenu' value="{{isset($parentmenu)?$parentmenu:''}}">
    <table class="mt-2" style="font-size: 12px; width: 100%;">
        <thead>
            <tr>
                <td width="15%"><span><input type="checkbox" class="view_all" id="viewmenu"> <label for='viewmenu'>View</label></span></td>
                <td width="15%"><span class="pl-2"><input type="checkbox" class="add_all" id='addmenu'> <label for='addmenu'>Add</span></td>
                <td width="15%"><span class="pl-2"><input type="checkbox" class="edit_all" id='editmenu'> <label for='editmenu'>Edit</span></span></td>
                <td width="55%">
                    <div class="row">
                        <div class="col-md-3">
                            <span>Company: </span>
                        </div>
                        <div class="col-md-9">
                            <select class="change-company-type chosen-select" style="padding: 3px;width:72%" name="access_role" required>
                                @php
                                $listData = App\Common\Common::dropDownList('COMP_ALL', 'all');
                                @endphp
                                @if ($listData)
                                @foreach ($listData as $listKey)
                                @php
                                if ($listKey['value'] == 1) {
                                $selected = 'selected';
                                } else {
                                $selected = '';
                                } @endphp
                                <option value="{{ $listKey['value'] }}" {{$selected}}>{{ $listKey['label'] }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="15%"></td>
                <td width="15%"></td>
                <td width="15%"></td>
                <td width="55%">
                    <div class="row">
                        <div class="col-md-3">
                            <span>Role : </span>
                        </div>
                        <div class="col-md-9">
                            <select class="change-accessrole-type chosen-select" style="padding: 3px;" name="access_role" required>
                                @foreach ($rolesList as $role)
                                @if($role->company_id=='999')
                                <option value="{{$role->id}}">Buyer - {{$role->role}}</option>
                                @else
                                <option value="{{$role->id}}">{{$role->role}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </td>

                <!-- <td class="p-2 w-50" id="choose-access-type"></td> -->
            </tr>
        </thead>
    </table>
    <div class="container access-menu"> {!! $stringMapping !!}</div>
</form>
<script>
    $.getScript("/js/common.js", function(data, textStatus, jqxhr) {});
    $(document).ready(function() {
        $("body").on('click', '.view_all', function() {
            if ($(this).is(":checked")) {
                $(".viewmenu").prop('checked', true);
            } else if ($(this).is(":not(:checked)")) {
                $(".viewmenu").prop('checked', false);
            }
        });
        $('body').on('click', '.viewmenu', function() {
            if ($(this).is(":not(:checked)")) {
                $(".view_all").prop('checked', false);
            }
        });

        $("body").on('click', '.add_all', function() {
            if ($(this).is(":checked")) {
                $(".addmenu").prop('checked', true);
            } else if ($(this).is(":not(:checked)")) {
                $(".addmenu").prop('checked', false);
            }
        });
        $('body').on('click', '.addmenu', function() {
            if ($(this).is(":not(:checked)")) {
                $(".add_all").prop('checked', false);
            }
        });

        $("body").on('click', '.edit_all', function() {
            if ($(this).is(":checked")) {
                $(".editmenu").prop('checked', true);
            } else if ($(this).is(":not(:checked)")) {
                $(".editmenu").prop('checked', false);
            }
        });
        $('body').on('click', '.editmenu', function() {
            if ($(this).is(":not(:checked)")) {
                $(".edit_all").prop('checked', false);
            }
        });
        $('.change-accessrole-type').on('change', function() {
            var role = $(this).val();
            var menu = $('#menu_name').val();
            if (role.length !== 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: APP_URL + '/usermenuAccessTypes',
                    data: {
                        'role': role,
                        'menu_name': menu
                    },
                    success: function(data) {
                        var parsed = JSON.parse(data);
                        $(".view_all").prop('checked', false);
                        $(".add_all").prop('checked', false);
                        $(".edit_all").prop('checked', false);
                        $(".access-menu").html(parsed.menulist);
                    }
                });
            }
        });

        $(".change-company-type").on('change', function() {
            var company = $(this).val();
            var menu = $('#menu_name').val();
            if (company.length !== 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: APP_URL + '/usermenuAccessTypes',
                    data: {
                        'company': company,
                        'menu_name': menu
                    },
                    success: function(data) {
                        var parsed = JSON.parse(data);
                        $(".view_all").prop('checked', false);
                        $(".add_all").prop('checked', false);
                        $(".edit_all").prop('checked', false);
                        $(".change-accessrole-type").html(parsed.roles);
                        $(".change-accessrole-type").trigger("chosen:updated");
                        $(".access-menu").html(parsed.menulist);
                    }
                });
            }
        });

    });
</script>
<style>
    .chosen-container {
        padding: 0px !important;
        height: 25px !important;
    }
</style>
