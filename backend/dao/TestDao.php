
<?php

require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/ReservationDao.php';
require_once __DIR__ . '/ZonesDao.php';
require_once __DIR__ . '/ParkingSpotDao.php';
require_once __DIR__ . '/MessagesDao.php';


$userDao = new UserDao();
$reservationDao = new ReservationDao();
$zoneDao = new ZonesDao();
$parkingspotDao = new ParkingSpotDao();
$messagesDao = new MessagesDao();



                                          INSERT
$userDao->insert([
    
    'name' => 'Mirza',
    'surname'=>'herenda',
    'email' => 'mirza1herenda@example.com',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
    'role' => 'user'
]);


$reservationDao->insert([
    'user_id' => 1,
    'parkingSpot_id' => 1, 
    'dateAndTime' => '2025-03-30 18:00:00',
    'zone' => 1, // Primer zone
    'location' => "radnicka 4", 
    'duration' => 2, 
    'price' => 2 
]);

//insert new zone
$zoneDao->insert([
    'id' => 11,
    'ZoneName' => "zone2", 
    'zoneCapacity' => 80,
    'zoneNumber' => 3, 
    'zonePrice' => 3,
    'location' => "ferhadija bb",
   
]);


  
$messagesDao->insert([
    'id' => 2,
    'emailAdress' => "mirzaherenda@gmail.com", 
    'title' => 'auto osteceno',
    'message' => "moje auto je u losem stanju", // Primer zone
    'time' => "2025-03-30 18:00:00", // Primer lokacije
    'user_id' => 8, // Primer trajanja u satima
   
 ]);
$parkingspotDao->insert([
    'id' =>11,
    'zona'=>11,
    'status' => "reserved",
   
]);


                                                     get

$users = $userDao->getAll();
print_r($users);

$reservation = $reservationDao->getAll();
print_r($reservation);

$zone = $zoneDao->getAll();
print_r($zone);

$messages = $messagesDao->getAll();
print_r($messages);

$parkingspot = $parkingspotDao->getAll();
print_r($parkingspot);



                                                    UPDATE

$userDao->update(1, [
    'name' => 'John Updated SECOND',
    'email' => 'john_updated2@example.com'
]);
echo "user  updated successfully.\n";


$reservationDao->update(2, [
    
    'duration' => 14,
    'location' => 'mula mehmeda bazerdzica 4',
]);
echo "Reservation updated successfully.\n";



$parkingspotDao->update(11, [
    'status' => 'occupied',
]);
echo "Parking spot updated successfully.\n";

$messagesDao->update(2, [
    'message' => 'auto je ipak u dobrom stanju',
]);
 echo "Message updated successfully.\n";

$zoneDao->update(11, [
    'zonePrice' => 6,
    'zoneCapacity' => 120,
]);
echo "Zone updated successfully.\n";


                                                    //DELETE
$userDao->delete(27);
echo "User deleted successfully.\n";
$reservationDao->delete(3);
echo "Reservation deleted successfully.\n";
$parkingspotDao->delete(11);
echo "Parking spot deleted successfully.\n";
$messagesDao->delete(2);
echo "Message deleted successfully.\n";

?>