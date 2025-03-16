#!/bin/sh

output=$(./vendor/bin/sail artisan passport:client --personal | tee /dev/tty)

client_id=$(echo "$output" | grep 'Client ID' | awk '{print $NF}')
client_secret=$(echo "$output" | grep 'Client secret' | awk '{print $NF}')

if [ -z "$client_id" ] || [ -z "$client_secret" ]; then
    echo "client_id: $client_id"
    echo "client_secret: $client_secret"
    echo "\n  ⚠️ Error: Failed to create personal client."
    exit 1
fi

sed -i '' -E "s|^PASSPORT_PERSONAL_ACCESS_CLIENT_ID=\"[^\"]*\"|PASSPORT_PERSONAL_ACCESS_CLIENT_ID=\"$client_id\"|" .env
sed -i '' -E "s|^PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=\"[^\"]*\"|PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=\"$client_secret\"|" .env

echo "\n  ✅️ Updated .env "
