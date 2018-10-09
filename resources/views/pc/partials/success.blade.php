<style>
    .alert-extra {
        padding: 10px;
    }

    .alert-extra strong {
        margin-bottom: 8px;
    }
</style>
@if (Session::has('success'))
    <div class="alert alert-success alert-extra">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>
            <i class="fa fa-check-circle fa-lg fa-fw"></i>
        </strong>
        {{ Session::get('success') }}
    </div>
@endif