#!/bin/bash

# 🚀 Laravel Forge Deploy Script - Filament Resource Generator
# Questo script è ottimizzato per Laravel Forge

cd $FORGE_SITE_PATH

# Git pull
git pull origin $FORGE_SITE_BRANCH

# Install/update composer dependencies
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear e rebuild cache per ottimizzazioni produzione

$FORGE_PHP artisan config:clear
$FORGE_PHP artisan route:clear
$FORGE_PHP artisan view:clear

$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache  
$FORGE_PHP artisan view:cache

# Ottimizzazioni aggiuntive per storage approach
$FORGE_PHP artisan optimize

# Verifica che il sistema storage approach funzioni
echo "🔍 Testing Filament Generator system..."
$FORGE_PHP artisan test:filament-generator ForgeDeployTest > /dev/null 2>&1

if [ $? -eq 0 ]; then
    echo "✅ Filament Generator system working correctly"
    
    # Cleanup test resource
    $FORGE_PHP artisan tinker --execute="
    try {
        \App\Models\ResourceGenerator::where('name', 'ForgeDeployTest')->delete();
        if(is_dir('storage/app/generated')) {
            array_map('unlink', glob('storage/app/generated/**/ForgeDeployTest*'));
        }
    } catch (Exception \$e) {}
    " > /dev/null 2>&1
    
    # Ensure generated files are symlinked into app/ so they are discoverable
    echo "🔗 Linking generated files into app/..."
    $FORGE_PHP artisan filament:link-generated --force > /dev/null 2>&1 || true

else
    echo "⚠️ Filament Generator test failed, but continuing deploy"
fi

# Assicurati che le directory abbiano i permessi corretti
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

echo "🎉 Deploy completed successfully!"