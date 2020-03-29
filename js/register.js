const usernameInput = document.getElementById("username");
const currentPasswordInput = document.getElementById("current-password");
const confirmPasswordInput = document.getElementById("confirm-password");
const usernameError = document.getElementById("username-error");
const currentPasswordError = document.getElementById("current-password-error");
const confirmPasswordError = document.getElementById("confirm-password-error");
const form = document.getElementById("form");

/**
 * Checks if value is valid after every input, displays error message
 */
usernameInput.addEventListener("input", () => {
  usernameError.innerHTML = checkUsernameValidation(usernameInput.value.trim());

  if (usernameError.innerHTML !== "") {
    usernameError.classList.remove("hidden");
  } else {
    usernameError.classList.add("hidden");
  }
});

const checkUsernameValidation = username => {
  if (/\s/.test(username)) {
    return "Username must not have spaces";
  }

  if (username.length > 25) {
    return "Username is too long";
  }

  if (username === undefined || username === null || username === "") {
    return "Please enter username";
  }

  return "";
};

/**
 * Checks if username exists after user is done typing it
 */
usernameInput.addEventListener("focusout", async () => {
  if (checkUsernameValidation(usernameInput.value) !== "") {
    return;
  }

  const usernameAvailable = await checkIfUsernameExists(
    usernameInput.value.trim()
  );
  if (!usernameAvailable.exist) {
    usernameError.classList.contains("hidden") &&
      usernameError.classList.add("hidden");
  } else {
    usernameError.classList.remove("hidden");
    usernameError.innerHTML = "Username already exist";
  }
});

const checkIfUsernameExists = async username => {
  const url = "../api/usernameMatch.php";

  let formData = new FormData();
  formData.append("compareUsername", username);

  const response = await fetch(url, {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    redirect: "follow",
    referrerPolicy: "no-referrer",
    body: formData
  });

  return response.json();
};

/**
 * Checks if value is valid after every input, displays error message
 */
currentPasswordInput.addEventListener("input", () => {
  currentPasswordError.innerHTML = checkCurrentPasswordValidation(
    currentPasswordInput.value.trim()
  );

  if (currentPasswordError.innerHTML !== "") {
    currentPasswordError.classList.remove("hidden");
  } else {
    currentPasswordError.classList.add("hidden");
  }
});

confirmPasswordInput.addEventListener("input", () => {
  confirmPasswordError.innerHTML = checkConfirmPasswordValidation(
    confirmPasswordInput.value.trim()
  );

  if (confirmPasswordError.innerHTML !== "") {
    confirmPasswordError.classList.remove("hidden");
  } else {
    confirmPasswordError.classList.add("hidden");
  }
});

const checkCurrentPasswordValidation = password => {
  if (password.length < 8) {
    return "Password must have at least 8 characters";
  }

  if (/[^A-Za-z0-9]/.test(password) === true) {
    return "Password can only contain letters and numbers";
  }
  if (!/\d/.test(password)) {
    return "Password must contain at least 1 number";
  }

  if (password === undefined || password === null || password === "") {
    return "Please enter password";
  }

  if (
    checkUsernameValidation(usernameInput.value.trim()) === "" &&
    password.includes(usernameInput.value.trim())
  ) {
    return "Password must not contain username";
  }
  return "";
};

/**
 * Checks if value is valid after every input, displays error message
 */
const checkConfirmPasswordValidation = password => {
  if (password !== currentPasswordInput.value.trim()) {
    return "Passwords do not match";
  }

  if (password === undefined || password === null || password === "") {
    return "Please confirm password";
  }

  return "";
};

/**
 * Final all input check before form submission
 */
form.addEventListener("submit", event => {
  usernameError.innerHTML = checkUsernameValidation(usernameInput.value.trim());
  currentPasswordError.innerHTML = checkCurrentPasswordValidation(
    currentPasswordInput.value.trim()
  );
  confirmPasswordError.innerHTML = checkConfirmPasswordValidation(
    confirmPasswordInput.value.trim()
  );

  if (
    usernameError.innerHTML !== "" ||
    currentPasswordError.innerHTML !== "" ||
    confirmPasswordError.innerHTML !== ""
  ) {
    event.preventDefault();
    alert("Registration was unsuccessful");
  }
});
