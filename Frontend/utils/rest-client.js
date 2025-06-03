let currentReservationCount = 0;
let AdminsPanelInformations = {
  numberOfUsers: 0,
  numberOfReservations: 0,
  totalPrice: 0,
  avaibleParkingSpots: 0,
};
let RestClient = {
  get: function (url, callback, error_callback) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + url,
      type: "GET",
      success: function (response) {
        // Remove general toastr.success
        if (callback) callback(response);
      },
      error: function (jqXHR) {
        // Remove general toastr.error
        if (error_callback) error_callback(jqXHR);
      },
    });
  },

  request: function (url, method, data, callback, error_callback) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + url,
      type: method,
      contentType: "application/json",
      data: JSON.stringify(data),
      success: function (response) {
        // Show toastr only for specific actions
        if (
          url.includes("auth") ||
          url.includes("reservation") ||
          url.includes("users/update")
        ) {
          const successMessage =
            method === "POST" && url.includes("reservation")
              ? "Rezervacija uspješno kreirana"
              : method === "PUT" && url.includes("users")
              ? "Podaci uspješno ažurirani"
              : "Operacija uspješna";
          toastr.success(successMessage);
        }
        if (callback) callback(response);
      },
      error: function (jqXHR) {
        console.error("Request failed:", jqXHR);
        // Show error toastr only for user-facing actions
        if (
          url.includes("auth") ||
          url.includes("reservation") ||
          url.includes("users/update")
        ) {
          toastr.error(jqXHR.responseJSON?.message || "Greška u zahtjevu.");
        }
        if (error_callback) {
          error_callback(jqXHR);
        }
      },
    });
  },

  post: function (url, data, callback, error_callback) {
    RestClient.request(url, "POST", data, callback, error_callback);
  },
  delete: function (url, data, callback, error_callback) {
    RestClient.request(url, "DELETE", data, callback, error_callback);
  },
  patch: function (url, data, callback, error_callback) {
    RestClient.request(url, "PATCH", data, callback, error_callback);
  },
  put: function (url, data, callback, error_callback) {
    RestClient.request(url, "PUT", data, callback, error_callback);
  },

  getAllMessages: function () {
    console.log("RestClient initialized");
    console.log("Base URL:", Constants.PROJECT_BASE_URL + "messages");

    RestClient.get(
      "messages",
      function (data) {
        console.log("Server response messages:", data);
       // toastr.success("Poruke uspješno učitane.");

        // Pretpostavljam da server vraća objekat sa poljem 'data' koje je niz poruka
        if (!data || !Array.isArray(data)) {
          console.error("Expected an array in data.data, got:", data);
          document.getElementById("messageTable").innerHTML =
            "<p style='color:red;'>Invalid data format from server.</p>";
          return;
        }

        let html = `
      <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
          <tr>
          
          </tr>
        </thead>
        <tbody>`;

        data.forEach((msg) => {
          html += `
        <tr>
          <td>${msg.id || "N/A"}</td>
          <td>${msg.emailAdress || "N/A"}</td>
          <td>${msg.title || "N/A"}</td>
          <td>${msg.message || "N/A"}</td>
          <td>${new Date(msg.time).toLocaleString() || "N/A"}</td>
             <td><button class="btn btn-sm btn-danger" data-id="${
               msg.id
             }" onclick="deleteMessage(this)">delete</button></td>

           
         
        </tr>`;
        });

        html += `</tbody></table>`;

        const messagesBody = document.getElementById("messageTable");
        if (messagesBody) {
          messagesBody.innerHTML = html;
        } else {
          console.error("Element with ID 'messages-content' not found");
          document.getElementById("messages-container").innerHTML =
            "<p style='color:red;'>Error: Table container not found.</p>";
        }
      },
      function (err) {
        console.error("Error fetching messages:", err);
        toastr.error(
          err.responseJSON?.message || "Greška pri učitavanju poruka."
        );
        const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
        document.getElementById(
          "messages-content"
        ).innerHTML = `<p style='color:red;'>Greška pri učitavanju poruka: ${errorMessage}</p>`;
      }
    );
  },
  getZoneInformations: function () {
    console.log("RestClient initialized", currentReservationCount);
    console.log("Base URL:", Constants.PROJECT_BASE_URL + "zones");

    RestClient.get(
      "zones",
      function (data) {
        console.log("Server response zones:", data);
       // toastr.success("Zone uspješno učitane.");

        if (!data || !Array.isArray(data)) {
          console.error("Expected an array in data, got:", data);
          document.getElementById("zoneTable").innerHTML =
            "<p style='color:red;'>Invalid data format from server.</p>";
          return;
        }

        let html = "";

        data.forEach((zone) => {
          html += `
      
        
          
          <label for="zoneName_${zone.id}" class="form-label">Zone Name</label>
          <input type="text" class="form-control mb-2" id="zoneName_${
            zone.id
          }" value="${zone.ZoneName || ""}" disabled>

          <label for="zoneCapacity_${
            zone.id
          }" class="form-label">Zone Capacity</label>
          <input type="number" class="form-control mb-2" id="zoneCapacity_${
            zone.id
          }" value="${zone.zoneCapacity || 0}">

          <label for="zonePrice_${
            zone.id
          }" class="form-label">Price per Hour</label>
          <input type="number" class="form-control mb-2" id="zonePrice_${
            zone.id
          }" value="${zone.zonePrice || 0}">
           <label  class="form-label">Space Remaining</label>
          <div class="form-control mb-2" style="height:auto;">
  <span style="color:gray;">${
    zone.zoneCapacity - currentReservationCount
  }</span>
  /
  <span style="color:green;">${zone.zoneCapacity}</span>



          <button class="btn w-100 mb-2" style="background-color:#1D3C6E;color: white;" onclick="updateZone(${
            zone.id
          })">Update Zone</button>
          <button class="btn btn-sm btn-danger w-100" onclick="deleteZone(${
            zone.id
          })">Delete Zone</button>
        </div>
      `;
        });

        const zoneTable = document.getElementById("zoneTable");
        if (zoneTable) {
          zoneTable.innerHTML = html;
        } else {
          console.error("Element with ID 'zoneTable' not found");
        }
      },
      function (err) {
        console.error("Error fetching zones:", err);
        toastr.error(
          err.responseJSON?.message || "Greška pri učitavanju zona."
        );
        const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
        document.getElementById(
          "zoneTable"
        ).innerHTML = `<p style='color:red;'>Greška pri učitavanju zona: ${errorMessage}</p>`;
      }
    );
  },
  deleteMessage: function (button) {
    const messageId = button.getAttribute("data-id");

    if (!messageId) {
      console.error("Reservation ID not found.");
      toastr.error("ID poruke nije pronađen.");
      return;
    }

    if (!confirm("Da li ste sigurni da želite otkazati ovu rezervaciju?")) {
      return;
    }

    RestClient.delete(
      `messages/${messageId}`,
      {},
      function (response) {
        toastr.success("Poruka uspješno obrisana.");
        // Ukloni red iz tabele bez reload-a
        const row = button.closest("tr");
        if (row) row.remove();
      },
      function (error) {
        console.error("Greška pri brisanju poruke:", error);
        toastr.error(
          error.responseJSON?.message ||
            "Došlo je do greške prilikom brisanja poruke."
        );
      }
    );
  },

  registerUser: function (userData, callback, error_callback) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/register",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(userData),
      success: function (response) {
        toastr.success("Registracija uspješna.");
        if (callback) callback(response);
        console.log("User registered successfully:", response);
      },
      error: function (jqXHR) {
        toastr.error(jqXHR.responseJSON?.message || "Greška pri registraciji.");
        if (error_callback) {
          error_callback(
            jqXHR.responseJSON || { message: "Greška pri registraciji." }
          );
        }
      },
    });
  },
  login: function (email, password, callback, error_callback) {
    // Configure toastr
    toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: "toast-top-right",
      timeOut: 3000,
      extendedTimeOut: 1000,
      showMethod: 'fadeIn',
      hideMethod: 'fadeOut',
      closeMethod: 'fadeOut',
      tapToDismiss: true,
      preventDuplicates: true,
      // Adding specific styling for success messages
      successClass: "toast-success"
    };

    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/login",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({ email: email, password: password }),
      success: function (response) {
        // Use success with options
        toastr.success("Prijava uspješna", "Uspjeh", {
          timeOut: 3000,
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
          positionClass: "toast-top-right",
        });
        console.log("Login successful:", response);
        localStorage.setItem("userId", response.data.id);
        localStorage.setItem("userRole", response.data.role);
        callback(response.data);
      },
      error: function (jqXHR) {
        toastr.error("Neuspješna prijava. Provjerite email i lozinku.");
        if (error_callback) {
          error_callback(jqXHR.responseJSON || { message: "Greška pri prijavi." });
        }
      },
    });
  },

  getSingleUser: function (userId) {
    console.log("RestClient initialized");
    console.log("Base URL:", Constants.PROJECT_BASE_URL + "users/" + userId);

    RestClient.get(
      "users/" + userId,
      function (data) {
        console.log("Server single user response:", data);
        toastr.success("Korisnički podaci uspješno učitani.");

        // Očekujemo direktno objekat korisnika
        if (!data || typeof data !== "object") {
          console.error("Expected a user object, got:", data);
          document.getElementById("user-form-container").innerHTML =
            "<p style='color:red;'>Invalid data format from server.</p>";
          return;
        }

        const user = data;

        let html = `
  <table class="table" id="user-form-table">
    <tr>
      <th>Ime:</th>
      <td >
        <input type="text"  readonly  class="form-control-plaintext" value="${
          user.name || ""
        }">
      </td>
    </tr>
    <tr>
      <th>Prezime:</th>
      <td>
        <input type="text"  readonly class="form-control-plaintext" value="${
          user.surname || ""
        }">
      </td>
    </tr>
    <tr>
      <th>Email:</th>
      <td>
        <input type="email" readonly class="form-control-plaintext" value="${
          user.emailAdress || user.email || ""
        }">
      </td>
    </tr>
    <tr>
      <th>Model Auta:</th>
      <td>
        <input type="text" readonly class="form-control-plaintext" value="${
          user.carModel || ""
        }">
      </td>
    </tr>
    <tr>
      <th>Boja:</th>
      <td>
        <input type="text" readonly class="form-control-plaintext" value="${
          user.carColor || ""
        }">
      </td>
    </tr>
    <tr>
      <th>Tablica:</th>
      <td>
        <input type="text" readonly class="form-control-plaintext" value="${
          user.licencePlate || ""
        }">
      </td>
    </tr>
  </table>
`;

        const container = document.getElementById("user-form-container");
        if (container) {
          container.innerHTML = html;
        } else {
          console.error("Element with ID 'user-form-container' not found");
        }
      },
      function (err) {
        console.error("Error fetching user:", err);
        toastr.error(
          err.responseJSON?.message || "Greška pri učitavanju korisnika."
        );
        const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
        document.getElementById(
          "user-form-container"
        ).innerHTML = `<p style='color:red;'>Greška pri učitavanju korisnika: ${errorMessage}</p>`;
      }
    );
  },

  getSingleUserEditable: function (userId) {
    console.log("RestClient initialized");
    console.log("Base URL:", Constants.PROJECT_BASE_URL + "users/" + userId);

    RestClient.get(
      "users/" + userId,
      function (data) {
        console.log("Server single user response editable:", data);
        toastr.success("Korisnički podaci uspješno učitani.");

        // Očekujemo direktno objekat korisnika
        if (!data || typeof data !== "object") {
          console.error("Expected a user object, got:", data);
          document.getElementById("user-form-container").innerHTML =
            "<p style='color:red;'>Invalid data format from server.</p>";
          return;
        }

        const user = data;

        let html = `
    <table class="table" id="user-form-table">
        <tr>
            <th>Ime:</th>
            <td>
                <input type="text" class="form-control" id="firstName" value="${
                  user.name || ""
                }">
            </td>
        </tr>
        <tr>
            <th>Prezime:</th>
            <td>
                <input type="text" class="form-control" id="lastName" value="${
                  user.surname || ""
                }">
            </td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>
                <input type="email" class="form-control" id="email" value="${
                  user.emailAdress || user.email || ""
                }">
            </td>
        </tr>
        <tr>
            <th>Model Auta:</th>
            <td>
                <input type="text" class="form-control" id="carModel" value="${
                  user.carModel || ""
                }">
            </td>
        </tr>
        <tr>
            <th>Boja:</th>
            <td>
                <input type="text" class="form-control" id="carColor" value="${
                  user.carColor || ""
                }">
            </td>
        </tr>
        <tr>
            <th>Tablica:</th>
            <td>
                <input type="text" class="form-control" id="licensePlate" value="${
                  user.licencePlate || ""
                }">
            </td>
        </tr>
    </table>

`;

        const container = document.getElementById("user-form-container");
        if (container) {
          container.innerHTML = html;
        } else {
          console.error("Element with ID 'user-form-container' not found");
        }
      },
      function (err) {
        console.error("Error fetching user:", err);
        toastr.error(
          err.responseJSON?.message || "Greška pri učitavanju korisnika."
        );
        const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
        document.getElementById(
          "user-form-container"
        ).innerHTML = `<p style='color:red;'>Greška pri učitavanju korisnika: ${errorMessage}</p>`;
      }
    );
  },

  createNewReservation: function (
    reservationData,
    callback = () => {},
    error_callback = () => {}
  ) {
    const url = "parkingreservations/create";

    // Priprema i validacija podataka
    const payload = {
      user_id: parseInt(reservationData.user_id),
      parkingSpot_id: parseInt(reservationData.parkingSpot_id),
      dateAndTime: reservationData.dateAndTime,
      zone: parseInt(reservationData.zone),
      location: reservationData.location,
      duration: reservationData.duration,
      price: parseFloat(reservationData.price),
    };

    console.log("Payload for reservation from server:", payload);
    console.log("URL for reservation:", Constants.PROJECT_BASE_URL + url);

    // Validacija osnovnih polja
    if (
      isNaN(payload.user_id) ||
      isNaN(payload.parkingSpot_id) ||
      !payload.dateAndTime ||
      isNaN(payload.zone) ||
      !payload.location ||
      !payload.duration ||
      isNaN(payload.price)
    ) {
      toastr.error("Neispravni podaci za rezervaciju.");
      return error_callback({ message: "Neispravni podaci za rezervaciju." });
    }

    RestClient.post(
      url,
      payload,
      function (response) {
        console.log("✅ Rezervacija uspješno kreirana:", response);
        toastr.success("Rezervacija uspješno kreirana"); // Keep this
        callback(response);
      },
      function (err) {
        console.error("❌ Greška pri kreiranju rezervacije:", err);
        toastr.error("Došlo je do greške prilikom kreiranja rezervacije"); // Keep this
        error_callback({ error: err, message: msg });
      }
    );
  },

  loadReservations: function () {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "parkingreservations/details/full",
      method: "GET",
      success: function (response) {
        toastr.success("Rezervacije uspješno učitane.");
        const body = document.getElementById("reservations-body");

        if (!body) {
          console.warn("⚠️ Element 'reservations-body' nije pronađen.");
          return;
        }

        body.innerHTML = "";

        console.log("Server response reservations:", response);
        if (Array.isArray(response.data) && response.data.length > 0) {
          console.log("Broj rezervacija:", response.length);
          response.data.forEach((reservation) => {
            // Parse duration and price
            const duration = parseInt(reservation.duration) || 0;
            const zonePrice = parseFloat(reservation.zone_price) || 0;
            const totalPrice = (duration * zonePrice).toFixed(2);

            // Format date properly
            const dateObj = new Date(reservation.startTime.split('.').reverse().join('-'));
            const formattedDate = dateObj.toLocaleString('bs-BA', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
            });

            const row = document.createElement("tr");
            row.innerHTML = `
              <td>${reservation.name || 'N/A'}</td>
              <td>${reservation.surname || 'N/A'}</td>
              <td>${formattedDate}</td>
              <td>${reservation.zone || 'N/A'}</td>
              <td>${duration} h</td>
              <td>${totalPrice} KM</td>
              <td>
                <button class="btn btn-sm btn-danger" 
                        onclick="RestClient.cancelReservation(this)" 
                        data-id="${reservation.id}">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            `;

            body.appendChild(row);
          });
        } else {
          body.innerHTML = "<tr><td colspan='7' class='text-center'>Nema rezervacija.</td></tr>";
        }
        localStorage.setItem("reservationsCount", response.length);
      },
      error: function (err) {
        console.error("❌ Greška pri učitavanju rezervacija:", err);
        toastr.error(err.responseJSON?.message || "Greška pri učitavanju rezervacija.");
      },
    });
  },

  cancelReservation: function (button) {
    const reservationId = button.getAttribute("data-id");

    if (!reservationId) {
      console.error("Reservation ID not found.");
      toastr.error("ID rezervacije nije pronađen.");
      return;
    }

    if (!confirm("Da li ste sigurni da želite otkazati ovu rezervaciju?")) {
      return;
    }

    RestClient.delete(
      `parkingreservations/${reservationId}`,
      {},
      function (response) {
        toastr.success("Rezervacija uspješno otkazana.");
        // Ukloni red iz tabele bez reload-a
        const row = button.closest("tr");
        if (row) row.remove();
        getAllMessages();
      },
      function (error) {
        console.error("Greška pri brisanju rezervacije:", error);
        toastr.error(
          error.responseJSON?.message ||
            "Došlo je do greške prilikom otkazivanja rezervacije."
        );
      }
    );
  },

  deleteReservationsByUserId: function () {
    const UserreservationId = button.getAttribute("data-id");

    if (!UserreservationId) {
      console.error("Reservation ID not found.");
      toastr.error("ID korisnika nije pronađen.");
      return;
    }

    if (!confirm("Da li ste sigurni da želite otkazati ovu rezervaciju?")) {
      return;
    }

    RestClient.delete(
      `parkingreservations/${UserreservationId}`,
      {},
      function (response) {
        toastr.success("Sve rezervacije uspješno otkazane.");
        // Ukloni red iz tabele bez reload-a
        const row = button.closest("tr");
        if (row) row.remove();
      },
      function (error) {
        console.error("Greška pri brisanju rezervacije:", error);
        toastr.error(
          error.responseJSON?.message ||
            "Došlo je do greške prilikom otkazivanja rezervacija."
        );
      }
    );
  },

  loadAllUsers: function () {
    console.log("RestClient initialized");
    console.log("Base URL:", Constants.PROJECT_BASE_URL + "users");

    RestClient.get(
      "users",
      function (data) {
        console.log("Server response:", data);
        toastr.success("Korisnici uspješno učitani.");

        if (!Array.isArray(data)) {
          console.error("Expected an array, got:", data);
          document.getElementById("users-table-body").innerHTML =
            "<tr><td colspan='5' style='color:red;'>Invalid data format from server.</td></tr>";
          return;
        }

        let html = "";

        data.forEach((user) => {
          const name = user.name || "Unknown";
          const email = user.email || "Unknown";
          const plate = user.licencePlate || "N/A";

          html += `
        <tr>
          <td>${name}</td>
           <td>${email}</td>
          <td>${plate}</td>
       
          <td><button class="btn btn-sm btn-danger" onclick="deleteRow(this)"><i class="bi-trash"></i></button></td>
        </tr>`;
        });

        const tableBody = document.getElementById("users-table-body");
        if (tableBody) {
          tableBody.innerHTML = html;
        } else {
          console.error("Element with ID 'users-table-body' not found.");
        }
      },
      function (err) {
        console.error("Error fetching users:", err);
        toastr.error(
          err.responseJSON?.message || "Greška pri učitavanju korisnika."
        );
        document.getElementById(
          "users-table-body"
        ).innerHTML = `<tr><td colspan="5" style='color:red;'>Greška pri učitavanju korisnika: ${errorMessage}</td></tr>`;
      }
    );
  },

  getReservationsByUserId: function (userId) {
    console.log("RestClient initialized");
    console.log(
      "Base URL:",
      Constants.PROJECT_BASE_URL + "parkingreservations/user/" + userId
    );

    RestClient.get(
      "parkingreservations/user/" + userId,
      function (response) {
        console.log("Server single reservation response:", response);
        toastr.success("Rezervacije korisnika uspješno učitane.");

        // Check if we have data in the expected format
        const reservations = response.data || response;

        if (!Array.isArray(reservations)) {
          console.error(
            "Expected array of reservations but got:",
            reservations
          );
          return;
        }

        // Find the table body element
        const tableBody = document.getElementById("reservation-table");
        if (!tableBody) {
          console.error("Reservation table element not found");
          return;
        }

        // Clear existing rows
        tableBody.innerHTML = "";

        // Add reservations to the table
        if (reservations.length === 0) {
          tableBody.innerHTML =
            '<tr><td colspan="4" class="text-center">Nema aktivnih rezervacija</td></tr>';
        } else {
          reservations.forEach(function (reservation) {
            // Format date for display
            const date = new Date(reservation.dateAndTime);
            const formattedDate = date.toLocaleString("bs-BA", {
              day: "2-digit",
              month: "2-digit",
              year: "numeric",
              hour: "2-digit",
              minute: "2-digit",
            });

            tableBody.innerHTML += `
          <tr>
            <td>${reservation.location || "Trg Heroja 12"}</td>
            <td>${formattedDate}</td>
            <td>${reservation.price} KM</td>
            <td>
              <button class="btn btn-sm btn-danger" 
                      onclick="cancelReservation(this)" 
                      data-id="${reservation.id}">
                Otkaži
              </button>
            </td>
          </tr>
        `;
          });
        }

        console.log("✅ Reservations displayed successfully");
      },
      function (err) {
        console.error("Error fetching reservations:", err);
        toastr.error(
          err.responseJSON?.message ||
            "Greška pri učitavanju rezervacija korisnika."
        );
        const tableBody = document.getElementById("reservation-table");
        if (tableBody) {
          tableBody.innerHTML =
            '<tr><td colspan="4" class="text-center text-danger">Greška pri učitavanju rezervacija</td></tr>';
        }
      }
    );
  },

  postForParkingSpot: function (
    parkingData,
    callback = () => {},
    error_callback = () => {}
  ) {
    const url = "parkingspots/create";

    // Mapiranje izlaznog JSON-a
    const payload = {
      zona: parseInt(parkingData.zona),
      price: parseInt(parkingData.price),
      status: "available",
    };
    console.log("Payload for parking spot:", payload);
    console.log("URL for parking spot:", Constants.PROJECT_BASE_URL + url);

    // Validacija
    if (isNaN(payload.zona) || isNaN(payload.price) || !payload.status) {
      toastr.error("Neispravni podaci za parking mjesto.");
      return error_callback({
        message: "Neispravni podaci za parking mjesto.",
      });
    }

    RestClient.post(
      url,
      payload,
      function (response) {
        console.log("✅ Parking mjesto dodano:", response);
        toastr.success("Parking mjesto uspješno dodano.");
        callback(response);
      },
      function (err) {
        console.error("❌ Greška pri dodavanju parking mjesta:", err);
        const msg =
          err?.responseJSON?.message ||
          "Došlo je do greške prilikom dodavanja parking mjesta.";
        toastr.error(msg);
        error_callback({ error: err, message: msg });
      }
    );
  },

  getDuration: function (start_time, end_time) {
    const start = new Date(start_time);
    const end = new Date(end_time);
    const diff = (end - start) / (1000 * 60); // Duration in minutes
    return `${Math.round(diff)} minutes`;
  },

  sendMessage: function(messageData, callback, error_callback) {
    if (!messageData.emailAdress || !messageData.title || !messageData.message) {
        toastr.error("Please fill in all required fields");
        return;
    }

    RestClient.post(
        '/messages',
        messageData,
        function(response) {
            console.log("✅ Message sent successfully:", response);
            if (callback) callback(response);
        },
        function(error) {
            console.error("❌ Failed to send message:", error);
            if (error_callback) error_callback(error);
        }
    );
  },

  // Add this method to RestClient
  loadDashboardStats: function() {
    RestClient.get(
        "api/statistics",
        function(response) {
            if (response.success && response.data) {
                const stats = response.data;
                document.getElementById("activeReservations").textContent = stats.reservations || '0';
                document.getElementById("totalUsers").textContent = stats.users || '0';
                document.getElementById("availableSpots").textContent = stats.availableSpots || '0';
                document.getElementById("todayRevenue").textContent = (stats.revenue || '0') + " KM";
            } else {
                console.error("Invalid statistics response:", response);
            }
        },
        function(error) {
            console.error("Error loading dashboard stats:", error);
            toastr.error("Failed to load dashboard statistics");
        }
    );
  },

};
