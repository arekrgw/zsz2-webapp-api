# zsz2-webapp-api

## Endpoints

### **GET** 

##### All songs from specific day

`http://domainname.com/radio/getSongs.php`

GET parameters:
- "devicehash" - required
- "hash" - **required**
- "date" - **optional** if not set the default is current day

###### EXAMPLE

> `http://domainname.com/radio/getSongs.php?devicehash=YOURDEVICEHASH&hash=YOURLOGINHASH&date=2019-01-23`
