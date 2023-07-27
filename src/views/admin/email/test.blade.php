
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post"  action="/email_admin/email/test" class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" name="email" class="form-control " value="">
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="">Title</label>
                <input type="text" name="title" class="form-control " value="">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">Content</label>
                <textarea type="text" name="content" class="form-control " ></textarea>
                <div class="invalid-feedback"></div>
            </div>
            <button class="btn btn-primary" type="submit">发送</button>
        </div>
    </form>

</div>
<style>

</style>
<script>

</script>
