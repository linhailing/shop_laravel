@extends('admin.base')

@section('content')
<div class="layui-row layui-col-space15">
    <div class="layui-col-md8">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header">快捷方式</div>
                    <div class="layui-card-body">
                        <div class="layui-carousel layadmin-carousel layadmin-shortcut">
                            <div carousel-item>
                                <ul class="layui-row layui-col-space10">
                                    <li class="layui-col-xs3">
                                        <a lay-href="">
                                            <i class="layui-icon layui-icon-align-left"></i>
                                            <cite>公司信息</cite>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs3">
                                        <a lay-href="">
                                            <i class="layui-icon layui-icon-rate-half"></i>
                                            <cite>商标信息</cite>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs3">
                                        <a lay-href="">
                                            <i class="layui-icon layui-icon-auz"></i>
                                            <cite>法律诉讼</cite>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs3">
                                        <a layadmin-event="im" lay-href="">
                                            <i class="layui-icon layui-icon-spread-left"></i>
                                            <cite>品牌信息</cite>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs3">
                                        <a lay-href="">
                                            <i class="layui-icon layui-icon-app"></i>
                                            <cite>分类信息</cite>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs3">
                                        <a lay-href="">
                                            <i class="layui-icon layui-icon-fonts-code"></i>
                                            <cite>标签管理</cite>
                                        </a>
                                    </li>
                                </ul>

                                <ul class="layui-row layui-col-space10">
                                    <li class="layui-col-xs3">
                                        <a lay-href="set/user/info.html">
                                            <i class="layui-icon layui-icon-set"></i>
                                            <cite>我的资料</cite>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header">数据统计</div>
                    <div class="layui-card-body">
                        <div class="layui-carousel layadmin-carousel layadmin-backlog">
                            <div carousel-item>
                                <ul class="layui-row layui-col-space10">
                                    <li class="layui-col-xs6">
                                        <a href="javascript:;" class="layadmin-backlog-body"><h3>严选品牌</h3><p><cite>{{$yTotal}}</cite></p></a>
                                    </li>
                                    <li class="layui-col-xs6">
                                        <a href="javascript:;" class="layadmin-backlog-body"><h3>普通品牌</h3><p><cite>{{$pTotal}}</cite></p></a>
                                    </li>
                                    <li class="layui-col-xs6">
                                        <a href="javascript:;" class="layadmin-backlog-body"><h3>用户总数</h3><p><cite>{{$member}}</cite></p></a>
                                    </li>
                                    <li class="layui-col-xs6">
                                        <a href="javascript:;"  class="layadmin-backlog-body"><h3>昨日新增</h3><p><cite>{{$lateDay}}</cite></p></a>
                                    </li>
                                </ul>
                                <ul class="layui-row layui-col-space10">
                                    <li class="layui-col-xs6">
                                        <a href="javascript:;" class="layadmin-backlog-body">
                                            <h3>待审友情链接</h3>
                                            <p><cite style="color: #FF5722;">5</cite></p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">数据概览</div>
                    <div class="layui-card-body">
                        <div class="layui-carousel layadmin-carousel layadmin-dataview" data-anim="fade"
                             lay-filter="LAY-index-dataview">
                            <div carousel-item id="LAY-index-dataview1">
                                <div><i class="layui-icon layui-icon-loading1 layadmin-loading"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-card">

                    <div class="layui-tab layui-tab-brief layadmin-latestData">

                        <ul class="layui-tab-title">

                            <li class="layui-this">今日热搜</li>

                            <li>今日热帖</li>

                        </ul>

                        <div class="layui-tab-content">

                            <div class="layui-tab-item layui-show">

                                <table id="LAY-index-topSearch-index"></table>

                            </div>

                            <div class="layui-tab-item">

                                <table id="LAY-index-topCard-index"></table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="layui-col-md4">

        <div class="layui-card">

            <div class="layui-card-header">版本信息</div>

            <div class="layui-card-body layui-text">

                <table class="layui-table">

                    <colgroup>

                        <col width="100">

                        <col>

                    </colgroup>

                    <tbody>

                    <tr>

                        <td>当前版本</td>

                        <td>

                            <script type="text/html" template>

                                v1.0.0

                                <a href="http://fly.layui.com/docs/3/" target="_blank"
                                   style="padding-left: 15px;">更新日志</a>

                            </script>

                        </td>

                    </tr>

                    <tr>

                        <td>基于框架</td>

                        <td>

                            <script type="text/html" template>

                                layui-v2.3.0

                            </script>

                        </td>

                    </tr>

                    <tr>

                        <td>主要特色</td>

                        <td>零门槛 / 响应式 / 清爽 / 极简</td>

                    </tr>

                    <tr>

                        <td>获取渠道</td>

                        <td style="padding-bottom: 0;">

                            <div class="layui-btn-container">

                                <a href="http://www.layui.com/admin/" target="_blank"
                                   class="layui-btn layui-btn-danger">获取授权</a>

                                <a href="http://fly.layui.com/download/layuiAdmin/" target="_blank" class="layui-btn">立即下载</a>

                            </div>

                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <div class="layui-card">

            <div class="layui-card-header">效果报告</div>

            <div class="layui-card-body layadmin-takerates">

                <div class="layui-progress" lay-showPercent="yes">

                    <h3>转化率（日同比 28% <span class="layui-edge layui-edge-top" lay-tips="增长" lay-offset="-15"></span>）</h3>

                    <div class="layui-progress-bar" lay-percent="65%"></div>

                </div>

                <div class="layui-progress" lay-showPercent="yes">

                    <h3>签到率（日同比 11% <span class="layui-edge layui-edge-bottom" lay-tips="下降" lay-offset="-15"></span>）
                    </h3>

                    <div class="layui-progress-bar" lay-percent="32%"></div>

                </div>

            </div>

        </div>


        <div class="layui-card">

            <div class="layui-card-header">实时监控</div>

            <div class="layui-card-body layadmin-takerates">

                <div class="layui-progress" lay-showPercent="yes">

                    <h3>CPU使用率</h3>

                    <div class="layui-progress-bar" lay-percent="58%"></div>

                </div>

                <div class="layui-progress" lay-showPercent="yes">

                    <h3>内存占用率</h3>

                    <div class="layui-progress-bar layui-bg-red" lay-percent="90%"></div>

                </div>

            </div>

        </div>


        <div class="layui-card">

            <div class="layui-card-header">产品动态</div>

            <div class="layui-card-body">

                <div class="layui-carousel layadmin-carousel layadmin-news" data-autoplay="true" data-anim="fade"
                     lay-filter="news">

                    <div carousel-item>

                        <div><a href="http://fly.layui.com/docs/2/" target="_blank" class="layui-bg-red">layuiAdmin
                                快速上手文档</a></div>

                        <div><a href="http://fly.layui.com/vipclub/list/layuiadmin/" target="_blank"
                                class="layui-bg-green">layuiAdmin 会员讨论专区</a></div>

                        <div><a href="http://www.layui.com/admin/#get" target="_blank" class="layui-bg-blue">获得 layui
                                官方后台模板系统</a></div>

                    </div>

                </div>

            </div>

        </div>


        <div class="layui-card">

            <div class="layui-card-header">

                作者心语

                <i class="layui-icon layui-icon-tips" lay-tips="要支持的噢" lay-offset="5"></i>

            </div>

            <div class="layui-card-body layui-text layadmin-text">

                <p>一直以来，layui 秉承无偿开源的初心，虔诚致力于服务各层次前后端 Web 开发者，在商业横飞的当今时代，这一信念从未动摇。即便身单力薄，仍然重拾决心，埋头造轮，以尽可能地填补产品本身的缺口。</p>

                <p>在过去的一段的时间，我一直在寻求持久之道，已维持你眼前所见的一切。而 layuiAdmin 是我们尝试解决的手段之一。我相信真正有爱于 layui 生态的你，定然不会错过这一拥抱吧。</p>

                <p>子曰：君子不用防，小人防不住。请务必通过官网正规渠道，获得 <a href="http://www.layui.com/admin/" target="_blank">layuiAdmin</a>！
                </p>

                <p>—— 贤心（<a href="http://www.layui.com/" target="_blank">layui.com</a>）</p>

            </div>

        </div>

    </div>

