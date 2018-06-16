var https = require('https');

// The IP to check
// var ip = '50.63.202.22';
var ip = '27.219.74.203'

// Authorization token
var authToken = '4b737a5da554f5ef92cd03abf753c94b86767e5d';
 
var options = {
  host: 'api.monapi.io',
  port: 443,
  path: '/api/v1/checkip/' + ip,
  headers: {
  }
};

if (authToken) {
   options.headers['Authorization'] = 'Token ' + authToken;
}

var request = https.get(options, function(res) {
   var body = "";
   res.on('data', function(data) {
      body += data;
   });
   res.on('end', function() {
     // Check response by status
     switch (res.statusCode) {
       case 200:
         console.log('HTTP Status OK, 200');
         console.log('Response DATA', body);
         break;
       case 404:
         console.log('Resource not found, 404');
         console.log('Response DATA', body);
         break;
       default:
         console.log('Unknown HTTP Status Code', res.statusCode);
         break;
     }
   })
   res.on('error', function(e) {
      console.log("Got HTTP Response error: " + e.message);
   });
});

request.on('error', (e) => {
  console.error('Request error:', e);
});

request.end();