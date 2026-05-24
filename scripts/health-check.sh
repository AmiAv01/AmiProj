#!/bin/bash
# Health check script

set -e

ENVIRONMENT=${1:-production}
API_URL=${API_URL:-http://localhost}
MAX_RETRIES=10

echo "🏥 Health Check for $ENVIRONMENT"
echo "URL: $API_URL"
echo ""

# 1. Liveness check
echo "1️⃣ Liveness check..."
for i in $(seq 1 $MAX_RETRIES); do
    if curl -sf "$API_URL/api/health/live" > /dev/null 2>&1; then
        RESPONSE=$(curl -s "$API_URL/api/health/live" | jq .)
        echo "✓ Liveness check passed"
        echo "$RESPONSE"
        break
    fi
    
    if [ $i -eq $MAX_RETRIES ]; then
        echo "❌ Liveness check failed after $MAX_RETRIES attempts"
        exit 1
    fi
    
    echo "Attempt $i/$MAX_RETRIES, retrying in 5s..."
    sleep 5
done

echo ""

# 2. Readiness check
echo "2️⃣ Readiness check..."
for i in $(seq 1 $MAX_RETRIES); do
    STATUS=$(curl -s "$API_URL/api/health/ready" | jq -r '.status')
    
    if [ "$STATUS" = "ready" ]; then
        RESPONSE=$(curl -s "$API_URL/api/health/ready" | jq .)
        echo "✓ Readiness check passed"
        echo "$RESPONSE"
        break
    fi
    
    if [ $i -eq $MAX_RETRIES ]; then
        echo "❌ Readiness check failed"
        echo "Checks:"
        curl -s "$API_URL/api/health/ready" | jq '.checks'
        exit 1
    fi
    
    echo "Attempt $i/$MAX_RETRIES, retrying in 5s..."
    sleep 5
done

echo ""
echo "✅ All health checks passed!"
