#!/bin/bash
# Sync page content and menus from readmanutd.com to target sites
# Usage: ./sync-content-and-menus.sh [site1] [site2] ...
# Default targets: readtottenham, readnorwich, readastonvilla, readcrystalpalace

set -euo pipefail

SSH_HOST="ssh.gb.stackcp.com"
SOURCE="readmanutd.com"
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Default targets
if [ $# -gt 0 ]; then
  TARGETS=("$@")
else
  TARGETS=("readtottenham" "readnorwich" "readastonvilla" "readcrystalpalace")
fi

# Team info mapping
declare -A TEAM_NAME TEAM_FULL TEAM_DOMAIN TEAM_STADIUM TEAM_OFFICIAL
TEAM_NAME[readtottenham]="Tottenham"
TEAM_FULL[readtottenham]="Tottenham Hotspur F.C."
TEAM_DOMAIN[readtottenham]="readtottenham.com"
TEAM_STADIUM[readtottenham]="Tottenham Hotspur Stadium"
TEAM_OFFICIAL[readtottenham]="https://www.tottenhamhotspur.com"

TEAM_NAME[readnorwich]="Norwich"
TEAM_FULL[readnorwich]="Norwich City F.C."
TEAM_DOMAIN[readnorwich]="readnorwich.com"
TEAM_STADIUM[readnorwich]="Carrow Road"
TEAM_OFFICIAL[readnorwich]="https://www.canaries.co.uk"

TEAM_NAME[readastonvilla]="Aston Villa"
TEAM_FULL[readastonvilla]="Aston Villa F.C."
TEAM_DOMAIN[readastonvilla]="readastonvilla.com"
TEAM_STADIUM[readastonvilla]="Villa Park"
TEAM_OFFICIAL[readastonvilla]="https://www.avfc.co.uk"

TEAM_NAME[readcrystalpalace]="Crystal Palace"
TEAM_FULL[readcrystalpalace]="Crystal Palace F.C."
TEAM_DOMAIN[readcrystalpalace]="readcrystalpalace.com"
TEAM_STADIUM[readcrystalpalace]="Selhurst Park"
TEAM_OFFICIAL[readcrystalpalace]="https://www.cpfc.co.uk"

# Export Man Utd pages
echo "📥 Exporting pages from readmanutd.com..."
PAGES_DIR="/tmp/manutd-page-content"
mkdir -p "$PAGES_DIR"

PAGES_WITH_CONTENT=("about" "ownership-info" "diversity-policy" "ethics-policy" "cookies-policy" "corrections-policy" "editorial-policy" "terms-conditions")

for slug in "${PAGES_WITH_CONTENT[@]}"; do
  echo "  Exporting $slug..."
  ssh "${SOURCE}@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --field=post_content" > "$PAGES_DIR/$slug.html" 2>/dev/null
  size=$(wc -c < "$PAGES_DIR/$slug.html" | tr -d ' ')
  echo "  ✅ $slug ($size bytes)"
done

echo ""

# Function to replace Man Utd references with target team
replace_content() {
  local content="$1"
  local site="$2"
  
  local name="${TEAM_NAME[$site]}"
  local full="${TEAM_FULL[$site]}"
  local domain="${TEAM_DOMAIN[$site]}"
  local stadium="${TEAM_STADIUM[$site]}"
  local official="${TEAM_OFFICIAL[$site]}"
  
  echo "$content" | sed \
    -e "s|Read Manchester United F\.C\.|Read ${full}|g" \
    -e "s|Read Man Utd|Read ${name}|g" \
    -e "s|Read ManUtd|Read ${name}|g" \
    -e "s|Manchester United F\.C\.|${full}|g" \
    -e "s|Manchester United|${name}|g" \
    -e "s|Man Utd|${name}|g" \
    -e "s|ManUtd|${name}|g" \
    -e "s|readmanutd\.com|${domain}|g" \
    -e "s|www\.readmanutd\.com|www.${domain}|g" \
    -e "s|https://readmanutd\.com|https://${domain}|g" \
    -e "s|https://www\.manutd\.com|${official}|g" \
    -e "s|Old Trafford|${stadium}|g"
}

# Process each target site
for site in "${TARGETS[@]}"; do
  name="${TEAM_NAME[$site]}"
  domain="${TEAM_DOMAIN[$site]}"
  full="${TEAM_FULL[$site]}"
  
  echo "============================================"
  echo "📍 $name ($domain)"
  echo "============================================"
  
  # --- UPDATE PAGE CONTENT ---
  echo ""
  echo "📄 Updating page content..."
  
  for slug in "${PAGES_WITH_CONTENT[@]}"; do
    template=$(cat "$PAGES_DIR/$slug.html")
    
    if [ -z "$template" ]; then
      echo "  ⏭️  $slug — no source content, skipping"
      continue
    fi
    
    # Replace content
    new_content=$(replace_content "$template" "$site")
    
    # Get page ID on target
    page_id=$(ssh "${domain}@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --fields=ID --format=csv 2>/dev/null | tail -1")
    
    if [ -n "$page_id" ] && [ "$page_id" != "ID" ]; then
      # Write content to temp file on remote and update
      echo "$new_content" | ssh "${domain}@${SSH_HOST}" "cat > /tmp/page-content-$slug.html && wp --path=public_html post update $page_id --post_content=\"\$(cat /tmp/page-content-$slug.html)\" --quiet 2>/dev/null && rm /tmp/page-content-$slug.html"
      echo "  ✅ $slug — updated (ID $page_id)"
    else
      echo "  ⚠️  $slug — page not found, creating..."
      title=$(echo "$slug" | sed 's/-/ /g' | sed 's/\b\(.\)/\u\1/g')
      echo "$new_content" | ssh "${domain}@${SSH_HOST}" "cat > /tmp/page-content-$slug.html && wp --path=public_html post create --post_type=page --post_title='$title' --post_name=$slug --post_status=publish --post_content=\"\$(cat /tmp/page-content-$slug.html)\" --porcelain 2>/dev/null && rm /tmp/page-content-$slug.html"
      echo "  ✅ $slug — created"
    fi
  done
  
  # --- FIX PAGE TITLES ---
  echo ""
  echo "📝 Fixing page titles..."
  
  declare -A PAGE_TITLES
  PAGE_TITLES[about]="About Read ${full}"
  PAGE_TITLES[contact-us]="Contact Read ${name}"
  PAGE_TITLES[jobs]="Jobs at Read ${full}"
  PAGE_TITLES[team]="Team at Read ${full}"
  PAGE_TITLES[diversity-policy]="Diversity Policy for Read ${full}"
  PAGE_TITLES[corrections-policy]="Corrections Policy for Read ${full}"
  PAGE_TITLES[editorial-policy]="Editorial Policy for Read ${full}"
  PAGE_TITLES[ethics-policy]="Ethics Policy for Read ${full}"
  PAGE_TITLES[terms-conditions]="Terms and Conditions — Read ${full}"
  PAGE_TITLES[cookies-policy]="Cookies Policy for Read ${full}"
  PAGE_TITLES[ownership-info]="Ownership & Transparency — Read ${full}"
  
  for slug in about contact-us jobs team diversity-policy corrections-policy editorial-policy ethics-policy terms-conditions cookies-policy ownership-info; do
    title="${PAGE_TITLES[$slug]:-}"
    if [ -z "$title" ]; then continue; fi
    
    page_id=$(ssh "${domain}@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --fields=ID --format=csv 2>/dev/null | tail -1")
    if [ -n "$page_id" ] && [ "$page_id" != "ID" ]; then
      ssh "${domain}@${SSH_HOST}" "wp --path=public_html post update $page_id --post_title='$title' --quiet 2>/dev/null"
      echo "  ✅ $slug → $title"
    fi
  done
  
  # --- SET UP MENUS ---
  echo ""
  echo "📋 Setting up menus..."
  
  # Delete existing menus and recreate (clean slate)
  for menu in copyright navigation news-menu privacy; do
    ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu delete $menu --quiet 2>/dev/null" || true
  done
  
  # Copyright menu
  ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu create 'Copyright' 2>/dev/null" || true
  for slug in about jobs team contact-us; do
    page_id=$(ssh "${domain}@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --fields=ID --format=csv 2>/dev/null | tail -1")
    if [ -n "$page_id" ] && [ "$page_id" != "ID" ]; then
      case $slug in
        about) menu_title="About Read ${name}" ;;
        jobs) menu_title="Jobs at Read ${name}" ;;
        team) menu_title="Team at Read ${name}" ;;
        contact-us) menu_title="Contact Read ${name}" ;;
      esac
      ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu item add-post copyright $page_id --title='$menu_title' 2>/dev/null"
    fi
  done
  echo "  ✅ Copyright menu"
  
  # Privacy menu
  ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu create 'Privacy' 2>/dev/null" || true
  for slug in diversity-policy corrections-policy editorial-policy terms-conditions cookies-policy ownership-info; do
    page_id=$(ssh "${domain}@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --fields=ID --format=csv 2>/dev/null | tail -1")
    if [ -n "$page_id" ] && [ "$page_id" != "ID" ]; then
      menu_title=$(echo "$slug" | sed 's/-/ /g' | sed 's/\b\(.\)/\u\1/g')
      [ "$slug" = "terms-conditions" ] && menu_title="Terms and Conditions"
      [ "$slug" = "ownership-info" ] && menu_title="Ownership & Transparency"
      ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu item add-post privacy $page_id --title='$menu_title' 2>/dev/null"
    fi
  done
  echo "  ✅ Privacy menu"
  
  # Navigation menu (category-based)
  ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu create 'Navigation' 2>/dev/null" || true
  for cat_slug in news match-reports match-previews transfer-news player-ratings features specials; do
    cat_id=$(ssh "${domain}@${SSH_HOST}" "wp --path=public_html term list category --slug=$cat_slug --fields=term_id --format=csv 2>/dev/null | tail -1")
    if [ -n "$cat_id" ] && [ "$cat_id" != "term_id" ]; then
      menu_title=$(echo "$cat_slug" | sed 's/-/ /g' | sed 's/\b\(.\)/\u\1/g')
      [ "$cat_slug" = "news" ] && menu_title="Latest News"
      [ "$cat_slug" = "transfer-news" ] && menu_title="Transfers"
      ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu item add-term navigation category $cat_id --title='$menu_title' 2>/dev/null"
    fi
  done
  echo "  ✅ Navigation menu"
  
  # News Menu (category-based)
  ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu create 'News Menu' 2>/dev/null" || true
  for cat_slug in news articles first-team exclusives interviews; do
    cat_id=$(ssh "${domain}@${SSH_HOST}" "wp --path=public_html term list category --slug=$cat_slug --fields=term_id --format=csv 2>/dev/null | tail -1")
    if [ -n "$cat_id" ] && [ "$cat_id" != "term_id" ]; then
      menu_title=$(echo "$cat_slug" | sed 's/-/ /g' | sed 's/\b\(.\)/\u\1/g')
      [ "$cat_slug" = "first-team" ] && menu_title="Match Day"
      ssh "${domain}@${SSH_HOST}" "wp --path=public_html menu item add-term news-menu category $cat_id --title='$menu_title' 2>/dev/null"
    fi
  done
  echo "  ✅ News Menu"
  
  echo ""
  echo "✅ $name complete!"
  echo ""
done

echo "============================================"
echo "✅ All sites synced!"
echo "============================================"
