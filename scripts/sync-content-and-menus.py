#!/usr/bin/env python3
"""
Sync page content and menus from readmanutd.com to target sites.
Batches commands to be faster.
"""

import subprocess
import sys
import os
import json

SSH_HOST = "ssh.gb.stackcp.com"
SOURCE = "readmanutd.com"

SITES = {
    "readtottenham": {
        "name": "Tottenham",
        "full_name": "Tottenham Hotspur F.C.",
        "domain": "readtottenham.com",
        "stadium": "Tottenham Hotspur Stadium",
        "official": "https://www.tottenhamhotspur.com",
    },
    "readnorwich": {
        "name": "Norwich",
        "full_name": "Norwich City F.C.",
        "domain": "readnorwich.com",
        "stadium": "Carrow Road",
        "official": "https://www.canaries.co.uk",
    },
    "readastonvilla": {
        "name": "Aston Villa",
        "full_name": "Aston Villa F.C.",
        "domain": "readastonvilla.com",
        "stadium": "Villa Park",
        "official": "https://www.avfc.co.uk",
    },
    "readcrystalpalace": {
        "name": "Crystal Palace",
        "full_name": "Crystal Palace F.C.",
        "domain": "readcrystalpalace.com",
        "stadium": "Selhurst Park",
        "official": "https://www.cpfc.co.uk",
    },
}

PAGES_WITH_CONTENT = [
    "about", "ownership-info", "diversity-policy", "ethics-policy",
    "cookies-policy", "corrections-policy", "editorial-policy", "terms-conditions"
]

ALL_PAGES = {
    "about": "About Read {full_name}",
    "contact-us": "Contact Read {name}",
    "jobs": "Jobs at Read {full_name}",
    "team": "Team at Read {full_name}",
    "diversity-policy": "Diversity Policy for Read {full_name}",
    "corrections-policy": "Corrections Policy for Read {full_name}",
    "editorial-policy": "Editorial Policy for Read {full_name}",
    "ethics-policy": "Ethics Policy for Read {full_name}",
    "terms-conditions": "Terms and Conditions — Read {full_name}",
    "cookies-policy": "Cookies Policy for Read {full_name}",
    "ownership-info": "Ownership & Transparency — Read {full_name}",
}

COPYRIGHT_ITEMS = ["about", "jobs", "team", "contact-us"]
COPYRIGHT_TITLES = {
    "about": "About Read {name}",
    "jobs": "Jobs at Read {name}",
    "team": "Team at Read {name}",
    "contact-us": "Contact Read {name}",
}

PRIVACY_ITEMS = ["diversity-policy", "corrections-policy", "editorial-policy", "terms-conditions", "cookies-policy", "ownership-info"]
PRIVACY_TITLES = {
    "diversity-policy": "Diversity Policy",
    "corrections-policy": "Corrections Policy",
    "editorial-policy": "Editorial Policy",
    "terms-conditions": "Terms and Conditions",
    "cookies-policy": "Cookies Policy",
    "ownership-info": "Ownership & Transparency",
}

NAV_CATEGORIES = [
    ("news", "Latest News"),
    ("match-reports", "Match Reports"),
    ("match-previews", "Match Previews"),
    ("transfer-news", "Transfers"),
    ("player-ratings", "Player Ratings"),
    ("features", "Features"),
    ("specials", "Specials"),
]

NEWS_CATEGORIES = [
    ("news", "News"),
    ("articles", "Articles"),
    ("first-team", "Match Day"),
    ("exclusives", "Exclusives"),
    ("interviews", "Interviews"),
]


def ssh_cmd(domain, cmd, timeout=30):
    full_cmd = f'ssh {domain}@{SSH_HOST} "wp --path=public_html {cmd}"'
    result = subprocess.run(full_cmd, shell=True, capture_output=True, text=True, timeout=timeout)
    return result.stdout.strip() if result.returncode == 0 else ""


def replace_content(content, site):
    if not content: return ""
    replacements = [
        ("Read Manchester United F.C.", f"Read {site['full_name']}"),
        ("Read Manchester United", f"Read {site['name']}"),
        ("Read Man Utd", f"Read {site['name']}"),
        ("Manchester United F.C.", site['full_name']),
        ("Manchester United", site['name']),
        ("Man Utd", site['name']),
        ("Old Trafford", site['stadium']),
        ("readmanutd.com", site['domain']),
        ("www.readmanutd.com", f"www.{site['domain']}"),
        ("https://readmanutd.com", f"https://{site['domain']}"),
        ("https://www.manutd.com", site['official']),
    ]
    result = content
    for old, new in replacements:
        result = result.replace(old, new)
    return result


def export_source_pages():
    print("📥 Exporting pages from readmanutd.com...")
    pages = {}
    for slug in PAGES_WITH_CONTENT:
        content = ssh_cmd(SOURCE, f"post list --post_type=page --name={slug} --field=post_content", timeout=15)
        pages[slug] = content or ""
        print(f"  Exported {slug} ({len(pages[slug])} chars)")
    return pages


