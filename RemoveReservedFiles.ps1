# PowerShell Script: RemoveReservedFiles.ps1
# Deletes reserved-name files like nul, con, prn, aux, etc. from a folder.

param(
    [string]$TargetFolder = "C:\Users\Faber"
)

# Reserved device names in Windows
$reserved = @("con","prn","aux","nul",
              "com1","com2","com3","com4","com5","com6","com7","com8","com9",
              "lpt1","lpt2","lpt3","lpt4","lpt5","lpt6","lpt7","lpt8","lpt9")

Write-Host "Scanning $TargetFolder for reserved-name files..." -ForegroundColor Cyan

foreach ($name in $reserved) {
    $path = "\\?\$TargetFolder\$name"
    if (Test-Path -LiteralPath $path) {
        Write-Host "Found reserved file: $name ... deleting" -ForegroundColor Yellow
        cmd /c del "$path"
        if (-not (Test-Path -LiteralPath $path)) {
            Write-Host "Deleted: $name ✅" -ForegroundColor Green
        } else {
            Write-Host "Failed to delete: $name ❌" -ForegroundColor Red
        }
    }
}

Write-Host "Scan complete." -ForegroundColor Cyan
