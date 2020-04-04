<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('favicon')
        <meta http-equiv="refresh" content="30">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 50px;
            }
            .content{
                padding: 25px;
                min-height: 100vh;
            }
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .table-notif {
                line-height: 10px;
            }
            .table-notif thead {
                background: #1b1e21;
                color: white;
            }
            .table-notif .link {
                display: block;
                padding: 0;
            }
            .table-notif a {
                display: block;
                width: 100%;
                height: 30px;
                background: #2fa360;
                text-align: center;
                line-height: 30px;
                color: black;
            }
            .table-notif span.disabled {
                display: block;
                width: 100%;
                height: 30px;
                background: #1b1e21;
                text-align: center;
                line-height: 30px;
                color: whitesmoke;
            }
        </style>
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
    </head>
    <body>
        <div class="position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif


        </div>
        @auth()
            <div class="content">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <p>Notifikasi Unit Breakdown</p>
                        <table class="table table-bordered table-notif">
                            <thead>
                            <tr>
                                <th>UNIT</th>
                                <th>STATUS</th>
                                <th>UNIT</th>
                                <th>STATUS</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notif_breakdown as $x)
                                @if($loop->first)
                                    <tr>
                                        @endif
                                        @if(!$x->checked)
                                            <td>{{$x->unit->code}}</td>
                                            <td class="link">
                                                @if(\Auth::user()->level == 2)
                                                    <a href="#" onclick="confirmNotif({{$x->id}}, '{{$x->unit->code}}')">Checked</a>
                                                @else
                                                    <span class="disabled" href="">Checked</span>
                                                @endif
                                            </td>
                                        @endif
                                        @if($loop->last || $loop->iteration % 2 == 0)
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <p class="text-right">Notifikasi Unit Ready</p>
                        <table class="table table-bordered table-notif">
                            <thead>
                            <tr>
                                <th>UNIT</th>
                                <th>STATUS</th>
                                <th>UNIT</th>
                                <th>STATUS</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notif_ready as $x)
                                @if($loop->first)
                                    <tr>
                                        @endif
                                        @if(!$x->checked)
                                            <td>{{$x->unit->code}}</td>
                                            <td class="link">
                                                @if(\Auth::user()->level != 2)
                                                    <a href="#" onclick="confirmNotif('{!! $x !!}')">Checked</a>
                                                @else
                                                    <span class="disabled" href="">Checked</span>
                                                @endif
                                            </td>
                                        @endif
                                        @if($loop->last || $loop->iteration % 2 == 0)
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    function confirmNotif(id, log) {
                        let bool = confirm(`Anda yakin mengkonfirmasi notifikasi Unit ${log}?`);
                        if(bool === true){
                            let xhr = new XMLHttpRequest();
                            xhr.open('POST', '{{route('notifikasi')}}');
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onload = function() {
                                if (xhr.status === 200) {
                                    setTimeout(function () {
                                        window.location.reload();
                                    },1000);
                                }
                                else {
                                    alert('Request failed.  Returned status of ' + xhr.status);
                                }
                            };
                            xhr.send(encodeURI('_token={{csrf_token()}}&log='+id));
                        }
                    }
                </script>
            </div>
        @endauth
        <div class="content">
            <p class="display-4 text-center">Data Breakdown</p>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <p>Data Unit Breakdown</p>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <th>Unit</th>
                            <th>Keterangan</th>
                            <th>Lokasi</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            </thead>
                            <tbody>
                            @foreach($breakdown as $x)
                                <tr>
                                    <td>{{$x->unit->code}}</td>
                                    <td>{{$x->keterangan}}</td>
                                    <td>{{$x->location}}</td>
                                    <td>{{ \Carbon\Carbon::parse($x->breakdown)->format('H:i') }} WITA</td>
                                    <td class="bg-danger">B/D</td>
                                    <td class="{{$x->kategori=='SCH'?'bg-info':'bg-secondary'}}">{{$x->kategori}}</td>
                                </tr>
                            @endforeach
                            @if(!count($breakdown))
                                <tr><td colspan="6" class="text-center">Saat ini kosong</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <p>Data Unit Ready</p>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <th>Unit</th>
                            <th>Keterangan</th>
                            <th>Lokasi</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            </thead>
                            <tbody>
                            @foreach($ready as $x)
                                <tr>
                                    <td>{{$x->unit->code}}</td>
                                    <td>{{$x->keterangan}}</td>
                                    <td>{{$x->location}}</td>
                                    <td>{{ \Carbon\Carbon::parse($x->ready)->format('H:i') }} WITA</td>
                                    <td class="bg-success">ready</td>
                                    <td class="{{$x->kategori=='SCH'?'bg-info':'bg-secondary'}}">{{$x->kategori}}</td>
                                </tr>
                            @endforeach
                            @if(!count($ready))
                                <tr><td colspan="6" class="text-center">Saat ini kosong</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-dark text-muted text-center py-3">
            <a class="text-muted" href="https://instagram.com/reyzeal">Reyzeal</a> &copy; 2019 <a class="text-muted" href="https://instagram.com/eternal_loops.id">Eternal Loops</a>
        </div>
    </body>
</html>
