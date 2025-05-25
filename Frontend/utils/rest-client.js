let RestClient = {

  get: function (url, callback, error_callback) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + url,
      type: "GET",
      success: function (response) {
        if (callback) callback(response);
      },
      error: function (jqXHR) {
        if (error_callback) error_callback(jqXHR);
      },
    });
  },

  request: function (url, method, data, callback, error_callback) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + url,
      type: method,
      data: data,
      
    })
    .done(function (response) {
      if (callback) callback(response);
    })
    .fail(function (jqXHR) {
      if (error_callback) {
        error_callback(jqXHR);
      } else {
        toastr.error(jqXHR.responseJSON?.message || "Greška u zahtjevu.");
      }
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

  RestClient.get("messages", function (data) {
    console.log("Server response messages:", data);

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

    data.forEach(msg => {
      html += `
        <tr>
          <td>${msg.id || "N/A"}</td>
          <td>${msg.emailAdress || "N/A"}</td>
          <td>${msg.title || "N/A"}</td>
          <td>${msg.message || "N/A"}</td>
          <td>${new Date(msg.time).toLocaleString() || "N/A"}</td>
             <td><button class="btn btn-sm btn-danger" data-id="${msg.id}" onclick="deleteMessage(this)">delete</button></td>

            <td><button class="btn btn-sm btn-primary">Details</button></td>
         
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

  }, function (err) {
    console.error("Error fetching messages:", err);
    const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
    document.getElementById("messages-content").innerHTML =
      `<p style='color:red;'>Greška pri učitavanju poruka: ${errorMessage}</p>`;
  });
},
getZoneInformations: function () {
  console.log("RestClient initialized");
  console.log("Base URL:", Constants.PROJECT_BASE_URL + "zones");

  RestClient.get("zones", function (data) {
    console.log("Server response zones:", data);

    if (!data || !Array.isArray(data)) {
      console.error("Expected an array in data, got:", data);
      document.getElementById("zoneTable").innerHTML =
        "<p style='color:red;'>Invalid data format from server.</p>";
      return;
    }

    let html = "";

    data.forEach(zone => {
      html += `
        <div class="card-custom p-3 border rounded mt-3">
          <h5>Zone Management - ID: ${zone.id}</h5>
          
          <label for="zoneName_${zone.id}" class="form-label">Zone Name</label>
          <input type="text" class="form-control mb-2" id="zoneName_${zone.id}" value="${zone.ZoneName || ""}" disabled>

          <label for="zoneCapacity_${zone.id}" class="form-label">Zone Capacity</label>
          <input type="number" class="form-control mb-2" id="zoneCapacity_${zone.id}" value="${zone.zoneCapacity || 0}">

          <label for="zonePrice_${zone.id}" class="form-label">Price per Hour</label>
          <input type="number" class="form-control mb-2" id="zonePrice_${zone.id}" value="${zone.zonePrice || 0}">

          <button class="btn w-100 mb-2" style="background-color:#1D3C6E;color: white;" onclick="updateZone(${zone.id})">Update Zone</button>
          <button class="btn btn-sm btn-danger w-100" onclick="deleteZone(${zone.id})">Delete Zone</button>
        </div>
      `;
    });

    const zoneTable = document.getElementById("zoneTable");
    if (zoneTable) {
      zoneTable.innerHTML = html;
    } else {
      console.error("Element with ID 'zoneTable' not found");
    }

  }, function (err) {
    console.error("Error fetching zones:", err);
    const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
    document.getElementById("zoneTable").innerHTML =
      `<p style='color:red;'>Greška pri učitavanju zona: ${errorMessage}</p>`;
  });
}
,



deleteMessage: function (button) {
    const messageId = button.getAttribute("data-id");

    if (!messageId) {
      console.error("Reservation ID not found.");
      return;
    }

    if (!confirm("Da li ste sigurni da želite otkazati ovu rezervaciju?")) {
      return;
    }

    RestClient.delete(`messages/${messageId}`, {}, function (response) {
      toastr.success("Rezervacija uspješno otkazana.");
      // Ukloni red iz tabele bez reload-a
      const row = button.closest("tr");
      if (row) row.remove();
    }, function (error) {
      console.error("Greška pri brisanju rezervacije:", error);
      toastr.error(error.responseJSON?.message || "Došlo je do greške prilikom otkazivanja rezervacije.");
    });
  },


  registerUser: function (userData, callback, error_callback) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/register",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(userData),
      success: function (response) {
        if (callback) callback(response);
        console.log("User registered successfully:", response);
       
      },
      error: function (jqXHR) {
        if (error_callback) {
          error_callback(jqXHR.responseJSON || { message: "Greška pri registraciji." });
        }
      },
    });
  },
login: function (email, password,  callback, error_callback) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "auth/login",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ email: email, password: password }),
    success: function (response) {
      console.log("Login successful:", response,email,password);
      localStorage.setItem("userId", response.data.id);     // ispravno: response.data.id
      localStorage.setItem("userRole", response.data.role); // ispravno: response.data.role
      callback(response.data); // šaljemo .data, ne ceo response
    },
    error: function (jqXHR) {
        if (error_callback) {
          error_callback(jqXHR.responseJSON || { message: "Greška pri registraciji." });
        }
      },
    });
  },
  

 getSingleUser: function (userId) {
  console.log("RestClient initialized");
  console.log("Base URL:", Constants.PROJECT_BASE_URL + "users/" + userId);

  RestClient.get("users/" + userId, function (data) {
    console.log("Server single user response:", data);

    // Očekujemo direktno objekat korisnika
    if (!data || typeof data !== "object") {
      console.error("Expected a user object, got:", data);
      document.getElementById("user-form-container").innerHTML =
        "<p style='color:red;'>Invalid data format from server.</p>";
      return;
    }

    const user = data;

    let html = `
  <table class="table">
    <tr>
      <th>Ime:</th>
      <td >
        <input type="text" readonly class="form-control-plaintext" value="${user.name || ''}">
      </td>
    </tr>
    <tr>
      <th>Prezime:</th>
      <td>
        <input type="text" readonly class="form-control-plaintext" value="${user.surname || ''}">
      </td>
    </tr>
    <tr>
      <th>Email:</th>
      <td>
        <input type="email" readonly class="form-control-plaintext" value="${user.emailAdress || user.email || ''}">
      </td>
    </tr>
    <tr>
      <th>Model Auta:</th>
      <td>
        <input type="text" readonly class="form-control-plaintext" value="${user.carModel || ''}">
      </td>
    </tr>
    <tr>
      <th>Boja:</th>
      <td>
        <input type="text" readonly class="form-control-plaintext" value="${user.carColor || ''}">
      </td>
    </tr>
    <tr>
      <th>Tablica:</th>
      <td>
        <input type="text" readonly class="form-control-plaintext" value="${user.licencePlate || ''}">
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

  }, function (err) {
    console.error("Error fetching user:", err);
    const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
    document.getElementById("user-form-container").innerHTML =
      `<p style='color:red;'>Greška pri učitavanju korisnika: ${errorMessage}</p>`;
  });
},

  loadReservations: function () {
    console.log("RestClient initialized");
    console.log("Base URL:", Constants.PROJECT_BASE_URL + "parkingreservations");

    RestClient.get("parkingreservations/details/full", function (data) {
      console.log("Server response:", data);

      if (!Array.isArray(data.  data)) {
        console.error("Expected an array, got:", data);
        document.getElementById("reservation-content").innerHTML =
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

      data.data.forEach(res => {
        html += `
          <tr>
            <td>${res.name || "N/A"}</td>
            <td>${res.surname || "N/A"}</td>
            <td>${res.startTime.toLocaleString()}</td>
            <td>${res.zone || "N/A"}</td>
            <td>${res.duration || "N/A"}h</td>
      
           <td>${res.zone_price && res.duration ? (res.zone_price * res.duration).toFixed(2) : "N/A"} KM</td>

            
           <td><button class="btn btn-sm btn-danger" data-id="${res.id}" onclick="cancelReservation(this)">delete</button></td>

            <td><button class="btn btn-sm btn-primary">Details</button></td>
          </tr>`;
      });

      html += `</tbody></table>`;

      const reservationsBody = document.getElementById("reservations-body");
      if (reservationsBody) {
        reservationsBody.innerHTML = html;
      } else {
        console.error("Element with ID 'reservations-body' not found");
        document.getElementById("reservation-content").innerHTML =
          "<p style='color:red;'>Error: Table body not found.</p>";
      }

    }, function (err) {
      console.error("Error fetching reservations:", err);
      const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
      document.getElementById("reservations-body").innerHTML =
        `<p style='color:red;'>Greška pri učitavanju rezervacija: ${errorMessage}</p>`;
    });
  },

cancelReservation: function (button) {
    const reservationId = button.getAttribute("data-id");

    if (!reservationId) {
      console.error("Reservation ID not found.");
      return;
    }

    if (!confirm("Da li ste sigurni da želite otkazati ovu rezervaciju?")) {
      return;
    }

    RestClient.delete(`parkingreservations/${reservationId}`, {}, function (response) {
      toastr.success("Rezervacija uspješno otkazana.");
      // Ukloni red iz tabele bez reload-a
      const row = button.closest("tr");
      if (row) row.remove();
      getAllMessages()
    }, function (error) {
      console.error("Greška pri brisanju rezervacije:", error);
      toastr.error(error.responseJSON?.message || "Došlo je do greške prilikom otkazivanja rezervacije.");
    });
  },


  deleteReservationsByUserId: function() {
const UserreservationId = button.getAttribute("data-id");

    if (!UserreservationId) {
      console.error("Reservation ID not found.");
      return;
    }

    if (!confirm("Da li ste sigurni da želite otkazati ovu rezervaciju?")) {
      return;
    }

    RestClient.delete(`parkingreservations/${UserreservationId}`, {}, function (response) {
      toastr.success("Rezervacija uspješno otkazana.");
      // Ukloni red iz tabele bez reload-a
      const row = button.closest("tr");
      if (row) row.remove();
    }, function (error) {
      console.error("Greška pri brisanju rezervacije:", error);
      toastr.error(error.responseJSON?.message || "Došlo je do greške prilikom otkazivanja rezervacije.");
    });
},




   loadAllUsers: function () {
  console.log("RestClient initialized");
  console.log("Base URL:", Constants.PROJECT_BASE_URL + "users");

  RestClient.get("users", function (data) {
    console.log("Server response:", data);

    if (!Array.isArray(data)) {
      console.error("Expected an array, got:", data);
      document.getElementById("users-table-body").innerHTML =
        "<tr><td colspan='5' style='color:red;'>Invalid data format from server.</td></tr>";
      return;
    }

    let html = "";

    data.forEach(user => {
      const name = user.name || "Unknown";
       const email = user.email || "Unknown";
      const plate = user.
licencePlate || "N/A";
   

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

  }, function (err) {
    console.error("Error fetching users:", err);
    const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
    document.getElementById("users-table-body").innerHTML =
      `<tr><td colspan="5" style='color:red;'>Greška pri učitavanju korisnika: ${errorMessage}</td></tr>`;
  });
},

getReservationsByUserId: function (userId) {
  console.log("RestClient initialized");
  console.log("Base URL:", Constants.PROJECT_BASE_URL + "parkingreservations/user/" + userId);

  RestClient.get("parkingreservations/user/" + userId, function (data) {
    console.log("Server single reservation response:", data.data);

    if (!Array.isArray(data.data)) {
      console.error("Expected an array of reservations, got:", data.data);
      document.getElementById("reservation-table").innerHTML =
        "<tr><td colspan='4' style='color:red;'>Invalid data format from server.</td></tr>";
      return;
    }

    let html = "";

    data.data.forEach(reservation => {
      html += `
        <tr>
          <td>${reservation.location || "N/A"}</td>
          <td>${reservation.duration || "N/A"} h</td>
          <td>${reservation.price && reservation.duration ? (reservation.price * reservation.duration).toFixed(2) : "N/A"} KM</td>
           <td><button class="btn btn-sm btn-danger" data-id="${reservation.id}" onclick="cancelReservation(this)">delete</button></td>
        </tr>
      `;
    });

    const container = document.getElementById("reservation-table");
    if (container) {
      container.innerHTML = html;
    } else {
      console.error("Element with ID 'reservation-table' not found");
    }

  }, function (err) {
    console.error("Error fetching reservation:", err);
    const errorMessage = err.responseJSON?.message || "Greška u zahtjevu.";
    document.getElementById("reservation-table").innerHTML =
      `<tr><td colspan='4' style='color:red;'>Greška pri učitavanju rezervacije: ${errorMessage}</td></tr>`;
  });
},


postForParkingSpot: function (parkingData, callback = () => {}, error_callback = () => {}) {
  const url = "parkingspots/create";

  // Mapiranje izlaznog JSON-a
  const payload = {
    zona: parseInt(parkingData.zona),           // iz parkingData.zona
    price: parseInt(parkingData.price),         // iz parkingData.price
    status: "available"                         // fiksni status
  };

  // Validacija
  if (isNaN(payload.zona) || isNaN(payload.price) || !payload.status) {
    return error_callback({ message: "Neispravni podaci za parking mjesto." });
  }

  RestClient.post(url, payload,
    function (response) {
      console.log("✅ Parking mjesto dodano:", response);
      callback(response);
    },
    function (err) {
      console.error("❌ Greška pri dodavanju parking mjesta:", err);
      const msg = err?.responseJSON?.message || "Došlo je do greške prilikom dodavanja parking mjesta.";
      error_callback({ error: err, message: msg });
    }
  );
},

// PUT /users/{id}
updateUser: function(userId, userData, callback, error_callback) {
  // SAMO relativna ruta!
  const relativePath = `users/${userId}`;

  RestClient.put(relativePath, userData,
    function(response) {
      console.log("✅ User updatedss:", response, "path:",relativePath);
      toastr.success("Podaci su uspješno spremljeni.");
      if (callback) callback(response);
    },
    function(err) {
      console.error("❌ Greška pri spremanju korisnika:", err);
      const msg = err.responseJSON?.message || "Došlo je do greške.";
      toastr.error(msg);
      if (error_callback) error_callback(err);
    }
  );
},




  getDuration: function (start_time, end_time) {
    const start = new Date(start_time);
    const end = new Date(end_time);
    const diff = (end - start) / (1000 * 60); // Duration in minutes
    return `${Math.round(diff)} minutes`;
  }
};
