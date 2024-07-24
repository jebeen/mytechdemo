const initialstate = {
    data : [],
    loading : true,
    error : false
}
const reducer =(state = initialstate,action) => {
    switch(action.type) {
        case 'savedata':
            let obj = Object.assign({},state,{data : action.payload});
            return obj;
        case 'setdata':
            localStorage.setItem('data', JSON.stringify(action.payload));
            break;
        case 'error':
            let err = Object.assign({},state, {'error' : action.payload})
            return err;
        case 'loading':
                let load = Object.assign({}, state, {loading: false})
                return load
        default:
            return state;
    }
}


export default reducer;