def generate_remote_script(site, page_templates):
    domain = site['domain']
    name = site['name']
    full_name = site['full_name']
    
    script = [
        "#!/bin/bash",
        "WP=\"wp --path=public_html\"",
        "echo \"Starting sync for " + domain + "...\"",
    ]
    
    # 1. Update/Create Pages with content
    for slug in PAGES_WITH_CONTENT:
        content = page_templates.get(slug, "")
        if not content: continue
        
        new_content = replace_content(content, site)
        title = ALL_PAGES.get(slug, slug).format(name=name, full_name=full_name)
        
        # Write content to local file first
        script.append(f"echo \"  - Processing page: {slug}...\"")
        script.append(f"cat > /tmp/page-{slug}.html <<'EOF'\n{new_content}\nEOF")
        
        script.append(f"ID=$($WP post list --post_type=page --name={slug} --fields=ID --format=csv | tail -1)")
        script.append("if [ \"$ID\" != \"\" ] && [ \"$ID\" != \"ID\" ]; then")
        script.append(f"  $WP post update $ID --post_content=\"$(cat /tmp/page-{slug}.html)\" --post_title=\"{title}\" --quiet")
        script.append("else")
        script.append(f"  $WP post create --post_type=page --post_title=\"{title}\" --post_name={slug} --post_status=publish --post_content=\"$(cat /tmp/page-{slug}.html)\" --quiet")
        script.append("fi")
        script.append(f"rm /tmp/page-{slug}.html")

    # 2. Fix other titles
    for slug, title_template in ALL_PAGES.items():
        if slug in PAGES_WITH_CONTENT: continue
        title = title_template.format(name=name, full_name=full_name)
        script.append(f"ID=$($WP post list --post_type=page --name={slug} --fields=ID --format=csv | tail -1)")
        script.append("if [ \"$ID\" != \"\" ] && [ \"$ID\" != \"ID\" ]; then")
        script.append(f"  $WP post update $ID --post_title=\"{title}\" --quiet")
        script.append("fi")
    
    # 3. Setup Menus
    script.append("echo \"  - Setting up menus...\"")
    for menu in ['copyright', 'navigation', 'news-menu', 'privacy']:
        script.append(f"$WP menu delete {menu} --quiet 2>/dev/null")
    
    # Copyright
    script.append("$WP menu create 'Copyright'")
    for slug in COPYRIGHT_ITEMS:
        title = COPYRIGHT_TITLES[slug].format(name=name)
        script.append(f"ID=$($WP post list --post_type=page --name={slug} --fields=ID --format=csv | tail -1)")
        script.append(f"if [ \"$ID\" != \"\" ] && [ \"$ID\" != \"ID\" ]; then $WP menu item add-post copyright $ID --title=\"{title}\"; fi")
    
    # Privacy
    script.append("$WP menu create 'Privacy'")
    for slug in PRIVACY_ITEMS:
        title = PRIVACY_TITLES[slug]
        script.append(f"ID=$($WP post list --post_type=page --name={slug} --fields=ID --format=csv | tail -1)")
        script.append(f"if [ \"$ID\" != \"\" ] && [ \"$ID\" != \"ID\" ]; then $WP menu item add-post privacy $ID --title=\"{title}\"; fi")
        
    # Navigation
    script.append("$WP menu create 'Navigation'")
    for cat_slug, title in NAV_CATEGORIES:
        script.append(f"ID=$($WP term list category --slug={cat_slug} --fields=term_id --format=csv | tail -1)")
        script.append(f"if [ \"$ID\" != \"\" ] && [ \"$ID\" != \"term_id\" ]; then $WP menu item add-term navigation category $ID --title=\"{title}\"; fi")
        
    # News Menu
    script.append("$WP menu create 'News Menu'")
    for cat_slug, title in NEWS_CATEGORIES:
        script.append(f"ID=$($WP term list category --slug={cat_slug} --fields=term_id --format=csv | tail -1)")
        script.append(f"if [ \"$ID\" != \"\" ] && [ \"$ID\" != \"term_id\" ]; then $WP menu item add-term news-menu category $ID --title=\"{title}\"; fi")
        
    script.append("echo \"Done!\"")
    return "\n".join(script)


def main():
    targets = sys.argv[1:] if len(sys.argv) > 1 else ["readtottenham", "readnorwich", "readastonvilla", "readcrystalpalace"]
    page_templates = export_source_pages()
    
    for site_key in targets:
        site = SITES[site_key]
        print(f"\n🚀 Syncing {site_key}...")
        remote_script = generate_remote_script(site, page_templates)
        
        # Pipe script to SSH
        process = subprocess.Popen(
            f'ssh {site["domain"]}@{SSH_HOST} "bash"',
            shell=True, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True
        )
        stdout, stderr = process.communicate(input=remote_script)
        print(stdout)
        if stderr: print(f"Errors:\n{stderr}")


if __name__ == '__main__':
    main()
