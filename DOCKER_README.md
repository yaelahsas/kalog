# Docker Setup for Kalog Application

This document explains how to set up and run the Kalog application using Docker with Apache and PHP-FPM 7.4.

## Prerequisites

- Docker installed on your system
- Docker Compose installed on your system

## Project Structure

```
kalog/
├── docker-compose.yml          # Main Docker Compose configuration
├── Dockerfile                  # PHP-FPM 7.4 container configuration
├── .dockerignore              # Files to exclude from Docker build
├── docker/
│   ├── apache/
│   │   ├── apache.conf        # Apache main configuration
│   │   └── vhost.conf         # Virtual host configuration
│   └── php/
│       └── php.ini            # PHP configuration
├── application/               # CodeIgniter application files
├── assets/                    # Static assets
├── system/                    # CodeIgniter system files
└── uploads/                   # Upload directory (writable)
```

## Quick Start

1. **Build and start the containers:**
   ```bash
   docker-compose up -d --build
   ```

2. **Access the application:**
   Open your browser and navigate to `http://localhost:8080`

3. **View logs:**
   ```bash
   docker-compose logs -f
   ```

4. **Stop the containers:**
   ```bash
   docker-compose down
   ```

## Services

### PHP-FPM
- **Container Name:** `kalog_php_fpm`
- **PHP Version:** 7.4
- **Extensions:** gd, pdo_mysql, mysqli, zip, xml, mbstring, curl, opcache, bcmath
- **Port:** 9000 (internal)

### Apache
- **Container Name:** `kalog_apache`
- **Image:** httpd:2.4-alpine
- **Port:** 8080 (host) → 80 (container)
- **Configuration:** Custom Apache config with PHP-FPM proxy

### MySQL (Optional)
MySQL service is commented out in the docker-compose.yml file. To enable it:
1. Uncomment the MySQL service in `docker-compose.yml`
2. Uncomment the mysql_data volume
3. Run `docker-compose up -d --build`

## Configuration

### PHP Configuration
PHP settings are configured in `docker/php/php.ini`:
- Memory limit: 512M
- Max execution time: 300 seconds
- Upload max file size: 64M
- Error reporting: E_ALL & ~E_DEPRECATED & ~E_STRICT
- Timezone: Asia/Bangkok

### Apache Configuration
Apache is configured to:
- Serve files from `/var/www/html`
- Proxy PHP requests to PHP-FPM on port 9000
- Allow .htaccess overrides for URL rewriting
- Set environment variable `CI_ENV=development`

## Development Workflow

1. **Make changes to your code locally**
2. **Changes are automatically reflected in the container** (due to volume mounting)
3. **Restart Apache if needed:**
   ```bash
   docker-compose restart apache
   ```

## Troubleshooting

### Permission Issues
If you encounter permission issues with uploads or logs:
```bash
docker-compose exec php-fpm chown -R www-data:www-data /var/www/html/uploads
docker-compose exec php-fpm chown -R www-data:www-data /var/www/html/application/logs
```

### Clear PHP Cache
To clear OPcache:
```bash
docker-compose restart php-fpm
```

### View PHP Info
Create a temporary info file:
```bash
echo "<?php phpinfo(); ?>" > info.php
```
Access `http://localhost:8080/info.php` and remember to remove it afterward.

## Production Deployment

For production deployment:

1. **Update environment variables:**
   - Set `CI_ENV=production` in `docker/apache/vhost.conf`
   - Update error reporting in `docker/php/php.ini`

2. **Secure your application:**
   - Use proper database credentials
   - Enable HTTPS
   - Set appropriate file permissions

3. **Optimize performance:**
   - Enable OPcache (already configured)
   - Use proper caching strategies
   - Consider using Redis for session storage

## Additional Commands

- **Execute commands in PHP container:**
  ```bash
  docker-compose exec php-fpm <command>
  ```

- **Execute commands in Apache container:**
  ```bash
  docker-compose exec apache <command>
  ```

- **View container status:**
  ```bash
  docker-compose ps
  ```

- **Rebuild containers:**
  ```bash
  docker-compose build --no-cache