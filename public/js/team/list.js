$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function updateMatchesTable(matches, week) {
    let tableMatches = $("#table-matches");
    tableMatches.empty();
    $.each(matches, function (index, match) {
        let row = "";
        row += "<tr>";
        row += "<td>" + match.home_team_title + "</td>";
        row += "<td>" + match.home_score + "</td>";
        row += "<td>-</td>";
        row += "<td>" + match.away_score + "</td>";
        row += "<td>" + match.away_team_title + "</td>";
        row += "</tr>";
        tableMatches.append(row);
    });
    $(".week").html(week);
}

function updateLeagueTable(teams) {
    let tableLeague = $("#table-league");
    tableLeague.empty();
    $.each(teams, function (index, team) {
        let row = '';
        row += '<tr>';
        row += '<td>' + team.title + '</td>';
        row += '<td>' + team.pts + '</td>';
        row += '<td>' + team.plays + '</td>';
        row += '<td>' + team.wins + '</td>';
        row += '<td>' + team.draws + '</td>';
        row += '<td>' + team.loses + '</td>';
        row += '<td>' + (team.goals_scored - team.goals_conceded) + '</td>';
        row += '</tr>';
        tableLeague.append(row);
    });
}
function handleEndOfSeason(winner) {
    let winnerElement = $("#winner");
    winnerElement.append(winner + " won the PL cup :)");
    winnerElement.animate({
        fontSize: "2em",
        specialEasing: {
            width: "linear",
            height: "easeOutBounce"
        },
        duration: 5000
    });
    let btnNextWeek = $("#btn-next-week");
    btnNextWeek.data('week', 0);
    btnNextWeek.addClass('btn-danger');
    btnNextWeek.html('Next Season!');
}
function playWeek(week) {
    $.ajax({
        type: 'POST',
        url: '/play-week',
        data: {week: week},
        success: function (results) {
            updateMatchesTable(results.matches, week);
            updateLeagueTable(results.teams);
            if (results.winner) {
                handleEndOfSeason(results.winner);
            } else {
                let btnNextWeek = $("#btn-next-week");
                btnNextWeek.data('week', (week + 1));
                btnNextWeek.html('Next Week!');
            }
        }
    });
}

function playAllWeeks(week) {
    $.ajax({
        type: 'POST',
        url: '/play-all',
        data: {week: week},
        success: function (results) {
            let maxWeek = 2 * (Object.keys(results.teams).length - 1);
            updateMatchesTable(results.matches, maxWeek);
            updateLeagueTable(results.teams);
            handleEndOfSeason(results.winner);
        }
    });
}
function predictChampion() {
    $.ajax({
        type: 'POST',
        url: '/predict-champion',
        success: function (result) {
            let tablePredictions =("#table-predictions");
            console.log(result);
            $.each(result, function (id, prob) {
                $("#pre-row-" + id).find(".prediction").html("%" + prob);
            });
        }
    });
}

$(document).ready(function () {
    $("#btn-next-week").on('click', function () {
        let week = $(this).data('week');
        if (week) {
            playWeek(week);
            if(week === 4) {
                predictChampion(week);
            }
        } else {
            location.reload();
        }
    });

    $("#btn-play-all").on('click', function () {
        let week = $("#btn-next-week").data('week');
        if (week) {
            playAllWeeks(week);
            predictChampion(week);
        } else {
            location.reload();
        }
    });
});
