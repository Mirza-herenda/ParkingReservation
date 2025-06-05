function calculatePrice() {
    const zoneId = parseInt($("#zone").val());
    const duration = parseInt($("input[name='duration']:checked").val());
    const pricePerHour = 2;
    const totalPrice = pricePerHour * duration;

    // Display price
    $("#totalPrice").text(totalPrice.toFixed(2) + " KM");
    return totalPrice;
}

function saveUserData() {
    event.preventDefault();
    console.log("Creating parking spot and reservation...");

    const zona = parseInt(document.getElementById("zone").value);
    const priceText = document.getElementById("totalPrice").textContent;
    const price = parseFloat(priceText.replace(" KM", ""));
    const duration = parseInt($("input[name='duration']:checked").val());
    const userId = localStorage.getItem("userId") || 93;

    const parkingData = {
        zona: zona,
        price: price,
        status: "available",
    };

    console.log("📦 Parking data:", parkingData);

    RestClient.postForParkingSpot(
        parkingData,
        (res) => {
            const spotId = res?.id || res?.data?.id;
            if (!spotId) {
                console.error("❌ Parking spot ID not returned:", res);
                alert("Greška: Nije moguće dobiti ID novog parking mjesta.");
                return;
            }

            const now = new Date();
            const formattedDate = 
                now.getFullYear() + 
                "-" + String(now.getMonth() + 1).padStart(2, "0") + 
                "-" + String(now.getDate()).padStart(2, "0") + 
                " " + String(now.getHours()).padStart(2, "0") + 
                ":" + String(now.getMinutes()).padStart(2, "0") + 
                ":00";

            const reservationPayload = {
                user_id: userId,
                parkingSpot_id: spotId,
                dateAndTime: formattedDate,
                zone: zona,
                location: "Trg Heroja 12, Sarajevo",
                duration: duration,
                price: price,
                status: "reserved"
            };

            console.log("📦 Reservation payload:", reservationPayload);

            RestClient.createNewReservation(
                reservationPayload,
                function(reservationResponse) {
                    console.log("✅ Reservation created successfully:", reservationResponse);
                    alert("Parking mjesto i rezervacija su uspješno kreirani!");
                    RestClient.getReservationsByUserId(userId);
                },
                function(err) {
                    console.error("❌ Failed to create reservation:", err);
                    alert("Greška pri kreiranju rezervacije: " + err.message);
                }
            );
        },
        (err) => {
            console.error("❌ Greška pri kreiranju parking mjesta:", err);
            alert("Greška pri kreiranju parking mjesta: " + err.message);
        }
    );
}

function getCurrentUserId() {
    return localStorage.getItem("userId");
}

function loadUserData() {
    const userId = getCurrentUserId();
    if (userId) {
        RestClient.getSingleUser(userId);
    }
}

function updateUserData() {
    const userId = localStorage.getItem("userId");
    if (!userId) {
        toastr.error("Niste prijavljeni.");
        return;
    }

    const userData = {
        name: document.getElementById("firstName").value.trim(),
        surname: document.getElementById("lastName").value.trim(),
        email: document.getElementById("email").value.trim(),
        carModel: document.getElementById("carModel").value.trim(),
        carColor: document.getElementById("carColor").value.trim(),
        licencePlate: document.getElementById("licensePlate").value.trim()
    };

    console.log("Sending user update:", userData);

    RestClient.put(
        `users/${userId}`,
        userData,
        function(response) {
            toastr.success("Podaci uspješno ažurirani!");
            console.log("Update successful:", response);
        },
        function(error) {
            console.error("Update failed:", error);
            toastr.error("Greška pri ažuriranju podataka");
        }
    );
}

function cancelReservation(button) {
    const reservationId = button.getAttribute("data-id");
    if (!reservationId) {
        console.error("Reservation ID not found.");
        return;
    }
    if (!confirm("Otkazati ovu rezervaciju?")) return;

    RestClient.delete(
        `parkingreservations/${reservationId}`,
        {},
        () => {
            const row = button.closest("tr");
            if (row) row.remove();
            alert("Rezervacija otkazana.");
        },
        (err) => {
            console.error("Greška pri otkazivanju:", err);
            alert("Greška: " + (err.responseJSON?.message || "Network error"));
        }
    );
}

