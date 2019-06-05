@extends('admin.base')

@section('content')
<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <div class="layui-btn-group">
            <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">清空操作日志</button>
        </div>
    </div>
    <div class="layui-card-body">
        <div class="test-table-reload-btn" style="margin-bottom: 10px;">
            搜索：
            <div class="layui-inline">
                <input class="layui-input" name="id" id="test-table-demoReload" placeholder="搜索公司名称，统一码，法人" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload">搜索</button>
        </div>
        <table id="dataTable" lay-filter="dataTable"></table>
    </div>
</div>
@endsection

@section('script')
<script>
    layui.use(['layer', 'table', 'form'], function () {
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        //用户表格初始化
        var dataTable = table.render({
            elem: '#dataTable'
            , height: 500
            , url: "{{ route('admin.cache.data') }}" //数据接口
            , where: {model: "logs"}
            , page: true //开启分页
            , cols: [[ //表头
                {field: 'id', title: 'ID', sort: true, width: 80}
                , {field: 'username', title: '操作用户'}
                , {field: 'path', title: '路径'}
                , {field: 'method', title: '请求方法'}
                , {field: 'ip', title: 'IP'}
                , {field: 'params', title: '操作说明'}
                , {field: 'create_time', title: '操作日期'}
            ]]
        });
        var $ = layui.$, active = {
            reload: function(){
                var demoReload = $('#test-table-demoReload');
                //执行重载
                table.reload('dataTable', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        q:demoReload.val()
                    }
                });
            }
        };
        $('.test-table-reload-btn .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //按钮批量删除
        $("#listDelete").click(function () {
            layer.confirm('确认删除吗？', function (index) {
                $.post("{{ route('admin.cache.logs.del') }}", {_method: 'delete'}, function (result) {
                    if (result.code == 0) {
                        dataTable.reload()
                    }
                    layer.close(index);
                    layer.msg(result.msg, {icon: 6})
                });
            })
        })
    })
</script>
@endsection
