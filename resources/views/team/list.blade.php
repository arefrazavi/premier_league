<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premier League</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">

    <!-- Javascript -->
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="application/javascript" src="{{ URL::asset('js/team/list.js') }}"></script>
</head>
<body>
<div class="container-fluid">
    <h1 class="text-center">Welcome to Premier League Stimulation!</h1>
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <table class="table table-striped">
                                    <caption>League Table</caption>
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Teams</th>
                                        <th scope="col">PTS</th>
                                        <th scope="col">P</th>
                                        <th scope="col">W</th>
                                        <th scope="col">D</th>
                                        <th scope="col">L</th>
                                        <th scope="col">GD</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-league">
                                    @foreach($teams as $team)
                                        <tr>
                                            <td>{{ $team->title }}</td>
                                            <td>{{ $team->pts }}</td>
                                            <td>{{ $team->plays }}</td>
                                            <td>{{ $team->wins }}</td>
                                            <td>{{ $team->draws }}</td>
                                            <td>{{ $team->loses }}</td>
                                            <td>{{ $team->goals_scored - $team->goals_conceded }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table class="table table-striped">
                                    <caption>
                                        Match Results - Week <span class="week">1</span>
                                    </caption>
                                    <tbody id="table-matches">
                                    @foreach($matches as $match)
                                        <tr>
                                            <td>{{ $match->home_team_title }}</td>
                                            <td>
                                                @if(isset($match->home_score))
                                                    {{ $match->home_score }}
                                                @else
                                                    ?
                                                @endif
                                            </td>
                                            <td>-</td>
                                            <td>
                                                @if(isset($match->away_score))
                                                    {{ $match->away_score }}
                                                @else
                                                    ?
                                                @endif
                                            </td>
                                            <td>{{ $match->away_team_title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div>
                        <button id="btn-play-all" class="btn btn-warning">Play All!</button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div>
                        <button id="btn-next-week" class="btn btn-primary" data-week="1">Start the Season!</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <table class="table table-bordered">
                <caption> Predictions of Championships - Week <span class="week">1</span></caption>
                <tbody id="table-predictions">
                    @foreach($teams as $team)
                        <tr id="pre-row-{{ $team->id }}">
                            <td>{{ $team->title }}</td>
                            <td class="prediction">?</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h3 id="winner" class="text-center"></h3>
        </div>
    </div>
</div>
</body>
</html>
