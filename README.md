# zsz2-webapp-api

## Endpoints

### **GET** 

##### All songs from specific day

`http://domainname.com/radio/getSongs.php`

GET parameters:
- "devicehash" - **required**
- "hash" - **required**
- "date" - **optional** - if not set the default is current day

###### EXAMPLE
``
```http://domainname.com/radio/getSongs.php?devicehash=YOURDEVICEHASH&hash=YOURLOGINHASH&date=2019-01-23```

##### RESPONSE

```
[
    {
        "id_song": "1",
        "url": "",
        "title": "Nirvana",
        "date": "2019-01-23",
        "autor": "Arek Pawlak"
    },
    {
        "id_song": "2",
        "url": "https://www.youtube.com/watch?v=7wtfhZwyrcc",
        "title": "Imagine Dragons Beliver",
        "date": "2019-01-10",
        "autor": "Annonyomous"
    }
]
```
### **POST**

##### Login User

`http://domainname.com/login.php`

POST body:
- "login" - **required**
- "password" - **required**
