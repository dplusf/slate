---
title: monapi API Reference

language_tabs: # must be one of https://git.io/vQNgJ
  - shell
  - python
  - php
  - javascript

toc_footers:
  - <a href='http://eepurl.com/duq3j5'>Sign Up for Free!</a>
  - <a href='https://github.com/lord/slate'>Documentation Powered by Slate</a>

includes:
  - errors

search: true
---

# Introduction

Welcome to the monapi.io API! You can use our API to access our endpoints,
which provides risk, check and reputation based information for
digital assets like IP's, Domain's and E-Mail Addresses in our database.

We have language bindings in Shell, Python PHP and Javascript! You can view code
examples in the dark area to the right, and you can switch the programming
language of the examples with the tabs in the top right.

Our API is under active development, and we plan to release more functionality.
If there are specific features that you need, please [contact us](http://monapi.io/contact).


## Server Address

The base server address is: [https://api.monapi.io](https://api.monapi.io)

Please note that HTTPS is suggested. HTTP will be redirected to HTTPS with 301 Status Code. You will not be able to connect through unencrypted HTTP.


## Limits / Throttling

To avoid individual rampant applications degrading the overall user experience and to improve our application's security performance we rate limit access to our API.
If you hit the Rate Limit you will get following message:

`"Request was throttled. Expected available in 3498.0 seconds."`

The monapi API has usage limits to avoid individual rampant applications degrading the overall user experience. There are two layers of limits, the first cover a shorter period of time and the second a longer period. This enables you to "burst" requests for shorter periods. There are two HTTP headers in every response describing your limits status.

The response headers are:

Req-Limit-Short
Req-Limit-Long
An example of the values of these headers:

Req-Limit-Short: Remaining: 394 Time until reset: 3589
Req-Limit-Long: Remaining: 71994 Time until reset: 2591989
In this case, we can see that the user has 394 requests left until the short limit is reached. In 3589 seconds, the short limit will be reset. In similar fashion, the long limit has 71994 requests left, and will be reset in 2591989 seconds.

If you feel restricted by these limits, please feel free to contact monapi support and request a higher limit. The limits are primarily here to protect the system from poorly coded applications, not to restrict you as a user.

## Authentication

> To authorize, use this code:

```python
import requests
response = requests.get(
'https://api.monapi.io', headers={'Authorization': 'Token your_api_key'})
```

```shell
# With shell, you can just pass the correct header with each request
curl "https://api.monapi.io" -H "Authorization: Token your_api_key"
```

> For clients to authenticate, the token key should be included in the Authorization HTTP header. The key should be prefixed by the string literal "Token", with whitespace separating the two strings.

Our API will always be accessible for not authenticated Users.
You can access our API with or without API Keys.
If you want to try out our API you can do so immediately within our throttling
thresholds without sign up.

You can register for our public Alpha Test a new monapi.io API key at our [developer portal](http://eepurl.com/duq3j5).

monapi.io expects for the API key to be included in all API requests to the server in a header that looks like the following for our authenticated users:

`Authorization: Token your_api_key`

<aside class="notice">
You can always use our API within the throttling thresholds for free!
</aside>


# IP

> Example: Is IP 1.1.1.1 blacklisted?

```shell
curl "https://api.monapi.io/v1/checkip/1.1.1.1"
```

```python
import requests

url = "https://api.monapi.io/v1/checkip/{ip}"

headers = {
    'accept': "application/json",
    'authorization': "your_api_key"
    }

response = requests.request("GET", url, headers=headers)

print(response.text)
print(response.status_code)
```

```php
<?php

// IP to check
$ip = '1.1.1.1';

$authToken = 'your_api_key';

// Init cURL
$curl = curl_init();

// Set URL & some options
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.monapi.io/v1/checkip/' . $ip
));

// Set the authorization header
if ($authToken) {
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    	'Authorization: Token ' . $authToken
	));
}

// Send the request
$response = curl_exec($curl);

// Check for cURL & HTTP errors or return response content
if (curl_errno($curl)) {
  echo 'Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl) . "\n";
} else {
  switch ($httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
    case 200:  # OK
      echo 'OK' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;
    case 404:
      echo 'Resource not found' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;  
    default:
      echo 'Unexpected HTTP-Code: ' . $httpCode . "\n";
  }
}
// Close request to clear up some resources
curl_close($curl);
```

```javascript
var https = require('https');

// The IP to check
var ip = '1.1.1.1'

// Authorization token
var authToken = 'Token your_api_key';

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
```


> The above command returns JSON structured like this:

```json
{
    "list-count": 2,
    "ip": "1.1.1.1",
    "blacklists": {
        "COINBL_HOSTS": "organizations",
        "PACKETMAIL_EMERGING_IPS": "reputation"
    }
}
```

Returns Blacklists for a given IPv4 Address

The IP Check will test an IPv4 Address against our aggregated database with more than 100 Blacklists and threat intelligence datasources. We update our database up to 144 times per day, achieving near real time up to date data.
The Test Duration is fast and usually don't take longer than 100 milliseconds.

GET /IPv4/

<aside class="success">
If the IP is not blacklisted you will get an 404 Error Message: not blacklisted": "nothing to see here"
</aside>

### HTTP Request

`GET https://api.monapi.io/v1/checkip/<ip>`

### URL Parameters

Parameter | Description
--------- | -----------
IP | The IPv4 Address for which the check should be executed.


# Domain

```shell
curl "https://api.monapi.io/v1/checkdomain/foobar.net" \
-H "Authorization: Token your_api_key" -H "Accept: application/json"
```

```python
import requests

url = "https://api.monapi.io/v1/checkdomain/{domain}"

headers = {
    'accept': "application/json",
    'authorization': "your_api_key"
    }

response = requests.request("GET", url, headers=headers)

print(response.text)
print(response.status_code)
```



```javascript
var https = require('https');

// The Domain to check
var domain = 'google.com'

// Authorization token
var authToken = 'Token your_api_key';

var options = {
  host: 'api.monapi.io',
  port: 443,
  path: '/api/v1/checkdomain/' + domain,
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
```

```php
<?php

// Domain to check
$domain = '50.63.202.22';

$authToken = 'your_api_key';

// Init cURL
$curl = curl_init();

// Set URL & some options
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.monapi.io/v1/checkip/' . $domain
));

// Set the authorization header
if ($authToken) {
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    	'Authorization: Token ' . $authToken
	));
}

// Send the request
$response = curl_exec($curl);

// Check for cURL & HTTP errors or return response content
if (curl_errno($curl)) {
  echo 'Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl) . "\n";
} else {
  switch ($httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
    case 200:  # OK
      echo 'OK' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;
    case 404:
      echo 'Resource not found' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;  
    default:
      echo 'Unexpected HTTP-Code: ' . $httpCode . "\n";
  }
}
// Close request to clear up some resources
curl_close($curl);
```

> The above command returns JSON structured like this:

```json
[
  {
      "blacklist": {
          "COINBL_HOSTS_BROWSER": "organizations",
          "HPHOSTS_WRZ": "reputation",
          "RANSOMWARE_FEED": "malware",
          "HPHOSTS_MMT": "reputation",
          "THREATCROWD": "malware",
          "COINBL_HOSTS": "organizations",
          "HPHOSTS_PHA": "reputation",
          "HPHOSTS_GRM": "spam"
      },
      "ip": "69.172.201.153",
      "domain": "foobar.net",
      "mx_blacklist": false,
      "ns_blacklist": false
  }
]
```

Returns Blacklists for a given Domain

The Domain Check will test a Domain Adress against our aggregated database with more than 100 Blacklists and threat intelligence datasources. In addition we resolve the ip address of the domain as well as NS and MX Records and search our database for those additional IP addresses too.
The Test Duration is fast and usually don't take longer than 100 milliseconds.

### HTTP Request

`GET https://api.monapi.io/v1/checkdomain/<domain>`

### URL Parameters

Parameter | Description
--------- | -----------
Domain | The Domain Address you wish to check against our database.

<aside class="success">
If the Domain is not blacklisted you will get an 404 Error Message: not blacklisted": "nothing to see here"
</aside>

# E-Mail
coming soon

# Geolocation

> Example: get geolocation data for IP 1.1.1.1

```shell
curl "https://api.monapi.io/v1/geoip/1.1.1.1"
```

```python
import requests

url = "https://api.monapi.io/v1/geoip/{ip}"

headers = {
    'accept': "application/json",
    'authorization': "your_api_key"
    }

response = requests.request("GET", url, headers=headers)

print(response.text)
print(response.status_code)
```

```php
<?php

// IP to geolocate
$ip = '1.1.1.1';

$authToken = 'your_api_key';

// Init cURL
$curl = curl_init();

// Set URL & some options
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.monapi.io/v1/geoip/' . $ip
));

// Set the authorization header
if ($authToken) {
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    	'Authorization: Token ' . $authToken
	));
}

// Send the request
$response = curl_exec($curl);

// Check for cURL & HTTP errors or return response content
if (curl_errno($curl)) {
  echo 'Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl) . "\n";
} else {
  switch ($httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
    case 200:  # OK
      echo 'OK' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;
    case 404:
      echo 'Resource not found' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;  
    default:
      echo 'Unexpected HTTP-Code: ' . $httpCode . "\n";
  }
}
// Close request to clear up some resources
curl_close($curl);
```

```javascript
var https = require('https');

// The IP to geolocate
var ip = '1.1.1.1'

// Authorization token
var authToken = 'Token your_api_key';

var options = {
  host: 'api.monapi.io',
  port: 443,
  path: '/api/v1/geoip/' + ip,
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
```


> The above command returns JSON structured like this:

```json
{
    "city": "Research",
    "country": "Australia",
    "country_names": {
        "de": "Australien",
        "en": "Australia",
        "es": "Australia",
        "fr": "Australie",
        "ja": "\u30aa\u30fc\u30b9\u30c8\u30e9\u30ea\u30a2",
        "pt-BR": "Austr\u00e1lia",
        "ru": "\u0410\u0432\u0441\u0442\u0440\u0430\u043b\u0438\u044f",
        "zh-CN": "\u6fb3\u5927\u5229\u4e9a"
    },
    "ip": "1.1.1.1",
    "iso_code": "AU",
    "latitude": -37.7,
    "longitude": 145.1833,
    "postal": "3095"
}
```

Returns Geolocation data for a given IPv4 Address or Domain

You can supply an IP address
to lookup. The Response include all important data for your application like city, postal, country, iso_code, latitude and longitude. See the whole response with all returned data in the example to the right.


GET /IPv4/

<aside class="success">
If we dont have any geolocation data for that ip you will get an 404 Error Message: not found": "nothing to see here"
</aside>

### HTTP Request

`GET https://api.monapi.io/v1/geoip/<ip>`

### URL Parameters

Parameter | Description
--------- | -----------
IP | The IPv4 Address or Domain you want to geolocate.

# ASN

> Example: get ASN data for IP 1.1.1.1

```shell
curl "https://api.monapi.io/v1/asn/1.1.1.1"
```

```python
import requests

url = "https://api.monapi.io/v1/asn/{ip}"

headers = {
    'accept': "application/json",
    'authorization': "your_api_key"
    }

response = requests.request("GET", url, headers=headers)

print(response.text)
print(response.status_code)
```

```php
<?php

// IP you want ASN data for
$ip = '1.1.1.1';

$authToken = 'your_api_key';

// Init cURL
$curl = curl_init();

// Set URL & some options
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.monapi.io/v1/asn/' . $ip
));

// Set the authorization header
if ($authToken) {
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    	'Authorization: Token ' . $authToken
	));
}

// Send the request
$response = curl_exec($curl);

// Check for cURL & HTTP errors or return response content
if (curl_errno($curl)) {
  echo 'Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl) . "\n";
} else {
  switch ($httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
    case 200:  # OK
      echo 'OK' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;
    case 404:
      echo 'Resource not found' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;  
    default:
      echo 'Unexpected HTTP-Code: ' . $httpCode . "\n";
  }
}
// Close request to clear up some resources
curl_close($curl);
```

```javascript
var https = require('https');

// The IP you want ASN data for
var ip = '1.1.1.1'

// Authorization token
var authToken = 'Token your_api_key';

var options = {
  host: 'api.monapi.io',
  port: 443,
  path: '/api/v1/asn/' + ip,
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
```


> The above command returns JSON structured like this:

```json
{
    "ip": "1.1.1.1",
    "system_number": 13335,
    "system_organization": "Cloudflare Inc"
}
```

Returns autonomous system number (ASN) data for a given IPv4 Address

What is an Autonomous System (AS)?

An AS is a group of IP networks operated by one or more network operator(s) that has a single and clearly defined external routing policy.

GET /IPv4/

<aside class="success">
If we don't have any ASN data for that ip you will get an 404 Error Message: not found": "nothing to see here"
</aside>

### HTTP Request

`GET https://api.monapi.io/v1/asn/<ip>`

### URL Parameters

Parameter | Description
--------- | -----------
IP | The IPv4 Address you want ASN data for.
