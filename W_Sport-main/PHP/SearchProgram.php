<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Program</title>
    <link rel="stylesheet" href="../css/search_program.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>


    <div class="navbar">
        <div class="navbar-content">
            <div class="navbar-item">
                <div class="logo">
                    <a href="../PHP/Index.php" class="logo-title">
                        <h2 title="Sport Management">SportManagement</h2>
                    </a>
                </div>

                <div class="nav_profile">
                    <div class="dashboard">
                        <p class="goto"><a href="../PHP/itemmenu.php">Go to Dashboard</a></p>
                        <p><img src="../img/profile.jpg" alt=""></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="header">Search Public Program</div>
        <div class="items">
            <form action="" method="post" class="form-item">
                <input class="search" type="text" placeholder="Search" id="search_title">
                <select id="sport" name="sportlist" form="sportform">
                    <option value="">Sport</option>
                    <option value="volleyball">VolleyBall</option>
                    <option value="badminton">Badminton</option>
                    <option value="tenis">Tenis</option>
                </select>
                <select id="all" name="all" form="all" class="inf_program">
                    <option value="">Type</option>
                    <option value="tounament">Tounament</option>
                    <option value="league">League</option>
                    <option value="camp">Camp</option>
                    <option value="class">Class</option>
                    <option value="training">Training</option>
                    <option value="event">Event</option>
                    <option value="club">Club</option>
                </select>
            </form>

        </div>
        <div id="searchresult"></div>

    </div>
    <script>
        function saveProgramId(programId) {
            <?php $_SESSION['program_id'] = $programId; ?>
        } 
    </script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        fetchdata();

        function fetchdata() {
            var action = 'fetchData';
            $.ajax({
                url: "livesearch_program2.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    $('#searchresult').html(data);
                }
            });
        }

        $(document).ready(function() {
            $('#search_title').keyup(function(event) {
                event.preventDefault();
                var action = 'searchRecord';
                var program_title = $('#search_title').val();
                if (program_title != '') {

                    $.ajax({
                        url: "livesearch_program2.php",
                        method: "POST",
                        data: {
                            action: action,
                            program_title: program_title
                        },
                        success: function(data) {
                            $('#searchresult').html(data);
                        }
                    })
                }
            });
        });

        $('#sport').on('change', function() {
            event.preventDefault();
            var action = 'searchBySport';
            var program_sport = $(this).val();
            $.ajax({
                url: "livesearch_program2.php",
                method: "POST",
                data: {
                    action: action,
                    program_sport: program_sport
                },
                success: function(data) {
                    $('#searchresult').html(data);

                }
            });
        });

        $('#all').on('change', function() {
            event.preventDefault();
            var action = 'searchByType';
            var program_type = $(this).val();
            $.ajax({
                url: "livesearch_program2.php",
                method: "POST",
                data: {
                    action: action,
                    program_type: program_type
                },
                success: function(data) {
                    $('#searchresult').html(data);

                }
            });
        });
    </script>

</body>

</html>