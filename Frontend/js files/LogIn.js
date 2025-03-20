function handleLogin(event) {
  event.preventDefault();

  let role = document.getElementById("role").value;
  let username = document.getElementById("username").value;
  let password = document.getElementById("password").value;
  localStorage.setItem("userRole", role);
  if (role === "admin" && username === "admin" && password === "1234") {
    spa.navigate("admin-home");
  } else if (
    role === "korisnik" &&
    username === "user" &&
    password === "0000"
  ) {
    spa.navigate("user-home");
  } else {
    alert("Pogrešno korisničko ime ili lozinka");
  }
}

function logout() {
  spa.navigate("login");
  localStorage.clear();
  console.log("User logged out, localStorage cleared.");

  alert("Odjavili ste se!");
}

function handleRegistration(event) {
  event.preventDefault();

  const password = document.getElementById("reg-password").value;
  const confirmPassword = document.getElementById("reg-confirm-password").value;

  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    return;
  }

  // Here you would typically send this to your backend
  const userData = {
    firstName: document.getElementById("reg-firstname").value,
    lastName: document.getElementById("reg-lastname").value,
    email: document.getElementById("reg-email").value,
    username: document.getElementById("reg-username").value,
    password: password,
  };

  // For demo purposes, just show success and redirect to login
  alert("Registration successful! Please login.");
  spa.navigate("login");
}
