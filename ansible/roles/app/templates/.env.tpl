APP_ENV={% if 'production' in group_names or 'production-slave' in group_names %}prod{% else %}dev{% endif %}

APP_DEBUG={% if 'production' in group_names or 'production-slave' in group_names %}false{% else %}true{% endif %}

APP_KEY=BDtUXRrXHehQCrgwkmZd9ezYpoU4TrjP

DB_CONNECTION=pgsql
DB_HOST={{ postgres.hostname|default('localhost') }}
DB_PORT=5432
DB_DATABASE={{ postgres.database }}
DB_USERNAME={{ postgres.user }}
DB_PASSWORD={{ postgres.password }}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST={{ mail.host }}
MAIL_PORT={{ mail.port }}
MAIL_USERNAME={{ mail.username }}
MAIL_PASSWORD={{ mail.password }}
MAIL_ENCRYPTION={{ mail.encryption }}

RABBITMQ_USER ={{rabbitmq.user}}
RABBITMQ_PASS ={{rabbitmq.password}}
RABBITMQ_HOST ={{rabbitmq.host}}

{% if 'production' in group_names or 'production-slave' in group_names %}
JWT_SECRET= {{ jwtSecret }}

UPLOADS=aws_s3
AWSAccessKeyId={{ aws.AWSAccessKeyId }}
AWSSecretKey={{ aws.AWSSecretKey }}
AWS_region={{ aws.region }}
AWS_version={{ aws.version }}
AWS_bucket={{ aws.bucket }}
{% endif %}

API_VERSION={{ api.version }}