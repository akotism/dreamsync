<?php
// Start the session
session_start();

// Check if session variables are set
if (!isset($_SESSION["ID"]) || !isset($_SESSION["dname"])) {
    echo "Session data not set. Please log in again.";
    exit;
}

require_once("config.php");

try {
    $db = get_db();

    // Prepare statement to fetch the doctor's name
    $bindDoc = $db->prepare("SELECT dname FROM Doctor WHERE DID = ?");
    $bindDoc->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if (!$bindDoc->execute()) {
        throw new Exception("Failed to execute doctor query: " . implode(", ", $bindDoc->errorInfo()));
    }

    $docArr = $bindDoc->fetchAll(PDO::FETCH_ASSOC);
    if (count($docArr) > 0) {
        $_SESSION["dname"] = $docArr[0]['dname'];
    } else {
        echo "Doctor not found.";
        exit;
    }

    // Query to fetch the doctor's patients ordered by name
    $patientQuery = $db->prepare("
        SELECT pname AS Name, Age, Sex, Height, Weight
        FROM Patient
        WHERE DID = ?
        ORDER BY pname
    ");
    $patientQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if ($patientQuery->execute()) {
        $patients = $patientQuery->fetchAll(PDO::FETCH_ASSOC);
    } else {
        throw new Exception("Failed to execute patient query: " . implode(", ", $patientQuery->errorInfo()));
    }

} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
        }

        /* Black Buttons */
        .btn-black {
            background-color: black;
            color: white;
            border: none;
        }

        .btn-black:hover {
            background-color: #333;
        }

        /* Navigation Cards */
        .card {
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Table Styling */
        table {
            margin-top: 20px;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Footer */
        footer {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-light">
    <header class="bg-primary text-white text-center py-4">
        <h1>Welcome, Dr. <?php echo htmlspecialchars($_SESSION["dname"]); ?>!</h1>
    </header>
    <div class="container my-5">
        <!-- Navigation Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Sleep Reports</h5>
                        <p class="card-text">Access detailed reports for your patients.</p>
                        <a href="doctorData.html" class="btn btn-black">View Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Polysomnograms</h5>
                        <p class="card-text">Analyze patient polysomnogram data.</p>
                        <a href="doctorPolysomnogram.html" class="btn btn-black">View Data</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Update Patient Info</h5>
                        <p class="card-text">Modify or update patient information.</p>
                        <a href="doctorUpdate.html" class="btn btn-black">Update Info</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-3">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Create Polysomnogram</h5>
                        <p class="card-text">Add new polysomnogram data for a patient.</p>
                        <a href="createPS.html" class="btn btn-black">Create Data</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Medications & Conditions</h5>
                        <p class="card-text">Check patients' meds and conditions.</p>
                        <a href="viewPMC.html" class="btn btn-black">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Delete Account</h5>
                        <p class="card-text">Permanently remove your account.</p>
                        <a href="deleteDoctor.php" class="btn btn-black">Delete Account</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Table -->
        <div class="mt-5">
            <h2>Your Patients</h2>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Height</th>
                        <th>Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($patient['Name']); ?></td>
                            <td><?php echo htmlspecialchars($patient['Age']); ?></td>
                            <td><?php echo htmlspecialchars($patient['Sex']); ?></td>
                            <td><?php echo htmlspecialchars($patient['Height']); ?></td>
                            <td><?php echo htmlspecialchars($patient['Weight']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Logout Button -->
        <div class="text-center mt-4">
            <form action="logout.php" method="post">
                <button type="submit" class="btn btn-black">Logout</button>
            </form>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        © 2024 DreamSync
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
