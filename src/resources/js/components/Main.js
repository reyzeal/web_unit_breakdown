import React from 'react';
import ReactDOM from 'react-dom';
const moment = require('moment');
const flatpickr = require('flatpickr');


class Main extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            breakdown : [],
            ready : [],
            all : [],
            startDate : '',
            endDate : '',
            endH : '',
            endM : '',
            startH : '',
            startM : '',
            loading: false,
            time : moment().format('D-M-Y HH:mm:ss'),
        };
        this.query = {
            start : null,
            end : null
        }
    }
    timer(){
        this.setState({
            time: moment().format('D-M-Y HH:mm:ss')
        });
    }
    componentWillUnmount() {
        clearInterval(this.timerID);
    }

    componentDidMount() {
        this.timeriD = setInterval(this.timer.bind(this), 1000);
        this.setState({loading: true});
        let url = '/resource' + ((this.query.start === this.query.end && this.query.start === null)?'':'?'+$.param(this.query));
        fetch(url).then(r => r.json()).then(r => {
            this.setState({
                breakdown : r.breakdown,
                ready : r.ready,
                all : r.all,
                startDate : r.startDate,
                endDate : r.endDate,
                endH : parseInt(r.endH),
                endM : parseInt(r.endM),
                startH : parseInt(r.startH),
                startM : parseInt(r.startM),
                loading : false,
            });
            let start = `${r.startDate} ${r.startH}:${r.startM}`;
            let end = `${r.endDate} ${r.endH}:${r.endM}`;
            flatpickr('.flatpick[name=startDate]',{
                enableTime:true,
                defaultDate:this.state.startDate,
                defaultHour:this.state.startH,
                defaultMinute:this.state.startM,
                dateFormat:"d-m-Y H:i",
                time_24hr:true,
                onClose: (a,b,c) => this.gantiWaktu('start', b)
            }).setDate(start);
            flatpickr('.flatpick[name=endDate]',{
                enableTime:true,
                defaultDate:this.state.endDate,
                defaultHour:this.state.endH,
                defaultMinute:this.state.endM,
                dateFormat:"d-m-Y H:i",
                time_24hr:true,
                onClose: (a,b,c) => this.gantiWaktu('end', b)
            }).setDate(end);
            // console.log('initial',start,end);
            this.query.start = start;
            this.query.end = end;
        });
    }
    editBreakdown(x){
        const {log, code, kategori, keterangan, location} = x;
        $('#editBreakdown [name=log]').val(log);
        $('#editBreakdown [name=keterangan]').val(keterangan);
        $('#editBreakdown [name=kategori]').find(`[value=${kategori}]`).attr('selected','selected');
        $('#editBreakdown [name=code]').val(code);
        $('#editBreakdown [name=location]').val(location);
    }
    setLogReady(x){
        const {log, code, kategori, keterangan, location} = x;
        $('#tambahReady [name=log]').val(log);
        $('#tambahReady [name=keterangan]').val(keterangan);
        $('#tambahReady [name=kategori]').find(`[value=${kategori}]`).attr('selected','selected');
        $('#tambahReady [name=code]').val(code);
        $('#tambahReady [name=location]').val(location);
    }
    gantiWaktu(i,b){
        this.query[i] = b;
        this.componentDidMount();
    }

    render() {
        const {breakdown, ready, all} = this.state;
        return <div>
            <div>
                <div className={!this.state.loading?'d-block w-100':'d-none'}>
                    <p className="text-muted p-1 float-right">{this.state.time}</p>
                </div>
            </div>
            <ul className="nav nav-tabs" id="myTab" role="tablist">
                <li className="nav-item">
                    <a className="nav-link active" id="breakdown-tab" data-toggle="tab" href="#breakdown" role="tab"
                       aria-controls="breakdown" aria-selected="true">Breakdown</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link" id="ready-tab" data-toggle="tab" href="#ready" role="tab"
                       aria-controls="ready" aria-selected="false">Ready</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link" id="report-tab" data-toggle="tab" href="#report" role="tab"
                       aria-controls="report" aria-selected="false">Report</a>
                </li>
            </ul>
            <div className="tab-content" id="myTabContent">
                <div className="tab-pane fade show active py-3" id="breakdown" role="tabpanel" aria-labelledby="breakdown-tab">
                    <button data-toggle="modal" data-target="#tambahBreakdown">
                        <i className="fa fa-gear"> </i> Add Breakdown
                    </button>
                    <p className="display-4 text-center">Data Breakdown</p>
                    <div className="table-responsive">
                        <table className="table table-bordered my-3">
                        <thead className="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Keterangan</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Kategori</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        {breakdown.map((data,index) => (<tr>
                            <td>{index+1}</td>
                            <td>{data.unit.code}</td>
                            <td>{data.keterangan}</td>
                            <td>{data.location}</td>
                            <td>{moment(data.breakdown).format('D-M-Y')}</td>
                            <td>{moment(data.breakdown).format('HH:mm')+' WITA'}</td>
                            <td className="bg-danger">B/D</td>
                            <td className={data.kategori==="SCH"?'bg-info':'bg-secondary'}>{data.kategori}</td>
                            <td>
                                <button className="btn btn-warning mx-1" data-toggle="modal" data-target="#editBreakdown" onClick={this.editBreakdown.bind(this,{log:data.id, kategori:data.kategori, keterangan:data.keterangan, code:data.unit.code, location:data.location})}>
                                    <i className="fa fa-pencil"> </i> Edit
                                </button>
                                <button className="btn btn-success mx-1" data-toggle="modal" data-target="#tambahReady" onClick={this.setLogReady.bind(this,{log:data.id, kategori:data.kategori, keterangan:data.keterangan, code:data.unit.code, location:data.location})}>
                                    <i className="fa fa-check"> </i> Ready
                                </button>
                            </td>
                        </tr>))}
                        {!breakdown.length?<tr><td className="text-center" colSpan={8}>Saat ini kosong</td></tr>:''}
                        </tbody>
                    </table>
                    </div>
                </div>
                <div className="tab-pane fade py-3" id="ready" role="tabpanel" aria-labelledby="ready-tab">
                    {/*<button data-toggle="modal" data-target="#tambahReady" className="btn btn-primary my-3">Tambah Ready</button>*/}
                    <div className="table-responsive">
                        <p className="display-4 text-center">Data Breakdown</p>
                        <table className="table table-bordered my-3">
                        <thead className="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            {/*<td>Action</td>*/}
                        </tr>
                        </thead>
                        <tbody>
                        {ready.map((data,index) => (<tr>
                            <td>{index+1}</td>
                            <td>{data.unit.code}</td>
                            <td>{data.keterangan}</td>
                            <td>{data.location}</td>
                            <td>{moment(data.ready).format('D-M-Y')}</td>
                            <td>{moment(data.ready).format('HH:mm')+' WITA'}</td>
                            <td className="bg-success">ready</td>
                            <td>{data.kategori}</td>
                            {/*<td>*/}
                            {/*    /!*<button className="btn btn-warning">Edit</button>*!/*/}
                            {/*    <button className="btn btn-success">Ready</button>*/}
                            {/*</td>*/}
                        </tr>))}
                        {!ready.length?<tr><td className="text-center" colSpan={7}>Saat ini kosong</td></tr>:''}
                        </tbody>
                    </table>
                    </div>
                </div>
                <div className="tab-pane fade py-3" id="report" role="tabpanel" aria-labelledby="report-tab">
                    <div className="table-responsive" id="table-report">
                        <p className="display-4 text-center">Data Breakdown</p>
                        <div className="d-flex justify-content-center">
                            <div className={this.state.loading?'d-block':'d-none'}>
                                <div className="spinner-grow text-primary" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                                <div className="spinner-grow text-secondary" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                                <div className="spinner-grow text-success" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div className="d-flex flex-row-reverse">
                            <a className="btn btn-outline-primary mx-2" href={"/download?"+$.param(this.query)}>
                                <i className="fa fa-file-excel-o"> </i> Print Excel
                            </a>
                            <div className="align-items-center">
                                end date:
                                <input name="endDate" className='flatpick' onChange={this.gantiWaktu.bind(this)}/>
                            </div>
                            <div className="align-items-center">
                                start date:
                                <input name="startDate" className='flatpick' onChange={this.gantiWaktu.bind(this)}/>
                            </div>
                        </div>
                        <table className="table table-bordered my-3">
                        <thead className="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            {/*<td>Action</td>*/}
                        </tr>
                        </thead>
                        <tbody>
                        {all.map((data,index) => (<tr>
                            <td>{index+1}</td>
                            <td>{data.unit.code}</td>
                            <td>{data.keterangan}</td>
                            <td>{data.location}</td>
                            <td>{moment(data.breakdown).format('D-M-Y')}</td>
                            <td>{moment(data.breakdown).format('HH:mm')+' WITA'}</td>
                            <td className="bg-danger">B/D</td>
                            <td>{data.ready?moment(data.ready).format('D-M-Y'):''}</td>
                            <td>{data.ready?moment(data.ready).format('HH:mm')+' WITA':''}</td>
                            <td className={data.ready?"bg-success":""}>{data.ready?"ready":"-"}</td>
                            <td>{data.kategori}</td>
                            {/*<td>*/}
                            {/*    /!*<button className="btn btn-warning">Edit</button>*!/*/}
                            {/*    <button className="btn btn-success">Ready</button>*/}
                            {/*</td>*/}
                        </tr>))}
                        {!all.length?<tr><td className="text-center" colSpan={11}>Saat ini kosong</td></tr>:''}
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>


        </div>;
    }
}

export default Main;

ReactDOM.render(<Main/>,document.getElementById('main'));
