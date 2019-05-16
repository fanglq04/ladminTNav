@if(!empty($role))
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">名称</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" id="name" name="name" value="{{ $role->name }}" class="form-control" placeholder="请输入名称">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="slug" class="col-sm-2 control-label">标识</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" id="slug" name="slug" value="{{ $role->slug }}" class="form-control" placeholder="请输入标识">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="permission_id" class="col-md-2 control-label">选择权限</label>
        <div class="col-sm-8">
            <table class="table">
                @forelse($permissions as $top)
                    @if(!empty($top['child']))
                        <tr><th colspan="{{count($top['child'])}}">{{$top['name']}}</th></tr>
                        <tr>
                            <td>
                                @foreach($top['child'] as $sub)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="permission_id[]" value="{{ $sub['id'] }}" {{ in_array($sub['id'],$role_permissions) ? "checked":'' }}> {{ $sub['name'] }}
                                    </label>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @empty
                @endforelse

                @foreach($permissions_top as $top1)
                    @if(!empty($top1['child']))
                        <tr><th colspan="{{count($top1['child'])}}">{{$top1['name']}}</th></tr>
                        <tr>
                            <td>
                                @foreach($top1['child'] as $sub1)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="permission_id[]" value="{{ $sub1['id'] }}" {{ in_array($sub1['id'],$role_permissions) ? "checked":'' }}> {{ $sub1['name'] }}
                                    </label>
                                @endforeach
                            </td>
                        </tr>
                    @else
                        <tr><th>{{$top1['name']}}</th></tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="permission_id[]" value="{{ $top1['id'] }}" {{ in_array($top1['id'],$role_permissions) ? "checked":'' }}> {{ $top1['name'] }}
                                </label>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">简介</label>
        <div class="col-sm-8">
            <textarea name="description" id="description" rows="10" class="form-control" placeholder="请输入简介">{{$role->description}}</textarea>
        </div>
    </div>
    @else
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">名称</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" id="name" name="name" class="form-control" placeholder="请输入名称">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="slug" class="col-sm-2 control-label">标识</label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="请输入标识">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="permission_id" class="col-md-2 control-label">选择权限</label>
        <div class="col-sm-8">
            <table class="table">
                @forelse($permissions as $top)
                    @if(!empty($top['child']))
                        <tr><th colspan="{{count($top['child'])}}">{{$top['name']}}</th></tr>
                        <tr>
                            <td>
                            @foreach($top['child'] as $sub)
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="permission_id[]" value="{{ $sub['id'] }}"> {{ $sub['name'] }}
                                </label>
                            @endforeach
                            </td>
                        </tr>
                    @endif
                @empty
                @endforelse

                @foreach($permissions_top as $top1)
                    @if(!empty($top1['child']))
                        <tr><th colspan="{{count($top1['child'])}}">{{$top1['name']}}</th></tr>
                        <tr>
                            <td>
                            @foreach($top1['child'] as $sub1)
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="permission_id[]" value="{{ $sub1['id'] }}"> {{ $sub1['name'] }}
                                </label>
                            @endforeach
                            </td>
                        </tr>
                    @else
                        <tr><th>{{$top1['name']}}</th></tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="permission_id[]" value="{{ $top1['id'] }}"> {{ $top1['name'] }}
                                </label>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">简介</label>
        <div class="col-sm-8">
            <textarea name="description" id="description" rows="10" class="form-control" placeholder="请输入简介"></textarea>
        </div>
    </div>
@endif

@push('js')
    <!--
<script>
        $('.permission-checkbox').bind('click',function () {
            var index = $(this).attr('data-index');
            var topIndex = $(this).attr('data-parent-index');
            if ( topIndex )
            {
                $.each( $('.top-permission'),function (key,val) {
                    if ( $(val).attr('data-index') == topIndex )
                    {
                        $(this).attr('checked','checked');
                    }
                });
            }
            else
            {
                $.each( $('.sub-permission'),function (key,val) {
                    if ( $(val).attr('data-parent-index') == index )
                    {
                        if ($(val).attr('checked'))
                        {
                            $(val).removeAttr('checked');
                        }
                        else{
                            $(val).attr('checked','checked');
                        }
                    }
                });
            }
        });
    </script>
-->
@endpush