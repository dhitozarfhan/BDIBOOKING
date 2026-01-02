#!/bin/bash

# BDIYK API Testing Script
# Usage: ./test_api.sh

# Configuration
BASE_URL="http://localhost:8000/api/v1"
TOKEN=""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "=========================================="
echo "BDIYK API Testing Script"
echo "=========================================="
echo ""

# Test 1: Get Slideshows
echo -e "${YELLOW}Test 1: Get Slideshows${NC}"
curl -s -X GET "${BASE_URL}/slideshows" \
  -H "Accept: application/json" | jq .
echo ""
echo "----------------------------------------"
echo ""

# Test 2: Get Categories
echo -e "${YELLOW}Test 2: Get Categories${NC}"
curl -s -X GET "${BASE_URL}/categories" \
  -H "Accept: application/json" | jq .
echo ""
echo "----------------------------------------"
echo ""

# Test 3: Get Articles
echo -e "${YELLOW}Test 3: Get Articles (News)${NC}"
curl -s -X GET "${BASE_URL}/articles?type=news&per_page=5" \
  -H "Accept: application/json" | jq .
echo ""
echo "----------------------------------------"
echo ""

# Test 4: Submit Question
echo -e "${YELLOW}Test 4: Submit Question${NC}"
QUESTION_RESPONSE=$(curl -s -X POST "${BASE_URL}/information/questions" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "phone": "081234567890",
    "identity_number": "1234567890123456",
    "address": "Test Address",
    "question": "This is a test question",
    "question_type": "general"
  }')

echo $QUESTION_RESPONSE | jq .
REGISTRATION_CODE=$(echo $QUESTION_RESPONSE | jq -r '.data.registration_code')
echo ""
echo "----------------------------------------"
echo ""

# Test 5: Check Question Status
if [ "$REGISTRATION_CODE" != "null" ] && [ -n "$REGISTRATION_CODE" ]; then
  echo -e "${YELLOW}Test 5: Check Question Status${NC}"
  curl -s -X GET "${BASE_URL}/information/questions/${REGISTRATION_CODE}" \
    -H "Accept: application/json" | jq .
  echo ""
  echo "----------------------------------------"
  echo ""
fi

# Test 6: Submit Information Request
echo -e "${YELLOW}Test 6: Submit Information Request${NC}"
REQUEST_RESPONSE=$(curl -s -X POST "${BASE_URL}/information/requests" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "phone": "081234567890",
    "identity_number": "1234567890123456",
    "address": "Test Address",
    "request_type": "document",
    "information_needed": "Test information needed",
    "purpose": "Testing purpose"
  }')

echo $REQUEST_RESPONSE | jq .
REQUEST_CODE=$(echo $REQUEST_RESPONSE | jq -r '.data.registration_code')
echo ""
echo "----------------------------------------"
echo ""

# Test 7: Check Information Request Status
if [ "$REQUEST_CODE" != "null" ] && [ -n "$REQUEST_CODE" ]; then
  echo -e "${YELLOW}Test 7: Check Information Request Status${NC}"
  curl -s -X GET "${BASE_URL}/information/requests/${REQUEST_CODE}" \
    -H "Accept: application/json" | jq .
  echo ""
  echo "----------------------------------------"
  echo ""
fi

# Test 8: Submit Gratification Report
echo -e "${YELLOW}Test 8: Submit Gratification Report${NC}"
GRAT_RESPONSE=$(curl -s -X POST "${BASE_URL}/gratification/reports" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reporter_name": "Test Reporter",
    "reporter_email": "reporter@example.com",
    "reporter_phone": "081234567890",
    "incident_date": "2025-01-01",
    "incident_location": "Test Location",
    "gratification_type": "money",
    "gratification_value": 1000000,
    "description": "Test gratification report"
  }')

echo $GRAT_RESPONSE | jq .
GRAT_CODE=$(echo $GRAT_RESPONSE | jq -r '.data.report_code')
echo ""
echo "----------------------------------------"
echo ""

# Test 9: Check Gratification Report Status
if [ "$GRAT_CODE" != "null" ] && [ -n "$GRAT_CODE" ]; then
  echo -e "${YELLOW}Test 9: Check Gratification Report Status${NC}"
  curl -s -X GET "${BASE_URL}/gratification/reports/${GRAT_CODE}" \
    -H "Accept: application/json" | jq .
  echo ""
  echo "----------------------------------------"
  echo ""
fi

# Test 10: Submit WBS Report
echo -e "${YELLOW}Test 10: Submit WBS Report${NC}"
WBS_RESPONSE=$(curl -s -X POST "${BASE_URL}/wbs/reports" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reporter_name": "Anonymous",
    "violation_type": "corruption",
    "violation_date": "2025-01-01",
    "violation_location": "Test Location",
    "perpetrator_name": "Test Perpetrator",
    "perpetrator_position": "Test Position",
    "description": "Test WBS report"
  }')

echo $WBS_RESPONSE | jq .
WBS_CODE=$(echo $WBS_RESPONSE | jq -r '.data.report_code')
echo ""
echo "----------------------------------------"
echo ""

# Test 11: Check WBS Report Status
if [ "$WBS_CODE" != "null" ] && [ -n "$WBS_CODE" ]; then
  echo -e "${YELLOW}Test 11: Check WBS Report Status${NC}"
  curl -s -X GET "${BASE_URL}/wbs/reports/${WBS_CODE}" \
    -H "Accept: application/json" | jq .
  echo ""
  echo "----------------------------------------"
  echo ""
fi

echo ""
echo -e "${GREEN}=========================================="
echo "Testing Complete!"
echo "==========================================${NC}"
echo ""
echo "Note: Login and authenticated endpoints require valid credentials."
echo "Please test those manually with actual user credentials."
echo ""
