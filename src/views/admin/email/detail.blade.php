
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <div class="">
        <div>
            <ul class="email">
                <li><span>Ipv4</span><span>{{$res['info']->ipv4}}</span></li>
                <li><span>Country</span><span>{{$res['info']->country_iso}}</span></li>
                <li><span>Date</span><span>{{$res['info']->created_at}}</span></li>
                <li><span>Ipv6</span><span>{{$res['info']->ipv6}}</span></li>
                <li><span>Url</span><span>{{$res['info']->url}}</span></li>
                <li><span>View</span><span>{{$res['info']->view}}</span></li>
                <li><span>Referrer</span><span>{{$res['info']->referrer}}</span></li>
                <li><span>Keyword</span><span>{{$res['info']->keyword}}</span></li>
                <li><span>Language</span><span>{{$res['info']->language}}</span></li>
                <li><span>Platform</span><span>{{$res['info']->platform}}</span></li>
                <li><span>UserAgent</span><span>{{$res['info']->userAgent}}</span></li>
                <li><span>Webdriver</span><span>{{$res['info']->webdriver}}</span></li>
            </ul>
        </div>
    </div>

</div>
<style>
    .email{}
    .email li{line-height:30px;display:flex}
    .email li span:first-child{margin-right:20px;width:100px;text-align:right;color:#666}
    .email li span:last-child{font-weight:600}
</style>
<script>

</script>
