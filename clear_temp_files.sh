#!/bin/bash

echo "🧹 Clearing Laravel temporary files..."

# Clear session files (keep .gitkeep)
echo "Clearing session files..."
find storage/framework/sessions -name "*.php" -type f -delete 2>/dev/null || true
find storage/framework/sessions -name "*" -type f ! -name ".gitkeep" -delete 2>/dev/null || true

# Clear compiled view cache (keep .gitkeep)
echo "Clearing compiled view cache..."
find storage/framework/views -name "*.php" -type f -delete 2>/dev/null || true

# Clear bootstrap cache (keep .gitkeep)
echo "Clearing bootstrap cache..."
find bootstrap/cache -name "*.php" -type f -delete 2>/dev/null || true

# Clear log files
echo "Clearing log files..."
> storage/logs/laravel.log 2>/dev/null || true

# Clear any other cache directories
echo "Clearing other cache files..."
find storage/framework/cache -name "*" -type f ! -name ".gitkeep" -delete 2>/dev/null || true

# Clear any temporary files
echo "Clearing temporary files..."
find . -name "*.tmp" -type f -delete 2>/dev/null || true
find . -name "*~" -type f -delete 2>/dev/null || true
find . -name "*.swp" -type f -delete 2>/dev/null || true

echo "✅ Temporary files cleared successfully!"
echo "📝 Note: .gitkeep files have been preserved to maintain directory structure."
