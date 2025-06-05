let userRole = localStorage.getItem("userRole");

if (userRole) {
  console.log("User role is:", userRole);
}

class SPA {
  constructor() {
    this.routes = {
      "login": "Frontend/HTML_templates_views/login.html",
      "register": "Frontend/HTML_templates_views/registration.html",
      "user-home": "Frontend/HTML_templates_views/HomePage.html",
      "user-personal":
        "Frontend/HTML_templates_views/HomeScreenMojiPodaci.html",
      "user-contact": "Frontend/HTML_templates_views/HomeScreenContact.html",
      "admin-home": "Frontend/HTML_templates_views/AdminHomePanel.html", // Your parking_zone.html
      "admin-messages": "Frontend/HTML_templates_views/AdminMessagePanel.html",
      "admin-reservations":
        "Frontend/HTML_templates_views/AdminReservations.html",
    };

    this.publicRoutes = ['login', 'register'];
    this.protectedRoutes = {
      admin: ['admin-home', 'admin-messages', 'admin-reservations'],
      user: ['user-home', 'user-personal', 'user-contact']
    };

    window.addEventListener("popstate", this.handleRoute.bind(this));
  }

  getNavbar(route) {
    const userName = localStorage.getItem("userName") || "Guest";
    
    if (route.startsWith("admin-")) {
      return `
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#1D3C6E;">
          <div class="container-fluid px-3">
            <a class="navbar-brand" href="#">Parkiraj.me</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#" onclick="spa.navigate('admin-home'); return false;">Admin Panel</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="spa.navigate('admin-messages'); return false;">Messages Panel</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="spa.navigate('admin-reservations'); return false;">All reservations</a></li>
              </ul>
            </div>
            <div class="d-flex align-items-center">
              <span id="user-role" class="text-white me-3">Welcome, Admin ${userName}</span>
              <button class="btn btn-outline-light" onclick="logout()">Logout</button>
            </div>
          </div>
        </nav>`;
    } else {
      return `
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#1D3C6E;">
          <div class="container-fluid px-3">
            <a class="navbar-brand" href="#">Parkiraj.me</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#" onclick="spa.navigate('user-home'); return false;">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="spa.navigate('user-personal'); return false;">Personal</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="spa.navigate('user-contact'); return false;">Contact</a></li>
              </ul>
            </div>
            <div class="d-flex align-items-center">
              <span id="user-role" class="text-white me-3">Welcome, ${userName}</span>
              <button class="btn btn-outline-light" onclick="logout()">Logout</button>
            </div>
          </div>
        </nav>`;
    }
  }

  async loadContent(route) {
    // Check if route is public
    if (!this.publicRoutes.includes(route)) {
      const userRole = localStorage.getItem('userRole');
      
      // If no role, redirect to login
      if (!userRole) {
        toastr.warning("Molimo prijavite se prvo");
        this.navigate('login');
        return;
      }

      // Check admin routes
      if (this.protectedRoutes.admin.includes(route) && userRole !== Constants.ADMIN_ROLE) {
        toastr.error("Pristup zabranjen. Samo administratori mogu pristupiti ovoj stranici.");
        this.navigate(userRole === Constants.USER_ROLE ? 'user-home' : 'login');
        return;
      }

      // Check user routes
      if (this.protectedRoutes.user.includes(route) && userRole !== Constants.USER_ROLE) {
        toastr.error("Pristup zabranjen. Ova stranica je samo za korisnike.");
        this.navigate(userRole === Constants.ADMIN_ROLE ? 'admin-home' : 'login');
        return;
      }
    }

    try {
      const response = await fetch(this.routes[route]);
      if (!response.ok) throw new Error("Failed to load content");

      const content = await response.text();
      const isAuthPage = route === "login" || route === "register";

      document.body.classList.toggle("login", isAuthPage);
      document.body.classList.toggle("dashboard", !isAuthPage);

      if (isAuthPage) {
        document.querySelector("#app").innerHTML = content;
        document.body.style.backgroundImage = 'url("Frontend/static_assets/Logo.png")';
        document.body.style.height = "100vh";
        document.body.style.display = "flex";
      } else {
        const wrapper = `
          <div class="dashboard-layout">
            ${this.getNavbar(route)}
            <div class="content-area">
              ${content}
            </div>
            <footer class="footer mt-auto py-1" style="background-color: #1D3C6E; color: white;">
              <div class="container text-center">
                <span>© 2025 Parkiraj.me - All rights reserved</span>
              </div>
            </footer>
          </div>`;
        document.querySelector("#app").innerHTML = wrapper;

        document.body.style.backgroundColor = "#f0f2f5";
        document.body.style.backgroundImage = "none";
        document.body.style.height = "auto";
        document.body.style.display = "block";
      }

      this.attachEventHandlers(route);
    } catch (error) {
      console.error("Error loading content:", error);
      if (error.status === 401 || error.status === 403) {
        window.location.href = '#login';
      }
    }
  }

