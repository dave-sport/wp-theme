#!/usr/bin/env python3
"""
Generate standard pages and footer menus for all deployed sites.
Uses Man Utd as the template and replaces team-specific content.
"""

import json
import subprocess
import sys
from pathlib import Path

SCRIPT_DIR = Path(__file__).parent
SITE_INFO_FILE = SCRIPT_DIR / 'site-info.json'
SSH_HOST = "ssh.gb.stackcp.com"

# Pages to create (name, slug, has_content)
PAGES = [
    ("about", "About Read {name}", True),
    ("contact-us", "Contact Read {name}", False),
    ("jobs", "Jobs at Read {name} F.C.", False),
    ("team", "Team at Read {name} F.C.", False),
    ("diversity-policy", "Diversity Policy for Read {name} F.C.", True),
    ("corrections-policy", "Corrections Policy for Read {name} F.C.", True),
    ("editorial-policy", "Editorial Policy for Read {name} F.C.", True),
    ("terms-conditions", "Terms and Conditions — Read {name} F.C.", True),
    ("cookies-policy", "Cookies Policy for Read {name} F.C.", True),
    ("ownership-info", "Ownership & Transparency — Read {name} F.C.", True),
]

def load_site_info():
    """Load site information from JSON"""
    with open(SITE_INFO_FILE, 'r') as f:
        return json.load(f)

def load_page_content(page_slug):
    """Load page content template from Man Utd export"""
    content_file = SCRIPT_DIR / f'templates/{page_slug}.html'
    if content_file.exists():
        with open(content_file, 'r') as f:
            return f.read()
    return ""

def replace_placeholders(content, site_info):
    """Replace team-specific placeholders in content"""
    if not content:
        return ""
    
    replacements = {
        'Manchester United': site_info['full_name'].replace(' F.C.', '').replace(' A.F.C.', '').replace(' C.F.', ''),
        'Man Utd': site_info['name'],
        'readmanutd.com': site_info['domain'],
        'www.readmanutd.com': f"www.{site_info['domain']}",
        'https://readmanutd.com': f"https://{site_info['domain']}",
        'https://www.manutd.com': site_info['official_club'],
        'Old Trafford': site_info['stadium'],
        'Read Man Utd': f"Read {site_info['name']}",
        'Read Manchester United F.C.': f"Read {site_info['full_name']}",
    }
    
    result = content
    for old, new in replacements.items():
        result = result.replace(old, new)
    
    return result

def wp_cli(site, command, capture=True):
    """Run wp-cli command on a site"""
    full_cmd = f'ssh {site}@{SSH_HOST} "wp --path=public_html {command}"'
    
    if capture:
        result = subprocess.run(full_cmd, shell=True, capture_output=True, text=True, timeout=30)
        return result.stdout.strip() if result.returncode == 0 else None
    else:
        result = subprocess.run(full_cmd, shell=True, timeout=30)
        return result.returncode == 0

def create_or_update_page(site, site_info, slug, title, content):
    """Create or update a page on a site"""
    print(f"  📄 {title}...", end=" ")
    
    # Check if page exists
    existing_id = wp_cli(site, f'post list --post_type=page --name={slug} --fields=ID --format=csv | tail -1')
    
    if existing_id and existing_id != 'ID':
        # Update existing
        escaped_content = content.replace('"', '\\"').replace('$', '\\$')
        success = wp_cli(site, f'post update {existing_id} --post_content="{escaped_content}"', capture=False)
        if success:
            print(f"✅ Updated (ID {existing_id})")
        else:
            print(f"❌ Failed to update")
        return existing_id
    else:
        # Create new
        escaped_content = content.replace('"', '\\"').replace('$', '\\$')
        page_id = wp_cli(site, f'post create --post_type=page --post_title="{title}" --post_name={slug} --post_status=publish --post_content="{escaped_content}" --porcelain')
        if page_id:
            print(f"✅ Created (ID {page_id})")
            return page_id
        else:
            print(f"❌ Failed to create")
            return None

def create_menu_if_missing(site, menu_name, menu_slug):
    """Create a menu if it doesn't exist"""
    existing = wp_cli(site, f'menu list --fields=slug --format=csv | grep "^{menu_slug}$"')
    if existing:
        return True
    
    success = wp_cli(site, f'menu create "{menu_name}" --porcelain', capture=False)
    return success

def add_menu_item(site, menu_slug, page_id, title):
    """Add a page to a menu"""
    wp_cli(site, f'menu item add-post {menu_slug} {page_id} --title="{title}"', capture=False)

def setup_site(site_slug, site_info, page_templates):
    """Set up pages and menus for a single site"""
    site_domain = f"{site_slug}.com"
    
    print(f"\n{'='*60}")
    print(f"📍 {site_info['name']} ({site_domain})")
    print(f"{'='*60}")
    
    # Create pages
    page_ids = {}
    for slug, title_template, has_content in PAGES:
        title = title_template.format(name=site_info['name'])
        content = ""
        
        if has_content and slug in page_templates:
            content = replace_placeholders(page_templates[slug], site_info)
        
        page_id = create_or_update_page(site_domain, site_info, slug, title, content)
        if page_id:
            page_ids[slug] = page_id
    
    # Create Copyright menu
    print(f"\n  📋 Creating Copyright menu...")
    if create_menu_if_missing(site_domain, "Copyright", "copyright"):
        for slug in ['about', 'jobs', 'team', 'contact-us']:
            if slug in page_ids:
                title = f"{slug.replace('-', ' ').title()} Read {site_info['name']}"
                add_menu_item(site_domain, "copyright", page_ids[slug], title)
        print("  ✅ Copyright menu created")
    
    # Create Privacy menu
    print(f"  📋 Creating Privacy menu...")
    if create_menu_if_missing(site_domain, "Privacy", "privacy"):
        for slug in ['diversity-policy', 'corrections-policy', 'editorial-policy', 'terms-conditions', 'cookies-policy', 'ownership-info']:
            if slug in page_ids:
                title = slug.replace('-', ' ').title()
                add_menu_item(site_domain, "privacy", page_ids[slug], title)
        print("  ✅ Privacy menu created")
    
    print(f"\n✅ {site_info['name']} complete!")

def main():
    """Main execution"""
    print("="*60)
    print("🏠 Pages & Footer Menus Generator")
    print("="*60)
    
    # Load site info
    sites = load_site_info()
    
    # Load page templates from Man Utd export
    print("\n📥 Loading page templates from /tmp/manutd-pages.json...")
    with open('/tmp/manutd-pages.json', 'r') as f:
        pages_data = json.load(f)
    
    page_templates = {p['post_name']: p['post_content'] for p in pages_data if p['post_content']}
    print(f"✅ Loaded {len(page_templates)} page templates")
    
    # Process each site (except Man Utd - it already has everything)
    deployed_sites = [
        'readarsenal', 'readastonvilla', 'readchelsea', 
        'readcrystalpalace', 'readmancity', 'readrealmadrid',
        'readsunderland', 'readtottenham', 'readwestham'
    ]
    
    for site_slug in deployed_sites:
        if site_slug in sites:
            setup_site(site_slug, sites[site_slug], page_templates)
    
    print("\n" + "="*60)
    print("✅ All sites configured!")
    print("="*60)

if __name__ == '__main__':
    main()
