const menu = document.getElementById("menu");

/**
 * Toggles navigation menu on small screens
 */
const toggleMenu = () => {
  if (menu.classList.contains("hidden")) {
    menu.classList.remove("hidden");
  } else {
    menu.classList.add("hidden");
  }
};
