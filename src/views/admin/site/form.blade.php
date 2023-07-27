
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/email_admin/site/save?id={{$res['info']->id}}" @else action="/email_admin/site/save" @endif class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">Host</label>
                <input type="text" name="host" class="form-control " value="{{$res['info']->host}}">
                <div class="invalid-feedback"></div>
            </div>
            @if($res['info']->id)
            <div class="form-group">
                <label for="">Appid</label>
                <input type="text" name="appid" class="form-control " value="{{$res['info']->appid}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">secret</label>
                <input type="text" name="secret" class="form-control " value="{{$res['info']->secret}}">
                <div class="invalid-feedback"></div>
            </div>
            @endif
            <div class="form-group">
                <label for="">状态</label>
                <select name="status"  class="form-control">
                    @if(isset($dict['status']))
                        @foreach($dict['status'] as $key=>$val)
                            <option value="{{$key}}" @if($res['info']->status==$key) selected @endif>{{$val}}</option>
                        @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <button class="btn btn-primary" type="submit">保存</button>
        </div>
    </form>

</div>
<style>

</style>
<script>

</script>
