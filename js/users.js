const form = document.getElementById("users-form");

form.addEventListener("submit", event => {
  if (!confirm("Are you sure that you want to delete this user?")) {
    event.preventDefault();
  }
});
