---
title: monapi API Reference

language_tabs: # must be one of https://git.io/vQNgJ
  - shell
  # - ruby
  - python
  # - javascript

toc_footers:
  - <a href='http://eepurl.com/duq3j5'>Sign Up for Beta Test</a>
  - <a href='https://github.com/lord/slate'>Documentation Powered by Slate</a>

includes:
  - errors

search: true
---

# Introduction

Welcome to the monapi.io API! You can use our API to access our endpoints,
which provides risk, check and reputation based information for
digital assets like IP's, Domain's and E-Mail Addresses in our database.

We have language bindings in Shell and Python! You can view code
examples in the dark area to the right, and you can switch the programming
language of the examples with the tabs in the top right.

Our API is under active development, and we plan to release more functionality.
If there are specific features that you need, please [contact us](http://monapi.io/contact).


## Server Address

The base server address is: [https://api.monapi.io](https://api.monapi.io)

Please note that HTTPS is suggested. HTTP will be redirected to HTTPS with 301 Status Code. You will not be able to connect through unencrypted HTTP.


## Throttling

To improve our application's security and performance we rate limit access to
our API.
Our API will always be accessible for not authenticated Users.
Rate Limit is 10 Requests per Hour when not sending API Keys in your Headers
Request.
Request was throttled. Expected available in 3498.0 seconds."

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

If you feel restricted by these limits, please feel free to contact Pingdom support and request a higher limit. The limits are primarily here to protect the system from poorly coded applications, not to restrict you as a user.

## Authentication

> To authorize, use this code:

```python
import kittn

api = kittn.authorize('your_api_key')
```

```shell
# With shell, you can just pass the correct header with each request
curl "api_endpoint_here"
  -H "Authorization: your_api_key"
```

> Make sure to replace `your_api_key` with your API key.

You can access our API with or without API Keys.
If you want to try out our API you can do so immediately within our throttling
thresholds.

You can register for our public Beta Test a new monapi.io API key at our [developer portal](http://eepurl.com/duq3j5).

monapi.io expects for the API key to be included in all API requests to the server in a header that looks like the following for our authenticated users:

`Authorization: your_api_key`

<aside class="notice">
You can always use our API within the throttling thresholds for free!
</aside>


# IP

```python
from requests import get

loc = get('https://api.monapi.io/api/v1/checkip/8.8.8.8')
# print loc.json()  # python 2.x
print(loc.json())  # python 3.x
```

```shell
curl "https://api.monapi.io/api/v1/checkip/8.8.8.8"
```

> The above command returns JSON structured like this:

```json
{
    "list-count": 2,
    "ip": "8.8.8.8",
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

<aside class="warning">Inside HTML code blocks like this one, you can't use Markdown, so use <code>&lt;code&gt;</code> blocks to denote code.</aside>

### HTTP Request

`GET https://api.monapi.io/api/v1/checkdomain/<ip>`

### URL Parameters

Parameter | Description
--------- | -----------
IP | The IPv4 Address for which the check should be executed.


# Domain

```python
from urllib2 import Request, urlopen

request = Request("https://api.monapi.io/api/v1/checkdomain/{domain}")
request.add_header("Authorization", "your_api_key")
result = urlopen(request)

print result.read()
```

```shell
curl "https://api.monapi.io/api/v1/checkdomain/foobar.net" -H "Authorization: your_api_key" -H "Accept: application/json"
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

`GET https://api.monapi.io/api/v1/checkdomain/<domain>`

### URL Parameters

Parameter | Description
--------- | -----------
Domain | The Domain Address you wish to check against our database.

<aside class="success">
If Domin is not blacklisted you will get an 404 Error Message: not blacklisted": "nothing to see here"
</aside>

# E-Mail
coming soon

# Geolocation
coming soon

# ASN
coming soon
