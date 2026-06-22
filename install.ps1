# ──────────────────────────────────────────────
#   EngiFolio Installer for Windows
# ──────────────────────────────────────────────

# function to update PATH after installation
function Refresh-Path {
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")
}

Write-Host ""
Write-Host "============================="
Write-Host "   EngiFolio Installer"
Write-Host "============================="
Write-Host ""
Write-Host "Windows detected."
Write-Host ""

# Verify winget
if (!(Get-Command winget -ErrorAction SilentlyContinue)) {
    Write-Host "winget not found. Please update Windows."
    exit 1
}

# PHP 8.4
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "Installing PHP 8.4..."
    winget install PHP.PHP.8.4 --silent --accept-package-agreements --accept-source-agreements
    Refresh-Path
} else {
    Write-Host "PHP already installed."
}

if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "PHP could not be loaded. Please restart PowerShell and run the script again."
    exit 1
}

# Enable PHP extensions
Write-Host "Configuring PHP extensions..."

$phpIniPath = $null

$phpIniOutput = php --ini 2>&1 | Select-String "Loaded Configuration File"
if ($phpIniOutput) {
    $detected = ($phpIniOutput -replace "Loaded Configuration File:\s+", "").Trim()
    if ($detected -ne "(none)" -and (Test-Path $detected)) {
        $phpIniPath = $detected
    }
}

if (!$phpIniPath) {
    $phpDir = Split-Path (Get-Command php).Source
    $phpIniPath = "$phpDir\php.ini"
    $iniDev = "$phpDir\php.ini-development"
    if (!(Test-Path $phpIniPath) -and (Test-Path $iniDev)) {
        Copy-Item $iniDev $phpIniPath
        Write-Host "Created php.ini from php.ini-development"
    }
}

if ($phpIniPath -and (Test-Path $phpIniPath)) {
    Write-Host "Configuring $phpIniPath"
    $iniContent = Get-Content $phpIniPath

    $extensions = @(
        "fileinfo", "curl", "mbstring", "openssl",
        "pdo_mysql", "mysqli", "xml", "zip",
        "gd", "intl", "bcmath", "tokenizer"
    )

    foreach ($ext in $extensions) {
        $iniContent = $iniContent -replace ";extension=$ext", "extension=$ext"
    }

    $iniContent | Set-Content $phpIniPath
    Write-Host "PHP extensions enabled."
} else {
    Write-Host "Warning: Could not find or create php.ini."
}

# Composer
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "Installing Composer..."
    $composerInstaller = "$env:TEMP\composer-setup.exe"
    Invoke-WebRequest -Uri "https://getcomposer.org/Composer-Setup.exe" -OutFile $composerInstaller
    Start-Process -FilePath $composerInstaller -Args "/VERYSILENT /NORESTART" -Wait
    Remove-Item $composerInstaller
    Refresh-Path
} else {
    Write-Host "Composer already installed."
}

if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "Composer could not be loaded. Please restart PowerShell and run the script again."
    exit 1
}

# Node.js
if (!(Get-Command node -ErrorAction SilentlyContinue)) {
    Write-Host "Installing Node.js..."
    winget install OpenJS.NodeJS.LTS --silent --accept-package-agreements --accept-source-agreements
    Refresh-Path
} else {
    Write-Host "Node.js already installed."
}

if (!(Get-Command node -ErrorAction SilentlyContinue)) {
    Write-Host "Node.js could not be loaded. Please restart PowerShell and run the script again."
    exit 1
}

# MySQL
if (!(Get-Command mysql -ErrorAction SilentlyContinue)) {
    Write-Host "Installing MySQL..."
    winget install Oracle.MySQL --silent --accept-package-agreements --accept-source-agreements
    Refresh-Path
} else {
    Write-Host "MySQL already installed."
}

# Find MySQL bin and add to PATH
$mysqlBin = $null
$mysqlPaths = @(
    "C:\Program Files\MySQL\MySQL Server 8.4\bin",
    "C:\Program Files\MySQL\MySQL Server 8.0\bin",
    "C:\Program Files\MySQL\MySQL Server 8.1\bin",
    "C:\Program Files\MySQL\MySQL Server 8.2\bin",
    "C:\Program Files\MySQL\MySQL Server 8.3\bin"
)

foreach ($path in $mysqlPaths) {
    if (Test-Path "$path\mysql.exe") {
        Write-Host "Found MySQL at $path"
        $mysqlBin = $path
        $env:Path = "$path;" + $env:Path
        [System.Environment]::SetEnvironmentVariable("Path", "$path;" + [System.Environment]::GetEnvironmentVariable("Path","Machine"), "Machine")
        break
    }
}

if (!$mysqlBin) {
    Write-Host "Could not find MySQL installation."
    exit 1
}

