# InMyMine7 File Manager v4

> Web-based File Manager with modern interface and complete features to manage your server files.

![Version](https://img.shields.io/badge/version-4.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Language](https://img.shields.io/badge/language-PHP-purple)

---

## 📸 Screenshot

![InMyMine7 File Manager Interface](https://raw.githubusercontent.com/InMyMine7/InMyMine7-FileManager/main/assets/fm.png)

---

## 📋 Table of Contents

- [Main Features](#-main-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Usage Guide](#-usage-guide)
- [Detailed Features](#-detailed-features)
- [Tools & Utilities](#-tools--utilities)
- [Security](#-security)
- [Troubleshooting](#-troubleshooting)
- [Important Documents](#-important-documents)

---

## 📄 Important Documents

Before using this software, please read:

- **[⚠️ disclaimer.md](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/disclaimer.md)** - Legal disclaimer and terms of use (REQUIRED)
- **[🔐 security.md](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/security.md)** - Comprehensive security guidelines (HIGHLY RECOMMENDED)

These documents contain critical information about liability, security best practices, and legal responsibilities.

---

## 🚀 Main Features

### File & Folder Management
- ✅ **Browse Directories** - Easy navigation with breadcrumb
- ✅ **Upload Files** - Drag & drop or click to upload (multi-file)
- ✅ **Create Files/Folders** - Create new files and folders quickly
- ✅ **Edit Files** - Built-in text editor with syntax highlighting
- ✅ **Rename** - Change file and folder names
- ✅ **Duplicate** - Duplicate files easily
- ✅ **Download** - Download individual files or ZIP archives
- ✅ **Copy/Move** - Copy or move files between folders
- ✅ **Delete** - Delete files with security confirmation
- ✅ **Bulk Actions** - Perform operations on multiple selected files

### Compression Features
- 📦 **Create ZIP** - Compress files to ZIP format
- 📦 **Extract ZIP** - Extract ZIP files easily
- 📦 **Download ZIP** - Download folders as ZIP

### Search & Filtering
- 🔍 **Recursive Search** - Search files across all directories
- 🔍 **Filter Results** - Display search results with details
- 🔍 **Pattern Matching** - Search by file name

### Terminal & Shell
- 💻 **Terminal Integration** - Run shell commands directly
- 💻 **Command History** - History of executed commands
- ⚠️ **Safe Execution** - Controlled execution with security warnings

### Tools & Utilities (15+ Tools)
- 🛠️ PHP Info
- 🛠️ Image Conversion & Resize
- 🛠️ Base64 Encoder/Decoder
- 🛠️ Hash Generator (MD5, SHA1, SHA256)
- 🛠️ .htpasswd Generator
- 🛠️ Cron Expression Builder
- 🛠️ Chmod Calculator
- 🛠️ JSON Formatter & Validator
- 🛠️ Regex Tester
- 🛠️ System Info
- 🛠️ UUID Generator
- 🛠️ URL Encoder/Decoder
- 🛠️ Text Statistics
- 🛠️ Password Generator
- 🛠️ Timestamp Converter

### User Interface
- 🎨 **Modern Dark Theme** - Elegant and comfortable UI design
- 🎨 **Responsive Design** - Compatible with mobile and desktop
- 🎨 **View Modes** - List and grid view modes
- 🎨 **Multi-Language** - Indonesian & English support
- 🎨 **System Information** - OS, IP, Disk, Server time info

---

## 💻 System Requirements

- **PHP**: 7.4 or newer
- **Web Server**: Apache, Nginx, or LiteSpeed
- **Browser**: Chrome, Firefox, Safari, Edge (latest)
- **Permissions**: PHP must have read/write access to directories

### Recommended
- PHP 8.0+
- SSD storage for better performance
- Minimum 512MB RAM

---

## 📥 Installation

### Prerequisites Check
Before installing, verify:
- ✅ PHP 7.4 or higher is installed on your server
- ✅ You have FTP or file manager access to your server
- ✅ You have a text editor for editing `fm.php`
- ✅ You know your domain/server address

### Step 1: Download fm.php
```bash
# Option A: Using wget
wget https://raw.githubusercontent.com/InMyMine7/InMyMine7-FileManager/main/fm.php

# Option B: Using curl
curl -o fm.php https://raw.githubusercontent.com/InMyMine7/InMyMine7-FileManager/main/fm.php

# Option C: Direct download from browser
# Visit https://github.com/InMyMine7/InMyMine7-FileManager and download fm.php
```

**Verify the download:**
- File size should be around 50-100KB
- Check that it's a valid PHP file (has `<?php` at the start)

### Step 2: Edit Configuration (IMPORTANT)
Before uploading, edit `fm.php` and change the password:

```php
// Find this line (approximately at line 3-5):
$password = 'admin'; // Ganti password sesuai kebutuhan

// Change it to your own secure password:
$password = 'your_very_strong_password_123!@#';
```

**Password Requirements:**
- Minimum 8 characters
- Mix of uppercase, lowercase, numbers, and symbols
- Example: `Fm@nag3r_S3cur3!`

### Step 3: Upload to Web Server

#### Method A: Using FTP Client
1. Open your FTP client (FileZilla, WinSCP, etc.)
2. Connect to your server:
   - Host: `ftp.your-domain.com` or `your-ip-address`
   - Username: Your FTP username
   - Password: Your FTP password
3. Navigate to your web root (usually `public_html` or `www`)
4. Upload `fm.php` to desired location:
   - Root: `/public_html/fm.php`
   - Subfolder: `/public_html/admin/fm.php`
5. Set file permissions to `644` (read/write for owner, read for others)

#### Method B: Using Hosting Control Panel (cPanel, Plesk, etc.)
1. Log in to your hosting control panel
2. Go to File Manager
3. Navigate to web root directory
4. Click "Upload" and select `fm.php`
5. Right-click uploaded file → Change Permissions to `644`

#### Method C: Using SSH (Advanced)
```bash
# Connect to your server
ssh user@your-domain.com

# Navigate to web directory
cd /home/user/public_html

# Upload using SCP from local machine
scp fm.php user@your-domain.com:/home/user/public_html/

# Set proper permissions
chmod 644 fm.php
chmod 755 . (current directory)
```

### Step 4: Verify Upload
1. Check file exists on server:
   ```bash
   ls -la fm.php
   # Output should show: -rw-r--r-- (644 permissions)
   ```
2. Check PHP syntax:
   ```bash
   php -l fm.php
   # Should output: No syntax errors detected
   ```

### Step 5: Access via Browser
```
Direct access:
http://your-domain.com/fm.php

With subfolder:
http://your-domain.com/admin/fm.php

Local testing:
http://localhost/fm.php
http://127.0.0.1/fm.php
```

**What you should see:**
- Modern login page with InMyMine7 branding
- Password input field
- Blue "Login" button
- Dark theme interface

### Step 6: Login
1. Enter your custom password (the one you set in Step 2)
2. Click "Login" button
3. Wait for page to load
4. You should see the file manager interface

**If login fails:**
- Check CAPS LOCK (password is case-sensitive)
- Clear browser cookies
- Try in incognito/private mode
- Check browser console for errors

### Step 7: Secure Your Installation (CRITICAL)
```php
// After first login, edit fm.php and consider:

// 1. Change password again
$password = 'new_stronger_password_2024!@#';

// 2. Rename the file
// Don't use obvious names like 'admin.php' or 'manager.php'
// Use something obscure like: 'c7k9m2x4_fm.php'

// 3. Move to non-public directory (if possible)
// Store in parent directory outside public_html
```

### Step 8: Test All Features
After login, test these features:
- ✅ Navigate folders (click folders in the file list)
- ✅ View system info (click "Tools" tab, then "System Info")
- ✅ Try search function with a test query
- ✅ Try creating a test file or folder
- ✅ Try uploading a small test file

---

## ⚠️ Important Notes

1. **Password Security** - Always change the default password BEFORE deploying
2. **File Permissions** - Set `fm.php` to 644, directories to 755
3. **HTTPS Only** - Use HTTPS (SSL) in production, never HTTP
4. **Firewall** - Consider restricting access by IP if possible
5. **Backups** - Keep backups of your files before using file operations
6. **Disclaimer** - Read [disclaimer.md](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/disclaimer.md) for important legal information
7. **Security** - Read [security.md](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/security.md) for comprehensive security guidelines

---

## 📖 Usage Guide

### Basic Login & Navigation

1. **Login to Application**
   - Open fm.php in your browser
   - Enter the correct password
   - Click "Login" button

2. **Navigate Folders**
   - Click folder name to open it
   - Use breadcrumb at the top for quick navigation
   - Click "Home" to return to root directory
   - Click "Up" to return to parent folder

3. **Change Language**
   - Click the flag (🇬🇧) in the top right to toggle language
   - Options: Indonesian (🇮🇩) or English (🇬🇧)
   - Preference is saved in cookies

4. **Logout**
   - Click "Logout" button in sidebar
   - Your session will be cleared and you'll return to login

---

## 📁 Feature Tabs

### 1. "Files" Tab
Main view showing all files and folders in current directory.

**Features:**
- View files with icons based on type
- File size, modification date
- File permission mode
- Actions: Open, Edit, Rename, Duplicate, Download, Copy/Move, Delete

**View Modes:**
- **List** - Detailed table view
- **Grid** - Visual card view

**Bulk Select:**
- "Select All" checkbox to select all files
- Or click individual checkboxes
- After selection, use bulk actions (ZIP, Delete, etc.)

---

### 2. "Upload" Tab
Upload one or multiple files to current folder.

**How to Use:**
1. Click upload area or drag & drop files
2. Select one or more files (multi-file support)
3. Files will upload automatically
4. Refresh to see newly uploaded files

**Examples:**
- Upload website: `index.html, style.css, script.js`
- Upload images: `photo1.jpg, photo2.png`
- Upload documents: `report.pdf, data.xlsx`

---

### 3. "Create New" Tab
Create new files or folders in current directory.

**Creating New File:**
1. Fill in "New file" with name (e.g.: `index.php`)
2. Click "Create File"
3. Empty file will be created with extension based on name

**Creating New Folder:**
1. Fill in "New folder" with name (e.g.: `assets`)
2. Click "Create Folder"
3. Folder will be created and ready to use

**Name Examples:**
```
- index.php
- style.css
- config.json
- uploads (folder)
- assets (folder)
```

---

### 4. "ZIP" Tab
Compress files into ZIP archive.

**How to Create ZIP:**
1. Select files to ZIP from the file list
2. Enter ZIP name (e.g.: `backup.zip`)
3. Click "Create ZIP"
4. ZIP file will be created in current folder

**How to Extract ZIP:**
1. Click `.zip` file in Files tab
2. Select "Extract"
3. ZIP will be extracted to a new folder

**Download Folder as ZIP:**
1. In Files tab, click "Download ZIP" on a folder
2. Entire folder contents will be compressed and downloaded

---

### 5. "Search" Tab
Search for files and folders recursively.

**How to Search:**
1. Enter file or folder name to search for
2. Click "Search"
3. Results will show all matches in subfolders

**Tips:**
- Use lowercase for broader results
- Searching "php" will find all `.php` files
- Search is case-insensitive

**Examples:**
```
Search: "config" → Find: config.php, config.json, config.ini
Search: ".js"    → Find: all JavaScript files
Search: "backup" → Find: backup.sql, backups (folder), etc.
```

---

### 6. "Terminal" Tab
Run shell/terminal commands directly from browser.

**⚠️ Security Warning:**
- Use responsibly and only for authorized administration
- Don't share this access with untrusted people
- Some dangerous commands may be blocked

**Example Commands:**
```bash
# System information
ls -la              # List files with details
pwd                 # Show current directory
whoami              # Show current user
date                # Show date and time
uname -a            # Show OS/Kernel info

# File operations
cat file.php        # Read file contents
cp file.php backup/ # Copy file
mv file.php new.php # Rename file
mkdir newfolder     # Create folder
rm file.txt         # Delete file
chmod 755 script.sh # Change permission

# Network
curl https://example.com/file.zip -O    # Download file
ping google.com                         # Test connection
netstat -an                             # Network status
```

**How to Use:**
1. Enter command in input field
2. Click "Run"
3. Output will display below

---

### 7. "Tools" Tab
Access various important utilities and tools.

See [Tools & Utilities](#-tools--utilities) section for complete details.

---

## 🛠️ Tools & Utilities

### 1. PHP Info
Display complete information about your PHP installation.

**Information Displayed:**
- PHP version
- Installed extensions
- Configuration settings
- Memory limit, upload max size
- Server info, disable functions

**Use Case:** Debugging, troubleshooting, verify server setup

---

### 2. Image Conversion
Convert image formats and resize images.

**Features:**
- Select image from server
- Convert to different format (JPG, PNG, WebP, etc.)
- Resize with width & height (pixels)
- Set output quality (1-100)
- Save conversion result

**Example:**
```
Input:  photo.png (2000x1500px)
Process: Convert to JPG, resize to 800x600px, quality 85%
Output: photo-optimized.jpg (smaller)
```

---

### 3. Base64 Encoder/Decoder
Encode or decode Base64 text.

**Encode:**
- Input plain text
- Output: Base64 text
- Use for: URL encoding, email, data transfer

**Decode:**
- Input: Base64 string
- Output: Original text
- Verify data integrity

**Example:**
```
Encode: "Hello World" → "SGVsbG8gV29ybGQ="
Decode: "SGVsbG8gV29ybGQ=" → "Hello World"
```

---

### 4. Hash Generator
Generate hash from text using various algorithms.

**Available Algorithms:**
- MD5 (Legacy, fast)
- SHA1 (Legacy, fast)
- SHA256 (Modern, secure)

**Use Cases:**
- Verify file integrity
- Generate password hash
- Checksum data

**Example:**
```
Input: "password123"
MD5:    5f4dcc3b5aa765d61d8327deb882cf99
SHA1:   482c811da5d5b4bc6d497ffa98491e38
SHA256: ef92b778bafe771e89245d171bafed6f56d4d2722213e0e12394fbf675ace20c
```

---

### 5. .htpasswd Generator
Generate password for `.htpasswd` file (Apache).

**Use Cases:**
- Password protect folders/applications
- Basic HTTP authentication
- Additional security layer

**How to Use:**
1. Enter username
2. Enter password
3. Select algorithm (MD5, SHA)
4. Copy result to `.htpasswd` file

---

### 6. Cron Expression
Builder and tester for cron expressions.

**Cron Format:**
```
* * * * *
│ │ │ │ │
│ │ │ │ └─ Day of week (0-6)
│ │ │ └─── Month (1-12)
│ │ └───── Day of month (1-31)
│ └─────── Hour (0-23)
└───────── Minute (0-59)
```

**Examples:**
```
0 9 * * 1    → Every Monday at 9:00 AM
0 0 1 * *    → Every 1st at 00:00
*/5 * * * *  → Every 5 minutes
0 8-17 * * * → Every hour 8 AM to 5 PM
```

---

### 7. Chmod Calculator
Calculate file permission values in octal.

**Permission Bits:**
- 4 (r) = Read
- 2 (w) = Write
- 1 (x) = Execute

**Example Combinations:**
```
755 → rwxr-xr-x (executable, full for owner)
644 → rw-r--r-- (readable, write only for owner)
777 → rwxrwxrwx (full access for everyone)
700 → rwx------ (only owner access)
```

**How to Use:**
1. Select read/write/execute combination for Owner, Group, Others
2. Get octal value (example: 755)
3. Use `chmod 755 filename` in terminal

---

### 8. JSON Formatter
Format and validate JSON.

**Features:**
- Paste raw JSON
- Auto-format with indentation
- Validate JSON syntax
- Highlight errors if any
- Copy result to clipboard

**Example:**
```json
Input:
{"name":"John","age":30,"city":"Jakarta"}

Output (formatted):
{
  "name": "John",
  "age": 30,
  "city": "Jakarta"
}
```

---

### 9. Regex Tester
Test and debug PHP regular expressions.

**Features:**
- Input regex pattern
- Input string for testing
- Show all matches
- Test different patterns

**Example:**
```
Pattern: /^[a-z0-9]+@[a-z.]+$/i
String:  user@example.com
Result:  Match found! ✓
```

---

### 10. System Info
Display complete server and PHP information.

**Information Displayed:**
- OS / Kernel
- PHP Version
- Server IP Address
- Remote Client IP
- Domain / Hostname
- Disk Usage (Used / Total)
- Server Time
- Shell availability
- Memory usage
- Processor info

---

### 11. UUID Generator
Generate random UUID v4.

**Use Cases:**
- Unique identifier for database
- Session ID
- Unique token
- Random data ID

**Format:**
```
550e8400-e29b-41d4-a716-446655440000
```

---

### 12. URL Encoder/Decoder
Encode or decode URL strings.

**Encode:**
- Convert special characters to %XX
- Use for query parameters
- Safe for URLs

**Decode:**
- Convert %XX back to original characters
- Read URL parameters

**Example:**
```
Encode: "Hello World!" → "Hello%20World%21"
Decode: "Hello%20World%21" → "Hello World!"
```

---

### 13. Text Statistics
Calculate text statistics.

**Statistics Calculated:**
- Total characters (with spaces)
- Total characters (without spaces)
- Total words
- Total lines
- Total sentences

**Use Cases:**
- SEO analysis (character/word count)
- Plagiarism detection
- Content analysis

---

### 14. Password Generator
Generate secure random passwords.

**Options:**
- Password length (8-64 characters)
- Include uppercase (A-Z)
- Include lowercase (a-z)
- Include numbers (0-9)
- Include special chars (!@#$%^&*)

**Example Result:**
```
K8@mPx#nR2$vQs9L
```

---

### 15. Timestamp Converter
Convert between Unix timestamp and date.

**Convert Timestamp to Date:**
```
Input:  1234567890
Output: 2009-02-13 23:31:30
```

**Convert Date to Timestamp:**
```
Input:  2009-02-13 23:31:30
Output: 1234567890
```

---

## 🔐 Security

### ⚠️ CRITICAL: Read Security Documentation First

**Before deploying, read:** [security.md](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/security.md)

This contains comprehensive security guidelines and best practices.

### Best Practices

#### 1. Change Default Password
```php
// CRITICAL: Always change password BEFORE deploying
// DO NOT use 'admin' in production

$password = 'YourStrongPassword123!@#';

// Requirements:
// - Minimum 16 characters
// - Mix of uppercase, lowercase, numbers, special characters
// - No dictionary words or personal information
// - Example: K9#mPq2@xFvL7$bN
```

#### 2. Use HTTPS Only
- Always access via HTTPS (secure connection)
- Never use HTTP in production
- Use SSL/TLS certificate from Let's Encrypt or CA
- Add security headers for HSTS protection

```apache
# Force HTTPS in .htaccess
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

#### 3. Restrict Access by IP
```apache
# Only allow specific IPs in .htaccess
<Files fm.php>
    Order Deny,Allow
    Deny from all
    Allow from 192.168.1.100    # Your office IP
    Allow from 203.0.113.50     # Your home IP
</Files>
```

#### 4. Rename File
- Don't use obvious names like `admin.php` or `manager.php`
- Use something obscure: `c7k9m2x4_fm.php`
- Store outside public directory if possible

#### 5. Set Correct Permissions
```bash
# File permissions
chmod 600 fm.php        # Read/write for owner only
chmod 644 .htaccess     # Read for all, write for owner

# Directory permissions
chmod 755 ./            # Standard directory
chmod 700 ./            # Restricted access

# Upload directory
chmod 755 uploads/

# Verify
ls -la fm.php
# Output: -rw------- (600 is ideal)
```

#### 6. Disable Shell Exec (Optional)
If you don't need terminal features:
```php
# In php.ini
disable_functions = shell_exec, exec, system, passthru, proc_open
```

#### 7. Regular Backups
- Backup all files and data regularly
- Store backups in secure, off-site location
- Test backup recovery procedures
- Encrypt sensitive backups

#### 8. Monitor Access
- Check server logs regularly
- Monitor failed login attempts
- Review file modification logs
- Set up alerts for suspicious activity

#### 9. Keep Software Updated
- Update PHP to latest version
- Update server software
- Install security patches immediately
- Use automatic updates where possible

#### 10. Implement 2-Factor Authentication (Optional)
Consider adding:
```php
// TOTP (Time-based One-Time Password)
// Code-based 2FA verification
// Email/SMS confirmation
// IP whitelist combination
```

---

### Regular Security Tasks

| Task | Frequency | Priority |
|---|---|---|
| Change password | Quarterly | High |
| Review logs | Weekly | High |
| Update PHP | As needed | Critical |
| Update server | Monthly | High |
| Backup data | Daily | Critical |
| Security audit | Annually | High |
| Penetration test | Annually | Medium |

---

### Common Vulnerabilities to Prevent

| Vulnerability | Prevention |
|---|---|
| **Brute Force Attack** | Implement rate limiting, strong password, IP whitelist |
| **SQL Injection** | Use parameterized queries, prepared statements |
| **Cross-Site Scripting (XSS)** | Escape output, use CSP headers |
| **File Upload Malware** | Validate file types, restrict uploads, scan files |
| **Path Traversal** | Validate file paths, restrict access |
| **Session Hijacking** | Use HTTPS, secure cookies, regenerate IDs |
| **MITM Attack** | Use HTTPS, HSTS headers, certificate pinning |
| **Unpatched Vulnerabilities** | Keep PHP and server updated |

---

### Security Checklist

Before going live:

```
INSTALLATION
☐ Changed default password
☐ Renamed file to something obscure
☐ Verified download integrity
☐ Uploaded via SFTP (not FTP)
☐ Set permissions to 600
☐ Removed from public root (optional)

ACCESS CONTROL
☐ IP whitelist configured
☐ HTTPS enabled
☐ Strong password set
☐ HTTP→HTTPS redirect active
☐ Security headers added

MONITORING
☐ Error logging enabled
☐ Login logging enabled
☐ Log file location secured
☐ Suspicious activity checks configured

BACKUP
☐ Backup schedule set
☐ Backup integrity verified
☐ Recovery procedure tested
☐ Off-site backup configured

ONGOING
☐ PHP updated to latest
☐ Server patches applied
☐ SSL certificate valid
☐ Logs reviewed regularly
☐ Security audit planned
```

---

### Additional Security Resources

- 📖 Read [security.md](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/security.md) for comprehensive guidelines
- 📖 Read [disclaimer.md](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/disclaimer.md) for legal responsibilities
- 🔗 OWASP Top 10: https://owasp.org/www-project-top-ten/
- 🔗 PHP Security: https://www.php.net/manual/en/security.php
- 🔗 Secure Headers: https://securityheaders.com/

---

### Report Security Issues

**DO NOT publicly disclose vulnerabilities!**

If you discover a security issue:
1. Report privately via [GitHub Issues](https://github.com/InMyMine7/InMyMine7-FileManager/issues) (mark as confidential)
2. Provide clear reproduction steps
3. Include affected versions
4. Allow 30-90 days for fix before disclosure

---

## ⚙️ Advanced Configuration

### Change Password
Edit `fm.php` and find:
```php
$password = 'admin'; // Change password as needed
```

### Change Root Directory
By default, root is the directory where `fm.php` is located. To change:
```php
$root_dir = realpath(__DIR__); // Change to desired path
```

### Disable Shell/Terminal
If shell_exec is unavailable or blocked, terminal will show a message.

### Set Upload Limit
Change in `php.ini`:
```
upload_max_filesize = 100M
post_max_size = 100M
```

---

## 🐛 Troubleshooting

### Problem: "Cannot read directory"

**Solution:**
1. Verify directory path is valid
2. Check PHP permissions (read/write)
3. Check folder ownership and chmod
```bash
chmod 755 /path/to/folder
```

---

### Problem: File Upload Failed

**Causes & Solutions:**
1. **File too large** → Increase `upload_max_filesize` in php.ini
2. **Permission denied** → Check chmod on upload folder
3. **No disk space** → Clean old files or add storage

---

### Problem: Terminal/Shell Not Working

**Causes & Solutions:**
1. **shell_exec disabled** → Enable in php.ini:
   ```
   disable_functions = (remove shell_exec)
   ```
2. **Server doesn't support** → Contact hosting provider

---

### Problem: Session Logout Not Working

**Solution:**
1. Clear browser cookies
2. Check session.save_path in php.ini is writable
3. Restart browser

---

### Problem: Uploaded Files Not Visible

**Solution:**
1. Click "Refresh" in Files tab
2. Reload page (F5)
3. Check file permissions
4. Verify folder path in URL

---

### Problem: Login Failed "Wrong Password"

**Causes & Solutions:**
1. Verify password is correct (case-sensitive)
2. Clear browser cache
3. Clear browser cookies
4. Check fm.php password configuration

---

## 📝 Tips & Tricks

### Quick Navigation
- **Home** - Quick access to root directory
- **Up** - Back to parent folder
- **Breadcrumb** - Click any part to jump directly
- **Refresh** - Update file list

### Bulk Operations
- Click "Select All" checkbox to select all files
- Or click individual checkboxes
- Use bulk action buttons (ZIP, Delete)

### File Editor
- Keyboard shortcut: `Ctrl+S` = Save
- `Tab` = Indent
- Syntax highlighting for various formats

### Upload Tips
- Drag & drop is faster than browse
- Supports multiple file upload at once
- Large files will upload progressively

### Search Tips
- Use lowercase for broader results
- Search by file extension: ".php", ".jpg"
- Recursive search includes subfolders

### Performance
- Download large files during low traffic
- Zip before downloading large folders
- Clear cache if UI loads slowly

---

## 📋 Minimum Requirements

| Requirement | Minimal | Recommended |
|---|---|---|
| PHP Version | 7.4 | 8.0+ |
| Memory | 256MB | 512MB+ |
| Disk Space | 50MB | 500MB+ |
| Browser | Chrome 80+ | Latest Version |
| Connection | 1Mbps | 10Mbps+ |

---

## 🌍 Multi-Language Support

Application supports two languages:

1. **English (🇬🇧)** - Now available
2. **Indonesian (🇮🇩)** - Also available

Toggle language by clicking flag in top right corner of interface.

---

## 📱 Responsive Design

File Manager is fully responsive and works well on:
- Desktop (1920x1080+)
- Laptop (1366x768)
- Tablet (768x1024)
- Mobile (320x568+)

---

## 🎨 UI Customization

File manager uses CSS variables that can be customized.

**Main Colors:**
- Purple: `#7c5cfc`
- Cyan: `#00f5c4`
- Pink: `#ff4d8d`
- Yellow: `#ffd166`

To change theme, edit CSS variables in the style section.

---

## 📞 Support & Documentation

- **Bug Report** - [Submit Issue](https://github.com/InMyMine7/InMyMine7-FileManager/issues)
- **Feature Request** - [Submit Feature Request](https://github.com/InMyMine7/InMyMine7-FileManager/issues)
- **Version** - InMyMine7 File Manager v4
- **Author** - InMyMine Development Team

---

## 📄 License

This project is licensed under the [MIT License](https://github.com/InMyMine7/InMyMine7-FileManager/blob/main/LICENSE).

---

## 🙏 Acknowledgments

Thanks to:
- Font Awesome for icons
- Google Fonts for typography
- Community users for feedback

---

## 🚀 Quick Start

### Quick Setup (5 Minutes)

1. **Download fm.php**
   ```bash
   wget https://raw.githubusercontent.com/InMyMine7/InMyMine7-FileManager/main/fm.php
   ```

2. **Upload to server**
   ```bash
   # Via FTP or hosting File Manager
   ```

3. **Access in browser**
   ```
   http://your-domain.com/fm.php
   ```

4. **Login with default password**
   ```
   Password: admin
   ```

5. **Change password immediately** ⚠️
   ```php
   Edit fm.php: $password = 'new_strong_password';
   ```

---

## 📊 Features Matrix

| Feature | Status |
|---|---|
| File Management | ✅ |
| File Upload | ✅ |
| File Editing | ✅ |
| File Search | ✅ |
| ZIP Creation | ✅ |
| ZIP Extraction | ✅ |
| Terminal/Shell | ✅ |
| Tools & Utilities | ✅ (15+ tools) |
| Multi-Language | ✅ (EN, ID) |
| Responsive UI | ✅ |
| Dark Theme | ✅ |
| Session Based Auth | ✅ |
| Bulk Operations | ✅ |
| File Permissions | ✅ |
| System Info | ✅ |

---

## 🔄 Update Log

### v4.0 (Current)
- Full rewrite with modern design
- 15+ tools and utilities
- Multi-language support
- Responsive mobile interface
- Premium dark theme
- Advanced search
- Terminal integration
- Bulk operations

---

## 📈 Performance Tips

1. **Optimize Images** - Use Image Converter tool
2. **Compress Files** - Zip before download
3. **Clean Old Files** - Delete unnecessary files
4. **Monitor Disk** - Check System Info regularly
5. **Use Terminal** - Command line is faster for batch operations

---

## 🎓 Learning Resources

- Read complete documentation in this README
- Explore Tools to understand capabilities
- Test features on development server first
- Use Terminal for advanced operations

---

## ✨ Version Information

```
InMyMine7 File Manager
Version:    4.0
Repository: https://github.com/InMyMine7/InMyMine7-FileManager
Author:     InMyMine Development Team
License:    MIT
Last Updated: 2026
```

---

For further help, please [open an issue](https://github.com/InMyMine7/InMyMine7-FileManager/issues) on GitHub.