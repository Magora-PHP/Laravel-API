server {
    server_name {{ nginx.servername }};

    listen 80;

    client_max_body_size 20M;

    root {{ nginx.docroot }};
    index index.php;

    server_name {{ nginx.servername }};

    {% if 'production' in group_names or 'production-slave' in group_names %}

    gzip on;
    gzip_comp_level 5;
    gzip_min_length 1024;
    gzip_types text/css text/plain text/json text/x-js text/javascript text/xml application/json application/x-javascript application/xml application/xml+rss application/javascript;


    {% endif %}


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

{% if 'staging' in group_names or 'vagrant' in group_names %}
    location ~ ^/(_debugbar|vendor|docs) {
        try_files $uri $uri/ /index.php?$query_string;
    }
{% endif %}

#nodejs frontend
#    location / {
#        proxy_pass       http://localhost:3000;
#        proxy_set_header Host      $host;
#        proxy_set_header X-Real-IP $remote_addr;
#    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        {% if 'vagrant' in group_names %}
        sendfile off;
        {% endif %}

        {% if 'staging' in group_names %}
        #auth_basic "Restricted";
        #auth_basic_user_file {{ httpAuth.filePath }};
        {% endif %}
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

{% if 'production' in group_names or 'production-slave' in group_names %}
#redirect to domain without www
#server {
#    server_name www.{{ nginx.servername }};
#    return 301  $schema://{{ nginx.servername }}$request_uri;
#}
{% endif %}
