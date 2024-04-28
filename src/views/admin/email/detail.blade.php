
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>

<div class="imain">
    <div class="">
        <div>
            <ul class="detail_view">
                <li><div class="view_li_l">email</div><div class="view_li_r">{{$res['info']->email}}</div></li>
                <li><div class="view_li_l">site_id</div><div class="view_li_r">{{$res['emailSite']->host}}</div></li>
                <li><div class="view_li_l">type</div><div class="view_li_r">
                        @if($dict['email_type'])
                            {{$dict['email_type'][$res['info']->type]}}
                        @endif
                    </div></li>
                <li><div class="view_li_l">queue_priority</div><div class="view_li_r">
                        @if($dict['email_queue_priority'])
                            {{$dict['email_queue_priority'][$res['info']->queue_priority]}}
                        @endif
                    </div></li>
                <li><div class="view_li_l">status</div><div class="view_li_r">
                        @if($dict['email_status'])
                           {{$dict['email_status'][$res['info']->status]}}
                        @endif</div></li>
                <li><div class="view_li_l">res</div><div class="view_li_r">
                        {{$res['info']->res}}
                    </div></li>
                <li><div class="view_li_l">title</div><div class="view_li_r">{{$res['info']->title}}</div></li>
                <li><div class="view_li_l">content</div><div class="view_li_r" >{{$res['info']->content}}</div></li>
                <li><div class="view_li_l">created_at</div><div class="view_li_r">{{$res['info']->created_at}}</div></li>
                <li><div class="view_li_l">updated_at</div><div class="view_li_r">{{$res['info']->updated_at}}</div></li>
            </ul>
        </div>
    </div>

</div>
<style>

</style>
<script>

</script>
