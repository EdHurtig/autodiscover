# autodiscover

Ping this app with your hostname to track your public IP

# App Setup

1. Create a mysql database and user.  
2. Clone this repo to your website root (or some subdir)
3. Copy `config.php.sample` to just `config.php` and fill in the fields
4. Test is out by hitting the root which should give you your IP address relative 
   to the server, and at hosts/ which should give you a list of all the servers registered

# Client Setup

Create the file `/etc/cron.d/autodiscover` with the following contents

```
* * * * * curl http://yourserver/hosts/?hostname=$(hostname)&auth=<your auth code>
```

# Usage

* Get your public IP: `GET http://server/`
* Get a JSON array of all clients reported `GET http://server/hosts/`
* Get a HOSTS file format for all clients reported `GET http://server/hosts/?format=json`
* Purge the database `DELETE http://server/hosts/`

