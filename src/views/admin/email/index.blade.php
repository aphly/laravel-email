<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<style>
    .table_scroll .table_header li:nth-child(4),.table_scroll .table_tbody li:nth-child(4){flex: 0 0 300px;}
    .table_scroll .table_header li:nth-child(6),.table_scroll .table_tbody li:nth-child(6){flex: 0 0 200px;}
</style>
<div class="imain">
    <div class="itop ">
        <form method="get" action="/email_admin/email/index" class="select_form">
        <div class="search_box ">
            <input type="hidden" name="host_id"  value="{{$res['emailHost']->id}}">
            <input type="search" name="ip" placeholder="IP" value="{{$res['search']['ip']}}">
            <button class="" type="submit">搜索</button>
        </div>
        </form>
    </div>
    <form method="post"  @if($res['search']['string']) action="/email_admin/email/del?{{$res['search']['string']}}" @else action="/email_admin/email/del" @endif  class="del_form">
    @csrf
        <div class="table_scroll">
            <div class="table">
                <ul class="table_header">
                    <li >ID</li>
                    <li >IP</li>
                    <li >country</li>
                    <li >url</li>
                    <li >查看次数</li>
                    <li >日期</li>
                    <li >操作</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody @if($v['viewed']==1) viewed @endif">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['id']}}">{{$v['id']}}</li>
                        <li class="wenzi">{{$v['ipv4']}}</li>
                        <li>
                            {{$v['country_iso']}}
                        </li>
                        <li>
                            {{$v['url']}}
                        </li>
                        <li>
                            {{$v->view}}
                        </li>
                        <li>
                            {{$v->created_at}}
                        </li>
                        <li>
                            <a class="badge badge-info ajax_get" data-href="/email_admin/email/detail?id={{$v['id']}}&host_id={{$res['emailHost']->id}}">查看</a>
                        </li>
                    </ul>
                    @endforeach
                    <ul class="table_bottom">
                        <li>
                            <input type="checkbox" class="delete_box deleteboxall"  onclick="checkAll(this)">
                            <button class="badge badge-danger del" type="submit">删除</button>
                        </li>
                        <li>
                            {{$res['list']->links('laravel::admin.pagination')}}
                        </li>
                    </ul>
                @endif
            </div>
        </div>

    </form>
</div>


