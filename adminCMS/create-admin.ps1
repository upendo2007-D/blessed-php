param(
    [Parameter(Mandatory = $true)]
    [string]$Email
)

$securePassword = Read-Host "Admin password (minimum 12 characters)" -AsSecureString
$passwordPointer = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($securePassword)

try {
    $env:BLESSED_ADMIN_PASSWORD = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($passwordPointer)
    & C:\xampp\php\php.exe (Join-Path $PSScriptRoot 'create_admin.php') $Email
    if ($LASTEXITCODE -ne 0) {
        exit $LASTEXITCODE
    }
}
finally {
    Remove-Item Env:BLESSED_ADMIN_PASSWORD -ErrorAction SilentlyContinue
    [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($passwordPointer)
}
