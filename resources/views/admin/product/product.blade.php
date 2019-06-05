@extends('admin.base')

@section('content')
<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <div class="layui-btn-group">
            <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>
            <a class="layui-btn layui-btn-sm" href="{{ route('admin.product.store') }}">添 加</a>
        </div>
    </div>
    <div class="layui-card-body">
        <div class="test-table-reload-btn" style="margin-bottom: 10px;">
            搜索：
            <div class="layui-inline">
                <input class="layui-input" name="id" id="test-table-demoReload" placeholder="搜索昵称，电话号码" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload">搜索</button>
        </div>
        <table id="dataTable" lay-filter="dataTable"></table>
        <script type="text/html" id="options">
            <div class="layui-btn-group">
                <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
            </div>
        </script>
    </div>
</div>
@endsection

@section('script')
<script>
    var pageCur = 1
    layui.use(['layer', 'table', 'form','laypage'], function () {
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var laypage = layui.laypage
        //用户表格初始化
        var dataTable = table.render({
            elem: '#dataTable'
            , height: 500
            , url: "{{ route('admin.product.data') }}" //数据接口
            , where: {model: "product"}
            , page: true //开启分页,
            , cols: [[ //表头
                {checkbox: true, fixed: true}
                , {field: 'id', title: 'ID', sort: true, width: 80}
                , {field: 'product_name', title: '商品名称'}
                , {field: 'price', title: '商品价格'}
                , {field: 'icon', title: '商品图片'}
                , {field: 'stock', title: '商品库存'}
                , {fixed: 'right', width: 120, align: 'center', toolbar: '#options'}
            ]],
            done:(res,cur, count)=>{
                pageCur = cur
            }
        });
        //执行一个laypage实例
    @if ($page > 1)
        table.reload('dataTable', {
            page: {
                curr: {{$page}} //重新从第 1 页开始
    }
    });
    @endif
        //监听工具条
        table.on('tool(dataTable)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data //获得当前行数据
                , layEvent = obj.event; //获得 lay-event 对应的值
            if (layEvent === 'del') {
                layer.confirm('确认删除吗？', function (index) {
                    $.post("{{ route('admin.product.del') }}", {
                        _method: 'delete',
                        id: data.id
                    }, function (result) {
                        if (result.code == 0) {
                            obj.del(); //删除对应行（tr）的DOM结构
                        }
                        layer.close(index);
                        layer.msg(result.msg, {icon: 6})
                    });
                });
            } else if (layEvent === 'edit') {
                location.href = '/admin/product/store?id=' + data.id+'&page='+ pageCur;
            }
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
            var ids = []
            var hasCheck = table.checkStatus('dataTable')
            var hasCheckData = hasCheck.data
            if (hasCheckData.length > 0) {
                $.each(hasCheckData, function (index, element) {
                    ids.push(element.id)
                })
            }
            if (ids.length > 0) {
                layer.confirm('确认删除吗？', function (index) {
                    $.post("{{ route('admin.product.del') }}", {_method: 'delete', id: id}, function (result) {
                        if (result.code == 0) {
                            dataTable.reload()
                        }
                        layer.close(index);
                        layer.msg(result.msg, {icon: 6})
                    });
                })
            } else {
                layer.msg('请选择删除项', {icon: 5})
            }
        })
    })
</script>
@endsection
