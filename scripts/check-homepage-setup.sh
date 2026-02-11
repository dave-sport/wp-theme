#!/bin/bash
# Check homepage setup on all sites

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

SSH_HOST="ssh.gb.stackcp.com"

echo "========================================="
echo "Homepage Setup Status"
echo "========================================="
echo ""

for site in "${SITES[@]}"; do
  echo "📍 ${site}:"
  
  show_on_front=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html option get show_on_front 2>/dev/null" | tr -d '\r\n')
  page_on_front=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html option get page_on_front 2>/dev/null" | tr -d '\r\n')
  
  if [ "$show_on_front" = "page" ] && [ "$page_on_front" != "0" ]; then
    page_title=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post get ${page_on_front} --field=post_title 2>/dev/null" | tr -d '\r\n')
    template=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post meta get ${page_on_front} _wp_page_template 2>/dev/null" | tr -d '\r\n')
    echo "  ✅ Static page: '${page_title}' (ID ${page_on_front})"
    echo "     Template: ${template}"
  else
    echo "  ❌ Shows blog posts (not using static homepage)"
  fi
  
  echo ""
done
