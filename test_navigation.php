<?php
// Navigation test script
echo "<h1>Navigation Test</h1>";

$base_url = 'http://localhost:2211/rms/';
$test_urls = [
    'Dashboard' => 'dashboard',
    'Properties List' => 'properties',
    'Properties Create' => 'properties/create',
    'Customers List' => 'customers',
    'Customers Create' => 'customers/create',
    'Staff List' => 'staff',
    'Staff Create' => 'staff/create',
    'Registrations List' => 'registrations',
    'Registrations Create' => 'registrations/create',
    'Transactions List' => 'transactions',
    'Reports' => 'reports',
    'Analytics Properties' => 'analytics/properties',
    'Analytics Financial' => 'analytics/financial',
    'Analytics Customers' => 'analytics/customers'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Page</th><th>URL</th><th>Status</th><th>Action</th></tr>";

foreach ($test_urls as $name => $url) {
    $full_url = $base_url . $url;
    
    // Test if URL is accessible
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $full_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request only
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status_color = 'red';
    $status_text = 'Error';
    
    if ($http_code == 200) {
        $status_color = 'green';
        $status_text = 'OK';
    } elseif ($http_code == 404) {
        $status_color = 'orange';
        $status_text = '404 Not Found';
    } elseif ($http_code == 500) {
        $status_color = 'red';
        $status_text = '500 Server Error';
    } else {
        $status_text = "HTTP $http_code";
    }
    
    echo "<tr>";
    echo "<td>$name</td>";
    echo "<td><a href='$full_url' target='_blank'>$url</a></td>";
    echo "<td style='color: $status_color;'>$status_text</td>";
    echo "<td><a href='$full_url' target='_blank' style='background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;'>Test</a></td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Quick Navigation Links</h2>";
echo "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";
foreach ($test_urls as $name => $url) {
    $full_url = $base_url . $url;
    echo "<a href='$full_url' target='_blank' style='background: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; margin: 2px;'>$name</a>";
}
echo "</div>";

echo "<p style='margin-top: 20px;'><strong>Note:</strong> Click on any link to test the navigation. Green status means the page loads successfully.</p>";
?>