<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
 session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
    }

require_once("connection/connectionConfig.php");

$user_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$user_query->bindValue(':client_id', $_SESSION["user_id"], PDO::PARAM_INT);

$user_query->execute();

$user = $user_query->fetch(PDO::FETCH_ASSOC);

$chart_query = $dbh->prepare("SELECT Caza_Name, COUNT(Client_Id) AS Client_Count FROM Client,Caza WHERE Caza.Caza_Id=Client.Caza_Id GROUP BY Caza_Name");
$chart_query->execute();
$chart_data = $chart_query->fetchAll(PDO::FETCH_ASSOC);


$query_str="SELECT Client_FullName, COUNT(Request_Id) AS Request_Count FROM Client,Request WHERE Client.Client_Id = Request.Client_Id ";
if($user["Client_Type_Id"]!=1){
    $query_str=$query_str."AND Client.Client_Id= ". $user["Client_Id"];
}
$query_str=$query_str. " GROUP BY Client_FullName";

$requestPerClientQuery = $dbh->prepare($query_str);

$requestPerClientQuery->execute();
$requestPerClientData = $requestPerClientQuery->fetchAll(PDO::FETCH_ASSOC);



$query_sttr="SELECT Request_Type,Client_Id,COUNT(Request_Id) AS Request_Category_Count FROM Request_Type,Request WHERE Request_Type.Request_Type_Id = Request.Request_Type_Id ";
if($user["Client_Type_Id"]!=1){
    $query_sttr=$query_sttr . " AND Request.Client_Id= " . $user["Client_Id"];
}
$query_sttr=$query_sttr . " GROUP BY Request_Type ";
$requestPerCategorieQuery = $dbh->prepare($query_sttr);
$requestPerCategorieQuery->execute();
$requestPerCategoryData = $requestPerCategorieQuery->fetchAll(PDO::FETCH_ASSOC);

