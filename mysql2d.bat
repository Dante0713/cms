
cd "public"
php index.php orm:convert:mapping --from-database annotation "result"
php index.php orm:generate-entities "result"
pause