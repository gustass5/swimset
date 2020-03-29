const menu = document.getElementById("menu");

const toggleMenu = () => {
  if (menu.classList.contains("hidden")) {
    menu.classList.remove("hidden");
  } else {
    menu.classList.add("hidden");
  }
};
