@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64
php "%BIN_TARGET%" %*
