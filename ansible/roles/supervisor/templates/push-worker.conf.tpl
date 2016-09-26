[program:push-worker]
command=php {{ appDir }}/artisan push-worker
numprocs=1
directory={{ appDir }}
stdout_logfile={{ appDir }}/storage/logs/push-worker.log
stderr_logfile={{ appDir }}/storage/logs/push-worker.log
autostart=true
autorestart=true
user=www-data
stopsignal=KILL

