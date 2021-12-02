# dr-webapp-sandbox-public
Public version of my webapp attack sandbox. This has everything relevant from the private repo, it just leaves out some extra files that were necessary for hosting the code on Heroku
Modifications needed to set up new instance:
1. Change PROJECT_ROOT and ALT_ROOT in includes/constants.php to respective roots of the new instance
2. Change the database credentials in api/classes/postgres_heroku_db.php to the respective credentials for the new database (this code *should* work with non-heroku postgres databases, but no guarantees)
3. Optionally, change hardcoded text (mostly in index.php) to whatever you want
