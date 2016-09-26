{% if 'production' in group_names %}
server {
    server_name {{ nginx.servername }} www.{{ nginx.servername }};
    listen 80;
    return 301 https://{{ nginx.servername }}$request_uri;
}
{% endif %}

server {
    server_name {{ nginx.servername }};

{% if 'production' in group_names %}
    listen  443 ssl;

    ssl on;
    ssl_certificate {{ appDir }}/certificates/ssl_planmeapp.com/ssl-cert.pem;
    ssl_certificate_key {{ appDir }}/certificates/ssl_planmeapp.com/private-key-without-password.key;

    ssl_session_timeout 24h;
    ssl_session_cache shared:SSL:2m;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers kEECDH+AES128:kEECDH:kEDH:-3DES:kRSA+AES128:kEDH+3DES:DES-CBC3-SHA:!RC4:!aNULL:!eNULL:!MD5:!EXPORT:!LOW:!SEED:!CAMELLIA:!IDEA:!PSK:!SRP:!SSLv2;
    ssl_prefer_server_ciphers on;
    add_header Strict-Transport-Security "max-age=31536000;";
    add_header Content-Security-Policy-Report-Only "default-src https:; script-src https: 'unsafe-eval' 'unsafe-inline'; style-src https: 'unsafe-inline'; img-src https: data:; font-src https: data:; report-uri /csp-report";

{% else %}
    listen 80;
{% endif %}

    client_max_body_size 20M;

    root {{ nginx.docroot }};
    index index.php;

    server_name {{ nginx.servername }};

    {% if 'production' in group_names %}

    gzip on;
    gzip_comp_level 5;
    gzip_min_length 1024;
    gzip_types text/css text/plain text/json text/x-js text/javascript text/xml application/json application/x-javascript application/xml application/xml+rss application/javascript;


    {% endif %}

    location ~ ^/(css|js|fonts|images)/ {
        root /var/www/planme_site/src/public;
        error_log off;
        access_log off;
        expires 1w;
    }

    location ~ ^/(api|uploads)/ {
        try_files $uri $uri/ /index.php?$query_string;
{% if 'vagrant' in group_names %}
        sendfile off;
{% endif %}

{% if 'staging' in group_names %}
        #auth_basic "Restricted";
        #auth_basic_user_file {{ httpAuth.filePath }};
{% endif %}
    }

{% if 'staging' in group_names %}
    location ~ ^/(_debugbar|vendor|docs) {
        try_files $uri $uri/ /index.php?$query_string;
    }
{% endif %}

    location / {
        proxy_pass       http://localhost:3000;
        proxy_set_header Host      $host;
        proxy_set_header X-Real-IP $remote_addr;
    }

    error_page 404 /404.html;

    error_page 500 502 503 504 /50x.html;
        location = /50x.html {
        root /usr/share/nginx/www;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

