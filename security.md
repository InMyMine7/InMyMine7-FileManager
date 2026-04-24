# Security Guide

## 🔐 InMyMine7 File Manager Security

This document provides comprehensive security guidelines for deploying and using InMyMine7 File Manager safely.

---

## Table of Contents

- [Pre-Installation Security](#pre-installation-security)
- [Installation Security](#installation-security)
- [Access Control](#access-control)
- [Password Security](#password-security)
- [Network Security](#network-security)
- [File Permissions](#file-permissions)
- [Monitoring & Logging](#monitoring--logging)
- [Vulnerability Disclosure](#vulnerability-disclosure)
- [Incident Response](#incident-response)
- [Compliance](#compliance)

---

## Pre-Installation Security

### 1. Server Assessment

Before installing, verify your server security:

```bash
# Check PHP version
php -v
# Should be 7.4 or higher (8.0+ recommended)

# Check security-related PHP settings
php -i | grep -E "(disable_functions|open_basedir|safe_mode)"

# List running services
netstat -tuln | grep LISTEN

# Check firewall status
sudo ufw status
# or
sudo firewall-cmd --list-all
```

### 2. Hosting Provider Security

Choose a hosting provider that provides:
- ✅ Regular security updates
- ✅ Firewall protection
- ✅ DDoS protection
- ✅ SSL/TLS certificates
- ✅ Automated backups
- ✅ Security monitoring
- ✅ Rapid response to incidents

### 3. System Hardening

Before installation:
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Update PHP
sudo apt upgrade php php-common -y

# Update Apache/Nginx
sudo apt upgrade apache2 -y
# or
sudo apt upgrade nginx -y

# Disable unnecessary services
sudo systemctl disable avahi-daemon
sudo systemctl disable cups

# Enable firewall
sudo ufw enable
```

---

## Installation Security

### 1. Secure Download

```bash
# Always download over HTTPS
curl -o fm.php https://raw.githubusercontent.com/InMyMine7/InMyMine7-FileManager/main/fm.php

# Verify file integrity (if checksum provided)
sha256sum fm.php
# Compare with official checksum

# Verify file is valid PHP
php -l fm.php
```

### 2. Pre-Upload Configuration

**CRITICAL: Change password BEFORE upload**

```php
// DO NOT use default password 'admin'
// Generate a strong password

// Option 1: Use PHP password hash
$password_hash = password_hash('YourStrongPassword123!@#', PASSWORD_BCRYPT);

// Then in the code:
if (password_verify($_POST['password'], $password_hash)) {
    // Allow login
}

// Option 2: Use simple password (less secure)
$password = 'UltraStr0ng_P@ssw0rd_2024!#%';
```

### 3. Secure Upload Method

```bash
# Use SFTP (SSH File Transfer Protocol) - Most Secure
sftp user@your-domain.com
> cd public_html
> put fm.php
> chmod 644 fm.php

# Verify upload
> ls -la fm.php
# Should show: -rw-r--r-- 1 user group

# Never use unencrypted FTP
# ❌ AVOID: ftp.your-domain.com (plain FTP)
# ✅ USE: sftp.your-domain.com or FTPS
```

---

## Access Control

### 1. IP Whitelist (.htaccess)

**Most Effective: Restrict by IP Address**

```apache
# .htaccess in same directory as fm.php
<Files fm.php>
    # Allow only specific IPs
    Order Deny,Allow
    Deny from all
    Allow from 192.168.1.100      # Your office
    Allow from 203.0.113.50       # Your home
    Allow from 198.51.100.0/24    # Your office network
    
    # OR Allow specific country (using mod_geoip)
    # Require geoip country US
</Files>
```

### 2. Basic HTTP Authentication

**Add extra layer of protection**

```php
// Before the main code in fm.php, add:

// HTTP Basic Authentication
if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="File Manager"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

// Set credentials (change these!)
$valid_user = 'admin';
$valid_pass = 'BasicAuth_P@ss123';

if ($_SERVER['PHP_AUTH_USER'] !== $valid_user || 
    $_SERVER['PHP_AUTH_PW'] !== $valid_pass) {
    header('HTTP/1.0 401 Unauthorized');
    echo 'Invalid credentials';
    exit;
}

// Continue with normal login flow...
```

### 3. Rate Limiting

Prevent brute force attacks:

```php
// Add to fm.php before login check
$max_attempts = 5;
$lockout_time = 900; // 15 minutes

// Use memcache or file-based rate limiting
session_start();
if (isset($_SESSION['login_attempts'])) {
    if ($_SESSION['login_attempts'] >= $max_attempts) {
        if (time() - $_SESSION['last_attempt'] < $lockout_time) {
            exit('Too many login attempts. Try again later.');
        }
    }
}

// On failed login:
$_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
$_SESSION['last_attempt'] = time();
```

---

## Password Security

### 1. Strong Password Generation

```bash
# Generate strong password (Linux/Mac)
openssl rand -base64 12
# Output: aB3cDeFgH9iJkLmN

# Alternative: Use online generators
# https://www.random.org/strings/
# https://www.lastpass.com/password-generator/
```

### 2. Password Policy

**Create a strong password with:**
- ✅ Minimum 16 characters (8 minimum, 20+ recommended)
- ✅ Mix of uppercase (A-Z)
- ✅ Mix of lowercase (a-z)
- ✅ Numbers (0-9)
- ✅ Special characters (!@#$%^&*)
- ✅ NO dictionary words
- ✅ NO personal information
- ✅ NO sequential patterns

**Examples of STRONG passwords:**
```
K9#mPq2@xFvL7$bN
FileM@n@ger_Sec2024!
R0ckSt@r_FM_Admin#99
Tr0p!cal_P@ssw0rd_2K24
```

**Examples of WEAK passwords:**
```
❌ password123 (too common)
❌ admin@123 (too common)
❌ fm_password (dictionary word)
❌ 12345678 (sequential)
❌ qwerty (keyboard pattern)
```

### 3. Secure Password Storage

```php
// Option 1: Using password_hash (Recommended)
$password = 'YourStrongPassword123!@#';
$hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Store hashed version in config
if (password_verify($_POST['password'], $hashed)) {
    $_SESSION['fm_logged_in'] = true;
}

// Option 2: Using SHA256 (Basic)
$password = 'YourStrongPassword123!@#';
$hashed = hash('sha256', $password);

if (hash('sha256', $_POST['password']) === $hashed) {
    $_SESSION['fm_logged_in'] = true;
}

// Option 3: Using .htpasswd (Apache)
# Generate .htpasswd file
htpasswd -c .htpasswd admin

# Use in .htaccess
<Files fm.php>
    AuthType Basic
    AuthName "File Manager"
    AuthUserFile /path/to/.htpasswd
    Require valid-user
</Files>
```

### 4. Change Password Regularly

```php
// Add password rotation reminder
$last_password_change = filemtime('fm.php');
$days_since_change = (time() - $last_password_change) / 86400;

if ($days_since_change > 90) {
    echo "⚠️ SECURITY: Consider changing password (last changed " . 
         floor($days_since_change) . " days ago)";
}
```

---

## Network Security

### 1. Use HTTPS/SSL Only

```bash
# Get free SSL certificate from Let's Encrypt
sudo apt install certbot
sudo certbot certonly --standalone -d your-domain.com

# Or via hosting control panel (cPanel, Plesk, etc.)
# Install certificate and enable auto-renewal
```

**Force HTTPS in .htaccess:**
```apache
# Force HTTPS redirect
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

**Force HTTPS in fm.php:**
```php
// At the very top of fm.php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Add HSTS header
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

### 2. Disable Directory Listing

```apache
# .htaccess - Prevent directory browsing
Options -Indexes

# Prevent access to sensitive files
<FilesMatch "(\.env|\.htaccess|\.htpasswd|fm\.php\.bak)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 3. Content Security Policy (CSP)

```php
// Add to fm.php
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdnjs.cloudflare.com; style-src 'self' https://fonts.googleapis.com");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
```

### 4. Disable Unnecessary Protocols

```apache
# .htaccess - Disable SSL v2/v3
SSLProtocol all -SSLv2 -SSLv3 -TLSv1 -TLSv1.1

# Disable weak ciphers
SSLCipherSuite HIGH:!aNULL:!MD5
```

---

## File Permissions

### 1. Linux/Unix Permissions

```bash
# Set fm.php permissions (read/write for owner only)
chmod 600 fm.php
# or allow group read
chmod 640 fm.php

# Set directory permissions (allow write)
chmod 755 ./
# or more restrictive
chmod 700 ./

# Set upload directory
chmod 755 uploads/

# Set config file
chmod 600 config.php

# Set backup directory
chmod 700 backups/

# Verify permissions
ls -la fm.php
# Should show: -rw------- (600) or -rw-r----- (640)
```

### 2. Windows IIS Permissions

```
1. Right-click fm.php → Properties
2. Security tab → Edit
3. Remove "Users" group
4. Add specific accounts with limited permissions
5. Remove inheritance
6. Apply permissions to file only
```

### 3. Directory Structure

```
public_html/
├── fm.php (600 permissions)
├── .htaccess (644)
├── uploads/ (755)
├── config/ (700 - if exists)
└── backups/ (700 - if exists)
```

---

## Monitoring & Logging

### 1. Enable PHP Error Logging

```php
// In fm.php
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/fm_errors.log');

// Or in php.ini
[PHP]
log_errors = On
error_log = /var/log/php/fm_errors.log
```

### 2. Monitor Login Attempts

```php
// Add to fm.php
if (isset($_POST['password'])) {
    $log = date('Y-m-d H:i:s') . ' - Login attempt from ' . $_SERVER['REMOTE_ADDR'];
    
    if ($_POST['password'] === $password) {
        $log .= ' - SUCCESS';
        file_put_contents('fm_login.log', $log . "\n", FILE_APPEND);
    } else {
        $log .= ' - FAILED';
        file_put_contents('fm_login.log', $log . "\n", FILE_APPEND);
    }
}
```

### 3. Monitor File Operations

```php
// Log all file operations
function log_operation($action, $file, $status) {
    $log = date('Y-m-d H:i:s') . " | " . 
           $_SERVER['REMOTE_ADDR'] . " | " . 
           $action . " | " . 
           $file . " | " . 
           $status . "\n";
    file_put_contents('fm_operations.log', $log, FILE_APPEND);
}

// Use: log_operation('UPLOAD', 'document.pdf', 'SUCCESS');
```

### 4. Check Logs Regularly

```bash
# Monitor login attempts
tail -f fm_login.log

# Monitor file operations
tail -f fm_operations.log

# Check for suspicious patterns
grep "FAILED" fm_login.log | wc -l
grep "DELETE" fm_operations.log

# Analyze remote IPs
grep "FAILED" fm_login.log | awk '{print $9}' | sort | uniq -c | sort -rn
```

---

## Vulnerability Disclosure

### 1. Report Security Issues

If you discover a security vulnerability:

**DO:**
1. ✅ Report privately via [GitHub Security Advisories](https://github.com/InMyMine7/InMyMine7-FileManager/security/advisories/new)
2. ✅ Give developers reasonable time to fix (30-90 days)
3. ✅ Provide clear reproduction steps
4. ✅ Include affected versions
5. ✅ Be respectful and constructive

**DON'T:**
1. ❌ Publicly disclose before fix
2. ❌ Use the vulnerability maliciously
3. ❌ Share details on social media
4. ❌ Demand immediate action
5. ❌ Be rude or demanding

### 2. Coordinated Disclosure

```
Timeline:
Day 0:  Report discovered vulnerability
Day 7:  Follow-up if no response
Day 30: Ask for timeline to fix
Day 60: Prepare for public disclosure
Day 90: Publish information
```

### 3. Response Format

**When reporting, include:**
```
Subject: [SECURITY] Vulnerability in fm.php

Description:
- Vulnerability type (SQL injection, XSS, etc.)
- Affected versions
- Reproduction steps
- Proof of concept (if possible)
- Suggested fix
- Your contact information

Request:
- Estimated time to patch
- Planned disclosure date
- Credit/acknowledgment preference
```

---

## Incident Response

### 1. Compromised Password

If password is compromised:

```bash
# 1. Immediately change password in fm.php
# 2. Check server logs for unauthorized access
tail -1000 /var/log/apache2/access.log | grep fm.php

# 3. Review recently modified files
find . -mtime -1  # Modified in last 24 hours

# 4. Check for backdoors
grep -r "<?php" --include="*.php" .

# 5. Consider full server audit
sudo lynis audit system

# 6. Enable 2FA if possible
# 7. Monitor for suspicious activity
# 8. Review all backups
```

### 2. Suspicious Activity

If you notice suspicious activity:

```bash
# 1. Check failed login attempts
grep "FAILED" fm_login.log | tail -20

# 2. Block suspicious IP addresses
# In .htaccess
Order deny,allow
Deny from 192.168.1.50
Deny from 203.0.113.100

# 3. Enable more verbose logging
# 4. Check system resources
top -b -n 1 | head -20

# 5. Review file modifications
stat fm.php
ls -la

# 6. Enable firewall rules
sudo ufw enable
sudo ufw default deny incoming
```

### 3. Backup and Recovery

```bash
# 1. Verify backup integrity
tar -tzf backup_20240424.tar.gz | head

# 2. Test restore in isolated environment
# 3. Keep incremental backups
# 4. Verify backup encryption
# 5. Document recovery procedure
# 6. Test recovery regularly (at least monthly)
```

---

## Compliance

### 1. GDPR Compliance

If storing/processing EU user data:

```
- ✅ Privacy Policy required
- ✅ Data Protection Agreement
- ✅ User consent for processing
- ✅ Right to access data
- ✅ Right to delete data
- ✅ Data breach notification (72 hours)
- ✅ Privacy by design
- ✅ Data minimization
```

### 2. CCPA Compliance

If storing/processing California resident data:

```
- ✅ Privacy Policy required
- ✅ Opt-out capability
- ✅ User rights
- ✅ Data selling disclosure
- ✅ Data breach notification
```

### 3. PCI DSS Compliance

If handling payment card data:

```
❌ DO NOT store credit card information
❌ DO NOT process payments through this app
❌ Use PCI-compliant payment processor

If you must handle card data:
- ✅ SSL/TLS encryption
- ✅ Regular security audits
- ✅ PCI DSS certification
- ✅ Firewall protection
- ✅ Access control
- ✅ Vulnerability scanning
```

---

## Security Checklist

Before going live:

```
INSTALLATION
□ Changed default password
□ Verified download integrity
□ Uploaded via SFTP/FTPS
□ Set correct file permissions (600)
□ Removed from public root (if possible)

ACCESS CONTROL
□ IP whitelist configured
□ HTTP authentication enabled
□ Rate limiting configured
□ Directory listing disabled
□ Sensitive files protected

NETWORK
□ HTTPS/SSL enabled
□ HSTS header added
□ CSP header configured
□ Security headers added
□ Firewall rules configured

MONITORING
□ Error logging enabled
□ Login logging enabled
□ Operation logging enabled
□ Log file secured
□ Monitoring tools configured

BACKUPS
□ Backup schedule set
□ Backups encrypted
□ Backup integrity verified
□ Recovery procedure tested
□ Off-site backup configured

MAINTENANCE
□ PHP updated to latest version
□ Server security patches applied
□ SSL certificate valid
□ SSL certificate auto-renewal set
□ Regular security audit scheduled
```

---

## Security Best Practices Summary

| Practice | Priority | Frequency |
|---|---|---|
| Change default password | 🔴 CRITICAL | Before deployment |
| Use HTTPS only | 🔴 CRITICAL | Always |
| Update PHP/Server | 🔴 CRITICAL | Monthly |
| Backup data | 🔴 CRITICAL | Daily |
| Review logs | 🟠 HIGH | Weekly |
| Change password | 🟠 HIGH | Quarterly |
| Security audit | 🟠 HIGH | Annually |
| Penetration test | 🟡 MEDIUM | Annually |
| Update docs | 🟡 MEDIUM | As needed |

---

## Additional Resources

- 📚 OWASP Top 10: https://owasp.org/www-project-top-ten/
- 🔐 PHP Security: https://www.php.net/manual/en/security.php
- 📖 CWE Top 25: https://cwe.mitre.org/top25/
- 🛡️ Secure Headers: https://securityheaders.com/

---

## Contact & Support

For security questions or to report vulnerabilities:
1. [Submit a private security advisory](https://github.com/InMyMine7/InMyMine7-FileManager/security/advisories/new)
2. [Open an issue](https://github.com/InMyMine7/InMyMine7-FileManager/issues) for general questions
3. Do not disclose publicly before fix

---

## Version Information

```
Security Guide Version: 1.0
Last Updated: April 24, 2026
Repository: https://github.com/InMyMine7/InMyMine7-FileManager
InMyMine7 File Manager: v4.0
```

---

**Remember: Security is ongoing, not one-time!**

Regular maintenance, updates, and monitoring are essential.