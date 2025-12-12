
document.addEventListener("DOMContentLoaded", () => {

  // ---- Login Validation ----
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function(e) {
      const email = document.getElementById("loginEmail").value.trim();
      const password = document.getElementById("loginPassword").value.trim();
      if (!email || !password) return alert("Please enter your email and password.");
      if (!email.includes("@") || !email.includes(".")) return alert("Invalid email format.");
    });
  }

  // ---- Signup Validation ----
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", function(e) {
      const name = document.getElementById("signupName").value.trim();
      const email = document.getElementById("signupEmail").value.trim();
      const password = document.getElementById("signupPassword").value.trim();
      const phone = document.getElementById("signupPhone").value.trim();

      if (!name) return alert("Name cannot be empty.");
      if (!email.includes("@") || !email.includes(".")) return alert("Invalid email format.");
      if (!/^(?=.*[A-Z])(?=.*\d).{8,}$/.test(password)) 
        return alert("Password must have at least 8 chars, 1 uppercase, 1 number.");
      if (!phone.startsWith("05") || phone.length !== 10) 
        return alert("Phone must start with 05 and be 10 digits.");
    });
  }

  // ---- Contact Validation ----
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function(e) {
      const name = document.getElementById("contactName").value.trim();
      const email = document.getElementById("contactEmail").value.trim();
      const msg = document.getElementById("contactMessage").value.trim();

      if (!name || !email || !msg) return alert("All fields are required.");
      if (msg.length > 300) return alert("Message cannot exceed 300 characters.");
    });
  }

});