  attachEventHandlers(route) {
    if(route === "admin-home") {
        console.log("Loading admin dashboard stats...");
        RestClient.loadDashboardStats();
        RestClient.loadAllUsers();
        RestClient.getZoneInformations();
    }
  

  if (route === "user-home") {
  const userId = localStorage.getItem("userId"); // ✅ izvučeno iz localStorage
  RestClient.getSingleUser(userId);
  console.log("Loading user data for user-home...",userId);
  RestClient.getReservationsByUserId(userId);
}
if(route === "user-personal") {
  const userId = localStorage.getItem("userId"); // ✅ izvučeno iz localStorage

  console.log("Loading user data for user-personal...", userId);
  RestClient.getSingleUserEditable(userId);
 
}

    if (route === "admin-home") {
      console.log("Loading zone capacity...");
            RestClient.loadReservations();

      console.log("Loading admin home...");
      // Chart initialization
      const labels = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
      ];
      const data = {
        labels: labels,
        datasets: [
          {
            label: "Concurrent Parked Cars",
            data: [65, 59, 80, 81, 56, 55, 40],
            fill: false,
            borderColor: "rgb(75, 192, 192)",
            backgroundColor: "rgb(75, 192, 192)",
            tension: 0.1,
          },
        ],
      };
      const config = {
        type: "line",
        data: data,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: { display: true },
          scales: {
            yAxes: [
              {
                ticks: { beginAtZero: true },
              },
            ],
          },
        },
      };
      RestClient.getZoneInformations();
      console.log("------->Loading reservations information for admin home...");
          RestClient.loadReservations();
     
      const canvas = document.getElementById("myChart");
      if (canvas) {
        if (window.myChart instanceof Chart) {
          window.myChart.destroy(); // Destroy existing chart if any
        }
        window.myChart = new Chart(canvas, config);
        console.log("Chart initialized for admin-home");
      } else {
        console.error("Canvas #myChart not found!");
      }

      // Delete row function
      window.deleteRow = function (button) {
        button.closest("tr").remove();
      };
    }else{
      console.log("No specific event handlers for this route.");
    }

    if(route === "admin-messages") {
      console.log("Loading messages for admin...");
    RestClient.getAllMessages()
        .catch(error => {
            if (error.status === 403) {
                alert("You don't have permission to access this feature");
                spa.navigate('user-home'); // Redirect to user home page
            }
        });
    }
      // Load messages when admin-messages route is accessed
    if (route === "admin-reservations") {
      // Load reservations when admin-reservations route is accessed
      console.log("Loading reservations for admin...");
      RestClient.loadReservations();
      
      // You can add additional event handlers specific to admin reservations here
      // For example, handling cancel or details buttons
      document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-danger')) {
          // Handle cancel button click
          const reservationId = e.target.dataset.id; // You'd need to add this data attribute
          if (confirm('Are you sure you want to cancel this reservation?')) {
            RestClient.delete(`parkingreservations/${reservationId}`, {}, 
              function() {
                // Success callback - refresh the list
                RestClient.loadReservations();
              },
              function(err) {
                console.error('Error cancelling reservation:', err);
                alert('Failed to cancel reservation');
              }
            );
          }
        }
        
        if (e.target && e.target.classList.contains('btn-primary')) {
          // Handle details button click
          const reservationId = e.target.dataset.id;
          // Implement details view logic here
        }
      });
    }
  
  }

  navigate(route) {
    if (!this.routes[route]) {
      console.warn(`Route "${route}" not found, redirecting to login.`);
      route = "login";
    }
    window.history.pushState({}, "", `#${route}`);
    this.loadContent(route);
  }

  handleRoute() {
    const hash = window.location.hash.slice(1) || "login";
    this.loadContent(hash);
  }
}

const spa = new SPA();

document.addEventListener("DOMContentLoaded", () => {
  spa.handleRoute();
});


function logout() {
  localStorage.removeItem("userRole");
  localStorage.removeItem("userName"); // Add this line to clear username on logout
  spa.navigate("login");
}

