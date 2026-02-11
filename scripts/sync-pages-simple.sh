#!/bin/bash
# Simple page creator - creates empty pages, we'll add content later

SSH_HOST="ssh.gb.stackcp.com"

SITES=("readarsenal" "readastonvilla" "readchelsea" "readcrystalpalace" "readmancity" "readrealmadrid" "readsunderland" "readtottenham" "readwestham")

# Page definitions: slug|title_template
PAGES=(
  "about|About Read {TEAM}"
  "contact-us|Contact Read {TEAM}"
  "jobs|Jobs at Read {TEAM}"
  "team|Team at Read {TEAM}"
  "diversity-policy|Diversity Policy for Read {TEAM}"
  "corrections-policy|Corrections Policy for Read {TEAM}"
  "editorial-policy|Editorial Policy for Read {TEAM}"
  "terms-conditions|Terms and Conditions — Read {TEAM}"
  "cookies-policy|Cookies Policy for Read {TEAM}"
  "ownership-info|Ownership & Transparency — Read {TEAM}"
)

get_team_name() {
  case "$1" in
    readarsenal) echo "Arsenal" ;;
    readastonvilla) echo "Aston Villa" ;;
    readchelsea) echo "Chelsea" ;;
    readcrystalpalace) echo "Crystal Palace" ;;
    readmancity) echo "Man City" ;;
    readrealmadrid) echo "Real Madrid" ;;
    readsunderland) echo "Sunderland" ;;
    readtottenham) echo "Tottenham" ;;
    readwestham) echo "West Ham" ;;
    *) echo "Unknown" ;;
  esac
}

for site in "${SITES[@]}"; do
  team=$(get_team_name "$site")
  echo ""
  echo "============================================"
  echo "📍 $team ($site)"
  echo "============================================"
  
  for page_def in "${PAGES[@]}"; do
    slug="${page_def%%|*}"
    title_template="${page_def##*|}"
    title="${title_template//\{TEAM\}/$team}"
    
    echo -n "  Creating $title... "
    
    # Check if exists
    existing=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --fields=ID --format=csv 2>/dev/null | tail -1")
    
    if [ -n "$existing" ] && [ "$existing" != "ID" ]; then
      echo "✅ Exists (ID $existing)"
    else
      page_id=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post create --post_type=page --post_title='$title' --post_name=$slug --post_status=publish --porcelain" 2>&1)
      if [ $? -eq 0 ]; then
        echo "✅ Created (ID $page_id)"
      else
        echo "❌ Failed"
      fi
    fi
  done
  
  echo ""
  echo "  📋 Creating menus..."
  
  # Create Copyright menu
  ssh "${site}.com@${SSH_HOST}" "wp --path=public_html menu create 'Copyright' 2>/dev/null" > /dev/null
  ssh "${site}.com@${SSH_HOST}" "wp --path=public_html menu create 'Privacy' 2>/dev/null" > /dev/null
  
  # Add items to Copyright menu
  for slug in about jobs team contact-us; do
    page_id=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --fields=ID --format=csv 2>/dev/null | tail -1")
    if [ -n "$page_id" ] && [ "$page_id" != "ID" ]; then
      ssh "${site}.com@${SSH_HOST}" "wp --path=public_html menu item add-post copyright $page_id 2>/dev/null" > /dev/null
    fi
  done
  
  # Add items to Privacy menu
  for slug in diversity-policy corrections-policy editorial-policy terms-conditions cookies-policy ownership-info; do
    page_id=$(ssh "${site}.com@${SSH_HOST}" "wp --path=public_html post list --post_type=page --name=$slug --fields=ID --format=csv 2>/dev/null | tail -1")
    if [ -n "$page_id" ] && [ "$page_id" != "ID" ]; then
      ssh "${site}.com@${SSH_HOST}" "wp --path=public_html menu item add-post privacy $page_id 2>/dev/null" > /dev/null
    fi
  done
  
  echo "  ✅ Menus created"
done

echo ""
echo "============================================"
echo "✅ All sites complete!"
echo "============================================"
echo ""
echo "Note: Pages created with empty content."
echo "Content will be added in next step."
