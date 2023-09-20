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
            <input type="hidden" name="site_id"  value="{{$res['emailSite']->id}}">
            <input type="search" name="email" placeholder="Email" value="{{$res['search']['email']}}">
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
                    <li >Email</li>
                    <li >title</li>
                    <li >类型</li>
                    <li >状态</li>
                    <li >日期</li>
                    <li >操作</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody @if($v['viewed']==1) viewed @endif">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['id']}}">{{$v['id']}}</li>
                        <li class="wenzi">{{$v['email']}}</li>
                        <li>
                            {{$v['title']}}
                        </li>
                        <li>
                            @if($dict['email_type'])
                                <span class="badge">{{$dict['email_type'][$v->type]}}</span>
                            @endif
                        </li>
                        <li>
                            @if($dict['email_status'])
                                @if($v->status==1)
                                    <span class="badge badge-success">{{$dict['email_status'][$v->status]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['email_status'][$v->status]}}</span>
                                @endif
                            @endif
                        </li>
                        <li>
                            {{$v->created_at}}
                        </li>
                        <li>
                            <a class="badge badge-info ajax_html" data-href="/email_admin/email/detail?id={{$v['id']}}&site_id={{$res['emailSite']->id}}">查看</a>
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


