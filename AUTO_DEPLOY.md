# GitHub Actions Auto-Deploy

## Overview

Automatically deploys theme updates to staging sites whenever code is pushed to the `main` branch of `moodco/wp-theme` repository.

## How It Works

1. Developer pushes changes to GitHub (main branch)
2. GitHub Actions triggers within seconds
3. SSH into each site and runs `bash ~/deploy.sh`
4. Deploy script pulls latest code and syncs to WordPress theme directory
5. Changes go live immediately (no manual SSH needed)

## Currently Auto-Deploying (6 sites)

- ✅ Aston Villa (`readastonvilla.com`)
- ✅ Crystal Palace (`readcrystalpalace.com`)
- ✅ Man City (`readmancity.com`)
- ✅ Real Madrid (`readrealmadrid.com`)
- ✅ Sunderland (`readsunderland.com`)
- ✅ Tottenham (`readtottenham.com`)

## Excluded from Auto-Deploy (3 sites)

- ❌ **Arsenal** (`readarsenal.com`) - manual changes that should not be overwritten
- ❌ **Chelsea** (`readchelsea.com`) - manual changes that should not be overwritten
- ❌ **West Ham** (`readwestham.com`) - manual changes that should not be overwritten

## Manual Deploy Process

For sites excluded from auto-deploy:

```bash
ssh readarsenal.com@ssh.gb.stackcp.com
bash ~/deploy.sh
```

(Replace `readarsenal.com` with `readchelsea.com` or `readwestham.com` as needed)

## Benefits

- **Zero-touch deployment** for most sites
- Changes go live in **~30 seconds**
- No need to SSH into 6 different servers
- **Parallel deployment** (all sites at once)
- Instant feedback via GitHub Actions UI

## Technical Details

**Configuration:**
- File: `.github/workflows/deploy.yml`
- Trigger: Push to `main` branch
- Action: `appleboy/ssh-action@v1.0.3`
- Auth: GitHub Secret `DEPLOY_SSH_KEY`

**Deploy Script (on each server):**
```bash
#!/bin/bash
cd ~/wp-theme-repo
git pull origin main
rsync -av --delete src/ ../public_html/wp-content/themes/davesport/
echo "Deployed: $(git log -1 --oneline)"
```

## To Add/Remove Sites

Edit `.github/workflows/deploy.yml`:
- **Remove from auto-deploy:** Comment out the job (add `#` before each line)
- **Add to auto-deploy:** Uncomment or create new job following existing pattern

## History

- **2026-02-10:** Initial setup with all 9 sites
- **2026-02-11:** Removed Arsenal, Chelsea, West Ham due to manual overwrites
