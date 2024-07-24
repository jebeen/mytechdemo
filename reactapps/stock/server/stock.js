const express = require('express')
const app=express()
const mongoose = require('mongoose')
const cors = require('cors')

const schema = mongoose.Schema({
    id: String,
    symbol : String,
    price : Number,
    capital: Number,
    high : Number,
    low: Number,
    lastrefresh : Date
},{
    timestamps: true
});

const model = mongoose.model('stock',schema);

function authMiddleware(req,res,next) {
    req.valid =0;
    if(req.method == 'POST') {
        if(req.header('authkey') == 'test') {
            req.valid = 1;
        }
    }
    next();
}
app.use(cors())
app.use(express.json())
app.use(authMiddleware)

app.post('/savestock', async (req,res) => {
    var sdata = [];
    let msg ='Error in processing ...';
    if(req.valid) {
        let data = new model(req.body);
        let exists = await model.find({id: req.body.id});
        if(exists.length) {
            let {id, ...rest} = req.body;
            let upd=await model.updateOne({'id' : id}, rest)
            if(upd) {
                msg = 'Stock updated ...';
            }
        } else {
            let result = await data.save()
            if(result) {
            msg ='Added successfully';
        }
        }
        sdata = await model.find({});
        res.json({status:1,message: msg, result : sdata});
    } else {
        res.json({status : 0, message: 'Invalid request'})
    }
})

app.post('/editstock', async (req,res) => {
    let {id, ...rest} = req.body;
    let data = await model.updateMany({symbol: id}, rest)
    
    if(data) {
        res.json({message: 'success', status: 1})
    } else {
        res.json({status:0})
    }
    
})
app.listen(5000, (req,res) => {
mongoose.connect('mongodb://127.0.0.1:27017/react')
  .then(() => {
    console.log('DB is connected ...');
    console.log('Server started');
  })
  .catch(err => console.log('No DB connection'))
})