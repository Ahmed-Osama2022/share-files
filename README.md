# Share-files
- Share files between client and server
- Can be used for sharing data between PC which work as a server and mobile phone

## NOTE:
<!-- - Nodejs is required in this project to make the bootstrap css & fontawesome work!! -->
<!-- - Project is made to work on port 8000, so make sure it's empty! -->
- composer is required in order to install the qr generator package!

### Requirements:
- Please make sure you have installed php-cli for this project in order to work
- php >= 8.2
- ```zip extention``` 
- ```gd extention```


### Download and install requirements
```bash
git clone https://github.com/Ahmed-Osama2022/share-files
cd share-files
composer install
```

### Instructions and tips
- Make sure you updated this values in your <strong>php.ini</strong> file!
- To be able to post larger files.
```conf
post_max_size = 2G
upload_max_filesize = 2G
memory_limit = 3G
```
- Make these values = ``` 5GB ``` if you need.

- Make sure if you are using apache2, to edit this .conf file in:
```bash
sudo nano /etc/apache2/apache2.conf
```
- And add the following here:
- This is an example for making "apache2 server" able to handle 2.7GB body size.

```conf
<Directory /var/www/>
        LimitRequestBody 2903040000
</Directory>
```
- Or make it ``` LimitRequestBody 5583457484 ```
- if you want ``` >= 5GB ``` 

- Then
```bash
sudo systemctl restart apache2
```

- Then make sure you updated the permission as followes <strong>If you are using apache2 server</strong>:

```bash
sudo chown -R www-data:$USER /var/www/html/share-files;
````
- If you wish to be able to edit the files later on...
```bash
sudo chmod -R g+w /var/www/html/share-files;
````

### Run:
- If you have node installed in your system, you can run using:
```bash
npm start
```
- Or using 

```bash
php -S 0.0.0.0:8000
```
--- 
- If you are using apache2 server, move the project to "your root docs folder", and make sure you started the apache2 server.

---