function deleteMessage(button) {
    const messageId = button.getAttribute("data-id");
    if (!messageId) {
        console.error("Reservation ID not found.");
        return;
    }
    if (!confirm("Obrisati ovu poruku?")) return;

    RestClient.delete(
        `messages/${messageId}`,
        {},
        () => {
            const row = button.closest("tr");
            if (row) row.remove();
            alert("poruka obrisana.");
        },
        (err) => {
            console.error("Greška pri otkazivanju:", err);
            alert("Greška: " + (err.responseJSON?.message || "Network error"));
        }
    );
}

function cancelAllReservations(userId) {
    if (!confirm("Obrisati sve vaše rezervacije?")) return;

    RestClient.delete(
        `parkingreservations/user/${userId}`,
        {},
        (response) => {
            alert(`${response.deletedCount} rezervacija obrisano.`);
            document.getElementById("reservation-table").innerHTML = "";
        },
        (err) => {
            console.error("Greška pri brisanju svih:", err);
            alert("Greška: " + (err.responseJSON?.message || "Network error"));
        }
    );
}

function registrationHandler() {
    event.preventDefault();
    
    const userData = {
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        name: document.getElementById("firstName").value,
        surname: document.getElementById("lastName").value,
        carModel: document.getElementById("carModel").value,
        carColor: document.getElementById("carColor").value,
        licencePlate: document.getElementById("carPlate").value,
        role: 'user'  // Default role
    };

    console.log("Registering with data:", userData);

    RestClient.registerUser(
        userData,
        function(response) {
            console.log("Registration successful:", response);
            toastr.success("Registration successful!");
            setTimeout(() => spa.navigate('login'), 1500);
        },
        function(error) {
            console.error("Registration failed:", error);
            toastr.error(error.message || "Registration failed");
        }
    );
}

function LogInHandler() {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    console.log("Attempting login with:", { email });

    RestClient.login(
        email,
        password,
        function(response) {
            console.log("Login successful:", response);
            const route = response.role === 'admin' ? 'admin-home' : 'user-home';
            spa.navigate(route);
        },
        function(error) {
            console.error("Login failed:", error);
            toastr.error(error.message || "Login failed");
        }
    );
}

function updateZone(id) {
    const name = document.getElementById(`zoneName_${id}`).value;
    const capacity = document.getElementById(`zoneCapacity_${id}`).value;
    const price = document.getElementById(`zonePrice_${id}`).value;

    const payload = {
        ZoneName: name,
        zoneCapacity: Number(capacity),
        zonePrice: Number(price),
    };

    RestClient.put(
        `zones/${id}`,
        payload,
        function (response) {
            alert("Zone updated successfully!");
            console.log("Updated zone:", response);
        },
        function (err) {
            console.error("Error updating zone:", err);
            alert("Failed to update zone.");
        }
    );
}
function sendMessage(event) {
    event.preventDefault(); // Prevent form from submitting normally
    
    const messageData = {
        emailAdress: document.getElementById('email').value,
        title: document.getElementById('title').value,
        message: document.getElementById('description').value,
        time: new Date().toISOString(),
        user_id: localStorage.getItem("userId") // Default to 93 if not set
    };

    // Validate required fields
    if (!messageData.emailAdress || !messageData.title || !messageData.message) {
        toastr.error("Please fill in all required fields");
        return;
    }

    console.log("Sending message data:", messageData);

    RestClient.post(
        'messages',
        messageData,
        function(response) {
            toastr.success('Message sent successfully!');
            
            console.log("Message sent successfully:", response);
        },
        function(error) {
            console.error("Failed to send message:", error);
            toastr.error('Failed to send message: ' + (error.message || 'Unknown error'));
        }
    );
}

function logout() {
    localStorage.removeItem("userRole");
    localStorage.removeItem("userName");
    localStorage.removeItem("userId");
    localStorage.removeItem("token"); // Clear the token
    spa.navigate("login");
}