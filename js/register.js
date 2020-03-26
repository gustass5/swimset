const usernameInput = document.getElementById("username");
const currentPasswordInput = document.getElementById("current-password");
const confirmPasswordInput = document.getElementById("confirm-password");
const usernameError = document.getElementById("username-error");
const currentPasswordError = document.getElementById("current-password-error");
const confirmPasswordError = document.getElementById("confirm-password-error");
const form = document.getElementById("form");

usernameInput.addEventListener("input", () => {
  if (!checkUsernameValidation()) {
    usernameError.innerHTML = "Please enter username!";
  }
});

const checkUsernameValidation = () => {
  if (usernameInput.value === "" || usernameInput.value === undefined) {
    return false;
  }

  const trimmedUsername = usernameInput.value.trim();
  if (/\s/.test(trimmedUsername)) {
    return false;
  }
  return true;
};
