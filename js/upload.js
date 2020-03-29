const form = document.getElementById("upload-form");
const nameInput = document.getElementById("sprite-name");
const spriteError = document.getElementById("sprite-error");
const spriteFile = document.getElementById("sprite-upload");

form.addEventListener("submit", event => {
  spriteError.innerHTML = "";
  spriteError.classList.remove("hidden");

  if (nameInput.value === "" || nameInput.value === undefined) {
    spriteError.innerHTML = "Please enter sprite/spritesheet name";
    event.preventDefault();
    return;
  }

  if (spriteFile.value === "") {
    spriteError.innerHTML = "Please select a file to upload";
    event.preventDefault();
    return;
  }

  spriteError.classList.add("hidden");
});
