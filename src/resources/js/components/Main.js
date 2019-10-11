import React from 'react';
import ReactDOM from 'react-dom';
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';
window.html2canvas = html2canvas;
class Main extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            breakdown : [],
            ready : [],
            all : [],
        }
    }
    componentDidMount() {
        fetch('/resource').then(r => r.json()).then(r => {
            this.setState({
                breakdown : r.breakdown,
                ready : r.ready,
                all : r.all,
            });
        });

    }
    editBreakdown(x){
        const {code, kategori, keterangan} = x;
        $('#editBreakdown [name=log]').val(log);
        $('#editBreakdown [name=keterangan]').val(keterangan);
        $('#editBreakdown [name=kategori]').val(kategori);
        $('#editBreakdown [name=code]').val(code);
    }
    setLogReady(x){
        const {log, code, kategori, keterangan} = x;
        $('#tambahReady [name=log]').val(log);
        $('#tambahReady [name=keterangan]').val(keterangan);
        $('#tambahReady [name=kategori]').val(kategori);
        $('#tambahReady [name=code]').val(code);
    }
    print(){
        html2canvas(document.getElementById('table-report')).then(canvas=>{
            let doc = new jsPDF({orientation:'landscape'});
            let img = canvas.toDataURL("image/png");
            doc.addImage(img,'JPEG',10,10);
            doc.save('report.pdf');
        });
    }

    render() {
        const {breakdown, ready, all} = this.state;
        return <div>
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
                    <table className="table table-bordered my-3">
                        <thead className="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Keterangan</th>
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
                            <td>{data.breakdown}</td>
                            <td className="bg-danger">B/D</td>
                            <td className={data.kategori==="SCH"?'bg-info':'bg-secondary'}>{data.kategori}</td>
                            <td>
                                <button className="btn btn-warning mx-1" data-toggle="modal" data-target="#editBreakdown" onClick={this.editBreakdown.bind(this,{log:data.id, kategori:data.kategori, keterangan:data.keterangan, code:data.unit.code})}>
                                    <i className="fa fa-pencil"> </i> Edit
                                </button>
                                <button className="btn btn-success mx-1" data-toggle="modal" data-target="#tambahReady" onClick={this.setLogReady.bind(this,{log:data.id, kategori:data.kategori, keterangan:data.keterangan, code:data.unit.code})}>
                                    <i className="fa fa-check"> </i> Ready
                                </button>
                            </td>
                        </tr>))}
                        {!breakdown.length?<tr><td className="text-center" colSpan={7}>Saat ini kosong</td></tr>:''}
                        </tbody>
                    </table>
                </div>
                <div className="tab-pane fade py-3" id="ready" role="tabpanel" aria-labelledby="ready-tab">
                    {/*<button data-toggle="modal" data-target="#tambahReady" className="btn btn-primary my-3">Tambah Ready</button>*/}
                    <table className="table table-bordered my-3">
                        <thead className="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
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
                            <td>{data.ready}</td>
                            <td className="bg-success">ready</td>
                            <td>{data.kategori}</td>
                            {/*<td>*/}
                            {/*    /!*<button className="btn btn-warning">Edit</button>*!/*/}
                            {/*    <button className="btn btn-success">Ready</button>*/}
                            {/*</td>*/}
                        </tr>))}
                        {!ready.length?<tr><td className="text-center" colSpan={6}>Saat ini kosong</td></tr>:''}
                        </tbody>
                    </table>
                </div>
                <div className="tab-pane fade py-3" id="report" role="tabpanel" aria-labelledby="report-tab">
                    <button onClick={this.print.bind(this)}>
                        <i className="fa fa-print"> </i> Print
                    </button>
                    <table className="table table-bordered my-3" id="table-report">
                        <thead className="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
                            <th>Jam</th>
                            <th>Status</th>
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
                            <td>{data.breakdown}</td>
                            <td className="bg-danger">B/D</td>
                            <td>{data.ready}</td>
                            <td className="bg-success">ready</td>
                            <td>{data.kategori}</td>
                            {/*<td>*/}
                            {/*    /!*<button className="btn btn-warning">Edit</button>*!/*/}
                            {/*    <button className="btn btn-success">Ready</button>*/}
                            {/*</td>*/}
                        </tr>))}
                        {!all.length?<tr><td className="text-center" colSpan={8}>Saat ini kosong</td></tr>:''}
                        </tbody>
                    </table>
                </div>
            </div>


        </div>;
    }
}

export default Main;

ReactDOM.render(<Main/>,document.getElementById('main'));