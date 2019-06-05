@extends('admin.base')

@section('content')
<div class="layui-card">
    <div class="layui-card-header  layuiadmin-card-header-auto">
        <h2>用户基本信息</h2>
    </div>
    <div class="layui-card-body">
        <form class="layui-form layui-form-pane" action="" method="post">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">昵&nbsp;&nbsp;&nbsp;称</label>
                <div class="layui-input-block">
                    <input type="text" name="username" value="{{$info->username?? old('username')}}" class="layui-input" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">邮&nbsp;&nbsp;&nbsp;箱</label>
                <div class="layui-input-block">
                    <input type="email" name="email" value="{{$info->email??old('email')}}" class="layui-input" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">用户名</label>
                <div class="layui-input-block">
                    <input type="text" name="name" value="{{$info->name??old('name')}}"  class="layui-input" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">手机号</label>
                <div class="layui-input-block">
                    <input type="text" name="tel" value="{{$info->tel??old('tel')}}" class="layui-input" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">创建时间</label>
                <div class="layui-input-block">
                    <input type="text" name="created_at" value="{{$info->created_at??old('created_at')}}" class="layui-input" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">更新时间</label>
                <div class="layui-input-block">
                    <input type="text" name="updated_at" value="{{$info->updated_at??old('updated_at')}}" class="layui-input" disabled>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
