# Repairs XAMPP MariaDB when it shuts down unexpectedly.
# Run in PowerShell (preferably as Administrator):
#   powershell -ExecutionPolicy Bypass -File C:\xampp\htdocs\leysafaris\scripts\repair-xampp-mysql.ps1

$ErrorActionPreference = 'Stop'
$Xampp = 'C:\xampp'
$MysqlData = Join-Path $Xampp 'mysql\data'
$MysqlBackup = Join-Path $Xampp 'mysql\backup\mysql'
$MysqlBin = Join-Path $Xampp 'mysql\bin'
$Stamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$RepairBackup = Join-Path $MysqlData "_repair-backup-$Stamp"

Write-Host '==> Stopping MySQL if running...'
Get-Process mysqld -ErrorAction SilentlyContinue | Stop-Process -Force
Start-Sleep -Seconds 2

if (-not (Test-Path $MysqlBackup)) {
    throw "Missing backup system tables at $MysqlBackup"
}

Write-Host "==> Backing up current mysql system tables to $RepairBackup"
New-Item -ItemType Directory -Path $RepairBackup -Force | Out-Null
if (Test-Path (Join-Path $MysqlData 'mysql')) {
    Copy-Item -Path (Join-Path $MysqlData 'mysql') -Destination (Join-Path $RepairBackup 'mysql') -Recurse -Force
}

Write-Host '==> Restoring clean mysql system tables from XAMPP backup'
Remove-Item -Path (Join-Path $MysqlData 'mysql') -Recurse -Force -ErrorAction SilentlyContinue
Copy-Item -Path $MysqlBackup -Destination (Join-Path $MysqlData 'mysql') -Recurse -Force

Write-Host '==> Removing corrupted InnoDB redo logs and temp files'
foreach ($file in @('ib_logfile0', 'ib_logfile1', 'ibtmp1', 'mysql.pid', 'mysqld.dmp')) {
    $path = Join-Path $MysqlData $file
    if (Test-Path $path) {
        Remove-Item $path -Force
        Write-Host "    removed $file"
    }
}

Write-Host '==> Removing broken replication junk files'
Get-ChildItem -Path $MysqlData -File | Where-Object {
    $_.Name -like 'master-*' -or
    $_.Name -like 'relay-log-*' -or
    $_.Name -like 'mysql-relay-bin-*'
} | ForEach-Object {
    Remove-Item $_.FullName -Force
    Write-Host "    removed $($_.Name)"
}

Write-Host '==> Resetting Aria log (delete control + logs together)'
foreach ($file in @('aria_log_control')) {
    $path = Join-Path $MysqlData $file
    if (Test-Path $path) {
        Remove-Item $path -Force
        Write-Host "    removed $file"
    }
}
Get-ChildItem -Path $MysqlData -File -Filter 'aria_log.*' -ErrorAction SilentlyContinue | ForEach-Object {
    Remove-Item $_.FullName -Force
    Write-Host "    removed $($_.Name)"
}

Write-Host '==> Repairing Aria tables in mysql system database'
$ariaChk = Join-Path $MysqlBin 'aria_chk.exe'
if (Test-Path $ariaChk) {
    Push-Location $MysqlData
    try {
        Get-ChildItem -Path (Join-Path $MysqlData 'mysql') -Filter '*.MAI' | ForEach-Object {
            $table = $_.FullName
            Write-Host "    aria_chk -r $table"
            & $ariaChk -r $table 2>$null
        }
    } finally {
        Pop-Location
    }
}

Write-Host ''
Write-Host 'Done. Start MySQL from XAMPP Control Panel.'
Write-Host "If it still fails, your user databases may need InnoDB recovery."
Write-Host "Previous mysql system tables were saved to: $RepairBackup"
