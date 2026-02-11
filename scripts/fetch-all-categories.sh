#!/bin/bash
# Fetch categories from all sites in parallel

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
OUTPUT_DIR="/tmp/wp-categories"
mkdir -p "$OUTPUT_DIR"

for site in "${SITES[@]}"; do
  {
    echo "Fetching ${site}..."
    ssh "${site}.com@${SSH_HOST}" \
      "wp --path=public_html term list category --fields=term_id,name,slug,parent,count --format=json" \
      > "${OUTPUT_DIR}/${site}.json" 2>&1
    
    if [ $? -eq 0 ]; then
      echo "✅ ${site} done"
    else
      echo "❌ ${site} failed"
    fi
  } &
done

# Wait for all background jobs
wait

echo ""
echo "All fetches complete. Results in $OUTPUT_DIR/"
ls -lh "$OUTPUT_DIR"
