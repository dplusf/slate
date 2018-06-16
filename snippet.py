import requests

url = "https://api.monapi.io/api/v1/checkip/213.182.64.146"

headers = {
    'accept': "application/json",
    'authorization': "YOUR_API_KEY"
    }

response = requests.request("GET", url, headers=headers)

print(response.text)
print(response.read)
print(response.status_code)
