@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div id="main"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="tambahBreakdown" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="{{route('breakdown')}}">
        @csrf
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Unit Breakdown</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Code Unit</label>
                        <input class="form-control" type="text" name="code">
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input class="form-control" type="text" name="location">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input class="form-control" type="text" name="keterangan">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="kategori" class="form-control">
                            <option>SCH</option>
                            <option>UNSCH</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="editBreakdown" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="{{route('edit.breakdown')}}">
        @csrf
        <input type="hidden" value="0" name="log">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Unit Breakdown</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Code Unit</label>
                        <input class="form-control" type="text" name="code">
                    </div>
                    <div class="form-group">
                        <label>Breakdown Timestamp</label>
                        <input class="form-control" type="text" name="location">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input class="form-control" type="text" name="keterangan">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="kategori" class="form-control">
                            <option value="SCH">SCH</option>
                            <option value="UNSCH">UNSCH</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="tambahReady" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="{{route('ready')}}">
        @csrf
        <input type="hidden" name="log" value="0">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unit Ready</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Code Unit</label>
                        <input class="form-control" type="text" name="code">
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input class="form-control" type="text" name="location">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input class="form-control" type="text" name="keterangan">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="kategori" class="form-control">
                            <option value="SCH">SCH</option>
                            <option value="UNSCH">UNSCH</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
