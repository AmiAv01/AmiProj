#!/bin/bash

# MySQL initialization script
# Runs once on first container start

set -e

echo "Initializing MySQL database..."

# Check if database already initialized
if mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -e "SELECT 1" $MYSQL_DATABASE > /dev/null 2>&1; then
    echo "Database already initialized, skipping..."
    exit 0
fi

echo "Creating tables structure..."

# Note: Laravel migrations will handle table creation
# This script is for additional initialization if needed
