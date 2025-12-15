document.addEventListener("DOMContentLoaded", () => {

  function showAlert(message) {
    alert(message);
  }

  /* =========================
     LOGIN
     ========================= */
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const email = document.getElementById("loginEmail").value.trim();
      const password = document.getElementById("loginPassword").value.trim();

      if (!email || !password) {
        showAlert("Please enter your email and password.");
        return;
      }

      fetch("loginPHP.php", {
        method: "POST",
        body: new FormData(loginForm)
      })
        .then(res => res.text())
        .then(response => {

          if (response.startsWith("Login successful")) {
            const name = response.split("|")[1] || "";
            showAlert("Welcome back, " + name + " 🎉");
            window.location.href = "SubmitArtworkPage.html";
          } else {
            showAlert(response);
          }

        })
        .catch(() => {
          showAlert("Something went wrong. Please try again.");
        });
    });
  }

  /* =========================
     SIGNUP
     ========================= */
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const name = document.getElementById("signupName").value.trim();
      const email = document.getElementById("signupEmail").value.trim();
      const password = document.getElementById("signupPassword").value.trim();
      const phone = document.getElementById("signupPhone").value.trim();

      if (!name || !email || !password || !phone) {
        showAlert("All fields are required.");
        return;
      }

      fetch("signupPHP.php", {
        method: "POST",
        body: new FormData(signupForm)
      })
        .then(res => res.text())
        .then(response => {

          if (response.startsWith("Signup successful")) {
            showAlert("Account created successfully. Welcome, " + name + " 🎉");
            window.location.href = "SubmitArtworkPage.html";
          } else {
            showAlert(response);
          }

        })
        .catch(() => {
          showAlert("Something went wrong. Please try again.");
        });
    });
  }

  /* =========================
     CONTACT
     ========================= */
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const name = document.getElementById("contactName").value.trim();
      const email = document.getElementById("contactEmail").value.trim();
      const message = document.getElementById("contactMessage").value.trim();

      if (!name || !email || !message) {
        showAlert("All fields are required.");
        return;
      }

      fetch("contactPHP.php", {
        method: "POST",
        body: new FormData(contactForm)
      })
        .then(res => res.text())
        .then(response => {

          if (response.startsWith("Message sent")) {
            showAlert(response);
            window.location.href = "Silent storiest home.html";
          } else {
            showAlert(response);
          }

        })
        .catch(() => {
          showAlert("Something went wrong. Please try again.");
        });
    });
  }

});

