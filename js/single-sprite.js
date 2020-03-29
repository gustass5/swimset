const form = document.getElementById("delete-sprite-form");

form.addEventListener("submit", event => {
  if (!confirm("Are you sure that you want to delete this sprite?")) {
    event.preventDefault();
  }
});
