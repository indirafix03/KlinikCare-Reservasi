<?php
// test-fonnte.php
$token = "Cd7HTvU8q8ZsDGhdAmST"; // Token Fonnte Anda
$target = "6285167655225"; // Nomor Anda

echo "🔧 Testing Fonnte API...\n";
echo "Token: " . substr($token, 0, 10) . "...\n";
echo "Target: $target\n\n";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.fonnte.com/send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
    'target' => $target,
    'message' => '🎉 Test notifikasi dari PHP Native!\n\nIni test langsung tanpa Laravel.',
    'countryCode' => '62',
  ),
  CURLOPT_HTTPHEADER => array(
    "Authorization: $token"
  ),
));

echo "📡 Mengirim request ke Fonnte API...\n";
$response = curl_exec($curl);

if (curl_errno($curl)) {
  $error_msg = curl_error($curl);
  echo "❌ Error: " . $error_msg . "\n";
} else {
  echo "✅ Response: " . $response . "\n";
}

curl_close($curl);

// Parse JSON response
$response_data = json_decode($response, true);
if ($response_data && isset($response_data['status']) && $response_data['status']) {
    echo "\n🎉 BERHASIL! Notifikasi terkirim ke $target\n";
} else {
    echo "\n❌ GAGAL! Cek token atau quota\n";
    if (isset($response_data['message'])) {
        echo "Pesan error: " . $response_data['message'] . "\n";
    }
}
?>