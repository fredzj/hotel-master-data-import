# Hotel Master Data Import - Deployment Guide

## System Requirements

- PHP 8.2 or higher
- MySQL 5.7 or higher
- Composer
- Node.js and NPM (for asset compilation, if needed)

## Shared Hosting Deployment

### 1. File Structure Setup

Upload your Laravel application to your shared hosting account. The structure should be:

```
public_html/
├── hotel-master-data-import/  (Laravel application root)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
└── public/  (or www/ depending on hosting)
    ├── index.php  (modified to point to Laravel)
    ├── css/
    ├── js/
    └── .htaccess
```

### 2. Public Directory Configuration

Since shared hosting typically uses `public_html` or `www` as the document root, you need to either:

**Option A: Move Laravel's public folder contents**
1. Copy all contents from `hotel-master-data-import/public/` to your domain's document root
2. Edit the `index.php` file in the document root to point to the correct paths:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Adjust these paths to point to your Laravel installation
require_once __DIR__.'/hotel-master-data-import/vendor/autoload.php';

$app = require_once __DIR__.'/hotel-master-data-import/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

**Option B: Subdomain or subdirectory setup**
Point your domain/subdomain's document root to the `hotel-master-data-import/public/` folder.

### 3. Environment Configuration

1. Copy `.env.example` to `.env`
2. Configure your environment variables:

```env
APP_NAME="Hotel Master Data Import"
APP_ENV=production
APP_KEY=base64:YOUR_APPLICATION_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u10919p130675_hmdi
DB_USERNAME=u10919p130675_hmdi
DB_PASSWORD=YUK2QhNzFgTaZJQbCjqH

QUEUE_CONNECTION=database

# Apaleo Configuration
APALEO_CLIENT_ID=your_apaleo_client_id
APALEO_CLIENT_SECRET=your_apaleo_client_secret
APALEO_BASE_URL=https://api.apaleo.com
APALEO_IDENTITY_URL=https://identity.apaleo.com
```

### 4. Storage Permissions

Ensure the `storage/` and `bootstrap/cache/` directories are writable:

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 5. Database Setup

Run the migrations and seeders:

```bash
php artisan migrate --seed
```

### 6. Queue Processing Setup

Since shared hosting doesn't support persistent processes, you need to set up queue processing. **For Vimexx Shared Hosting**, the standard Laravel scheduler cron job is not allowed.

#### Option A: Simple Cron Job for Vimexx
Create a cron job that runs every minute using the full PHP path:

```bash
* * * * * /usr/local/bin/php /home/yourusername/domains/yourdomain.com/hotel-master-data-import/artisan queue:work --stop-when-empty
```

#### Option B: Web-based Scheduler (Recommended for Vimexx)
Create a web endpoint to trigger the scheduler and use a cron job to call it:

1. Create a new route in `routes/web.php`:
```php
Route::get('/cron', function () {
    Artisan::call('schedule:run');
    return response('Scheduler executed', 200);
})->middleware('throttle:60,1'); // Limit to 60 requests per minute
```

2. Set up a cron job to call this URL:
```bash
* * * * * curl -s "https://yourdomain.com/cron" > /dev/null 2>&1
```

#### Option C: Manual Queue Processing
If cron jobs are severely limited, process queues manually through the admin interface:
- Add a "Process Queue" button in the dashboard
- Use `php artisan queue:work --stop-when-empty` via web interface

The exact path format for Vimexx is typically:
- PHP Path: `/usr/local/bin/php` or `/usr/bin/php`
- Laravel App Path: `/home/yourusername/domains/yourdomain.com/hotel-master-data-import/`
- Public Path: `/home/yourusername/domains/yourdomain.com/public_html/` (for web files)

### 7. Cache and Config Optimization

For production, optimize the application:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Default Login Credentials

After running the seeders, you can login with:

- **Email**: admin@hotel-data.com
- **Password**: password

**Important**: Change these credentials immediately after first login!

## PMS Integration Setup

### Apaleo Setup

1. Obtain API credentials from your Apaleo account
2. Update the `.env` file with your credentials:
   ```env
   APALEO_CLIENT_ID=your_client_id
   APALEO_CLIENT_SECRET=your_client_secret
   ```
3. Test the connection using the dashboard import feature

## Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check storage permissions
   - Verify .env file configuration
   - Check error logs in `storage/logs/`

2. **Queue Jobs Not Processing**
   - Verify cron job is set up correctly
   - Check that `QUEUE_CONNECTION=database` in .env
   - Ensure jobs table exists (run migrations)

3. **Database Connection Issues**
   - Verify database credentials in .env
   - Ensure database exists and user has proper permissions
   - Check if host should be 'localhost' or an IP address

4. **Import Jobs Failing**
   - Check API credentials in .env
   - Verify network connectivity to PMS APIs
   - Review logs in `storage/logs/laravel.log`

### Log Files

Check these log files for troubleshooting:
- `storage/logs/laravel.log` - Application logs
- Server error logs (location varies by hosting provider)

## Security Considerations

1. **Environment File**: Never commit `.env` to version control
2. **Database**: Use strong passwords and limit database user permissions
3. **API Keys**: Rotate API credentials regularly
4. **Updates**: Keep Laravel and dependencies updated
5. **Backups**: Set up regular database and file backups

## Performance Optimization

1. **Caching**: Enable Redis or Memcached if available
2. **Database**: Add indexes for frequently queried fields
3. **Assets**: Use a CDN for static assets if possible
4. **Monitoring**: Set up application monitoring and alerting

## Support

For technical issues:
1. Check the logs first
2. Verify configuration settings
3. Test with minimal data first
4. Contact your hosting provider for server-specific issues