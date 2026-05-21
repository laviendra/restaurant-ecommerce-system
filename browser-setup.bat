@echo off
echo =======================================
echo    McDonald's Browser Setup
echo =======================================
echo.
echo This will open multiple browser windows
echo with different profiles for testing
echo.

echo [1] Opening Chrome for User Account...
start chrome --new-window --user-data-dir="%TEMP%\chrome-user" "http://localhost:8000"

timeout /t 2 /nobreak >nul

echo [2] Opening Chrome for Admin Account...
start chrome --new-window --user-data-dir="%TEMP%\chrome-admin" "http://localhost:8000/admin/dashboard"

echo.
echo =======================================
echo    Browser Windows Opened!
echo =======================================
echo.
echo WINDOW 1 (User Profile):
echo - Login: user@mcd.com / user123
echo - Access: Customer features
echo.
echo WINDOW 2 (Admin Profile):
echo - Login: admin@mcd.com / admin123
echo - Access: Admin dashboard
echo.
echo Each window has separate session storage!
echo.
pause