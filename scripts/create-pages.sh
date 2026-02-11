#!/bin/bash
# Create pages and menus for all sites
# Uses stdin for page content to avoid escaping issues

set -e

SSH_HOST="ssh.gb.stackcp.com"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Site slugs (excluding Man Utd)
SITES=(
  "readarsenal"
  "readastonvilla"
  "readchelsea"
  "readcrystalpalace"
  "readmancity"
  "readrealmadrid"
  "readsunderland"
  "readtottenham"
  "readwestham"
)

create_page() {
  local site=$1
  local slug=$2
  local title=$3
  local content_file=$4
  
  echo -n "  📄 $title... "
  
  # Check if page exists
  existing_id=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=${slug} --fields=ID --format=csv 2>/dev/null | tail -1")
  
  if [ -n "$existing_id" ] && [ "$existing_id" != "ID" ]; then
    # Update existing
    if [ -f "$content_file" ]; then
      ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post update ${existing_id}" < "$content_file" > /dev/null 2>&1
    fi
    echo "✅ Updated (ID $existing_id)"
    echo "$existing_id"
  else
    # Create new
    if [ -f "$content_file" ] && [ -s "$content_file" ]; then
      page_id=$(ssh "${site}.com@${SSH_HOST}" "cat > /tmp/page_content.txt && wp --path=public_html post create --post_type=page --post_title='${title}' --post_name=${slug} --post_status=publish --porcelain < /tmp/page_content.txt && rm /tmp/page_content.txt" < "$content_file" 2>/dev/null)
    else
      page_id=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post create --post_type=page --post_title='${title}' --post_name=${slug} --post_status=publish --porcelain" 2>/dev/null)
    fi
    
    if [ -n "$page_id" ]; then
      echo "✅ Created (ID $page_id)"
      echo "$page_id"
    else
      echo "❌ Failed"
      echo ""
    fi
  fi
}

echo "============================================================"
echo "This script needs Python helper - run generate-pages-and-menus.py"
echo "============================================================"
