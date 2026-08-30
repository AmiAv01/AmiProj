#!/bin/bash
# Smoke tests for deployment verification

set -euo pipefail

echo "🧪 Running Smoke Tests"
echo ""

: "${BASE_URL:?BASE_URL must be set, for example https://example.com}"

# Test counter
PASSED=0
FAILED=0

# Test helper
test_endpoint() {
    local name=$1
    local method=$2
    local endpoint=$3
    local expected_status=$4
    
    echo -n "Testing $name... "
    
    RESPONSE=$(curl -sS -w "%{http_code}" -X "$method" "$BASE_URL$endpoint")
    STATUS_CODE=${RESPONSE: -3}
    
    if [ "$STATUS_CODE" = "$expected_status" ]; then
        echo "✓ ($STATUS_CODE)"
        PASSED=$((PASSED + 1))
    else
        echo "✗ (got $STATUS_CODE, expected $expected_status)"
        FAILED=$((FAILED + 1))
    fi
}

# Run tests
test_endpoint "Liveness" "GET" "/api/health/live" "200"
test_endpoint "Readiness" "GET" "/api/health/ready" "200"
test_endpoint "Home page" "GET" "/" "200"

echo ""
echo "Results: $PASSED passed, $FAILED failed"

if [ $FAILED -gt 0 ]; then
    echo "❌ Smoke tests failed"
    exit 1
fi

echo "✅ All smoke tests passed"
