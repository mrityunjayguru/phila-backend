const mysql =  require('mysql');
require('dotenv').config();

const connection = mysql.createConnection({
    host:process.env.DB_HOST,
    user :process.env.DB_USERNAME,
    password:process.env.DB_PASSWORD,
    database:process.env.DB_DATABASE,
    //database:process.env.DB_DATABASE,
})


connection.connect((err)=>{
    if(err) throw err;
    console.log('connection success');
}) 

module.exports = connection;