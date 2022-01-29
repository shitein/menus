<div class="col-sm-6">
    <!-- <form method="get" action="" class="filter" id="search-action"> -->
        <div class="input-group">
            <button class="input-group-text br-rad-tp-r-0 br-rad-b-r-0 border-right-0" id="allSearch" type="submit" tabindex="3">All</button>
            <input type="text" class="form-control" id="search-box" name="basics" value="@if(isset($_GET['basics'])){{ $_GET['basics'] }}@endif" placeholder="Search for..." tabindex="1">
            @if(isset($_GET['service']))
                <input type="hidden" name="service" id="service-type" value="{{ $_GET['service'] }}">
            @endif

            {{-- User in error log search --}}
            <input type="hidden" name="error_date" id="error_date" value="@if(isset($_GET['date'])){{$_GET['date']}}@endif">


            <button class="input-group-text br-rad-tp-l-0 br-rad-b-l-0 border-left-0"
                    type="button" id="search_record" tabindex="2">Go!</button>
        </div>
    <!-- </form> -->
</div>
<div class="col-sm-3 pl-0">
    @if($settings['filter_button'] == 1)
        <span class="group">
            @if(isset($settings['filter_btn']) && !empty($settings['filter_btn']))
                <button type="button" class="btn-sm btn-sky-blue" data-toggle="collapse" data-target="#collapse" style="margin-right: -17px;">
                    {{$settings['filter_btn']}}
                </button>
            @else
                <button type="button" class="btn-sm btn-sky-blue" data-toggle="collapse" data-target="#collapse" style="margin-right: -17px;">
                    Customize
                </button>
            @endif
            <span class="customise_bage">@if(isset($settings['settings']['no_of_filter_set'])){{$settings['settings']['no_of_filter_set']}}@else{{0}}@endif</span>
        </span>
    @endif
    @if($settings['add_button'] == 1)
        <button type="button" class="addButton btn-sm btn-sky-blue" data-toggle="modal" data-target="#modalAddEdit" id="" onclick="addModuleOpen('listing',null)">Add</button>
    @endif
    @if($settings['add_button'] == 2)
            <?php
            if(isset($settings['multiSaveKey'])){
                $customForm = $settings['multiSaveKey'];
            }else{
                $customForm = $settings['customFormController'];
            }
            ?>
        @if (isset($_GET['service']))
            <button type="button" class="btn-sm btn-sky-blue" data-toggle="modal" data-target="#modalAddEdit" onclick="addCustomModuleOpen('{{ $customForm }}', '{{ $addEditFormKey }}', null, '{{ $_GET['service'] }}')">Add</button>
        @else
            <button type="button" class="btn-sm btn-sky-blue" data-toggle="modal" data-target="#modalAddEdit" onclick="addCustomModuleOpen('{{ $customForm }}', '{{ $addEditFormKey }}', null, '')">Add</button>
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
<script>
    $(document).ready(function(){
        $(".chosen-container").addClass("border-sky-blue");

        /**/
        $('#search_record').click(function () {
            $('.loading').show();
            var search_string   = $('#search-box').val();
            var service_type    = $('#service-type').val();
            var url             = $(location).attr('href').split("?");
            var error_date      = $('#error_date').val();

            console.log(search_string)
            $('#search_string').val(search_string);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: url[0],
                data: { 'basics': search_string, service: service_type, 'date' : error_date },
                success: function(ret_data) {

                    $('.accordion-list').html(ret_data);

                    var config = {
                        '.chosen-select': {},
                        '.chosen-select-deselect': { allow_single_deselect: true },
                        '.chosen-select-no-single': { disable_search_threshold: 10 },
                        '.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
                    }

                    for (var selector in config) {
                        $(selector).chosen(config[selector]);
                    }

                    $(".chosen-select").chosen().change(function () {
                        $(this).val()
                    });

                    /*-- Set date after Filter --*/
                    $(function () {
                        try {
                            var start = moment().subtract(29, 'days');
                            var end = moment();

                            function cb(start, end) {

                                $('.reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                            }

                            $('.reportrange').daterangepicker({
                                startDate: start,
                                endDate: end,
                                ranges: {
                                    'Today': [moment(), moment()],
                                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                                }
                            }, cb);

                            cb(start, end);
                        } catch (err) {
                            for (var prop in err) {
                                //console.log("property: " + prop + " value: [" + err[prop] + "]\n");
                            }
                        }

                        var start = moment().subtract(29, 'days');
                        var end   = moment();

                        var from_date_parts     = $('#filter_from_date').val().split('-');
                        var format_from_date    = moment(from_date_parts[2] + '.' + from_date_parts[1] + '.' + from_date_parts[0], "DD.MM.YYYY").format("MMMM D, YYYY");

                        var to_date_parts   = $('#filter_to_date').val().split('-');
                        var format_to_date  = moment(to_date_parts[2] + '.' + to_date_parts[1] + '.' + to_date_parts[0], "DD.MM.YYYY").format("MMMM D, YYYY");

                        var from_date   = format_from_date != 'Invalid date' ? format_from_date : start.format('MMMM D, YYYY');
                        var to_date     = format_to_date != 'Invalid date' ? format_to_date : end.format('MMMM D, YYYY');

                        $('.reportrange span').html(from_date + ' - ' + to_date);
                    });
                    $('.loading').hide();
                }
            });
        });
    });
</script>
