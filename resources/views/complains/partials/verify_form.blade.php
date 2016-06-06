{!! Form::open(array('route' => ['complain.verify',$complain->complain_id],'method'=>'put','class'=>"form-horizontal", 'id'=>"form1")) !!}

<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title">Verifikasi Pengguna</h3>
    </div>
    <div class="panel-body">


        <div class="form-group">
            <label class="col-sm-2 control-label">Tarikh </label>
            <div class="col-sm-2">
                <p class="form-control-static">{{ $complain->created_at->format('m/d/Y') }}</p>
            </div>
            <label class="col-sm-2 control-label">Masa </label>
            <div class="col-sm-2">
                <p class="form-control-static">{{ $complain->created_at->format('m/d/Y') }}</p>
            </div>
        </div>

        @if($complain->complain_status_id==3)

            <div class="form-group">
                <label class="col-sm-2 control-label">Komen Pengguna (Jika Ada)</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="user_comment" rows="3">{{ old('user_comment') }}</textarea>
                </div>
            </div>

        @endif

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">

                <input type="hidden" name="submit_type" value="{{ old('submit_type') }}" id="submit_type" />

                @if($complain->complain_status_id==3)

                    <button type="button" id="submit_finish" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> Selesai</button>
                    <button type="button" id="submit_reject" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Tidak Selesai</button>

                @endif

            </div>
        </div>



    </div>
</div>
<!--end-->

{!! Form::close() !!}