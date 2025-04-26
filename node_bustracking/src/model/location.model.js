const db = require('../database');

const getlocation = (params,callback) =>{
    db.query(
        `select * from buses where status =?`,
        [params],
        (error,results,fields)=>{
            console.log(results);
            if(error){
                callback(error);
            }
           
            return callback(null,results);
        }
    )
    

}


const UpdateLocationModel = (res,callback) =>{

    const id = req.params.id;
    const latitude = req.body.latitude;
    const longitude = req.body.longitude;

    db.query(
        `UPDATE buses SET latitude = ? , longitude = ? WHERE id = ?`,
        [latitude,longitude,id],
        (error,results,fields)=>{
            console.log(results);
            if(error){
                callback(error);
            }
           
            return callback(null,results);
        }
    )
    

}


module.exports = {
    getlocation,
    UpdateLocationModel

}