<?php
include "connect.php";

if (isset($_GET['inventory_id'])) {
    $inventory_id = $conn->real_escape_string($_GET['inventory_id']);

    // Fetch inventory details
    $sql_inventory = "SELECT * FROM inventory WHERE id = $inventory_id";
    $result_inventory = $conn->query($sql_inventory);
    $inventory = $result_inventory->fetch_assoc();

    // Fetch maintenance equipment related to the inventory
    $sql_maintenance = "SELECT * FROM maintenance WHERE built_num = '{$inventory['built_num']}'";
    $result_maintenance = $conn->query($sql_maintenance);

    if ($result_maintenance->num_rows > 0) {
        echo "<table class='borrowed-table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Type</th>";
        echo "<th>Name</th>";
        echo "<th>Brand</th>";
        echo "<th>Built number</th>";
        echo "<th>Color</th>";
        echo "<th>arrival_date</th>";
        echo "<th>Maintenance Date</th>";
        echo "<th>Comment</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $result_maintenance->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
            echo "<td>" . htmlspecialchars($row['built_num']) . "</td>";
            echo "<td>" . htmlspecialchars($row['color']) . "</td>";
            echo "<td>" . htmlspecialchars($row['arrival_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['maintenance_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No maintenance record found.</p>";
    }
} else {
    echo "<p>Invalid inventory ID.</p>";
}

$conn->close();
?>
