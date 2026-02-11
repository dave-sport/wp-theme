#!/bin/bash
# Fetch category structures from all deployed sites

SITES=(
  "readarsenal.com"
  "readastonvilla.com"
  "readchelsea.com"
  "readcrystalpalace.com"
  "readmancity.com"
  "readrealmadrid.com"
  "readsunderland.com"
  "readtottenham.com"
  "readwestham.com"
)

SSH_HOST="ssh.gb.stackcp.com"

for site in "${SITES[@]}"; do
  echo ""
  echo "========================================="
  echo "🔍 Fetching categories from $site"
  echo "========================================="
  
  ssh "${site}@${SSH_HOST}" "wp-cli.phar --path=public_html term list category --fields=term_id,name,slug,parent,count --format=json" 2>/dev/null
  
  if [ $? -eq 0 ]; then
    echo "✅ Success"
  else
    echo "❌ Failed to fetch from $site"
  fi
done
