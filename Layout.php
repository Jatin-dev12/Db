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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <title>Buy Token Layout</title>
</head>
<body>
    <!-- Our Main Content  -->
  <div class="main">
       <!-- Pagination links -->
       
     <ul class="pagination justify-content-end">
          <?php
         for ($i = 1; $i <= $totalPages; $i++) {
           echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
         }
          ?>
        </ul>
    <div class="tab-content justify-content-center" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <table class="table table-hover">
          <thead>
            <tr>
              <th class="text-center">Time</th>
              <th class="text-center" style="width: 20%;">Requesting Address</th>
              <th class="text-center" style="width: 15%;">Receiving Address</th>
              <th class="text-center">Amount</th>
              <th class="text-center">Status</th>
              <th class="text-center">Transaction_id</th>
              <th class="text-center">Request Type</th>


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
            
                echo "<tr class=data ><td>" . $row["request_date"] . "</td><td>" .
                $dest_addr_short . "</td><td>" . $src_addr_short . "</td><td >" . $row["token_swap"] . "</td><td class=status>";
            
                if ($row["approve_status"] == 1) {
                  echo "<button class=complete>Complete</button>";
                } else {
                  echo "<button class=pending>Pending</button>";
                }
            
                echo "</td><td>" .$src_trnsc_short. "</td><td>";
                
                
                
                if ($row["source_data"] == 1  ){
                  echo "<span> <img src='algo.png' alt='cacca'  </span>";
                }
                else{
                  echo "<span> <img src='eth.png' alt='cacca'  </span>";
                }
              }

echo "</td></tr>" ;

            } else {
              echo "<tr><td colspan='6'>0 results</td></tr>";
            }
            // 
            ?>

          </tbody>
        </table>

       
        
      </div>
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">THIS IS SECOND PAGE IN THIS PAGE WE WANT TO ADD TABLE DATA</div>
    </div>
  </div>

</body>
</html>
