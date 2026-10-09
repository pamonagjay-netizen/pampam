# Scans every folder inside gallery\ and writes gallery\manifest.js
$root = Join-Path $PSScriptRoot 'gallery'
$ext  = '.jpg','.jpeg','.png','.webp','.gif','.avif','.mp4','.webm','.mov','.m4v'
$map  = [ordered]@{}
Get-ChildItem -Path $root -Directory | Sort-Object Name | ForEach-Object {
    $names = @(Get-ChildItem -Path $_.FullName -File |
        Where-Object { $ext -contains $_.Extension.ToLower() } |
        Sort-Object Name | ForEach-Object { $_.Name })
    $map[$_.Name] = $names
    Write-Host ("{0}: {1} file(s)" -f $_.Name, $names.Count)
}
$json = $map | ConvertTo-Json -Depth 3
Set-Content -Path (Join-Path $root 'manifest.js') -Value ("window.CAMPUSMEET_GALLERY = " + $json + ";") -Encoding UTF8
Write-Host "Done - gallery\manifest.js updated."
