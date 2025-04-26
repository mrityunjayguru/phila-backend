
//  const db = require('../database');
 
//  const UpdateLocation = async (response) => {  
//     const final = response.data;
//     final.map(async(res)=>{
        
//         var last_visited_stop = await getNearestStop(res.latitude, res.longitude);
//         if(last_visited_stop != false){
//             db.query(
//                 `UPDATE buses SET latitude = ? , longitude = ?, live_status = ?, last_visited_stop = ? WHERE device_id = ?`,
//                 [res.latitude,res.longitude,res.status,last_visited_stop,res.deviceId],
//                 (error,results,fields)=>{              
//                     if(error){
//                         console.log(error);
//                     }
//                     console.log('Updated with stop ID');
                   
//                 }
//             )
//         }else{
//             if(res.latitude != '' && res.longitude != '') {
//                 db.query(
//                     `UPDATE buses SET latitude = ? , longitude = ?, live_status = ? WHERE device_id = ?`,
//                     [res.latitude,res.longitude,res.status,res.deviceId],
//                     (error,results,fields)=>{              
//                         if(error){
//                             console.log(error);
//                         }
//                         console.log('Updated without stop ID');
                       
//                     }
//                 )
//             }
//         }
//     })
// }

// getNearestStop = (latitude, longitude) => {
//   return new Promise(async (resolve, reject) => {
//     db.query(`SELECT priority,(6371 * acos(cos(radians(?)) * cos(radians(stops.latitude)) * cos(radians(stops.longitude) - radians(?)) + sin(radians(?)) * sin(radians(stops.latitude)))) AS distance FROM stops HAVING distance < 0.2 ORDER BY distance LIMIT 0, 1`,
//             [latitude,longitude,latitude],
//             (error,results,fields)=>{              
//                 if(error){
//                     resolve(false);
//                 }
//                 if (results && results.length) {
//                     last_visited_stop = results[0].priority;
// 					resolve(last_visited_stop);
//                 }else{
// 					resolve(false);
// 				}
//             }
//         )
//   });
// }


// module.exports = UpdateLocation


const db = require('../database');

const UpdateLocation = async (response) => {  
    const final = response.data;

    // Check if final is defined and is an array
    if (!final || !Array.isArray(final)) {
        console.error("response.data is either undefined or not an array");
        return;
    }

    final.map(async (res) => {
        var last_visited_stop = await getNearestStop(res.latitude, res.longitude);
        if (last_visited_stop != false) {
            db.query(
                `UPDATE buses SET latitude = ?, longitude = ?, live_status = ?, last_visited_stop = ? WHERE device_id = ?`,
                [res.latitude, res.longitude, res.status, last_visited_stop, res.deviceId],
                (error, results, fields) => {
                    if (error) {
                        console.log(error);
                    }
                    console.log('Updated with stop ID');
                }
            );
        } else {
            if (res.latitude != '' && res.longitude != '') {
                db.query(
                    `UPDATE buses SET latitude = ?, longitude = ?, live_status = ? WHERE device_id = ?`,
                    [res.latitude, res.longitude, res.status, res.deviceId],
                    (error, results, fields) => {
                        if (error) {
                            console.log(error);
                        }
                        console.log('Updated without stop ID');
                    }
                );
            }
        }
    });
}

getNearestStop = (latitude, longitude) => {
    return new Promise(async (resolve, reject) => {
        db.query(`SELECT priority, (6371 * acos(cos(radians(?)) * cos(radians(stops.latitude)) * cos(radians(stops.longitude) - radians(?)) + sin(radians(?)) * sin(radians(stops.latitude)))) AS distance FROM stops HAVING distance < 0.2 ORDER BY distance LIMIT 0, 1`,
            [latitude, longitude, latitude],
            (error, results, fields) => {
                if (error) {
                    resolve(false);
                }
                if (results && results.length) {
                    last_visited_stop = results[0].priority;
                    resolve(last_visited_stop);
                } else {
                    resolve(false);
                }
            }
        );
    });
}

module.exports = UpdateLocation;