</div>
@endsection

@section('script')
    <script>
        layui.use(['index','console','table','echarts'], function () {
            var table = layui.table;
            table.render({
                elem: "#LAY-index-topSearch-index",
                //url: layui.setter.base + "json/console/top-search.js",
                url:  "/admin/index/data",
                where: {model: "search_log"},
                page: !0,
                cols: [[{
                    type: "numbers",
                    fixed: "left"
                },
                    {
                        field: "search_name",
                        title: "关键词",
                        minWidth: 120,
                        templet: '<div><a href="https://www.baidu.com/s?wd=@{{d.search_name}}" target="_blank" class="layui-table-link">@{{d.search_name}}</div>'
                    },
                    {
                        field: "search_count",
                        title: "搜索次数",
                        minWidth: 120,
                        sort: !0
                    },
                    {
                        field: "uid",
                        title: "用户数",
                        sort: !0,
                        minWidth: 120,
                    },
                    {
                        field: "create_time",
                        title: "日期",
                        minWidth: 120,
                    }]],
                skin: "line"
            })
            var e = layui.$,
                t = layui.carousel,
                a = layui.echarts,
                i = [],
                l = [
                    {
                        title: {
                            text: "最近一周新增的用户量",
                            x: "center",
                            textStyle: {
                                fontSize: 14
                            }
                        },
                        tooltip: {
                            trigger: "axis",
                            formatter: "{b}<br>新增用户：{c}"
                        },
                        xAxis: [{
                            type: "category",
                            data: {{json_encode(array_keys($reportDay))}}
                        }],
                        yAxis: [{
                            type: "value"
                        }],
                        series: [{
                            type: "line",
                            data: {{json_encode(array_values($reportDay))}}
                        }]
                    }],
                n = e("#LAY-index-dataview1").children("div"),
                r = function (e) {
                    i[e] = a.init(n[e], layui.echartsTheme),
                        i[e].setOption(l[e]),
                        window.onresize = i[e].resize
                };
            if (n[0]) {
                r(0);
                var o = 0;
                t.on("change(LAY-index-dataview1)",
                    function (e) {
                        r(o = e.index)
                    }),
                    layui.admin.on("side",
                        function () {
                            setTimeout(function () {
                                r(o)
                            }, 300)
                        }),
                    layui.admin.on("hash(tab)",
                        function () {
                            layui.router().path.join("") || r(o)
                        })
            }
        })
    </script>
@endsection
