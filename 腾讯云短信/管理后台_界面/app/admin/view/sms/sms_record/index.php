{extend name="public/container"}
{block name="head_top"}

{/block}
{block name="content"}
<div class="layui-fluid" style="background: #fff;margin-top: -10px;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">

                            <!--                            <div class="layui-inline">-->
                            <!--                                <label class="layui-form-label">发送账号</label>-->
                            <!--                                <div class="layui-input-block">-->
                            <!--                                    <input type="text" name="uid" class="layui-input" placeholder="请输入发送账号">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <div class="layui-inline">
                                <label class="layui-form-label">手机号码</label>
                                <div class="layui-input-block">
                                    <input type="text" name="phone" class="layui-input" placeholder="请输入手机号码">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">短信模板</label>
                                <div class="layui-input-block">
                                    <select name="template">
                                        <option value="">全部</option>
                                        <option value="VERIFICATION_CODE">VERIFICATION_CODE</option>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">用户短信接收状态</label>
                                <div class="layui-input-block">
                                    <select name="status">
                                        <option value="">全部</option>
                                        <option value="100">成功</option>
                                        <option value="130">失败</option>
                                        <option value="131">空号</option>
                                        <option value="132">停机</option>
                                        <option value="134">暂无</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search"
                                            lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="layui-card">
                <div class="layui-card-header">短信记录</div>
                <div class="layui-card-body">
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--                    短信下发状态-->
                    <script type="text/html" id="resultcode">

                        {{#if(d._resultcode == '成功'){ }}
                            <span class="layui-badge  layui-bg-green">
                        {{# }else if(d._resultcode == '失败'){ }}
                            <span class="layui-badge  layui-bg-red">
                        {{# } else { }}
                            <span class="layui-badge  layui-bg-gray">
                        {{# } }}
                        {{d._resultcode}}</span>

                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List', "{:Url('recordList')}", function () {
        return [
            {field: 'id', title: 'ID', sort: true, width: '5%'},
            // {field: 'uid', title: '发送账号', width: '10%'},
            {field: 'phone', title: '手机号码', width: '13%'},
            {field: 'template', title: '短信模板', width: '18%'},
            {field: '_content', title: '模板内容', width: '10%'},
            {field: '_resultcode', title: '用户短信接收状态', templet: '#resultcode', width: '20%'},
            {field: 'note', title: '备注 / 用户接收时间', width: '18%'},
            {field: 'add_time', title: '发送时间', sort: true, width: '17%'},
        ];
    });
    //查询
    layList.search('search', function (where) {
        layList.reload(where);
    });
</script>
{/block}