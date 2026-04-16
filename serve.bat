@echo off
set PHP="C:\Users\cehs\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.NTS.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
echo Starting Laravel dev server...
start "Laravel" cmd /k "%PHP% artisan serve"
echo Starting Vite dev server...
start "Vite" cmd /k "npm run dev"
echo.
echo Servers started:
echo   Laravel: http://127.0.0.1:8000
echo   Filament: http://127.0.0.1:8000/admin
echo.
echo Admin login: admin@abas.lt / password
