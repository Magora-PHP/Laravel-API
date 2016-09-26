[program:mail-worker]
command=php {{ appDir }}/artisan mail-worker
numprocs=1
directory={{ appDir }}
stdout_logfile={{ appDir }}/storage/logs/mail-worker.log
stderr_logfile={{ appDir }}/storage/logs/mail-worker.log
autostart=true
autorestart=true
user=www-data
stopsignal=KILL