function rowCount($dbh,$query){
    $stmt=$dbh->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, php, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>PGD Dashboard</title>

    <link href="css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
      <?php include("sidebar.php") ?>

        <div class="main">

          <?php include("heading.php") ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

                    <div class="row">
                        <div class="col-xl-6 col-xxl-5 d-flex">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?php if ($user["Client_Type_Id"] === 1) : ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Client Number</h5>

                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="truck"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT * FROM `Client`")?></h1>

                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($user["Client_Type_Id"] === 1) : ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT * FROM `Request`")?></h1>

                                            </div>
                                        </div>
                                        <?php else: ?>
                                            <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Your Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT * FROM `Request` WHERE Client_Id = " . $user["Client_Id"]) ?></h1>

                                            </div>
                                        </div>


                                        <?php endif; ?>
                                        <?php if ($user["Client_Type_Id"] === 1) : ?>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Solved Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="check"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT Status.Status_Id FROM Request,Status  WHERE Request.Status_Id=Status.Status_Id AND Status_Name='solved'")?></h1>

                                            </div>
                                        </div>
                                        <?php else: ?>
                                         <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Solved Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="check"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT Status.Status_Id FROM Request,Status  WHERE Request.Status_Id=Status.Status_Id AND Status_Name='solved' AND Request.Client_Id = " . $user["Client_Id"])?></h1>

                                            </div>
                                        </div>

                                        <?php endif; ?>
                                        <?php if ($user["Client_Type_Id"] === 1) : ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Unsolved Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="x"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT Status.Status_Id FROM Request,Status  WHERE Request.Status_Id=Status.Status_Id AND Status_Name='unsolved'")?></h1>

                                            </div>
                                        </div>
                                        </div>
                                        <?php else: ?>
                                            <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Unsolved Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                          <i class="align-middle" data-feather="x"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT Status.Status_Id FROM Request,Status  WHERE Request.Status_Id=Status.Status_Id AND Status_Name='unsolved' AND Request.Client_Id = " . $user["Client_Id"])?></h1>

                                            </div>
                                        </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($user["Client_Type_Id"] === 1) : ?>
                                        <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Pending Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="shopping-cart"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT Status.Status_Id FROM Request,Status  WHERE Request.Status_Id=Status.Status_Id AND Status_Name='pending'")?></h1>

                                            </div>
                                        </div>
                                        <?php else: ?>
                                            <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Pending Requests</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="clipboard"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3"><?php echo rowCount($dbh,"SELECT Status.Status_Id FROM Request,Status  WHERE Request.Status_Id=Status.Status_Id AND Status_Name='pending' AND Request.Client_Id = " . $user["Client_Id"])?></h1>

                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-xxl-7">
                        <?php if ($user["Client_Type_Id"] === 1) : ?>
                            <div class="card flex-fill w-100">
                                <div class="card-header">
Number of clients per caza</h5>
                                </div>
                                <div class="card-body py-3">
                                    <div class="chart chart-sm">
                                        <canvas id="chartjs-dashboard-line"></canvas>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="card flex-fill w-100">
    <div class="card-header">
        Number of Requests per Client
    </div>
    <div class="card-body py-3">
        <div class="chart chart-sm">
            <canvas id="chartjs-requests-per-client"></canvas>
        </div>
    </div>
</div>
<div class="card flex-fill w-100">
    <div class="card-header">
        Number of Requests per Type
    </div>
    <div class="card-body py-3">
        <div class="chart chart-sm">
            <canvas id="chartjs-request-per-type"></canvas>
        </div>
    </div>
</div>
                        </div>


                    </div>

            </main>

        </div>
    </div>

    <script src="js/app.js"></script>

    <script>
     document.addEventListener("DOMContentLoaded", function() {
    var cazaNames = <?php echo json_encode(array_column($chart_data, 'Caza_Name')); ?>;
    var clientCounts = <?php echo json_encode(array_column($chart_data, 'Client_Count')); ?>;

    // Create a new chart using Chart.js
    var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");

    new Chart(ctx, {
        type: "bar", // You can choose the chart type you prefer
        data: {
            labels: cazaNames,
            datasets: [{
                label: "Number of Clients",
                data: clientCounts,
                backgroundColor: window.theme.primary,
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 1, // Adjust this value based on your data
                    },
                    display: true,
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }]
            }
        }
    });
});

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    var clientNames = <?php echo json_encode(array_column($requestPerClientData, 'Client_FullName')); ?>;
    var requestCounts = <?php echo json_encode(array_column($requestPerClientData, 'Request_Count')); ?>;

    // Create a new chart for requests per client
    var ctx = document.getElementById("chartjs-requests-per-client").getContext("2d");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: clientNames,
            datasets: [{
                label: "Number of Requests",
                data: requestCounts,
                backgroundColor: window.theme.primary,
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 1, // Adjust this value based on your data
                    },
                    display: true,
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }]
            }
        }
    });
});

    </script>
     <script>
     document.addEventListener("DOMContentLoaded", function() {
    var TypeName = <?php echo json_encode(array_column($requestPerCategoryData, 'Request_Type')); ?>;
    var clientCountsPerCategory = <?php echo json_encode(array_column($requestPerCategoryData, 'Request_Category_Count')); ?>;

    // Create a new chart using Chart.js
    var ctx = document.getElementById("chartjs-request-per-type").getContext("2d");

    new Chart(ctx, {
        type: "bar", // You can choose the chart type you prefer
        data: {
            labels: TypeName,
            datasets: [{
                label: "Number of Clients",
                data: clientCountsPerCategory,
                backgroundColor: window.theme.primary,
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 1, // Adjust this value based on your data
                    },
                    display: true,
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }]
            }
        }
    });
});

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Bar chart
            new Chart(document.getElementById("chartjs-dashboard-bar"), {
                type: "bar",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "This year",
                        backgroundColor: window.theme.primary,
                        borderColor: window.theme.primary,
                        hoverBackgroundColor: window.theme.primary,
                        hoverBorderColor: window.theme.primary,
                        data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
                        barPercentage: .75,
                        categoryPercentage: .5
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: false
                            },
                            stacked: false,
                            ticks: {
                                stepSize: 20
                            }
                        }],
                        xAxes: [{
                            stacked: false,
                            gridLines: {
                                color: "transparent"
                            }
                        }]
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
            var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
            document.getElementById("datetimepicker-dashboard").flatpickr({
                inline: true,
                prevArrow: "<span title=\"Previous month\">&laquo;</span>",
                nextArrow: "<span title=\"Next month\">&raquo;</span>",
                defaultDate: defaultDate
            });
        });
    </script>

</body>

    </html>
