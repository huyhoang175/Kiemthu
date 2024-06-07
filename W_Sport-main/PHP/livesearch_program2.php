<?php
if (isset($_POST['action'])) {

    if ($_POST['action'] == 'fetchData') {
        $query = "SELECT programs.*, organizations.name as organization_name FROM programs 
        INNER JOIN organizations ON programs.organization_id = organizations._id ORDER BY _id DESC ";
        getData($query);
    }

    if ($_POST['action'] == 'searchRecord') {
        $program_title = $_POST['program_title'];
        $query = "SELECT programs.*, organizations.name as organization_name FROM programs 
        INNER JOIN organizations ON programs.organization_id = organizations._id WHERE title LIKE '%$program_title%' ORDER BY _id DESC";
        getData($query);
    }

    if ($_POST['action'] == 'searchBySport') {
        $program_sport = $_POST['program_sport'];

        $query = "SELECT programs.*, organizations.name as organization_name FROM programs 
        INNER JOIN organizations ON programs.organization_id = organizations._id WHERE sport = '$program_sport' ORDER BY _id DESC";
        getData($query);
    }

    if ($_POST['action'] == 'searchByType') {
        $program_type = $_POST['program_type'];

        $query = "SELECT programs.*, organizations.name as organization_name FROM programs 
        INNER JOIN organizations ON programs.organization_id = organizations._id WHERE type = '$program_type' ORDER BY _id DESC";
        getData($query);
    }
}

function getData($query)
{
    include("ConnectData.php");
    $output = "";
    $total_row = mysqli_query($connect, $query) or die('error');

    if (mysqli_num_rows($total_row) > 0) {
        foreach ($total_row as $row) {
            // Kiểm tra và xử lý khóa 'img'
            $img = isset($row['img']) ? $row['img'] : 'default_image.jpg';

            $output .= '
            <a class="container" href="../PHP/programDetail.php?programId=' . urlencode($row['_id']) . '">
                <div class="profile">
                    <img src="' . $img . '" alt="Profile Picture">
                </div>
                <div class="info">
                    <p class="program_name">' . $row['title'] . '</p>';
                    
            if ($row['openRegister'] != 1) {
                $output .= '<p class="program_title" style="color: red;">LOCK</p>';
            }

            $output .= '
                    <p class="program_title">Sport: ' . $row['sport'] . '</p>
                    <p class="program_organ">Organization: ' . $row['organization_name'] . '</p>
                    <p class="program_address">Location: ' . $row['location'] . '</p>
                </div>
            </a>';
        }
    
    }
     else {
        $output = "<h4>Data not found</h4>";
    }
    echo $output;
}

