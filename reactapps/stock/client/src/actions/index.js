

const getdata = async (dispatch,url) => {
    try {
        let resp = await fetch(url);
        let json = await resp.json();
        if(json) {
        dispatch({
            type: 'setdata', 
            payload: json
        })
        dispatch({
            type: 'loading',
            payload: false
        })
    }
    } catch(e) {
        dispatch({
            type: 'Error',
            payload: 'Error in processing request ...'
          })
    }
}

const editstock = async(dispatch, id, price) => {
    let option = {
        method:'post',
        headers: {
            'Content-Type' : 'application/json',
            'authkey' : 'test'
        },
        body: JSON.stringify({id: id, price: price})
    }
    await fetch('http://localhost:5000/editstock',option)
    .then(result=> result.json())
    .then(res=>{
        document.getElementById("resp").innerHTML = "updated";
        setTimeout (() => {
            window.location.reload()
        },1000)
    })
    .catch(err => document.getElementById("resp").innerHTML = "Error in processing")
}
const savestockdata =async(dispatch, url,id, symbol,current_price,market_cap,high_24h,low_24h,last_updated) => {
    let input_data = {
        id : id,
        symbol : symbol,
        price : current_price,
        capital: market_cap,
        high: high_24h,
        low: low_24h,
        lastrefresh : last_updated
    };
    
    let option = {
        body: JSON.stringify(input_data),
        headers : {
            'Content-Type' : 'application/json',        
            'authkey' : 'test'
        },
        method: 'POST'
    };
    try {
        let resp = await fetch(url,option)
        let jsonres = await resp.json()
        dispatch({
            type: 'loading',
            payload: true
        })
        if(jsonres.status) {
    
        dispatch({
            type: 'savedata',
            payload: jsonres.result
        })

        dispatch({
            type: 'loading',
            payload: false
        })

    } else {
        dispatch({
            type: 'error',
            payload: 'Error in processing'
        })
    }
    } catch(e) {
        dispatch({
            type: 'error',
            payload: 'Error in processing 123'
        })
    }
}

export {
    getdata,
    savestockdata,
    editstock
}