# Initialize data directory if needed
$mysqlDataPaths = @(
    "C:\ProgramData\MySQL\MySQL Server 8.4\Data",
    "C:\ProgramData\MySQL\MySQL Server 8.0\Data"
)

$dataExists = $false
foreach ($dataPath in $mysqlDataPaths) {
    if (Test-Path $dataPath) { $dataExists = $true; break }
}

if (!$dataExists) {
    Write-Host "Initializing MySQL data directory..."
    & "$mysqlBin\mysqld.exe" --initialize-insecure
    Start-Sleep -Seconds 5
}

# Register and start MySQL service
$svc = Get-Service -Name "MySQL*" -ErrorAction SilentlyContinue | Select-Object -First 1
if (!$svc) {
    Write-Host "Registering MySQL service..."
    & "$mysqlBin\mysqld.exe" --install MySQL84
    Start-Sleep -Seconds 2
    $svc = Get-Service -Name "MySQL84" -ErrorAction SilentlyContinue
}

if ($svc) {
    Write-Host "Starting MySQL service ($($svc.Name))..."
    Start-Service -Name $svc.Name -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 3
} else {
    Write-Host "Could not start MySQL. Please start it manually from Services then press Enter."
    Read-Host "Press Enter to continue"
}

if (!(Get-Command mysql -ErrorAction SilentlyContinue)) {
    Write-Host "MySQL not found. Please restart PowerShell and run the script again."
    exit 1
}

# BACKEND SETUP
Write-Host ""
Write-Host "Setting up backend..."
Write-Host ""

Set-Location "$PSScriptRoot\backend"

composer install
if ($LASTEXITCODE -ne 0) { Write-Host "composer install failed."; exit 1 }

if (!(Test-Path .env)) {
    Copy-Item .env.example .env
    Write-Host "Created .env file."
} else {
    Write-Host ".env already exists, skipping."
}

php artisan key:generate

# DATABASE
Write-Host ""
Write-Host "Database setup"
Write-Host "--------------"

$DB_USER = Read-Host "MySQL username (press enter for default: root)"
if ([string]::IsNullOrWhiteSpace($DB_USER)) { $DB_USER = "root" }

$DB_PASS_SECURE = Read-Host "MySQL password (leave blank and press enter if none)" -AsSecureString
$DB_PASS = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($DB_PASS_SECURE))

$DB_NAME = Read-Host "Database name (press enter for default: engifolio)"
if ([string]::IsNullOrWhiteSpace($DB_NAME)) { $DB_NAME = "engifolio" }

if ([string]::IsNullOrWhiteSpace($DB_PASS)) {
    mysql -u $DB_USER -e "CREATE DATABASE IF NOT EXISTS ``$DB_NAME``;"
} else {
    mysql -u $DB_USER -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS ``$DB_NAME``;"
}
if ($LASTEXITCODE -ne 0) {
    Write-Host "Failed to create database. Check your MySQL credentials."
    exit 1
}

(Get-Content .env) -replace 'DB_DATABASE=.*', "DB_DATABASE=$DB_NAME" | Set-Content .env
(Get-Content .env) -replace 'DB_USERNAME=.*', "DB_USERNAME=$DB_USER" | Set-Content .env
(Get-Content .env) -replace 'DB_PASSWORD=.*', "DB_PASSWORD=$DB_PASS" | Set-Content .env
(Get-Content .env) -replace 'APP_URL=.*', "APP_URL=http://localhost:8000" | Set-Content .env

php artisan migrate
if ($LASTEXITCODE -ne 0) { Write-Host "Migration failed."; exit 1 }

php artisan storage:link

composer require barryvdh/laravel-dompdf

# SEED
Write-Host ""
$SEED = Read-Host "Seed the database with sample data? (y/n)"
if ($SEED -eq "y" -or $SEED -eq "Y") {
    php artisan migrate:fresh --seed
    Write-Host "Database seeded."
}

# FRONTEND SETUP
Write-Host ""
Write-Host "Setting up frontend..."
Write-Host ""

Set-Location "$PSScriptRoot\frontend"

npm install
if ($LASTEXITCODE -ne 0) { Write-Host "npm install failed."; exit 1 }

# DONE
Write-Host ""
Write-Host "============================="
Write-Host "   Setup complete!"
Write-Host "============================="
Write-Host ""
Write-Host "To run the app open two terminals:"
Write-Host ""
Write-Host "  Terminal 1 (backend):"
Write-Host "    cd backend"
Write-Host "    php artisan serve"
Write-Host ""
Write-Host "  Terminal 2 (frontend):"
Write-Host "    cd frontend"
Write-Host "    npm run dev"
Write-Host ""
Write-Host "Then open http://localhost:5173 in your browser."
Write-Host ""
