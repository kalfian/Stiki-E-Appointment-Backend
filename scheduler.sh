#!/usr/bin/env bash

echo "Running the scheduler..."
while [ true ]
do
    (php artisan schedule:run --verbose --no-interaction &)
    sleep 60
done
