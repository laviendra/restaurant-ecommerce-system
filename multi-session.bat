@echo off
echo =======================================
echo    McDonald's Multi-Session Setup
echo =======================================
echo.
echo Starting multiple servers for different sessions...
echo.

echo [1] Starting User Server (Port 8000)...
start "User Server" cmd /k "php artisan serve --host=127.0.0.1 --port=8000"

timeout /t 2 /nobreak >nul

echo [2] Starting Admin Server (Port 8001)...
start "Admin Server" cmd /k "php artisan serve --host=127.0.0.1 --port=8001"

echo.
echo =======================================
echo    Servers Started Successfully!
echo =======================================
echo.
echo USER ACCESS:
echo URL: http://localhost:8000
echo Login: user@mcd.com / user123
echo.
echo ADMIN ACCESS:
echo URL: http://localhost:8001/admin/dashboard
echo Login: admin@mcd.com / admin123
echo.
echo You can now login with different accounts
echo in different browser tabs/windows!
echo.
echo Press any key to continue...
pause >nul