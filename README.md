# premier_league
Premier League Stimulation

The project stimulates matches for a full season of a sample league and provide predictions about probability of winning a title for each team.


To run the project do the followings:

`git clone https://github.com/arefrazavi/premier_league.git`

`cd premier_league`

`composer install`

`cp .env.example .env`

`php artisan key:generate`

Create a database (e.g. premier_league_db)

Add its credentials to .env

`php artisan migrate --seed`

`php astrisan serve`
