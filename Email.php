<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST["name"];
    $address = $_POST["address"];
    $services = $_POST["services"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $day = $_POST["day"];

    // Connect to your database (replace these details with your actual database credentials)
    $servername = "your_server_name";
    $username = "your_username";
    $password = "your_password";
    $dbname = "proairduct";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch data from the table
    $sql = "SELECT * FROM get_appointments WHERE name = '$name' AND email = '$email'";

    $result = $conn->query($sql);

    // Check if data is found
    if ($result->num_rows > 0) {
        // Fetch data
        $row = $result->fetch_assoc();

        // Compose JSON response
        $response = [
            "name" => $row["name"],
            "address" => $row["address"],
            "services" => $row["services"],
            "email" => $row["email"],
            "phone" => $row["phone"],
            "date" => $row["date"],
            "time" => $row["time"],
            "day" => $row["day"],
        ];

        // Output JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Data not found
        echo json_encode(["error" => "Data not found"]);
    }

    // Close connection
    $conn->close();

    // Define prices for each service
    $prices = [
        "Duct Cleaning" => 269,
        "Dryer Vents" => 169,
        "Large House" => 379,
        "Air Vents" => 199,
    ];

    // Compose email message
    $subject = "Appointment Request";
    $message = "Name: $name\n";
    $message .= "Address: $address\n";
    $message .= "Services Needed: $services\n";
    $message .= "Email: $email\n";
    $message .= "Phone Number: $phone\n";
    $message .= "Date: $date\n";
    $message .= "Time: $time\n";
    $message .= "Day: $day\n\n";

    // Additional company details
    $message .= "Payment: Pay after the service is done. No payment information is required.\n";
    $message .= "Company Details: We are a local company based in Corona, CA. Registered, certified, and insured.\n";
    $message .= "Free Estimates: Free estimates over the phone.\n";
    $message .= "Contractors: We don't use third-party contractors like most others.\n";
    $message .= "Equipment: We use machines specifically designed for air vents cleaning.\n";
    $message .= "Services Offered: Residential and commercial.\n";

    // Calculate and append the price to the email message
    $price = isset($prices[$services]) ? $prices[$services] : "Price not available";
    $message .= "Service Price: $$price\n";

    // Your email address where you want to receive the form submissions
    $to = "your@example.com";

    // Headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully!";
        // You can redirect the user to a thank you page or perform other actions here
    } else {
        echo "Error sending email.";
    }
}
?>
