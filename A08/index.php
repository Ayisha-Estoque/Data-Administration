<?php
include("connect.php");

$airlineNameFilter = isset($_GET['airlineName']) ? $_GET['airlineName'] : '';
$departureAirportCodeFilter = isset($_GET['departureAirportCode']) ? $_GET['departureAirportCode'] : '';
$arrivalAirportCodeFilter = isset($_GET['arrivalAirportCode']) ? $_GET['arrivalAirportCode'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : '';

$flightQuery = "SELECT * FROM flightLogs";

// Build query with filters
if ($airlineNameFilter != '' || $departureAirportCodeFilter != '' || $arrivalAirportCodeFilter != '') {
    $flightQuery = $flightQuery . " WHERE";

    if ($airlineNameFilter != '') {
        $flightQuery = $flightQuery . " airlineName='$airlineNameFilter'";
    }

    if ($airlineNameFilter != '' && ($departureAirportCodeFilter != '' || $arrivalAirportCodeFilter != '')) {
        $flightQuery = $flightQuery . " AND";
    }

    if ($departureAirportCodeFilter != '') {
        $flightQuery = $flightQuery . " departureAirportCode='$departureAirportCodeFilter'";
    }

    if ($departureAirportCodeFilter != '' && $arrivalAirportCodeFilter != '') {
        $flightQuery = $flightQuery . " AND";
    }

    if ($arrivalAirportCodeFilter != '') {
        $flightQuery = $flightQuery . " arrivalAirportCode='$arrivalAirportCodeFilter'";
    }
}

if ($sort != '') {
    $flightQuery = $flightQuery . " ORDER BY $sort";
    if ($order != '') {
        $flightQuery = $flightQuery . " $order";
    }
}

$flightResults = executeQuery($flightQuery);

$airlineNameQuery = "SELECT DISTINCT airlineName FROM flightLogs";
$airlineNameResults = executeQuery($airlineNameQuery);

$departureAirportCodeQuery = "SELECT DISTINCT departureAirportCode FROM flightLogs";
$departureAirportCodeResults = executeQuery($departureAirportCodeQuery);

$arrivalAirportCodeQuery = "SELECT DISTINCT arrivalAirportCode FROM flightLogs";
$arrivalAirportCodeResults = executeQuery($arrivalAirportCodeQuery);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PUP AIRPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="image/airport.png">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #4B4B4D;
            width: 100%;
            top: 0;
        }
        .navbarHeader {
            font-family: 'Roboto';
            color: white;
            font-size: 24px;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table th {
            background-color: #343a40;
            color: #fff;
        }

        table {
            border-radius: 5px;
            overflow: hidden;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media (min-width: 992px) {
            .table-responsive {
                overflow-x: visible;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbarHeader">PUP AIRPORT</span>
        </div>
    </nav>
    <div class="container my-5">
        <!-- Filter Form -->
        <form>
            <div class="card p-4 rounded-5">
                <h4 class="mb-4">Flight Filter</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="airlineSelect" class="form-label">Airline Name</label>
                        <select id="airlineSelect" name="airlineName" class="form-select">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($airlineNameResults) > 0) {
                                while ($airlineNameRow = mysqli_fetch_assoc($airlineNameResults)) {
                                    ?>
                                    <option value="<?= $airlineNameRow['airlineName'] ?>"
                                        <?= $airlineNameFilter == $airlineNameRow['airlineName'] ? 'selected' : '' ?>>
                                        <?= $airlineNameRow['airlineName'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="departureAirportCodeSelect" class="form-label">Departure Airport Code</label>
                        <select id="departureAirportCodeSelect" name="departureAirportCode" class="form-select">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($departureAirportCodeResults) > 0) {
                                while ($departureAirportCodeRow = mysqli_fetch_assoc($departureAirportCodeResults)) {
                                    ?>
                                    <option value="<?= $departureAirportCodeRow['departureAirportCode'] ?>"
                                        <?= $departureAirportCodeFilter == $departureAirportCodeRow['departureAirportCode'] ? 'selected' : '' ?>>
                                        <?= $departureAirportCodeRow['departureAirportCode'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="arrivalAirportCodeSelect" class="form-label">Arrival Airport Code</label>
                        <select id="arrivalAirportCodeSelect" name="arrivalAirportCode" class="form-select">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($arrivalAirportCodeResults) > 0) {
                                while ($arrivalAirportCodeRow = mysqli_fetch_assoc($arrivalAirportCodeResults)) {
                                    ?>
                                    <option value="<?= $arrivalAirportCodeRow['arrivalAirportCode'] ?>"
                                        <?= $arrivalAirportCodeFilter == $arrivalAirportCodeRow['arrivalAirportCode'] ? 'selected' : '' ?>>
                                        <?= $arrivalAirportCodeRow['arrivalAirportCode'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="sort" class="form-label">Sort By</label>
                        <select id="sort" name="sort" class="form-select">
                            <option value="">None</option>
                            <option value="departureDatetime" <?php if ($sort == "departureDatetime")
                                echo "selected"; ?>>
                                Departure DateTime
                            </option>
                            <option value="arrivalDatetime" <?php if ($sort == "arrivalDatetime")
                                echo "selected"; ?>>
                                Arrival DateTime
                            </option>
                            <option value="aircraftType" <?php if ($sort == "aircraftType")
                                echo "selected"; ?>>
                                Aircraft Type
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="order" class="form-label">Order</label>
                        <select id="order" name="order" class="form-select">
                            <option value="ASC" <?= $order == "ASC" ? 'selected' : '' ?>>Ascending</option>
                            <option value="DESC" <?= $order == "DESC" ? 'selected' : '' ?>>Descending</option>
                        </select>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>

        <!-- Results Table -->
        <div class="card p-4 rounded-5 my-5">
            <h4 class="mb-4">Flight Logs</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Flight Number</th>
                            <th>Departure Airport Code</th>
                            <th>Arrival Airport Code</th>
                            <th>Departure Date and Time</th>
                            <th>Flight Duration (Minutes)</th>
                            <th>Airline Name</th>
                            <th>Aircraft Type</th>
                            <th>Passenger Count</th>
                            <th>Ticket Price</th>
                            <th>Credit Card Number</th>
                            <th>Credit Card Type</th>
                            <th>Pilot Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($flightResults) > 0) {
                            while ($flightRow = mysqli_fetch_assoc($flightResults)) {
                                ?>
                                <tr>
                                    <td><?= $flightRow['flightNumber']; ?></td>
                                    <td><?= $flightRow['departureAirportCode']; ?></td>
                                    <td><?= $flightRow['arrivalAirportCode']; ?></td>
                                    <td><?= $flightRow['departureDatetime']; ?></td>
                                    <td><?= $flightRow['flightDurationMinutes']; ?></td>
                                    <td><?= $flightRow['airlineName']; ?></td>
                                    <td><?= $flightRow['aircraftType']; ?></td>
                                    <td><?= $flightRow['passengerCount']; ?></td>
                                    <td><?= $flightRow['ticketPrice']; ?></td>
                                    <td><?= $flightRow['creditCardNumber']; ?></td>
                                    <td><?= $flightRow['creditCardType']; ?></td>
                                    <td><?= $flightRow['pilotName']; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>