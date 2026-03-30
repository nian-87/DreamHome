<?php
include '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['delete'])) {
    $property_no = $_GET['delete'];
    $sql = "DELETE FROM Property WHERE PropertyNo = :property_no";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':property_no', $property_no);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Property deleted successfully!</div>";
    }
}

$sql = "SELECT p.*, CONCAT(s.FName, ' ', s.LName) as manager, b.BranchName as branch_name 
        FROM Property p 
        LEFT JOIN Staff s ON p.StaffNo = s.StaffNo 
        LEFT JOIN Branch b ON p.BranchNo = b.BranchNo 
        ORDER BY p.PropertyNo";
$stmt = $db->prepare($sql);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Property Management</h2>
        <button class="btn btn-light" onclick="location.href='add.php'">+ Add New Property</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Property No</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Type</th>
                        <th>Rooms</th>
                        <th>Monthly Rent</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </thead>
                <tbody>
                    <?php foreach ($properties as $property): ?>
                    <tr>
                        <td><?php echo $property['PropertyNo']; ?></td>
                        <td><?php echo $property['StreetName']; ?></td>
                        <td><?php echo $property['City']; ?></td>
                        <td><?php echo $property['PropertyType']; ?></td>
                        <td><?php echo $property['Rooms']; ?></td>
                        <td><?php echo formatMoney($property['RentAmount']); ?></td>
                        <td><?php echo $property['manager']; ?></td>
                        <td>
                            <?php if ($property['Status'] == 'Available'): ?>
                                <span class="badge bg-success">Available</span>
                            <?php elseif ($property['Status'] == 'Rented'): ?>
                                <span class="badge bg-warning">Rented</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Withdrawn</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="location.href='view.php?property_no=<?php echo $property['PropertyNo']; ?>'">View</button>
                            <button class="btn btn-sm btn-warning" onclick="location.href='edit.php?property_no=<?php echo $property['PropertyNo']; ?>'">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="if(confirm('Delete this property?')) location.href='index.php?delete=<?php echo $property['PropertyNo']; ?>'">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>