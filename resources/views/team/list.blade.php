<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Premier League</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->
    <style>
        .table {
            display: table;
        }

        .row {
            display: table-row;
            width: 100%;
        }

        .row > div {
            display: table-cell;
        }

        .table-caption {
            display: table-caption;
            white-space: nowrap;
        }


        .teams-table {
            width: 50%;
        }

        .teams-table .col {
            width: 20%;
        }

    </style>
</head>
<body>
<div class="table" style="width: 50%">
    <div class="row">
        <div class="col">
            <div class="table-caption">
                League Table
            </div>
            <div class="table teams-table">
                <div class="row">
                    <div>Teams</div>
                    <div>PTS</div>
                    <div>P</div>
                    <div>W</div>
                    <div>D</div>
                    <div>L</div>
                    <div>GD</div>
                </div>
                @foreach($teams as $team)
                    <div class="row">
                        <div>{{ $team->title }}</div>
                        <div>{{ $team->pts }}</div>
                        <div>{{ $team->plays }}</div>
                        <div>{{ $team->wins }}</div>
                        <div>{{ $team->draws }}</div>
                        <div>{{ $team->loses }}</div>
                        <div>{{ $team->goals_scored - $team->goals_received }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @if(isset($matches) && !empty($matches))
            <div class="col">
                <div class="table-caption">
                    Match Results
                </div>
                @foreach($matches as $match)
                <div class="row">
                    <div> </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    <a href="/start-season">Start Season</a>
</div>
</body>
</html>
