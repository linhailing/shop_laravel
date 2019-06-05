@extends('admin.base')

@section('content')
<div class="layui-card">
    <div class="layui-card-header  layuiadmin-card-header-auto">
        <h2>修改用户密码</h2>
    </div>
    <div class="layui-card-body">
        <form class="layui-form layui-form-pane" action="{{route('admin.user.password',['uid'=>$uid])}}" method="post">
            {{csrf_field()}}
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
                <label for="" class="layui-form-label">密&nbsp;&nbsp;&nbsp;码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" value="" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">确认密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="password_confirmation" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
<!--                    <a class="layui-btn" href="{{route('admin.user.list')}}" >返 回</a>-->
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
