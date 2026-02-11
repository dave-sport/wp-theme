#!/usr/bin/env python3
"""
Fetch category structures from all deployed sites and update homepage.sections in JSON configs.
Intelligently picks the best parent categories for each site.
"""

import json
import subprocess
import sys
from pathlib import Path

SITES = [
    "readarsenal.com",
    "readastonvilla.com",
    "readchelsea.com",
    "readcrystalpalace.com",
    "readmancity.com",
    "readrealmadrid.com",
    "readsunderland.com",
    "readtottenham.com",
    "readwestham.com",
]

SSH_HOST = "ssh.gb.stackcp.com"

# Preferred parent category slugs (in priority order)
PREFERRED_PARENTS = [
    "first-team",
    "match-days", 
    "matchdays",
    "articles",
    "news",
    "features",
    "opinion",
    "analysis",
]

def fetch_categories(site):
    """Fetch categories from a WordPress site via SSH + wp-cli"""
    print(f"🔍 Fetching categories from {site}...", end=" ")
    sys.stdout.flush()
    
    cmd = [
        "ssh",
        f"{site}@{SSH_HOST}",
        "wp --path=public_html term list category --fields=term_id,name,slug,parent,count --format=json"
    ]
    
    try:
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=30)
        if result.returncode == 0:
            cats = json.loads(result.stdout)
            print(f"✅ Found {len(cats)} categories")
            return cats
        else:
            print(f"❌ Error: {result.stderr[:100]}")
            return None
    except Exception as e:
        print(f"❌ Exception: {str(e)[:100]}")
        return None

def pick_best_parents(categories, count=3):
    """
    Pick the best N parent categories for homepage sections.
    Prioritizes: PREFERRED_PARENTS order, then high post count, then alphabetical.
    """
    if not categories:
        return []
    
    # Filter to parent categories only (parent=0) and exclude Uncategorized
    parents = [
        c for c in categories 
        if c['parent'] == 0 and c['slug'] != 'uncategorized' and c['count'] > 0
    ]
    
    if not parents:
        return []
    
    # Score each parent: preferred slugs get priority, then count
    def score(cat):
        slug = cat['slug']
        if slug in PREFERRED_PARENTS:
            # Higher priority = lower index = better score
            pref_score = (len(PREFERRED_PARENTS) - PREFERRED_PARENTS.index(slug)) * 10000
        else:
            pref_score = 0
        return pref_score + cat['count']
    
    parents_sorted = sorted(parents, key=score, reverse=True)
    return parents_sorted[:count]

def update_site_config(site_slug, categories):
    """Update the site's JSON config with correct homepage sections"""
    config_file = Path(__file__).parent.parent / 'sites' / f'{site_slug}.json'
    
    if not config_file.exists():
        print(f"⚠️  Config file not found: {config_file}")
        return False
    
    with open(config_file, 'r') as f:
        config = json.load(f)
    
    best_parents = pick_best_parents(categories, count=3)
    
    if not best_parents:
        print(f"⚠️  No suitable parent categories found for {site_slug}")
        return False
    
    # Build sections array
    sections = []
    for parent in best_parents:
        sections.append({
            "parent_category_slug": parent['slug'],
            "subcategory_count": 3
        })
    
    config['homepage']['sections'] = sections
    
    # Write back
    with open(config_file, 'w') as f:
        json.dump(config, f, indent=2, ensure_ascii=False)
    
    print(f"✅ Updated {site_slug}.json:")
    for s in sections:
        parent_cat = next((c for c in categories if c['slug'] == s['parent_category_slug']), None)
        if parent_cat:
            print(f"   - {parent_cat['name']} ({parent_cat['slug']}) — {parent_cat['count']} posts")
    
    return True

def main():
    print("=" * 60)
    print("🏠 Homepage Sections Config Updater")
    print("=" * 60)
    print()
    
    for site_domain in SITES:
        site_slug = site_domain.replace('.com', '').replace('.', '')
        
        print(f"\n📍 {site_domain} ({site_slug})")
        print("-" * 60)
        
        categories = fetch_categories(site_domain)
        if categories:
            update_site_config(site_slug, categories)
        else:
            print(f"❌ Skipping {site_slug} due to fetch error")
        
        print()
    
    print("=" * 60)
    print("✅ Done! Review changes and commit.")
    print("=" * 60)

if __name__ == '__main__':
    main()
