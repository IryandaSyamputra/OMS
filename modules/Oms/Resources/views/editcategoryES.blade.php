@extends('oms::layouts.dashboardlayout')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Category
        <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('oms.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Edit Category</li>
    </ol>
</section>

@if(count($categoryArray)>0)

{{--*/ $category = $categoryArray[0] /*--}}
{{--*/ $categoryES = $category->category_es /*--}}
{{--*/ $categoryMarketplace = $category->category_marketplace_desc /*--}}
{{--*/ $categoryMarketplaceID = $category->category_marketplace_id /*--}}
{{--*/ $categoryMappingID = $category->category_mapping_id /*--}}

@else
{{--*/ $categoryES = ''; /*--}}
{{--*/ $categoryMarketplace = '' /*--}}
{{--*/ $categoryMarketplaceID = '' /*--}}
{{--*/ $categoryMappingID = '' /*--}}
@endif

<!-- Main content -->
<section class="content">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{$categoryES}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <form id='form' name="form" action="{{route('oms.saveCategory')}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{{ csrf_token() }}}" />
            <input type='hidden' name='categoryMappingID' value='{{$categoryMappingID}}'>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">              
                        <label>Category</label>
                        <div class="input-group input-group-sm"> 
                            {!! Form::text('category', $categoryES , ['id' => 'category','class'=>'form-control add-top-margin', 'placeholder'=>'category'] ) !!}
                            <span class="input-group-btn">
                                <button type='button' class="btn btn-info btn-flat" data-toggle="modal" href="#ESModal">Find</button>
                            </span>
                        </div>
                        
                    </div>
                    
                    <div class="form-group">              
                        <label>Market Place</label>

                        <select class="form-control" id="marketPlace" name='marketPlace'>
                            @foreach($marketPlaceArray as $marketPlaceArrayDetail)                        
                            <option value="{{$marketPlaceArrayDetail->marketplace_id}}">{{$marketPlaceArrayDetail->marketplace_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">              
                        <label>Mapping</label>
                        <div class="input-group input-group-sm">
                            {!! Form::text('categoryMarketplace', $categoryMarketplace , ['id' => 'categoryMarketplace','class'=>'form-control add-top-margin', 'readonly'=>'readonly'] ) !!}
                            {!! Form::hidden('categoryMarketplaceID', $categoryMarketplaceID , ['id' => 'categoryMarketplaceID','class'=>'form-control add-top-margin', 'placeholder'=>'category', 'readonly'=>'readonly'] ) !!}
                            <span class="input-group-btn">
                                <button type='button' class="btn btn-info btn-flat" data-toggle="modal" href="#MPModal">Find</button>
                            </span>
                        </div>
                        <!--<button type='button' class="btn btn-default" onclick="callPopUp('{{route('oms.listcategory')}}')">Find</button>-->
                    </div>
                    
                    
                </div>
                

                <div class="col-md-6">
                    
                </div>
                
                <!-- /.col -->
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Post & save</button>
            </div>
            <!-- /.row -->
            </form>
        </div>

    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<script>
$(document).on('ready', function(){

    $('#ESModal').on('shown.bs.modal', function () {
    $(this).find('.modal-dialog').css({width:'auto',
            height:'auto',
            'max-width':'80%',
            'max-height':'80%',
    });
        urlAPI = "";
        requestData = {
            'limit' : '1000',
            'page' : '0'
            }
    $.ajax({
    headers: {
    'X-CSRF-TOKEN': $('#token').val(),
    },
            data: requestData,
            url: urlAPI,
            type: 'post',
            beforeSend: function() {
                $("#reloadDiv").show();
            },
            complete: function() {
                $("#reloadDiv").hide();
            },
            error : function(){
              alert('Time Out, please try again');  
            },
            success: function(data) {
            
            }
        });
    });
    
    $('#MPModal').on('shown.bs.modal', function () {
    $(this).find('.modal-dialog').css({width:'auto',
            height:'auto',
            'max-width':'80%',
            'max-height':'80%',
    });
        urlAPI = "{!! route('oms.getAllMPCategoryModals') !!}";
        requestData = {
            'marketPlace' : $("#marketPlace").val(),
            }
    $.ajax({
    headers: {
    'X-CSRF-TOKEN': $('#token').val(),
    },
            data: requestData,
            url: urlAPI,
            type: 'post',
            beforeSend: function() {
                $("#reloadDiv").show();
            },
            complete: function() {
                $("#reloadDiv").hide();
            },
            error : function(){
              alert('Time Out, please try again');  
            },
            success: function(data) {
                
            var dataResults = JSON.parse(data);
            var result = dataResults.results;
                    var table = '';
                    table += '<table id="MPProduct" class="table table-bordered table-striped">' +
                    '<thead>' +
                    '<tr>' +
                    '<th width="20%">Code</th>'+
                    '<th width="40%">Category</th>' +
                    '<th width="10%">Level</th>' +
                    '<th width="10%">Action</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                    for (var index in result){
            table += '<tr>' +
                    '<td>' + result[index]['code'] + '</td>' +
                    '<td>' + result[index]['category'] + '</td>' +
                    '<td>' + result[index]['level'] + '</td>' +
                    '<td>' +
                    '<button \n\
                    data-category="' + result[index]['category'] + '" \n\
                    data-category-id="' + result[index]['id'] + '" \n\
                    class="btn btn-mini mp-color-btn">Pick</button>' +
                    '</td>' +
                    '</tr>';
            }
            table += '</tbody>' +
                    '<tfoot>' +
                    '<tr>' +
                    '<th width="20%">Code</th>'+
                    '<th width="40%">Category</th>' +
                    '<th width="10%">Level</th>' +
                    '<th width="10%">Action</th>' +
                    '</tr>' +
                    '</tfoot>' +
                    '</table>';
                    $("#MPTableDiv").html(table);
                    $("#MPProduct").DataTable();
                    
            $(".mp-color-btn").on('click', function(){
                    $("#categoryMarketplace").val($(this).data('category'));
                    $("#categoryMarketplaceID").val($(this).data('category-id'));
                    $("#MPModal").modal('toggle')
            });
            
            }
        });
    });
    
}); 
 </script>
 
<script>
    function callPopUp(menu){
        var marketPlace = $("#marketPlace").val();
        var menuPage = menu + '/' + marketPlace;
        showPopup(menuPage, '850', '700');
        return false;
    }
</script>

<!-- Modal -->
<div class="modal fade" id="ESModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">ES Category</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id='esTableDiv' align="center">
                    <img src="{{URL::asset('images/reload.gif')}}" id="reloadDiv">
                    <!-- Modals Content ES Brand -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal -->
<div class="modal fade" id="MPModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">MarketPlace Category</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id='MPTableDiv' align="center">
                    <img src="{{URL::asset('images/reload.gif')}}" id="reloadDiv">
                    <!-- Modals Content MP Brand -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop
