if [ "$1" = "server" ]; then
    # Start the Laravel development server
    php artisan serve --host=127.0.0.1 --port=8000
elif [ "$1" = "queue" ]; then
    # Start the queue worker
    php artisan queue:work --daemon
else
    echo "Usage: $0 [server|queue]"
fi
