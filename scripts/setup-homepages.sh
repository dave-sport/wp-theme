#!/bin/bash
# Setup homepage on all sites that need it

SITES=(
  "readastonvilla"
  "readcrystalpalace"
  "readmancity"
  "readrealmadrid"
  "readsunderland"
  "readtottenham"
  "readwestham"
)

SSH_HOST="ssh.gb.stackcp.com"

echo "========================================="
echo "Homepage Setup Script"
echo "========================================="
echo ""

# Fix Chelsea first (page exists, just needs template)
echo "🔧 Fixing Chelsea (page exists, adding template)..."
ssh readchelsea.com@${SSH_HOST} "wp --path=public_html post meta update 75295 _wp_page_template page-home.php"
if [ $? -eq 0 ]; then
  echo "✅ Chelsea template updated"
else
  echo "❌ Chelsea failed"
fi
echo ""

# Fix other sites (create page + set as front page)
for site in "${SITES[@]}"; do
  echo "🔧 Setting up ${site}..."
  
  # Check if a Home page already exists
  existing_id=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=home --fields=ID --format=csv 2>/dev/null | tail -1")
  
  if [ -n "$existing_id" ] && [ "$existing_id" != "ID" ]; then
    echo "   Page exists (ID ${existing_id}), updating template..."
    page_id=$existing_id
  else
    echo "   Creating new Home page..."
    page_id=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post create --post_type=page --post_title='Home' --post_status=publish --post_name=home --porcelain 2>&1")
    if [ $? -ne 0 ]; then
      echo "   ❌ Failed to create page"
      continue
    fi
    echo "   Created page ID: ${page_id}"
  fi
  
  # Set template
  ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post meta update ${page_id} _wp_page_template page-home.php 2>&1" > /dev/null
  
  # Set as front page
  ssh "${site}.com@${SSH_HOST}" "wp --path=public_html option update show_on_front page 2>&1" > /dev/null
  ssh "${site}.com@${SSH_HOST}" "wp --path=public_html option update page_on_front ${page_id} 2>&1" > /dev/null
  
  echo "   ✅ Done"
  echo ""
done

echo "========================================="
echo "✅ All sites configured!"
echo "========================================="
