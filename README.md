Yii2 Database Config Generator
=

Database config generator.

Install
-
Put the controller in the directory console commands.

Example: `app/commands`.

Using
-
```
php yii db --dbname=name
```
or
```
php yii db/generate --dbname=name
```
After completion in the directory `app/config` file will be created configuration database `db.php`.

To display all settings, type:
```
php yii help db/generate
```
