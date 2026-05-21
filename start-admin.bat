@echo off
echo =======================================
echo    McDonald's Admin Server
echo =======================================
echo.
echo Starting admin server on port 8001...
echo Admin URL: http://localhost:8001/admin/dashboard
echo.
echo Login credentials:
echo Email: admin@mcd.com
echo Password: admin123
echo.
echo Press Ctrl+C to stop the server
echo =======================================
echo.

php artisan serve --host=127.0.0.1 --port=8001