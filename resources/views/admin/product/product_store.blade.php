@extends('admin.base')

@section('content')
<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <h2>@if(!empty($id)) 更新信息 @else 新增信息 @endif</h2>
    </div>
    <div class="layui-card-body">
        <form action="{{route('admin.product.store',['id'=>$id])}}" method="post" class="layui-form">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="product_name" value="{{$info->product_name??old('product_name')}}" placeholder="如:一点点">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品编码</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="product_core" lay-verify="required" value="{{$info->product_core??old('product_core')}}" placeholder="如:123456">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">国条码</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="bar_code" value="{{$info->bar_code??old('bar_code')}}" placeholder="如:123456">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品分类</label>
                <div class="layui-input-block">
                    <div class="layui-input-inline">
                        <select name="category_id"  lay-search="">
                            <option value="0">请选择分类</option>
                            <option value="1" @if($info && $info->category_id==1) selected @endif>普通品牌</option>
                            <option value="2" @if($info && $info->category_id==2) selected @endif>严选品牌</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品售价</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="price" value="{{$info->price??old('price')}}" placeholder="如:298.00">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品加权平均成本</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="average_cost" value="{{$info->average_cost??old('average_cost')}}" placeholder="如:300.00">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">上下架状态</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="publish_status" @if($info && $info->publish_status == 1) checked @endif  lay-skin="switch" lay-filter="switchTest" lay-text="上架|下架" value="1">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">审核状态</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="audit_status" @if($info && $info->audit_status == 1) checked @endif  lay-skin="switch" lay-filter="switchTest" lay-text="已审核|未审核" value="1">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品图片</label>
                <button type="button" class="layui-btn" id="pic_file"><i class="layui-icon"></i>上传头像</button>
                <div class="layui-input-block">
                    <div class="layui-upload-list">
                        <div class="layui-upload-list">
                            <img class="layui-upload-img" id="pic_img" src="{{$info->pic_icon??''}}">
                            <input class="layui-input" id="pic_value" type="hidden" name="pic_icon"  value="{{$info->pic_icon??old('pic_icon')}}">
                            <p id="pic_text"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品图册</label>
                <button type="button" class="layui-btn" id="ppimglist">多图片上传</button>
                <div class="layui-input-block">
                    <div class="layui-upload">
                        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                            预览图：
                            <div class="layui-upload-list" id="ppimglist_div">
                                @if(!empty($info->pics_url))
                                @foreach($info->pics_url as $item)
                                <dd class="layui-upload-dd">
                                    <i onclick="deleteImg($(this))" class="layui-icon"></i>
                                    <img src="{{$item}}" class="layui-upload-img">
                                    <input type="hidden" name="pics_url[]" value="{{$item}}"/>
                                </dd>
                                @endforeach
                                @endif
                            </div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品重量</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="weight" value="{{$info->weight??old('weight')}}" placeholder="如:20g">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品长度</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="length" value="{{$info->length??old('length')}}" placeholder="如:12.5">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品高度</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="height" value="{{$info->height??old('height')}}" placeholder="如:12.5">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品宽度</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="width" value="{{$info->width??old('width')}}" placeholder="如:12.5">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品颜色</label>
                <div class="layui-input-block">
                    <input type="radio" name="color_type" value="红" title="红" @if($info && $info->color_type=='红') checked="" @endif>
                    <input type="radio" name="color_type" value="黄" title="黄" @if($info && $info->color_type=='黄') checked="" @endif>
                    <input type="radio" name="color_type" value="蓝" title="蓝" @if($info && $info->color_type=='蓝') checked="" @endif>
                    <input type="radio" name="color_type" value="黑" title="黑" @if($info && $info->color_type=='黑') checked="" @endif>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">生产日期</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="production_date" id="production_date" value="{{$info->production_date??old('production_date')}}" placeholder="如：2019-01-20">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品有效期</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="shelf_life" value="{{$info->shelf_life??old('shelf_life')}}" placeholder="如：12">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品库存</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="stock" value="{{$info->stock??old('stock')}}" placeholder="如：100">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">商品描述</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="descript" class="layui-textarea" style="height: 300px;width: 1000px;border: 0;padding: 0;" name="descript">{{$info->descript??old('descript')}}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit="">确 认</button>
                    <a href="{{route('admin.product')}}?page={{$page}}" class="layui-btn">返 回</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('style')
<style>
    .layui-upload-img{
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;

    }
    .layui-upload-dd{
        display: inline-block;
        position: relative;
        margin: 0 10px 10px 0;
    }
    .layui-upload-dd .layui-icon{
        position: absolute;
        z-index: 999;
        right: 0;
        top: -20px;
    }
</style>
@endsection
@section('script')
<script type="text/javascript" charset="utf-8" src="/static/plugin/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/plugin/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/static/plugin/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/editorConfig.js"></script>
<script>
    var _token = '{{csrf_token()}}';
    var ue = UE.getEditor('descript', config(_token));
    layui.use(['laydate','upload'], function () {
        var $ = layui.jquery
            ,upload = layui.upload,
            laydate = layui.laydate;
        //开启公历节日
        laydate.render({
            elem: '#production_date'
        });
        //普通图片上传
        var pic = upload.render({
            elem: '#pic_file'
            ,url: '/admin/uploadImage'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#pic_img').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }
                //上传成功
                $('#pic_value').val(res.url)
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#pic_text');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    pic.upload();
                });
            }
        });
        //多图片上传
        upload.render({
            elem: '#ppimglist'
            ,url: '/admin/uploadImage'
            ,multiple: true
            ,before: function(obj){
                layer.msg('图片上传中...', {
                    icon: 16,
                    shade: 0.01,
                    time: 0
                })
            }
            ,done: function(res){
                layer.close(layer.msg('上传成功！'));
                $('#ppimglist_div').append('<dd class="layui-upload-dd"><i onclick="deleteImg($(this))"   class="layui-icon"></i>' +
                    '<img src="' + res.url + '" class="layui-upload-img" ><input type="hidden" name="pics_url[]" value="' + res.url + '" />' +
                    '</dd>');
            },
            error: function () {
                layer.msg('上传错误！');
            }
        });
    });
    function deleteImg(obj){
        obj.parent('.layui-upload-dd').remove();
    }
</script>
@endsection
