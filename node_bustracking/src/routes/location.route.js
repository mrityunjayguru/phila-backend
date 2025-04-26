const express = require('express');
const { location, UpdateLocation } = require('../controller/location.controller');

const router = express.Router();


router.get('/location/:status',location);
router.put('/location/:id',UpdateLocation);



module.exports = router;