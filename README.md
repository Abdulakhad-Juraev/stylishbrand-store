#stylishbrand-store 

• Author: [Ulugbek](https://github.com/Ulugbek-Muhammadjonov) <br>
• Telegram: [@U_Muhammadjonov](https://t.me/@U_Muhammadjonov) <br>

## Usage <br>

☝️ Program launch procedure!
************************************************
⭐️ Download the project from git

⭐️ Connect the project to the database

⭐️ Run the following commands through console

⭐️ For translations: Import the database from this folder into your new database:

Path: <b><i>common\modules\translation\db.sql</i></b>

************************************************

```
php init
```

and

```
composer install
```

and

```
yii migrate --migrationPath=@yii/rbac/migrations/
```

and

```
yii migrate
```

and

```
yii migrate/up --migrationPath=@common/modules/tempUser/migrations
```
