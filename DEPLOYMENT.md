# GitHub Actions Deployment Setup

This document explains how to set up automated deployment to your production server using GitHub Actions, replacing the simple-php-git-deploy system.

## Prerequisites

1. A production server with SSH access
2. PHP 7.3+ installed on the production server
3. Composer installed on the production server (optional, dependencies are built during CI)
4. Proper file permissions on the production server

## Required GitHub Secrets

You need to configure the following secrets in your GitHub repository settings (Settings → Secrets and variables → Actions):

### Required Secrets

- `HOST` - Your production server's IP address or domain name
- `USER` - SSH username for the production server
- `SSH_KEY` - Private SSH key for authentication (see SSH Key Setup below)
- `ROOT_PATH` - Base directory on the server (e.g., `/home/username/`)
- `DEPLOY_PATH` - Full path to your web directory (e.g., `/home/username/public_html/`)

### Optional Secrets

- `PORT` - SSH port (defaults to 22 if not set)

## Directory Structure

The new setup separates deployment files from backups and temporary files:

```
/home/username/                    (ROOT_PATH)
├── backups/                       # Deployment backups (outside web root)
│   ├── 20241003_143022/          # Timestamped backup directories
│   └── 20241003_150045/
├── temp/                          # Temporary deployment files (outside web root)
└── public_html/                   # Your web directory (DEPLOY_PATH)
    ├── index.php
    ├── admin/
    ├── site/
    └── ...
```

This organization ensures:
- Backups are stored outside the public web directory for security
- Temporary deployment files don't interfere with the live site
- Better separation of concerns between deployment infrastructure and web content

## SSH Key Setup

1. Generate an SSH key pair on your local machine:
   ```bash
   ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy
   ```

2. Copy the public key to your production server:
   ```bash
   ssh-copy-id -i ~/.ssh/github_actions_deploy.pub user@your-server.com
   ```

3. Add the private key content to GitHub Secrets:
   ```bash
   cat ~/.ssh/github_actions_deploy
   ```
   Copy the entire output (including `-----BEGIN OPENSSH PRIVATE KEY-----` and `-----END OPENSSH PRIVATE KEY-----`) and paste it as the `SSH_KEY` secret.

## Workflow Features

### Automatic Deployment
- Triggers on pushes to the `master` branch
- Can be manually triggered from GitHub Actions tab

### Deployment Process
1. **Code Checkout** - Downloads your repository code
2. **PHP Setup** - Configures PHP 7.4 with required extensions
3. **Dependency Installation** - Installs Composer dependencies (production mode)
4. **Archive Creation** - Creates a clean deployment package
5. **Server Deployment** - Uploads and extracts files to production
6. **File Preservation** - Preserves important files (configs, uploads, images)
7. **Permission Setting** - Sets proper file and directory permissions
8. **Post-deployment Scripts** - Runs migrations and fix scripts
9. **Verification** - Checks deployment health
10. **Backup Management** - Keeps last 5 backups, auto-cleanup

### Rollback Protection
- Automatic backup before each deployment (stored in `ROOT_PATH/backups/`)
- Automatic rollback on deployment failure
- Manual rollback capability using previous backups

### Files Preserved During Deployment
- `.env` files
- Configuration files in `admin/config/` and `site/config/`
- Images directory content
- Uploads directory (if exists)
- Log files and cache directories

## Directory Permissions

The workflow automatically sets these permissions:
- PHP files: 644
- Directories: 755
- Cache/logs directories: 777
- Migration script (`dm`): 755

## Post-Deployment Scripts

The workflow automatically runs these scripts if they exist:
1. `php lib/fix-env.php` - Environment setup
2. `./dm migrate --no-interaction` - Database migrations
3. `php lib/fix-thumbs.php` - Thumbnail processing

## Manual Deployment

You can trigger deployment manually:
1. Go to your repository on GitHub
2. Click "Actions" tab
3. Select "Deploy to Production" workflow
4. Click "Run workflow"

## Monitoring Deployments

- Check the Actions tab for deployment status
- View detailed logs for each deployment step
- Get notified of deployment success/failure

## Troubleshooting

### Common Issues

1. **SSH Connection Failed**
   - Verify SSH key is correctly added to server
   - Check `HOST` and `USER` secrets
   - Ensure SSH port is correct (default: 22)

2. **Permission Denied**
   - Verify SSH user has write access to both `ROOT_PATH` and `DEPLOY_PATH`
   - Check that both directories exist and are accessible

3. **Composer Issues**
   - Ensure PHP and required extensions are available on server
   - Check server has sufficient memory for Composer

4. **Migration Failures**
   - Verify database credentials in config files
   - Check that `dm` script has execute permissions

### Manual Rollback

If you need to manually rollback:

```bash
ssh user@your-server.com
cd /home/username/backups/  # Your ROOT_PATH
ls -la  # List available backups
rm -rf /home/username/public_html/*  # Clear current deployment
mv backup_folder_name/* /home/username/public_html/  # Restore from backup
```

## Security Notes

- SSH keys should be unique and used only for deployment
- Regularly rotate SSH keys
- Monitor deployment logs for suspicious activity
- Keep GitHub secrets secure and limit repository access
- Backups are stored outside the web root for added security

## Migration from simple-php-git-deploy

1. Remove the old `deploy.php` file from your repository
2. Set up the GitHub secrets as described above
3. Ensure your server directory structure matches the new organization
4. Test the new workflow on a staging environment first
5. Update any external services that might be calling the old deploy webhook

The new system provides better security, reliability, and visibility compared to the webhook-based deployment system.
