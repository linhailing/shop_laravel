@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新权限</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.permission.store',['id'=>$id])}}" method="post">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">父级</label>
                    <div class="layui-input-block">
                        <select name="parent_id" lay-search>
                            <option value="0">顶级权限</option>
                            @forelse($permissions as $perm)
                                <option value="{{$perm['id']}}" {{ isset($info->id) && $perm['id'] == $info->parent_id ? 'selected' : '' }} >{{$perm['display_name']}}</option>
                                @if(isset($perm['_child']))
                                    @foreach($perm['_child'] as $childs)
                                        <option value="{{$childs['id']}}" {{ isset($info->id) && $childs['id'] == $info->parent_id ? 'selected' : '' }} >&nbsp;&nbsp;┗━━{{$childs['display_name']}}</option>
                                        @if(isset($childs['_child']))
                                            @foreach($childs['_child'] as $lastChilds)
                                                <option value="{{$lastChilds['id']}}" {{ isset($info->id) && $lastChilds['id'] == $info->parent_id ? 'selected' : '' }} >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{$lastChilds['display_name']}}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="{{$info->name??old('name')}}" lay-verify="required" class="layui-input" placeholder="如：system.index">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">显示名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="display_name" value="{{$info->display_name??old('display_name')}}" lay-verify="required" class="layui-input" placeholder="如：系统管理">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">路由</label>
                    <div class="layui-input-block">
                        <input class="layui-input" type="text" name="route" value="{{$info->route??old('route')}}" placeholder="如：admin.member" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">图标</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" type="hidden" name="icon_id" >
                    </div>
                    <div class="layui-form-mid layui-word-aux" id="icon_box">
                        <i class="layui-icon {{$info->class??''}}"></i> {{$info->cname??''}}
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <button type="button" class="layui-btn layui-btn-xs" onclick="showIconsBox()">选择图标</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" >确 认</button>
                        <a href="{{route('admin.permission.list')}}" class="layui-btn"  >返 回</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.permission._js')
@endsection