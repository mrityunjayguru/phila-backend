const express = require('express')
const dotenv = require('dotenv')
const needle = require('needle');
const UpdateLocation = require('./src/service/location.service');
const helmet = require('helmet')


const app = express();
dotenv.config();
app.use(express.json());
app.use(helmet.contentSecurityPolicy());
app.use(helmet.crossOriginEmbedderPolicy());
app.use(helmet.crossOriginOpenerPolicy());
app.use(helmet.xssFilter());
// app.use(helmet.dnsPrefetchControl());
// app.use(helmet.expectCt());
// app.use(helmet.frameguard());
// app.use(helmet.hidePoweredBy());
// app.use(helmet.hsts());
// app.use(helmet.ieNoOpen());
// app.use(helmet.noSniff());
// app.use(helmet.originAgentCluster());
// app.use(helmet.permittedCrossDomainPolicies());
// app.use(helmet.referrerPolicy());




async function callme(){

  const networkPromise =  needle.get('https://libitrack.in/api/v1/index.php/getDevPos',
    {
        username: "worklooper",
        password: "User@123"
    }, (err, res) => {
        if (err) {
            console.error(err);
            return false;
        };        
        UpdateLocation(res.body);
    });

    var timeOutPromise = new Promise(function(resolve, reject) {
        // 2 Second delay
        setTimeout(resolve, 2000, 'Timeout Done');
      });    
      Promise.all(
      [networkPromise, timeOutPromise]).then(function(values) {
        callme();
      });    

}

callme();

app.get('/', (req, res) => {
    res.status(httpStatus.CREATED).send('you are not aurthorize to use this')
})

const PORT = process.env.PORT || 5784;
app.listen(PORT, () => {
    console.log(` Server is running on Port ${PORT}`)
})