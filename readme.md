Nginx configuration
```
location / {
    try_files $uri $uri/ /index.php?$args
}
```

Tested on Linux with nginx and php-fpm, htaccess for apache is created, not tested yet.
Todo:
Sharing files between users
