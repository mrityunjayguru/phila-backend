const { getlocation, UpdateLocationModel } = require("../model/location.model");
const db = require('../database');





const location = (req, res) => {
  
    const status = req.params.status;
    getlocation(status,(err,result)=>{
        if(err){
            console.log(err);
            return;
        }       
        return    res.json({
            success:1,
            data : result,
            message : 'found '
        })
    })

}

const UpdateLocation = (response) => {  


    console.log(  response );

    return false;

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
           
        }
    )



    

}





module.exports = { 
    location ,
    UpdateLocation
};





