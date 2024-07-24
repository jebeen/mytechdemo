import { Component } from 'react';
import {connect} from 'react-redux'
import * as action from '../actions';
import '../App.css';

const mapStateToProps = (state) => {
    return state;
}

const mapDispatchToProps = (dispatch) => {
    return {
        getdata : async() => {
            let url = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=100&page=1&sparkline=false';
            let response = await action.getdata(dispatch, url);
        },
        savestockdata : async(id, symbol,current_price,market_cap,high_24h,low_24h,price_change_24h,price_change_percentage_24h,last_updated) => {
            let surl = 'http://localhost:5000/savestock';
            let result = await action.savestockdata(dispatch,surl,id, symbol,current_price,market_cap,high_24h,low_24h,price_change_24h,price_change_percentage_24h,last_updated)
        },
        editstock: async(id,price) =>{
            let resp = await action.editstock(dispatch,id,price)
        }
    }
}

const TableHead = () => {
    return (
            <tr>
                <th>Id</th>
                <th>Symbol</th>
                <th>Price</th>
                <th>Market capital</th>
                <th>High(Last 24h)</th>
                <th>Low(Last 24h)</th>
                <th>Last refresh</th>
                <th>Action</th>
            </tr>
            )
}
const Display =({key1,data,cb}) => {
    const handleClick = (e) => {
        if(e.target.innerHTML =='Save') {
            let val = document.getElementById('price_'+e.target.getAttribute('data-id')).innerHTML;
            cb(e.target.getAttribute('data-id'),val)
        }
        document.getElementById('price_'+e.target.getAttribute('data-id')).setAttribute('contentEditable', true)
        e.target.innerHTML = 'Save';
    }
    return (
        <tr id={'row_'+data.symbol}>
            <td>{data.id}</td>
            <td>{data.symbol}</td>
            <td id={'price_'+data.symbol}>{data.price}</td>
            <td>{data.capital}</td>
            <td>{data.high}</td>
            <td>{data.low}</td>
            <td>{data.lastrefresh}</td>
            <td><button data-id={data.symbol} className='btn' onClick={(e)=>handleClick(e)}>Edit</button></td>
        </tr>
    )
}
class Stock extends Component {
    constructor(props) {
        super(props)
        
    }
    async fetchdata() {
       let a = await this.props.getdata()
    }
    async savestockdata(id, symbol,current_price,market_cap,high_24h,low_24h,last_updated) {
        let b = await this.props.savestockdata(id, symbol,current_price,market_cap,high_24h,low_24h,last_updated)
    }
    componentDidMount() {
        const locdata = JSON.parse(localStorage.getItem('data'));
        if(!locdata) {
            this.fetchdata();
        } else {
            for(let [i,j] of Object.entries(locdata)) {
                if( i <= 5) {
                    let {id, symbol,current_price,market_cap,high_24h,low_24h,last_updated} = j;
                    let lupdate = last_updated.split("T")[0];
                    this.savestockdata(id, symbol,current_price,market_cap,high_24h,low_24h,lupdate)
                }
            }
        }
    }
    render() {
        const {getdata,savestockdata,data,loading,error,editstock} = this.props;
        let i=0;
        const items = JSON.parse(localStorage.getItem('data'));
        return (
            {data} ? 
            <table id="table">
                <span id='resp'></span>
                <div id="result">{loading  ? <p>Loading ...</p> : ''}</div>
                {i ==0 ? <TableHead /> : ''}
                {
                    data.map((item)=> <Display key1={i++} data={item} cb={this.props.editstock}/>) 
                }
            </table>
            : <table><TableHead /><tr><td>No data</td></tr></table>
        )
    }
}
export default connect(mapStateToProps,mapDispatchToProps)(Stock);
