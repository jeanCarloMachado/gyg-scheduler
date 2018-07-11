# A scheduler of activities for GYG



## Cli usage:

```sh
#run the mock server
make

# get your schedule
php main.php 10 '2018-11-17 00:00:00' '2018-11-19 23:59:59' '1000' | jb
```



## Assumptions:

An api endpoint for activities that filter's out:

 - city
 - date



## Mock api request example:


```sh
curl "http://localhost:8000/remoteServerMock.php?cityId=10&from=2018-01-01&to=2018-01-01" | jb 
```


