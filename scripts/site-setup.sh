#!/bin/bash
# Site Setup Script - Run on each new site via SSH
# Usage: bash site-setup.sh SITE_KEY WP_PATH
# Example: bash site-setup.sh readastonvilla /home/sites/XX/X/XXXX/public_html

SITE_KEY=$1
WP_PATH=$2

if [ -z "$SITE_KEY" ] || [ -z "$WP_PATH" ]; then
    echo "Usage: bash site-setup.sh SITE_KEY WP_PATH"
    echo "Example: bash site-setup.sh readastonvilla /home/sites/XX/X/XXXX/public_html"
    exit 1
fi

echo "🚀 Setting up $SITE_KEY..."
echo

# 1. Clone repo if not exists
if [ ! -d ~/wp-theme-repo ]; then
    echo "📦 Cloning repo..."
    git clone https://github.com/moodco/wp-theme.git ~/wp-theme-repo
else
    echo "✅ Repo already exists"
fi

# 2. Create deploy.sh
echo "📝 Creating deploy script..."
cat > ~/deploy.sh << 'EOF'
#!/bin/bash
set -e
REPO=~/wp-theme-repo
THEME=~/public_html/wp-content/themes/davesport

cd $REPO
git pull origin main --ff-only
rsync -a --delete --exclude=".git" $REPO/src/ $THEME/
rm -rf $THEME/sites
cp -r $REPO/sites $THEME/sites
echo "Deployed: $(git log --oneline -1)"
EOF

chmod +x ~/deploy.sh
echo "✅ deploy.sh created"

# 3. Add MOODCO_SITE_KEY to wp-config.php if not exists
if ! grep -q "MOODCO_SITE_KEY" "$WP_PATH/wp-config.php"; then
    echo "🔑 Adding MOODCO_SITE_KEY to wp-config.php..."
    # Insert before "That's all, stop editing!"
    sed -i "/That's all, stop editing/i define('MOODCO_SITE_KEY', '$SITE_KEY');" "$WP_PATH/wp-config.php"
    echo "✅ MOODCO_SITE_KEY added"
else
    echo "✅ MOODCO_SITE_KEY already set"
fi

# 4. Initial deploy
echo "🚀 Running initial deploy..."
bash ~/deploy.sh

echo
echo "✨ Setup complete for $SITE_KEY!"
echo
echo "📋 Next steps:"
echo "1. In WordPress admin, create 'Navigation' and 'Footer' menus"
echo "2. Run: php ~/wp-cli.phar --path=$WP_PATH eval-file ~/wp-theme-repo/scripts/setup-menus.php"
echo "3. Verify site looks correct"
