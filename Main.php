<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Buy";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Pagination //
$limit = 12; // Number of rows to display per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$start = ($page - 1) * $limit; // Starting row index for the current page
// Query to fetch data from the table with pagination
$sql = "SELECT * FROM `new_bridge_swap` LIMIT $start, $limit";
$result = $conn->query($sql);
// Query to get the total number of rows
$totalRowsQuery = "SELECT COUNT(*) as count FROM `new_bridge_swap`";
$totalRowsResult = $conn->query($totalRowsQuery);
$totalRows = $totalRowsResult->fetch_assoc()['count'];
// Calculate the total number of pages
$totalPages = ceil($totalRows / $limit);
// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <link rel="stylesheet" href="Main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <title>Buy Token Layout</title>
</head>
<body>
    <!-- Our Main Content  -->
  <div class="main">
       <!-- Pagination links -->
       <?php
// $totalPages = 10; // replace with your total pages count
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// calculate start and end page numbers
$startPage = $currentPage > 5 ? $currentPage - 2 : 1;
$endPage = $startPage + 4;
if ($endPage > $totalPages) {
    $endPage = $totalPages;
}

// generate navigation links
echo '<ul class="pagination pagination-sm justify-content-end">';


if ($currentPage > 1) {
  echo '<li class="page-item"><a class="page-link" href="?page=1">First</a></li>';
}
// previous page link
if ($currentPage > 1) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
}

// generate page navigation links
for ($i = $startPage; $i <= $endPage; $i++) {
    if ($i == $currentPage) {
        echo '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    } else {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
}

// next page link
if ($currentPage < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
}

// last page link
if ($currentPage < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">Last</a></li>';
}

echo '</ul>';
?>
<!--        
       <ul class="pagination pagination-sm justify-content-end">
    <?php
    // for ($i = 1; $i <= $totalPages; $i++) {
        // echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
    // }
    // ?>
    <li class="page-item">
        <input type="text" id="goToPageInput" class="form-control" placeholder="Go to Page">
        <button  class="go"  onclick="goToPage()" class="btn btn-primary">Go</button>
    </li>
</ul>

<script>
    function goToPage() {
        var page = document.getElementById("goToPageInput").value;
        window.location.href = "?page=" + page;
    }
</script> -->
    <div class="tab-content justify-content-center" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <table class="table table-hover">
          <thead>
            <tr>
              <th  class="text-center" scope="col">Time</th>
              <th class="text-center" scope="col" style="width: 20%;">Requesting Address</th>
              <th class="text-center" scope="col" style="width: 15%;">Receiving Address</th>
              <th class="text-center" scope="col" >Amount</th>
              <th class="text-center" scope="col" >Status</th>
              <th class="text-center" scope="col" >Transaction_id</th>
              <th class="text-center" scope="col" >Request Type</th>


            </tr>
          </thead>
          <tbody>
          <?php 
if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    $dest_addr_short = substr($row["destination_wallet_address"], 0, 10) . "*****" . substr($row["destination_wallet_address"], -10);
    $src_addr_short = substr($row["source_wallet_address"], 0, 10) . "*****" . substr($row["source_wallet_address"], -10);
    $src_trnsc_short = substr($row["approve_txnHash"], 0, 10) . "*****" . substr($row["approve_txnHash"], -10);

    echo "<tr class=data ><td data-label='Time'>" . $row["request_date"] . "</td><td <td data-label='Requesting Address'>" . $dest_addr_short . "</td><td <td data-label='Receiving Address'>" . $src_addr_short . "</td><td <td data-label='Amount'>" . $row["token_swap"] . "</td><td <td data-label='Status' class=status>";
    if ($row["approve_status"] == 1) {
      echo "<button class=complete>Complete</button>";
    } else {
      echo "<button class=pending>Pending</button>";
    }
    echo "</td>";
    
    echo "</td><td <td data-label='Transaction_id'>" .$src_trnsc_short. "</td>";


    echo "<td <td data-label='Request Type'>";
    if ($row["source_data"] == 1 && $row["destination_data"] == 2) {
      echo "<span> <img src='algo.png'><i class='fa fa-arrow-right'></i></i><img class='bep' src='bep.png'></span>";
  } elseif ($row["source_data"] == 2 && $row["destination_data"] == 1) {
      echo "<span> <img class='bep' src='bep.png'><i class='fa fa-arrow-right'></i></i><img  src='algo.png'></span>";
  }
  if ($row["source_data"] == 1 && $row["destination_data"] == 3) {
    echo "<span> <img src='algo.png'><i class='fa fa-arrow-right'></i></i><img src='eth.png'></span>";
} elseif ($row["source_data"] == 3 && $row["destination_data"] == 1) {
    echo "<span> <img src='eth.png'><i class='fa fa-arrow-right'></i></i><img  src='algo.png'></span>";
}


    echo "</td></tr>";
  }
} else {
  echo "<tr><td colspan='6'>0 results</td></tr>";
}
?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">THIS IS SECOND PAGE IN THIS PAGE WE WANT TO ADD TABLE DATA</div>
    </div>
  </div>

</body>
</html>
