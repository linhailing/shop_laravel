@extends('admin.base')

@section('content')
<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <h2>清空缓存</h2>
    </div>
    <div class="layui-card-body">
        <form action="{{route('admin.cache') }}" method="post" class="layui-form">
            {{csrf_field()}}
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit="">清空缓存</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
