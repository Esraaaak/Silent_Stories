document.addEventListener("DOMContentLoaded", () => {

  function showAlert(message) {
    const cleanMessage = message.split('|')[0].trim();
    alert(cleanMessage);
  }

  const passwordRegex = /^[A-Z][a-zA-Z\d/*\-?!@#\.]{7,}$/;
  const messageBox = document.getElementById("Message");

  // PASSWORD LIVE CHECK (SIGNUP)
  const passwordInput = document.getElementById("signupPassword");
  if (passwordInput && messageBox) {
    passwordInput.addEventListener("input", () => {
      if (!passwordRegex.test(passwordInput.value)) {
        messageBox.textContent =
          "Password must start with an uppercase letter, be at least 8 characters, and contain a symbol.";
        messageBox.className = "error";
      } else {
        messageBox.textContent = "Strong password ✔";
        messageBox.className = "success";
      }
    });
  }

  // LOGIN
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      fetch("loginPHP.php", {
        method: "POST",
        body: new FormData(loginForm)
      })
        .then(res => res.text())
        .then(response => {
          if (response.startsWith("Login successful")) {
           
            window.location.replace('ArtExhibitions.php');
          } else {
            showAlert(response);
          }
        })
        .catch(() => showAlert("Something went wrong."));
    });
  }

  // SIGNUP
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const password = document.getElementById("signupPassword").value.trim();
      if (!passwordRegex.test(password)) {
        showAlert("Please enter a valid password.");
        return;
      }

      fetch("signupPHP.php", {
        method: "POST",
        body: new FormData(signupForm)
      })
        .then(res => res.text())
        .then(response => {
          if (response.startsWith("Signup successful")) {
       
            window.location.replace('ArtExhibitions.php');
          } else {
            showAlert(response);
          }
        })
        .catch(() => showAlert("Something went wrong."));
    });
  }

  // CONTACT
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();

      fetch("contactPHP.php", {
        method: "POST",
        body: new FormData(contactForm)
      })
        .then(res => res.text())
        .then(response => {
          showAlert(response);
          if (response.startsWith("Message sent")) {
            window.location.href = "Silent storiest home.html";
          }
        })
        .catch(() => showAlert("Something went wrong."));
    });
  }

});
