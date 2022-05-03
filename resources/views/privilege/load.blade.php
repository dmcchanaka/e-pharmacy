
<div class="card">
    <div class="card-body">
    @if(isset($permission))
        @if(isset($permission) && sizeof($permission) > 0)
        <div class="table-responsive">
        <table class="table table-striped table-bordered first" id="user_permission_table">
            <thead class="thead-custom">
                <tr>
                    <th style="text-align: center">Permission Group</th>
                    <th style="text-align: center">User Type</th>
                    <th style="text-align: center">View</th>
                    <th style="text-align: center">Edit</th>
                    <th style="text-align: center">Delete</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($permission as $item)
                <tr>
                <td>{{$item->per_gp_name}}</td>
                <td>{{$item->user_type}}</td>
                <td style="text-align: center">
                    <span class="pull-right-container">
                        <a href="{{url('display_privilege/'.$item->per_gp_id)}}" target="_blank"><i class="fa fa-list-alt fa-lg"></i></a>
                    </span>
                </td>
                <td style="text-align: center;cursor: pointer">
                    <span class="pull-right-container">
                    @if ($permission = \App\Models\User::checkUserPermission(Auth::user()->per_gp_id,Auth::user()->u_tp_id,"PRIVILEGE EDIT") == 1)
                        <a href="{{url('edit_privilege/'.$item->per_gp_id)}}" target="_blank"><i class="fa fa-pencil fa-lg"></i></a>
                    @endif
                    </span>
                </td>
                <td style="text-align: center;cursor: pointer">
                    <span class="pull-right-container">
                    @if ($permission = \App\Models\User::checkUserPermission(Auth::user()->per_gp_id,Auth::user()->u_tp_id,"PRIVILEGE DELETE") == 1)
                        <a href="{{url('delete_privilege/'.$item->per_gp_id)}}" data-method="delete"><i class="fa fa-trash-alt"  style="color:red"></i></a>
                    @endif
                    </span>
                </td>
                </tr>
            @endforeach
            </tbody>
        </div>
        @else 
        <div style="text-align:center"><label class="col-form-label" style="text-align:center;color:red">No Record Found</label></div>
        @endif
    @endif
    </div>
</div>